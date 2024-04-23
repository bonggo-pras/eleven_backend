<?php

namespace Webkul\Marketing\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Webkul\Admin\DataGrids\MarketingEarningDataGrid;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\MarketingReseller;
use Webkul\Sales\Models\Order;

class MarketingEarningController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Marketing\Repositories\TemplateRepository  $templateRepository
     * @return void
     */
    public function __construct()
    {
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
            return app(MarketingEarningDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function view($id)
    {
        $marketing_resseler = MarketingReseller::where('id', $id)->first();

        if ($marketing_resseler) {
            $marketingId = $marketing_resseler->marketing_id;
            $marketing = Customer::find($marketingId);

            $orders = Order::select([
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name) as full_name'),
                'orders.created_at',
                'orders.total_item_count',
                'point_histories.amount',
                'point_histories.status'
            ])->join('marketing_reseller', 'orders.customer_id', 'marketing_reseller.customer_id')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->leftJoin('point_histories', 'orders.id', 'point_histories.id')
            ->where('marketing_reseller.marketing_id', $marketingId)
            ->get();

            $sales = MarketingReseller::join('customers', 'marketing_reseller.customer_id', 'customers.id')
                ->join('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                ->where('marketing_reseller.marketing_id', $marketingId)->get();

            return view($this->_config['view'], [
                'orders' => $orders,
                'sales' => $sales,
                'marketing' => $marketing
            ]);
        }
    }
}
