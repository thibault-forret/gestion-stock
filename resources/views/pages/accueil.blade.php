@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
@endsection

{{-- Faire en FR et EN (lang) --}}
@section('title', 'Accueil')
@section('description', 'Permet de sélectionner son moyen de connexion, entrepot ou magasin.')

@section('content')
    <div class="entrepot">
        <a href="{{ route('entrepot.login') }}">Entrepot</a>
    </div>

    <div class="magasin">
        <a href="{{ route('magasin.login') }}">Magasin</a>
    </div>
@endsection