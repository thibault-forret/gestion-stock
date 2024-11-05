@extends('layouts.app')

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.store'))

@section('content')
    Dashboard magasin

    <a href="{{ route('store.logout') }}">Se déconnecter</a>
@endsection