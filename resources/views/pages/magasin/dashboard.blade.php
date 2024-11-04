@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'Affiche un aperçu sur le magasin.')

@section('content')
    Dashboard magasin

    <a href="{{ route('magasin.logout') }}">Se déconnecter</a>
@endsection