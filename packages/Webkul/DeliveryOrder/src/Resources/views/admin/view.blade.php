@extends('admin::layouts.master')
@section('page_title')
Surat Jalan {{ $deliveryOrder->name }}
@stop

@section('content-wrapper')
<div class="content full-page">
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.deliveryorder.index') }}'"></i> Surat Jalan Pada Toko #{{ $deliveryOrder->store_name }}
            </h1>
        </div>
        
        <div class="page-action">
            <a href="{{ route('admin.deliveryorder.print', $deliveryOrder->id) }}" class="btn btn-lg btn-primary">
                Print Surat Jalan
            </a>
        </div>
    </div>

    <div class="page-content">
        <tabs>
            {!! view_render_event('admin.deliveryOrder.tabs.before', ['deliveryOrder' => $deliveryOrder]) !!}

            <tab name="Surat Jalan Info" :selected="true">
                <div class="sale-container">

                    <accordian title="Detail Surat Jalan" :active="true">
                        <div slot="body">
                            <div class="sale">
                                <div class="sale-section">
                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                Nama
                                            </span>

                                            <span class="value">
                                                {{ $deliveryOrder->name }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                Nama Toko
                                            </span>

                                            <span class="value">
                                                {{ $deliveryOrder->store_name }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                Keterangan
                                            </span>

                                            <span class="value">
                                                {{ $deliveryOrder->keterangan }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                Status
                                            </span>

                                            <span class="value">
                                                {{ $deliveryOrder->status }}
                                            </span>
                                        </div>

                                        {!! view_render_event('admin.deliveryOrder.channel_name.after', ['order' => $deliveryOrder]) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </accordian>

                    <accordian title="Tabel List Barang Keluar" :active="true">
                        <div slot="body">
                            <div class="table">
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Price</th>
                                                <th>Stok Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($deliveryOrder->items as $index => $item)
                                            @if ($item->product)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>{{ core()->currency($item->productFlat->price) }}</td>
                                                    <td>{{ $item->stock }}</td>
                                                </tr>
                                            @elseif (!$item->product)
                                                <tr>
                                                    <td class="empty" colspan="7">Produk telah dihapus atau tidak ditemukan</td>
                                                <tr>
                                            @endif
                                            @endforeach

                                            @if (!$deliveryOrder->items->count())
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

            {!! view_render_event('admin.deliveryOrder.tabs.after', ['deliveryOrder' => $deliveryOrder]) !!}
        </tabs>
    </div>
</div>
@stop