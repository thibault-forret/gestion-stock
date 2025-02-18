@extends('layouts.app')

{{-- Retirer le header --}}
@php
    $removeHeader = true;
@endphp

@section('css')
	<link href="{{ mix('css/pages/login.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.login'))
@section('description', __('description.login.' . $page))

@section('content')

    <div class="content-form">
        <form class="login-form" method="POST" action="{{ route($page . '.login.submit') }}" autocomplete="on">
            @csrf

            <div class="info">
                <div class="container-img">
                    <img src="{{ asset('images/logoNova.png') }}" alt="Logo" class="logo">
                </div>
            </div>

            @if($page === 'warehouse')
                <div class="title-page">{{ __('auth.warehouse_form') }}</div>
            @elseif($page === 'store')
                <div class="title-page">{{ __('auth.store_form') }}</div>
            @endif

            <div class="item-form">
                <label for="username">{{__('auth.username') }}</label>
                <input type="text" id="username" name="username" required autofocus autocomplete="username">
            </div>

            <div class="item-form">
                <label for="user_password">{{__('auth.password') }}</label>
                <input type="password" id="user_password" name="user_password" required autocomplete="current-password">
            </div>

            @if ($errors->any())
                <div class="center-child error-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="center-child">
                <button class="login-button" type="submit">{{__('auth.login') }}</button>
                <a href="{{ route('index') }}">{{__('basics.return')}}</a>
            </div>

        </form>

        <div class="language">
            <form action="{{ route('lang.switch') }}" method="GET">
                <select name="locale" id="lang-select" onchange="this.form.submit();">
                    @foreach($available_locales as $locale_name => $available_locale)
                        <option value="{{ $available_locale }}" {{ $available_locale === $current_locale ? 'selected' : '' }}>
                            {{ ucfirst($locale_name) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

@endsection
