@extends('admin::layouts.content')

@section('page_title')
    Rewards
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Rewards</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('customers.groups.create'))
                    <a href="{{ route('admin.rewards.create') }}" class="btn btn-lg btn-primary">
                        Add Reward
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.rewards.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
