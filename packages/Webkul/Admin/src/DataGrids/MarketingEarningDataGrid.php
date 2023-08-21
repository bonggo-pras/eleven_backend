<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class MarketingEarningDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $index = 'id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customers')
            ->select([
                'marketing_reseller.id as id',
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name) as full_name'),
                'marketing_reseller.marketing_id',
                'marketing_reseller.customer_id',
                DB::raw('COUNT(DISTINCT orders.customer_id = 1) as total_reseller'),
                DB::raw('SUM(orders.total_qty_ordered) as total_qty'),
                DB::raw('SUM(orders.base_grand_total) as grand_total'),
                'orders.created_at as order_date',
            ])
            ->whereNotNull('customers.referral_code')
            ->join('marketing_reseller', 'customers.id', 'marketing_reseller.marketing_id')
            ->join('orders', 'marketing_reseller.customer_id', 'orders.customer_id')
            ->groupBy('marketing_reseller.marketing_id');

        $this->addFilter('order_date', 'orders.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => 'Nama Lengkap',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'total_reseller',
            'label'      => 'Total Reseller',
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'order_date',
            'label'      => 'Order Data',
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'total_qty',
            'label'      => 'Total Barang Terjual',
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => 'Total Transaksi',
            'type'       => 'price',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.sales.orders.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
