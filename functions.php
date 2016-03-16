<?php

// add minified CSS
add_action( 'wp_enqueue_scripts', 'abc_minified_css', 5 );
function abc_minified_css() {
    // add minified stylesheet
    wp_enqueue_style( 'abc-style', get_stylesheet_directory_uri() . '/css/main.min.css' );
}

// replace default fonts and stylesheet
add_action( 'wp_enqueue_scripts', 'abc_webfonts_remove', 20 );
function abc_webfonts_remove() {
    wp_deregister_style( 'twentysixteen-style' );
    wp_deregister_style( 'twentysixteen-fonts' );
}
add_action( 'wp_footer', 'abc_webfonts_add' );
function abc_webfonts_add() {
    ?><script>
      (function(d) {
        var config = {
          kitId: 'umm0dso',
          scriptTimeout: 3000,
          async: true
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script><?php
}

// add custom image sizes
set_post_thumbnail_size( 2400, 600, true );
