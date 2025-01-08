@extends('layouts.app')

@section('css')
    <style>
        .order-list {
            max-width: 900px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .order-list h3 {
            text-align: center;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
            margin-top: 20px;
        }

        .order-table th, .order-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-table th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
        }

        .order-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            color: #fff;
            font-size: 0.9rem;
        }

        .badge-warning {
            background-color: #ff9800;
        }

        .badge-success {
            background-color: #4caf50;
        }

        .badge-info {
            background-color: #2196f3;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            font-size: 1rem;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #4eb5ff;
            color: white;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .empty-order {
            text-align: center;
            font-size: 1.2rem;
            color: #999;
            margin-top: 20px;
        }
    </style>
@endsection

@section('title', __('title.order'))
@section('description', __('description.order'))

@section('content')
    <div class="order-list">
        <h3>Liste des commandes</h3>

        @if($orders->count() > 0)
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID de la commande</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @if($order->order_status == 'IN PROGRESS')
                                    <span class="badge badge-warning">{{ __('In Progress') }}</span>
                                @elseif($order->order_status == 'DELIVERED')
                                    <span class="badge badge-success">{{ __('Delivered') }}</span>
                                @elseif($order->order_status == 'PENDING')
                                    <span class="badge badge-info">{{ __('Pending') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status == 'IN PROGRESS')
                                    @if($order->orderLines->count() > 0)
                                        <a href="{{ route('store.order.recap', ['order_id' => $order->id]) }}" class="btn btn-primary">
                                            Voir récapitulatif
                                        </a>
                                    @endif
                                    <a href="{{ route('store.order.place', ['order_id' => $order->id]) }}" class="btn btn-secondary">
                                        Voir la commande
                                    </a>
                                @elseif($order->order_status == 'DELIVERED' || $order->order_status == 'PENDING')
                                    <a href="{{ route('store.order.detail', ['order_id' => $order->id]) }}" class="btn btn-info">
                                        Détails de la commande
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-order">Aucune commande trouvée.</p>
        @endif
    </div>
@endsection
