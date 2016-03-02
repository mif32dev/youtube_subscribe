<?php
require_once 'ui/ui-input-fox.php';
if ( ! class_exists( 'Youtube_Subscribe_Widget' ) ) {

	/**
	 * Adds Youtube_Subscribe_Widget widget.
	 */
	class Youtube_Subscribe_Widget extends WP_Widget{

		// Default youtube api key
		const DEFAULT_YOUTUBE_KEY = 'AIzaSyC8ABgdjegQgcxF9zkhmV2gkXM5l0mgFB8';

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'youtube_subscribe_widget',
				__( 'Youtube subscribe widget', 'blogetti' ),
				array( 'description' => __( 'Youtube subscribe Widget', 'blogetti' ) )
			);
		}

		/**
		 * Get data about channel from YoutubeAPI
		 *
		 * @return array
		 */
		private function get_channel_data( $channel, $app_key = '' ) {
			if ( empty( $app_key ) ) {
				$app_key = self::DEFAULT_YOUTUBE_KEY;
			}

			$url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&forUsername=' . $channel . '&key=' . $app_key;

			$result = self::get_contents($url);
                                print_r($result);
			return $result ? json_decode( $result, true ) : false;
		}
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
                  * Set Cache
                  * @param string  $key
                  * @param string  $val    
                  * @param integer $time   
                  * @param string  $prefix 
                  */
                public static function set_cache($key, $val, $time = 3600)
                {
                     set_transient( $key, $val, $time );
                }
                /**
                 * Get Cache
                 * @param  string $key    
                 * @param  string $prefix 
                 * @return mixed
                 */
                public static function get_cache( $key ) { 
                        $cached   = get_transient( $key );
                        if ( false !== $cached ) return $cached;
                        return false;
                }
                /**
                 * Instead $GLOBALS['wp_filesystem']->get_contents( $file )
                 *
                 * @param type $url host url.
                 * @return string requres data
                */
                public static function get_contents( $url ) {
                   //  echo 222;
                    $cache_key = md5( $url );
                    $result = self::get_cache( $cache_key );
                    if ( ! $result ) {
                      //  echo $url."<br/>";
                        $response = wp_remote_get( $url );
                       // print_r( $response);
                       // echo 444;
                        if( is_array( $response ) ) {
                            self::set_cache( $cache_key, $response['body'] );
                            return $response['body'];
                        }
                    }
                        
                    return $result;
                }
		/**
		 * Frontend view
		 *
		 * @param type $args array.
		 * @param type $instance array.
		 */
		public function widget( $args, $instance ) {

			$channel_data = $this->get_channel_data( self::array_get( $instance, 'channel_name' ), self::array_get( $instance, 'app_key', self::DEFAULT_YOUTUBE_KEY ) );

			if ( empty( $channel_data['items'][0]['statistics']['subscriberCount'] ) ) {
				$subscriber_count = 0;
			} else {
				$subscriber_count = self::array_get( $channel_data['items'][0]['statistics'], 'subscriberCount', 0 );
			}

			if ( empty( $channel_data['items'][0]['statistics']['videoCount'] ) ) {
				$video_count = 0;
			} else {
				$video_count = self::array_get( $channel_data['items'][0]['statistics'], 'videoCount', 0 );
			}

			$this->render(
				'view/front-end.php',
				array(
					'before_widget'		=> $args['before_widget'],
					'before_title'		=> $args['before_title'],
					'after_title'		=> $args['after_title'],
					'after_widget'		=> $args['after_widget'],
					'title'			=> self::array_get( $instance, 'title' ),
					'channel_name'		=> self::array_get( $instance, 'channel_name' ),
					'channel_url'		=> self::array_get( $instance, 'channel_url' ),
					'subscriber_count'	=> $subscriber_count,
					'video_count'		=> $video_count,
				)
			);
		}

		/**
		 * Admin view
		 *
		 * @param type $instance array.
		 */
		public function form( $instance ) {
                       
                        $title_value = '';
                        if( isset($instance['title']) ) {
                            $title_value = $instance['title'];
                        }
			$title_field = new UI_Input_Fox(
					array(
						'id'		=> $this->get_field_id( 'title' ),
						'name'		=> $this->get_field_name( 'title' ),
						'value'		=> $title_value,
						'placeholder'	=> __( 'Widget title', 'blogetti' ),
						'label'		=> __( 'Title', 'blogetti' ),
					)
			);
			$title_html = $title_field->output();
                        
                        $app_key = '';
                        if( isset($instance['app_key']) ) {
                            $app_key = $instance['app_key'];
                        }
			$app_key_field = new UI_Input_Fox(
					array(
						'id'		=> $this->get_field_id( 'app_key' ),
						'name'		=> $this->get_field_name( 'app_key' ),
						'value'		=> $app_key,
						'placeholder'	=> __( 'api key', 'blogetti' ),
						'label'		=> __( 'Youtube API key', 'blogetti' ),
					)
			);
			$app_key_html = $app_key_field->output();

                        $channel_name = '';
                        if( isset($instance['channel_name']) ) {
                            $channel_name = $instance['channel_name'];
                        }
			$channel_name_field = new UI_Input_Fox(
					array(
						'id'		=> $this->get_field_id( 'channel_name' ),
						'name'		=> $this->get_field_name( 'channel_name' ),
						'value'		=> $channel_name,
						'placeholder'	=> __( 'channel name', 'blogetti' ),
						'label'		=> __( 'Channel name', 'blogetti' ),
					)
			);
			$channel_name_html = $channel_name_field->output();
                        
                        $channel_url = '';
                        if( isset($instance['channel_url']) ) {
                            $channel_url = $instance['channel_url'];
                        }
			$channel_url_field = new UI_Input_Fox(
					array(
						'id'		=> $this->get_field_id( 'channel_url' ),
						'name'		=> $this->get_field_name( 'channel_url' ),
						'value'		=> $channel_url,
						'placeholder'	=> __( 'channel url', 'blogetti' ),
						'label'		=> __( 'Channel url', 'blogetti' ),
					)
			);
			$channel_url_html = $channel_url_field->output(); ?>
                            <div class="tm-youtube-subscribe-form-widget">
                                    <p> <?php echo $title_html; ?> </p>

                                    <p> <?php echo $app_key_html; ?> </p>

                                    <p> <?php echo $channel_name_html; ?> </p>

                                    <p> <?php echo $channel_url_html; ?> </p>

                                    <p>&nbsp;</p>
                            </div>

             <?php  }


		/**
		 * Update settings
		 *
		 * @param type $new_instance array.
		 * @param type $old_instance array.
		 * @return type array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance					= array();
			$instance['title']			= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['app_key']		= esc_attr( $new_instance['app_key'] );
			$instance['channel_name']	= esc_attr( $new_instance['channel_name'] );
			$instance['channel_url']	= esc_attr( $new_instance['channel_url'] );

			return $instance;
		}
                private function render($route, $args) {
 
                        extract($args);

			ob_start();

                            require $route;

                            $view = ob_get_contents();

			ob_end_clean(); 
                        echo $view;
					
		}
	
	}
}
