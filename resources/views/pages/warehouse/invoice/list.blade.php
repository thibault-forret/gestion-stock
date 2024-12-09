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
        
        // Définir la date maximale (aujourd'hui)
        const today = new Date();
        const maxDate = today.toISOString().split('T')[0];
        document.getElementById('day').max = maxDate;
        document.getElementById('week').max = `${today.getFullYear()}-W${getWeekNumber(today)}`;
        document.getElementById('month').max = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
        document.getElementById('year').max = today.getFullYear();

        // Fonction pour obtenir le numéro de la semaine
        function getWeekNumber(date) {
            const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
            const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
            return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
        }

        // Gérer le changement d'option
        typeDate.addEventListener('change', () => {
            // Masquer tous les conteneurs
            dayPicker.classList.add('hidden');
            weekPicker.classList.add('hidden');
            monthPicker.classList.add('hidden');
            yearPicker.classList.add('hidden');

            // Afficher le conteneur correspondant
            switch (typeDate.value) {
                case 'day':
                    dayPicker.classList.remove('hidden');
                    break;
                case 'week':
                    weekPicker.classList.remove('hidden');
                    break;
                case 'month':
                    monthPicker.classList.remove('hidden');
                    break;
                case 'year':
                    yearPicker.classList.remove('hidden');
                    break;
            }
        });

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
            <label for="order">Trier par ordre</label>
            <select id="order" name="order" required>
                <option value="asc">Croissant</option>
                <option value="desc">Décroissant</option>
            </select>
        </div>

        <div>
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="2" selected>Tous</option>
                <option value="1">Réglé</option>
                <option value="0">Non réglé</option>
            </select>
        </div>

        <div>
            <label for="type_date">Type recherche date</label>
            <select id="type_date" name="type_date" required>
                <option value="all" selected>Aucune sélection</option>
                <option value="day">Jour</option>
                <option value="week">Semaine</option>
                <option value="month">Mois</option>
                <option value="year">Année</option>
            </select>
        </div>

        <div id="day-picker" class="hidden">
            <label for="day">Sélectionnez un jour :</label>
            <input type="date" id="day" max="">
        </div>

        <div id="week-picker" class="hidden">
            <label for="week">Sélectionnez une semaine :</label>
            <input type="week" id="week" max="">
        </div>

        <div id="month-picker" class="hidden">
            <label for="month">Sélectionnez un mois :</label>
            <input type="month" id="month" max="">
        </div>

        <div id="year-picker" class="hidden">
            <label for="year">Sélectionnez une année :</label>
            <input type="number" id="year" min="1900" max="">
        </div>

        <button type="submit">Rechercher</button>
    </form>

    <a href="{{ route('warehouse.invoice.list') }}">Rénitialiser recherche</a>

@endsection