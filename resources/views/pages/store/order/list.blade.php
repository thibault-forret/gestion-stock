@extends('layouts.app')

@section('css')
    <style>
        .order-list {
            width: 80%;
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

        .badge-red {
            background-color: #f44336;
        }

        .badge-warning {
            background-color: #ff9800;
        }

        .badge-success {
            background-color: #4caf50;
        }

        .badge-info {
            background-color: #ffc400;
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

@section('title', __('title.order_list'))
@section('description', __('description.order_list'))
@section('parent-route', route('store.order.index'))
@section('title-content', mb_strtoupper(__('title.order_list')))

@section('content')
    
    <div class="order-list">
        <h3>Liste des commandes</h3>

        @if($orders->count() > 0)
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Last updated</th>
                        <th>Tarif HT</th>
                        <th>Tarif TTC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->updated_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ number_format($order->calculateTotalPrice(), 2) }} €</td>
                            <td>{{ number_format($order->calculateTotalPrice() * $warehouse->global_margin, 2) }} €</td>
                            <td>
                                @if($order->order_status == 'IN PROGRESS')
                                    <span class="badge badge-info">{{ __('In Progress') }}</span>
                                @elseif($order->order_status == 'DELIVERED')
                                    <span class="badge badge-success">{{ __('Delivered') }}</span>
                                @elseif($order->order_status == 'PENDING')
                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @elseif($order->order_status == 'REFUSED')
                                    <span class="badge badge-red">{{ __('Refused') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status == 'IN PROGRESS')
                                    <a href="{{ route('store.order.place', ['order_id' => $order->id]) }}" class="btn btn-info">
                                        Modifier la commande
                                    </a>

                                    <a class="btn btn-info" href="{{ route('store.order.recap', ['order_id' => $order->id]) }}">
                                        Confirmer la commande
                                    </a>
                                @endif
                                <a href="{{ route('store.order.detail', ['order_id' => $order->id]) }}" class="btn btn-info">
                                    Détails de la commande
                                </a>
                                @if($order->order_status != 'DELIVERED')
                                    <form action="{{ route('store.order.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button class="btn btn-info" type="submit">Supprimer la commande</button>
                                    </form>
                                @else
                                    <a target="_blank" href="{{ route('store.order.invoice.show', ['invoice_number' => $order->invoice->invoice_number]) }}" class="btn btn-info">
                                        Voir la facture
                                    </a>
                                    <a href="{{ route('store.order.invoice.download', ['invoice_number' => $order->invoice->invoice_number]) }}" class="btn btn-info">
                                        Télécharger la facture
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