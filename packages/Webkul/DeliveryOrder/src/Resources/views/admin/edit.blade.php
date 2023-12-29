@extends('admin::layouts.content')

@section('page_title')
Edit Surat Jalan Baru
@stop

@section('content')
<div class="content" id="app">
    <form method="POST" action="{{ route('admin.deliveryorder.update', $deliveryOrder->id) }}" @submit.prevent="onSubmit">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.customer.index') }}'"></i>

                    Edit Surat Jalan Baru
                </h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-lg btn-primary">
                    Simpan Surat Jalan
                </button>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                @csrf()

                <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                    <label for="name" class="required">Name</label>
                    <input type="text" class="control" value="{{ $deliveryOrder->name }}" id="name" name="name" v-validate="'required'" value="{{ old('name') }}">
                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('store_name') ? 'has-error' : '']">
                    <label for="store_name" class="required">Store Name</label>
                    <input type="text" class="control" value="{{ $deliveryOrder->store_name }}" id="store_name" name="store_name" v-validate="'required'" value="{{ old('store_name') }}">
                    <span class="control-error" v-if="errors.has('store_name')">@{{ errors.first('store_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('keterangan') ? 'has-error' : '']">
                    <label for="keterangan" class="required">Keterangan</label>
                    <input type="text" class="control" value="{{ $deliveryOrder->keterangan }}" id="keterangan" name="keterangan" v-validate="'required'" value="{{ old('name') }}">
                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                </div>

                <div class="control-group date">
                    <label for="end">Selesai</label>
                    <datetime>
                        <input type="text" name="end" class="control" value="{{ $deliveryOrder->end }}" />
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
            <v-select :reduce="(option) => option" @input="myChangeEvent" :options="options" id="cari" style="width: 70%; margin-top: 10px;" :filterable="false" @search="onSearch">
                <template slot="no-options">
                    Ketikan nama produk...
                </template>
                <template slot="option" slot-scope="option">
                    @{{ option.name }}
                </template>
                <template slot="selected-option" slot-scope="option">
                    <div class="selected d-center">
                        @{{ option.name }}
                    </div>
                </template>
            </v-select>
        </div>

        <div class="control-group" v-if="variants.length > 0">
            <label for="cari">Cari variant barang disini</label>
            <v-select :reduce="(option) => option" :options="variants" id="cariVariant" @input="selectedVariant" style="width: 70%; margin-top: 10px;">
                <template slot="no-options">
                    Ketikan nama produk...
                </template>
                <template slot="option" slot-scope="option">
                    @{{ option.name }}
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
                <th>Stok Keluar</th>
                <th>Opsi</th>
            </thead>
            <tbody>
            <tr v-if="itemProducts.length > 0" v-for="(item, index) in itemProducts" :key="index" style="text-align: center;">
                    <input type="text" name="productIds[]" v-model="item.id" style="display: none;"> 
                    <td>@{{ index + 1 }}</td>
                    <td>@{{ item.name }}</td>
                    <td>@{{ item.formatted_price ? item.formatted_price : item.price }}</td>
                    <span v-if="itemProducts.type == 'configurable'">
                    <td v-if="item.in_stock != null">@{{ item.in_stock ? "In Stock" : "Out Of Stock" }}</td>
                    <td v-else>@{{ item.status ? "In Stock" : "Out Of Stock" }}</td>
                    </span>
                    <span v-else>
                    <td v-if="item.inventories">@{{ item.inventories[0]['qty'] > 0 ? "In Stock" : "Out Of Stock" }}</td>
                    <td v-else><p style="text-align: center;">Maaf Sistem tidak dapat menemukan stok. <br> Silahkan cek manual pada tabel produk </p></td>
                    </span>
                    <td style="width: 100px;"> <div class="control-group"><input type="number" class="control" v-model="itemProducts[index].stock" name="stocks[]"></div> </td>
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
                itemProducts: '',
                options: [],
                variants: [],
                baseUrl: "{{ url('/') }}/api/v1/search?outStock=true&term="
            }
        },
        created: function() {
            const product = @json($deliveryOrder->items);
            let formatedProduct = [];

            product.forEach((item) => {
                const format = {
                    'id': item.product_id,
                    'name': item.product_flat.name,
                    'formatted_price': this.formatRupiah(item.product_flat.price, 'Rp'),
                    'stock': item.stock,
                }

                formatedProduct.push(format);
            });

            this.itemProducts = formatedProduct;
        },
        methods: {
            onSearch(search, loading) {
                if (search.length) {
                    loading(true);
                    this.search(loading, search, this);
                }
            },
            onSearchVariant(search, loading) {
                if (search.length) {
                    loading(true);
                    this.searchVariants(loading, search, this);
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
            searchVariants: _.debounce((loading, search, vm) => {}, 350),
            addItem() {
                if (this.newItem.trim() !== '') {
                    this.itemProducts.push(this.newItem);
                    this.newItem = '';
                }
            },
            myChangeEvent(item) {
                if (item.type == 'configurable') {
                    this.variants = item.variants;
                } else {
                    this.itemProducts.push(item);
                }

                console.log(this.variants, this.itemProducts);
            },
            deleteItem(value) {
                this.itemProducts = this.itemProducts.filter(item => item.id !== value);
            },
            selectedVariant(item) {
                const index = this.itemProducts.findIndex(object => object.id === item.id);

                if (index === -1) {
                    this.itemProducts.push(item);
                }
            },
            formatRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
        }
    });
</script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
</script>
@endpush