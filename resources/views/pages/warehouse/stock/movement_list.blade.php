@extends('layouts.app')

@section('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/search-new-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', __('title.warehouse_stock_movement_list'))
@section('description', __('description.warehouse_stock_movement_list'))
@section('parent-route', route('warehouse.stock.index'))
@section('title-content', strtoupper(__('title.warehouse_stock_movement_list')))

@section('content')
    <h2>Liste des mouvements de stock</h2>

    @if($stockMovements && count($stockMovements) > 0)
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID du produit</th>
                    <th>ID de l'entrepôt</th>
                    <th>ID de l'utilisateur</th>
                    <th>Quantité déplacée</th>
                    <th>Type de mouvement</th>
                    <th>Date du mouvement</th>
                    <th>Statut du mouvement</th>
                    <th>Source du mouvement</th>
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
        <p>Aucun mouvement de stock.</p>
    @endif

@endsection