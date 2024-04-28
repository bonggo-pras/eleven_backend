@extends('admin::layouts.content')

@section('page_title')
Delivery Orders
@stop

@section('content')

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
        <button @click="showModal('deliveryFilterModal')">Test</button>
        <table class="table-data-delivery-order" id="table-data-delivery-order">
            <thead>
                <tr style=" height: 65px;">
                    <!---->
                    <th>ID</th>
                    <th>Name</th>
                    <th>Store Name</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>End</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $row)
                <tr>
                    <td>{{$row->id}}</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->store_name}}</td>
                    <td>{{$row->keterangan}}</td>
                    <td>{{$row->status}}</td>
                    <td>{{$row->end}}</td>
                    <td>{{$row->created_at}}</td>
                    <td>{{$row->updated_at}}</td>
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

<!-- Modal -->
<modal id="deliveryFilterModal" :is-open="modalIds.deliveryFilterModal">
    <h3 slot="header">Test</h3>

    <div slot="body">
        {{-- <form method="POST" action="{{ route('admin.sales.invoices.send-duplicate-invoice', $invoice->id) }}"
            @submit.prevent="onSubmit">
            @csrf()

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <label for="email" class="required">{{ __('admin::app.admin.emails.email') }}</label>

                <input class="control" id="email" v-validate="'required|email'" type="email" name="email"
                    data-vv-as="&quot;{{ __('admin::app.admin.emails.email') }}&quot;"
                    value="{{ $invoice->order->customer_email }}">

                <span class="control-error" v-text="errors.first('email')" v-if="errors.has('email')">
                </span>
            </div> --}}

            {{-- <button type="submit" class="btn btn-lg btn-primary float-right">
                {{ __('admin::app.sales.invoices.send') }}
            </button> --}}
            {{--
        </form> --}}
    </div>
</modal>
{{-- Akhir Modal --}}

@endsection
@push('scripts')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // console.log('test')
        $('#table-data-delivery-order').DataTable();

        $(document).on("click", ".item-del", function() {

            let ini = $(this);
            let id = $(this).data('id');

            let a = confirm('Do you really want to perform this action ?');
            if(a == true){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.deliveryorder.delete','id')}}".replace('id', id),
                    method: "POST",
                    success: function(ev) {
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
                // console.log($('meta[name="csrf-token"]').attr('content'))
            // }
            }
            // console.log(a)
        });


    });
</script>
@endpush