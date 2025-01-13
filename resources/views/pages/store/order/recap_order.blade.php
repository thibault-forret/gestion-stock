@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/recap.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.recap_order'))
@section('description', __('description.recap_order'))
@section('parent-route', route('store.order.place', ['order_id' => $order->id]))
@section('title-content', mb_strtoupper(__('title.recap_order')))

@section('content')

    <div class="order-recap-container">
        <h2 class="order-title">Récapitulatif de la commande</h2>
    
        @if(isset($order) && count($order->orderLines) > 0)
            <div class="order-details">
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
                                    <td>{{ number_format($orderLine->unit_price, 2) }} €</td>
                                    <td>{{ number_format($orderLine->unit_price * $orderLine->quantity_ordered, 2, ',', ' ') }} €</td>                            
                                    <td>{{ number_format($orderLine->unit_price * $orderLine->quantity_ordered * $warehouse->global_margin, 2, ',', ' ') }} €</td> 
                                    <td>
                                        <form action="{{ route('store.order.remove.product') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button type="submit" class="btn btn-danger">Retirer</button>
                                        </form>
                                        <form action="{{ route('store.order.remove.quantity') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->quantity_ordered }}" required>
                                            <button type="submit" class="btn btn-success">Ajouter quantité</button>
                                            <button type="submit" class="btn btn-warning">Retirer quantité</button>
                                        </form>

                                        <form action="{{ route('store.order.add.quantity') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}" required>
                                            <button type="submit" class="btn btn-warning">Ajouter quantité</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
                <div class="order-summary">
                    <div class="order-total">
                        <span class="total-label">Total HT :</span>
                        <span class="total-value">{{ number_format($total, 2) }} €</span>
                        <span class="total-label">Total TTC :</span>
                        <span class="total-value">{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</span>
                    </div>
                    <div class="confirm-order">
                        <form action="{{ route('store.order.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit" class="btn btn-success">Confirmer la commande</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>    

@endsection