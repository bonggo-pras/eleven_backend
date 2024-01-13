@extends('admin::layouts.master')
@section('page_title')
Barang Masuk {{ $inventoryManagement->name }}
@stop

@section('content-wrapper')
<div class="content full-page">
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.inventorymanagement.index') }}'"></i> Barang Masuk #{{ $inventoryManagement->id }}
            </h1>
        </div>
        
        <div class="page-action">
            <a href="{{ route('admin.inventorymanagement.print', $inventoryManagement->id) }}" class="btn btn-lg btn-primary">
                Print Barang Masuk
            </a>
        </div>
    </div>

    <div class="page-content">
        <tabs>
            {!! view_render_event('admin.inventoryManagement.tabs.before', ['inventoryManagement' => $inventoryManagement]) !!}

            <tab name="Barang Masuk Info" :selected="true">
                <div class="sale-container">

                    <accordian title="Detail Barang Masuk" :active="true">
                        <div slot="body">
                            <div class="sale">
                                <div class="sale-section">
                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                Nama
                                            </span>

                                            <span class="value">
                                                {{ $inventoryManagement->name }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                Keterangan
                                            </span>

                                            <span class="value">
                                                {{ $inventoryManagement->keterangan }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                Status
                                            </span>

                                            <span class="value">
                                                {{ $inventoryManagement->status }}
                                            </span>
                                        </div>

                                        {!! view_render_event('admin.inventoryManagement.channel_name.after', ['order' => $inventoryManagement]) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </accordian>

                    <accordian title="Tabel List Barang Masuk" :active="true">
                        <div slot="body">
                            <div class="table">
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Price</th>
                                                <th>Stok Masuk</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($inventoryManagement->items as $index => $item)
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

                                            @if (!$inventoryManagement->items->count())
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

            {!! view_render_event('admin.inventoryManagement.tabs.after', ['inventoryManagement' => $inventoryManagement]) !!}
        </tabs>
    </div>
</div>
@stop