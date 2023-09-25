@if ($logo = core()->getCurrentChannel()->logo_url)
    <img src="{{ $logo }}" alt="{{ config('app.name') }}" style="height: 50px;"/>
@else
    <img src="{{ asset('themes/default/assets/images/logo.svg') }}" alt="{{ config('app.name') }}"/>
@endif