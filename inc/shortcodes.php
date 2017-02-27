<?php

add_shortcode( 'grad_offering_progress', 'abc_grad_offering_progress' );
function abc_grad_offering_progress( $attributes ) {
    $shortcode_attributes = shortcode_atts( array (
        'goal'          => 100000,
        'amount_raised' => 0,
    ), $attributes );

    wp_add_inline_script( 'grad-offering', 'var gradOfferingGoal = ' . $shortcode_attributes['goal'] . ', gradOfferingProgress = ' . $shortcode_attributes['amount_raised'] . ';', 'before' );
    wp_enqueue_script( 'grad-offering' );

    $shortcode_content = '<div class="thermometer-wrapper">
        <svg id="GO-progress" xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300"><g id="chart"><path d="M150,17C76.546,17,17,76.546,17,150s59.546,133,133,133s133-59.546,133-133S223.454,17,150,17z M150,261c-61.304,0-111-49.696-111-111S88.696,39,150,39s111,49.696,111,111S211.304,261,150,261z"/></g><g id="progress"><circle id="meter" fill="transparent" stroke-miterlimit="10" cx="150" cy="150" r="133" transform="rotate(-90 150 150)"/></g></svg>
        <p>Amount raised: $<span class="amount-raised">' . $shortcode_attributes['amount_raised'] . '</span></p>
    </div>';

    return $shortcode_content;
}
