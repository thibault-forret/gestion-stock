const mix = require('laravel-mix');

// Compile les fichiers JavaScript et SCSS en fichiers CSS et JS respectifs.
mix.js('resources/js/app.js', 'public/js')

    .js('resources/js/sidebar.js', 'public/js/')

    .sass('resources/scss/style.scss', 'public/css/')

    // Pages
    .sass('resources/scss/pages/login.scss', 'public/css/pages/')
    .sass('resources/scss/pages/home.scss', 'public/css/pages/')

    // Warehouse
    .sass('resources/scss/pages/warehouse/dashboard.scss', 'public/css/pages/warehouse/')
    .sass('resources/scss/pages/warehouse/search_new_product.scss', 'public/css/pages/warehouse/')

    .sass('resources/scss/pages/warehouse/add_product.scss', 'public/css/pages/warehouse/')

    .sass('resources/scss/pages/warehouse/supply/index.scss', 'public/css/pages/warehouse/supply/')
    .sass('resources/scss/pages/warehouse/invoice/index.scss', 'public/css/pages/warehouse/invoice/')
    .sass('resources/scss/pages/warehouse/supply/place.scss', 'public/css/pages/warehouse/supply/')

    .sass('resources/scss/pages/warehouse/stock/movement_list.scss', 'public/css/pages/warehouse/stock/')

    // Store
    .sass('resources/scss/pages/store/dashboard.scss', 'public/css/pages/store/')
    .sass('resources/scss/pages/store/order/place.scss', 'public/css/pages/store/order/')
    .sass('resources/scss/pages/store/order/liste.scss', 'public/css/pages/store/order/')
    .sass('resources/scss/pages/store/order/recap.scss', 'public/css/pages/store/order/')
    //.sass('resources/scss/pages/store/order/detail.scss', 'public/css/pages/store/order/')

    // Fonctionnement :
    // JS : mix.js('chemin_du_fichier_js', 'chemin_du_fichier_js') -> Voir exemple ci-dessus
    // SCSS : mix.sass('chemin_du_fichier_scss', 'chemin_du_fichier_css') -> Voir exemple ci-dessus

    // Versionnage des fichiers
    .version();

// Versionnage ".version()" des fichiers afin de générer des url uniques pour chaque fichier compilé. Cela permet de forcer les navigateurs
// à récupérer les nouvelles versions des fichiers après une mise à jour, plutôt que d'utiliser une version mise en cache.

// Pour compiler les fichiers, il suffit de lancer la commande "npm run watch" pour compiler les fichiers en temps réel.
// Pour utiliser les fichiers compilés, il suffit de les inclure dans les fichiers Blade avec la fonction "mix('chemin_du_fichier')".
