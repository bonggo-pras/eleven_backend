<?php

namespace Webkul\DeliveryOrder\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Webkul\Admin\DataGrids\DeliveryOrderDataGrid;
use Webkul\DeliveryOrder\Models\DeliveryOrderItem;
use Webkul\DeliveryOrder\Models\DeliveryOrder;
use Webkul\Product\Models\Product;
use Webkul\Core\Traits\PDFHandler;
use Webkul\Product\Models\ProductInventory;
use Webkul\Sales\Repositories\ShipmentItemRepository;
// use DataTables;
use Yajra\DataTables\Facades\DataTables;

class DeliveryOrderController extends Controller
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
            return app(DeliveryOrderDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function indexCustom()
    {
        $datas = DB::table('delivery_order_items as doi')
            ->join('delivery_orders as do', 'do.id', '=', 'doi.delivery_order_id')
            ->join('product_categories as pc', 'pc.product_id', '=', 'doi.product_id')
            ->join('category_translations as ct', 'ct.category_id', '=', 'pc.category_id')
            ->join('products as p', 'p.id', '=', 'pc.product_id')
            ->select(
                'do.id',
                'do.store_name',
                'doi.id as delivery_order_items_id',
                'p.sku as nama_product',
                DB::raw('SUM(doi.stock) as jumlah_stok'),
                'ct.name as nama_kategori',
                'do.name',
                'do.end'
            )
            ->groupBy('ct.category_id', 'do.id', 'p.id', 'do.name', 'do.end')
            ->get();

        return view($this->_config['view'], ['datas' => $datas]);
    }
    public function indexJson(Request $request)
    {
        $datas = DB::table('delivery_order_items as doi')
            ->join('delivery_orders as do', 'do.id', '=', 'doi.delivery_order_id')
            ->join('product_categories as pc', 'pc.product_id', '=', 'doi.product_id')
            ->join('category_translations as ct', 'ct.category_id', '=', 'pc.category_id')
            ->join('products as p', 'p.id', '=', 'pc.product_id')
            ->select(
                'do.id',
                'do.store_name',
                'doi.id as delivery_order_items_id',
                'p.sku as nama_product',
                DB::raw('SUM(doi.stock) as jumlah_stok'),
                'ct.name as nama_kategori',
                'do.name',
                'do.end'
            );


        if ($request->name != '') {
            $datas = $datas->where('p.sku', 'like', "%{$request->name}%");
        }
        if ($request->name_do != '') {
            $datas = $datas->where('do.name', 'like', "%{$request->name_do}%");
        }
        if ($request->store_name_barang != '') {
            $datas = $datas->where('do.store_name', 'like', "%{$request->store_name_barang}%");
        }

        if ($request->kategori_barang != '') {
            $datas = $datas->where('ct.category_id', '=', "{$request->kategori_barang}");
        }

        if ($request->tgl_awal != '') {
            $datas = $datas->whereDate('do.end', '>=', "{$request->tgl_awal}");
        }

        if ($request->tgl_akhir != '') {
            $datas = $datas->whereDate('do.end', '<=', "{$request->tgl_akhir}");
        }
        $datas = $datas->groupBy('ct.category_id', 'do.id', 'p.id', 'do.name', 'do.end')->get();

        return DataTables::of($datas)->addIndexColumn()->addColumn('action', function ($row) {
            $button =  '<div class="action">';
            $button .= "<a href='/admin/deliveryorder/edit/$row->id'><span
                 class='icon pencil-lg-icon'></span></a>
                 <a href='/admin/deliveryorder/view/$row->id) }}'><span
                 class='icon eye-icon'></span></a>
                 <a href='javascript::void(0)' class='item-del' data-id='$row->id'><span
                 class='icon trash-icon'></span></a>
                ";
            $button .= '</div>';
            return $button;
        })->make();
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
        $deliveryItems = [];

        $deliveryOrder = DeliveryOrder::create([
            'name' => $request->name,
            'store_name' => $request->store_name,
            'keterangan' => $request->keterangan,
            'status' => 'complete',
            'end' => $request->end,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($request->productIds != null) {
            foreach ($request->productIds as $key => $productId) {
                $reqQty = intval($request->stocks[$key]);
                $inventory = ProductInventory::where('product_id', $productId)->first();

                if ($inventory) {
                    if ($reqQty > $inventory->qty) {
                        $massage = 'Stok barang kurang dari stok keluar: #';
                        session()->flash('error', $massage);

                        return redirect()->back();
                    }

                    if ($inventory->qty >= $reqQty) {
                        $arrayItem = [
                            'delivery_order_id' => $deliveryOrder->id,
                            'product_id' => $productId,
                            'stock' => $reqQty,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];

                        if (($reqQty = $inventory->qty - $reqQty) < 0) {
                            $reqQty = 0;
                        }

                        $deliveryOrderItem = DeliveryOrderItem::create($arrayItem);

                        if ($deliveryOrderItem) {
                            $inventory->update(['qty' => $reqQty]);
                        }
                    }
                }
            }

            // DeliveryOrderItem::insert($deliveryItems);
        }

        session()->flash('success', 'Berhasil menambahkan surat jalan');

        return redirect()->route('admin.deliveryorder.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $deliveryOrder = DeliveryOrder::with(['items', 'items.productFlat'])->where('id', $id)->first();

        return view($this->_config['view'], compact('deliveryOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function cetakDo($id)
    {
        // $deliveryOrder = DeliveryOrder::with(['items', 'items.productFlat'])->where('id', $id)->first();

        // return $this->downloadPDF(
        //     view($this->_config['view'], compact('deliveryOrder'))->render(),
        //     'delivery-order-' . $deliveryOrder->created_at->format('d-m-Y')
        // );
        return view($this->_config['view']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $deliveryOrder = DeliveryOrder::with(['items', 'items.product', 'items.product.inventories', 'items.productFlat'])->where('id', $id)->first();

        return view($this->_config['view'], compact('deliveryOrder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $deliveryItems = [];

        $deliveryOrder = DeliveryOrder::find($id);
        $deliveryOrder->name = $request->name;
        $deliveryOrder->store_name = $request->store_name;
        $deliveryOrder->keterangan = $request->keterangan;
        $deliveryOrder->status = 'complete';
        $deliveryOrder->end = $request->end;
        $deliveryOrder->updated_at = Carbon::now();
        $deliveryOrder->save();

        if ($request->productIds != null) {
            foreach ($request->productIds as $key => $productId) {
                $reqQty = intval($request->stocks[$key]);
                $inventory = ProductInventory::where('product_id', $productId)->first();
                $item = $deliveryOrder->items->where('product_id', $productId)->first();
                $productName = $item->productFlat->name ?? '';

                if ($inventory) {
                    if ($reqQty > $inventory->qty) {
                        $massage = 'Stok barang kurang dari stok keluar: #' . $productName;
                        session()->flash('error', $massage);

                        return redirect()->back();
                    }

                    if ($item) {
                        $inventoryAwal = $inventory->qty + $item->stock;

                        if ($reqQty > $inventoryAwal) {
                            $massage = 'Ada perbedaan selisih pada barang: #' . $productName;
                            session()->flash('error', $massage);

                            return redirect()->back();
                        }
                    }
                }
            }
        }

        foreach ($deliveryOrder->items as $key => $item) {
            $productId = $item->product_id;

            $inventory = ProductInventory::where('product_id', $productId)->first();

            if ($inventory) {
                $qty = $item->stock;

                if (($qty = $inventory->qty + $qty) < 0) {
                    $qty = 0;
                }

                $inventory->update(['qty' => $qty]);
            }
        }

        // Mereset semua data yang berkaitan
        DeliveryOrderItem::where('delivery_order_id', $deliveryOrder->id)->delete();

        if ($request->productIds != null) {
            foreach ($request->productIds as $key => $productId) {
                $inventory = ProductInventory::where('product_id', $productId)->first();

                if ($inventory) {
                    $qty = intval($request->stocks[$key]);

                    $arrayItem = [
                        'delivery_order_id' => $deliveryOrder->id,
                        'product_id' => $productId,
                        'stock' => $qty,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];

                    if (($qty = $inventory->qty - $qty) < 0) {
                        $qty = 0;
                    }

                    array_push($deliveryItems, $arrayItem);

                    $inventory->update(['qty' => $qty]);
                }
            }
        }

        DeliveryOrderItem::insert($deliveryItems);

        session()->flash('success', 'Berhasil menambahkan surat jalan');

        return redirect()->route('admin.deliveryorder.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deliveryOrder = DeliveryOrder::find($id);
        $deliveryOrder->items()->delete();


        if ($deliveryOrder->delete()) {
            return response()->json(['err' => false]);
        } else {
            return response()->json(['err' => true]);
        }
    }
}
