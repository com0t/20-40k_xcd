<?php
/**
 * Function Custom meta box for Premium
 * 
 * @package WP News and Scrolling Widgets
 * @since 2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="pro-notice"><?php echo sprintf( __( 'Upgrade to <a href="%s" target="_blank">Pro Version</a> with Essential Plugin Bundle to boost your website design.', 'sp-news-and-widget'), WPNW_PLUGIN_LINK); ?></div>
<table class="form-table wpnw-metabox-table">
	<tbody>

		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Layouts', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('6 (Grid, Slider, Carousel, List, Gridbox, GridBox Slider, News Ticker). In lite version only 1 layout.', 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Designs', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('120+. In lite version only two design.', 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Widgets', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('7. In lite version only two widget.', 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('WP Templating Features ', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('You can modify plugin html/designs in your current theme.', 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Shortcode Generator ', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Play with all shortcode parameters with preview panel. No documentation required.' , 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Drag & Drop Slide Order Change', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Arrange your desired slides with your desired order and display.' , 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Page Builder Support', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Gutenberg Block, Elementor, Bevear Builder, SiteOrigin, Divi, Visual Composer and Fusion Page Builder Support', 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
		<tr class="wpnw-pro-feature">
			<th>
				<?php _e('Exclude News and Exclude Some Categories', 'sp-news-and-widget'); ?><span class="wpnw-pro-tag"><?php _e('PRO','sp-news-and-widget');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Do not display the news & Do not display the news for particular categories.' , 'sp-news-and-widget'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .wpnw-metabox-table -->

