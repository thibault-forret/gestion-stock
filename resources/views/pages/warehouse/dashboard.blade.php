@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/dashboard.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.warehouse'))
@section('title-content', strtoupper(__('title.dashboard')))

@section('content')
    Dashboard entrepot
@endsection