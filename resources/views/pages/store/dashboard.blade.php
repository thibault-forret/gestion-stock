@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/dashboard.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.store'))
@section('title-content', strtoupper(__('title.dashboard')))

@section('content')
    Dashboard magasin
@endsection
