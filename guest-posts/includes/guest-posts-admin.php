<?php
/**
 * Function to add menu item for managing guest posts
 */
function gp_add_menu() {
	add_menu_page(
		'Manage Guest Posts',
		'Guest Posts',
		'manage_options',
		'guest_posts_page',
		'gp_guest_posts_page',
		'dashicons-admin-users',
		20
	);
}
add_action( 'admin_menu', 'gp_add_menu' );

/**
 * Function to display the admin dashboard page for managing guest posts
 */
function gp_guest_posts_page() {

	// Handle post approval and rejection.
	gp_handle_post_actions();

	// Query guest posts.
	$args        = array(
		'post_type'      => 'guest_post',
		'posts_per_page' => 10,
		'paged'          => isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1,
	);
	$guest_posts = new WP_Query( $args );

	// Display guest posts.
	echo '<div class="wrap">';
	echo '<h1>Guest Posts</h1>';
	echo '<table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th class="manage-column">Title</th>
                    <th class="manage-column">Author</th>
                    <th class="manage-column">Submission Date</th>
                    <th class="manage-column">Status</th>
                    <th class="manage-column">Actions</th>
                </tr>
            </thead>
            <tbody>';

	if ( $guest_posts->have_posts() ) {
		while ( $guest_posts->have_posts() ) {
			$guest_posts->the_post();
			$post_id         = get_the_ID();
			$author_name     = get_post_meta( $post_id, 'gp_author_name', true );
			$submission_date = get_the_date();
			$post_status     = get_post_status();

			echo '<tr>
                    <td>' . esc_html( get_the_title() ) . '</td>
                    <td>' . esc_html( $author_name ) . '</td>
                    <td>' . esc_html( $submission_date ) . '</td>
                    <td>' . esc_html( $post_status ) . '</td>
                    <td>';
			if ( $post_status === 'pending' ) {
				echo '<form method="post" style="display:inline-block;">
                        <input type="hidden" name="gp_post_id" value="' . esc_attr( $post_id ) . '">
                        <input type="submit" name="gp_approve_post" value="Approve" class="button button-primary">
                    </form>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="gp_post_id" value="' . esc_attr( $post_id ) . '">
                        <input type="submit" name="gp_reject_post" value="Reject" class="button button-secondary">
                    </form>';
			} else {
				echo '<a href="' . get_edit_post_link( $post_id ) . '">Edit</a>';
			}
			echo '</td>
                </tr>';
		}
	} else {
		echo '<tr><td colspan="5">No guest posts found.</td></tr>';
	}

	echo '</tbody></table>';

	// Pagination.
	$total_pages = $guest_posts->max_num_pages;
	if ( $total_pages > 1 ) {
		echo '<div class="tablenav"><div class="tablenav-pages">';
		$current_page = max( 1, get_query_var( 'paged' ) );
		echo paginate_links(
			array(
				'base'    => add_query_arg( 'paged', '%#%' ),
				'format'  => '',
				'current' => $current_page,
				'total'   => $total_pages,
			)
		);
		echo '</div></div>';
	}

	echo '</div>';

	wp_reset_postdata();
}

/**
 * Function to handle post approval and rejection.
 */
function gp_handle_post_actions() {
	if ( isset( $_POST['gp_post_id'] ) ) {
		$post_id = intval( $_POST['gp_post_id'] );
		if ( isset( $_POST['gp_approve_post'] ) ) {
			wp_update_post(
				array(
					'ID'          => $post_id,
					'post_status' => 'publish',
				)
			);
		} elseif ( isset( $_POST['gp_reject_post'] ) ) {
			wp_delete_post( $post_id, true );
		}
	}
}

/**
 * Add Meta Boxes for Author & Email
 */
function gp_add_guest_post_meta_boxes() {
	add_meta_box(
		'gp_author_meta_box',
		'Author Details',
		'gp_render_author_meta_box',
		'guest_post',
		'normal',
		'default'
	);
}

add_action( 'add_meta_boxes', 'gp_add_guest_post_meta_boxes' );

/**
 * Render Callback for Meta Boxes
 */
function gp_render_author_meta_box( $post ) {
	$author_name  = get_post_meta( $post->ID, 'gp_author_name', true );
	$author_email = get_post_meta( $post->ID, 'gp_author_email', true );
	?>
	<label for="gp_author_name">Author Name:</label>
	<input type="text" id="gp_author_name" name="gp_author_name" value="<?php echo esc_attr( $author_name ); ?>"><br><br>
	<label for="gp_author_email">Author Email:</label>
	<input type="email" id="gp_author_email" name="gp_author_email" value="<?php echo esc_attr( $author_email ); ?>">
	<?php
}

/**
 * Save the meta data
 */
function gp_save_guest_post_meta( $post_id ) {
	if ( array_key_exists( 'gp_author_name', $_POST ) ) {
		update_post_meta(
			$post_id,
			'gp_author_name',
			sanitize_text_field( $_POST['gp_author_name'] )
		);
	}

	if ( array_key_exists( 'gp_author_email', $_POST ) ) {
		update_post_meta(
			$post_id,
			'gp_author_email',
			sanitize_email( $_POST['gp_author_email'] )
		);
	}
}

add_action( 'save_post', 'gp_save_guest_post_meta' );

