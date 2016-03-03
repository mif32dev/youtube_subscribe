<?php
/**
 * Description: Fox ui-elements
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 *
 * @package ui_input_fox
 *
 * @since 0.3
 */
if ( ! class_exists( 'UI_Input_Fox' ) ) {

	/**
	 * UI-input.
	 */
	class UI_Input_Fox {

		/**
		 * Default settings
		 *
		 * @var type array
		 */
		private $default_settings = array(
			'id' => 'input-fox',
			'class' => '',
			'type' => 'text',
			'name' => 'input-fox',
			'value' => '',
			'placeholder' => 'enter string',
			'datalist' => null,
		);

		/**
		 * Required settings
		 *
		 * @var type array
		 */
		private $required_settings = array(
			'class' => 'input-fox',
		);

		/**
		 * Settings
		 *
		 * @var type array
		 */
		public $settings;

		/**
		 * Init base settings
		 */
		public function __construct( $attr = null ) {
			if ( empty( $attr ) || ! is_array( $attr ) ) {
				$attr = $this->default_settings;
			} else {
				foreach ( $this->default_settings as $key => $value ) {
					if ( empty( $attr[ $key ] ) ) {
						$attr[ $key ] = $this->default_settings[ $key ];
					}
				}
			}

			$this->settings = $attr;
		}

		/**
		 * Add styles
		 */
		private function assets() {
			$url =  WP_PLUGIN_URL . '/youtube_subscribe/ui/ui-input/assets/css/input.min.css';
			wp_enqueue_style( 'input-fox', $url, array(), '0.2.0', 'all' );
		}

		/**
		 * Render html
		 *
		 * @return string
		 */
		public function output() {
			$this->assets();
			foreach ( $this->required_settings as $key => $value ) {
				$this->settings[ $key ] = empty( $this->settings[ $key ] ) ? $value : $this->settings[ $key ] . ' ' . $value;
			}

			$label = null;
			if ( ! empty( $this->settings['label'] ) ) {
				$label = $this->settings['label'];
				unset( $this->settings['label'] );
			}

			$datalist = null;
			if ( ! empty( $this->settings['datalist'] ) && is_array( $this->settings['datalist'] ) ) {
				$datalist = $this->settings['datalist'];
			}
			unset( $this->settings['datalist'] );

			$datalist_id = $this->settings['id'] . '-datalist';

			$attributes = '';
			foreach ( $this->settings as $key => $value ) {
				$attributes .= ' ' . $key . '="' . $value . '"';
			}
			ob_start();

			require 'ui-input/views/input.php';

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		}
	}
}
