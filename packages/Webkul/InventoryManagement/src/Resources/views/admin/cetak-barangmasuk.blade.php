<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* table td,
        table th {
            border: 1px solid black;

        } */

        table {
            border-collapse: collapse;
            /* or separate */
        }

        /* Optional: Style table borders */
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    @php
    $no = 0;
    @endphp
    <table width="100%">
        <thead>
            <tr style=" height: 65px;">
                <th>No</th>
                <th>Kategori Barang</th>
                <th>Barang</th>
                <th>Stok</th>
                <th>Nama DO </th>
                <th>Tanggal Keluar</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($datas as $row)
            <tr>
                <td style="text-align:center;">{{++$no}}</td>
                <td>{{$row->nama_kategori}}</td>
                <td>{{$row->nama_product}}</td>
                <td style="text-align:center;">{{$row->jumlah_stok}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->end}}</td>
            </tr>
            @endforeach


        </tbody>
    </table>
</body>

</html>