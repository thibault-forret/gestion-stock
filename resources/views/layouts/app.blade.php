<!DOCTYPE html>
<html lang="{{ $current_locale }}" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description')">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="{{ mix('css/style.css') }}" rel="stylesheet">

    <style>
        .alert {
            width: 60%;
            padding: 10px 15px;
            margin: 10px auto;
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
    </style>

    @hasSection('css')
    	@yield('css')
    @endif
</head>

@if (!isset($removeHeader))
    @if (request()->is('warehouse*'))
        @include('components._warehouse_header')
    @elseif (request()->is('store*'))
        @include('components._store_header')
    @endif
@endif

<body>

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

    <div class="content">

        @yield('content')

    </div>

    <script src="{{ mix('js/app.js') }}"></script>

    @if (!isset($removeHeader))
        <script src="{{ mix('js/sidebar.js') }}"></script>
    @endif

    @hasSection('js')
    	@yield('js')
    @endif

</body>

@if (!isset($login))
    @include('components._footer')
@endif

</html>
