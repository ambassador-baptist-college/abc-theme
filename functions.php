<?php

// add minified CSS
add_action( 'wp_enqueue_scripts', 'abc_minified_css', 5 );
function abc_minified_css() {
    // add minified stylesheet
    wp_enqueue_style( 'twentysixteen-style', get_stylesheet_directory_uri() . '/css/main.min.css' );
}

// replace default fonts and stylesheet
add_action( 'wp_enqueue_scripts', 'abc_webfonts_remove', 20 );
function abc_webfonts_remove() {
    wp_dequeue_style( 'twentysixteen-fonts' );
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
    wp_enqueue_style( 'dashicons' );
}

// add custom image sizes
set_post_thumbnail_size( 2400, 600, true );

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
        $semester_choices .= '<span class="wpcf7-list-item first"><label><input type="checkbox" name="semester[]" value="Spring ' . date ( 'Y' ) + 1 . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
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

// Add signature custom image size
function signature_image_size( $sizes ) {
    $new_sizes = array(
        'signature'       => 'Signature',
    );
    return array_merge( $sizes, $new_sizes );
}
add_filter( 'image_size_names_choose', 'signature_image_size' );
add_image_size( 'signature', 300, 60 );

// Add page header if exists
function abc_add_page_thumb() {
    if ( ! is_archive() && ! is_search() && has_post_thumbnail( $post->ID ) ) {
        echo '<style type="text/css">.site-header:before { background-image: url(' . get_the_post_thumbnail_url() . '); }</style>';
    }
}
add_action( 'wp_footer', 'abc_add_page_thumb' );
