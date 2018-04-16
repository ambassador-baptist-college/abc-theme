<section class="highlighted-event home-stripe full-width">
    <div class="container">
        <div class="image">
        <?php
            echo '<a href="' . $event_link . '">';
            if ( isset( $event['event']['event_image'] ) ) {
                echo wp_get_attachment_image( $event['event']['event_image'], 'highlighted-event-small', false, array( 'class' => 'highlighted-event' ) );
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
</section>
