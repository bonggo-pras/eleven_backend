@extends('admin::layouts.master')
@section('page_title')
Customer #{{ $point_histories->full_name }}
@stop

@section('content-wrapper')
<div class="content full-page">
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.claim-reward.index') }}'"></i> Customer #{{ $point_histories->full_name }}
            </h1>
        </div>
        @if ($point_histories->status == 'approve')
        <div class="page-action">
            <button type="button" @click="$root.showModal('addGroupForm')" class="btn btn-lg btn-primary">
                Simpan Reward
            </button>
        </div>
        @endif
        @if ($point_histories->status == 'on-shipment')
        <div class="page-action">
            <button type="button" @click="$root.showModal('finishShipment')" class="btn btn-lg btn-primary">
                Selesaikan Reward
            </button>
        </div>
        @endif
    </div>

    <div class="page-content">
        <tabs>
            {!! view_render_event('sales.order.tabs.before', ['point_histories' => $point_histories]) !!}

            <tab name="{{ __('admin::app.sales.orders.info') }}" :selected="true">
                <div class="sale-container">

                    <accordian title="Customer Information" :active="true">
                        <div slot="body">
                            <div class="sale">
                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>Customer Informasi </span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.first_name') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->first_name }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.created_at.after', ['order' => $point_histories]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.last_name') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->last_name }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.status_label.after', ['order' => $point_histories]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.email') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->email }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.gender') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->gender ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.channel_name.after', ['point_histories' => $point_histories]) !!}
                                    </div>
                                </div>

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <p></p>
                                    </div>

                                    <div class="section-content">

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.status') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->status ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.date_of_birth') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->date_of_birth ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.customers.customers.phone') }}
                                            </span>

                                            <span class="value">
                                                {{ $point_histories->phone ?? "Tidak Disebutkan" }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.order.customer_group.after', ['point_histories' => $point_histories]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </accordian>

                    <accordian title="Reward Information" :active="true">
                        <div slot="body">
                            <div class="sale">
                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>Reward Information</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="section-content">
                                            <div class="row">
                                                <span class="title">
                                                    Reward Name
                                                </span>

                                                <span class="value">
                                                    {{ $point_histories->reward_name }}
                                                </span>
                                            </div>

                                            {!! view_render_event('sales.order.created_at.after', ['order' => $point_histories]) !!}

                                            <div class="row">
                                                <span class="title">
                                                    Claim At
                                                </span>

                                                <span class="value">
                                                    {{ $point_histories->claim_at }}
                                                </span>
                                            </div>

                                            {!! view_render_event('sales.order.status_label.after', ['order' => $point_histories]) !!}

                                            <div class="row">
                                                <span class="title">
                                                    Shipment At
                                                </span>

                                                <span class="value">
                                                    {{ $point_histories->shipment_at ?? 'Kosong' }}
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span class="title">
                                                    Finish Shipment
                                                </span>

                                                <span class="value">
                                                    {{ $point_histories->finish_shipment ?? 'Kosong' }}
                                                </span>
                                            </div>

                                            {!! view_render_event('sales.order.channel_name.after', ['point_histories' => $point_histories]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </accordian>

                    <modal id="addGroupForm" :is-open="$root.modalIds.addGroupForm">
                        <h3 slot="header">Kirim Reward</h3>

                        <div slot="body">
                            <form method="POST" action="{{ route('admin.claim-reward.claim-reward', $point_histories->id) }}" data-vv-scope="add-group-form">
                                <div class="page-content">
                                    <div class="form-container">
                                        @csrf()

                                        <div class="control-group date">
                                            <label for="shipment_at" class="required">Shipment At</label>
                                            <datetime>
                                                <input type="text" value="" class="control" id="shipment_at" name="shipment_at" />
                                            </datetime>
                                        </div>

                                        <button type="submit" class="btn btn-lg btn-primary">
                                            Simpan Claim dan Kirim Reward
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </modal>

                    <modal id="finishShipment" :is-open="$root.modalIds.finishShipment">
                        <h3 slot="header">Kirim Reward</h3>

                        <div slot="body">
                            <form method="POST" action="{{ route('admin.claim-reward.finish-reward', $point_histories->id) }}" data-vv-scope="add-group-form">
                                <div class="page-content">
                                    <div class="form-container">
                                        @csrf()

                                       <input type="text" name="status" value="completed" style="display: none;">

                                       <p style="margin-bottom: 50px;">Apakah reward telah ini sudah selesai?</p>

                                        <div class="row">
                                            <button type="button" class="btn btn-lg btn-primary">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-lg btn-primary">
                                                Selesaikan Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </modal>
                </div>
            </tab>

            {!! view_render_event('sales.order.tabs.after', ['point_histories' => $point_histories]) !!}
        </tabs>
    </div>
</div>
@stop