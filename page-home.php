<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post(); ?>

        <section class="video streaming home-stripe off-air">
        <?php
            if ( get_field( 'live_streaming' ) ) {
                the_field( 'live_streaming' );
            }
        ?>
        </section><!-- .apply -->

            <?php
            // Include the page content template. ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php
                    // page content
                    the_content();

                    wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ) );
                    ?>
                </div><!-- .entry-content -->

                <?php
                    edit_post_link(
                        sprintf(
                            /* translators: %s: Name of current post */
                            __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
                            get_the_title()
                        ),
                        '<footer class="entry-footer"><span class="edit-link">',
                        '</span></footer><!-- .entry-footer -->'
                    );
                ?>

            </article><!-- #post-## -->

            <?php // End of the loop.
        endwhile;
        ?>

        <section class="home-features home-stripe">
            <section class="events">
                <header>
                    <h2 class="entry-title">Events</h2>
                </header>
                <?php
                    if ( get_field( 'events_content' ) ) {
                        the_field( 'events_content' );
                    }
                    if ( get_field( 'events_link' ) ) {
                        echo '<p class="read-more"><a href="' . get_field( 'events_link' ) . '">Read more</a></p>';
                    }
                    if ( get_field( 'events_background' ) ) {
                        $home_features_styles .= '.home-features .events { background-image: url(\'' . get_field( 'events_background' ) . '\'); }';
                    }
                ?>
            </section>
            <section class="news">
                <header>
                    <h2 class="entry-title">News</h2>
                </header>
                <?php
                    // WP_Query arguments
                    $args = array (
                        'post_type'              => array( 'post' ),
                        'posts_per_page'         => '5',
                        'post_status'            => 'publish',
                    );

                    // The Query
                    $news_query = new WP_Query( $args );
                    // The Loop
                    if ( $news_query->have_posts() ) {
                        echo '<ul>';
                        while ( $news_query->have_posts() ) {
                            $news_query->the_post();
                            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>' . "\n";
                        }
                        echo '</ul>
                        <p class="read-more"><a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">Read More</a></p>';
                    }
                    wp_reset_postdata();
                    if ( get_field( 'news_background' ) ) {
                        $home_features_styles .= '.home-features .news { background-image: url(\'' . get_field( 'news_background' ) . '\'); }' . "\n";
                    }
                ?>
            </section>
            <section class="info">
                <header>
                    <h2 class="entry-title">Info</h2>
                </header>
                <?php
                    if ( get_field( 'contact_info_content' ) ) {
                        the_field( 'contact_info_content' );
                    }
                    if ( get_field( 'contact_info_link' ) ) {
                        echo '<p class="read-more"><a href="' . get_field( 'contact_info_link' ) . '">Read more</a></p>';
                    }
                    if ( get_field( 'contact_info_background' ) ) {
                        $home_features_styles .= '.home-features .info { background-image: url(\'' . get_field( 'contact_info_background' ) . '\'); }' . "\n";
                    }

                    if ( $home_features_styles ) { ?>
                    <style type="text/css">
                        <?php echo $home_features_styles; ?>
                    </style>
                    <?php }
                ?>
            </section>
        </section><!-- .home-features -->

        <section class="apply home-stripe">
        <?php
            if ( get_field( 'cta_headline' ) ) {
                the_field( 'cta_headline' );
            }
            if ( get_field( 'cta_target' ) && get_field( 'cta_button_text' ) ) {
                echo '<a class="button green" href="' . get_field( 'cta_target' ) . '">' . get_field( 'cta_button_text' ) . '</a>';
            }
        ?>
        </section><!-- .apply -->

        <section class="video home-stripe">
            <div class="container">
            <?php
                if ( get_field( 'video_content' ) ) {
                    echo '<div class="video-content">' . get_field( 'video_content' ) . '</div>';
                }
                if ( get_field( 'video_link' ) ) {
                    echo '<div class="video-link">' . get_field( 'video_link' ) . '</div>';
                }
            ?>
            </div>
        </section><!-- .video -->

        <section class="latest-sermon home-stripe">
        <?php
            if ( get_field( 'latest_sermon' ) ) {
                the_field( 'latest_sermon' );

                // WP_Query arguments
                $args = array (
                    'post_type'              => array( 'wpfc_sermon' ),
                    'posts_per_page'         => '1',
                    'post_status'            => 'publish',
                    'order'                  => 'DESC',
                    'orderby'                => 'meta_value',
                    'meta_query'             => array(
                        array(
                            'key'       => 'sermon_date',
                            'type'      => 'NUMERIC',
                        ),
                    ),
                    'no_found_rows'          => true,
                );

                // The Query
                $sermons_query = new WP_Query( $args );
                // The Loop
                if ( $sermons_query->have_posts() ) {
                    // remove some mediaJS features
                    wp_localize_script( 'wp-mediaelement', '_wpmejsSettings', array(
                        'features'  => array( 'playpause', 'progress' )
                    ));

                    while ( $sermons_query->have_posts() && $counter == 0) {
                        $sermons_query->the_post();

                        echo '<p class="latest-sermon"><a id="latest_sermon_title" title="' . esc_attr( get_the_title() ) . '" href="' . get_permalink() . '">' . get_the_title() . '</a>, preached by ';
                            // preacher
                            the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher">', ', ', '</span>' );
                        echo '</p>';
                        wpfc_sermon_files();
                    }
                }
                // reset the query
                wp_reset_postdata();
            }
        ?>
        </section>
    </main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>
