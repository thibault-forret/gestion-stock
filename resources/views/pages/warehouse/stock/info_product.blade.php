@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock_info_product'))
@section('description', __('description.stock_info_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', strtoupper(__('title.stock_info_product')))

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
        <div>
            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" style="max-width: 200px;">
        </div>
        <p><strong>Quantité disponible :</strong> {{ $stock->quantity_available }}</p>
        <p><strong>Seuil d'alerte :</strong> {{ $stock->alert_threshold }}</p>
        <p><strong>Seuil de réapprovisionnement :</strong> {{ $stock->restock_threshold }}</p>
        <p><strong>Quantité de réapprovisionnement automatique :</strong> {{ $stock->auto_restock_quantity }}</p>

        <a href="{{ route('warehouse.stock.product.edit', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Modifier</a>
        <a href="{{ route('warehouse.stock.product.supply', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Approvisionner</a>
        <a href="{{ route('warehouse.stock.product.remove', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Retirer</a>
    </div>

@endsection