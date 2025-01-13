@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/pages/home.css') }}">
@endsection

@section('title', __('title.supply'))
@section('description', __('description.supply'))
@section('parent-route', route('warehouse.stock.index'))
@section('title-content', mb_strtoupper(__('title.supply')))

@section('content')

    <div class="container">

        <div class="role-selection">
            <div class="role-card">
                <a href="{{ route('warehouse.stock.supply.new') }}">
                    <div class="icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="role-title">{{ __('title.new_supply') }}</div>
                    <p class="role-description">{{ __('description.new_supply') }}</p>
                </a>
            </div>
            <div class="role-card">
                <a href="{{ route('warehouse.stock.supply.list') }}">
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="role-title">{{ __('title.supply_list') }}</div>
                    <p class="role-description">{{ __('description.supply_list') }}</p>
                </a>
            </div>
        </div>

    </div>

@endsection