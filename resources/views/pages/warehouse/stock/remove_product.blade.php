@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/remove_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_remove_product'))
@section('description', __('description.stock_remove_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_remove_product')))

@section('content')
    <div class="container mt-5">
        {{-- Une carte englobant le contenu --}}
        <div class="card shadow-sm">
            {{-- En-tête --}}
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">{{ __('Retirer un produit du Stock') }}</h3>
            </div>

            {{-- Corps de la carte --}}
            <div class="card-body p-4">
                {{-- Informations sur le Produit --}}
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

                {{-- Formulaire de retrait de quantité --}}
                <h4 class="text-muted"><strong>Retirer des Quantités :</strong></h4>
                <form action="{{ route('warehouse.stock.product.remove.quantity.submit') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité à retirer :</label>
                        <input type="number"
                               id="quantity"
                               name="quantity"
                               value="{{ old('quantity') ?? 1 }}"
                               min="1"
                               class="form-control"
                               required>
                    </div>

                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-minus"></i> Retirer la quantité
                    </button>
                </form>

                {{-- Formulaire de suppression complète --}}
                <h4 class="text-muted"><strong>Supprimer Complètement le Produit :</strong></h4>
                <form action="{{ route('warehouse.stock.product.remove.product.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Retirer le Produit
                    </button>
                </form>
            </div>

            {{-- Affichage des erreurs (si présent) --}}
            @if ($errors->any())
                <div class="mt-3 alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
