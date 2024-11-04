@extends('layouts.app')

{{-- Retirer le header --}}
@php
    $login = true;    
@endphp

@section('css')
	<link href="{{ mix('css/pages/login.css') }}" rel="stylesheet">
@endsection

@section('title', 'Se connecter')
@section('description', 'Se connecter à l\'application.')

@section('content') 

    <form method="POST" action="{{ route('magasin.login.post') }}" autocomplete="on">
        @csrf

        <div class="content-form">
            <div class="info">
                <div class="container-img">
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo de l'entreprise">
                </div>
            </div>
        
            <div class="item-form">
                <label for="username">Utilisateur</label>
                <input type="text" id="username" name="username" required autofocus autocomplete="username">
            </div>
        
            <div class="item-form">
                <label for="password">Mot de passe</label>
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
                <button class="login-button" type="submit">Connexion</button>
            </div>     
        </div>

    </form>
    
@endsection
