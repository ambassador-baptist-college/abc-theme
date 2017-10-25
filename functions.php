<?php

const ABC_THEME_VERSION = '1.0.1';

// add minified CSS
function abc_minified_css() {
    // add minified stylesheet
    wp_enqueue_style( 'twentysixteen-style', get_stylesheet_directory_uri() . '/css/main.min.css', array(), ABC_THEME_VERSION );
    wp_register_script( 'video-res', get_stylesheet_directory_uri() . '/js/video-res.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'abc_minified_css', 5 );

// replace default fonts and stylesheet
function abc_webfonts_remove() {
    wp_dequeue_style( 'twentysixteen-fonts' );
}
add_action( 'wp_enqueue_scripts', 'abc_webfonts_remove', 20 );
function abc_webfonts_add() {
    ?><script>
    WebFontConfig = {
        google: {
            families: ['Montserrat:400,400italic,600,600italic', 'Rokkitt:400,700']
        }
    };

    (function(d) {
    var wf = d.createElement('script'), s = d.scripts[0];
    wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6/webfont.js';
    s.parentNode.insertBefore(wf, s);
    })(document);
    </script><?php
    wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_footer', 'abc_webfonts_add' );

// add theme assets
function abc_add_assets() {
    wp_enqueue_style( 'chosen', get_stylesheet_directory_uri() . '/css/chosen.min.css' );
    wp_enqueue_script( 'chosen', get_stylesheet_directory_uri() . '/js/chosen.jquery.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'theme', get_stylesheet_directory_uri() . '/js/theme.min.js', array( 'jquery', 'chosen' ), ABC_THEME_VERSION );
    wp_register_script( 'grad-offering', get_stylesheet_directory_uri() . '/js/grad-offering.min.js', array( 'jquery' ), ABC_THEME_VERSION, true );

    // homepage JS
    if ( is_front_page() ) {
        $json_videos = '';
        $videos = glob( get_stylesheet_directory() . '/video/*.mp4' );
        foreach ( $videos as $video ) {
            $basename = basename( $video, '.mp4' );
            $size_array = explode( 'x', $basename );
            $json_videos[$size_array[1]] = array(
                'width'     => $size_array[0],
                'height'    => $size_array[1],
                'url'       => get_stylesheet_directory_uri() . '/video/' . $basename . '.mp4',
            );
        }
        ksort($json_videos);
        wp_add_inline_script( 'video-res', 'var themeUrl = "' . get_stylesheet_directory_uri() . '", videoRes = ' . json_encode( $json_videos ) );
        wp_enqueue_script( 'video-res' );
    }
}
add_action( 'wp_enqueue_scripts', 'abc_add_assets' );

// add backend styles
function abc_add_backend_styles() {
    wp_register_style( 'abc-backend', get_stylesheet_directory_uri() . '/css/backend.min.css' );

    if ( 'wpfc_sermon' == get_post_type() ) {
        wp_enqueue_style( 'abc-backend' );
    }
}
add_action( 'after_setup_theme', 'abc_custom_image_sizes' );

// add custom image sizes
// default thumbnail
function abc_custom_image_sizes() {
    set_post_thumbnail_size( 2400, 600, true );
    add_image_size( 'thumbnail-tall', 2400, 1280, true );
    add_image_size( 'signature', 300, 60 );
    add_image_size( 'home-square', 75, 75, true );
    add_image_size( 'highlighted-event-small', 600, 300, true );
    add_image_size( 'highlighted-event-medium', 900, 450, true );
}
add_action( 'after_setup_theme', 'abc_custom_image_sizes' );

// Add signature custom image size
function signature_image_size( $sizes ) {
    $sizes['signature'] = 'Signature';
    return $sizes;
}
add_filter( 'image_size_names_choose', 'signature_image_size' );

// override default post meta
function twentysixteen_entry_meta() {
    if ( in_array( get_post_type(), array( 'newsletter' ) ) ) {
        $author_avatar_size = apply_filters( 'twentysixteen_author_avatar_size', 49 );
        printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
            get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
            _x( 'Author', 'Used before post author name.', 'twentysixteen' ),
            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
            get_the_author()
        );
    }

    do_action( 'custom_footer_meta' );

    if ( in_array( get_post_type(), array( 'post', 'attachment', 'newsletter' ) ) ) {
        twentysixteen_entry_date();
    }

    $format = get_post_format();
    if ( current_theme_supports( 'post-formats', $format ) ) {
        printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
            sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentysixteen' ) ),
            esc_url( get_post_format_link( $format ) ),
            get_post_format_string( $format )
        );
    }

    if ( 'post' === get_post_type() ) {
        twentysixteen_entry_taxonomies();
    }

    if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
        comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentysixteen' ), get_the_title() ) );
        echo '</span>';
    }
}

// add footer widget areas 3 and 4
add_action( 'widgets_init', 'abc_add_extra_footer_widgets', 11 );
function abc_add_extra_footer_widgets() {
    register_sidebar( array(
        'name'          => __( 'Content Bottom 3', 'twentysixteen' ),
        'id'            => 'sidebar-4',
        'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Content Bottom 4', 'twentysixteen' ),
        'id'            => 'sidebar-5',
        'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}

// add shortcode for semesters on info request form
add_action( 'wpcf7_init', 'abc_semester_list_init' );
function abc_semester_list_init() {
    wpcf7_add_shortcode( array( 'semester_list' ), 'abc_semester_list_shortcode_handler', true );
}
function abc_semester_list_shortcode_handler() {
    $semester_choices = '<span class="wpcf7-form-control-wrap semester">
        <span class="wpcf7-form-control wpcf7-checkbox">
        ';

    //   determine whether the coming semester is Fall or Spring
    if ( ( date( 'n' ) >= 1 ) AND ( date( 'n' ) <= 8 ) ) {
        $semester_choices .= '<span class="wpcf7-list-item first"><label><input type="checkbox" name="semester[]" value="Fall ' . date ( 'Y' ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . date( 'Y' ) . '</span></label></span>
        <span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date ( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
        <span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Fall ' . ( date ( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
        <span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date ( 'Y' ) + 2 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 2 ) . '</span></label></span>
        ';
    }
    elseif ((date('n') >= 8) AND (date('n') <= 12)) {
        $semester_choices .= '<span class="wpcf7-list-item first"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date ( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
        <span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Fall ' . ( date ( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
        <span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date ( 'Y' ) + 2 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 2 ) . '</span></label></span>
        <span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Fall ' . ( date ( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . ( date( 'Y' ) + 2 ) . '</span></label></span>
        ';
    }
    $semester_choices .= '<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Other">&nbsp;<span class="wpcf7-list-item-label">Other</span></label></span>
    </span>
    </span>';

    return $semester_choices;
}

// Add CPT archive headers
function abc_cpt_archive_headers() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        // add main options page
        acf_add_options_page( array(
            'page_title'    => 'Custom Post Type Archive Headers',
            'menu_title'    => 'CPT Archive Headers',
            'menu_slug'     => 'cpt-archive-headers',
            'capability'    => 'manage_options',
            'redirect'      => false,
        ));
    }
}
add_action( 'init', 'abc_cpt_archive_headers' );

// Add page header if exists
function abc_add_page_thumb() {
    // get CPT archive options
    $cpt_headers = get_field( 'cpt_archive_headers', 'option' );
    $cpts = array();
    foreach ( $cpt_headers as $cpt ) {
        $cpts[$cpt['post_type']] = $cpt['archive_image'];
    }

    if ( is_archive() || is_home() ) {
        foreach( $cpt_headers as $cpt ) {
            if ( array_key_exists( get_post_type(), $cpts ) ) {
                echo abc_header_image( $cpts[get_post_type()] );
            }
        }
    } elseif ( is_singular() ) {
        // single posts
        if ( has_post_thumbnail( $post->ID ) ) {
            echo abc_header_image( get_the_post_thumbnail_url() );
        }
    } elseif ( is_404() ) {
        if ( array_key_exists( '404', $cpts ) ) {
            echo abc_header_image( $cpts['404'] );
        }
    }
}
add_action( 'wp_head', 'abc_add_page_thumb' );

// Return CSS for page header image
function abc_header_image( $post_thumbnail_URL ) {
    return '<style type="text/css">.site-header, .tall .site-header { background-image: url(' . $post_thumbnail_URL . '); }</style>';
}

// Modify the sermon archive title
function filter_sermon_page_title( $title, $id = NULL ) {
    global $post;
    if ( is_post_type_archive( 'wpfc_sermon' ) ) {
        $title = 'Sermon Archive';
    } elseif ( is_tax() && 'wpfc_sermon' == $post->post_type ) {
        global $wp_query;
        $term = $wp_query->get_queried_object();
        $title = 'Sermons: ' . $term->name;
    }
    return $title;
}
add_filter( 'custom_title', 'filter_sermon_page_title' );
add_filter( 'get_the_archive_title', 'filter_sermon_page_title' );

// Remove some MediaJS features for sermons
function abc_sermon_player() {
    wp_localize_script( 'wp-mediaelement', '_wpmejsSettings', array(
        'features'  => array( 'playpause', 'progress' )
    ));
}
add_action( 'wp_footer', 'abc_sermon_player' );

/**
 * Add favicons to <head>
 */
function abc_favicons() {
    ?>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#2e5499">
    <meta name="theme-color" content="#2e5499">
    <?php
}
add_action( 'wp_head', 'abc_favicons' );

/**
 * Trim trailing space from excerpt
 * @param  string $excerpt     default excerpt
 * @param  string $raw_excerpt raw excerpt text
 * @return string excerpt with extra spaces removed
 */
function abc_trim_excerpt( $excerpt, $raw_excerpt ) {
    return str_replace( ' &hellip; ', '&hellip;', $excerpt );
}
add_filter( 'wp_trim_excerpt', 'abc_trim_excerpt', 10, 2 );

include( 'inc/shortcodes.php' );
