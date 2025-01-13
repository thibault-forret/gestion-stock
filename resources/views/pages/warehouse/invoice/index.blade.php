@extends('layouts.app')

@section('css')
    <style>
        .hidden {
            display: none;
        }

        .content {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }


        form .search-element {
            width: 90%;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        form div {
            flex: 1 1 calc(33% - 20px);
            display: flex;
            flex-direction: column;
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        form input,
        form select,
        form button {
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        form input:focus,
        form select:focus {
            border-color: #007bff;
            outline: none;
        }

        form .buttons {;
            margin: auto;
            width: 40%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-evenly;
            margin: 20px 0;
        }

        /* Style pour les boutons */
        .buttons .btn {
            margin-bottom: 10px;
            width: 250px;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .buttons .btn.red {
            background-color: #dc3545;
        }

        .buttons .btn.red:hover {
            background-color: #c82333;
        }

        .buttons .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .buttons .btn:active {
            background-color: #004085;
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .buttons .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
        }


        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }

        .invoices {
            width: 80%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .invoice {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .invoice:hover {
            transform: scale(1.01);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .invoice h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #007bff;
        }

        .invoice p {
            margin: 5px 0;
            line-height: 1.5;
            color: #555;
        }

        .invoice a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .invoice a:hover {
            background-color: #0056b3;
        }

        .status-paid {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
        }

        .status-due-soon {
            background-color: #ffc107;
            color: #212529;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
        }

        .status-due-week {
            background-color: #fd7e14;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
        }

        .status-overdue {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
        }

        .center-child {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-message p {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
        }

    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/search-new-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('title', __('title.invoice_list'))
@section('description', __('description.invoice_list'))

@section('content')

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h3>{{ __('title.invoice_list') }}</h3>

    <form action="{{ route('warehouse.invoice.search') }}" method="POST">
        @csrf
        <div class="search-element">
            <div>
                <label for="search">Recherche par numéro de facture</label>
                <input type="text" id="search" name="search" value="" placeholder="Numéro de facture" required>
            </div>
        </div>
        <div class="buttons">
            <button class="btn" type="submit">Rechercher</button>
            <a class="btn red" href="{{ route('warehouse.invoice.index') }}">Rénitialiser recherche</a>
        </div>
    </form>

    <div class="redirection-buttons">
        <a href="{{ route('warehouse.invoice.list.supply') }}">Approvisionnement entrepôt</a>
        <a href="{{ route('warehouse.invoice.list.order') }}">Commandes magasins</a>
    </div>

@endsection