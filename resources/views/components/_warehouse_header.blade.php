<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <img src="{{ asset('images/logoNova.png') }}" alt="Logo" class="navbar-logo">
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

            <a href="{{ route('warehouse.logout') }}" class="nav-button">
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
                <a href="{{ route('warehouse.dashboard') }}" class="{{ Route::is('warehouse.dashboard*') ? 'active-page' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width=25 height=25 fill="currentColor" class="icon">
                        <path d="M23.9,11.437A12,12,0,0,0,0,13a11.878,11.878,0,0,0,3.759,8.712A4.84,4.84,0,0,0,7.113,23H16.88a4.994,4.994,0,0,0,3.509-1.429A11.944,11.944,0,0,0,23.9,11.437Zm-4.909,8.7A3,3,0,0,1,16.88,21H7.113a2.862,2.862,0,0,1-1.981-.741A9.9,9.9,0,0,1,2,13,10.014,10.014,0,0,1,5.338,5.543,9.881,9.881,0,0,1,11.986,3a10.553,10.553,0,0,1,1.174.066,9.994,9.994,0,0,1,5.831,17.076ZM7.807,17.285a1,1,0,0,1-1.4,1.43A8,8,0,0,1,12,5a8.072,8.072,0,0,1,1.143.081,1,1,0,0,1,.847,1.133,.989.989,0,0,1-1.133.848,6,6,0,0,0-5.05,10.223Zm12.112-5.428A8.072,8.072,0,0,1,20,13a7.931,7.931,0,0,1-2.408,5.716,1,1,0,0,1-1.4-1.432,5.98,5.98,0,0,0,1.744-5.141,1,1,0,0,1,1.981-.286Zm-5.993.631a2.033,2.033,0,1,1-1.414-1.414l3.781-3.781a1,1,0,1,1,1.414,1.414Z"/>
                    </svg>
                    <div class="text">{{ __('title.dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('warehouse.product.index')}}" class="{{ Route::is('warehouse.product*') ? 'active-page' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                        <path d="M23.576,6.429l-1.91-3.171L12,.036,2.334,3.258,.442,6.397c-.475,.792-.563,1.742-.243,2.607,.31,.839,.964,1.488,1.8,1.793l-.008,9.844,10,3.333,10-3.333,.008-9.844c.846-.296,1.507-.946,1.819-1.788,.317-.857,.229-1.797-.242-2.582Zm-5.737-2.338l-5.831,1.946-5.833-1.951,5.825-1.942,5.839,1.946ZM2.156,7.428l1.292-2.145,7.048,2.357-1.529,2.549c-.239,.398-.735,.581-1.173,.434l-5.081-1.693c-.297-.099-.53-.324-.639-.618-.108-.293-.079-.616,.082-.883Zm1.843,4.038l3.163,1.054c1.343,.448,2.792-.088,3.521-1.302l.316-.526-.005,10.843-7-2.333,.006-7.735Zm8.994,10.068l.005-10.849,.319,.532c.556,.928,1.532,1.459,2.561,1.459,.319,0,.643-.051,.96-.157l3.161-1.053-.006,7.734-7,2.333Zm8.95-13.216c-.105,.285-.331,.503-.619,.599l-5.118,1.706c-.438,.147-.934-.035-1.173-.434l-1.526-2.543,7.051-2.353,1.305,2.167c.156,.26,.186,.573,.08,.858Z"/>
                    </svg>
                    <div class="text">{{ __('title.products') }}</div>
                </a>
            </li>
            <li class="has-submenu">
                <div class="menu-item-container">
                    <a href="{{ route('warehouse.stock.index') }}" class="{{ Route::is('warehouse.stock*') ? 'active-page' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                            <path d="M22.849,7.68L13.849,.637c-1.088-.852-2.609-.852-3.697,0L1.151,7.68c-.731,.572-1.151,1.434-1.151,2.363v13.957H6V13c0-.551,.448-1,1-1h10c.552,0,1,.449,1,1v11h6V10.043c0-.929-.42-1.791-1.151-2.363Zm-.849,14.32h-2V13c0-1.654-1.346-3-3-3H7c-1.654,0-3,1.346-3,3v9H2V10.043c0-.31,.14-.597,.384-.788L11.384,2.212c.363-.284,.869-.284,1.232,0l9,7.043c.244,.191,.384,.478,.384,.788v11.957Zm-14-2h3v4h-3v-4Zm0-6h3v4h-3v-4Zm5,6h3v4h-3v-4Z"/>
                        </svg>
                        <div class="text">{{ __('title.stock') }}</div>
                    </a>
                    <div class="arrow-button {{ Route::is('warehouse.stock*') ? 'active-page' : '' }}"><span>&#9660;</span></div>
                </div>
                <ul class="submenu">
                    <li class="has-submenu-of-submenu">
                        <div class="menu-item-container">
                            <a href="{{ route('warehouse.stock.supply.index') }}">
                                <div class="text">Approvisionner un produit</div>
                            </a>
                            <div class="arrow-button"><span>&#9660;</span></div>
                        </div>
                        <ul class="submenu-of-submenu">
                            <li><a href="{{ route('warehouse.stock.supply.new') }}"><div class="text">Nouvel approvisionnement</div></a></li>
                            <li><a href="{{ route('warehouse.stock.supply.list') }}"><div class="text">Liste des approvisionnements</div></a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('warehouse.stock.list') }}"><div class="text">Liste des produits en stock</div></a></li>
                    <li><a href="{{ route('warehouse.stock.list.movement') }}"><div class="text">Liste des mouvements de stock</div></a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('warehouse.order.list') }}" class="{{ Route::is('warehouse.order*') ? 'active-page' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                        <path d="M9,22c0,1.105-.895,2-2,2s-2-.895-2-2,.895-2,2-2,2,.895,2,2Zm8-2c-1.105,0-2,.895-2,2s.895,2,2,2,2-.895,2-2-.895-2-2-2ZM5.419,13l-.941-8h5.591c.087-.699,.262-1.369,.518-2H4.242l-.041-.351c-.178-1.511-1.459-2.649-2.979-2.649H0V2H1.222c.507,0,.934,.38,.993,.884l1.584,13.467c.178,1.511,1.459,2.649,2.979,2.649h13.222v-2H6.778c-.507,0-.934-.38-.993-.884l-.131-1.116H21.835l.363-2H5.419ZM24,6c0,3.309-2.691,6-6,6s-6-2.691-6-6S14.691,0,18,0s6,2.691,6,6Zm-2,0c0-2.206-1.794-4-4-4s-4,1.794-4,4,1.794,4,4,4,4-1.794,4-4Zm-3-3h-2v3.414l2.293,2.293,1.414-1.414-1.707-1.707V3Z"/>
                      </svg>
                    <div class="text">{{ __('title.order') }}</div>
                </a>
            </li>
            <li class="has-submenu">
                <div class="menu-item-container">
                    <a href="{{ route('warehouse.invoice.index') }}" class="{{ Route::is('warehouse.invoice*') ? 'active-page' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                            <path d="M24,20c0,1.654-1.346,3-3,3v1h-2v-1c-1.654,0-3-1.346-3-3h2c0,.551,.448,1,1,1h2c.552,0,1-.449,1-1,0-.378-.271-.698-.644-.76l-3.041-.507c-1.342-.223-2.315-1.373-2.315-2.733,0-1.654,1.346-3,3-3v-1h2v1c1.654,0,3,1.346,3,3h-2c0-.551-.448-1-1-1h-2c-.552,0-1,.449-1,1,0,.378,.271,.698,.644,.76l3.041,.507c1.342,.223,2.315,1.373,2.315,2.733Zm-9.899-5c.152-.743,.482-1.416,.924-2H5v7H14v-2H7v-3h7.101ZM5,11h5v-2H5v2Zm5-6H5v2h5v-2Zm6.031,19H1V3C1,1.346,2.346,0,4,0H13.414l7.586,7.586v2.414h-2v-1h-7V2H4c-.551,0-1,.449-1,1V22H14.424c.352,.801,.913,1.483,1.607,2ZM14,7h3.586l-3.586-3.586v3.586Z"/>
                        </svg>
                        <div class="text">{{ __('title.invoice') }}</div>
                    </a>
                    <div class="arrow-button {{ Route::is('warehouse.invoice*') ? 'active-page' : '' }}"><span>&#9660;</span></div>
                </div>
                <ul class="submenu">
                    <li><a href="{{ route('warehouse.invoice.list.supply') }}"><div class="text">Approvisionnement entrepôt</div></a></li>
                    <li><a href="{{ route('warehouse.invoice.list.order') }}"><div class="text">Commande magasin</div></a></li>
                </ul>
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
