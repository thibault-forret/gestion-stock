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

        /* Style des boutons désactivés */
        button.disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }

        button.disabled:hover {
            background-color: #ccc;
        }

    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/search-new-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productList = document.querySelector('.product-list');
            const selectedProductsContainer = document.querySelector('.selected-products');
            const confirmSupplyButton = document.getElementById('confirm-supply-btn');

            // Fonction pour activer/désactiver le bouton
            function toggleConfirmButton() {
                const hasSelectedProducts = selectedProductsContainer.children.length > 0;
                confirmSupplyButton.disabled = !hasSelectedProducts;
            }

            // Ajouter un produit sélectionné
            function addSelectedProduct(productId, productItem) {
                // Vérifie si le produit est déjà ajouté
                const existingProduct = selectedProductsContainer.querySelector(`.product-item[data-id="${productId}"]`);

                if (!existingProduct) {
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
                        <button type="button" class="btn btn-danger btn-remove-selected">Retirer</button>
                    `;

                    // Ajoute l'écouteur d'événements pour le bouton "Retirer"
                    productElement.querySelector('.btn-remove-selected').addEventListener('click', function () {
                        // Supprime l'élément de la liste des produits sélectionnés
                        productElement.remove();
                        toggleConfirmButton();

                        // Réactive le bouton "Sélectionner" dans la liste principale des produits
                        const originalProductItem = document.querySelector(`.product-list .product-item[data-id="${productId}"]`);
                        if (originalProductItem) {
                            toggleButtons(originalProductItem, false);
                        }
                    });

                    // Ajoute le produit au conteneur des produits sélectionnés
                    selectedProductsContainer.appendChild(productElement);
                    toggleConfirmButton();
                }
            }

            function removeSelectedProduct(productId) {
                const productElement = selectedProductsContainer.querySelector(`.product-item[data-id="${productId}"]`);
                if (productElement) {
                    productElement.remove();
                    toggleConfirmButton();
                }
            }

            // Bascule les boutons "Sélectionner" et "Retirer"
            function toggleButtons(productItem, isSelected) {
                const selectButton = productItem.querySelector('.btn-select');
                const removeButton = productItem.querySelector('.btn-remove');

                if (isSelected) {
                    // Désactiver le bouton "Sélectionner" et activer "Retirer"
                    selectButton.classList.add('disabled');
                    selectButton.setAttribute('disabled', 'true');
                    removeButton.classList.remove('disabled');
                    removeButton.removeAttribute('disabled');
                } else {
                    // Activer le bouton "Sélectionner" et désactiver "Retirer"
                    selectButton.classList.remove('disabled');
                    selectButton.removeAttribute('disabled');
                    removeButton.classList.add('disabled');
                    removeButton.setAttribute('disabled', 'true');
                }
            }


            // Gestion des clics sur les boutons "Sélectionner" et "Retirer"
            productList.addEventListener('click', function (event) {
                const button = event.target;
                const productItem = button.closest('.product-item');
                const productId = productItem.getAttribute('data-id');

                if (button.classList.contains('btn-select')) {
                    addSelectedProduct(productId, productItem);
                    toggleButtons(productItem, true);
                } else if (button.classList.contains('btn-remove')) {
                    removeSelectedProduct(productId);
                    toggleButtons(productItem, false);
                }
            });

            // Recherche et filtrage des produits
            const productIdInput = document.getElementById('product-id');
            const productNameInput = document.getElementById('product-name');
            const categorySelect = document.getElementById('category-name');
            const supplierSelect = document.getElementById('supplier-name');
            const productItems = document.querySelectorAll('.product-list .product-item');
            const resetButton = document.getElementById('reset-button');


            function filterProducts() {
                const productId = productIdInput.value;
                const productName = productNameInput.value.toLowerCase();
                const selectedCategory = categorySelect.value.toLowerCase();
                const selectedSupplier = supplierSelect.value.toLowerCase();

                let visibleProducts = 0;

                productItems.forEach(productItem => {
                    const id = productItem.querySelector('.product_id').textContent;
                    const name = productItem.querySelector('.product_name').textContent.toLowerCase();
                    const categories = Array.from(productItem.querySelectorAll('.product_category')).map(c => c.textContent.toLowerCase());
                    const supplier = productItem.querySelector('.product_supplier').textContent.toLowerCase();

                    const matchesProductId = id.includes(productId);
                    const matchesProductName = name.includes(productName);
                    const matchesCategory = !selectedCategory || categories.includes(selectedCategory);
                    const matchesSupplier = !selectedSupplier || supplier.includes(selectedSupplier);

                    if (matchesProductName && matchesProductId && matchesCategory && matchesSupplier) {
                        productItem.style.display = '';
                        visibleProducts++;
                    } else {
                        productItem.style.display = 'none';
                    }
                });

                const noResultsMessage = document.querySelector('.no-results-message');
                if (visibleProducts === 0) {
                    if (!noResultsMessage) {
                        const message = document.createElement('p');
                        message.textContent = 'Aucun produit trouvé.';
                        message.classList.add('no-results-message');
                        productList.appendChild(message);
                    }
                } else if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            }

            resetButton.addEventListener('click', () => {
                productIdInput.value = '';
                productNameInput.value = '';
                categorySelect.value = '';
                supplierSelect.value = '';
                filterProducts();
            });
            productIdInput.addEventListener('input', filterProducts);
            productNameInput.addEventListener('input', filterProducts);
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
        <label for="product-id">Rechercher par ID</label>
        <input type="text" id="product-id" name="product-id">
    </div>
    <div>
        <label for="product-name">Rechercher par nom</label>
        <input type="text" id="product-name" name="product-name">
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
                        <span class="product_id">{{ $product->id }}</span>
                    </p>
                    <p><u>Fournisseur :</u> 
                        <span class="product_supplier">{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</span>
                    </p>
                    <p>
                        <u>Quantité disponible :</u> 
                        {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
                    </p>
                
                    <!-- Boutons "Sélectionner" et "Retirer" -->
                    <button class="btn btn-primary btn-select">Sélectionner</button>
                    <button class="btn btn-danger btn-remove disabled" disabled>Retirer</button>
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

        <button type="submit" id="confirm-supply-btn" disabled>Confirmer l'approvisionnement</button>
    </form>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

@endsection