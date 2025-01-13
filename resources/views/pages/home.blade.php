@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/pages/home.css') }}">
@endsection

@section('title', __('title.home'))

@section('content')
    <div class="container">
        <div class="role-selection">
            <div class="role-card">
                <a href="{{ route('warehouse.login') }}">
                    <div class="icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="role-title">{{ __('home.warehouse_service_title') }}</div>
                    <p class="role-description">{{ __('home.warehouse_service_description') }}</p>
                </a>
            </div>
            <div class="role-card">
                <a href="{{ route('store.login') }}">
                    <div class="icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="role-title">{{ __('home.store_service_title') }}</div>
                    <p class="role-description">{{ __('home.store_service_description') }}</p>
                </a>
            </div>
        </div>
    </div>
    <div class="thin-bar">
        2025 &bull; Nextgen Solutions
    </div>
@endsection
