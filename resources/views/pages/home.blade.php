@extends('layouts.app')

@php
    $removeHeader = true;
@endphp

@section('css')
    <link rel="stylesheet" href="{{ mix('css/pages/home.css') }}">
@endsection

@section('title', __('home.welcome_title'))

@section('content')
    <div class="container">
        <div class="header-section">
            <img src="/images/logoNova.png" alt="Logo Nova" class="logo">
            <div class="description">
                <h1>{{ __('home.welcome_title') }}</h1>
                <p>{{ __('home.welcome_message') }}</p>
            </div>
            <div class="language-switcher">
                <a href="{{ route('lang.switch', 'en') }}" class="btn-lang {{ $current_locale === 'en' ? 'active' : '' }}">English</a>
                <a href="{{ route('lang.switch', 'fr') }}" class="btn-lang {{ $current_locale === 'fr' ? 'active' : '' }}">Français</a>
            </div>
        </div>
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
