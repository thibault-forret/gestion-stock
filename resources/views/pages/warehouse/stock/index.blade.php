@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock'))
@section('description', __('description.stock'))

@section('content')
    <div>
        <h2>{{ __('title.stock') }}</h2>
        
        <a href="{{ route('warehouse.stock.supply.make') }}">Approvisionner le stock</a>
        <a href="{{ route('warehouse.stock.list') }}">Liste des produits en stock</a>
    </div>

@endsection