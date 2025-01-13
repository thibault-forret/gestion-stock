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

@section('title', __('title.order'))
@section('description', __('description.order'))

@section('content')
    
    {{ __('description.order') }} <br>

    Même système que invoice -> filtrage par magasin etc <br>

    <div class="order-list">
        <h3>Liste des commandes</h3>

        @if($supplies->count() > 0)
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Last updated</th>
                        <th>Tarif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplies as $supply)
                        <tr>
                            <td>{{ $supply->id }}</td>
                            <td>{{ $supply->updated_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ number_format($supply->calculateTotalPrice(), 2) }} €</td>
                            <td>
                                @if($supply->supply_status == 'IN PROGRESS')
                                    <span class="badge badge-info">{{ __('In Progress') }}</span>
                                @elseif($supply->supply_status == 'DELIVERED')
                                    <span class="badge badge-success">{{ __('Delivered') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($supply->supply_status == 'IN PROGRESS')
                                    <a href="{{ route('warehouse.stock.supply.place', ['supply_id' => $supply->id]) }}" class="btn btn-info">
                                        Modifier la commande
                                    </a>

                                    <a class="btn btn-info" href="{{ route('warehouse.stock.supply.recap', ['supply_id' => $supply->id]) }}">
                                        Confirmer la commande
                                    </a>
                                @endif
                                <a href="{{ route('warehouse.stock.supply.detail', ['supply_id' => $supply->id]) }}" class="btn btn-info">
                                    Détails de la commande
                                </a>
                                @if($supply->supply_status != 'DELIVERED')
                                    <form action="{{ route('warehouse.stock.supply.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <button class="btn btn-info" type="submit">Supprimer la commande</button>
                                    </form>
                                @else
                                    <a target="_blank" href="{{ route('warehouse.invoice.show', ['invoice_number' => $supply->invoice->invoice_number]) }}" class="btn btn-info">
                                        Voir la facture
                                    </a>
                                    <a href="{{ route('warehouse.invoice.download', ['invoice_number' => $supply->invoice->invoice_number]) }}" class="btn btn-info">
                                        Télécharger la facture
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-order">Aucun approvisionnement trouvé.</p>
        @endif
    </div>

@endsection