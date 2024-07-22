@extends('admin::layouts.content')

@section('page_title')
Delivery Orders
@stop

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link href="//cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css">
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
        {{-- <button @click="showModal('deliveryFilterModal')" class="btn btn-lg"
            style="background-color:#ffc107;">Filter</button> --}}
        {{-- <button id="btn-filter" class="btn btn-sm" style="background-color:#ffc107;">Filter</button> <br> --}}
        <table class="table-data-delivery-order" id="table-data-delivery-order">
            <thead>
                <tr style=" height: 65px;">
                    <!---->
                    <th>ID</th>
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
                @foreach ($datas as $key => $row)
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{$row->nama_kategori}}</td>
                    <td>{{$row->nama_product}}</td>
                    <td>{{$row->jumlah_stok}}</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->store_name}}</td>
                    <td>{{$row->end}}</td>
                    <td class="actions" style="white-space: nowrap; width: 100px;">
                        <div class="action">
                            <a href="{{ route('admin.deliveryorder.edit', $row->id) }}"><span
                                    class="icon pencil-lg-icon"></span></a>
                            <a href="{{ route('admin.deliveryorder.view', $row->id) }}" target="_blank"><span
                                    class="icon eye-icon"></span></a>
                            <a href="javascript:void(0)" class="item-del" data-id="{{$row->id}}"><span
                                    class="icon trash-icon"></span></a>
                        </div>
                    </td>
                </tr>
                @endforeach
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
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
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

        $('#table-data-delivery-order').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'print'
            ]
        });

        $('<button id="btn-filter" class="btn btn-sm" style="background-color:#ffc107; margin-right: 20px;">Filter</button>').insertBefore('.buttons-print');
        $('<br><br>').insertAfter('.buttons-print');
        $('.buttons-print').addClass('btn btn-sm btn-primary');

        $('#btn-filter').click(function(){
            $('#deliveryFilterModal').modal('show');
        });

        $('#deliveryFilterForm').submit(function(e){
            e.preventDefault();

            // delete $.ajaxSettings.headers["X-CSRF-TOKEN'"];
            $.ajax({
                    url: "{{route('admin.deliveryorder.filter')}}",
                    method: "POST",
                    data:$(this).serialize(),
                    beforeSend:function(){
                        $('#btn-filter-modal').html('load...');
                    },
                    success:function(ev){

                        if(!ev.err){

                            let tr = '';
                            $no = 1;
                            for (const key in  ev.datas) {
                            tr += `<tr>
                                <td>${$no++}</td>
                                    <td>${ev.datas[key].nama_kategori}</td>
                                    <td>${ev.datas[key].nama_product}</td>
                                    <td>${ev.datas[key].jumlah_stok}</td>
                                    <td>${ev.datas[key].name}</td>
                                    <td>${ev.datas[key].store_name}</td>
                                    <td>${ev.datas[key].end}</td>
                                    <td class="actions" style="white-space: nowrap; width: 100px;">
                                        <div class="action">
                                            <a href="admin/deliveryorder/edit/${ev.datas[key].id}"><span
                                                    class="icon pencil-lg-icon"></span></a>
                                            <a href="admin/deliveryorder/view/${ev.datas[key].id}" target="_blank"><span
                                                    class="icon eye-icon"></span></a>
                                            <a href="javascript:void(0)" class="item-del" data-id="${ev.datas[key].id}"><span
                                                    class="icon trash-icon"></span></a>
                                        </div>
                                    </td>
                                </tr>`;
                            }

                            $('#table-data-delivery-order').DataTable().destroy();
                            $('#table-data-delivery-order tbody').html(tr);
                            $('#table-data-delivery-order').DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    'print'
                                ]
                            });
                            $('<button id="btn-filter" class="btn btn-sm" style="background-color:#ffc107; margin-right: 20px;">Filter</button>').insertBefore('.buttons-print');
                            $('<br><br>').insertAfter('.buttons-print');
                            $('.buttons-print').addClass('btn btn-sm btn-primary');
                            $('#btn-filter').click(function(){
                                $('#deliveryFilterModal').modal('show');
                            });

                            $('#btn-filter-modal').html('Filter');
                            $('#deliveryFilterModal').modal('hide');

                            Toast.fire({
                            icon: "success",
                            title: "Load Success"
                            });

                        }else{

                            // $('#table-data-delivery-order').DataTable().destroy();
                            $('#table-data-delivery-order tbody').html(`<tr>
                                <td colspan="8">${ev.datas}</td>

                                </tr>`);
                            // $('#table-data-delivery-order').DataTable();

                            $('#btn-filter-modal').html('Filter');
                            $('#deliveryFilterModal').modal('hide');

                            Toast.fire({
                            icon: "success",
                            title: "Load Success"
                            });

                        }
                    }
            });
        });

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