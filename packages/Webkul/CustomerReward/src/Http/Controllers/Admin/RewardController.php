<?php

namespace Webkul\CustomerReward\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Webkul\CustomerReward\DataGrids\RewardDataGrid;
use Webkul\CustomerReward\Models\Reward;

class RewardController extends Controller
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
            return app(RewardDataGrid::class)->toJson();
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
        $validated = $request->validate([
            'name' => 'required',
            'point_required' => 'required|integer',
            'stock' => 'required|integer',
            'keterangan' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $request->merge([
            'status' => $request->status ?? 'non-active'
        ]);

        $reward = Reward::create($request->all());

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $reward = Reward::find($id);

        return view($this->_config['view'], [
            'reward' => $reward
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $validated = request()->validate([
            'name' => 'required',
            'point_required' => 'required|integer',
            'stock' => 'required|integer',
            'keterangan' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        request()->merge([
            'status' => request()->status ?? 'non-active'
        ]);

        $reward = Reward::where('id', $id)->update(request()->except('_token'));

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
