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
        <h3>{{ __('title.order_list') }}</h3>

        @if($orders->count() > 0)
            <table class="order-table">
                <thead>
                    <tr>
                        <th>{{ __('order.id') }}</th>
                        <th>{{ __('order.last_updated') }}</th>
                        <th>{{ __('order.price_ht') }}</th>
                        <th>{{ __('order.price_ttc') }}</th>
                        <th>{{ __('order.statut') }}</th>
                        <th>{{ __('order.actions') }}</th>
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
                                    <span class="badge badge-info">{{ __('order.in_progress') }}</span>
                                @elseif($order->order_status == 'DELIVERED')
                                    <span class="badge badge-success">{{ __('order.delivered') }}</span>
                                @elseif($order->order_status == 'PENDING')
                                    <span class="badge badge-warning">{{ __('order.pending') }}</span>
                                @elseif($order->order_status == 'REFUSED')
                                    <span class="badge badge-red">{{ __('order.refused') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status == 'IN PROGRESS')
                                    <a href="{{ route('store.order.place', ['order_id' => $order->id]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/rouage.svg') }}" alt="ModifierCommande">
                                        {{ __('order.modify_order') }}
                                    </a>
                                @endif
                                <a href="{{ route('store.order.detail', ['order_id' => $order->id]) }}" class="btn btn-info">
                                    <img src="{{ asset('images/loupe.svg') }}" alt="DétailsCommande">
                                    {{ __('order.detail_order') }}
                                </a>
                                @if($order->order_status == 'IN PROGRESS')
                                    <a class="btn badge-success" href="{{ route('store.order.recap', ['order_id' => $order->id]) }}">
                                        <img src="{{ asset('images/valide.svg') }}" alt="ConfirmerCommande">
                                        {{ __('order.confirm_order') }}
                                    </a>
                                @endif

                                @if($order->order_status != 'DELIVERED')
                                    <form action="{{ route('store.order.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button class="btn badge-red supprimer-bouton" type="submit">
                                            <img src="{{ asset('images/croix2(1).svg') }}" alt="SupprimerCommande">
                                            {{ __('order.delete_order') }}
                                        </button>
                                    </form>
                                @else
                                    <a target="_blank" href="{{ route('store.order.invoice.show', ['invoice_number' => $order->invoice->invoice_number]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/oeuil.svg') }}" alt="VoirFacture">
                                        {{ __('order.see_invoice') }}
                                    </a>
                                    <a href="{{ route('store.order.invoice.download', ['invoice_number' => $order->invoice->invoice_number]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/télécharger.svg') }}" alt="TelechargerFacture">
                                        {{ __('order.donwload_invoice') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-order">{{ __('order.no_order_found') }}</p>
        @endif
    </div>

@endsection
