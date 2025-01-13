@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/supply/index.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.new_order'))
@section('description', __('description.new_order'))
@section('parent-route', route('warehouse.stock.supply.index'))
@section('title-content', mb_strtoupper(__('title.new_order')))

@section('content')
    
    @if($suppliers->isEmpty())
        <p class="no-suppliers">Aucun produits ajoutés à l'entrepôt, veuillez ajouter des produits</p>
        <a href="{{ route('warehouse.product.index')}}">Ajouter des produits</a>
    @endif

    <div class="role-selection">
        @foreach($suppliers as $supplier)
            <form action="{{ route('warehouse.stock.supply.place.new') }}" method="post">
                @csrf
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <button class="role-card" type="submit">
                    <div class="role-title">{{ $supplier->supplier_name }}</div>
                    <p class="role-description">{{ __('description.new_order') }}</p>
                </button>
            </form>
        @endforeach
    </div>
    
@endsection