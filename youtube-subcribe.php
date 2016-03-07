<?php
/**
 * Plugin Name: TM Youtube Subscribe Widget
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Templatemonster
 * Author URI: http://www.templatemonster.com/
 * Text Domain: youtube-subscribe
 *
 * @package Monster_Youtube_Subscribe_Widget
 *
 * @since 1.1
 */
function ys_subscribe_widget() {
	echo TEMPLATEPATH;
	load_plugin_textdomain('youtube-subscribe', false, basename( dirname( FILE ) ) . '/languages' );
	require_once 'ui/ui-input-fox.php';
	require_once 'class-youtube-subscribe-helper.php';
	require_once 'class-youtube-subscribe-widget.php';
	register_widget( 'Youtube_Subscribe_Widget' );

	if ( apply_filters( 'youtube_subscribe_styles', true ) ) {
		wp_enqueue_style( 'youtube-widget-style',  plugin_dir_url( __FILE__ ) . 'assets/youtube-style.css' );
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/font-awesome/css/font-awesome.min.css' );
	}
}
function ys_subscribe_init() {
	 load_plugin_textdomain( 'youtube-subscribe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'widgets_init', 'ys_subscribe_widget' );
add_action('plugins_loaded', 'ys_subscribe_init');
