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
            padding: 20px;
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
            background-color: #39a521;
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
@section('parent-route', route('warehouse.invoice.list.supply'))
@section('title-content', mb_strtoupper(__('title.invoice_info')))

@section('content')
<div class="invoice-container">

    <!-- Détails de la facture -->
    <div class="invoice-section">
        <h4>{{ __('Invoice Details') }}</h4>
        <p><strong>{{ __('Number') }}:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>{{ __('Date') }}:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>{{ __('Status') }}:</strong> {{ $invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID ? __('Settled') : __('Not settled') }}</p>
        @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID)
            <p><strong>Settled on:</strong> {{ $invoice->updated_at->format('d/m/Y H:i:s') }}</p>
        @endif
    </div>

    <!-- Détails de l'entrepôt -->
    <div class="invoice-section">
        <h4>{{ __('Warehouse Details') }}</h4>
        <p><strong>{{ __('Name') }}:</strong> {{ $invoice->warehouse_name }}</p>
        <p><strong>{{ __('Location') }}:</strong> {{ $invoice->warehouse_address }}</p>
        <p><strong>{{ __('Email') }}:</strong> {{ $supply->warehouse->warehouse_email }}</p>
        <p><strong>{{ __('Phone') }}:</strong> {{ $supply->warehouse->warehouse_phone }}</p>
        <p><strong>{{ __('Manager') }}:</strong> {{ $invoice->warehouse_director }}</p>
    </div>

    <!-- Détails du fournisseur -->
    <div class="invoice-section">
        <h4>{{ __('Supplier Details') }}</h4>
        <p><strong>{{ __('Name') }}:</strong> {{ $invoice->entity_name }}</p>
        <p><strong>{{ __('Email') }}:</strong> {{ $supply->supplier->supplier_email }}</p>
        <p><strong>{{ __('Phone') }}:</strong> {{ $supply->supplier->supplier_phone }}</p>
        <p><strong>{{ __('Address') }}:</strong> {{ $invoice->entity_address }}</p>
        <p><strong>{{ __('Contact') }}:</strong> {{ $invoice->entity_director }}</p>
    </div>

    <!-- Détails de l'approvisionnement -->
    <div class="invoice-section">
        <h4>{{ __('Supply Details') }}</h4>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Quantity Supplied') }}</th>
                    <th>{{ __('Unit Price (€)') }}</th>
                    <th>{{ __('Total (€)') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($supply->supplyLines as $line)
                    <tr>
                        <td>{{ $line->product->id }}</td>
                        <td>{{ $line->product->product_name }}</td>
                        <td>{{ $line->quantity_supplied }}</td>
                        <td>{{ number_format($line->unit_price, 2, ',', ' ') }}</td>
                        <td>{{ number_format($line->unit_price * $line->quantity_supplied, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Total et bouton de règlement -->
    <div class="total-section">
        <h4>{{ __('Total Amount') }}:
            <span class="text-primary">{{ number_format($total_amount, 2, ',', ' ') }} €</span>
        </h4>

        @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_UNPAID)
            <form action="{{ route('warehouse.invoice.settle') }}" method="POST" class="action-form">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <button type="submit" class="btn settle-btn">
                    {{ __('Settle Invoice') }}
                </button>
            </form>
        @endif

        <div class="action-links">
            <a target="_blank" href="{{ route('warehouse.invoice.show', ['invoice_number' => $invoice->invoice_number]) }}" class="btn secondary-btn">
                Voir la facture
            </a>
            <a target="_blank" href="{{ route('warehouse.invoice.download', ['invoice_number' => $invoice->invoice_number]) }}" class="btn secondary-btn">
                Télécharger la facture
            </a>
        </div>
    </div>
</div>
@endsection

