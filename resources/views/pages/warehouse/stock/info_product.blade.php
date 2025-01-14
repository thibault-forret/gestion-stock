@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/info_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_info_product'))
@section('description', __('description.stock_info_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_info_product')))

@section('content')
    <div class="container mt-5">
        {{-- Une carte englobant tout le contenu dans un cadre esthétique --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">{{ __('Informations sur le Produit') }}</h3>
            </div>

            <div class="card-body p-4">
                {{-- Section principale : Nom et Image --}}
                <div class="row mb-4">
                    <div class="col-lg-6 text-center">
                        <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="img-fluid rounded border" style="max-width: 250px;">
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-muted"><strong>Nom du Produit :</strong></h4>
                        <p class="h5 font-weight-bold">{{ $product->product_name }}</p>
                    </div>
                </div>

                {{-- Section pour les catégories et fournisseur --}}
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <h4 class="text-muted"><strong>Catégories :</strong></h4>
                        @if ($product->categories->isNotEmpty())
                            <ul class="list-group">
                                @foreach($product->categories as $category)
                                    <li class="list-group-item border-0">{{ $category->category_name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Aucune catégorie associée</p>
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <h4 class="text-muted"><strong>Fournisseur :</strong></h4>
                        <p>{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</p>
                    </div>
                </div>

                {{-- Section pour les stocks et seuils --}}
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <h4 class="text-muted"><strong>Quantité Disponible :</strong></h4>
                        <p class="h5">{{ $stock->quantity_available }}</p>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-muted"><strong>Seuils de Stock :</strong></h4>
                        <ul class="list-unstyled">
                            <li><strong>Seuil d’Alerte :</strong> {{ $stock->alert_threshold }}</li>
                            <li><strong>Seuil de Réapprovisionnement :</strong> {{ $stock->restock_threshold }}</li>
                            <li><strong>Quantité de Réapprovisionnement Automatique :</strong> {{ $stock->auto_restock_quantity }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Footer avec les actions --}}
            <div class="card-footer bg-light text-center">
                <a href="{{ route('warehouse.stock.product.edit', ['product_id' => $stock->product_id]) }}" class="btn btn-outline-primary mx-2">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('warehouse.stock.product.supply', ['product_id' => $stock->product_id]) }}" class="btn btn-outline-success mx-2">
                    <i class="fas fa-plus"></i> Approvisionner
                </a>
                <a href="{{ route('warehouse.stock.product.remove', ['product_id' => $stock->product_id]) }}" class="btn btn-outline-danger mx-2">
                    <i class="fas fa-minus"></i> Retirer
                </a>
            </div>
        </div>
    </div>
@endsection
