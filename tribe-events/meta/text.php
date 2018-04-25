<?php
/**
 * Renders text field
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/meta/text.php
 *
 * @version 4.3.5
 *
 * @package TribeEventTickets
 */

$multiline = isset( $field['extra'] ) && isset( $field['extra']['multiline'] ) ? $field['extra']['multiline'] : '';
$option_id = "tribe-tickets-meta_{$this->slug}" . ( $attendee_id ? '_' . $attendee_id : '' );

switch ( $field['label'] ) {
	// Camp Barnabas.
	case 'Name':
		$placeholder = 'John Doe';
		break;
	case 'Age':
		$placeholder = '14';
		break;
	case 'Camper’s Physician':
		$placeholder = 'John Doe';
		break;
	case 'Physician’s Phone':
		$placeholder = '234-567-8901';
		break;
	case 'Date of Birth':
		$placeholder = '1/1/' . ( (int) date( 'Y' ) - 14 );
		break;
	case 'Emergency Contact':
		$placeholder = "Jane Doe: 234-567-8901\nJim Doe: 234-567-8901";
		break;

	// Banquets.
	case 'First Name':
		$placeholder = 'John';
		break;
	case 'Last Name':
		$placeholder = 'Doe';
		break;
	case 'Box Number (students only)':
		$placeholder = '3000';
		break;
}

?>
<div class="tribe-tickets-meta tribe-tickets-meta-text <?php echo $required ? 'tribe-tickets-meta-required' : ''; ?>">
	<label for="<?php echo esc_attr( $option_id ); ?>"><?php echo wp_kses_post( $field['label'] ); ?></label>
	<?php
	if ( $multiline ) {
		?>
		<textarea
			id="<?php echo esc_attr( $option_id ); ?>"
			class="ticket-meta"
			name="tribe-tickets-meta[<?php echo esc_attr( $attendee_id ); ?>][<?php echo esc_attr( $this->slug ); ?>]"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			<?php echo $required ? 'required' : ''; ?>
			<?php disabled( $this->is_restricted( $attendee_id ) ); ?>
		><?php echo esc_textarea( $value ); ?></textarea>
		<?php
	} else {
		?>
		<input
			type="text"
			id="<?php echo esc_attr( $option_id ); ?>"
			class="ticket-meta"
			name="tribe-tickets-meta[<?php echo esc_attr( $attendee_id ); ?>][<?php echo esc_attr( $this->slug ); ?>]"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo $required ? 'required' : ''; ?>
			<?php disabled( $this->is_restricted( $attendee_id ) ); ?>
		>
		<?php
	}
	?>
</div>
