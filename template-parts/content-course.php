<?php
/**
 * The template part for displaying single courses
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title">
            <?php
            if ( function_exists( 'get_field' ) ) :

            if ( get_field( 'course_code' ) ) {
                echo '<span class="course-code">' . get_field( 'course_code' ) . ':</span> ';
            }

            the_title();

            if ( get_field( 'credit_hours' ) ) {
                echo ' <span class="credit-hours">' . get_field( 'credit_hours' ) . ' credit hour';
                if ( '1' != get_field( 'credit_hours' ) ) {
                    echo 's';
                }
                echo '</span>';
            }

            endif;
            ?>
        </h1>
    </header><!-- .entry-header -->

    <?php twentysixteen_excerpt(); ?>

    <?php twentysixteen_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
            the_content();

            wp_link_pages( array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
                'separator'   => '<span class="screen-reader-text">, </span>',
            ) );

            if ( '' !== get_the_author_meta( 'description' ) ) {
                get_template_part( 'template-parts/biography' );
            }
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php twentysixteen_entry_meta(); ?>
        <?php
            edit_post_link(
                sprintf(
                    /* translators: %s: Name of current post */
                    __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
