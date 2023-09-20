// webpack.mix.js

let mix = require('laravel-mix');

mix.js('resources/js/site.js', 'js').postCss('resources/css/site.css', 'css');

mix.webpackConfig({
stats: {
    children: true,
},});


