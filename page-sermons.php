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
		while ( have_posts() ) :
			the_post();

			// Include the page content template.
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<div class="entry-content sermon-block">
						<?php the_content(); ?>
					</div>

					<?php
					// Get sermon archive slug.
					$sermons_options = get_option( 'wpfc_options' );
					$sermons_slug = $sermons_options['archive_slug'];

					$args = array(
						'post_type'              => array( 'wpfc_sermon' ),
						'posts_per_page'         => '51',
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

					$sermons_query = new WP_Query( $args );

					$counter = 0;
					?>

					<section class="sermon-block latest">
						<header>
							<h1 class="entry-title">Listen to our Latest Sermon!</h1>
						</header>
						<?php
						if ( $sermons_query->have_posts() ) {
							while ( $sermons_query->have_posts() && 0 === $counter ) {
								$sermons_query->the_post();
								?>

								<h2><a id="latest_sermon_title" title="<?php echo wp_kses_post( get_the_title() ); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="meta">
									<?php
									// Preacher.
									the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher">', ', ', '</span> | ' );

									// Sermon date.
									wpfc_sermon_date( get_option( 'date_format' ) );

									// Bible passage.
									wpfc_sermon_meta( 'bible_passage', ' | <span class="bible_passage">', '</span>' );
									?>
								</p>
								<?php
								echo wpfc_sermon_media(); // WPCS: XSS ok.

								// Increment counter.
								$counter++;
							}
						}
						?>
					</section>

					<section class="sermon-block event">
						<header>
							<h2 class="entry-title">Events</h2>
						</header>
						<?php
						$events = get_terms(
							array(
								'taxonomy'      => 'wpfc_sermon_series',
								'orderby'       => 'term_order',
								'order'         => 'DESC',
								'number'        => 12,
							)
						);

						if ( $events ) {
							echo '<ul class="columns three">';
							foreach ( $events as $event ) {
								echo '<li><a href="' . esc_url( home_url( '/' . $sermons_slug . '/series/' . $event->slug ) ) . '">' . esc_attr( $event->name ) . '</a></li>';
							}
							echo '</ul>';
						}
						?>
					</section>

					<section class="sermon-block search">
						<header>
							<h2 class="entry-title">Search</h2>
						</header>
						<?php
						$speakers = get_terms(
							array(
								'taxonomy'      => 'wpfc_preacher',
								'orderby'       => 'name',
								'order'         => 'ASC',
							)
						);

						if ( $speakers ) {
							echo '<form class="wpfc_speaker" action="' . esc_url( home_url() ) . '" data-path="' . esc_url( home_url( '/' . $sermons_slug . '/preacher/' ) ) . '">
							<h3>Speakers</h3>
							<select class="sermon-search" name="wpfc_preacher">';
							foreach ( $speakers as $speaker ) {
								echo '<option value="' . esc_attr( $speaker->slug ) . '">' . esc_attr( $speaker->name ) . '</option>';
							}
							echo '</select>
							<button type="submit">Go&rarr;</button>
							</form>';
						}

						$books = get_terms(
							array(
								'taxonomy'      => 'wpfc_bible_book',
								'orderby'       => 'term_order',
								'order'         => 'ASC',
							)
						);

						if ( $books ) {
							echo '<form class="wpfc_bible_book" action="' . esc_url( home_url() ) . '" data-path="' . esc_url( home_url( '/' . $sermons_slug . '/book/' ) ) . '">
							<h3>Books</h3>
							<select class="sermon-search" name="wpfc_bible_book">';
							foreach ( $books as $book ) {
								echo '<option value="' . esc_attr( $book->slug ) . '">' . esc_attr( $book->name ) . '</option>';
							}
							echo '</select>
							<button type="submit">Go&rarr;</button>
							</form>';
						}
						?>
					</section>

					<section class="sermon-block date">
						<header>
							<h2 class="entry-title">Recent Sermons</h2>
						</header>
						<?php
						if ( $sermons_query->have_posts() ) {
							while ( $sermons_query->have_posts() ) {
								$sermons_query->the_post();
								?>

								<h2><a title="<?php echo wp_kses_post( get_the_title() ); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="meta">
									<?php
									// Preacher.
									the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher">', ', ', '</span> | ' );

									// Sermon date.
									wpfc_sermon_date( get_option( 'date_format' ) );

									// Bible passage.
									wpfc_sermon_meta( 'bible_passage', ' | <span class="bible_passage">', '</span>' );
									?>
								</p>
								<?php
								echo wpfc_sermon_media(); // WPCS: XSS ok.
							}
						}
						?>
					</section>

					<?php
					wp_reset_postdata();

					wp_link_pages(
						 array(
							 'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
							 'after'       => '</div>',
							 'link_before' => '<span>',
							 'link_after'  => '</span>',
							 'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
							 'separator'   => '<span class="screen-reader-text">, </span>',
						 )
						);
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

			<?php
			// End of the loop.
		endwhile;

		wp_reset_postdata();
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
