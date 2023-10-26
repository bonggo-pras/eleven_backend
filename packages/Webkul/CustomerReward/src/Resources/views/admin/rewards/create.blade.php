@extends('admin::layouts.content')

@section('page_title')
Add Reward
@stop

@section('content')
<div class="content">
    <form method="POST" action="{{ route('admin.rewards.store') }}" @submit.prevent="onSubmit">

        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.rewards.index') }}'"></i>

                    Add Reward
                </h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-lg btn-primary">
                    Simpan Reward
                </button>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                @csrf()

                <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                    <label for="name" class="required">{{ __('admin::app.customers.groups.name') }}</label>
                    <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.customers.groups.name') }}&quot;" v-code />
                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('point_required') ? 'has-error' : '']">
                    <label for="point_required" class="required">
                        Point Required
                    </label>
                    <input type="number" class="control" name="point_required" v-validate="'required'" value="{{ old('point_required') }}" data-vv-as="&quot;Point Required&quot;">
                    <span class="control-error" v-if="errors.has('point_required')">@{{ errors.first('point_required') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('stock') ? 'has-error' : '']">
                    <label for="stock" class="required">Kuantiti</label>
                    <input type="number" v-validate="'required'" class="control" id="stock" name="stock" data-vv-as="&quot;Stock&quot;" v-code />
                    <span class="control-error" v-if="errors.has('stock')">@{{ errors.first('stock') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('keterangan') ? 'has-error' : '']">
                    <label for="keterangan" class="required">Keterangan</label>
                    <input v-validate="'required'" class="control" id="keterangan" name="keterangan" data-vv-as="&quot;Keterangan&quot;" v-code />
                    <span class="control-error" v-if="errors.has('keterangan')">@{{ errors.first('keterangan') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('start') ? 'has-error' : '']">
                    <label for="dob">Start</label>
                    <input type="date" v-validate="'required'" class="control" id="dob" name="start" v-validate="" value="{{ old('start') }}" placeholder="{{ __('admin::app.customers.customers.start_placeholder') }}" data-vv-as="&quot;Start&quot;">
                    <span class="control-error" v-if="errors.has('start')">@{{ errors.first('start') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('end') ? 'has-error' : '']">
                    <label for="dob">End</label>
                    <input type="date" v-validate="'required'" class="control" id="dob" name="end" v-validate="" value="{{ old('end') }}" placeholder="{{ __('admin::app.customers.customers.end_placeholder') }}" data-vv-as="&quot;End&quot;">
                    <span class="control-error" v-if="errors.has('end')">@{{ errors.first('end') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                    <label for="status">{{ __('admin::app.users.users.status') }}</label>

                    <label class="switch">
                        <input type="checkbox" id="status" name="status" value="active" {{ old('status') ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

    </form>
</div>
@stop