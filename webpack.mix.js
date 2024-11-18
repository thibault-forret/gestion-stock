const mix = require('laravel-mix');

// Compile les fichiers JavaScript et SCSS en fichiers CSS et JS respectifs.
mix.js('resources/js/app.js', 'public/js')

    .sass('resources/scss/style.scss', 'public/css/')

    // Pages
    .sass('resources/scss/pages/login.scss', 'public/css/pages/')
    .sass('resources/scss/pages/home.scss', 'public/css/pages/')

    // Warehouse
    .sass('resources/scss/pages/warehouse/dashboard.scss', 'public/css/pages/warehouse/')

    // Store
    .sass('resources/scss/pages/store/dashboard.scss', 'public/css/pages/store/')

    // Fonctionnement :
    // JS : mix.js('chemin_du_fichier_js', 'chemin_du_fichier_js') -> Voir exemple ci-dessus
    // SCSS : mix.sass('chemin_du_fichier_scss', 'chemin_du_fichier_css') -> Voir exemple ci-dessus

    // Versionnage des fichiers
    .version();

// Versionnage ".version()" des fichiers afin de générer des url uniques pour chaques fichier compilé. Cela permet de forcer les navigateurs
// à récupérer les nouvelles verisons des fichiers après une mise à jour, plutôt que d'utiliser une version mise en cache.

// Pour compiler les fichiers, il suffit de lancer la commande "npm run watch" pour compiler les fichiers en temps réel.
