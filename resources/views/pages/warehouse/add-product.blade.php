<form action="{{ route('warehouse.product.add.submit') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product['id'] }}">

    <div>
        <h2>Informations sur le produit</h2>
        <p><strong>Nom :</strong> {{ $product['name'] }}</p>
        <p><strong>Catégorie(s) :</strong>
            @foreach($product['categories'] as $category)
                <p>{{ $category->category_name }}</p>
            @endforeach
        </p>
        <p><strong>Fournisseur(s) :</strong> {{ $product['supplier']->supplier_name }}</p>
        <div>
            <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" style="max-width: 200px;">
        </div>
    </div>

    <div>
        <label for="quantity">Quantité à mettre en stock :</label>
        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') == null ? 1 : old('quantity') }}" min="1" required>
    </div>

    <div>
        <label for="alert_threshold">Seuil d'alerte :</label>
        <input type="number" id="alert-threshold" name="alert_threshold" value="{{ old('alert_threshold') == null ? 1 : old('alert_threshold') }}" min="1" required>
    </div>

    <div>
        <label for="restock_threshold">Seuil de réapprovisionnement:</label>
        <input type="number" id="restock-threshold" name="restock_threshold" value="{{ old('restock_threshold') == null ? 0 : old('restock_threshold') }}" min="0" required>
    </div>

    <div>
        <label for="auto_restock_quantity">Quantité de réapprovisionnement automatique :</label>
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