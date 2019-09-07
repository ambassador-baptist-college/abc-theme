<?php
/**
 * Theme functions
 *
 * @package WordPress
 * @subpackage ABC_Theme
 */

define( 'ABC_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Add minified stylesheet
 */
function abc_minified_css() {
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_directory_uri() . '/css/style.min.css', array(), ABC_THEME_VERSION );
	wp_register_script( 'video-res', get_stylesheet_directory_uri() . '/js/video-res.min.js', array( 'jquery' ), ABC_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'abc_minified_css', 5 );

/**
 * Replace default fonts and stylesheet
 */
function abc_webfonts_remove() {
	wp_dequeue_style( 'twentysixteen-fonts' );
}
add_action( 'wp_enqueue_scripts', 'abc_webfonts_remove', 20 );

/**
 * Add branding webfonts
 */
function abc_webfonts_add() {
	?><script>
	WebFontConfig = {
		google: {
			families: ['Montserrat:500,500italic,800,800italic', 'Rokkitt:400,700']
		}
	};

	(function(d) {
	var wf = d.createElement('script'), s = d.scripts[0];
	wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6/webfont.js';
	s.parentNode.insertBefore(wf, s);
	})(document);
	</script>
	<?php
	wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_footer', 'abc_webfonts_add' );

/**
 * Add theme assets
 */
function abc_add_assets() {
	wp_enqueue_style( 'chosen', get_stylesheet_directory_uri() . '/css/chosen.min.css' );
	wp_enqueue_script( 'chosen', get_stylesheet_directory_uri() . '/js/chosen.jquery.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'theme', get_stylesheet_directory_uri() . '/js/theme.min.js', array( 'jquery', 'countup', 'chosen' ), ABC_THEME_VERSION, true );
	wp_register_script( 'grad-offering', get_stylesheet_directory_uri() . '/js/grad-offering.min.js', array( 'jquery' ), ABC_THEME_VERSION, true );

	// Handle homepage videos.
	if ( is_page_template( 'template-home.php' ) ) {
		$json_videos = array();
		$videos = glob( get_stylesheet_directory() . '/video/*.mp4' );
		foreach ( $videos as $video ) {
			$basename = basename( $video, '.mp4' );
			$size_array = explode( 'x', $basename );
			$width = $size_array[1];
			$json_videos[ $width ] = array(
				'width'     => $size_array[0],
				'height'    => $size_array[1],
				'url'       => get_stylesheet_directory_uri() . '/video/' . $basename . '.mp4',
			);
		}
		ksort( $json_videos );
		wp_add_inline_script( 'video-res', 'var themeUrl = "' . get_stylesheet_directory_uri() . '", videoRes = ' . json_encode( $json_videos ) );
		wp_enqueue_script( 'video-res' );
	}
}
add_action( 'wp_enqueue_scripts', 'abc_add_assets' );

/**
 * Add backend assets
 */
function abc_add_backend_styles() {
	wp_register_style( 'abc-backend', get_stylesheet_directory_uri() . '/css/backend.min.css' );

	if ( 'wpfc_sermon' == get_post_type() ) {
		wp_enqueue_style( 'abc-backend' );
	}
}
add_action( 'after_setup_theme', 'abc_custom_image_sizes' );

/**
 * Add custom image sizes
 */
function abc_custom_image_sizes() {
	set_post_thumbnail_size( 2400, 600, true );
	add_image_size( 'thumbnail-normal', 2400, 1280, true );
	add_image_size( 'thumbnail-tall', 2400, 1280, true );
	add_image_size( 'signature', 300, 60 );
	add_image_size( 'home-square', 75, 75, true );
	add_image_size( 'highlighted-event-small', 600, 300, true );
	add_image_size( 'highlighted-event-medium', 900, 450, true );
	add_image_size( 'open-graph', 1200, 630, true );
}
add_action( 'after_setup_theme', 'abc_custom_image_sizes' );

/**
 * Add name image size
 *
 * @param  array $sizes Media image sizes.
 * @return array Modified media image sizes
 */
function signature_image_size( $sizes ) {
	$sizes['signature'] = 'Signature';
	return $sizes;
}
add_filter( 'image_size_names_choose', 'signature_image_size' );

/**
 * Override default post meta
 */
function twentysixteen_entry_meta() {
	if ( in_array( get_post_type(), array( 'newsletter' ) ) ) {
		$author_avatar_size = apply_filters( 'twentysixteen_author_avatar_size', 49 );
		printf(
			'<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			esc_attr_x( 'Author', 'Used before post author name.', 'twentysixteen' ),
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
		printf(
			'<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', esc_attr_x( 'Format', 'Used before post format.', 'twentysixteen' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		); // WPCS: XSS ok.
	}

	if ( 'post' === get_post_type() ) {
		twentysixteen_entry_taxonomies();
	}

	if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		// Translators: ignore.
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentysixteen' ), get_the_title() ) );
		echo '</span>';
	}
}

/**
 * Add footer widget areas 3 and 4
 */
function abc_add_extra_footer_widgets() {
	register_sidebar(
		 array(
			 'name'          => __( 'Content Bottom 3', 'twentysixteen' ),
			 'id'            => 'sidebar-4',
			 'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
			 'before_widget' => '<section id="%1$s" class="widget %2$s">',
			 'after_widget'  => '</section>',
			 'before_title'  => '<h2 class="widget-title">',
			 'after_title'   => '</h2>',
		 )
		);

	register_sidebar(
		 array(
			 'name'          => __( 'Content Bottom 4', 'twentysixteen' ),
			 'id'            => 'sidebar-5',
			 'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
			 'before_widget' => '<section id="%1$s" class="widget %2$s">',
			 'after_widget'  => '</section>',
			 'before_title'  => '<h2 class="widget-title">',
			 'after_title'   => '</h2>',
		 )
		);
}
add_action( 'widgets_init', 'abc_add_extra_footer_widgets', 11 );

/**
 * Add shortcode for semesters on info request form
 */
function abc_semester_list_init() {
	wpcf7_add_shortcode( array( 'semester_list' ), 'abc_semester_list_shortcode_handler', true );
}
add_action( 'wpcf7_init', 'abc_semester_list_init' );

/**
 * Add callback handler for CF7 semester_list shortcode
 *
 * @return string HTML content
 */
function abc_semester_list_shortcode_handler() {
	$semester_choices = '<span class="wpcf7-form-control-wrap semester">
		<span class="wpcf7-form-control wpcf7-checkbox">
		';

	// Determine whether the coming semester is Fall or Spring.
	if ( ( date( 'n' ) >= 1 ) && ( date( 'n' ) <= 8 ) ) {
		$semester_choices .= '<span class="wpcf7-list-item first"><label><input type="checkbox" name="semester[]" value="Fall ' . date( 'Y' ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . date( 'Y' ) . '</span></label></span>
		<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
		<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Fall ' . ( date( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
		<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date( 'Y' ) + 2 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 2 ) . '</span></label></span>
		';
	} elseif ( ( date( 'n' ) >= 8 ) && ( date( 'n' ) <= 12 ) ) {
		$semester_choices .= '<span class="wpcf7-list-item first"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
		<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Fall ' . ( date( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . ( date( 'Y' ) + 1 ) . '</span></label></span>
		<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Spring ' . ( date( 'Y' ) + 2 ) . '">&nbsp;<span class="wpcf7-list-item-label">Spring ' . ( date( 'Y' ) + 2 ) . '</span></label></span>
		<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Fall ' . ( date( 'Y' ) + 1 ) . '">&nbsp;<span class="wpcf7-list-item-label">Fall ' . ( date( 'Y' ) + 2 ) . '</span></label></span>
		';
	}
	$semester_choices .= '<span class="wpcf7-list-item"><label><input type="checkbox" name="semester[]" value="Other">&nbsp;<span class="wpcf7-list-item-label">Other</span></label></span>
	</span>
	</span>';

	return $semester_choices;
}

/**
 * Add CPT archive headers
 */
function abc_cpt_archive_headers() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => 'Header Images',
				'menu_title' => 'Header Images',
				'menu_slug'  => 'cpt-archive-headers',
				'capability' => 'manage_options',
				'redirect'   => false,
			)
		);
	}
}
add_action( 'init', 'abc_cpt_archive_headers' );

/**
 * Add custom page header if specified
 */
function abc_add_page_thumb() {

	// If tax archive, check for category image first.
	if ( function_exists( 'z_taxonomy_image_url' ) && ! empty( z_taxonomy_image_url() ) ) {
		$bg_image = z_taxonomy_image_url();
	} elseif ( function_exists( 'get_field' ) ) {

		$bg_image = get_field( 'header_background_image' );
		if ( empty( $bg_image ) ) {
			$cpt_headers = get_field( 'cpt_archive_headers', 'option' );
			if ( ! empty( $cpt_headers ) ) {
				$cpts = array();
				foreach ( $cpt_headers as $cpt ) {
					if ( get_post_type() === $cpt['post_type'] ) {
						$bg_image = $cpt['archive_image'];
						break;
					}
				}
			}

			if ( empty( $bg_image ) ) {
				$bg_image = get_field( 'fallback_image', 'option' );
			}
		}
	}

	echo abc_header_image( $bg_image ); // WPCS: XSS ok because it’s escaped in the function.
}
add_action( 'wp_head', 'abc_add_page_thumb' );

/**
 * Return CSS for page header image
 *
 * @param  string $post_thumbnail_url Post thumbnail URL.
 * @return string Modified image string
 */
function abc_header_image( $post_thumbnail_url ) {
	return '<style type="text/css">.site-header, .tall .site-header { background-image: url(' . esc_url( $post_thumbnail_url ) . '); }</style>';
}

/**
 * Modify the sermon archive title
 *
 * @param  string    $title       Page/archive title.
 * @param  integer [ $id         = NULL] WP post ID.
 * @return string  Page/archive title
 */
function filter_sermon_page_title( $title, $id = null ) {
	global $post;
	if ( is_post_type_archive( 'wpfc_sermon' ) ) {
		$title = 'Sermon Archive';
	} elseif ( is_tax() && 'wpfc_sermon' === $post->post_type ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		$title = 'Sermons: ' . $term->name;
	}
	return $title;
}
add_filter( 'custom_title', 'filter_sermon_page_title' );
add_filter( 'get_the_archive_title', 'filter_sermon_page_title' );

/**
 * Remove some MediaJS features for sermons
 */
function abc_sermon_player() {
	wp_localize_script(
		 'wp-mediaelement', '_wpmejsSettings', array(
			 'features'  => array( 'playpause', 'progress' ),
		 )
		);
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
 *
 * @param  string $excerpt     Default excerpt.
 * @param  string $raw_excerpt Raw excerpt text.
 * @return string excerpt with extra spaces removed
 */
function abc_trim_excerpt( $excerpt, $raw_excerpt ) {
	return str_replace( ' &hellip; ', '&hellip;', $excerpt );
}
add_filter( 'wp_trim_excerpt', 'abc_trim_excerpt', 10, 2 );

/**
 * Show product author name
 */
function abc_product_author() {
	$author_name = get_field( 'author_name' );

	if ( ! empty( $author_name ) ) {
		echo '<p class="entry-meta">by ' . esc_attr( $author_name ) . '</p>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'abc_product_author', 4 );

/**
 * Add preacher name to podcast episodes descriptions.
 *
 * @param null   $check     Whether to proceed with retrieving metadata.
 * @param int    $object_id Object ID.
 * @param string $meta_key  Meta key.
 * @param bool   $single    Whether to return single or not.
 *
 * @return string           Preacher name and date.
 */
function abc_sermon_podcast_preacher( $check, $object_id, $meta_key, $single ) {
	if ( is_feed() && 'sermon_description' === $meta_key ) {
		$terms = get_the_terms( get_the_ID(), 'wpfc_preacher' );
		$check = 'Preached by ' . $terms[0]->name . ' on ' . get_the_date( 'l, F j, Y' );
	}
	return $check;
}
add_filter( 'get_post_metadata', 'abc_sermon_podcast_preacher', 15, 4 );

/**
 * Remove wpfc hook to add sermon to the_content
 */
remove_filter( 'the_content', 'add_wpfc_sermon_content' );

/**
 * Strip out content from sermons content since it’s displayed in the excerpt
 *
 * @param  string $content HTML content.
 * @return string HTML content
 */
function abc_strip_sermon_content( $content ) {
	if ( 'wpfc_sermon' === get_post_type() ) {
		$content = '';
	}

	return $content;
}
add_filter( 'the_content', 'abc_strip_sermon_content' );

/**
 * Auto-complete virtual orders other than banquet tickets
 *
 * @param  string  $order_status Current order status.
 * @param  integer $order_id     WooCommerce order ID.
 * @return string  order status
 */
function abc_autocomplete_virtual_orders( $order_status, $order_id ) {
	$order = wc_get_order( $order_id );
	if ( 'processing' === $order_status && ( 'on-hold' === $order_status || 'pending' === $order_status || 'failed' === $order_status ) ) {
		$virtual_order = null;
		if ( count( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				// Check for banquets.
				foreach ( get_the_terms( $item->get_product_id(), 'product_cat' ) as $term ) {
					if ( 'banquets' === $term['slug'] ) {
						break;
					}
				}
				// Non-banquet tickets.
				if ( 'line_item' === $item['type'] ) {
					$_product = $order->get_product_from_item( $item );
					if ( ! $_product->is_virtual() ) {
						$virtual_order = false;
						break;
					} else {
						$virtual_order = true;
					}
				}
			}
		}
		if ( $virtual_order ) {
			return 'completed';
		}
	}

	return $order_status;
}
add_filter( 'woocommerce_payment_complete_order_status', 'abc_autocomplete_virtual_orders', 10, 2 );

/**
 * Add link to registration form for Tribe Events
 *
 * @param  string $content HTML post content.
 * @return string HTML post content
 */
function abc_events_form_link( $content ) {
	if ( 'tribe_events' === get_post_type() ) {
		if ( tribe_events_has_tickets_on_sale( get_the_ID() ) ) {
			$content = '<p><a class="button" href="#register-form">Register Online<span class="dashicons dashicons-arrow-down-alt2"></span></a></p>' . $content;
		}
	}

	return $content;
}
add_filter( 'the_content', 'abc_events_form_link' );

/**
 * Return number of words for homepage news excerpts
 *
 * @return integer Excerpt length
 */
function abc_return_news_excerpt_length() {
	return 13;
}

/**
 * Remove payment info before storing form data
 *
 * @param  array $form_data Submitted form data.
 * @return array Modified form data
 */
function abc_cfdb7_before_save_data( $form_data ) {
	// Remove credit card data.
	unset( $form_data['credit-card'] );
	unset( $form_data['cvv'] );
	unset( $form_data['expiration-month'] );
	unset( $form_data['expiration-year'] );

	// Remove useless data.
	unset( $form_data['g-recaptcha-response'] );

	return $form_data;
}
add_filter( 'cfdb7_before_save_data', 'abc_cfdb7_before_save_data' );

/**
 * Remove recaptcha from [all-fields] output.
 *
 * @param string $value   Previous output.
 * @param string $k       Field label.
 * @param string $v       Value of the field.
 * @param string $format  Either "html" or "text".
 *
 * @return string
 */
function abc_all_fields_exclude_captcha( $value, $k, $v, $format ) {
	if ( 'G Recaptcha Response' === $k ) {
		$value = '';
	}

	return $value;
}
add_filter( 'wpcf7_send_all_fields_format_item', 'abc_all_fields_exclude_captcha' );

/**
 * Include shortcodes
 */
require_once 'inc/shortcodes.php';
