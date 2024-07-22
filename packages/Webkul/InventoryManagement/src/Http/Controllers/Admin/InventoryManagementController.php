<?php

namespace Webkul\InventoryManagement\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\Admin\DataGrids\InventoryManagementDataGrid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\InventoryManagement\Models\InventoryManagement;
use Webkul\InventoryManagement\Models\InventoryManagementItem;
use Webkul\Product\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Traits\PDFHandler;
use Webkul\Product\Models\ProductInventory;
use Yajra\DataTables\Facades\DataTables;

class InventoryManagementController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, PDFHandler;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(InventoryManagementDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function indexCustom()
    {
        $datas = DB::table('inventory_management_items as imi')
            ->join('inventory_management as im', 'im.id', '=', 'imi.inventory_management_id')
            ->join('product_categories as pc', 'pc.product_id', '=', 'imi.product_id')
            ->join('category_translations as ct', 'ct.category_id', '=', 'pc.category_id')
            ->join('products as p', 'p.id', '=', 'pc.product_id')
            ->select(
                'im.id',
                'imi.id as inventory_management_items_id',
                'p.sku as nama_product',
                DB::raw('SUM(imi.stock) as jumlah_stok'),
                'ct.name as nama_kategori',
                'im.name',
                'im.end'
            )
            ->groupBy('ct.category_id', 'im.id', 'p.id', 'im.name', 'im.end')
            ->orderBy('im.created_at', 'desc')
            ->get();

        return view($this->_config['view'], ['datas' => $datas]);
    }

    public function indexJson(Request $request)
    {
        $datas = InventoryManagementItem::select([
            'inv_management.id',
            'inv_management.name',
            'inv_management.end',
            'inventory_management_items.stock as jumlah_stok',
            'products.id as product_id',
            'product_flat.name as nama_product',
            'category_translations.name as nama_kategori',
            DB::raw('CASE WHEN product_flat.name IS NULL THEN "Produk ini sudah dihapus" ELSE product_flat.name END as nama_product'),
            DB::raw('CASE WHEN product_flat.name IS NULL THEN "Produk ini sudah dihapus" ELSE category_translations.name END as nama_kategori'),
            'latest_product_category.category_id'
        ])
        ->join('inventory_management as inv_management', 'inv_management.id', '=', 'inventory_management_items.inventory_management_id')
        ->join('products', 'products.id', '=', 'inventory_management_items.product_id')
        ->join('product_flat', 'product_flat.product_id', '=', 'products.id')
        ->leftJoin(DB::raw('(
            SELECT 
                pc1.product_id, 
                COALESCE(products.parent_id, pc1.category_id) AS category_id
            FROM product_categories pc1
            JOIN products ON products.id = pc1.product_id
            WHERE pc1.id = (
                SELECT pc2.id
                FROM product_categories pc2
                WHERE pc2.product_id = pc1.product_id
                ORDER BY pc2.id DESC
                LIMIT 1
            )
        ) as latest_product_category'), function($join) {
            $join->on('products.id', '=', 'latest_product_category.product_id')
                 ->orOn('products.parent_id', '=', 'latest_product_category.product_id');
        })
        ->leftJoin('category_translations', 'category_translations.category_id', '=', 'latest_product_category.category_id')
        ->orderBy('inv_management.created_at', 'desc');

        if ($request->name != '' || $request->name != null) {
            $datas = $datas->where('product_flat.name', 'like', "%{$request->name}%");
        }

        if ($request->name_inventory_management != '' || $request->name_inventory_management != null) {
            $datas = $datas->where('inv_management.name', 'like', "%{$request->name_inventory_management}%");
        }

        if ($request->kategori_barang != '' || $request->kategori_barang != null) {
            $datas = $datas->where('latest_product_category.category_id', '=', "{$request->kategori_barang}");
        }

        if ($request->tgl_awal != '' || $request->tgl_awal != null) {
            $datas = $datas->whereDate('inv_management.end', '>=', "{$request->tgl_awal}");
        }

        if ($request->tgl_akhir != '' || $request->tgl_akhir != null) {
            $datas = $datas->whereDate('inv_management.end', '<=', "{$request->tgl_akhir}");
        }

        $datas = $datas->get();

        return DataTables::of($datas)->addIndexColumn()->addColumn('action', function ($row) {
            $button =  '<div class="action">';
            $button .= "<a href='/admin/inventorymanagement/edit/$row->id'><span
                 class='icon pencil-lg-icon'></span></a>
                 <a href='/admin/inventorymanagement/view/$row->id) }}'><span
                 class='icon eye-icon'></span></a>
                 <a href='javascript::void(0)' class='item-del' data-id='$row->id'><span
                 class='icon trash-icon'></span></a>
                ";
            $button .= '</div>';
            return $button;
        })->make();
    }

    public function cetakDo(Request $request)
    {
        $datas = InventoryManagementItem::select([
            'inv_management.id',
            'inv_management.name',
            'inv_management.end',
            'inventory_management_items.stock as jumlah_stok',
            'products.id as product_id',
            'product_flat.name as nama_product',
            'category_translations.name as nama_kategori',
            DB::raw('CASE WHEN product_flat.name IS NULL THEN "produk ini telah dihapus" ELSE product_flat.name END as nama_product'),
            DB::raw('CASE WHEN product_flat.name IS NULL THEN "Produk ini sudah dihapus" ELSE category_translations.name END as nama_kategori'),
            'latest_product_category.category_id'
        ])
        ->join('inventory_management as inv_management', 'inv_management.id', '=', 'inventory_management_items.inventory_management_id')
        ->join('products', 'products.id', '=', 'inventory_management_items.product_id')
        ->join('product_flat', 'product_flat.product_id', '=', 'products.id')
        ->leftJoin(DB::raw('(
            SELECT 
                pc1.product_id, 
                COALESCE(products.parent_id, pc1.category_id) AS category_id
            FROM product_categories pc1
            JOIN products ON products.id = pc1.product_id
            WHERE pc1.id = (
                SELECT pc2.id
                FROM product_categories pc2
                WHERE pc2.product_id = pc1.product_id
                ORDER BY pc2.id DESC
                LIMIT 1
            )
        ) as latest_product_category'), function($join) {
            $join->on('products.id', '=', 'latest_product_category.product_id')
                 ->orOn('products.parent_id', '=', 'latest_product_category.product_id');
        })
        ->leftJoin('category_translations', 'category_translations.category_id', '=', 'latest_product_category.category_id')
        ->orderBy('inv_management.created_at', 'desc');

        if ($request->print_name != '' || $request->print_name != null) {
            $datas = $datas->where('product_flat.name', 'like', "%{$request->print_name}%");
        }

        if ($request->print_name_inventory_management != '' || $request->print_name_inventory_management != null) {
            $datas = $datas->where('inv_management.name', 'like', "%{$request->print_name_inventory_management}%");
        }

        if ($request->print_kategori_barang != '' || $request->print_kategori_barang != null) {
            $datas = $datas->where('latest_product_category.category_id', '=', "{$request->print_kategori_barang}");
        }

        if ($request->print_tgl_awal != '' || $request->print_tgl_awal != null) {
            $datas = $datas->whereDate('inv_management.end', '>=', "{$request->print_tgl_awal}");
        }

        if ($request->print_tgl_akhir != '' || $request->print_tgl_akhir != null) {
            $datas = $datas->whereDate('inv_management.end', '<=', "{$request->print_tgl_akhir}");
        }

        $datas = $datas->get();

        $pdf = Pdf::loadView($this->_config['view'], ['datas' => $datas])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventoryManagementItems = [];

        $inventoryManagement = InventoryManagement::create([
            'name' => $request->name,
            'keterangan' => $request->keterangan,
            'status' => 'complete',
            'end' => $request->end,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        foreach ($request->productIds as $key => $productId) {
            $reqQty = intval($request->stocks[$key]);
            $inventory = ProductInventory::where('product_id', $productId)->first();

            if ($inventory) {
                $arrayItem = [
                    'inventory_management_id' => $inventoryManagement->id,
                    'product_id' => $productId,
                    'stock' => $reqQty,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                if (($reqQty = $inventory->qty + $reqQty) < 0) {
                    $reqQty = 0;
                }

                array_push($inventoryManagementItems, $arrayItem);

                $inventory->update(['qty' => $reqQty]);
            }
        }

        InventoryManagementItem::insert($inventoryManagementItems);

        session()->flash('success', 'Berhasil memperbaharui stok');
        return redirect()->route('admin.inventorymanagement.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $inventoryManagement = InventoryManagement::with(['items', 'items.productFlat'])->where('id', $id)->first();

        return view($this->_config['view'], compact('inventoryManagement'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function print($id)
    {
        $inventoryManagement = InventoryManagement::with(['items', 'items.productFlat'])->where('id', $id)->first();

        return $this->downloadPDF(
            view($this->_config['view'], compact('inventoryManagement'))->render(),
            'inventory-management-' . $inventoryManagement->created_at->format('d-m-Y')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $inventoryManagement = InventoryManagement::with(['items', 'items.product', 'items.product.inventories', 'items.productFlat'])->where('id', $id)->first();

        return view($this->_config['view'], compact('inventoryManagement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $inventoryManagementItems = [];

        $inventoryManagement = InventoryManagement::find($id);
        $inventoryManagement->name = $request->name;
        $inventoryManagement->keterangan = $request->keterangan;
        $inventoryManagement->status = 'complete';
        $inventoryManagement->end = $request->end;
        $inventoryManagement->updated_at = Carbon::now();
        $inventoryManagement->save();

        if ($request->productIds != null) {
            foreach ($request->productIds as $key => $productId) {
                $reqQty = $request->stocks[$key];
                $inventory = ProductInventory::where('product_id', $productId)->first();
                $item = $inventoryManagement->items->where('product_id', $productId)->first();
                $productName = $item->productFlat->name ?? '';

                if ($inventory) {
                    if ($item) {
                        if ($reqQty < $item->stock) {
                            $reqQty = $reqQty - $item->stock;
                        }

                        if (($reqQty = $inventory->qty - $reqQty) < 0) {
                            $massage = 'Ada perbedaan selisih pada barang: #' . $productName;
                            session()->flash('error', $massage);

                            return redirect()->back();
                        }
                    }
                }
            }
        }

        foreach ($inventoryManagement->items as $key => $item) {
            $productId = $item->product_id;
            $inventory = ProductInventory::where('product_id', $productId)->first();

            if ($inventory) {
                $qty = $item->stock;

                if (($qty = $inventory->qty - $qty) < 0) {
                    $qty = 0;
                }

                $inventory->update(['qty' => $qty]);
            }
        }

        // Mereset semua data yang berkaitan
        InventoryManagementItem::where('inventory_management_id', $inventoryManagement->id)->delete();

        if ($request->productIds != null) {
            foreach ($request->productIds as $key => $productId) {
                $inventory = ProductInventory::where('product_id', $productId)->first();
                $reqQty = $request->stocks[$key];

                if ($inventory) {
                    $arrayItem = [
                        'inventory_management_id' => $inventoryManagement->id,
                        'product_id' => $productId,
                        'stock' => $reqQty,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];

                    if (($reqQty = $inventory->qty + $reqQty) < 0) {
                        $reqQty = 0;
                    }

                    array_push($inventoryManagementItems, $arrayItem);

                    $inventory->update(['qty' => $reqQty]);
                }
            }
        }

        InventoryManagementItem::insert($inventoryManagementItems);

        session()->flash('success', 'Berhasil memperbaharui stok');

        return redirect()->route('admin.inventorymanagement.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $inventoryManagement = InventoryManagement::find($id);
            $inventoryManagement->items()->delete();

            $inventoryManagement->delete();
            session()->flash('success', 'Berhasil menghapus stok');

            return redirect()->route($this->_config['redirect']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
