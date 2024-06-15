const mix = require('laravel-mix');

mix.webpackConfig({
    watchOptions: {
        ignored: /node_modules/
    },
    stats: {
        children: true,
    },
    devServer: {
        hot: true, // Enable hot module replacement
    },
    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    }
})
.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css')
.css('resources/css/app.css', 'public/css')
.copy('node_modules/bulma/css/bulma.css', 'public/css')
.vue();
