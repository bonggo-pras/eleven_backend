<?php

namespace Webkul\DeliveryOrder\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Webkul\Admin\DataGrids\DeliveryOrderDataGrid;
use Webkul\DeliveryOrder\Models\DeliveryOrderItem;
use Webkul\DeliveryOrder\Models\DeliveryOrder;
use Webkul\Product\Models\Product;
use Webkul\Sales\Repositories\ShipmentItemRepository;

class DeliveryOrderController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
            'status' => $request->status,
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

                if ($inventory->qty > $qty) {
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

        try {
        } catch (\Throwable $th) {
            //throw $th;
        }

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
    public function print($id)
    {
        $deliveryOrder = DeliveryOrder::with(['items', 'items.productFlat'])->where('id', $id)->first();

        return view($this->_config['view'], compact('deliveryOrder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
