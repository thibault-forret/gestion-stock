@extends('layouts.app')

{{-- Retirer le header --}}
@php
    $login = true;    
@endphp

@section('css')
	<link href="{{ mix('css/pages/login.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.login'))
@section('description', __('description.login.warehouse'))

@section('content') 

    <form method="POST" action="{{ route('warehouse.login.post') }}" autocomplete="on">
        @csrf

        <div class="content-form">
            <div class="info">
                <div class="container-img">
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo">
                </div>
            </div>
        
            <div class="item-form">
                <label for="user_email">{{__('auth.email') }}</label>
                <input type="email" id="user_email" name="user_email" required autofocus autocomplete="email">
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
        </div>

    </form>
    
@endsection
