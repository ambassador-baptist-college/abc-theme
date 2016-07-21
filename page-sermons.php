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
        while ( have_posts() ) : the_post();

            // Include the page content template. ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php
                    // page content
                    the_content();

                    // get sermon archive slug
                    $sermons_options = get_option( 'wpfc_options' );
                    $sermons_slug = $sermons_options['archive_slug'];

                    // WP_Query arguments
                    $args = array (
                        'post_type'              => array( 'wpfc_sermon' ),
                        'posts_per_page'         => '14',
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

                    $counter = 0;
                    ?>

                    <section class="sermon-block latest">
                        <header>
                            <h1 class="entry-title">Listen to our Latest Sermon!</h1>
                        </header>
                        <?php
                        // The Loop
                        if ( $sermons_query->have_posts() ) {
                            while ( $sermons_query->have_posts() && $counter == 0) {
                                $sermons_query->the_post(); ?>

                                <h2><a id="latest_sermon_title" title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                                <p class="meta">
                                    <?php
                                    // preacher
                                    the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher">', ', ', '</span> | ' );

                                    // sermon date
                                    wpfc_sermon_date( get_option( 'date_format' ) );

                                    // Bible passage
                                    wpfc_sermon_meta( 'bible_passage', ' | <span class="bible_passage">', '</span>' ); ?>
                                </p>
                                <?php wpfc_sermon_files();

                                // increment counter
                                $counter++;
                            }
                        } ?>
                    </section>

                    <section class="sermon-block date">
                        <header>
                            <h2 class="entry-title">By Date</h2>
                        </header>
                        <?php
                        if ( $sermons_query->have_posts() ) {
                            echo '<ul class="columns three">';
                            while ( $sermons_query->have_posts() && $counter >= 1) {
                                $sermons_query->the_post();

                                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';

                                $counter++;
                            }
                            echo '</ul>';
                        }
                        ?>
                    </section>

                    <section class="sermon-block event">
                        <header>
                            <h2 class="entry-title">By Event</h2>
                        </header>
                        <?php
                        $events = get_terms( array(
                            'taxonomy'      => 'wpfc_sermon_series',
                            'orderby'       => 'term_order',
                            'order'         => 'DESC',
                            'number'        => 12,
                        ));

                        if ( $events ) {
                            echo '<ul class="columns three">';
                            foreach ( $events as $event ) {
                                echo '<li><a href="' . home_url( '/' . $sermons_slug . '/series/' . $event->slug ) . '">' . $event->name. '</a></li>';
                            }
                            echo '</ul>';
                        } ?>
                    </section>

                    <section class="sermon-block book">
                        <header>
                            <h2 class="entry-title">By Book</h2>
                        </header>
                        <?php
                        $books = get_terms( array(
                            'taxonomy'      => 'wpfc_bible_book',
                            'orderby'       => 'term_order',
                            'order'         => 'ASC',
                        ));

                        if ( $books ) {
                            echo '<ul class="columns three">';
                            foreach ( $books as $book ) {
                                echo '<li><a href="' . home_url( '/' . $sermons_slug . '/book/' . $book->slug ) . '">' . $book->name. '</a></li>';
                            }
                            echo '</ul>';
                        } ?>
                    </section>

                    <?php
                    // Restore original Post Data
                    wp_reset_postdata();

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

    </main><!-- .site-main -->

    <?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
