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

@section('title', __('title.warehouse_stock_list'))
@section('description', __('description.warehouse_stock_list'))

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

    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item">
                    <h3>{{ $product->product_name }}</h3>
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    <p><u>Catégorie(s) :</u>
                        @foreach($product->categories as $category)
                            <p>{{ $category->category_name }}</p>
                        @endforeach
                    </p>
                    <p>
                        <u>Fournisseur(s) :</u> {{ $product->supplyLines->first()->supply->supplier->supplier_name }}
                    </p>

                    @php
                        $stock = $product->stocks->where('warehouse_id', $warehouse->id)->first();
                    @endphp

                    <a href="{{ route('warehouse.stock.info', ['stock_id' => $stock->id]) }}" class="btn btn-primary">Informations</a>
                    <a href="{{ route('warehouse.stock.edit', ['stock_id' => $stock->id]) }}" class="btn btn-primary">Modifier</a>
                    <a href="{{ route('warehouse.stock.supply', ['stock_id' => $stock->id]) }}" class="btn btn-primary">Approvisionner</a>
                    <a href="{{ route('warehouse.stock.remove', ['stock_id' => $stock->id]) }}" class="btn btn-primary">Retirer</a>
                </div>
            @endforeach
        @else
            <p>Aucun produit dans le stock.</p>
        @endif
    </div>


@endsection