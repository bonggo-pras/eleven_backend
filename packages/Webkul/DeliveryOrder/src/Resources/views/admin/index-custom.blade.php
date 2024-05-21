@extends('admin::layouts.content')

@section('page_title')
Delivery Orders
@stop

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
{{--
<link href="//cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css"> --}}
<link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css">


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

        <form action="{{route('admin.deliveryorder.cetak-do')}}" method="post" target="_blank">
            @csrf
            <input type="hidden" id="print_name" name="print_name" value="">
            <input type="hidden" id="print_name_do" name="print_name_do" value="">
            <input type="hidden" id="print_store_name_barang" name="print_store_name_barang" value="">
            <input type="hidden" id="print_kategori_barang" name="print_kategori_barang" value="">
            <input type="hidden" id="print_tgl_awal" name="print_tgl_awal" value="">
            <input type="hidden" id="print_tgl_akhir" name="print_tgl_akhir" value="">

            <button id="btn-filter" type="button" class="btn btn-sm"
                style="background-color:#ffc107; margin-right: 20px;">Filter</button> <button type="submit"
                id="btn-print" class="btn btn-sm btn-primary" style=" margin-right: 20px;">Print</button>

        </form>
        <table class="table-data-delivery-order" id="table-data-delivery-order">
            <thead>
                <tr style=" height: 65px;">
                    <!---->
                    <th>No</th>
                    <th>Id DO</th>
                    <th>Kategori Barang</th>
                    <th>Barang</th>
                    <th>Stok</th>
                    <th>Nama DO </th>
                    <th>Store Nama Barang</th>
                    <th>Tanggal Keluar</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

{{-- Awal Modal --}}
<div id="deliveryFilterModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deliveryFilterForm" name="deliveryFilterForm">
                    @csrf
                    <table width="100%">
                        <tr>
                            <td> <label for="name" style="font-weight: bold;">Nama Barang</label></td>
                            <td>:</td>
                            <td><input id="name" style="width:100%;" name="name"></td>
                        </tr>
                        <tr>
                            <td> <label for="name_do" style="font-weight: bold;">Nama DO</label></td>
                            <td>:</td>
                            <td><input id="name_do" style="width:100%;" name="name_do"></td>
                        </tr>
                        <tr>
                            <td> <label for="store_name_barang" style="font-weight: bold;">Store Nama Barang</label>
                            </td>
                            <td>:</td>
                            <td><input id="store_name_barang" name="store_name_barang" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td> <label for="kategori_barang" style="font-weight: bold;">Kategori
                                    Barang</label></td>
                            <td>:</td>
                            <td>
                                @php
                                $arr_kategori = \DB::table('category_translations')->get();
                                // var_dump($arr_kategori);
                                @endphp
                                <select id="kategori_barang" name="kategori_barang" style="width:100%;"
                                    class=" form-control">
                                    <option value="">.:Pilih Kategori:.</option>
                                    @for ($i = 0; $i < count($arr_kategori); $i++) <option
                                        value="{{ $arr_kategori[$i]->category_id }}">
                                        {{ $arr_kategori[$i]->name }}
                                        </option>
                                        @endfor
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td> <label for="tgl_awal" style="font-weight: bold;">Tanggal Awal</label></td>
                            <td>:</td>
                            <td> <input class="control" name="tgl_awal" id="tgl_awal" type="date" style="width:100%;">
                            </td>
                        </tr>
                        <tr>
                            <td> <label for="tgl_akhir" style="font-weight: bold;">Tanggal Akhir</label></td>
                            <td>:</td>
                            <td> <input class="control" name="tgl_akhir" id="tgl_akhir" type="date" style="width:100%;">
                            </td>
                        </tr>
                    </table>
                    <br>
                    {{-- <a href="javascript:void(0)" class="btn btn-md btn-primary btn-filter"
                        id="btn-filter">Filter</a> --}}
                    <button class="btn btn-md btn-primary" id="btn-filter-modal">Filter</button>

                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

{{-- Akhir Modal --}}
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
{{-- <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script> --}}
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script></script>
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
        //  $.ajax({
        //     url: "{{route('admin.deliveryorder.indexJson')}}",
        //     method: "POST",
        //     success: function(ev) {
        //          console.log(ev)
        //     }
        // });
        // $('#table-data-delivery-order').DataTable({
        //     "ajax": {
        //         "url": "{{route('admin.deliveryorder.indexJson')}}",
        //         "type": "GET"
        //     }
        // });

        var table = $('#table-data-delivery-order').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('admin.deliveryorder.indexJson')}}",
                type: "POST",
            },
            columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data:'id', name: 'id'},
                    {data: 'nama_kategori', name: 'nama_kategori'},
                    {data: 'nama_product', name: 'nama_product'},
                    {data: 'jumlah_stok', name: 'jumlah_stok'},
                    {data: 'name', name: 'name'},
                    {data: 'store_name', name: 'store_name'},
                    {data: 'end', name: 'end'},
                    {data:'action',name:'action'}
                ],
            // dom: 'Bfrtip',
            // buttons: [{
            //     extend: 'print',
            //     oSelectorOpts: {
            //         page: 'all'
            //     },
            // }]
        });

        // $('<button id="btn-filter" class="btn btn-sm" style="background-color:#ffc107; margin-right: 20px;">Filter</button>').insertBefore('.buttons-print');
        // $('<br><br>').insertAfter('.buttons-print');
        // $('.buttons-print').addClass('btn btn-sm btn-primary');

        $('#btn-filter').click(function(){
            $('#deliveryFilterModal').modal('show');
        });

        $('#deliveryFilterForm').submit(function(e){
            e.preventDefault();
            let ini = $(this);
            $('#print_name').val($('#name').val());
            $('#print_name_do').val( $('#name_do').val())
            $('#print_store_name_barang').val($('#store_name_barang').val())
            $('#print_kategori_barang').val($('#kategori_barang').val())
            $('#print_tgl_awal').val($('#tgl_awal').val())
            $('#print_tgl_akhir').val($('#tgl_akhir').val())

            $('#table-data-delivery-order').DataTable().destroy();

            async function load(){
                await  $('#table-data-delivery-order').DataTable({
                    processing: true,
                    cache:false,
                    serverSide: true,
                    ajax: {
                        url: "{{route('admin.deliveryorder.indexJson')}}",
                        type: "POST",
                        data: {
                            'name': $('#name').val(),
                            'name_do': $('#name_do').val(),
                            'store_name_barang': $('#store_name_barang').val(),
                            'tgl_awal': $('#tgl_awal').val(),
                            'tgl_akhir': $('#tgl_akhir').val(),
                            'kategori_barang': $('#kategori_barang').val()
                        }
                    },
                    columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data:'id', name: 'id'},
                            {data: 'nama_kategori', name: 'nama_kategori'},
                            {data: 'nama_product', name: 'nama_product'},
                            {data: 'jumlah_stok', name: 'jumlah_stok'},
                            {data: 'name', name: 'name'},
                            {data: 'store_name', name: 'store_name'},
                            {data: 'end', name: 'end'},
                            {data:'action',name:'action'}
                    ],
                    // dom: 'Bfrtip',
                    // buttons: [{
                    //     extend: 'print',
                    //     oSelectorOpts: {
                    //         page: 'all'
                    //     },
                    // }]
                });
            }

            $('#btn-filter-modal').html('load...');

            load().then(function(){
                // $('<button id="btn-filter" class="btn btn-sm" style="background-color:#ffc107; margin-right: 20px;">Filter</button>').insertBefore('.buttons-print');
                // $('<br><br>').insertAfter('.buttons-print');
                // $('.buttons-print').addClass('btn btn-sm btn-primary');
                $('#btn-filter').click(function(){
                    $('#deliveryFilterModal').modal('show');
                });

                $('#btn-filter-modal').html('Filter');
                $('#deliveryFilterModal').modal('hide');
                Toast.fire({
                    icon: "success",
                    title: "Load Success"
                });
            }).catch();
        });

        // $('.tet').on('click', function(){
        //     // table.page.len(-1).draw();
        //     // window.open('', '_blank');
        //     var openPrint = window.open('/admin/deliveryorder/cetak-do','_blank');
        //     openPrint.onload = function() {
        //         var doc = openPrint.document;
        //         // let newDiv =  doc.document.createElement("div");
        //         // newDiv.className = "card-body";
        //         // doc.document.getElementsByClassName('contain').item(0).appendChild(newDiv);
        //         var newDiv = $(``);

        //         $(doc).find('body').append(newDiv);

        //     };
        // })
        $(document).on("click", ".item-del", function() {

            let ini = $(this);
            let id = $(this).data('id');

            let a = confirm('Do you really want to perform this action ?');
            if(a == true){

                $.ajax({
                    url: "{{route('admin.deliveryorder.delete','id')}}".replace('id', id),
                    method: "POST",
                    success: function(ev) {
                        if(!ev.err){


                            Toast.fire({
                            icon: "success",
                            title: "Data Berhasil dihapus"
                            });

                            ini.closest('tr').remove();
                        }else{
                            Toast.fire({
                            icon: "error",
                            title: "Data Gagal dihapus"
                            });
                        }
                    }
                });
            }

        });


    });
</script>
@endpush