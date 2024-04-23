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
        $queryBuilder = DB::table('marketing_reseller')
            ->select([
                'marketing_reseller.id as id',
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'cust_marketing.first_name, " ", ' . DB::getTablePrefix() . 'cust_marketing.last_name) as full_name'),
                'marketing_reseller.marketing_id',
                'marketing_reseller.customer_id',
                DB::raw('SUM(orders.total_qty_ordered) as total_qty'),
                DB::raw('SUM(orders.base_grand_total) as grand_total'),
                'orders.created_at as order_date',
            ])
            ->join('customers as cust_marketing', 'marketing_reseller.marketing_id', 'cust_marketing.id')
            ->join('customers', 'marketing_reseller.customer_id', 'customers.id')
            ->join('orders', 'customers.id', 'orders.customer_id')
            ->groupBy('marketing_reseller.marketing_id')
            ->orderBy('marketing_reseller.id', 'ASC');

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
            'route'  => 'admin.marketings.earnings.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
