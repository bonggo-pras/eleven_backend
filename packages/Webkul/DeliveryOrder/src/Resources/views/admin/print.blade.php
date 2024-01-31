<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
    {{-- meta tags --}}
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    {{-- lang supports inclusion --}}
    <style type="text/css">
       @font-face {
            font-family: 'Hind';
            src: url({{ asset('vendor/webkul/ui/assets/fonts/Hind/Hind-Regular.ttf') }}) format('truetype');
        }

        @font-face {
            font-family: 'Noto Sans';
            src: url({{ asset('vendor/webkul/ui/assets/fonts/Noto/NotoSans-Regular.ttf') }}) format('truetype');
        }
    </style>

    @php
    /* main font will be set on locale based */
    $mainFontFamily = app()->getLocale() === 'ar' ? 'DejaVu Sans' : 'Noto Sans';
    @endphp

    {{-- main css --}}
    <style type="text/css">
        * {
            font-family: '{{ $mainFontFamily }}';
        }

        body,
        th,
        td,
        h5 {
            font-size: 12px;
            color: #000;
        }

        .container {
            padding: 20px;
            display: block;
        }

        .invoice-summary {
            margin-bottom: 20px;
        }

        .table {
            margin-top: 20px;
        }

        .table table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table thead th {
            font-weight: 700;
            border-top: solid 1px #d3d3d3;
            border-bottom: solid 1px #d3d3d3;
            border-left: solid 1px #d3d3d3;
            background: #F4F4F4;
        }

        .table thead th:last-child {
            border-right: solid 1px #d3d3d3;
        }

        .table tbody td {
            border-bottom: solid 1px #d3d3d3;
            border-left: solid 1px #d3d3d3;
            color: #3A3A3A;
            vertical-align: middle;
        }

        .table tbody td p {
            margin: 0;
        }

        .table tbody td:last-child {
            border-right: solid 1px #d3d3d3;
        }

        .sale-summary {
            margin-top: 40px;
            float: right;
        }

        .sale-summary tr td {
            padding: 3px 5px;
        }

        .sale-summary tr.bold {
            font-weight: 600;
        }

        .label {
            color: #000;
            font-weight: bold;
        }

        .logo {
            height: 70px;
            width: 70px;
        }

        .merchant-details {
            margin-bottom: 5px;
        }

        .merchant-details-title {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .logo {
            margin-left: 300px;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .column1 {
            width: 1em;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }

        .column2 {
            word-wrap: break-word;
            max-width: 30em;
            overflow: hidden;
            text-overflow: ellipsis;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }

        .column3 {
            padding: 0px 20px;
            width: 6em;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }

        .column4 {
            width: 3em;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }

        .row {
            display: flex;
        }
    </style>
</head>

<body style="background-image: none; background-color: #fff;">
    <div class="container">
        <div class="header">
            <h1 class="text-center">Surat Jalan</h1>
        </div>

        <div class="invoice-summary">
            <div class="row">
               <div style="width: 20%;">
                    <span class="label">Surat Jalan ID -</span>
                    <span class="value">#{{ $deliveryOrder->id }}</span>
               </div>
               <div style="width: 20%;">
                    <span class="label">Keterangan -</span>
                    <span class="value">#{{ $deliveryOrder->keterangan }}</span>
               </div>
            </div>

            <div class="row">
                <div style="width: 20%;">
                    <span class="label">Nama surat jalan -</span>
                    <span class="value">#{{ $deliveryOrder->name }}</span>
                </div>

                <div style="width: 20%;">
                    <span class="label">Status -</span>
                    <span class="value">#{{ $deliveryOrder->status }}</span>
               </div>
            </div>

            <div class="row">
                <div style="width: 20%;">
                    <span class="label">Nama toko -</span>
                    <span class="value">#{{ $deliveryOrder->store_name }}</span>
                </div>

                <div style="width: 20%;">
                    <span class="label">{{ __('admin::app.sales.invoices.date') }} -</span>
                    <span class="value">{{ core()->formatDate($deliveryOrder->created_at, 'd-m-Y') }}</span>
                </div>
               </div>
            </div>

            <div class="table items">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="column1">No.</th>
                            <th class="column2">Nama Produk</th>
                            <th class="column3">Price</th>
                            <th class="column4">Stok Keluar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($deliveryOrder->items as $index => $item)
                        @if ($item->product)
                        <tr>
                            <td class="column1">{{ $index + 1 }}</td>
                            <td class="column2">{{ $item->product->name }}</td>
                            <td class="column3">{{ core()->currency($item->productFlat->price) }}</td>
                            <td class="column4">{{ $item->stock }}</td>
                        </tr>
                        @elseif (!$item->product)
                            <tr>
                                <td class="empty" colspan="4">Produk telah dihapus atau tidak ditemukan</td>
                            <tr>
                        @endif
                        @endforeach
                        @if (!$deliveryOrder->items->count())
                        <tr>
                            <td class="empty" colspan="4">{{ __('admin::app.common.no-result-found') }}</td>
                        <tr>
                            @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>