@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock_edit_product'))
@section('description', __('description.stock_edit_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_edit_product')))

@section('content')

    <form action="{{ route('warehouse.stock.product.edit.submit') }}" method="POST">
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
                <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" style="max-width: 200px;">
            </div>
        </div>

        <div>
            <label for="alert_threshold">Seuil d'alerte :</label>
            <input type="number" id="alert-threshold" name="alert_threshold" value="{{ old('alert_threshold') == null ? $stock->alert_threshold : old('alert_threshold') }}" min="1" required>
        </div>

        <div>
            <label for="restock_threshold">Seuil de réapprovisionnement:</label>
            <input type="number" id="restock-threshold" name="restock_threshold" value="{{ old('restock_threshold') == null ? $stock->restock_threshold : old('restock_threshold') }}" min="0" required>
        </div>

        <div>
            <label for="auto_restock_quantity">Quantité de réapprovisionnement automatique :</label>
            <input type="number" id="auto-restock-quantity" name="auto_restock_quantity" value="{{ old('auto_restock_quantity') == null ? $stock->auto_restock_quantity : old('auto_restock_quantity') }}" min="1" required>
        </div>

        <button type="submit">Modifier le produit</button>
    </form>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

@endsection