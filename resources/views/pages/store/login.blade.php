@extends('layouts.app')

{{-- Retirer le header --}}
@php
    $login = true;    
@endphp

@section('css')
	<link href="{{ mix('css/pages/login.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.login'))
@section('description', __('description.login.store'))

@section('content') 

    <form method="POST" action="{{ route('store.login.post') }}" autocomplete="on">
        @csrf

        <div class="content-form">
            <div class="info">
                <div class="container-img">
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo">
                </div>
            </div>
        
            <div class="item-form">
                <label for="username">{{__('auth.username') }}</label>
                <input type="text" id="username" name="username" required autofocus autocomplete="username">
            </div>
        
            <div class="item-form">
                <label for="password">{{__('auth.password') }}</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
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
        </div>

    </form>
    
@endsection
