@extends('layouts.app')

@section('css')
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px 0;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/search-new-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('product-search');
            const categorySelect = document.getElementById('category-name');
            const supplierSelect = document.getElementById('supplier-name');
            const productItems = document.querySelectorAll('.product-item');
            const resetButton = document.getElementById('reset-button');

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
            }

            resetButton.addEventListener('click', () => {
                searchInput.value = '';
                categorySelect.value = '';
                supplierSelect.value = '';
                filterProducts();
            });
            searchInput.addEventListener('input', filterProducts);
            categorySelect.addEventListener('change', filterProducts);
            supplierSelect.addEventListener('change', filterProducts);
        });

    </script>
@endsection

@section('title', __('title.warehouse_stock_list'))
@section('description', __('description.warehouse_stock_list'))
@section('parent-route', route('warehouse.stock.index'))
@section('title-content', strtoupper(__('title.warehouse_stock_list')))

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

    <h3>{{ __('title.warehouse_stock_list') }}</h3>

    <form action="{{ route('warehouse.stock.search') }}" method="POST">
        @csrf
        <div class="search-element">
            <div>
                <label for="search">Recherche par id de produit</label>
                <input type="text" id="search" name="search" value="" placeholder="ID du produit" required>
            </div>
        </div>
        <div class="buttons">
            <button class="btn" type="submit">Rechercher</button>
            <a class="btn red" href="{{ route('warehouse.stock.list') }}">Rénitialiser recherche</a>
        </div>
    </form>

    <div>
        <label for="product-search">Rechercher par nom</label>
        <input type="text" id="product-search" name="product-search">
    </div>
    <div>
        <label for="category_name">Catégorie :</label>
        <select id="category-name" name="category_name">
            <option value="">Aucune sélection</option>
            @foreach($categories as $category)
                <option value="{{ $category->category_name }}">
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>
    
    </div>
    <div>
        <label for="supplier_name">Fournisseur :</label>
        <select id="supplier-name" name="supplier_name" required>
            <option value="">Aucune sélection</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->supplier_name }}">
                    {{ $supplier->supplier_name }}
                </option>
            @endforeach
        </select>
    </div>

    <button id="reset-button">Rénitialiser recherche</button>

    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item" data-id="{{ $product->id }}">
                    <h3 class="product_name">{{ $product->product_name }}</h3>
                    <img class="product_image" src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                    <p><u>Catégorie(s) :</u>
                        @foreach($product->categories as $category)
                            <span class="product_category">{{ $category->category_name }}</span>
                        @endforeach
                    </p>
                    <p><u>ID :</u> 
                        {{ $product->id }}
                    </p>
                    <p><u>Fournisseur :</u> 
                        <span class="product_supplier">{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</span>
                    </p>
                    <p>
                        <u>Quantité disponible :</u> 
                        {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
                    </p>

                    @php
                        $stock = $product->stocks->where('warehouse_id', $warehouse->id)->first();
                    @endphp

                    <a href="{{ route('warehouse.stock.product.info', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Informations</a>
                    <a href="{{ route('warehouse.stock.product.edit', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Modifier</a>
                    <a href="{{ route('warehouse.stock.product.supply', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Approvisionner</a>
                    <a href="{{ route('warehouse.stock.product.remove', ['product_id' => $stock->product_id]) }}" class="btn btn-primary">Retirer</a>
                </div>
            @endforeach
        @else
            <p>Aucun produit dans le stock.</p>
        @endif
    </div>


@endsection