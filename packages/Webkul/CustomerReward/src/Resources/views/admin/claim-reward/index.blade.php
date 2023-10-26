@extends('admin::layouts.content')

@section('page_title')
Customer Claim Rewards
@stop

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Customer Claim Rewards</h1>
        </div>
    </div>

    <div class="page-content">
        <datagrid-plus src="{{ route('admin.claim-reward.index') }}"></datagrid-plus>
    </div>
</div>
@stop