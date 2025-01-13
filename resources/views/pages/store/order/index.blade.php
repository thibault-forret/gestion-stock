@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/pages/home.css') }}">
@endsection

@section('title', __('title.order'))
@section('description', __('description.order'))
@section('parent-route', route('store.dashboard'))
@section('title-content', mb_strtoupper(__('title.order')))

@section('content')
    
    <div class="container">

        <div class="role-selection">
            <div class="role-card">
                <a href="{{ route('store.order.new') }}">
                    <div class="icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="role-title">{{ __('title.new_order') }}</div>
                    <p class="role-description">{{ __('description.new_order') }}</p>
                </a>
            </div>
            <div class="role-card">
                <a href="{{ route('store.order.list') }}">
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="role-title">{{ __('title.order_list') }}</div>
                    <p class="role-description">{{ __('description.order_list') }}</p>
                </a>
            </div>
        </div>

    </div>

@endsection