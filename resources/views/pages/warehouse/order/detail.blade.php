@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/recap.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.detail_order'))
@section('description', __('description.detail_order'))
@section('parent-route', route('warehouse.order.list'))
@section('title-content', mb_strtoupper(__('title.detail_order')))

@section('content')

    <div class="order-recap-container">
        <h2 class="order-title">{{ __('title.detail_order') }}</h2>

        @if(isset($order) && count($order->orderLines) > 0)
            <div class="order-details">
                <div class="scrollable">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>{{ __('order.product') }}</th>
                                <th>{{ __('order.name') }}</th>
                                <th>{{ __('order.quantity') }}</th>
                                <th>{{ __('order.unit_price') }}</th>
                                <th>{{ __('order.total_ht') }}</th>
                                <th>{{ __('order.total_ttc') }}</th>
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
                        <span class="total-label">{{ __('order.total_ht') }} :</span>
                        <span class="total-value">{{ number_format($order->calculateTotalPrice(), 2) }} €</span>
                        <span class="total-label">{{ __('order.total_ttc') }} :</span>
                        <span class="total-value">{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</span>
                    </div>

                </div>
            </div>
        @else
            <p class="empty-order">{{ __('order.no_product_in_order') }}</p>
        @endif
    </div>

@endsection
