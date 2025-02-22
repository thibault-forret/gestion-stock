@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/invoice/index.css') }}" rel="stylesheet">
    <link href="{{ mix('css/pages/home.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.invoice_list'))
@section('description', __('description.invoice_list'))
@section('parent-route', route('warehouse.dashboard'))
@section('title-content', mb_strtoupper(__('title.invoice_list')))

@section('content')
    @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="search-container">
        <form action="{{ route('warehouse.invoice.search') }}" method="POST">
            @csrf
            <div class="search-element">
                <div>
                    <label for="search">Recherche par numéro de facture</label>
                    <input type="text" id="search" name="search" value="" placeholder="Numéro de facture" required>
                </div>
            </div>
            <div class="buttons">
                <button class="btn" type="submit">Rechercher</button>
                <a class="btn red" href="{{ route('warehouse.invoice.index') }}">Rénitialiser recherche</a>
            </div>
        </form>
    </div>

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
@endsection
