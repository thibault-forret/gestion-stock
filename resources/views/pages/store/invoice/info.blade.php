@extends('layouts.app')

@section('css')
    <style>
        .invoice-container {
            width: 80%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        .invoice-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-section {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .invoice-section h4 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .invoice-section p {
            margin: 5px 0;
            color: #555;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }

        .total-section h4 {
            font-size: 20px;
            color: #333;
        }

        .total-section h4 .text-primary {
            color: #173b75;
            font-weight: bold;
        }

        .total-section .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            transition: all 0.3s ease;
        }

        /* Style pour le bouton "Settle Invoice" */
        .total-section .btn.settle-btn {
            background-color: #5a5a5c;
            color: #fff;
        }

        .total-section .btn.settle-btn:hover {
            background-color: #494949;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Style pour les boutons secondaires */
        .total-section .btn.secondary-btn {
            background-color: #173b75;
            color: #fff;
            margin-left: 10px;
        }

        .total-section .btn.secondary-btn:hover {
            background-color: #0f264a;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Alignement et marges des liens */
        .total-section .action-links {
            margin-top: 10px;
        }

        .total-section .action-links a {
            margin-left: 5px;
        }

        /* Formulaire de règlement */
        .total-section .action-form {
            margin-top: 15px;
        }
    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', __('title.invoice_info'))
@section('description', __('description.invoice_info'))
@section('parent-route', route('store.invoice.list'))
@section('title-content', mb_strtoupper(__('title.invoice_info')))

@section('content')
<div class="invoice-container">
    <!-- Détails de la facture -->
    <div class="invoice-section">
        <h4>{{ __('invoice.invoice_details') }}</h4>
        <p><strong>{{ __('invoice.number') }}:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>{{ __('invoice.date') }}:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>{{ __('invoice.status') }}:</strong> {{ $invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID ? __('invoice.settled') : __('invoice.not_settled') }}</p>
        @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID)
            <p><strong>{{ __('invoice.settled_on') }}:</strong> {{ $invoice->updated_at->format('d/m/Y H:i:s') }}</p>
        @endif
    </div>

    <!-- Détails de l'entrepôt -->
    <div class="invoice-section">
        <h4>{{ __('invoice.warehouse_details') }}</h4>
        <p><strong>{{ __('invoice.name') }}:</strong> {{ $warehouse->warehouse_name }}</p>
        <p><strong>{{ __('invoice.location') }}:</strong> {{ $warehouse->warehouse_address }}</p>
        <p><strong>{{ __('invoice.email') }}:</strong> {{ $warehouse->warehouse_email }}</p>
        <p><strong>{{ __('invoice.phone') }}:</strong> {{ $warehouse->warehouse_phone }}</p>
        <p><strong>{{ __('invoice.manager') }}:</strong> {{ $warehouse->manager->username }}</p>
    </div>

    <!-- Détails du fournisseur -->
    <div class="invoice-section">
        <h4>{{ __('invoice.store_details') }}</h4>
        <p><strong>{{ __('invoice.name') }}:</strong> {{ $order->store->store_name }}</p>
        <p><strong>{{ __('invoice.email') }}:</strong> {{ $order->store->store_email }}</p>
        <p><strong>{{ __('invoice.phone') }}:</strong> {{ $order->store->store_phone }}</p>
        <p><strong>{{ __('invoice.address') }}:</strong> {{ $order->store->store_address }}</p>
        <p><strong>{{ __('invoice.manager') }}:</strong> {{ $order->store->manager->username }}</p>
    </div>

    <!-- Détails de l'approvisionnement -->
    <div class="invoice-section">
        <h4>{{ __('invoice.order_details') }}</h4>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>{{ __('order.id') }}</th>
                    <th>{{ __('order.product') }}</th>
                    <th>{{ __('order.quantity') }}</th>
                    <th>{{ __('order.unit_price') }}</th>
                    <th>{{ __('order.total_ht') }} (€)</th>
                    <th>{{ __('order.total_ttc') }} (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderLines as $line)
                    <tr>
                        <td>{{ $line->product->id }}</td>
                        <td>{{ $line->product->product_name }}</td>
                        <td>{{ $line->quantity_ordered }}</td>
                        <td>{{ number_format($line->unit_price, 2, ',', ' ') }}</td>
                        <td>{{ number_format($line->unit_price * $line->quantity_ordered, 2, ',', ' ') }}</td>
                        <td>{{ number_format($line->unit_price * $line->quantity_ordered * $warehouse->global_margin, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Total et bouton de règlement -->
    <div class="total-section">
        <h4>{{ __('order.total_ht') }} :
            <span class="text-primary">{{ number_format($total_amount_ht, 2) }} €</span>
        </h4>
        <h4>{{ __('order.total_ttc') }} :
            <span class="text-primary">{{ number_format($total_amount_ttc, 2) }} €</span>
        </h4>

        @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_UNPAID)
            <form action="{{ route('store.invoice.settle') }}" method="POST" class="action-form">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <button type="submit" class="btn settle-btn">
                    {{ __('invoice.settle_invoice') }}
                </button>
            </form>
        @endif

        <div class="action-links">
            <a target="_blank" href="{{ route('store.order.invoice.show', ['invoice_number' => $invoice->invoice_number]) }}" class="btn secondary-btn">
                {{ __('order.see_invoice') }}
            </a>
            <a target="_blank" href="{{ route('store.order.invoice.download', ['invoice_number' => $invoice->invoice_number]) }}" class="btn secondary-btn">
                {{ __('order.download_invoice') }}
            </a>
        </div>
    </div>
</div>
@endsection

