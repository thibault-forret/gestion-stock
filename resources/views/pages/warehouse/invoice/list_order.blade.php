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
                dayInput.setAttribute('value', '');
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

@section('title', __('title.invoice_list_order'))
@section('description', __('description.invoice_list'))
@section('parent-route', route('warehouse.invoice.index'))
@section('title-content', mb_strtoupper(__('title.invoice_list_order')))

@section('content')
    <div class="search-container">
        <form action="{{ route('warehouse.invoice.search') }}" method="POST">
            @csrf
            <div class="search-element">
                <div>
                    <label for="search">{{ __('invoice.search_invoice') }}</label>
                    <input type="text" id="search" name="search" value="" placeholder="{{ __('invoice.invoice_number') }}" required>
                </div>
            </div>
            <div class="buttons">
                <button class="btn" type="submit">{{ __('invoice.search') }}</button>
                <a class="btn red" href="{{ route('warehouse.invoice.list.order') }}">{{ __('invoice.reset_search') }}</a>
            </div>
        </form>
    </div>

    <div class="filter-form">
        <form action="{{ route('warehouse.invoice.filter.order') }}" method="get">
            <div class="search-element">
                <div>
                    <label for="store">{{ __('invoice.store') }} :</label>
                    <select id="store" name="store">
                        <option value="">{{ __('invoice.no_selection') }}</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->store_name }}" {{ request('store') == $store->store_name ? 'selected' : '' }}>
                                {{ $store->store_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="order">{{ __('invoice.sort_order') }}</label>
                    <select id="order" name="order" required>
                        <option value="desc" {{ request('order') != 'desc' ? '' : 'selected' }}>{{ __('invoice.descending') }}</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>{{ __('invoice.ascending') }}</option>
                    </select>
                </div>

                <div>
                    <label for="status">{{ __('invoice.payment_status') }}</label>
                    <select id="status" name="status" required>
                        <option value="all" {{ request('status') != 'all' ? '' : 'selected' }}>{{ __('invoice.all') }}</option>
                        <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>{{ __('invoice.settled') }}</option>
                        <option value="not-settled" {{ request('status') == 'not-settled' ? 'selected' : '' }}>{{ __('invoice.not_settled') }}</option>
                    </select>
                </div>

                <div>
                    <label for="type_date">{{ __('invoice.date_search_type') }}</label>
                    <select id="type_date" name="type_date" required>
                        <option value="all" {{ request('type_date') == 'all' ? 'selected' : '' }}>{{ __('invoice.no_selection') }}</option>
                        <option value="day" {{ request('type_date') == 'day' ? 'selected' : '' }}>{{ __('invoice.day') }}</option>
                        <option value="week" {{ request('type_date') == 'week' ? 'selected' : '' }}>{{ __('invoice.week') }}</option>
                        <option value="month" {{ request('type_date') == 'month' ? 'selected' : '' }}>{{ __('invoice.month') }}</option>
                        <option value="year" {{ request('type_date') == 'year' ? 'selected' : '' }}>{{ __('invoice.year') }}</option>
                    </select>
                </div>

                <div id="day-picker" class="hidden">
                    <label for="day">{{ __('invoice.select_day') }}</label>
                    <input type="date" id="day" name="day" value="{{ request('day') == null ? '' : request('day') }}" max="">
                </div>
                
                <div id="week-picker" class="hidden">
                    <label for="week">{{ __('invoice.select_week') }}</label>
                    <input type="week" id="week" name="week" value="{{ request('week') == null ? '' : request('week') }}" max="">
                </div>

                <div id="month-picker" class="hidden">
                    <label for="month">{{ __('invoice.select_month') }}</label>
                    <input type="month" id="month" name="month" value="{{ request('month') == null ? '' : request('month') }}" max="">
                </div>

                <div id="year-picker" class="hidden">
                    <label for="year">{{ __('invoice.select_year') }}</label>
                    <input type="number" id="year" name="year" value="{{ request('year') == null ? '' : request('year') }}" min="1900" max="">
                </div>
            </div>

            <div class="buttons">
                <button class="btn" type="submit">{{ __('invoice.search') }}</button>
                <a class="btn red" href="{{ route('warehouse.invoice.list.order') }}">{{ __('invoice.reset_search') }}</a>
            </div>
        </form>
    </div>
    
    @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
    @endif
    
    <div class="invoices">
        @if ($invoices->isEmpty())
            <p style="margin: auto">{{ __('invoice.no_invoice_found') }}</p>
        @endif
        
        @foreach ($invoices as $invoice)
            @php
                $store = $invoice->order->store;
                $warehouse = $store->warehouse;
                $order = $invoice->order;

                $total_amount_ht = $order->calculateTotalPrice();
                $total_amount_ttc = $total_amount_ht * $warehouse->global_margin;

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
                    <p>{{ __('invoice.number') }} : {{ $invoice->invoice_number }}</p>
                    <p>{{ __('invoice.store') }} : {{ $invoice->entity_name }}</p>
                    <p>{{ __('invoice.date') }} : {{ $invoice->created_at->format('d/m/Y H:i:s') }}</p>
                    <p>{{ __('order.total_ht') }} : {{ number_format($total_amount_ht, 2) }} €</p>
                    <p>{{ __('order.total_ttc') }} : {{ number_format($total_amount_ttc, 2) }} €</p>
                    @if ($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID)
                        <p>{{ __('invoice.settled_on') }} : {{ $invoice->updated_at->format('d/m/Y H:i:s') }}</p>
                    @endif
                    <p class="{{ $statusClass }}">
                        {{ __('invoice.status') }} : {{ $invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID ? __('invoice.settled') : __('invoice.not_settled') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('warehouse.invoice.info.order', ['invoice_number' => $invoice->invoice_number]) }}"><i class="far fa-question-circle"></i> {{ __('invoice.info') }}</a>
                    <a target="_blank" href="{{ route('warehouse.order.invoice.show', ['invoice_number' => $invoice->invoice_number]) }}"><i class="far fa-eye"></i> {{ __('order.see_invoice') }}</a>
                    <a target="_blank" href="{{ route('warehouse.order.invoice.download', ['invoice_number' => $invoice->invoice_number]) }}"><i class="fas fa-download"></i> {{ __('order.download_invoice') }}</a>
                </div>    
            </div>
        @endforeach
    </div>
@endsection
