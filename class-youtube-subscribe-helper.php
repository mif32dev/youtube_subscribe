<?php
/**
 * Description: Class Helper
 * Author: Bazil Anton
 * Author URI: https://github.com/mif32dev
 *
 * @package Monster_Youtube_Subscribe_Widget
 *
 * @since 0.1
 */
class Youtube_Subscribe_Helper {
	/**
	 * Set Cache
	 *
	 * @param string  $key cache name.
	 * @param string  $val data.
	 * @param integer $time cache time.
	 */
	public static function set_cache( $key, $val, $time = 3600 ) {
		set_transient( $key, $val, $time );
	}

	/**
	 * Get Cache
	 *
	 * @param string $key cache file.
	 * @return mixed
	 */
	public static function get_cache( $key ) {
		$cached = get_transient( $key );
		if ( false !== $cached ) {
			return $cached;
		}
		return false;
	}

	/**
	 * Instead $GLOBALS['wp_filesystem']->get_contents( $file )
	 *
	 * @param type $url host url.
	 * @return string requres data
	 */
	public static function get_contents( $url ) {
		$cache_key = md5( $url );
		$result = self::get_cache( $cache_key );
		if ( ! $result ) {
			$response = wp_remote_get( $url );
			if ( is_array( $response ) ) {
				self::set_cache( $cache_key, $response['body'] );
				return $response['body'];
			}
		}

		return $result;
	}

	/**
	 * Making and print view
	 *
	 * @param string $route route to the view file.
	 * @param array  $args data value for view.
	 */
	public static function render( $route, $args ) {

		if ( $route ) {
			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			include $route;

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );

		} else {
			echo 'Template not found';
		}
	}

	/**
	 * Searching at array
	 *
	 * @param array  $array array for search.
	 * @param string $key searching key.
	 * @param string $default retutn value if search fail.
	 */
	public static function array_get( $array, $key, $default = '' ) {
		$array = (array) $array;
		if ( is_null( $key ) ) {
			return $array;
		}
		if ( array_key_exists( $key, $array ) ) {
			return $array[ $key ];
		}
		return $default;
	}
	/**
	 * Overloading of view file
	 */
	public static function get_view_file() {
		
		//locate_template('templates/youtube-subscribe.php',false );
		if ( file_exists( get_template_directory() . '/templates/youtube-subscribe.php' ) ) {
			return get_template_directory() . '/templates/youtube-subscribe.php';
		}
		if ( file_exists( plugin_dir_path( __FILE__ ) . '/templates/youtube-subscribe-view.php' ) ) {
			return plugin_dir_path( __FILE__ ) . '/templates/youtube-subscribe-view.php';
		}

		return false;
	}
}
