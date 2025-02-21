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
        <div class="product-info">
            <div class="text-container">
                <h2>Informations sur le produit</h2>
                <p>Nom :<strong> {{ $product['name'] }}</strong></p>
                <p>Fournisseur(s) :<strong> {{ $product['supplier']->supplier_name }}</strong></p>
                <p>Catégorie(s) :
                    <strong>
                @foreach($product['categories'] as $category)
                    {{ $category->category_name }}
                        @endforeach
                    </strong>
                </p>
            </div>

            <div>
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
            </div>
        </div>

        <form action="{{ route('warehouse.product.add.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product['id'] }}">

            <div class="form-fields">
                <h2>Paramètres de l'ajout</h2>
                <label for="quantity">&#x1F4E6 Quantité à mettre en stock :</label>
                <div class="quantity-picker">
                    <button type="button" onclick="decrementQuantity()">-</button>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity') == null ? 1 : old('quantity') }}" min="1" required>
                    <button type="button" onclick="incrementQuantity()">+</button>
                </div>
            </div>

            <div class="form-fields">
                <label for="alert_threshold">&#x1F514 Seuil d'alerte :</label>
                <input type="number" id="alert-threshold" name="alert_threshold" value="{{ old('alert_threshold') == null ? 1 : old('alert_threshold') }}" min="1" required>
            </div>

            <div class="form-fields">
                <label for="restock_threshold">&#x1F504 Seuil de réapprovisionnement :</label>
                <input type="number" id="restock-threshold" name="restock_threshold" value="{{ old('restock_threshold') == null ? 0 : old('restock_threshold') }}" min="0" required>
            </div>

            <div class="form-fields">
                <label for="auto_restock_quantity">&#x2705 Réapprovisionnement auto :</label>
                <input type="number" id="auto-restock-quantity" name="auto_restock_quantity" value="{{ old('auto_restock_quantity') == null ? 1 : old('auto_restock_quantity') }}" min="1" required>
            </div>

            <button type="submit">Ajouter le produit</button>
        </form>

        @if ($errors->any())
            <div class="center-child error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <div class="info-message">
        <h2>Informations</h2>
        <p><strong>Quantité à mettre en stock :</strong> Indiquez la quantité de ce produit que vous souhaitez ajouter au stock.</p>
        <p><strong>Seuil d'alerte :</strong> Définissez le seuil à partir duquel une alerte sera déclenchée pour ce produit.</p>
        <p><strong>Seuil de réapprovisionnement :</strong> Définissez le seuil à partir duquel le produit doit être réapprovisionné.</p>
        <p><strong>Réapprovisionnement auto :</strong> Indiquez la quantité à réapprovisionner automatiquement lorsque le seuil de réapprovisionnement est atteint.</p>
    </div>

    <script>
        function incrementQuantity() {
            var quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decrementQuantity() {
            var quantityInput = document.getElementById('quantity');
            if (quantityInput.value > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }
    </script>
@endsection
