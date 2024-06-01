<?php
/**
 * Register Custom Post type i.e. Guest Posts
 */
function gp_register_guest_post_type() {
	$labels = array(
		'name'               => _x( 'Guest Posts', 'post type general name', 'gp' ),
		'singular_name'      => _x( 'Guest Post', 'post type singular name', 'gp' ),
		'menu_name'          => _x( 'Guest Posts', 'admin menu', 'gp' ),
		'name_admin_bar'     => _x( 'Guest Post', 'add new on admin bar', 'gp' ),
		'add_new'            => _x( 'Add New', 'guest post', 'gp' ),
		'add_new_item'       => __( 'Add New Guest Post', 'gp' ),
		'new_item'           => __( 'New Guest Post', 'gp' ),
		'edit_item'          => __( 'Edit Guest Post', 'gp' ),
		'view_item'          => __( 'View Guest Post', 'gp' ),
		'all_items'          => __( 'All Guest Posts', 'gp' ),
		'search_items'       => __( 'Search Guest Posts', 'gp' ),
		'parent_item_colon'  => __( 'Parent Guest Posts:', 'gp' ),
		'not_found'          => __( 'No guest posts found.', 'gp' ),
		'not_found_in_trash' => __( 'No guest posts found in Trash.', 'gp' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'guest_post' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor' ),
	);

	register_post_type( 'guest_post', $args );
}

add_action( 'init', 'gp_register_guest_post_type' );
