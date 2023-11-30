@extends('admin::layouts.content')

@section('page_title')
Delivery Orders
@stop

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Delivery Orders</h1>
        </div>
        <div class="page-action">
            <a href="{{ route('admin.deliveryorder.create') }}" class="btn btn-lg btn-primary">
                Tambah Surat Jalan Baru
            </a>
        </div>
    </div>

    <div class="page-content">
        <datagrid-plus src="{{ route('admin.deliveryorder.index') }}"></datagrid-plus>
    </div>
</div>
@endsection