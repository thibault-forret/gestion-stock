@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/supply/index.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.new_supply'))
@section('description', __('description.new_supply'))
@section('parent-route', route('warehouse.stock.supply.index'))
@section('title-content', mb_strtoupper(__('title.new_supply')))

@section('content')

    @if($suppliers->isEmpty())
        <p class="no-suppliers">{{ __('supply.no_product_in_warehouse') }}</p>
        <a href="{{ route('warehouse.product.index')}}">{{ __('supply.add_products') }}</a>
    @endif

    <div class="role-selection">

        <h3 class="title">{{ __('supply.select_supplier') }}</h3>

        @foreach($suppliers as $supplier)
            <div class="role-card">
                <form action="{{ route('warehouse.stock.supply.place.new') }}" method="post">
                    @csrf
                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                    <button type="submit">
                        <div class="icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="role-title">{{ $supplier->supplier_name }}</div>
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
