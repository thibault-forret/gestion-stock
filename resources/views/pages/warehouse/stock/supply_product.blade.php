@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock_supply_product'))
@section('description', __('description.stock_supply_product'))

@section('content')

    <form action="{{ route('warehouse.stock.product.supply.submit') }}" method="POST">
        @csrf
        
        <input type="hidden" name="stock_id" value="{{ $stock->id }}">

        <div>
            <h2>Informations sur le produit</h2>
            <p><strong>Nom :</strong> {{ $product->product_name }}</p>
            <p><strong>Catégorie(s) :</strong>
                @foreach($product->categories as $category)
                    <p>{{ $category->category_name }}</p>
                @endforeach
            </p>
            <p><strong>Fournisseur(s) :</strong> {{ $product->supplyLines->first()->supply->supplier->supplier_name }}</p>
            <p>
                <strong>Quantité disponible :</strong> {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
            </p>
            <div>
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width: 200px;">
            </div>
        </div>

        <div>
            <label for="quantity">Quantité à approvisionner :</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') == null ? 1 : old('quantity') }}" min="1" max="{{ $total_quantity }}" required>
        </div>

        <button type="submit">Approvisionner le produit</button>
    </form>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

@endsection