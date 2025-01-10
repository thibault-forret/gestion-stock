<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Font Awesome</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <button id="sidebar-toggle" class="nav-button">
                <img src="{{ asset('images/menu-burger.svg') }}" alt="sidebar">
            </button>
        </div>
        <div class="navbar-center">
            <div class="logo">
                <img src="{{ asset('images/logoNova.png') }}" alt="Logo" class="navbar-logo">
            </div>
        </div>
        <div class="navbar-right">
            @foreach($available_locales as $locale_name => $available_locale)
                @switch($available_locale)
                    @case('fr')
                        @if($available_locale === $current_locale)
                            <img src="{{ asset('images/france.png') }}" alt="Français">
                        @else
                            <a href="{{ route('lang.switch', $available_locale) }}">
                                <img src="{{ asset('images/france.png') }}" alt="Français">
                            </a>
                        @endif
                        @break
                    @case('en')
                        @if($available_locale === $current_locale)
                            <img src="{{ asset('images/etats-unis.png') }}" alt="English">
                        @else
                            <a href="{{ route('lang.switch', $available_locale) }}">
                                <img src="{{ asset('images/etats-unis.png') }}" alt="English">
                            </a>
                        @endif
                    @break
                @endswitch
            @endforeach
            <button class="nav-button">
                <img src="{{ asset('images/porte.svg') }}" alt="deconnexion">
            </button>
            <button class="nav-button">
                <img src="{{ asset('images/utilisateur.svg') }}" alt="utilisateurActuel">
            </button>
        </div>
    </nav>

    <div id="sidebar" class="sidebar">
        <button id="close-sidebar" class="close-button">
            <img src="{{ asset('images/croix.svg') }}" alt="fermer">
        </button>
        <ul class="menu-list">
            <li class="menu-item active">
                <div class="icon-container">
                    <img src="{{ asset('images/sablier.svg') }}" alt="commandeEnCours">
                </div>
                <div class="text-container">Commandes en cours</div>
            </li>
            <li class="menu-item">
                <div class="icon-container">
                    <img src="{{ asset('images/des-boites.svg') }}" alt="commandeDeMasse">
                </div>
                <div class="text-container">Commande de masse</div>
            </li>
            <li class="menu-item">
                <div class="icon-container">
                    <img src="{{ asset('images/utilisateur(1).svg') }}" alt="listeUtilisateur">
                </div>
                <div class="text-container">Utilisateur</div>
            </li>
            <li class="menu-item">
                <div class="icon-container">
                    <img src="{{ asset('images/entrepot.svg') }}" alt="entrepot">
                </div>
                <div class="text-container">Entrepôt/magasin</div>
            </li>
            <li class="menu-item">
                <div class="icon-container">
                    <img src="{{ asset('images/boite-ouverte-pleine.svg') }}" alt="produit">
                </div>
                <div class="text-container">Produit</div>
            </li>
        </ul>
    </div>
</body>
</html>
