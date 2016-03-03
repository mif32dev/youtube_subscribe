<?php
/**
 * Plugin Doc Comment
 * Plugin Name: TM Youtube Subscribe Widget
 * Plugin URI: https://github.com/RDSergij
 * Description: Show twitter timeline of user
 * Version: 1.0
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 * Text Domain: photolab
 *
 * @package Monster_Youtube_Subscribe_Widget
 *
 * @since 1.1
 */
function subscribe_widget() {
	require_once 'ui/ui-input-fox.php';
	require_once 'class-helper.php';
	require_once 'class-youtube-subscribe-widget.php';
	register_widget( 'Youtube_Subscribe_Widget' );
	wp_enqueue_style( 'youtube-widget-style', WP_PLUGIN_URL . '/youtube_subscribe/youtube-style.css' );
	wp_enqueue_style( 'font-awesome', WP_PLUGIN_URL . '/youtube_subscribe/assets/font-awesome/css/font-awesome.min.css' );
}

add_action( 'widgets_init', 'subscribe_widget' );
