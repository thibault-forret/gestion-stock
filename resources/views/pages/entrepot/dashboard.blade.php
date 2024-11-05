@extends('layouts.app')

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.warehouse'))

@section('content')
    Dashboard entrepot

    <a href="{{ route('warehouse.logout') }}">Se déconnecter</a>
@endsection