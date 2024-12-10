@extends('layouts.app')

@section('css')
    <style>
        .hidden {
            display: none;
        }
    </style>
    {{-- <link href="{{ mix('css/pages/warehouse/product/search-new-product.css') }}" rel="stylesheet"> --}}
@endsection

@section('js')
    <script>
        // Obtenir l'élément select et les conteneurs
        const typeDate = document.getElementById('type_date');
        const dayPicker = document.getElementById('day-picker');
        const weekPicker = document.getElementById('week-picker');
        const monthPicker = document.getElementById('month-picker');
        const yearPicker = document.getElementById('year-picker');

        const dayInput = document.getElementById('day');
        const weekInput = document.getElementById('week');
        const monthInput = document.getElementById('month');
        const yearInput = document.getElementById('year');

        // Définir la date maximale (aujourd'hui)
        const today = new Date();
        const maxDate = today.toISOString().split('T')[0];
        dayInput.max = maxDate;
        weekInput.max = `${today.getFullYear()}-W${getWeekNumber(today)}`;
        monthInput.max = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
        yearInput.max = today.getFullYear();

        // Fonction pour obtenir le numéro de la semaine
        function getWeekNumber(date) {
            const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
            const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
            return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
        }

        function changeTypeDate(changeValue = false) {
            // Réinitialiser tous les conteneurs et les attributs required
            resetPickers(changeValue);

            // Afficher le conteneur et définir required pour le champ correspondant
            switch (typeDate.value) {
                case 'day':
                    dayPicker.classList.remove('hidden');
                    dayInput.required = true;
                    break;
                case 'week':
                    weekPicker.classList.remove('hidden');
                    weekInput.required = true;
                    break;
                case 'month':
                    monthPicker.classList.remove('hidden');
                    monthInput.required = true;
                    break;
                case 'year':
                    yearPicker.classList.remove('hidden');
                    yearInput.required = true;
                    break;
            }
        }

        function resetPickers(changeValue = false) {
            // Cacher tous les pickers
            dayPicker.classList.add('hidden');
            weekPicker.classList.add('hidden');
            monthPicker.classList.add('hidden');
            yearPicker.classList.add('hidden');

            if(changeValue) {
                // Réinitialiser les valeurs
                dayInput.setAttribute('value', ''); // S'assurer qu'il n'y a plus de valeur par défaut
                weekInput.setAttribute('value', '');
                monthInput.setAttribute('value', '');
                yearInput.setAttribute('value', '');
            }

            // Désactiver les champs en fonction du type de date
            dayInput.required = false;
            weekInput.required = false;
            monthInput.required = false;
            yearInput.required = false;
        }

        // Détecter les changements seulement après une interaction (pas au chargement)
        typeDate.addEventListener('change', function() {
            changeTypeDate(true);
        });

        // Initialiser l'état du formulaire avec la valeur actuelle
        changeTypeDate();
    </script>
@endsection

@section('title', __('title.warehouse_stock_list'))
@section('description', __('description.warehouse_stock_list'))

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h3>{{ __('title.warehouse_stock_list') }}</h3>

    <form action="{{ route('warehouse.invoice.filter') }}" method="get">
        <div>
            <label for="supplier">Fournisseur :</label>
            <select id="supplier" name="supplier">
                <option value="all">Aucune sélection</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->supplier_name }}" {{ request('supplier') == $supplier->supplier_name ? 'selected' : '' }}>
                        {{ $supplier->supplier_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="order">Trier par ordre</label>
            <select id="order" name="order" required>
                <option value="desc" {{ request('status') != 'desc' ? '' : 'selected' }}>Décroissant</option>
                <option value="asc" {{ request('status') == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
        </div>

        <div>
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="all" {{ request('status') != 'all' ? '' : 'selected' }}>Tous</option>
                <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Réglé</option>
                <option value="not-settled" {{ request('status') == 'not-settled' ? 'selected' : '' }}>Non réglé</option>
            </select>
        </div>

        <div>
            <label for="type_date">Type recherche date</label>
            <select id="type_date" name="type_date" required>
                <option value="all" {{ request('type_date') == 'all' ? 'selected' : '' }}>Aucune sélection</option>
                <option value="day" {{ request('type_date') == 'day' ? 'selected' : '' }}>Jour</option>
                <option value="week" {{ request('type_date') == 'week' ? 'selected' : '' }}>Semaine</option>
                <option value="month" {{ request('type_date') == 'month' ? 'selected' : '' }}>Mois</option>
                <option value="year" {{ request('type_date') == 'year' ? 'selected' : '' }}>Année</option>
            </select>
        </div>

        <div id="day-picker" class="hidden">
            <label for="day">Sélectionnez un jour :</label>
            <input type="date" id="day" name="day" value="{{ request('day') == null ? '' : request('day') }}" max="">
        </div>

        <div id="week-picker" class="hidden">
            <label for="week">Sélectionnez une semaine :</label>
            <input type="week" id="week" name="week" value="{{ request('week') == null ? '' : request('week') }}" max="">
        </div>

        <div id="month-picker" class="hidden">
            <label for="month">Sélectionnez un mois :</label>
            <input type="month" id="month" name="month" value="{{ request('month') == null ? '' : request('month') }}" max="">
        </div>

        <div id="year-picker" class="hidden">
            <label for="year">Sélectionnez une année :</label>
            <input type="number" id="year" name="year" value="{{ request('year') == null ? '' : request('year') }}" min="1900" max="">
        </div>

        <button type="submit">Rechercher</button>
    </form>

    <a href="{{ route('warehouse.invoice.list') }}">Rénitialiser recherche</a>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <style>
        .invoices {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .invoice {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            width: calc(33.333% - 20px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .invoice:hover {
            transform: scale(1.01);
        }

        .invoice p {
            margin: 5px 0;
        }

        .invoice a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.2s;
        }

        .invoice a:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="invoices">
        @foreach ($invoices as $invoice)

            @php
                $supplier = $invoice->supply->supplier;
                $total_price = $invoice->supply->supplyLines->sum(function ($supply_line) {
                    return $supply_line->quantity_supplied * $supply_line->unit_price;
                });
            @endphp

            <div class="invoice">
                <div>
                    <p>Fournisseur : {{ $supplier->supplier_name }}</p>
                    <p>Date : {{ $invoice->invoice_date }}</p>
                    <p>Status : {{ $invoice->invoice_status }}</p>
                    <p>Prix total : {{ $total_price }} €</p>
                </div>
                <div>
                    <a href="{{ route('warehouse.invoice.info', ['invoice_id' => $invoice->id]) }}">Voir</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection