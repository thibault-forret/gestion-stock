@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/info_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_remove_product'))
@section('description', __('description.stock_remove_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_remove_product')))

@section('content')
    <div class="main-container">
        <div class="product-info">
            <div class="header">
                <h2>{{ __('Retirer un produit du Stock') }}</h2>
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

                        <div class="info-block stock">
                            <div class="stock-item">
                                <span class="label">Quantité Disponible</span>
                                <span class="value">{{ $stock->quantity_available }}</span>
                            </div>
                        </div>

                        <form action="{{ route('warehouse.stock.product.remove.quantity.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">

                            <div class="info-block stock">
                                <div class="stock-item">
                                    <label for="quantity" class="label">Quantité à retirer</label>
                                    <input type="number" id="quantity" name="quantity"
                                           value="{{ old('quantity') ?? 1 }}"
                                           min="1" required>
                                </div>
                            </div>

                            <div class="actions">
                                <button type="submit" class="btn remove">Retirer la quantité</button>
                            </div>
                        </form>

                        <form action="{{ route('warehouse.stock.product.remove.product.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                            <div class="actions">
                                <button type="submit" class="btn remove">Retirer le Produit</button>
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
