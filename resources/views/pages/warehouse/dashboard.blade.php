@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/dashboard.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.warehouse'))

@section('content')
    Dashboard entrepot

    <a href="{{ route('warehouse.logout') }}">{{ __('auth.logout') }}</a>
@endsection