@extends('layouts.app')

@section('css')
    <style>
        .order-recap-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
            font-weight: bold;
        }

        .order-details {
            margin-top: 20px;
        }

        .scrollable {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-table th, .order-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 0.9rem;
        }

        .order-table th {
            background: #007bff;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .order-table tr:hover {
            background: #f9f9f9;
        }

        .product-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .order-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .total-label {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .total-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }

        .confirm-order {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .confirm-order .btn {
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-order .btn-secondary {
            color: #8c8c8c;
            border: none;
            margin-right: 10px;
            transition: color 0.3s ease;
        }

        .confirm-order .btn-secondary:hover {
            color: #4d4d4d;
        }

        .confirm-order .btn-success {
            background-color: #28a745;
            color: #fff;
            border: none;
        }

        .confirm-order .btn-success:hover {
            background-color: #218838;
        }

        .confirm-order .btn:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .empty-order {
            text-align: center;
            font-size: 1rem;
            color: #999;
            margin: 20px 0;
        }

        .inline-form {
            display: inline-block;
            margin-right: 10px;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: #fff;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #212529;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-warning:hover {
            background: #e0a800;
        }
    </style>


    {{-- <link href="{{ mix('css/pages/store/order/recap.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', 'Détail de la commande')
@section('description', __('description.recap_order'))

@section('content')
    
    {{ __('description.recap_order') }}

    <div class="order-recap-container">
        <h2 class="order-title">Détail de la commande</h2>
    
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
                <div class="order-summary">
                    <div class="order-total">
                        <span class="total-label">Total HT :</span>
                        <span class="total-value">{{ number_format($order->calculateTotalPrice(), 2) }} €</span>
                        <span class="total-label">Total TTC :</span>
                        <span class="total-value">{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</span>
                    </div>
                    <div class="confirm-order">
                        <a href="{{ route('store.order.list') }}" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
            </div>
        @endif
    </div>    

@endsection