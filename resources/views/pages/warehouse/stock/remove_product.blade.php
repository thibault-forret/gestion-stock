@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock_remove_product'))
@section('description', __('description.stock_remove_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_remove_product')))

@section('content')
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
            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" style="max-width: 200px;">
        </div>
    </div>

    <form action="{{ route('warehouse.stock.product.remove.quantity.submit') }}" method="POST">
        @csrf
        
        <input type="hidden" name="stock_id" value="{{ $stock->id }}">

        <div>
            <label for="quantity">Quantité à retirer :</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') == null ? 1 : old('quantity') }}" min="1" required>
        </div>

        <button type="submit">Retirer la quantité</button>
    </form>

    <form action="{{ route('warehouse.stock.product.remove.product.submit') }}" method="POST">
        @csrf
        
        <input type="hidden" name="stock_id" value="{{ $stock->id }}">

        <button type="submit">Retirer le produit</button>
    </form>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

@endsection