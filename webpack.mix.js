const mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');

// Fonction pour récupérer tous les fichiers sauf ceux qui commencent par un underscore
function getFiles(dir, fileType) 
{
    let files = fs.readdirSync(dir);
    let fileList = [];

    files.forEach(file => {
        let filePath = path.join(dir, file);
        let stat = fs.statSync(filePath);

        if (stat.isDirectory()) {
            fileList = fileList.concat(getFiles(filePath, fileType));
        } else if (filePath.endsWith(fileType) && !path.basename(file).startsWith('_')) {
            fileList.push(filePath);
        }
    });

    return fileList;
}

// Récupère tous les fichiers SCSS sauf ceux qui commencent par un underscore
const scssFiles = getFiles('resources/scss', '.scss');

// Compile chaque fichier SCSS trouvé
scssFiles.forEach(file => {
    let output = file.replace('resources/scss', 'public/css').replace('.scss', '.css');
    mix.sass(file, output).version();;
});

// Compile les fichiers JavaScript
mix.js('resources/js/app.js', 'public/js')
    .version();

// Versionnage ".version()" des fichiers afin de générer des url uniques pour chaques fichier compilé. Cela permet de forcer les navigateurs
// à récupérer les nouvelles verisons des fichiers après une mise à jour, plutôt que d'utiliser une version mise en cache.
