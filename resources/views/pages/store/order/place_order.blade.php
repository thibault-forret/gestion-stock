@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/supply/place.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.place_order'))
@section('description', __('description.place_order.store'))
@section('parent-route', route('store.order.index'))
@section('title-content', mb_strtoupper(__('title.place_order')))

@section('content')
<div class="order-container">
    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available == 0) not-available @endif" data-id="{{ $product->id }}">
                    @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available == 0)
                        <div class="overlay">
                            <span>{{ __('order.not_available') }}</span>
                        </div>
                    @endif
                    <h3 class="product_name">{{ $product->product_name }}</h3>
                    <img class="product_image" src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                    <p><u>{{ __('order.categories') }} :</u>
                        @foreach($product->categories as $category)
                            <span class="product_category">{{ $category->category_name }}</span>
                        @endforeach
                    </p>
                    <p><u>{{ __('order.id') }} :</u>
                        <span class="product_id">{{ $product->id }} :</span>
                    </p>
                    <p><u>{{ __('order.supplier') }} :</u>
                        <span class="product_supplier">{{ $product->supplyLines->first()->supply->supplier->supplier_name }}</span>
                    </p>
                    @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available != 0)
                        <p><u>{{ __('order.quantity_available') }} :</u>
                            {{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}
                        </p>
                    @endif
                    <p><u>{{ __('order.unit_price_ht') }} :</u>
                        <span class="product_price">{{ number_format($product->reference_price, 2) }} €</span>
                    </p>
                    <p><u>{{ __('order.unit_price_ttc') }} :</u>
                        <span class="product_price">{{ number_format($product->reference_price * $warehouse->global_margin, 2, ',', ' ') }} €</span>
                    </p>

                    @if($product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available != 0)
                        <div class="buttons">
                            <form class="add-to-supply-form" method="POST" action="{{ route('store.order.add') }}">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="quantity-picker">
                                    <button type="button" onclick="decrementQuantity(this)">-</button>
                                    <input type="number" name="quantity" class="quantity-input" value="1"
                                           min="1" max="{{ $product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}"
                                           step="1" required>
                                    <button type="button" onclick="incrementQuantity(this)">+</button>
                                </div>

                                <button type="submit" class="submit-btn">{{ __('order.add_to_order') }}</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p>{{ __('order.not_product_available') }}</p>
        @endif
    </div>

    <div class="order-recap">
        <h3 class="order-title">{{ __('order.recap_order') }}</h3>

        @if(isset($order) && count($order->orderLines) > 0)
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
                                <td>{{ number_format($orderLine->unit_price, 2, ',', ' ') }} €</td>
                                <td>{{ number_format($orderLine->unit_price * $orderLine->quantity_ordered, 2, ',', ' ') }} €</td>
                                <td>{{ number_format($orderLine->unit_price * $orderLine->quantity_ordered * $warehouse->global_margin, 2, ',', ' ') }} €</td>
                                <td style="display: flex; flex-direction: column; justify-content: center;">
                                    <form action="{{ route('store.order.remove.product') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button type="submit" class="btn" id="btn-retirer">{{ __('order.remove_product') }}</button>
                                    </form>

                                    <form action="{{ route('store.order.remove.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->quantity_ordered }}" required>
                                        <button type="submit" class="btn" id="btn-retirer-quantite">{{ __('order.remove_quantity') }}</button>
                                    </form>

                                    <form action="{{ route('store.order.add.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $orderLine->product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $orderLine->product->stocks->where('warehouse_id', $warehouse->id)->first()->quantity_available }}" required>
                                        <button type="submit" class="btn">{{ __('order.add_quantity') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="order-total">
                <span class="total-label">{{ __('order.total_ht') }} :</span>
                <span class="total-value">{{ number_format($total, 2) }} €</span>
                <span class="total-label">{{ __('order.total_ttc') }} :</span>
                <span class="total-value">{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</span>
            </div>

            <div class="confirm">
                <a class="btn" href="{{ route('store.order.recap', ['order_id' => $order->id]) }}" id="btn-recapitulatif">
                    {{ __('order.see_recap') }}
                </a>
            </div>
        @else
            <p class="empty-order">{{ __('order.no_order_in_progress') }}</p>
        @endif
    </div>
</div>
<script>
    function incrementQuantity(btn) {
        const input = btn.parentElement.querySelector('input[type="number"]');
        const max = parseInt(input.max);
        const value = parseInt(input.value);
        if (value < max) {
            input.value = value + 1;
        }
    }

    function decrementQuantity(btn) {
        const input = btn.parentElement.querySelector('input[type="number"]');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endsection
