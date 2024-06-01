<?php
/**
 * Plugin Name: Guest Posts Plugin
 * Description: A plugin to allow users to submit and manage guest posts.
 * Version: 1.0
 * Author: Nikita Borkar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue styles.
 */
function enqueue_guest_post_styles() {
	wp_enqueue_style( 'guest-post-form-style', plugin_dir_url( __FILE__ ) . 'css/guest-post-form.css', array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_guest_post_styles' );

// Include necessary files.
require_once plugin_dir_path( __FILE__ ) . 'includes/guest-posts-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/guest-posts-form.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/guest-posts-admin.php';
