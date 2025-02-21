@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/pages/warehouse/add_product.css') }}">
@endsection

@section('title', __('title.add_product'))
@section('description', __('description.add_product'))
@section('parent-route', route('warehouse.product.index'))
@section('title-content', mb_strtoupper(__('title.add_product')))

@section('content')

    <div class="main-container">
        <div class="product-info">
            <div class="text-container">
                <h2>{{ __('add_product.info_product') }}</h2>
                <p>{{ __('add_product.name') }} : <strong> {{ $product['name'] }}</strong></p>
                <p>{{ __('add_product.supplier') }} : <strong> {{ $product['supplier']->supplier_name }}</strong></p>
                <p>{{ __('add_product.categories') }} :
                    <strong>
                        @foreach($product['categories'] as $category)
                            {{ $category->category_name }}
                        @endforeach
                    </strong>
                </p>
            </div>

            <div>
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
            </div>
        </div>

        <form action="{{ route('warehouse.product.add.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product['id'] }}">

            <div class="form-fields">
                <h2>{{ __('add_product.parameters') }}</h2>
                <label for="quantity">&#x1F4E6 {{ __('add_product.quantity') }} :</label>
                <div class="quantity-picker">
                    <button type="button" onclick="decrementQuantity()">-</button>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                    <button type="button" onclick="incrementQuantity()">+</button>
                </div>
            </div>

            <div class="form-fields">
                <label for="alert_threshold">&#x1F514 {{ __('add_product.alert_threshold') }} :</label>
                <input type="number" id="alert-threshold" name="alert_threshold" value="{{ old('alert_threshold', 1) }}" min="1" required>
            </div>

            <div class="form-fields">
                <label for="restock_threshold">&#x1F504 {{ __('add_product.restock_threshold') }} :</label>
                <input type="number" id="restock-threshold" name="restock_threshold" value="{{ old('restock_threshold', 0) }}" min="0" required>
            </div>

            <div class="form-fields">
                <label for="auto_restock_quantity">&#x2705 {{ __('add_product.auto_restock_quantity') }} :</label>
                <input type="number" id="auto-restock-quantity" name="auto_restock_quantity" value="{{ old('auto_restock_quantity', 1) }}" min="1" required>
            </div>

            <button type="submit">{{ __('add_product.add_product') }}</button>
        </form>

        @if ($errors->any())
            <div class="center-child error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <div class="info-message">
        <h2>{{ __('add_product.info') }}</h2>
        <p><strong>{{ __('add_product.quantity') }} :</strong> {{ __('add_product.quantity_info') }}</p>
        <p><strong>{{ __('add_product.alert_threshold') }} :</strong> {{ __('add_product.alert_threshold_info') }}</p>
        <p><strong>{{ __('add_product.restock_threshold') }} :</strong> {{ __('add_product.restock_threshold_info') }}</p>
        <p><strong>{{ __('add_product.auto_restock_quantity') }} :</strong> {{ __('add_product.auto_restock_quantity_info') }}</p>
    </div>

    <script>
        function incrementQuantity() {
            var quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decrementQuantity() {
            var quantityInput = document.getElementById('quantity');
            if (quantityInput.value > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }
    </script>
@endsection
