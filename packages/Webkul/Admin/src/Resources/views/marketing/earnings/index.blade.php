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

            <div class="page-action">
                @if (bouncer()->hasPermission('marketing.sitemaps.create'))
                    <a href="{{ route('admin.sitemaps.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.marketing.sitemaps.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.marketings.earnings.index') }}"></datagrid-plus>
        </div>
    </div>
@endsection
