<?php

namespace Webkul\Marketing\Http\Controllers;

use Webkul\Admin\DataGrids\MarketingEarningDataGrid;

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
}