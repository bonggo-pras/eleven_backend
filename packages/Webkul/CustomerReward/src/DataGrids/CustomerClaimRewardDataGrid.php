<?php

namespace Webkul\CustomerReward\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class CustomerClaimRewardDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('point_histories')
            ->select([
                'point_histories.id as id',
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name) as full_name'),
                'point_histories.status',
                'point_histories.created_at as claim_at',
                'point_histories.status',
                'point_histories.shipment_at as shipment_at',
                'point_histories.finish_shipment as finish_shipment'
            ])
            ->join('customers', 'point_histories.customer_id', 'customers.id')
            ->leftJoin('rewards', 'point_histories.reward_id', 'rewards.id')->where('point_histories.type', 'out');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => 'Status',
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'claim_at',
            'label'      => 'Claim At',
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'shipment_at',
            'label'      => 'Shipment At',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                if ($row->shipment_at == null) {
                    return 'Belum Dirikim';
                } else {
                    return $row->shipment_at;
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'finish_shipment',
            'label'      => 'Finish Shipment',
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($row) {
                if ($row->finish_shipment == null) {
                    return 'Belum Dirikim';
                } else {
                    return $row->shipment_at;
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => 'Show',
            'method' => 'GET',
            'route'  => 'admin.claim-reward.show',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
