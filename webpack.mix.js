const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
mix.js('resources/js/ad-data.js', 'public/js')
mix.js('resources/js/policy.js', 'public/js')
mix.js('resources/js/proxy.js', 'public/js')
mix.js('resources/js/network.js', 'public/js')
mix.js('resources/js/system.js', 'public/js')
mix.js('resources/js/query-builder.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
