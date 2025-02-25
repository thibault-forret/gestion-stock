@extends('layouts.app')

@php
    $removeHeader = true;
@endphp

@section('css')
    <link rel="stylesheet" href="{{ mix('css/pages/home.css') }}">
@endsection

@section('title', __('title.home'))

@section('content')
    <div class="container">
        <div class="header-section">
            <img src="/images/logoNova.png" alt="Logo Nova" class="logo">
            <div class="description">
                <h1>{{ __('home.welcome_title') }}</h1>
                <p>{{ __('home.welcome_message') }}</p>
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
        <form action="{{ route('lang.switch') }}" style="margin-bottom: 100px" method="GET">
            <select name="locale" id="lang-select" onchange="this.form.submit();">
                @foreach($available_locales as $locale_name => $available_locale)
                    <option value="{{ $available_locale }}" {{ $available_locale === $current_locale ? 'selected' : '' }}>
                        {{ ucfirst($locale_name) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="thin-bar">
        2025 &bull; Nextgen Solutions
    </div>
@endsection
