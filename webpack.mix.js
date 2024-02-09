// webpack.mix.js

let mix = require('laravel-mix');

mix.postCss('resources/css/site.css', 'css');
mix.scripts([
    'resources/js/site.js'
], 'public/js/all.js');

mix.webpackConfig({
stats: {
    children: true,
},});


