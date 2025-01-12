@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/place.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.place_order'))
@section('description', __('description.place_order.store'))

@section('content')
<div class="order-container">
    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available == 0) not-available @endif" data-id="{{ $product->id }}">
                    @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available == 0)
                        <div class="overlay">
                            <span>NON DISPONIBLE</span>
                        </div>
                    @endif
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
                    @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available != 0)
                        <p><u>Quantité disponible :</u> 
                            {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
                        </p>
                    @endif
                    <p><u>Prix unitaire HT :</u> 
                        <span class="product_price">{{ number_format($product->reference_price, 2) }} €</span>
                    </p>
                    <p><u>Prix unitaire TTC :</u> 
                        <span class="product_price">{{ number_format($product->reference_price * $warehouse->global_margin, 2, ',', ' ') }} €</span>
                    </p>

                    @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available != 0)  
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
                    @endif
                </div>
            @endforeach
        @else
            <p>Aucun produit disponible.</p>
        @endif
    </div>

    <div class="order-recap">
        <h3 class="order-title">Récapitulatif de la commande</h3>
    
        @if(isset($order) && count($order->orderLines) > 0)
            <div class="scrollable">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total HT</th>
                            <th>Total TTC</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach($order->orderLines as $orderLine)
                            @php
                                $total += $orderLine->quantity_ordered * $orderLine->unit_price;
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $orderLine->product->image_url }}" class="product-thumbnail" alt="Produit">
                                </td>
                                <td>{{ $orderLine->product->product_name }}</td>
                                <td>{{ $orderLine->quantity_ordered }}</td>
                                <td>{{ number_format($orderLine->unit_price, 2, ',', ' ') }} €</td>
                                <td>{{ number_format($orderLine->unit_price * $orderLine->quantity_ordered, 2, ',', ' ') }} €</td>                            
                                <td>{{ number_format($orderLine->unit_price * $orderLine->quantity_ordered * $warehouse->global_margin, 2, ',', ' ') }} €</td>
                                <td style="display: flex; flex-direction: column; justify-content: center;">
                                    <form action="{{ route('store.order.remove.product') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button type="submit" class="btn">Retirer</button>
                                    </form>

                                    <form action="{{ route('store.order.remove.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->quantity_ordered }}" required>
                                        <button type="submit" class="btn">Ajouter la quantité</button>
                                        <button type="submit" class="btn">Retirer la quantité</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            <div class="order-total">
                <span class="total-label">Total HT :</span>
                <span class="total-value">{{ number_format($total, 2) }} €</span>
                <span class="total-label">Total TTC :</span>
                <span class="total-value">{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</span>
            </div>

            <div class="confirm">
                <a class="btn" href="{{ route('store.order.recap', ['order_id' => $order->id]) }}">
                    Voir le récapitulatif
                </a>
            </div>
        @else
            <p class="empty-order">Aucune commande en cours.</p>
        @endif
    </div>
</div>
@endsection
