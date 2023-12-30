<?php

namespace Webkul\InventoryManagement\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\Admin\DataGrids\InventoryManagementDataGrid;
use Illuminate\Http\Request;
use Webkul\InventoryManagement\Models\InventoryManagement;
use Webkul\InventoryManagement\Models\InventoryManagementItem;
use Webkul\Product\Models\Product;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Traits\PDFHandler;

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
        try {
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
                $inventory = Product::find($productId)->inventories()
                    ->where('vendor_id', 0)
                    ->first();

                if ($inventory) {
                    $qty = $request->stocks[$key];

                    $arrayItem = [
                        'inventory_management_id' => $inventoryManagement->id,
                        'product_id' => $productId,
                        'stock' => $qty,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];

                    if (($qty = $inventory->qty + $qty) < 0) {
                        $qty = 0;
                    }

                    array_push($inventoryManagementItems, $arrayItem);

                    $inventory->update(['qty' => $qty]);
                }
            }

            InventoryManagementItem::insert($inventoryManagementItems);

            session()->flash('success', 'Berhasil memperbaharui stok');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

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
        $inventoryManagement = InventoryManagement::with(['items', 'items.productFlat'])->where('id', $id)->first();

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
                $qty = $request->stocks[$key];
                $inventory = Product::find($productId)->inventories()
                    ->where('vendor_id', 0)
                    ->first();

                if ($qty > $inventory->qty) {
                    $massage = 'Ada barang yang tidak bisa diedit stoknya: #' . $qty;
                    session()->flash('error', $massage);

                    return redirect()->back();
                }

                if ($inventory) {
                    $item = $inventoryManagement->items->where('product_id', $productId)->first();

                    if ($item) {
                        if ($qty < $item->stock) {
                            $qty = $qty - $item->stock;
                        }

                        if (($qty = $inventory->qty - $qty) < 0) {
                            $massage = 'Ada barang yang tidak bisa diedit stoknya: #' . $qty;
                            session()->flash('error', $massage);

                            return redirect()->back();
                        }
                    }
                }
            }
        }

        foreach ($inventoryManagement->items as $key => $item) {
            $productId = $item->product_id;

            $inventory = Product::find($productId)->inventories()
                ->where('vendor_id', 0)
                ->first();

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
                $inventory = Product::find($productId)->inventories()
                    ->where('vendor_id', 0)
                    ->first();

                if ($inventory) {
                    $qty = $request->stocks[$key];

                    $arrayItem = [
                        'inventory_management_id' => $inventoryManagement->id,
                        'product_id' => $productId,
                        'stock' => $qty,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];

                    if (($qty = $inventory->qty + $qty) < 0) {
                        $qty = 0;
                    }

                    array_push($inventoryManagementItems, $arrayItem);

                    $inventory->update(['qty' => $qty]);
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
