@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/liste.css') }}" rel="stylesheet">
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
                                        <img src="{{ asset('images/rouage.svg') }}" alt="ModifierCommande">
                                        Modifier la commande
                                    </a>
                                @endif
                                <a href="{{ route('store.order.detail', ['order_id' => $order->id]) }}" class="btn btn-info">
                                    <img src="{{ asset('images/loupe.svg') }}" alt="DétailsCommande">
                                    Détails de la commande
                                </a>
                                @if($order->order_status == 'IN PROGRESS')
                                    <a class="btn badge-success" href="{{ route('store.order.recap', ['order_id' => $order->id]) }}">
                                        <img src="{{ asset('images/valide.svg') }}" alt="ConfirmerCommande">
                                        Confirmer la commande
                                    </a>
                                @endif
                                
                                @if($order->order_status != 'DELIVERED')
                                    <form action="{{ route('store.order.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button class="btn badge-red supprimer-bouton" type="submit">
                                            <img src="{{ asset('images/croix2(1).svg') }}" alt="SupprimerCommande">
                                            Supprimer la commande
                                        </button>
                                    </form>
                                @else
                                    <a target="_blank" href="{{ route('store.order.invoice.show', ['invoice_number' => $order->invoice->invoice_number]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/oeuil.svg') }}" alt="VoirFacture">
                                        Voir la facture
                                    </a>
                                    <a href="{{ route('store.order.invoice.download', ['invoice_number' => $order->invoice->invoice_number]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/télécharger.svg') }}" alt="TelechargerFacture">
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