<?php
/**
 * Description: Youtube Subscribe Widget
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 *
 * @package Monster_Youtube_Subscribe_Widget
 *
 * @since 0.1
 */
if ( ! class_exists( 'Youtube_Subscribe_Widget' ) ) {

	/**
	 * Adds Youtube_Subscribe_Widget widget.
	 */
	class Youtube_Subscribe_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
					'youtube_subscribe_widget', __( 'Youtube subscribe widget', 'youtube_subscribe' ), array( 'description' => __( 'Youtube subscribe Widget', 'youtube_subscribe' ) )
			);
		}

		/**
		 * Get data about channel from YoutubeAPI
		 *
		 * @return array
		 */
		private function get_channel_data( $channel_url, $app_key = '' ) {

			$url_parts = explode("/", $channel_url);

			$url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id=' . end($url_parts) . '&key=' . $app_key;

			$result = Helper::get_contents( $url );

			return $result ? json_decode( $result, true ) : false;
		}

		/**
		 * Frontend view
		 *
		 * @param type $args array.
		 * @param type $instance array.
		 */
		public function widget( $args, $instance ) {

			$channel_data = $this->get_channel_data( Helper::array_get( $instance, 'channel_url' ), Helper::array_get( $instance, 'app_key', '' ) );

			if ( empty( $channel_data['items'][0]['statistics']['subscriberCount'] ) ) {
				$subscriber_count = 0;
			} else {
				$subscriber_count = Helper::array_get( $channel_data['items'][0]['statistics'], 'subscriberCount', 0 );
			}

			if ( empty( $channel_data['items'][0]['statistics']['videoCount'] ) ) {
				$video_count = Helper::array_get( $instance, 'novideo' );
			} else {
				$video_count = Helper::array_get( $channel_data['items'][0]['statistics'], 'videoCount', 0 );
				$video_count = $video_count . ' ' . Helper::array_get( $instance, 'manyvideos' );
				if ( 1 == $video_count ) {
					$video_count = $video_count . ' ' . Helper::array_get( $instance, 'onevideo' );
				}
			}

			Helper::render(
					'view/front-end.php', array(
				'before_widget' => $args['before_widget'],
				'before_title' => $args['before_title'],
				'after_title' => $args['after_title'],
				'after_widget' => $args['after_widget'],
				'title' => Helper::array_get( $instance, 'title' ),
				'channel_name' => Helper::array_get( $instance, 'channel_name' ),
				'channel_url' => Helper::array_get( $instance, 'channel_url' ),
				'subscriber_count' => $subscriber_count,
				'video_count' => $video_count,
					)
			);
		}

		/**
		 * Admin view
		 *
		 * @param type $instance array.
		 */
		public function form( $instance ) {
			$title_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'title' ),
						'name'			=> $this->get_field_name( 'title' ),
						'value'			=> Helper::array_get( $instance, 'title' ),
						'placeholder'	=> __( 'Widget title', 'youtube_subscribe' ),
						'label'			=> __( 'Title', 'youtube_subscribe' ),
					)
			);
			$title_html = $title_field->output();

			$app_key_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'app_key' ),
						'name'			=> $this->get_field_name( 'app_key' ),
						'value'			=> Helper::array_get( $instance, 'app_key' ),
						'placeholder'	=> __( 'api key', 'youtube_subscribe' ),
						'label'			=> __( 'Youtube API key', 'youtube_subscribe' ),
					)
			);
			$app_key_html = $app_key_field->output();

			$channel_name_field = new UI_Input_Fox(
					array(
						'id'		=> $this->get_field_id( 'channel_name' ),
						'name'		=> $this->get_field_name( 'channel_name' ),
						'value'		=> Helper::array_get( $instance, 'channel_name' ),
						'placeholder'	=> __( 'channel name', 'youtube_subscribe' ),
						'label'		=> __( 'Channel name', 'youtube_subscribe' ),
					)
			);
			$channel_name_html = $channel_name_field->output();

			$channel_url_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'channel_url' ),
						'name'			=> $this->get_field_name( 'channel_url' ),
						'value'			=> Helper::array_get( $instance, 'channel_url' ),
						'placeholder'	=> __( 'channel url', 'youtube_subscribe' ),
						'label'			=> __( 'Channel url', 'youtube_subscribe' ),
					)
			);
			$channel_url_html = $channel_url_field->output();

			$label_for_novideo = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'novideo' ),
						'name'			=> $this->get_field_name( 'novideo' ),
						'value'			=> Helper::array_get( $instance, 'novideo' ),
						'placeholder'	=> __( 'no video', 'youtube_subscribe' ),
						'label'			=> __( 'Videos count label(no video)', 'youtube_subscribe' ),
					)
			);
			$novideo_html = $label_for_novideo->output();

			$label_for_one_video = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'onevideo' ),
						'name'			=> $this->get_field_name( 'onevideo' ),
						'value'			=> Helper::array_get( $instance, 'onevideo' ),
						'placeholder'	=> __( 'video', 'youtube_subscribe' ),
						'label'			=> __( 'Videos count label(1 video)', 'youtube_subscribe' ),
					)
			);
			$one_video_html = $label_for_one_video->output();

			$label_for_many_videos = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'manyvideos' ),
						'name'			=> $this->get_field_name( 'manyvideos' ),
						'value'			=> Helper::array_get( $instance, 'manyvideos' ),
						'placeholder'	=> __( 'videos', 'youtube_subscribe' ),
						'label'			=> __( 'Videos count label(more than 1 video)', 'youtube_subscribe' ),
					)
			);
			$many_videos_html = $label_for_many_videos->output();

			Helper::render(
					'view/back-end.php',
					array(
						'title_html'		 => $title_html,
						'app_key_html'		 => $app_key_html,
						'channel_name_html'	 => $channel_name_html,
						'channel_url_html'	 => $channel_url_html,
						'novideo'			 => $novideo_html,
						'one_video'			 => $one_video_html,
						'many_videos'		 => $many_videos_html,
					)
			);
		}

		/**
		 * Update settings
		 *
		 * @param type $new_instance array.
		 * @param type $old_instance array.
		 * @return type array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['app_key'] = esc_attr( $new_instance['app_key'] );
			$instance['channel_name'] = esc_attr( $new_instance['channel_name'] );
			$instance['channel_url'] = esc_attr( $new_instance['channel_url'] );
			$instance['novideo'] = esc_attr( $new_instance['novideo'] );
			$instance['onevideo'] = esc_attr( $new_instance['onevideo'] );
			$instance['manyvideos'] = esc_attr( $new_instance['manyvideos'] );

			return $instance;
		}
	}

}
