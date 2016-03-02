<?php
/**
 * Description: Class Helper
 * Author: Bazil Anton
 * Author URI: https://github.com/mif32dev
 *
 * @package  
 *
 * @since 0.1
 */
class Helper {

	/**
	 * Set Cache
	 * @param string  $key
	 * @param string  $val    
	 * @param integer $time   
	 * @param string  $prefix 
	 */
	public static function set_cache( $key, $val, $time = 3600 ) {
		set_transient( $key, $val, $time );
	}

	/**
	 * Get Cache
	 * @param  string $key    
	 * @param  string $prefix 
	 * @return mixed
	 */
	public static function get_cache( $key ) {
		$cached = get_transient( $key );
		if ( false !== $cached )
			return $cached;
		return false;
	}

	/**
	 * Instead $GLOBALS['wp_filesystem']->get_contents( $file )
	 *
	 * @param type $url host url.
	 * @return string requres data
	 */
	public static function get_contents($url) {
		$cache_key = md5($url);
		$result = self::get_cache($cache_key);
		if (!$result) {
			$response = wp_remote_get($url);
			if (is_array($response)) {
				self::set_cache($cache_key, $response['body']);
				return $response['body'];
			}
		}

		return $result;
	}

	/**
	 *  
	 *
	 * @param $route route to the view file.
	 * @param $args data value for view.
	 */
	public static function render($route, $args) {

		extract($args);

		ob_start();

		require $route;

		$view = ob_get_contents();

		ob_end_clean();
		echo $view;
	}

	public static function array_get($array, $key, $default = '') {
		$array = (array) $array;
		if (is_null($key)) {
			return $array;
		}
		if (array_key_exists($key, $array)) {
			return $array[$key];
		}
		return $default;
	}

}
