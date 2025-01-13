@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ mix('/css/pages/warehouse/add_product.css') }}">
@endsection
@section('title', __('title.add_product'))
@section('description', __('description.add_product'))
@section('parent-route', route('warehouse.product.index'))
@section('title-content', mb_strtoupper(__('title.add_product')))

@section('content')

    <div class="main-container">

        <!-- Bloc principal des informations produit -->
        <div class="product-info">
            <!-- Texte des informations -->
            <div class="text-container">
                <h2>Informations sur le produit</h2>
                <p><strong>Nom :</strong> {{ $product['name'] }}</p>
                <p><strong>Catégorie(s) :</strong>
                @foreach($product['categories'] as $category)
                    <p>{{ $category->category_name }}</p>
                    @endforeach
                    <p><strong>Fournisseur(s) :</strong> {{ $product['supplier']->supplier_name }}</p>
            </div>

            <!-- Image du produit -->
            <div>
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
            </div>
        </div>

        <!-- Formulaire d'ajout du produit -->
        <form action="{{ route('warehouse.product.add.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product['id'] }}">

            <!-- Champs organisés en lignes horizontales -->
            <div class="form-fields">
                <label for="quantity">Quantité à mettre en stock :</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') == null ? 1 : old('quantity') }}" min="1" required>
            </div>

            <div class="form-fields">
                <label for="alert_threshold">Seuil d'alerte :</label>
                <input type="number" id="alert-threshold" name="alert_threshold" value="{{ old('alert_threshold') == null ? 1 : old('alert_threshold') }}" min="1" required>
            </div>

            <div class="form-fields">
                <label for="restock_threshold">Seuil de réapprovisionnement :</label>
                <input type="number" id="restock-threshold" name="restock_threshold" value="{{ old('restock_threshold') == null ? 0 : old('restock_threshold') }}" min="0" required>
            </div>

            <div class="form-fields">
                <label for="auto_restock_quantity">Réapprovisionnement auto :</label>
                <input type="number" id="auto-restock-quantity" name="auto_restock_quantity" value="{{ old('auto_restock_quantity') == null ? 1 : old('auto_restock_quantity') }}" min="1" required>
            </div>

            <!-- Bouton d'ajout -->
            <button type="submit">Ajouter le produit</button>
        </form>

        <!-- Affichage des erreurs -->
        @if ($errors->any())
            <div class="center-child error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

    </div>
@endsection
