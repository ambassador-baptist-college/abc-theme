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

get_header();

wp_enqueue_script( 'video-res' );
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post(); ?>

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

        <?php if ( get_field( 'show_cancellation_notice' ) ) { ?>
            <section class="cancellation home-stripe">
            <?php
                if ( get_field( 'live_streaming' ) ) {
                    echo '<div class="teaser">' . get_field( 'cancellation_notice' ) . '</div>';
                }
            ?>
            </section><!-- .cancellation -->
        <?php } ?>

        <section class="streaming home-stripe off-air">
        <?php
            if ( get_field( 'live_streaming' ) ) {
                echo '<div class="teaser">' . get_field( 'live_streaming' ) . '</div>
                <div class="streaming-frame-shade"></div>
                <div class="streaming-frame"><span class="close">&times;</span>' . do_shortcode( '[youtube_live autoplay="false" js_only="true"]' ) . '</div>';
            }
        ?>
        </section><!-- .video.streaming -->

        <section class="home-features home-stripe">
            <?php $home_feature_styles = ''; ?>

            <?php if ( get_field( 'button_2_text' ) && get_field( 'button_2_link' ) && get_field( 'button_2_image' ) ) {
                $home_feature_styles .= '.button-2 {background-image: url("' .  get_field( 'button_2_image' ) . '");}';
                ?>
            <section class="button-2 events">
                <?php echo do_shortcode( '[calendar id="' . get_field( 'button_2_calendar' ) . '"]' ); ?>
                <h2 class="read-more"><a href="<?php the_field( 'button_2_link' ) ?>"><?php the_field( 'button_2_text' ) ?></a></h2>
            </section>
            <?php } ?>

            <?php
            if ( get_field( 'button_1_text' ) && get_field( 'button_1_link' ) && get_field( 'button_1_image' ) ) {
                $home_feature_styles .= '.button-1 {background-image: url("' .  get_field( 'button_1_image' ) . '");}';
                ?>
            <section class="button-1 students">
                <h2 class="read-more"><a href="<?php the_field( 'button_1_link' ) ?>"><?php the_field( 'button_1_text' ) ?></a></h2>
            </section>
            <?php } ?>

            <?php if ( get_field( 'button_3_text' ) && get_field( 'button_3_link' ) && get_field( 'button_3_image' ) ) {
                $home_feature_styles .= '.button-3 {background-image: url("' .  get_field( 'button_3_image' ) . '");}';
                ?>
            <section class="button-3 news">
                <?php
                // WP_Query arguments
                $args = array (
                    'post_type'              => array( 'post' ),
                    'posts_per_page'         => '3',
                    'post_status'            => 'publish',
                );

                // The Query
                $news_query = new WP_Query( $args );
                // The Loop
                if ( $news_query->have_posts() ) {
                    while ( $news_query->have_posts() ) {
                        $news_query->the_post();

                        add_filter( 'excerpt_length', function() { return 13; } );

                        echo '<article class="home-news-excerpt">';
                            echo '<h3 class="entry-title"><a href="' . get_permalink() . '">' . wp_trim_words( get_the_title(), 4, '&hellip;') . '</a></h3>';
                            the_excerpt();
                        echo '</article>' . "\n";
                    }
                }
                wp_reset_postdata();
                ?>
                <h2 class="read-more"><a href="<?php the_field( 'button_3_link' ) ?>"><?php the_field( 'button_3_text' ) ?></a></h2>
            </section>
            <?php
            }
            if ( $home_feature_styles ) { ?>
                <style type="text/css">
                    <?php echo $home_feature_styles; ?>
                </style>
            <?php } ?>
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

        <?php
        if ( get_field( 'highlighted_event' ) ) {
            $event_id = get_field( 'highlighted_event' );
            $event_link = get_permalink( $event_id );
            $event_excerpt = get_post_field( 'post_excerpt', $event_id );
            if ( $event_excerpt ) {
                $excerpt = apply_filters( 'the_excerpt', $event_excerpt );
            } else {
                $excerpt = strip_shortcodes( get_post_field( 'post_content', $event_id ) );
                $excerpt = wp_trim_words( $excerpt, 25, '&hellip;' );
            }

        ?>
        <section class="highlighted-event home-stripe full-width">
            <div class="container">
                <div class="image">
                <?php
                    echo '<a href="' . $event_link . '">';
                    if ( get_field( 'highlighted_event_image' ) ) {
                        echo wp_get_attachment_image( get_field( 'highlighted_event_image' ), 'highlighted-event-small', false, array( 'class' => 'highlighted-event' ) );
                    } else {
                        echo get_the_post_thumbnail( $event_id, 'highlighted-event-small', array( 'class' => 'highlighted-event' ) );
                    }
                    echo '</a>';
                ?>
                </div>
                <div class="content">
                    <h2 class="entry-title"><a href="<?php echo $event_link; ?>"><?php echo get_the_title( $event_id ); ?></a></h2>
                    <p class="excerpt"><?php echo $excerpt; ?></p>
                    <p><a class="button" href="<?php echo $event_link; ?>">More Information</a></p>
                </div>
            </div>
        </section><!-- .highlighted-event -->
        <?php } ?>

        <section class="video home-stripe full-width">
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
                    while ( $sermons_query->have_posts() ) {
                        $sermons_query->the_post();

                        echo '<p class="latest-sermon"><a id="latest_sermon_title" title="' . esc_attr( get_the_title() ) . '" href="' . get_permalink() . '">' . get_the_title() . '</a>, preached by ';
                            // preacher
                            the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher">', ', ', '</span>' );
                        echo '</p>' . ( function_exists( 'wpfc_sermon_media' ) ? wpfc_sermon_media() : '<a class="button" href="' . get_the_permalink() . '">Listen here</a>' );
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
