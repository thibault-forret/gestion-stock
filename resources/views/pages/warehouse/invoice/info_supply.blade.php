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
        .total-section .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        .total-section .btn:hover {
            background-color: #218838;
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
    <h1 class="invoice-title">{{ __('title.invoice_info') }}</h1>

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
            <form action="{{ route('warehouse.invoice.settle') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <button type="submit" class="btn">
                    {{ __('Settle Invoice') }}
                </button>
            </form>
        @endif

        <a target="_blank" href="{{ route('warehouse.invoice.show', ['invoice_number' => $invoice->invoice_number]) }}">Voir la facture</a>
        <a target="_blank" href="{{ route('warehouse.invoice.download', ['invoice_number' => $invoice->invoice_number]) }}">Télécharger la facture</a>
    </div>
</div>
@endsection

