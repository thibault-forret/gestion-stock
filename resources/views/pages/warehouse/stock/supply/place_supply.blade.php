@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/supply/place.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.place_supply'))
@section('description', __('description.place_order.warehouse'))
@section('parent-route', route('warehouse.stock.supply.index'))
@section('title-content', mb_strtoupper(__('title.place_supply')))

@section('content')
<div class="order-container">
    <div class="product-list">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product-item" data-id="{{ $product->id }}">
                    <h3 class="product_name">{{ $product->product_name }}</h3>
                    <img class="product_image" src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                    <p><u>{{ __('order.categories') }} :</u>
                        @foreach($product->categories as $category)
                            <span class="product_category">{{ $category->category_name }}</span>
                        @endforeach
                    </p>
                    <p><u>{{ __('order.id') }} :</u>
                        <span class="product_id">{{ $product->id }}</span>
                    </p>
                    <p><u>{{ __('order.unit_price') }} :</u>
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
                            <button type="submit" class="btn">{{ __('supply.add_to_supply') }}</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p>{{ __('order.not_product_available') }}</p>
        @endif
    </div>

    <div class="order-recap">
        <h3 class="order-title">{{ __('supply.recap_supply') }}</h3>

        @if(isset($supply) && count($supply->supplyLines) > 0)
            <div class="scrollable">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>{{ __('order.product') }}</th>
                            <th>{{ __('order.name') }}</th>
                            <th>{{ __('order.quantity') }}</th>
                            <th>{{ __('order.unit_price') }}</th>
                            <th>{{ __('invoice.total') }}</th>
                            <th>{{ __('order.actions') }}</th>
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
                                        <button type="submit" class="btn" id="btn-retirer">{{ __('order.remove_product') }}</button>
                                    </form>

                                    <form action="{{ route('warehouse.stock.supply.remove.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $supplyLine->quantity_supplied }}" required>
                                        <button type="submit" class="btn" id="btn-retirer-quantite">{{ __('order.remove_quantity') }}</button>
                                    </form>

                                    <form action="{{ route('warehouse.stock.supply.add.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $total_quantity }}" required>
                                        <button type="submit" class="btn">{{ __('order.add_quantity') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="order-total">
                <span class="total-label">{{ __('invoice.total') }} :</span>
                <span class="total-value">{{ number_format($total, 2) }} €</span>
            </div>

            <div class="confirm">
                <a class="btn" href="{{ route('warehouse.stock.supply.recap', ['supply_id' => $supply->id]) }}">
                    {{ __('order.see_recap') }}
                </a>
            </div>
        @else
            <p class="empty-order">{{ __('supply.no_supply_in_progress') }}</p>
        @endif
    </div>
</div>

@endsection
