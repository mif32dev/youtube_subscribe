<?php
/**
 * Frontend view
 *
 * @package TM_Youtube_Channel_Widget
 */
?>
<?php echo $before_widget; ?>
<div class="bg-white inset-3">
	<div class="youtube">
		<?php
		if ($title != '') {
			echo $before_title;
			apply_filters('widget_title', $title);
			echo $after_title;
		}
		?>

		<div class="channel-name">
			<h5 class="txt-heading text-primary"> <?php echo $channel_name; ?></h5>
			<p> <?php echo sprintf(__('%d Videos', 'blogetti'), $video_count); ?></p>
			<a href="<?php echo $channel_url; ?>" class="icon icon-lg icon-secondary fa fa-3x fa-youtube"></a>
		</div>

		<div class="button-cnt">
			<a href="<?php echo $channel_url; ?>" class="btn btn-primary"><i class="material-icons">play_circle_outline</i> <em><?php echo __('Subscribe', 'blogetti'); ?></em></a>

			<div class="youtube-cnt">
				<p><?php echo $subscriber_count ?></p>
			</div>
		</div>
	</div>
</div>
<?php
echo $after_widget;
