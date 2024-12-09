@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.stock_info_product'))
@section('description', __('description.stock_info_product'))

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
    </div>

@endsection