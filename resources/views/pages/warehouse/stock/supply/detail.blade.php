@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/recap.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.detail_supply'))
@section('description', __('description.detail_supply'))
@section('parent-route', route('warehouse.stock.supply.list'))
@section('title-content', mb_strtoupper(__('title.detail_supply')))

@section('content')

    <div class="order-recap-container">
        <h2 class="order-title">{{ __('title.detail_supply') }}</h2>
    
        @if(isset($supply) && count($supply->supplyLines) > 0)
            <div class="order-details">
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    
                <div class="order-summary">
                    <div class="order-total">
                        <span class="total-label">Total :</span>
                        <span class="total-value">{{ number_format($supply->calculateTotalPrice(), 2) }} €</span>
                    </div>
                </div>
            </div>
        @endif
    </div>    

@endsection