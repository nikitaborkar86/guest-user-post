<?php
/**
 * Guest Post Form
 */
function gp_guest_post_form() {
	$message = '';

	if ( isset( $_POST['gp_submit_guest_post'] ) && wp_verify_nonce( $_POST['gp_submit_guest_post_nonce'], 'gp_submit_guest_post_action' ) ) {
		$post_title   = sanitize_text_field( $_POST['gp_post_title'] );
		$post_content = sanitize_textarea_field( $_POST['gp_post_content'] );
		$author_name  = sanitize_text_field( $_POST['gp_author_name'] );
		$author_email = sanitize_email( $_POST['gp_author_email'] );

		if ( ! empty( $post_title ) && ! empty( $post_content ) && ! empty( $author_name ) && is_email( $author_email ) ) {
			$post_id = wp_insert_post(
				array(
					'post_title'   => $post_title,
					'post_content' => $post_content,
					'post_status'  => 'pending',
					'post_type'    => 'guest_post',
				)
			);

			if ( $post_id ) {
				update_post_meta( $post_id, 'gp_author_name', $author_name );
				update_post_meta( $post_id, 'gp_author_email', $author_email );

				$message = '<p style="color: green; text-align: center;">Thank you for your submission!</p>';
			} else {
				$message = '<p style="color: red; text-align: center;">Submission failed. Please try again.</p>';
			}
		} else {
			$message = '<p style="color: red; text-align: center;">Please fill in all fields with valid information.</p>';
		}
	}

	ob_start(); ?>
	<?php echo $message; ?>

	<form method="post">
		<?php wp_nonce_field( 'gp_submit_guest_post_action', 'gp_submit_guest_post_nonce' ); ?>
		<p>
			<label for="gp_post_title">Title</label>
			<input type="text" name="gp_post_title" id="gp_post_title" required>
		</p>
		<p>
			<label for="gp_post_content">Content</label>
			<textarea name="gp_post_content" id="gp_post_content" required></textarea>
		</p>
		<p>
			<label for="gp_author_name">Author Name</label>
			<input type="text" name="gp_author_name" id="gp_author_name" required>
		</p>
		<p>
			<label for="gp_author_email">Author Email</label>
			<input type="email" name="gp_author_email" id="gp_author_email" required>
		</p>
		<p>
			<input type="submit" name="gp_submit_guest_post" value="Submit">
		</p>
	</form>
	<?php
	return ob_get_clean();
}

add_shortcode( 'guest_post_form', 'gp_guest_post_form' );
