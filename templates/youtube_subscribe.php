<?php
/**
 * Frontend view
 *
 * @package TM_Youtube_Channel_Widget
 */
?>
<?php echo $args['before_widget']; ?>
<div class="bg-white inset-3">
	<div class="youtube">
		<?php
		if ( '' != $args['title'] ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $args['title'] );
			echo $args['after_title'];
		}
		?>

		<div class="channel-name">
			<h5 class="txt-heading text-primary"> <?php echo $args['channel_name']; ?></h5>
			<p> <?php echo $args['video_count']; ?></p>
			<a href="<?php echo $args['channel_url']; ?>" class="icon icon-lg icon-secondary fa fa-3x fa-youtube"></a>
		</div>

		<div class="button-cnt">
			<a href="<?php echo $args['channel_url']; ?>" class="btn btn-primary">
				<i class="material-icons fa fa-play-circle-o"></i>
				<em><?php echo __( 'Subscribe', 'blogetti' ); ?></em>
			</a>

			<div class="youtube-cnt">
				<p><?php echo $args['subscriber_count']; ?></p>
			</div>
		</div>
	</div>
</div>
<?php
echo $args['after_widget'];
