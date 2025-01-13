@extends('layouts.app')

@section('css')
    <style>
        .product-item {
            position: relative;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            overflow: hidden;
            transition: 0.3s ease;
        }

        .product-item.not-available {
            opacity: 0.6; /* Rendre moins visible */
        }

        .product-item .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2;
            transform-origin: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 3rem;
            font-weight: bold;
            pointer-events: none; /* Empêche l'overlay d'interférer avec les clics */
        }

        .product-item.not-available .overlay span {
            white-space: nowrap;
            color: rgba(255, 255, 255, 0.6);
            transform: rotate(-70deg); /* Corrige l'angle du texte */
        }

        .product-item .product_name,
        .product-item .product_image,
        .product-item p {
            position: relative;
            z-index: 1; /* Pour s'assurer que le contenu reste visible sous l'overlay */
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

        .order-title {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .order-recap {
            max-width: 1000px;
            margin: 50px auto;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #333;
        }

        .order-table thead {
            background: #007bff;
            color: #fff;
        }

        .order-table th, 
        .order-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .order-table th {
            font-weight: bold;
            text-transform: uppercase;
        }

        .order-table tbody tr:hover {
            background: #f1f1f1;
        }

        .order-table tfoot {
            font-weight: bold;
            background: #f7f7f7;
        }

        .total-label {
            text-align: right;
            font-size: 1rem;
        }

        .total-value {
            font-size: 1.2rem;
            color: #007bff;
        }

        .product-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .empty-order {
            text-align: center;
            font-size: 1rem;
            color: #999;
            margin: 20px 0;
        }

        .scrollable {
            max-height: 300px; /* Limite la hauteur pour activer le scroll */
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .scrollable::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }

        .scrollable::-webkit-scrollbar-thumb {
            background: #007bff;
            border-radius: 8px;
        }

        .scrollable::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
        }

        .order-total {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .total-label {
            margin-right: 10px;
        }

        .total-value {
            color: #007bff;
        }
    </style>
    {{-- <link href="{{ mix('css/pages/store/order/place_order.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', __('title.place_order'))
@section('description', __('description.place_order.store'))
@section('parent-route', route('warehouse.stock.supply.list'))
@section('title-content', strtoupper(__('title.place_order')))

@section('content')
    
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
                    <p><u>Prix unitaire :</u> 
                        <span class="product_price">{{ number_format($product->reference_price, 2) }} €</span>
                    </p>

                    <div class="buttons">
                        <form class="add-to-supply-form" method="POST" action="{{ route('warehouse.stock.supply.add') }}">
                            @csrf
                            <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" class="quantity-input" value="1" 
                                min="1" max="{{ $total_quantity }}" 
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

    <div class="order-recap">
        <h3 class="order-title">Récapitulatif de la commande</h3>
    
        @if(isset($supply) && count($supply->supplyLines) > 0)
            <div class="scrollable">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach($supply->supplyLines as $supplyLine)
                            @php
                                $total += $supplyLine->quantity_supplied * $supplyLine->unit_price;
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $supplyLine->product->image_url }}" class="product-thumbnail" alt="Produit">
                                </td>
                                <td>{{ $supplyLine->product->product_name }}</td>
                                <td>{{ $supplyLine->quantity_supplied }}</td>
                                <td>{{ number_format($supplyLine->unit_price, 2, ',', ' ') }} €</td>
                                <td>{{ number_format($supplyLine->unit_price * $supplyLine->quantity_supplied, 2, ',', ' ') }} €</td>                            
                                <td style="display: flex; flex-direction: column; justify-content: center;">
                                    <form action="{{ route('warehouse.stock.supply.remove.product') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <button type="submit" class="btn">Retirer</button>
                                    </form>

                                    <form action="{{ route('warehouse.stock.supply.remove.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $supplyLine->quantity_supplied }}" required>
                                        <button type="submit" class="btn">Retirer la quantité</button>
                                    </form>

                                    <form action="{{ route('warehouse.stock.supply.add.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $total_quantity }}" required>
                                        <button type="submit" class="btn">Ajouter la quantité</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            <div class="order-total">
                <span class="total-label">Total :</span>
                <span class="total-value">{{ number_format($total, 2) }} €</span>
            </div>

            <div class="confirm">
                <a class="btn" href="{{ route('warehouse.stock.supply.recap', ['supply_id' => $supply->id]) }}">
                    Voir le récapitulatif
                </a>
            </div>
        @else
            <p class="empty-order">Aucune commande en cours.</p>
        @endif
    </div>
    

@endsection             