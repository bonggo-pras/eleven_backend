@extends('admin::layouts.content')

@section('page_title')
Inventory Management
@stop

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
{{--
<link href="//cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css"> --}}
<link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"
    integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Inventory Management</h1>
        </div>
        <div class="page-action">
            <a href="{{ route('admin.inventorymanagement.create') }}" class="btn btn-lg btn-primary">
                Tambah Surat Jalan Baru
            </a>
        </div>
    </div>

    <div class="page-content">

        <form action="{{route('admin.inventorymanagement.cetak-inventory-management')}}" method="post" target="_blank">
            @csrf
            <input type="hidden" id="print_name" name="print_name" value="">
            <input type="hidden" id="print_name_inventory_management" name="print_name_inventory_management" value="">
            <input type="hidden" id="print_kategori_barang" name="print_kategori_barang" value="">
            <input type="hidden" id="print_tgl_awal" name="print_tgl_awal" value="">
            <input type="hidden" id="print_tgl_akhir" name="print_tgl_akhir" value="">

            <button id="btn-filter" type="button" class="btn btn-sm"
                style="background-color:#ffc107; margin-right: 20px;">Filter</button> <button type="submit"
                id="btn-print" class="btn btn-sm btn-primary" style=" margin-right: 20px;">Print</button>

        </form>
        <table class="table-data-inventory-management" id="table-data-inventory-management">
            <thead>
                <tr style=" height: 65px;">
                    <!---->
                    <th>No</th>
                    <th>Id </th>
                    <th>Kategori Barang</th>
                    <th>Barang</th>
                    <th>Stok</th>
                    <th>Nama  </th>
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
                            <td> <label for="name_inventory_management" style="font-weight: bold;">Nama </label></td>
                            <td>:</td>
                            <td><input id="name_inventory_management" style="width:100%;" name="name_inventory_management"></td>
                        </tr>
                        <tr>
                            <td> <label for="kategori_barang" style="font-weight: bold;">Kategori
                                    Barang</label></td>
                            <td>:</td>
                            <td>
                                @php
                                $arr_kategori = \DB::table('category_translations')->get();
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
                            <td> <input class="control tgl_awal" name="tgl_awal" id="tgl_awal" type="text"
                                    style="width:100%;">
                            </td>
                        </tr>
                        <tr>
                            <td> <label for="tgl_akhir" style="font-weight: bold;">Tanggal Akhir</label></td>
                            <td>:</td>
                            <td> <input class="control tgl_akhir" name="tgl_akhir" id="tgl_akhir" type="text"
                                    style="width:100%;">
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
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"
    integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
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

        $('.tgl_awal').datepicker({format:'dd/mm/yyyy'});
        $('.tgl_awal').datepicker('setDate', moment().startOf('year').format('DD/MM/YYYY'));

        $('.tgl_akhir').datepicker({format:'dd/mm/yyyy'});
        $('.tgl_akhir').datepicker('setDate',moment().format('DD/MM/YYYY'));

        let tgl_awal = $('#tgl_awal').val();
        tgl_awal = tgl_awal.split('/');
        tgl_awal = tgl_awal[2]+'-'+tgl_awal[1]+'-'+tgl_awal[0];
        let tgl_akhir = $('#tgl_akhir').val();
        tgl_akhir = tgl_akhir.split('/');
        tgl_akhir = tgl_akhir[2]+'-'+tgl_akhir[1]+'-'+tgl_akhir[0];
        $('#print_tgl_awal').val(tgl_awal)
        $('#print_tgl_akhir').val(tgl_akhir)

        var table = $('#table-data-inventory-management').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('admin.inventorymanagement.indexJson')}}",
                type: "POST",
                data: {
                    'tgl_awal': tgl_awal,
                    'tgl_akhir': tgl_akhir,
                }
            },
            columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data:'id', name: 'id'},
                    {data: 'nama_kategori', name: 'nama_kategori'},
                    {data: 'nama_product', name: 'nama_product'},
                    {data: 'jumlah_stok', name: 'jumlah_stok'},
                    {data: 'name', name: 'name'},
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
            let tgl_awal = $('#tgl_awal').val();
            tgl_awal = tgl_awal.split('/');
            tgl_awal = tgl_awal[2]+'-'+tgl_awal[1]+'-'+tgl_awal[0];
            let tgl_akhir = $('#tgl_akhir').val();
            tgl_akhir = tgl_akhir.split('/');
            tgl_akhir = tgl_akhir[2]+'-'+tgl_akhir[1]+'-'+tgl_akhir[0];

            $('#print_name').val($('#name').val());
            $('#print_name_inventory_management').val( $('#name_inventory_management').val())
            $('#print_kategori_barang').val($('#kategori_barang').val())
            $('#print_tgl_awal').val(tgl_awal)
            $('#print_tgl_akhir').val(tgl_akhir)
            // console.log(tgl_awal, tgl_akhir)
            // console.log(tgl_awal, $('#tgl_awal').val());
            // console.log(tgl_awal,$('.tgl_akhir').val());
            $('#table-data-inventory-management').DataTable().destroy();

            async function load(){
                await  $('#table-data-inventory-management').DataTable({
                    processing: true,
                    cache:false,
                    serverSide: true,
                    ajax: {
                        url: "{{route('admin.inventorymanagement.indexJson')}}",
                        type: "POST",
                        data: {
                            'name': $('#name').val(),
                            'name_inventory_management': $('#name_inventory_management').val(),
                            'store_name_barang': $('#store_name_barang').val(),
                            'tgl_awal': tgl_awal,
                            'tgl_akhir': tgl_akhir,
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


        $(document).on("click", ".item-del", function() {

            let ini = $(this);
            let id = $(this).data('id');

            let a = confirm('inventory-management you really want to perform this action ?');
            if(a == true){

                $.ajax({
                    url: "{{route('admin.inventorymanagement.delete','id')}}".replace('id', id),
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