@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/store/order/liste.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.supply_list'))
@section('description', __('description.supply_list'))
@section('parent-route', route('warehouse.stock.supply.index'))
@section('title-content', mb_strtoupper(__('title.supply_list')))

@section('content')

    <div class="order-list">
        <h3>{{ __('title.supply_list') }}</h3>

        @if($supplies->count() > 0)
            <table class="order-table">
                <thead>
                    <tr>
                        <th>{{ __('order.id') }}</th>
                        <th>{{ __('order.last_updated') }}</th>
                        <th>{{ __('order.price') }}</th>
                        <th>{{ __('order.statut') }}</th>
                        <th>{{ __('order.actions') }}</th>
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
                                    <span class="badge badge-info">{{ __('order.in_progress') }}</span>
                                @elseif($supply->supply_status == 'DELIVERED')
                                    <span class="badge badge-success">{{ __('order.delivered') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($supply->supply_status == 'IN PROGRESS')
                                    <a href="{{ route('warehouse.stock.supply.place', ['supply_id' => $supply->id]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/rouage.svg') }}" alt="">
                                        {{ __('supply.modify_supply') }}
                                    </a>
                                @endif
                                <a href="{{ route('warehouse.stock.supply.detail', ['supply_id' => $supply->id]) }}" class="btn btn-info">
                                    <img src="{{ asset('images/loupe.svg') }}" alt="">
                                    {{ __('supply.supply_details') }}
                                </a>
                                @if($supply->supply_status == 'IN PROGRESS')
                                    <a class="btn badge-success" href="{{ route('warehouse.stock.supply.recap', ['supply_id' => $supply->id]) }}">
                                        <img src="{{ asset('images/valide.svg') }}" alt="">
                                        {{ __('supply.confirm_supply') }}
                                    </a>
                                @endif
                                @if($supply->supply_status != 'DELIVERED')
                                    <form action="{{ route('warehouse.stock.supply.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="supply_id" value="{{ $supply->id }}">
                                        <button class="btn badge-red supprimer-bouton" type="submit">
                                            <img src="{{ asset('images/croix2(1).svg') }}" alt="">
                                            {{ __('supply.delete_supply') }}
                                        </button>
                                    </form>
                                @else
                                    <a target="_blank" href="{{ route('warehouse.invoice.show', ['invoice_number' => $supply->invoice->invoice_number]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/oeuil.svg') }}" alt="">
                                        {{ __('order.see_invoice') }}
                                    </a>
                                    <a href="{{ route('warehouse.invoice.download', ['invoice_number' => $supply->invoice->invoice_number]) }}" class="btn btn-info">
                                        <img src="{{ asset('images/télécharger.svg') }}" alt="">
                                        {{ __('order.download_invoice') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-order">{{ __('supply.no_supply_found') }}</p>
        @endif
    </div>

@endsection
