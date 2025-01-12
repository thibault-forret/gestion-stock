@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/dashboard.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.store'))

@section('content')
    Dashboard magasin

    <a href="{{ route('store.logout') }}">{{ __('auth.logout') }}</a>
@endsection
