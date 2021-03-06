<?php
/**
 * Calendar Details
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
 * SP_Meta_Box_Calendar_Details
 */
class SP_Meta_Box_Calendar_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$taxonomies    = get_object_taxonomies( 'sp_calendar' );
		$caption       = get_post_meta( $post->ID, 'sp_caption', true );
		$status        = get_post_meta( $post->ID, 'sp_status', true );
		$date          = get_post_meta( $post->ID, 'sp_date', true );
		$date_from     = get_post_meta( $post->ID, 'sp_date_from', true );
		$date_to       = get_post_meta( $post->ID, 'sp_date_to', true );
		$date_past     = get_post_meta( $post->ID, 'sp_date_past', true );
		$date_future   = get_post_meta( $post->ID, 'sp_date_future', true );
		$date_relative = get_post_meta( $post->ID, 'sp_date_relative', true );
		$event_format  = get_post_meta( $post->ID, 'sp_event_format', true );
		$day           = get_post_meta( $post->ID, 'sp_day', true );
		$teams         = get_post_meta( $post->ID, 'sp_team', false );
		$players       = get_post_meta( $post->ID, 'sp_player', false );
		$table_id      = get_post_meta( $post->ID, 'sp_table', true );
		$orderby       = get_post_meta( $post->ID, 'sp_orderby', true );
		$order         = get_post_meta( $post->ID, 'sp_order', true );
		?>
		<div>
			<p><strong><?php esc_attr_e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>

			<p><strong><?php esc_attr_e( 'Status', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'name'     => 'sp_status',
					'id'       => 'sp_status',
					'selected' => $status,
				);
				sp_dropdown_statuses( $args );
				?>
			</p>
			<p><strong><?php esc_attr_e( 'Event Format', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_event_format" class="postform">
					<option value="all">All</option>
					<?php foreach ( SP()->formats->event as $key => $format ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $event_format, $key ); ?>><?php echo esc_html( $format ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<div class="sp-date-selector">
				<p><strong><?php esc_attr_e( 'Date', 'sportspress' ); ?></strong></p>
				<p>
					<?php
					$args = array(
						'name'     => 'sp_date',
						'id'       => 'sp_date',
						'selected' => $date,
					);
					sp_dropdown_dates( $args );
					?>
				</p>
				<div class="sp-date-range">
					<p class="sp-date-range-absolute">
						<input type="text" class="sp-datepicker-from" name="sp_date_from" value="<?php echo $date_from ? esc_attr( $date_from ) : esc_attr( date_i18n( 'Y-m-d' ) ); ?>" size="10">
						:
						<input type="text" class="sp-datepicker-to" name="sp_date_to" value="<?php echo $date_to ? esc_attr( $date_to ) : esc_attr( date_i18n( 'Y-m-d' ) ); ?>" size="10">
					</p>

					<p class="sp-date-range-relative">
						<?php esc_attr_e( 'Past', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="sp_date_past" value="<?php echo '' !== $date_past ? esc_attr( $date_past ) : 7; ?>">
						<?php esc_attr_e( 'days', 'sportspress' ); ?>
						&rarr;
						<?php esc_attr_e( 'Next', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="sp_date_future" value="<?php echo '' !== $date_future ? esc_attr( $date_future ) : 7; ?>">
						<?php esc_attr_e( 'days', 'sportspress' ); ?>
					</p>

					<p class="sp-date-relative">
						<label>
							<input type="checkbox" name="sp_date_relative" value="1" id="sp_date_relative" <?php checked( $date_relative ); ?>>
							<?php esc_attr_e( 'Relative', 'sportspress' ); ?>
						</label>
					</p>
				</div>
			</div>
			<div class="sp-event-day-field">
				<p><strong><?php esc_attr_e( 'Match Day', 'sportspress' ); ?></strong></p>
				<p>
					<input name="sp_day" type="text" class="medium-text" placeholder="<?php esc_attr_e( 'All', 'sportspress' ); ?>" value="<?php echo esc_attr( $day ); ?>">
				</p>
			</div>
			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong><?php esc_attr_e( 'Team', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'post_type'   => 'sp_team',
					'name'        => 'sp_team[]',
					'selected'    => $teams,
					'values'      => 'ID',
					'class'       => 'widefat',
					'property'    => 'multiple',
					'chosen'      => true,
					'placeholder' => esc_attr__( 'All', 'sportspress' ),
				);
				if ( ! sp_dropdown_pages( $args ) ) :
					sp_post_adder( 'sp_team', esc_attr__( 'Add New', 'sportspress' ) );
				endif;
				?>
			</p>
			<p><strong><?php esc_attr_e( 'Player', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'post_type'   => 'sp_player',
					'name'        => 'sp_player[]',
					'selected'    => $players,
					'values'      => 'ID',
					'class'       => 'widefat',
					'property'    => 'multiple',
					'chosen'      => true,
					'placeholder' => esc_attr__( 'All', 'sportspress' ),
				);
				if ( ! sp_dropdown_pages( $args ) ) :
					sp_post_adder( 'sp_player', esc_attr__( 'Add New', 'sportspress' ) );
				endif;
				?>
			</p>
			<p><strong><?php esc_attr_e( 'Sort by', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_orderby">
					<option value="date" <?php selected( 'date', $orderby ); ?>><?php esc_attr_e( 'Date', 'sportspress' ); ?></option>
					<option value="day" <?php selected( 'day', $orderby ); ?>><?php esc_attr_e( 'Match Day', 'sportspress' ); ?></option>
				</select>
			</p>
			<p><strong><?php esc_attr_e( 'Sort Order', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_order">
					<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php esc_attr_e( 'Ascending', 'sportspress' ); ?></option>
					<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php esc_attr_e( 'Descending', 'sportspress' ); ?></option>
				</select>
			</p>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', sp_array_value( $_POST, 'sp_caption', 0, 'text' ) );
		update_post_meta( $post_id, 'sp_status', sp_array_value( $_POST, 'sp_status', 0, 'text' ) );
		update_post_meta( $post_id, 'sp_event_format', sp_array_value( $_POST, 'sp_event_format', 0, 'key' ) );
		update_post_meta( $post_id, 'sp_date', sp_array_value( $_POST, 'sp_date', 0, 'text' ) );
		update_post_meta( $post_id, 'sp_date_from', sp_array_value( $_POST, 'sp_date_from', null, 'text' ) );
		update_post_meta( $post_id, 'sp_date_to', sp_array_value( $_POST, 'sp_date_to', null, 'text' ) );
		update_post_meta( $post_id, 'sp_date_past', sp_array_value( $_POST, 'sp_date_past', 0, 'text' ) );
		update_post_meta( $post_id, 'sp_date_future', sp_array_value( $_POST, 'sp_date_future', 0, 'text' ) );
		update_post_meta( $post_id, 'sp_date_relative', sp_array_value( $_POST, 'sp_date_relative', 0, 'text' ) );
		update_post_meta( $post_id, 'sp_day', sp_array_value( $_POST, 'sp_day', null, 'text' ) );
		$tax_input = sp_array_value( $_POST, 'tax_input', array() );
		update_post_meta( $post_id, 'sp_main_league', in_array( 'auto', sp_array_value( $tax_input, 'sp_league' ) ) );
		update_post_meta( $post_id, 'sp_current_season', in_array( 'auto', sp_array_value( $tax_input, 'sp_season' ) ) );
		update_post_meta( $post_id, 'sp_orderby', sp_array_value( $_POST, 'sp_orderby', null, 'key' ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', null, 'text' ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array(), 'int' ) );
		sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array(), 'int' ) );
	}
}
