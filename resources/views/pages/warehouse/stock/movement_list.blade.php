@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/stock/movement_list.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.warehouse_stock_movement_list'))
@section('description', __('description.warehouse_stock_movement_list'))
@section('parent-route', route('warehouse.stock.index'))
@section('title-content', mb_strtoupper(__('title.warehouse_stock_movement_list')))

@section('content')

    @if($stockMovements && count($stockMovements) > 0)

        <table>
            <thead>
                <tr>
                    <th>{{ __('stock_movement.id') }}</th>
                    <th>{{ __('stock_movement.product_id') }}</th>
                    <th>{{ __('stock_movement.warehouse_id') }}</th>
                    <th>{{ __('stock_movement.user_id') }}</th>
                    <th>{{ __('stock_movement.quantity_moved') }}</th>
                    <th>{{ __('stock_movement.movement_type') }}</th>
                    <th>{{ __('stock_movement.movement_date') }}</th>
                    <th>{{ __('stock_movement.movement_status') }}</th>
                    <th>{{ __('stock_movement.movement_source') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockMovements as $movement)
                    <tr>
                        <td>{{ $movement->id }}</td>
                        <td>{{ $movement->product_id }}</td>
                        <td>{{ $movement->warehouse_id }}</td>
                        <td>{{ $movement->user_id }}</td>
                        <td>{{ $movement->quantity_moved }}</td>
                        <td>{{ $movement->movement_type }}</td>
                        <td>{{ $movement->movement_date }}</td>
                        <td>{{ $movement->movement_status }}</td>
                        <td>{{ $movement->movement_source }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <p style="text-align: center">{{ __('stock_movement.no_movement') }}Aucun mouvement de stock.</p>
    @endif

@endsection
