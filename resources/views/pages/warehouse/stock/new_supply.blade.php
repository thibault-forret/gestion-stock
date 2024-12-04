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
        document.addEventListener("DOMContentLoaded", function () {
            const productList = document.querySelector('.product-list');
            const selectedProductsContainer = document.querySelector('.selected-products');

            // Permet d'ajouter un produit sélectionné ou d'augmenter sa quantité
            function addSelectedProduct(productId, productItem) {
                // Vérifie si le produit est déjà ajouté
                const existingProduct = selectedProductsContainer.querySelector(`.product-item[data-id="${productId}"]`);

                if (existingProduct) {
                    // Incrémente la quantité
                    const quantityInput = existingProduct.querySelector('input[name="quantities[]"]');
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                } else {
                    // Récupère le nom et l'image du produit
                    const productName = productItem.querySelector('.product_name').textContent.trim();
                    const productImage = productItem.querySelector('.product_image').src;

                    // Crée un élément de produit avec une quantité
                    const productElement = document.createElement('div');
                    productElement.classList.add('product-item');
                    productElement.setAttribute('data-id', productId);

                    // Définit le contenu HTML pour ce produit
                    productElement.innerHTML = `
                        <input type="hidden" name="products[]" value="${productId}">
                        <img src="${productImage}" alt="${productName}" style="max-width: 100px; max-height: 100px;">
                        <p>Produit: <strong>${productName}</strong></p>
                        <p>Quantité: <input type="number" name="quantities[]" value="1" min="1"></p>
                    `;

                    // Ajoute le produit au conteneur des produits sélectionnés
                    selectedProductsContainer.appendChild(productElement);
                }
            }

            productList.addEventListener('click', function (event) {
                if (event.target && event.target.classList.contains('btn-primary')) {
                    const productId = event.target.value;
                    const productItem = event.target.closest('.product-item');

                    // Ajouter ce produit au formulaire
                    addSelectedProduct(productId, productItem);
                }
            });

            // Recherche et filtrage des produits
            const searchInput = document.getElementById('product-search');
            const categorySelect = document.getElementById('category-name');
            const supplierSelect = document.getElementById('supplier-name');
            const productItems = document.querySelectorAll('.product-item');
            const noResultsMessage = document.createElement('p');
            noResultsMessage.textContent = 'Aucun produit trouvé.';
            noResultsMessage.style.display = 'none';
            productList.appendChild(noResultsMessage);

            function filterProducts() {
                const query = searchInput.value.toLowerCase();
                const selectedCategory = categorySelect.value.toLowerCase();
                const selectedSupplier = supplierSelect.value.toLowerCase();
                let visibleProducts = 0;

                productItems.forEach(productItem => {
                    const productName = productItem.querySelector('.product_name').textContent.toLowerCase();
                    const categories = Array.from(productItem.querySelectorAll('.product_category')).map(p => p.textContent.toLowerCase());
                    const supplier = productItem.querySelector('.product_supplier').textContent.toLowerCase();

                    // Recherche dans le nom, les catégories et le fournisseur
                    const matchesSearch = productName.includes(query);
                    const matchesCategory = selectedCategory === '' || categories.some(category => category.includes(selectedCategory));
                    const matchesSupplier = selectedSupplier === '' || supplier.includes(selectedSupplier);

                    if ((matchesSearch || categories.some(category => category.includes(query)) || supplier.includes(query)) && matchesCategory && matchesSupplier) {
                        productItem.style.display = '';
                        visibleProducts++;
                    } else {
                        productItem.style.display = 'none';
                    }
                });

                if (visibleProducts === 0) {
                    noResultsMessage.style.display = '';
                } else {
                    noResultsMessage.style.display = 'none';
                }
            }

            searchInput.addEventListener('input', filterProducts);
            categorySelect.addEventListener('change', filterProducts);
            supplierSelect.addEventListener('change', filterProducts);
        });
    </script>
@endsection

@section('title', __('title.stock_new_supply'))
@section('description', __('description.stock_new_supply'))

@section('content')

    <h3>{{ __('title.stock_new_supply') }}</h3>

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

    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item">
                    <h3 class="product_name">{{ $product->product_name }}</h3>
                    <img class="product_image" src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                    <p><u>Catégorie(s) :</u>
                        @foreach($product->categories as $category)
                            <span class="product_category">{{ $category->category_name }}</span>
                        @endforeach
                    </p>
                    <p><u>Fournisseur :</u> 
                        <span class="product_supplier">{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</span>
                    </p>
                    <p>
                        <u>Quantité disponible :</u> 
                        {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
                    </p>
    
                    <button class="btn btn-primary" value="{{ $product->id }}">Sélectionner</button>
                </div>
            @endforeach
        @else
            <p>Aucun produit dans le stock.</p>
        @endif
    </div>
    

    <form class="selected-product" action="{{ route('warehouse.stock.supply.new.submit') }}" method="POST">

        @csrf

        <h3>Produits sélectionner</h3>

        <div class="selected-products">
            {{-- Les produits sélectionnés seront ajoutés ici --}}
        </div>

        <button type="submit">Confirmer l'approvisionnement</button>

    </form>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

@endsection