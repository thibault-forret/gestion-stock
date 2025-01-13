@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/pages/home.css') }}">
@endsection

@section('title', __('title.stock'))
@section('description', __('description.stock'))
@section('parent-route', route('warehouse.dashboard'))
@section('title-content', mb_strtoupper(__('title.stock')))

@section('content')

    <div class="container">

        <div class="role-selection">
            <div class="role-card">
                <a href="{{ route('warehouse.stock.supply.index') }}">
                    <div class="icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="role-title">{{ __('title.stock_supply_product') }}</div>
                    <p class="role-description">{{ __('description.stock_supply_product') }}</p>
                </a>
            </div>
            <div class="role-card">
                <a href="{{ route('warehouse.stock.list') }}">
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="role-title">{{ __('title.warehouse_stock_list') }}</div>
                    <p class="role-description">{{ __('description.warehouse_stock_list') }}</p>
                </a>
            </div>
            <div class="role-card">
                <a href="{{ route('warehouse.stock.list.movement') }}">
                    <div class="icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="role-title">{{ __('title.warehouse_stock_movement_list') }}</div>
                    <p class="role-description">{{ __('description.warehouse_stock_movement_list') }}</p>
                </a>
            </div>
        </div>

    </div>

@endsection