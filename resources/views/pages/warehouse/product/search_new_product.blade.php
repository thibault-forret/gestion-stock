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
                <div class="filter-item">
                    <label for="search_by_name">{{ __('search_product.search_by_name') }} :</label>
                    <input type="text" id="search-by-name" name="search_by_name" value="{{ request('search_by_name') }}">
                </div>

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

                <div class="filter-item">
                    <label for="page_number">Page :</label>
                    <input type="number" id="page-number" name="page_number" value="{{ request('page_number') ?? 1 }}" min="1">
                </div>

                <div class="filter-item filter-actions">
                    <a href="{{ route('warehouse.product.index') }}" class="btn-reset">{{ __('search_product.reset') }}</a>
                    <button type="submit" class="btn-filter">{{ __('search_product.search') }}</button>
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

        @if(isset($products) && count($products) > 0)
            <div class="pagination-wrapper-top">
                <div class="pagination-controls">
                    @if((request('page_number') ?? 1) > 1)
                        <a href="{{ route('warehouse.product.search', array_merge(request()->query(), ['page_number' => (request('page_number') ?? 1) - 1])) }}" class="pagination-link">&laquo; Précédent</a>
                    @endif

                    @if(count($products) >= 24)
                        <a href="{{ route('warehouse.product.search', array_merge(request()->query(), ['page_number' => (request('page_number') ?? 1) + 1])) }}" class="pagination-link">Suivant &raquo;</a>
                    @endif
                </div>
                <div class="pagination-info">
                    @php
                        $currentPage = request('page_number') ?? 1;
                        $itemsPerPage = 24;
                        $start = ($currentPage - 1) * $itemsPerPage + 1;
                        $end = min($start + count($products) - 1, $currentPage * $itemsPerPage);
                    @endphp
                    Affichage des produits {{ $start }} à {{ $end }} sur la page {{ $currentPage }}
                </div>
            </div>
        @endif

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
                        <div class="product-actions">
                            <a href="{{ route('warehouse.product.add', ['product_id' => $product['id']]) }}" class="add-to-cart">
                                <i class="fa-solid fa-cart-shopping"></i> Ajouter le produit
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="no-results">Aucun produit trouvé.</p>
            @endif
        </div>

        @if(isset($products) && count($products) > 0)
            <div class="pagination-wrapper-bottom">
                <div class="pagination-info">
                    @php
                        $currentPage = request('page_number') ?? 1;
                        $itemsPerPage = 24;
                        $start = ($currentPage - 1) * $itemsPerPage + 1;
                        $end = min($start + count($products) - 1, $currentPage * $itemsPerPage);
                    @endphp
                    Affichage des produits {{ $start }} à {{ $end }} sur la page {{ $currentPage }}
                </div>
                <div class="pagination-controls">
                    @if((request('page_number') ?? 1) > 1)
                        <a href="{{ route('warehouse.product.search', array_merge(request()->query(), ['page_number' => (request('page_number') ?? 1) - 1])) }}" class="pagination-link">&laquo; Précédent</a>
                    @endif

                    @if(count($products) >= 24)
                        <a href="{{ route('warehouse.product.search', array_merge(request()->query(), ['page_number' => (request('page_number') ?? 1) + 1])) }}" class="pagination-link">Suivant &raquo;</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
