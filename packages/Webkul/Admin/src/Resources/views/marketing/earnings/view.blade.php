@extends('admin::layouts.master')
@section('page_title')
Marketing #{{ $marketing->first_name ?? "Tidak Ditemukan" }} {{ $marketing->last_name ?? "" }}
@stop

@section('content-wrapper')
<div class="content full-page">
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.marketings.earnings.index') }}'"></i> Marketing #{{ $marketing->first_name ?? "Tidak Ditemukan" }} {{ $marketing->last_name ?? "" }}
            </h1>
        </div>
    </div>

    <div class="page-content">
        <tabs>
            {!! view_render_event('sales.order.tabs.before', ['orders' => $orders]) !!}

            <tab name="{{ __('admin::app.sales.orders.info') }}" :selected="true">
                <div class="sale-container">

                    <accordian title="Marketing Detail" :active="true">
                        <div slot="body">
                            <div class="sale">
                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>Informasi Marketing </span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.first_name') }}
                                            </span>

                                            <span class="value">
                                                {{ $marketing->first_name ?? "Tidak Ditemukan / Sudah dihapus" }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.created_at.after', ['order' => $marketing]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.last_name') }}
                                            </span>

                                            <span class="value">
                                                {{ $marketing->last_name ?? "Tidak Ditemukan / Sudah dihapus" }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.status_label.after', ['order' => $marketing]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.email') }}
                                            </span>

                                            <span class="value">
                                                {{ $marketing->email ?? "Tidak Ditemukan / Sudah dihapus" }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.gender') }}
                                            </span>

                                            <span class="value">
                                                {{ $marketing->gender ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.channel_name.after', ['order' => $marketing]) !!}
                                    </div>
                                </div>

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <p></p>
                                    </div>

                                    <div class="section-content">

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.status') }}
                                            </span>

                                            <span class="value">
                                                @if ($marketing == null)
                                                Tidak Ditemukan / Sudah dihapus
                                                @else
                                                {{ $marketing->status ? "Aktif" : "Tidak Aktif" }}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.date_of_birth') }}
                                            </span>

                                            <span class="value">
                                                {{ $marketing->date_of_birth ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.phone') }}
                                            </span>

                                            <span class="value">
                                                {{ $marketing->phone ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                Referral Code
                                            </span>

                                            <span class="value">
                                                {{ $marketing->referral_code ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.customer_group.after', ['order' => $marketing]) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </accordian>

                    <accordian title="Sales" :active="true">
                        <div slot="body">
                            <div class="section-content">
                                <div class="table">
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Nama Lengkap</th>
                                                    <th>{{ __('admin::app.customers.customers.email') }}</th>
                                                    <th>{{ __('admin::app.customers.customers.customer_group') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($sales as $sale)
                                                <tr>
                                                    <td>{{ $sale->first_name }} {{ $sale->last_name }}</td>
                                                    <td>{{ $sale->email }}</td>
                                                    <td>{{ $sale->name }}</td>
                                                </tr>
                                                @endforeach

                                                @if (!$sales->count())
                                                <tr>
                                                    <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                                <tr>
                                                    @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </accordian>

                    <accordian title="Data History Order" :active="true">
                        <div slot="body">
                            <div class="table">
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <th>Order Date</th>
                                                <th>Total Item Order</th>
                                                <th>Total Point</th>
                                                <th>Point Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->full_name }}</td>
                                                <td>{{ $order->created_at }}</td>
                                                <td>{{ $order->total_item_count }}</td>
                                                <td>{{ $order->amount ?? 0 }}</td>
                                                <td>{{ $order->status ?? 'pending'}}</td>
                                            </tr>
                                            @endforeach

                                            @if (!$orders->count())
                                            <tr>
                                                <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                            <tr>
                                                @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </accordian>
                </div>
            </tab>

            {!! view_render_event('sales.order.tabs.after', ['orders' => $orders]) !!}
        </tabs>
    </div>
</div>
@stop