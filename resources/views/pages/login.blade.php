@extends('layouts.app')

{{-- Retirer le header --}}
@php
    $login = true;    
@endphp

@section('css')
	<link href="{{ mix('css/pages/login.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.login'))
@section('description', __('description.login.' . $page))

@section('content') 

    <form method="POST" action="{{ route($page . '.login.submit') }}" autocomplete="on">
        @csrf

        <div class="content-form">
            <div class="info">
                <div class="container-img">
                    <img src="{{ asset('images/logoNova.png') }}" alt="Logo" class="logo">
                </div>
            </div>

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
            </div>
            
            <div class="language">
                @foreach($available_locales as $locale_name => $available_locale)
                    @switch($available_locale)
                        @case('fr')
                            @if($available_locale === $current_locale)
                                <img src="{{ asset('images/france.png') }}" alt="Français">
                            @else
                                <a href="{{ route('lang.switch', $available_locale) }}">
                                    <img src="{{ asset('images/france.png') }}" alt="Français">
                                </a>
                            @endif
                            @break
                        @case('en')
                            @if($available_locale === $current_locale)
                                <img src="{{ asset('images/etats-unis.png') }}" alt="English">
                            @else
                                <a href="{{ route('lang.switch', $available_locale) }}">
                                    <img src="{{ asset('images/etats-unis.png') }}" alt="English"></a>
                            @endif
                        @break
                    @endswitch
                @endforeach
            </div>

        </div>

    </form>
    
@endsection
