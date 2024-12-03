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
            
            // Permet d'ajouter un produit sélectionné au formulaire
            function addSelectedProduct(productId, productItem) {
                // Récupère le nom et l'image du produit
                const productName = productItem.querySelector('.product_name').textContent.trim();
                const productImage = productItem.querySelector('.product_image').src;

                // Crée un élément de produit avec une quantité
                const productElement = document.createElement('div');
                productElement.classList.add('product-item');
                
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

            // Ajoute un écouteur d'événement pour chaque bouton de sélection de produit
            productList.addEventListener('click', function (event) {
                if (event.target && event.target.classList.contains('btn-primary')) {
                    const productId = event.target.value;
                    const productItem = event.target.closest('.product-item');

                    // Ajouter ce produit au formulaire
                    addSelectedProduct(productId, productItem);
                }
            });
        });
    </script>
@endsection

@section('title', __('title.stock_new_supply'))
@section('description', __('description.stock_new_supply'))

@section('content')

    <h3>{{ __('title.stock_new_supply') }}</h3>

    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item">
                    <h3 class="product_name">{{ $product->product_name }}</h3>
                    <img class="product_image" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    <p><u>Catégorie(s) :</u>
                        @foreach($product->categories as $category)
                            <p>{{ $category->category_name }}</p>
                        @endforeach
                    </p>
                    <p>
                        <u>Fournisseur(s) :</u> {{ $product->supplyLines->first()->supply->supplier->supplier_name }}
                    </p>
                    <p>
                        <u>Quantité disponible :</u> {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
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