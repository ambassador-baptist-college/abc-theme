<?php
/**
 * Homepage event stripe content
 *
 * @package WordPress
 * @subpackage ABC_theme
 */

?>
<section class="highlighted-event home-stripe full-width">
	<div class="container">
		<div class="image">
		<?php
			echo '<a href="' . esc_url( $event_link ) . '">';
			if ( isset( $event['event']['event_image'] ) ) {
			echo wp_get_attachment_image( $event['event']['event_image'], 'highlighted-event-small', false, array( 'class' => 'highlighted-event' ) ); // WPCS: XSS ok.
			} else {
			echo get_the_post_thumbnail( $event_id, 'highlighted-event-small', array( 'class' => 'highlighted-event' ) ); // WPCS: XSS ok.
			}
			echo '</a>';
		?>
		</div>
		<div class="content">
			<h2 class="entry-title"><a href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_attr( get_the_title( $event_id ) ); ?></a></h2>
			<p class="excerpt"><?php echo wp_kses_post( $excerpt ); ?></p>
			<p><a class="button" href="<?php echo esc_url( $event_link ); ?>">More Information</a></p>
		</div>
	</div>
</section>
