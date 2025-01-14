@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/stock_list.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('product-search');
            const categorySelect = document.getElementById('category-name');
            const supplierSelect = document.getElementById('supplier-name');
            const productItems = document.querySelectorAll('.product-item');

            function filterProducts() {
                const searchValue = searchInput.value.toLowerCase();
                const selectedCategory = categorySelect.value.toLowerCase();
                const selectedSupplier = supplierSelect.value.toLowerCase();

                productItems.forEach(product => {
                    const productName = product.querySelector('.product_name').textContent.toLowerCase();
                    const productCategories = Array.from(product.querySelectorAll('.product_category')).map(cat => cat.textContent.toLowerCase());
                    const productSupplier = product.querySelector('.product_supplier').textContent.toLowerCase();

                    // Check if the product matches the filters
                    const matchesName = productName.includes(searchValue);
                    const matchesCategory = !selectedCategory || productCategories.includes(selectedCategory);
                    const matchesSupplier = !selectedSupplier || productSupplier.includes(selectedSupplier);

                    if (matchesName && matchesCategory && matchesSupplier) {
                        product.style.display = ''; // Show the product
                    } else {
                        product.style.display = 'none'; // Hide the product
                    }
                });

                // Check if there are no products matching the filters
                const noProducts = Array.from(productItems).every(product => product.style.display === 'none');

                if (noProducts) {
                    const noProductsMessageElement = document.querySelector('.no-products-message');
                    const noProductStockElement = document.querySelector('.no-product-stock');

                    if (noProductsMessageElement || noProductStockElement) {
                        return; // No need to add the message if it's already there
                    }

                    const noProductsMessage = document.createElement('p');
                    noProductsMessage.textContent = 'Aucun produit ne correspond aux filtres sélectionnés.';
                    noProductsMessage.classList.add('no-products-message');
                    noProductsMessage.classList.add('no-product');

                    document.querySelector('.product-list').appendChild(noProductsMessage);
                } else {
                    const noProductsMessage = document.querySelector('.no-products-message');

                    if (noProductsMessage) {
                        noProductsMessage.remove();
                    }
                }
            }

            searchInput.addEventListener('input', filterProducts);
            categorySelect.addEventListener('change', filterProducts);
            supplierSelect.addEventListener('change', filterProducts);
        });

    </script>
@endsection

@section('title', __('title.warehouse_stock_list'))
@section('description', __('description.warehouse_stock_list'))
@section('parent-route', route('warehouse.stock.index'))
@section('title-content', mb_strtoupper(__('title.warehouse_stock_list')))

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('warehouse.stock.search') }}" method="POST" class="filter-buttons search-form">
        @csrf
        <div class="filter-group">
            <div class="filter-item">
                <label for="search">Recherche par ID de produit</label>
                <input type="text" id="search" name="search" placeholder="ID du produit" required>
            </div>
        </div>
        
        <div class="filter-actions">
            <button class="btn-filter" type="submit">Rechercher</button>
            <a href="{{ route('warehouse.stock.list') }}" class="btn-reset">Réinitialiser</a>
        </div>
    </form>
    
    <div class="filter-buttons">    
        <div class="filter-group">
            <div class="filter-item">
                <label for="product-search">Rechercher par nom</label>
                <input type="text" id="product-search" name="product-search" placeholder="Nom du produit">
            </div>
    
            <div class="filter-item">
                <label for="category-name">Catégorie</label>
                <select id="category-name" name="category_name">
                    <option value="">Aucune sélection</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_name }}">
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="filter-item">
                <label for="supplier-name">Fournisseur</label>
                <select id="supplier-name" name="supplier_name" required>
                    <option value="">Aucune sélection</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_name }}">
                            {{ $supplier->supplier_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('warehouse.stock.list') }}" class="btn-reset">Réinitialiser</a>
        </div>

    <div class="product-list-wrapper">
        <div class="product-list">
            @if(isset($products) && count($products) > 0)
                @foreach($products as $product)
                    <div class="product-item" data-id="{{ $product->id }}">
                        <h3 class="product_name">{{ $product->product_name }}</h3>
                        <img class="product_image" src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                        <p><strong>ID :</strong>    
                            {{ $product->id }}
                        </p>
                        <p><strong>Catégorie(s) :</strong>
                            @foreach($product->categories as $category)
                                <span class="product_category">{{ $category->category_name }}</span>
                            @endforeach
                        </p>
                        <p><strong>Fournisseur :</strong> 
                            <span class="product_supplier">{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</span>
                        </p>
                        <p>
                            <strong>Quantité disponible :</strong> 
                            {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
                        </p>

                        @php
                            $stock = $product->stocks->where('warehouse_id', $warehouse->id)->first();
                        @endphp
                        
                        <div class="actions-buttons">
                            <a href="{{ route('warehouse.stock.product.info', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Informations</a>
                            <a href="{{ route('warehouse.stock.product.edit', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Modifier</a>
                            <a href="{{ route('warehouse.stock.product.supply', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Approvisionner</a>
                            <a href="{{ route('warehouse.stock.product.remove', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Retirer</a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="no-product">Aucun produit dans le stock.</p>
            @endif
        </div>
    </div>

</div>



@endsection