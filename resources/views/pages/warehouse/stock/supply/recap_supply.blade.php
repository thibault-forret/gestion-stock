@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/recap.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.recap_supply'))
@section('description', __('description.recap_supply'))
@section('parent-route', route('warehouse.stock.supply.place', ['supply_id' => $supply->id]))
@section('title-content', mb_strtoupper(__('title.recap_supply')))

@section('content')

    <div class="order-recap-container">
        <h2 class="order-title">{{ __('title.recap_supply') }}</h2>

        @if(isset($supply) && count($supply->supplyLines) > 0)
            <div class="order-details">
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
                                    <td>{{ number_format($supplyLine->unit_price, 2) }} €</td>
                                    <td>{{ number_format($supplyLine->unit_price * $supplyLine->quantity_supplied, 2, ',', ' ') }} €</td>
                                    <td>
                                        <form action="{{ route('warehouse.stock.supply.remove.product') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                            <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                            <button type="submit" class="btn btn-danger">{{ __('order.remove_product') }}</button>
                                        </form>
                                        <form action="{{ route('warehouse.stock.supply.remove.quantity') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                            <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $supplyLine->quantity_supplied }}" required>
                                            <button type="submit" class="btn btn-warning">{{ __('order.remove_quantity') }}</button>
                                        </form>

                                        <form action="{{ route('warehouse.stock.supply.add.quantity') }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                            <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $total_quantity }}" required>
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
                        <span class="total-label">{{ __('invoice.total') }} :</span>
                        <span class="total-value">{{ number_format($total, 2) }} €</span>
                    </div>
                    <div class="confirm-order">
                        <form action="{{ route('warehouse.stock.supply.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                            <button type="submit" class="btn btn-success">{{ __('supply.confirm_supply') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
