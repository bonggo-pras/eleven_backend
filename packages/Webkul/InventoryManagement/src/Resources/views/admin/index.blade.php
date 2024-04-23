@extends('admin::layouts.content')

@section('page_title')
Inventory Management
@stop

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Inventory Management</h1>
        </div>

        <div class="page-action">
            <a href="{{ route('admin.inventorymanagement.create') }}" class="btn btn-lg btn-primary">
                Tambah Inventory Management
            </a>
        </div>
    </div>

    <div class="page-content">
        <datagrid-plus src="{{ route('admin.inventorymanagement.index') }}"></datagrid-plus>
    </div>
</div>
@stop