// webpack.mix.js

let mix = require('laravel-mix');

mix.css('resources/css/site.css', 'css').js('resources/js/site.js', 'js');

mix.js('resources/js/app.js', 'js');
mix.js('resources/js/bootstrap.js', 'js');
