// webpack.mix.js

let mix = require('laravel-mix');

mix.postCss('resources/css/site.css', 'css');

mix.webpackConfig({
stats: {
    children: true,
},});


