@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock'))
@section('description', __('description.stock'))

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div>
        <h2>{{ __('title.stock') }}</h2>
        
        <a href="{{ route('warehouse.stock.supply.new') }}">Approvisionner le stock</a>
        <a href="{{ route('warehouse.stock.list') }}">Liste des produits en stock</a>
        <a href="{{ route('warehouse.stock.list.movement') }}">Liste des mouvements de stock</a>
    </div>

@endsection