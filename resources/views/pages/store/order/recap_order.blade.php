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
        <h2 class="order-title">{{ __('title.recap_order') }}</h2>

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
                                <th>{{ __('order.actions') }}</th>
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
                                            <button type="submit" class="btn btn-retirer">{{ __('order.remove_product') }}</button>
                                        </form>
                                        <form action="{{ route('store.order.remove.quantity') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->quantity_ordered }}" required>
                                            <button type="submit" class="btn btn-warning">{{ __('order.remove_quantity') }}</button>
                                        </form>

                                        <form action="{{ route('store.order.add.quantity') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}" required>
                                            <button type="submit" class="btn btn-warning">{{ __('order.add_quantity') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="order-summary">
                    <div class="order-total">
                        <span class="total-label">{{ __('order.total_ht') }} :</span>
                        <span class="total-value">{{ number_format($total, 2) }} €</span>
                        <span class="total-label">{{ __('order.total_ttc') }} :</span>
                        <span class="total-value">{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</span>
                    </div>
                    <div class="confirm-order">
                        <form action="{{ route('store.order.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit" class="btn btn-success">{{ __('order.confirm_order') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
