@extends('admin::layouts.content')

@section('page_title')
    Pendapatan Marketing
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Pendapatan Marketing</h1>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.marketings.earnings.index') }}"></datagrid-plus>
        </div>
    </div>
@endsection
