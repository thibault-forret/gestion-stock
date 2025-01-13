@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/store/order/index.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.order'))
@section('description', __('description.order'))

@section('content')
    
    {{ __('description.order') }}

    <a href="{{ route('warehouse.stock.supply.new') }}">Nouvelle commande</a>
    <a href="{{ route('warehouse.stock.supply.list') }}">{{ __('title.list_orders') }}</a>

@endsection