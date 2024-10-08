<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description')">
    <link 
        rel="icon" 
        type="image/png" 
        sizes="32x25"
        href="{{ asset('images/logo.webp') }}"
    >

    <link href="{{ mix('css/style.css') }}" rel="stylesheet">
</head>

@include('components._header') 

<body>

    <div class="content">

        @yield('content')

    </div>
    
    <script src="{{ mix('js/app.js') }}"></script>

    @hasSection('js')
    	@yield('js')
    @endif    

</body>

@include('components._footer')

</html>
