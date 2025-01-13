@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/store/order/index.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.order'))
@section('description', __('description.order'))
@section('parent-route', route('store.dashboard'))
@section('title-content', mb_strtoupper(__('title.order')))

@section('content')
    
    {{ __('description.order') }}

    <a href="{{ route('store.order.new') }}">Nouvelle commande</a> {{-- {{ __('title.place_order') }} --}}
    <a href="{{ route('store.order.list') }}">{{ __('title.list_orders') }}</a>

@endsection