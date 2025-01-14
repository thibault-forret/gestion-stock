@extends('layouts.app')

@section('css')
     <link href="{{ mix('css/pages/warehouse/search_new_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.search_new_product'))
@section('description', __('description.search_new_product'))
@section('parent-route', route('warehouse.dashboard'))
@section('title-content', mb_strtoupper(__('title.search_new_product')))

@section('content')

    <div class="filter-buttons">
        <form action="{{ route('warehouse.product.search') }}" method="GET">
            <div class="filter-group">
                <!-- Recherche par nom -->
                <div class="filter-item">
                    <label for="search_by_name">{{ __('search_product.search_by_name') }} :</label>
                    <input type="text" id="search-by-name" name="search_by_name" value="{{ request('search_by_name') }}">
                </div>

                <!-- Filtre par catégorie -->
                <div class="filter-item">
                    <label for="category_name">{{ __('search_product.categories') }} :</label>
                    <select id="category-name" name="category_name">
                        <option value="">{{ __('search_product.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_name }}"
                                {{ request('category_name') == $category->category_name ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre par fournisseur -->
                <div class="filter-item">
                    <label for="supplier_name">{{ __('search_product.suppliers') }} :</label>
                    <select id="supplier-name" name="supplier_name">
                        <option value="">{{ __('search_product.select_supplier') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_name }}"
                                {{ request('supplier_name') == $supplier->supplier_name ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pagination -->
                <div class="filter-item">
                    <label for="page_number">Page :</label>
                    <input type="number" id="page-number" name="page_number" value="{{ request('page_number') ?? 1 }}" min="1">
                </div>

                <div class="filter-item filter-actions">
                    <button type="submit" class="btn-filter">{{ __('search_product.search') }}</button>
                    <a href="{{ route('warehouse.product.index') }}" class="btn-reset">{{ __('search_product.reset') }}</a>
                </div>
            </div>
        </form>
    </div>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="product-list-wrapper">
        <div class="product-list">
            @if(isset($products) && count($products) > 0)
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-info">
                            <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="product-image">
                            <div class="product-details">
                                <h3>{{ $product['name'] }}</h3>
                                <p><strong>Catégories :</strong>
                                    @foreach($product['categories'] as $category)
                                        {{ $category->category_name }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                                <p><strong>Fournisseur :</strong> {{ $product['supplier']->supplier_name }}</p>
                            </div>
                        </div>
                        <!-- Actions placées à droite -->
                        <div class="product-actions">
                            <a href="{{ route('warehouse.product.add', ['product_id' => $product['id']]) }}" class="btn btn-success add-to-cart">
                                Ajouter le produit
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="text-align: center">Aucun produit trouvé.</p>
            @endif
        </div>
    </div>


@endsection
