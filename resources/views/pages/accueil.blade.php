@extends('layouts.app')

{{-- Faire en FR et EN (lang) --}}
@section('title', 'Accueil')
@section('description', 'Permet de sélectionner son moyen de connexion, entrepot ou magasin.')

@section('content')
    <div class="entrepot">
        <a href="{{ route('entrepot.login') }}">Se connecter à l'entrepot</a>
    </div>

    <div class="magasin">
        <a href="{{ route('magasin.login') }}">Se connecter au magasin</a>
    </div>
@endsection