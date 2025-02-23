@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/info_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_info_product'))
@section('description', __('description.stock_info_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_info_product')))

@section('content')
    <div class="main-container">
        <div class="product-info">
            <div class="header">
                <h2>{{ __('Informations sur le Produit') }}</h2>
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
                            <div class="stock-item">
                                <span class="label">Seuil d'Alerte</span>
                                <span class="value">{{ $stock->alert_threshold }}</span>
                            </div>
                            <div class="stock-item">
                                <span class="label">Seuil de Réapprovisionnement</span>
                                <span class="value">{{ $stock->restock_threshold }}</span>
                            </div>
                            <div class="stock-item">
                                <span class="label">Quantité de Réapprovisionnement</span>
                                <span class="value">{{ $stock->auto_restock_quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('warehouse.stock.product.edit', ['product_id' => $stock->product_id]) }}" class="btn edit">
                    Modifier
                </a>
                <a href="{{ route('warehouse.stock.product.supply', ['product_id' => $stock->product_id]) }}" class="btn supply">
                    Approvisionner
                </a>
                <a href="{{ route('warehouse.stock.product.remove', ['product_id' => $stock->product_id]) }}" class="btn remove">
                    Retirer
                </a>
            </div>
        </div>
    </div>
@endsection
