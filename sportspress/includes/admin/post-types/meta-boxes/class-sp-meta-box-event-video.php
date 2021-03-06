<?php
/**
 * Event Video
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SP_Meta_Box_Event_Video
 */
class SP_Meta_Box_Event_Video {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$video = get_post_meta( $post->ID, 'sp_video', true );
		if ( $video ) :
			?>
		<fieldset class="sp-video-embed">
			<?php echo wp_kses( apply_filters( 'the_content', '[embed width="254"]' . esc_url( $video ) . '[/embed]' ), array( 'iframe' => array( 'title' => array(), 'width' => array(), 'height' => array(), 'src' => array(), 'frameborder' => array(), 'allow' => array(), 'allowfullscreen' => array(), 'style' => array()) ) ); ?>
			<p><a href="#" class="sp-remove-video"><?php esc_attr_e( 'Remove video', 'sportspress' ); ?></a></p>
		</fieldset>
		<?php endif; ?>
		<fieldset class="sp-video-field hidden">
			<p><strong><?php esc_attr_e( 'URL', 'sportspress' ); ?></strong></p>
			<p><input class="widefat" type="text" name="sp_video" id="sp_video" value="<?php echo esc_url( $video ); ?>"></p>
			<p><a href="#" class="sp-remove-video"><?php esc_attr_e( 'Cancel', 'sportspress' ); ?></a></p>
		</fieldset>
		<fieldset class="sp-video-adder
		<?php
		if ( $video ) :
			?>
			 hidden<?php endif; ?>">
			<p><a href="#" class="sp-add-video"><?php esc_attr_e( 'Add video', 'sportspress' ); ?></a></p>
		</fieldset>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_video', sp_array_value( $_POST, 'sp_video', null, 'text' ) );
	}
}
