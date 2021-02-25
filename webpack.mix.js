// webpack.mix.js

let mix = require('laravel-mix');

mix.sass('scss/backend.scss', 'css/backend.min.css').options({
	processCssUrls: false,
});
mix.sass('scss/chosen.scss', 'css/chosen.min.css').options({
	processCssUrls: false,
});
mix.sass('scss/style.scss', 'css/style.min.css').options({
	processCssUrls: false,
});

mix.js('js/chosen.jquery.js', 'js/chosen.jquery.min.js');
mix.js('js/countup.js', 'js/countup.min.js');
mix.js('js/csr.js', 'js/csr.min.js');
mix.js('js/grad-offering.js', 'js/grad-offering.min.js');
mix.js('js/theme.js', 'js/theme.min.js');
mix.js('js/video-res.js', 'js/video-res.min.js');
