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
            <?php if ( get_field( 'button_1_text' ) && get_field( 'button_1_link' ) && get_field( 'button_1_image' ) ) { ?>
            <section class="button-1">
                <a href="<?php the_field( 'button_1_link' ) ?>">
                    <?php echo wp_get_attachment_image( get_field( 'button_1_image' ), 'full' ); ?>
                    <h2 class="entry-title"><?php the_field( 'button_1_text' ) ?></h2>
                </a>
            </section>
            <?php } ?>

            <?php if ( get_field( 'button_2_text' ) && get_field( 'button_2_link' ) && get_field( 'button_2_image' ) ) { ?>
            <section class="button-2">
                <a href="<?php the_field( 'button_2_link' ) ?>">
                    <?php echo wp_get_attachment_image( get_field( 'button_2_image' ), 'full' ); ?>
                    <h2 class="entry-title"><?php the_field( 'button_2_text' ) ?></h2>
                </a>
            </section>
            <?php } ?>

            <?php if ( get_field( 'button_3_text' ) && get_field( 'button_3_link' ) && get_field( 'button_3_image' ) ) { ?>
            <section class="button-3">
                <a href="<?php the_field( 'button_3_link' ) ?>">
                    <?php echo wp_get_attachment_image( get_field( 'button_3_image' ), 'full' ); ?>
                    <h2 class="entry-title"><?php the_field( 'button_3_text' ) ?></h2>
                </a>
            </section>
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
                    while ( $sermons_query->have_posts() && $counter == 0) {
                        $sermons_query->the_post();

                        echo '<p class="latest-sermon"><a id="latest_sermon_title" title="' . esc_attr( get_the_title() ) . '" href="' . get_permalink() . '">' . get_the_title() . '</a>, preached by ';
                            // preacher
                            the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher">', ', ', '</span>' );
                        echo '</p>' . wpfc_sermon_media();
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
