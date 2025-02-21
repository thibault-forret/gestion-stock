@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/invoice/index.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.invoice_list'))
@section('description', __('description.invoice_list'))
@section('parent-route', route('warehouse.dashboard'))
@section('title-content', mb_strtoupper(__('title.invoice_list')))

@section('content')

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h3>{{ __('title.invoice_list') }}</h3>

    <form action="{{ route('warehouse.invoice.search') }}" method="POST">
        @csrf
        <div class="search-element">
            <div>
                <label for="search">{{ __('invoice.search_invoice') }}</label>
                <input type="text" id="search" name="search" value="" placeholder="{{ __('invoice.invoice_number') }}" required>
            </div>
        </div>
        <div class="buttons">
            <button class="btn" type="submit">{{ __('invoice.search') }}</button>
            <a class="btn red" href="{{ route('warehouse.invoice.index') }}">{{ __('invoice.reset_search') }}</a>
        </div>
    </form>

    <div class="container">

        <div class="role-selection">
            <div class="role-card">
                <a href="{{ route('warehouse.invoice.list.supply') }}">
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="role-title">{{ __('title.supply_warehouse') }}</div>
                    <p class="role-description">{{ __('description.invoice_list') }}</p>
                </a>
            </div>
            <div class="role-card">
                <a href="{{ route('warehouse.invoice.list.order') }}">
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="role-title">{{ __('title.order_store') }}</div>
                    <p class="role-description">{{ __('description.invoice_list') }}</p>
                </a>
            </div>
        </div>

    </div>

@endsection
