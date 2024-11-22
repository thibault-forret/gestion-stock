<form action="{{ route('warehouse.product.search') }}" method="POST">
    @csrf
    <div>
        <label for="search_by_name">Rechercher par nom</label>
        <input type="text" id="search-by-name" name="search_by_name">
    </div>
    <div>
        <label for="category_name">Catégorie :</label>
        <select id="category-name" name="category_name" required>
            <option value="">Sélectionner une catégorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
            @endforeach
        </select>
    
    </div>
    <div>
        <label for="supplier_name">Fournisseur :</label>
        <select id="supplier-name" name="supplier_name" required>
            <option value="">Sélectionner un fournisseur</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->supplier_name }}">{{ $supplier->supplier_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <button type="submit">Rechercher</button>
    </div>
</form>

@if ($errors->any())
    <div class="center-child error-message">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<style>
    .product-item {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
    .product-item h3 {
        margin: 0;
        font-size: 1.2em;
    }
    .product-item p {
        margin: 5px 0;
    }
    .product-item img {
        max-width: 100px;
        max-height: 100px;
        display: block;
        margin: 10px 0;
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
</style>

<div class="product-list">
    @if(isset($products) && count($products) > 0)
        @foreach($products as $product)
            <div class="product-item">
                <h3>{{ $product['name'] }}</h3>
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                <p><u>Catégorie(s) :</u>
                    @foreach($product['categories'] as $category)
                        <p>{{ $category->category_name }}</p>
                    @endforeach
                </p>
                <p>
                    <u>Fournisseur(s) :</u> {{ $supplier->supplier_name }}
                </p>
            </div>
        @endforeach
    @else
        <p>Aucun produit trouvé.</p>
    @endif
</div>