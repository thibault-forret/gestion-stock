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
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/search-new-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', __('title.search_new_product'))
@section('description', __('description.search_new_product'))
@section('parent-route', route('warehouse.dashboard'))
@section('title-content', mb_strtoupper(__('title.search_new_product')))

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

    <form action="{{ route('warehouse.product.search') }}" method="GET">
        <div>
            <label for="search_by_name">Rechercher par nom</label>
            <input type="text" id="search-by-name" name="search_by_name" value="{{ request('search_by_name') }}">
        </div>
        <div>
            <label for="category_name">Catégorie :</label>
            <select id="category-name" name="category_name">
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_name }}" {{ request('category_name') == $category->category_name ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        
        </div>
        <div>
            <label for="supplier_name">Fournisseur :</label>
            <select id="supplier-name" name="supplier_name" required>
                <option value="">Sélectionner un fournisseur</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->supplier_name }}" {{ request('supplier_name') == $supplier->supplier_name ? 'selected' : '' }}>
                        {{ $supplier->supplier_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="page_number">Page</label>
            <input type="number" id="page-number" name="page_number" value="{{ request('page_number') == null ? 1 : request('page_number') }}" min="1">
        </div>
        <div>
            <button type="submit">Rechercher</button>
        </div>
    </form>

    <a href="{{ route('warehouse.product.index') }}">Rénitialiser recherche</a>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

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
                        <u>Fournisseur(s) :</u> {{ $product['supplier']->supplier_name }}
                    </p>

                    
                    <a href="{{ route('warehouse.product.add', ['product_id' => $product['id']]) }}" class="btn btn-primary">Ajouter le produit</a>
                </div>
            @endforeach
        @else
            <p>Aucun produit trouvé.</p>
        @endif
    </div>


@endsection