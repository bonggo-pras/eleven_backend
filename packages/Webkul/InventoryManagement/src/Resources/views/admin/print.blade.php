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
            padding: 5px 10px;
            background: #F4F4F4;
        }

        .table thead th:last-child {
            border-right: solid 1px #d3d3d3;
        }

        .table tbody td {
            padding: 5px 10px;
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
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .column1 {
            width: 1em;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }

        .column3 {
            width: 6em;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }

        .column4 {
            width: 3em;
            /* Sesuaikan lebar kolom sesuai kebutuhan */
        }
    </style>
</head>

<body style="background-image: none; background-color: #fff;">
    <div class="container">
        <div class="header">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Inventory Management</h1>
                </div>
            </div>
        </div>

        <div class="invoice-summary">
            <div class="row">
                <span class="label">Inventory Management ID -</span>
                <span class="value">#{{ $inventoryManagement->id }}</span>
            </div>

            <div class="row">
                <span class="label">Nama Inventory Management -</span>
                <span class="value">#{{ $inventoryManagement->name }}</span>
            </div>

            <div class="row">
                <span class="label">Keterangan -</span>
                <span class="value">#{{ $inventoryManagement->keterangan }}</span>
            </div>

            <div class="row">
                <span class="label">Status -</span>
                <span class="value">#{{ $inventoryManagement->status }}</span>
            </div>

            <div class="row">
                <span class="label">{{ __('admin::app.sales.invoices.date') }} -</span>
                <span class="value">{{ core()->formatDate($inventoryManagement->created_at, 'd-m-Y') }}</span>
            </div>

            <div class="table items">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="column1">No.</th>
                            <th>Nama Produk</th>
                            <th class="column3">Price</th>
                            <th class="column4">Stok Masuk</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($inventoryManagement->items as $index => $item)
                        <tr>
                            <td class="column1">{{ $index + 1 }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td class="column3">{{ core()->currency($item->productFlat->price) }}</td>
                            <td class="column4">{{ $item->stock }}</td>
                        </tr>
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
</body>

</html>