@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/warehouse/invoice/supply_list.css') }}" rel="stylesheet">
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

@section('title', __('title.invoice_list_supply'))
@section('description', __('description.invoice_list'))
@section('parent-route', route('warehouse.invoice.index'))
@section('title-content', mb_strtoupper(__('title.invoice_list_supply')))

@section('content')

    <div class="search-container">
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
                <a class="btn red" href="{{ route('warehouse.invoice.list.supply') }}">Réinitialiser recherche</a>
            </div>
        </form>
    </div>
    <div class="filter-form">
        <form action="{{ route('warehouse.invoice.filter.supply') }}" method="get">
            <div class="search-element">

                <div>
                    <label for="supplier"><i class="fas fa-dolly-flatbed"></i> Fournisseur :</label>
                    <select id="supplier" name="supplier">
                        <option value="">Aucune sélection</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_name }}" {{ request('supplier') == $supplier->supplier_name ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="order"><i class="fas fa-sort-amount-down-alt"></i> Trier par ordre</label>
                    <select id="order" name="order" required>
                        <option value="desc" {{ request('order') != 'desc' ? '' : 'selected' }}>Décroissant</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>

                <div>
                    <label for="status"><i class="fas fa-receipt"></i> Statut du paiement</label>
                    <select id="status" name="status" required>
                        <option value="all" {{ request('status') != 'all' ? '' : 'selected' }}>Tous</option>
                        <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Réglé</option>
                        <option value="not-settled" {{ request('status') == 'not-settled' ? 'selected' : '' }}>Non réglé</option>
                    </select>
                </div>

                <div>
                    <label for="priority_level"><i class="fas fa-exclamation-circle"></i> Niveau de priorité</label>
                    <select id="priority_level" name="priority_level" required>
                        <option value="all" {{ request('priority_level') == 'all' ? 'selected' : '' }}>Aucune sélection</option>
                        <option value="low" {{ request('priority_level') != 'low' ? '' : 'selected' }}>À traiter</option>
                        <option value="medium" {{ request('priority_level') == 'medium' ? 'selected' : '' }}>En attente</option>
                        <option value="high" {{ request('priority_level') == 'high' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>

                <div>
                    <label for="type_date"><i class="fas fa-calendar-day"></i> Type recherche date</label>
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
                <a class="btn red" href="{{ route('warehouse.invoice.list.supply') }}">Rénitialiser recherche</a>
            </div>
        </form>
    </div>
    @if ($errors->any())
        <div class="center-child error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="invoices">
        @if ($invoices->isEmpty())
            <p style="margin: auto">Aucune facture trouvée</p>
        @endif
        @foreach ($invoices as $invoice)
            @php
                $supplier = $invoice->supply->supplier;
                $total_price = $invoice->supply->supplyLines->sum(function ($supply_line) {
                    return $supply_line->quantity_supplied * $supply_line->unit_price;
                });

                $invoiceDate = new DateTime($invoice->invoice_date);
                $currentDate = new DateTime();
                $daysDifference = $currentDate->diff($invoiceDate)->days;

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
                    <p>Fournisseur : {{ $invoice->entity_name }}</p>
                    <p>Date : {{ $invoice->created_at->format('d/m/Y H:i:s') }}</p>
                    <p>Prix total : {{ $total_price }} €</p>
                    @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID)
                        <p>Date réglement : {{ $invoice->updated_at->format('d/m/Y H:i:s') }}</p>
                    @endif
                    <p class="{{ $statusClass }}">
                        Status : {{ $invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID ? __('Settled') : __('Not settled') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('warehouse.invoice.info.supply', ['invoice_number' => $invoice->invoice_number]) }}"><i class="far fa-question-circle"></i> Informations</a>
                    <a target="_blank" href="{{ route('warehouse.invoice.show', ['invoice_number' => $invoice->invoice_number]) }}"><i class="far fa-eye"></i> Voir la facture</a>
                    <a target="_blank" href="{{ route('warehouse.invoice.download', ['invoice_number' => $invoice->invoice_number]) }}"><i class="fas fa-download"></i> Télécharger la facture</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
