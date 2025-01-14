@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/info_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_edit_product'))
@section('description', __('description.stock_edit_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_edit_product')))

@section('content')
    <div class="container mt-5">
        {{-- Une carte englobant tout le contenu dans un cadre esthétique --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">{{ __('Modifier les informations du Produit') }}</h3>
            </div>

            <div class="card-body p-4">
                {{-- Section principale : Nom et Image --}}
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <h4 class="text-muted"><strong>Nom du Produit :</strong></h4>
                        <p class="h5 font-weight-bold">{{ $product->product_name }}</p>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="img-fluid rounded border" style="max-width: 250px;">
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

                {{-- Section pour le formulaire de modification --}}
                <form action="{{ route('warehouse.stock.product.edit.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="alert-threshold" class="form-label">Seuil d'alerte :</label>
                            <input type="number" id="alert-threshold" name="alert_threshold" value="{{ old('alert_threshold') == null ? $stock->alert_threshold : old('alert_threshold') }}" min="1" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="restock-threshold" class="form-label">Seuil de réapprovisionnement :</label>
                            <input type="number" id="restock-threshold" name="restock_threshold" value="{{ old('restock_threshold') == null ? $stock->restock_threshold : old('restock_threshold') }}" min="0" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="auto-restock-quantity" class="form-label">Quantité auto réapprovisionnement :</label>
                            <input type="number" id="auto-restock-quantity" name="auto_restock_quantity" value="{{ old('auto_restock_quantity') == null ? $stock->auto_restock_quantity : old('auto_restock_quantity') }}" min="1" class="form-control" required>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-center mt-4">
                        <button type="submit" class="btn btn-primary-submit">
                            <i class="fas fa-save"></i> Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>

            {{-- Affichage des erreurs s'il y en a --}}
            @if ($errors->any())
                <div class="card-footer text-danger text-center">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
