<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage ABC_theme
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<?php
	if ( 'page' == get_post_type() && function_exists( 'get_field' ) && get_field( 'tall_header_image' ) ) {
	$body_class_string = $post->post_name . ' tall';
	} elseif ( is_singular() ) {
	$body_class_string = $post->post_name;
	} else {
	$body_class_string = '';
	}

	if ( is_front_page() ) {
	$site_title_tag = 'h1';
	} else {
	$site_title_tag = 'p';
	}
?>

<body <?php body_class( $body_class_string ); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'twentysixteen' ); ?></a>
	<header id="masthead" class="site-header" role="banner">
		<div class="site-header-main site-inner site-content">
			<div class="site-branding">
				<<?php echo esc_attr( $site_title_tag ); ?> class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Ambassador Baptist College" rel="home">
					<?php include( get_stylesheet_directory() . '/img/ABC-logo.svg' ); ?>
				</a></<?php echo esc_attr( $site_title_tag ); ?>>
			</div><!-- .site-branding -->

			<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) ) : ?>
				<button id="menu-toggle" class="menu-toggle"><?php esc_html_e( 'Menu', 'twentysixteen' ); ?></button>

				<div id="site-header-menu" class="site-header-menu">
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
						<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php echo esc_attr( 'Primary Menu', 'twentysixteen' ); ?>">
							<?php
								wp_nav_menu(
									 array(
										 'theme_location' => 'primary',
										 'menu_class'     => 'primary-menu',
									 )
									);
							?>
						</nav><!-- .main-navigation -->
					<?php endif; ?>

					<?php if ( has_nav_menu( 'social' ) ) : ?>
						<nav id="social-navigation" class="social-navigation" role="navigation" aria-label="<?php echo esc_attr( 'Social Links Menu', 'twentysixteen' ); ?>">
							<?php
								wp_nav_menu(
									 array(
										 'theme_location' => 'social',
										 'menu_class'     => 'social-links-menu',
										 'depth'          => 1,
										 'link_before'    => '<span class="screen-reader-text">',
										 'link_after'     => '</span>',
									 )
									);
							?>
						</nav><!-- .social-navigation -->
					<?php endif; ?>
				</div><!-- .site-header-menu -->
			<?php endif; ?>
			<header class="entry-header">
				<h1 class="entry-title">
				<?php
				if ( is_front_page() ) {
					echo '';
				} elseif ( is_home() ) {
					echo 'News Archive';
				} elseif ( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) ) {
					echo 'Bookstore';
				} elseif ( is_archive() ) {
					echo esc_attr( apply_filters( 'custom_title', ucfirst( get_post_type() ) . ' Archive' ) );
				} elseif ( is_404() ) {
					echo 'Not Found';
				} else {
					apply_filters( 'custom_title', the_title() );
				}
				?>
				</h1>
			</header><!-- .entry-header -->
		</div><!-- .site-header-main -->
	</header><!-- .site-header -->

	<div class="site-inner">
		<div id="content" class="site-content">
