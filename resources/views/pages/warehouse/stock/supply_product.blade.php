@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/supply_product.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.stock_supply_product'))
@section('description', __('description.stock_supply_product'))
@section('parent-route', route('warehouse.stock.list'))
@section('title-content', mb_strtoupper(__('title.stock_supply_product')))

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">{{ __('Approvisionner un produit') }}</h3>
            </div>

            <div class="card-body p-4">
                {{-- Informations du produit --}}
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="col-lg-6 text-center">
                            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="img-fluid rounded border" style="max-width: 250px;">
                        </div>
                        <h4 class="text-muted"><strong>Nom du Produit :</strong></h4>
                        <p class="h5 font-weight-bold">{{ $product->product_name }}</p>

                        <h4 class="text-muted"><strong>Catégorie(s) :</strong></h4>
                            <ul class="list-group">
                                @foreach($product->categories as $category)
                                    <li class="list-group-item border-0">{{ $category->category_name }}</li>
                                @endforeach
                            </ul>

                        <h4 class="text-muted"><strong>Fournisseur :</strong></h4>
                        <p>{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</p>
                    </div>
                </div>

                {{-- Approvisionnement --}}
                <form action="{{ route('warehouse.stock.product.supply.submit') }}" method="POST">
                    @csrf

                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">

                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <h4 class="text-muted"><strong>Quantité disponible :</strong></h4>
                            <p class="h5">{{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <label for="quantity" class="form-label"><strong>Quantité à approvisionner :</strong></label>
                            <input type="number"
                                   id="quantity"
                                   name="quantity"
                                   value="{{ old('quantity') == null ? 1 : old('quantity') }}"
                                   min="1"
                                   max="{{ $total_quantity }}"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    {{-- Bouton Approvisionner --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-plus"></i> Approvisionner le produit
                        </button>
                    </div>
                </form>
            </div>

            {{-- Affichage des erreurs (si existant) --}}
            @if ($errors->any())
                <div class="card-footer bg-light text-center error-message">
                    @foreach ($errors->all() as $error)
                        <p class="text-danger mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
