@extends('layouts.app')

{{-- @section('css')
    <link href="{{ mix('css/pages/warehouse/product/add-product.css') }}" rel="stylesheet">
@endsection --}}

@section('title', __('title.invoice_info'))
@section('description', __('description.invoice_info'))

@section('content')
    <div>
        <h2>Informations sur la facture</h2>
        
        <div>
            <h3>Informations générales</h3>
            <p>Numéro de facture: {{ $invoice->invoice_number }}</p>    
            <p>Date de création: {{ $invoice->created_at }}</p>
            <p>Statut: {{ $invoice->invoice_status }}</p>
            @if ($invoice->invoice_status === App\Models\Invoice::INVOICE_STATUS_PAID)
                <p>Date de paiement: {{ $invoice->updated_at }}</p>
            @endif
            <p>Montant total: {{ $total_amount }}</p>

    </div>

@endsection