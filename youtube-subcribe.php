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
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If class 'Cherry_Custom_Sidebars' not exists.
if ( ! class_exists( 'Cherry_Youtube_Subscribe' ) ) {

	/**
	 * Class add all hooks.
	 */
	class Cherry_Youtube_Subscribe {
		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;
		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since 1.1.0
		 * @var   object
		 */
		private $core = null;
		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'get_core' ), 3 );
			// Internationalize the text strings used.
			add_action( 'plugins_loaded', array( $this, 'lang' ), 5 );
			// Load the functions files.
			add_action( 'widgets_init', array( $this, 'subscribe_widget' ), 4 );
		}

		/**
		 * Add text domain to WP.
		 *
		 * @since 1.0.0
		 */
		function lang() {
			load_plugin_textdomain( 'youtube-subscribe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * theme because they have required functions for use.
		 *
		 * @since  1.1.0
		 */
		public function get_core() {
			/**
			 * Fires before loads the core theme functions.
			 *
			 * @since  1.1.0
			 */
			do_action( 'cherry_core_before' );
			if ( null !== $this->core ) {
				return $this->core;
			}
			if ( ! class_exists( 'Cherry_Core' ) ) {
				require_once( plugin_dir_path( __FILE__ ) . 'cherry-framework/cherry-core.php' );
			}
			$this->core = new Cherry_Core( array(
				'base_dir'	=> plugin_dir_path( __FILE__ ) . 'cherry-framework',
				'base_url'	=> plugin_dir_url( __FILE__ ) . 'cherry-framework',
				'modules'	=> array(
					'cherry-js-core'	=> array(
						'priority'	=> 999,
						'autoload'	=> true,
					),
					'cherry-ui-elements' => array(
						'priority'	=> 999,
						'autoload'	=> true,
						'args'		=> array(
							'ui_elements' => array(
								'text',
							),
						),
					),
					'cherry-widget-factory' => array(
						'priority'	=> 999,
						'autoload'	=> true,
					),
				),
			));
		}
		/**
		 * Include and add all foles.
		 *
		 * @since  1.0.0
		 *
		 */
		function subscribe_widget() {

			require_once 'class-youtube-subscribe-helper.php';
			require_once 'class-youtube-subscribe-widget.php';
			register_widget( 'Youtube_Subscribe_Widget' );

			if ( apply_filters( 'youtube_subscribe_styles', true ) ) {
				wp_enqueue_style( 'youtube-widget-style',  plugin_dir_url( __FILE__ ) . 'assets/youtube-style.css' );
				wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/font-awesome/css/font-awesome.min.css' );
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

	Cherry_Youtube_Subscribe::get_instance();
}
