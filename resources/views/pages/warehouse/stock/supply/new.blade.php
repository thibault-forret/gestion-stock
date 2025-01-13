@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/store/order/index.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.new_order'))
@section('description', __('description.new_order'))
@section('parent-route', route('warehouse.stock.supply.index'))
@section('title-content', strtoupper(__('title.new_order')))

@section('content')
    
    {{ __('description.order') }}

    @if($suppliers->isEmpty())
        <p class="no-suppliers">Aucun produits ajoutés à l'entrepôt, veuillez ajouter des produits</p>
        <a href="{{ route('warehouse.product.index')}}">Ajouter des produits</a>
    @endif

    @foreach($suppliers as $supplier)
        <form action="{{ route('warehouse.stock.supply.place.new') }}" method="post">
            @csrf
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
            <button type="submit">{{ __('New order') }} {{ $supplier->supplier_name }}</button>
        </form>
    @endforeach

@endsection