<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="site-inner">
            <?php get_sidebar( 'content-bottom' ); ?>

            <?php if ( has_nav_menu( 'primary' ) ) : ?>
                <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Primary Menu', 'twentysixteen' ); ?>">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'primary-menu',
                         ) );
                    ?>
                </nav><!-- .main-navigation -->
            <?php endif; ?>

            <?php if ( has_nav_menu( 'social' ) ) : ?>
                <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'twentysixteen' ); ?>">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'social',
                            'menu_class'     => 'social-links-menu',
                            'depth'          => 1,
                            'link_before'    => '<span class="screen-reader-text">',
                            'link_after'     => '</span>',
                        ) );
                    ?>
                </nav><!-- .social-navigation -->
            <?php endif; ?>
        </div><!-- .site-inner -->
    </footer><!-- .site-footer -->
    <footer class="site-info">
        <div class="site-inner">
            <?php
                /**
                 * Fires before the twentysixteen footer text for footer customization.
                 *
                 * @since Twenty Sixteen 1.0
                 */
                do_action( 'twentysixteen_credits' );
            ?>
            <span class="site-copyright">&copy;<?php echo date( 'Y' ); ?></span> <span class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span> | Developed by <a href="https://andrewrminion.com/?ref=ambassadors.edu">AndrewRMinion Design</a> | All Rights Reserved.
        </div><!-- .site-inner -->
    </footer><!-- .site-info -->
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
