<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Font Awesome</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div id="overlay" class="overlay"></div>
    <nav class="navbar">
        <div class="navbar-left">
            <button id="sidebar-toggle" class="nav-button">
                <img src="{{ asset('images/menu-burger.svg') }}" alt="sidebar">
            </button>
        </div>
        <div class="navbar-center">
            <div class="logo">
                <a href="{{ route('warehouse.dashboard') }}">
                    <img src="{{ asset('images/logoSimple.png') }}" alt="Logo" class="navbar-logo">
                </a>
            </div>
        </div>
        <div class="navbar-right">
            <form action="{{ route('lang.switch') }}" method="GET">
                <select name="locale" id="lang-select" onchange="this.form.submit();">
                    @foreach($available_locales as $locale_name => $available_locale)
                        <option value="{{ $available_locale }}" {{ $available_locale === $current_locale ? 'selected' : '' }}>
                            {{ ucfirst($locale_name) }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('store.logout') }}" class="nav-button">
                <img src="{{ asset('images/porte.svg') }}" alt="deconnexion">
            </a>
            <button class="nav-button">
                <img src="{{ asset('images/utilisateur.svg') }}" alt="utilisateurActuel">
            </button>
        </div>
    </nav>

    <div id="sidebar" class="sidebar">
        <button id="close-sidebar" class="close-button">
            <img src="{{ asset('images/croix.svg') }}" alt="fermer">
        </button>
        <ul>
            <li>
                <a href="{{ route('store.dashboard') }}" class="{{ Route::is('store.dashboard*') ? 'active-page' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width=25 height=25 fill="currentColor" class="icon">
                        <path d="M23.9,11.437A12,12,0,0,0,0,13a11.878,11.878,0,0,0,3.759,8.712A4.84,4.84,0,0,0,7.113,23H16.88a4.994,4.994,0,0,0,3.509-1.429A11.944,11.944,0,0,0,23.9,11.437Zm-4.909,8.7A3,3,0,0,1,16.88,21H7.113a2.862,2.862,0,0,1-1.981-.741A9.9,9.9,0,0,1,2,13,10.014,10.014,0,0,1,5.338,5.543,9.881,9.881,0,0,1,11.986,3a10.553,10.553,0,0,1,1.174.066,9.994,9.994,0,0,1,5.831,17.076ZM7.807,17.285a1,1,0,0,1-1.4,1.43A8,8,0,0,1,12,5a8.072,8.072,0,0,1,1.143.081,1,1,0,0,1,.847,1.133.989.989,0,0,1-1.133.848,6,6,0,0,0-5.05,10.223Zm12.112-5.428A8.072,8.072,0,0,1,20,13a7.931,7.931,0,0,1-2.408,5.716,1,1,0,0,1-1.4-1.432,5.98,5.98,0,0,0,1.744-5.141,1,1,0,0,1,1.981-.286Zm-5.993.631a2.033,2.033,0,1,1-1.414-1.414l3.781-3.781a1,1,0,1,1,1.414,1.414Z"/>
                    </svg>
                    <div class="text">{{ __('title.dashboard') }}</div>
                </a>
            </li>
            <li class="has-submenu">
                <div class="menu-item-container">
                    <a href="{{ route('store.order.index') }}" class="{{ Route::is('store.order*') ? 'active-page' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                            <path d="M9,22c0,1.105-.895,2-2,2s-2-.895-2-2,.895-2,2-2,2,.895,2,2Zm8-2c-1.105,0-2,.895-2,2s.895,2,2,2,2-.895,2-2-.895-2-2-2ZM5.419,13l-.941-8h5.591c.087-.699,.262-1.369,.518-2H4.242l-.041-.351c-.178-1.511-1.459-2.649-2.979-2.649H0V2H1.222c.507,0,.934,.38,.993,.884l1.584,13.467c.178,1.511,1.459,2.649,2.979,2.649h13.222v-2H6.778c-.507,0-.934-.38-.993-.884l-.131-1.116H21.835l.363-2H5.419ZM24,6c0,3.309-2.691,6-6,6s-6-2.691-6-6S14.691,0,18,0s6,2.691,6,6Zm-2,0c0-2.206-1.794-4-4-4s-4,1.794-4,4,1.794,4,4,4,4-1.794,4-4Zm-3-3h-2v3.414l2.293,2.293,1.414-1.414-1.707-1.707V3Z"/>
                        </svg>
                        <div class="text">{{ __('title.order') }}</div>
                    </a>
                    <div class="arrow-button {{ Route::is('store.order*') ? 'active-page' : '' }}"><span>&#9660;</span></div>
                </div>
                <ul class="submenu">
                    <li><a href="{{ route('store.order.new') }}"><div class="text">{{ __('title.new_order') }}</div></a></li>
                    <li><a href="{{ route('store.order.list') }}"><div class="text">{{ __('title.order_list') }}</div></a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('store.invoice.list') }}" class="{{ Route::is('store.invoice*') ? 'active-page' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                        <path d="M24,20c0,1.654-1.346,3-3,3v1h-2v-1c-1.654,0-3-1.346-3-3h2c0,.551,.448,1,1,1h2c.552,0,1-.449,1-1,0-.378-.271-.698-.644-.76l-3.041-.507c-1.342-.223-2.315-1.373-2.315-2.733,0-1.654,1.346-3,3-3v-1h2v1c1.654,0,3,1.346,3,3h-2c0-.551-.448-1-1-1h-2c-.552,0-1,.449-1,1,0,.378,.271,.698,.644,.76l3.041,.507c1.342,.223,2.315,1.373,2.315,2.733Zm-9.899-5c.152-.743,.482-1.416,.924-2H5v7H14v-2H7v-3h7.101ZM5,11h5v-2H5v2Zm5-6H5v2h5v-2Zm6.031,19H1V3C1,1.346,2.346,0,4,0H13.414l7.586,7.586v2.414h-2v-1h-7V2H4c-.551,0-1,.449-1,1V22H14.424c.352,.801,.913,1.483,1.607,2ZM14,7h3.586l-3.586-3.586v3.586Z"/>
                    </svg>
                    <div class="text">{{ __('title.invoice') }}</div>
                </a>
            </li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des clics sur les boutons de flèche
            document.querySelectorAll('.sidebar .arrow-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Empêcher la propagation du clic au parent
                    const submenu = this.parentElement.nextElementSibling; // Le sous-menu est l'élément suivant

                    // Basculer l'état ouvert/fermé du sous-menu
                    if (submenu.classList.contains('open')) {
                        submenu.classList.remove('open');
                        this.classList.remove('open');
                    } else {
                        submenu.classList.add('open');
                        this.classList.add('open');
                    }
                });
            });
        });
    </script>
</body>
</html>
