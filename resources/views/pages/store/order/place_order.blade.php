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
    {{-- <link href="{{ mix('css/pages/store/order/place_order.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', __('title.place_order'))
@section('description', __('description.place_order.store'))

@section('content')
    
    {{ __('description.place_order.store') }}


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

                    <div class="buttons">
                        <form class="add-to-order-form" method="POST" action="{{ route('store.order.add') }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" class="quantity-input" value="1" 
                                min="1" max="{{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}" 
                                step="1" required>
                            <button type="submit" class="btn">Ajouter à la commande</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p>Aucun produit disponible.</p>
        @endif
    </div>

    <h3>Récapitulatif de la commande</h3>

    <div class="order-recap">
        
        @if(isset($order) && count($order->orderLines) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Faire le sans marge et avec marge de l'entrepot --}}
                    @foreach($order->orderLines as $orderLine)
                        <tr>
                            <td>{{ $orderLine->product->product_name }}</td>
                            <td>{{ $orderLine->quantity_ordered }}</td>
                            <td>{{ $orderLine->unit_price }} €</td>
                            <td>{{ $orderLine->quantity_ordered * $orderLine->unit_price }} €</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td>{{ $order->total }} €</td>
                    </tr>
                </tfoot>
            </table>
        @endif


    </div>

@endsection