@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/info_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_edit_product'))
@section('description', __('description.stock_edit_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_edit_product')))

@section('content')
    <div class="main-container">
        <div class="product-info">
            <div class="header">
                <h2>{{ __('Modifier les informations du Produit') }}</h2>
            </div>

            <div class="content">
                <div class="product-details">
                    <div class="image-container">
                        <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                    </div>

                    <div class="text-info">
                        <div class="info-block">
                            <h3>{{ $product->product_name }}</h3>
                            <p class="supplier">{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</p>
                        </div>

                        <div class="info-block categories">
                            <h4>Catégories</h4>
                            @if ($product->categories->isNotEmpty())
                                <div class="category-tags">
                                    @foreach($product->categories as $category)
                                        <span class="tag">{{ $category->category_name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p>Aucune catégorie associée</p>
                            @endif
                        </div>

                        <form action="{{ route('warehouse.stock.product.edit.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">

                            <div class="info-block stock">
                                <div class="stock-item">
                                    <label for="alert-threshold" class="label">Seuil d'alerte</label>
                                    <input type="number" id="alert-threshold" name="alert_threshold"
                                           value="{{ old('alert_threshold') ?? $stock->alert_threshold }}"
                                           min="1" required>
                                </div>
                                <div class="stock-item">
                                    <label for="restock-threshold" class="label">Seuil de réapprovisionnement</label>
                                    <input type="number" id="restock-threshold" name="restock_threshold"
                                           value="{{ old('restock_threshold') ?? $stock->restock_threshold }}"
                                           min="0" required>
                                </div>
                                <div class="stock-item">
                                    <label for="auto-restock-quantity" class="label">Quantité auto réapprovisionnement</label>
                                    <input type="number" id="auto-restock-quantity" name="auto_restock_quantity"
                                           value="{{ old('auto_restock_quantity') ?? $stock->auto_restock_quantity }}"
                                           min="1" required>
                                </div>
                            </div>

                            <div class="actions">
                                <button type="submit" class="btn edit">Sauvegarder les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div class="error-messages">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
