<?php

namespace Webkul\CustomerReward\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\CustomerReward\DataGrids\CustomerClaimRewardDataGrid;
use Webkul\CustomerReward\DataGrids\RewardDataGrid;
use Webkul\CustomerReward\Models\PointHistory;
use Webkul\CustomerReward\Models\Reward;

class CustomerClaimRewardController extends Controller
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
            return app(CustomerClaimRewardDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $point_history = PointHistory::select([
            'point_histories.id as id',
            'customers.first_name',
            'customers.last_name',
            'customers.email',
            'customers.gender',
            'rewards.name as reward_name',
            DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name) as full_name'),
            'point_histories.status',
            'point_histories.created_at as claim_at',
            'point_histories.status',
            'point_histories.shipment_at as shipment_at',
            'point_histories.finish_shipment as finish_shipment',
        ])
        ->join('customers', 'point_histories.customer_id', 'customers.id')
        ->leftJoin('rewards', 'point_histories.reward_id', 'rewards.id')
        ->where([
            'point_histories.type' => 'out',
            'point_histories.id' => $id
        ])->first();

        return view($this->_config['view'], [
            'point_histories' => $point_history
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveShipment($id)
    {
        $claim = PointHistory::find($id);
        $claim->status = 'on-shipment';
        $claim->shipment_at = request()->shipment_at;
        $claim->save();

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function finishReward($id)
    {
        $claim = PointHistory::find($id);
        $claim->status = 'completed';
        $claim->finish_shipment = Carbon::now();
        $claim->save();

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reward = Reward::where('id', $id)->first();

        if ($reward->status == 'active') {
            return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Customer']) . ' Reward status active'], 400);
        } else {
            $delete = $reward->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => $reward->name])]);
        }

        return redirect()->route($this->_config['redirect']);
    }
}
