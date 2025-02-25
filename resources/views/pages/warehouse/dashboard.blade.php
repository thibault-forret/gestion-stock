@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/dashboard.css') }}" rel="stylesheet">

    <style>
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .chart-item {
            background-color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
        }

        .full-width {
            grid-column: span 2;
        }

        canvas {
            width: 100%;
            height: 300px;
        }

        body {
            font-family: 'Roboto Thin', sans-serif;
            background-color: #f2f5fc;
            background-image: url('/images/2.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #5a5a5c;
            margin: 0;

            padding: 0;
        }
    </style>
@endsection

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.warehouse'))
@section('title-content', mb_strtoupper(__('title.dashboard'), 'UTF-8'))

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(isset($stocks) && count($stocks) > 0)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Préparation des données
            const fullLabels = @json(collect($stocks)->pluck('produit'));
            const stockData = @json(collect($stocks)->pluck('quantite'));
            const seuils = @json(collect($stocks)->map(function ($s) {
                return [
                    'alerte' => $s->seuil_alerte,
                    'reappro' => $s->restock_threshold
                ];
            }));

            // Troncature des libellés pour l'affichage
            const truncatedLabels = fullLabels.map(label =>
                label.length > 20 ? label.substring(0, 20) + '...' : label
            );

            // Génération des couleurs pour le graphique à barres
            const stockColors = stockData.map((qte, index) => {
                if(qte <= seuils[index].alerte) return 'rgba(255, 99, 132, 0.7)';
                if(qte <= seuils[index].reappro) return 'rgba(255, 205, 86, 0.7)';
                return 'rgba(75, 192, 192, 0.7)';
            });

            // Palette de couleurs distinctes pour le camembert
            const pieColors = [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
                'rgba(83, 102, 255, 0.7)',
                'rgba(255, 99, 255, 0.7)',
                'rgba(99, 255, 132, 0.7)'
            ];

            // Tri des stocks les plus bas
            const lowStocks = stockData
                .map((qte, index) => ({ qte, label: fullLabels[index], color: stockColors[index] }))
                .sort((a, b) => a.qte - b.qte)
                .slice(0, 5);

            // Graphique principal
            new Chart(document.getElementById('mainStockChart'), {
                type: 'bar',
                data: {
                    labels: truncatedLabels,
                    datasets: [{
                        label: 'Stock par produit',
                        data: stockData,
                        backgroundColor: stockColors,
                        borderColor: stockColors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 90,
                                minRotation: 45,
                                autoSkip: false
                            }
                        },
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: (items) => fullLabels[items[0].dataIndex]
                            }
                        }
                    }
                }
            });

            // Camembert de répartition
            new Chart(document.getElementById('stockPieChart'), {
                type: 'doughnut',
                data: {
                    labels: truncatedLabels,
                    datasets: [{
                        label: 'Répartition',
                        data: stockData,
                        backgroundColor: pieColors.slice(0, stockData.length), // Utilisez les couleurs distinctes
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 10 } }
                        },
                        tooltip: {
                            callbacks: {
                                title: (items) => fullLabels[items[0].dataIndex]
                            }
                        }
                    }
                }
            });

            // Graphique des stocks critiques
            new Chart(document.getElementById('lowStockChart'), {
                type: 'bar',
                data: {
                    labels: lowStocks.map(s => s.label),
                    datasets: [{
                        label: 'Stocks critiques',
                        data: lowStocks.map(s => s.qte),
                        backgroundColor: lowStocks.map(s => s.color),
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
@endif
@endsection

@section('content')
    <div class="content">
        <div class="container">

            @if(isset($stocks) && count($stocks) > 0)
                <div class="chart-grid">
                    <div class="chart-item">
                        <h3>{{ __('supply.repart_stock') }}</h3>
                        <canvas id="stockPieChart"></canvas>
                    </div>

                    <div class="chart-item">
                        <h3>{{ __('supply.stock_by_product') }}</h3>
                        <canvas id="mainStockChart"></canvas>
                    </div>

                    <div class="chart-item">
                        <h3>{{ __('supply.top_5_stock') }}</h3>
                        <canvas id="lowStockChart"></canvas>
                    </div>
                </div>
            @else
                <p>{{ __('supply.no_stock') }}</p>
            @endif
        </div>
    </div>
@endsection
