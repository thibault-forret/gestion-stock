@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock'))
@section('description', __('description.stock'))
@section('parent-route', route('warehouse.dashboard'))
@section('title-content', strtoupper(__('title.stock')))

@section('content')

    <div>
        <h2>{{ __('title.stock') }}</h2>
        
        <a href="{{ route('warehouse.stock.supply.index') }}">Approvisionner le stock</a>
        <a href="{{ route('warehouse.stock.list') }}">Liste des produits en stock</a>
        <a href="{{ route('warehouse.stock.list.movement') }}">Liste des mouvements de stock</a>
    </div>

@endsection