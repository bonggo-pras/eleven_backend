@extends('admin::layouts.content')

@section('page_title')
Buat Inventory Management Baru
@stop

@section('content')
<div class="content" id="app">
    <form method="POST" action="{{ route('admin.inventorymanagement.store') }}" @submit.prevent="onSubmit">

        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.customer.index') }}'"></i>

                    Buat Inventory Management Baru
                </h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-lg btn-primary">
                    Simpan Inventory Management
                </button>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                @csrf()

                <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                    <label for="name" class="required">Name</label>
                    <input type="text" class="control" id="name" name="name" v-validate="'required'" value="{{ old('name') }}">
                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('keterangan') ? 'has-error' : '']">
                    <label for="keterangan" class="required">Keterangan</label>
                    <input type="text" class="control" id="keterangan" name="keterangan" v-validate="'required'" value="{{ old('name') }}">
                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                </div>

                <div class="control-group date">
                    <label for="end">Selesai</label>
                    <datetime>
                        <input type="text" name="end" class="control" value="{{ old('end') }}" />
                    </datetime>
                </div>
                <add-product></add-product>
            </div>
        </div>
    </form>
</div>

@stop

@push('scripts')
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

<script type="text/x-template" id="add-product-template">
    <div class="form-container">
        <div class="control-group">
            <label for="cari">Cari barang disini</label>
            <v-select :reduce="(option) => option" @input="myChangeEvent" :options="options" id="cari" style="width: 70%; margin-top: 10px;" :filterable="false" :options="options" @search="onSearch">
                <template slot="no-options">
                    Ketikan nama produk...
                </template>
                <template slot="option" slot-scope="option">
                    @{{ option.name }}
                    <div class="d-center" v-for="(variant, index) in option.variants" :key="index">
                        @{{ variant.name }}
                    </div>
                </template>
                <template slot="selected-option" slot-scope="option">
                    <div class="selected d-center">
                        @{{ option.name }}
                    </div>
                </template>
            </v-select>
        </div>

        <table class="table table-striped">
            <thead>
                <th>No.</th>
                <th>Nama Produk</th>
                <th>Price</th>
                <th>Status</th>
                <th>Stok Masuk</th>
                <th>Opsi</th>
            </thead>
            <tbody>
                <tr v-if="itemProducts.length > 0" v-for="(item, index) in itemProducts" :key="index" style="text-align: center;">
                    <input type="text" name="productIds[]" v-model="item.id" style="display: none;"> 
                    <td>@{{ index + 1 }}</td>
                    <td>@{{ item.name }}</td>
                    <td>@{{ item.formatted_price }}</td>
                    <td>@{{ item.in_stock ? "In Stock" : "Out Of Stock" }}</td>
                    <td style="width: 100px;"> <div class="control-group"><input type="number" class="control" name="stocks[]"></div> </td>
                    <td><span class="icon trash-icon" style="cursor: pointer;" v-on:click="deleteItem(item.id)"></span></td>
                </tr>
                <tr v-else><p style="text-align: center;">Kosong</p></tr>
            </tbody>
        </table>
    </div>
</script>

<script>
    Vue.component('add-product', {
        template: '#add-product-template',
        data: function() {
            return {
                newItem: '',
                itemProducts: [],
                options: [],
                baseUrl: "{{ url('/') }}/api/v1/search?term="
            }
        },
        methods: {
            onSearch(search, loading) {
                if (search.length) {
                    loading(true);
                    this.search(loading, search, this);
                }
            },
            search: _.debounce((loading, search, vm) => {
                vm.options = [];
                fetch(`${vm.baseUrl}${escape(search)}`).then(res => {
                    res.json().then(json => {
                        vm.options = [];

                        vm.options = json.data;
                    });


                    loading(false);
                });
            }, 350),
            addItem() {
                if (this.newItem.trim() !== '') {
                    this.itemProducts.push(this.newItem);
                    this.newItem = '';
                }
            },
            myChangeEvent(item) {
                console.log(item);
                this.itemProducts.push(item);
            },
            deleteItem(value) {
                this.itemProducts = this.itemProducts.filter(item => item.id !== value);
            }
        }
    });
</script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
</script>
@endpush