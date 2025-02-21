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
                                <div class="quantity-picker">
                                    <button type="button" onclick="decrementQuantity(this)">-</button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $total_quantity }}" required>
                                    <button type="button" onclick="incrementQuantity(this)">+</button>
                                </div>
                                <button type="submit" class="submit-btn">Ajouter à l'approvisionnement</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Aucun produit disponible.</p>
            @endif
        </div>

        <div class="order-recap">
            <h3 class="order-title">Récapitulatif de l'approvisionnement</h3>

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
                            <th>Actions</th>
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
                                        <button type="submit" class="btn" id="btn-retirer">Retirer</button>
                                    </form>

                                    <form action="{{ route('warehouse.stock.supply.remove.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <div class="quantity-picker">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $supplyLine->quantity_supplied }}" required>

                                        </div>
                                        <button type="submit" class="btn" id="btn-retirer-quantite">Retirer la quantité</button>
                                    </form>

                                    <form action="{{ route('warehouse.stock.supply.add.quantity') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $supplyLine->product->id }}">
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <div class="quantity-picker">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $total_quantity }}" required>
                                        </div>
                                        <button type="submit" class="btn" id="btn-add-quantity">Ajouter la quantité</button>
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
                <p class="empty-order">Aucun approvisionnement en cours.</p>
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
