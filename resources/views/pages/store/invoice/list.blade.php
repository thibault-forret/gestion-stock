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
            color: #f05c2b;
        }

        form {
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form .search-input {
            width: 400px;
            margin-bottom: 20px;
            display: flex;
            margin: auto;
            text-align: center;
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
            border-color: #f05c2b;
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
            background-color: #f05c2b;
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
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .buttons .btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .buttons .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.5);
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

        .invoice p {
            margin: 5px 0;
            line-height: 1.5;
            color: #555;
        }

        .invoice a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #f05c2b;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .invoice a:hover {
            background-color: #ff5b24;
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

@section('title', __('title.invoice_list'))
@section('description', __('description.invoice_list'))
@section('parent-route', route('store.dashboard'))
@section('title-content', mb_strtoupper(__('title.invoice_list')))

@section('content')

    <h3>{{ __('title.invoice_list') }}</h3>

    <form action="{{ route('store.invoice.search') }}" method="POST">
        @csrf
        <div class="search-input">
            <div>
                <label for="search">Recherche par numéro de facture</label>
                <input type="text" id="search" name="search" value="" placeholder="Numéro de facture" required>
            </div>
        </div>
        <div class="buttons">
            <button class="btn" type="submit">Rechercher</button>
            <a class="btn red" href="{{ route('store.invoice.list') }}">Rénitialiser recherche</a>
        </div>
    </form>

    <form action="{{ route('store.invoice.filter') }}" method="get">
        <div class="search-element">
            <div>
                <label for="order">Trier par ordre</label>
                <select id="order" name="order" required>
                    <option value="desc" {{ request('order') != 'desc' ? '' : 'selected' }}>Décroissant</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                </select>
            </div>

            <div>
                <label for="status">Statut du paiement</label>
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
        </div>

        <div class="buttons">
            <button class="btn" type="submit">Rechercher</button>

            <a class="btn red" href="{{ route('store.invoice.list') }}">Rénitialiser recherche</a>
        </div>
    </form>

    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Faire système de trie par magasin etc si on a le temps (reprendre le code de supply) --}}
    @if ($invoices->isEmpty())
        <p style="margin: auto">Aucune facture trouvée</p>
    @endif
    <div class="invoices">
        @foreach ($invoices as $invoice)
            @php
                $store = $invoice->order->store;
                $warehouse = $store->warehouse;
                $order = $invoice->order;

                $total_amount_ht = $order->calculateTotalPrice();
                $total_amount_ttc = $total_amount_ht * $warehouse->global_margin;

                // Calculer la différence en jours entre aujourd'hui et la date de la facture
                $invoiceDate = new DateTime($invoice->invoice_date);
                $currentDate = new DateTime();
                $daysDifference = $currentDate->diff($invoiceDate)->days;

                // Déterminer la classe CSS selon le statut et la date
                $statusClass = '';
                if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID) {
                    $statusClass = 'status-paid';
                } elseif ($daysDifference <= 7) {
                    $statusClass = 'status-due-soon';
                } elseif ($daysDifference > 7 && $daysDifference <= 14) {
                    $statusClass = 'status-due-week';
                } else {
                    $statusClass = 'status-overdue';
                }
            @endphp

            <div class="invoice">
                <div>
                    <p>Numéro de facture : {{ $invoice->invoice_number }}</p>
                    <p>Date : {{ $invoice->created_at->format('d/m/Y H:i:s') }}</p>
                    <p>Total HT : {{ number_format($total_amount_ht, 2) }} €</p>
                    <p>Total TTC : {{ number_format($total_amount_ttc, 2) }} €</p>
                    @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID)
                        <p>Date réglement : {{ $invoice->updated_at->format('d/m/Y H:i:s') }}</p>
                    @endif
                    <p class="{{ $statusClass }}">
                        Status : {{ $invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID ? __('Settled') : __('Not settled') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('store.invoice.info', ['invoice_number' => $invoice->invoice_number]) }}">Informations</a>
                    <a target="_blank" href="{{ route('store.order.invoice.show', ['invoice_number' => $invoice->invoice_number]) }}">Voir la facture</a>
                    <a target="_blank" href="{{ route('store.order.invoice.download', ['invoice_number' => $invoice->invoice_number]) }}">Télécharger la facture</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection