<?php
/**
 * SportsPress General Settings
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Settings_General' ) ) :

	/**
	 * SP_Settings_General
	 */
	class SP_Settings_General extends SP_Settings_Page {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id    = 'general';
			$this->label = esc_attr__( 'General', 'sportspress' );

			add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'sportspress_admin_field_timezone', array( $this, 'timezone_setting' ) );
			add_action( 'sportspress_admin_field_colors', array( $this, 'colors_setting' ) );
			add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
		}

		/**
		 * Get settings array
		 *
		 * @return array
		 */
		public function get_settings() {

			$presets = SP_Admin_Sports::get_preset_options();

			$leagues = array( '' => esc_attr__( '&mdash; Select &mdash;', 'sportspress' ) );

			$terms = get_terms(
				array(
					'taxonomy'   => 'sp_league',
					'hide_empty' => false,
				)
			);

			if ( $terms ) {
				foreach ( $terms as $term ) {
					$leagues[ $term->term_id ] = $term->name;
				}
			}

			$seasons = array( '' => esc_attr__( '&mdash; Select &mdash;', 'sportspress' ) );

			$terms = get_terms(
				array(
					'taxonomy'   => 'sp_season',
					'hide_empty' => false,
				)
			);

			if ( $terms ) {
				foreach ( $terms as $term ) {
					$seasons[ $term->term_id ] = $term->name;
				}
			}

			$settings = array_merge(
				array(
					array(
						'title' => esc_attr__( 'General Options', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'general_options',
					),
				),
				apply_filters(
					'sportspress_general_options',
					array(
						array( 'type' => 'timezone' ),

						array(
							'title'   => esc_attr__( 'Sport', 'sportspress' ),
							'id'      => 'sportspress_sport',
							'default' => 'none',
							'type'    => 'sport',
							'options' => $presets,
						),

						array(
							'title'   => esc_attr__( 'Main League', 'sportspress' ),
							'id'      => 'sportspress_league',
							'default' => null,
							'type'    => 'select',
							'options' => $leagues,
						),

						array(
							'title'   => esc_attr__( 'Current Season', 'sportspress' ),
							'id'      => 'sportspress_season',
							'default' => null,
							'type'    => 'select',
							'options' => $seasons,
						),
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'general_options',
					),
					array(
						'title' => esc_attr__( 'Styles and Scripts', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'script_styling_options',
					),
				)
			);

			$options = array(
				array( 'type' => 'colors' ),
			);

			if ( ( $styles = SP_Frontend_Scripts::get_styles() ) && array_key_exists( 'sportspress-general', $styles ) ) :
				$options = array_merge(
					$options,
					array(
						array(
							'title'   => esc_attr__( 'Align', 'sportspress' ),
							'id'      => 'sportspress_table_text_align',
							'default' => 'default',
							'type'    => 'radio',
							'options' => array(
								'default' => esc_attr__( 'Default', 'sportspress' ),
								'left'    => esc_attr__( 'Left', 'sportspress' ),
								'center'  => esc_attr__( 'Center', 'sportspress' ),
								'right'   => esc_attr__( 'Right', 'sportspress' ),
							),
						),

						array(
							'title'             => esc_attr__( 'Padding', 'sportspress' ),
							'id'                => 'sportspress_table_padding',
							'class'             => 'small-text',
							'default'           => null,
							'placeholder'       => esc_attr__( 'Auto', 'sportspress' ),
							'desc'              => 'px',
							'type'              => 'number',
							'custom_attributes' => array(
								'step' => 1,
							),
						),
					)
				);
			endif;

			$options = array_merge(
				$options,
				array(
					array(
						'title' => esc_attr__( 'Custom CSS', 'sportspress' ),
						'id'    => 'sportspress_custom_css',
						'css'   => 'width:100%; height: 130px;',
						'type'  => 'textarea',
					),
				),
				apply_filters(
					'sportspress_general_script_options',
					array(
						array(
							'title'         => esc_attr__( 'Scripts', 'sportspress' ),
							'desc'          => esc_attr__( 'Live countdowns', 'sportspress' ),
							'id'            => 'sportspress_enable_live_countdowns',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => 'start',
							'desc_tip'      => esc_attr__( 'This will enable a script allowing the countdowns to be animated.', 'sportspress' ),
						),

						array(
							'desc'          => esc_attr__( 'Shortcode menu', 'sportspress' ),
							'id'            => 'sportspress_rich_editing',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => 'end',
							'desc_tip'      => esc_attr__( 'This will enable a shortcode menu to be displayed in the visual editor.', 'sportspress' ),
						),
					)
				),
				array(
					array(
						'title'         => esc_attr__( 'Tables', 'sportspress' ),
						'desc'          => esc_attr__( 'Responsive', 'sportspress' ),
						'id'            => 'sportspress_enable_responsive_tables',
						'default'       => 'no',
						'type'          => 'checkbox',
						'checkboxgroup' => 'start',
					),

					array(
						'desc'          => esc_attr__( 'Scrollable', 'sportspress' ),
						'id'            => 'sportspress_enable_scrollable_tables',
						'default'       => 'yes',
						'type'          => 'checkbox',
						'checkboxgroup' => '',
					),

					array(
						'desc'          => esc_attr__( 'Sortable', 'sportspress' ),
						'id'            => 'sportspress_enable_sortable_tables',
						'default'       => 'yes',
						'type'          => 'checkbox',
						'checkboxgroup' => 'end',
					),

					array(
						'title'    => esc_attr__( 'Widgets', 'sportspress' ),
						'desc'     => esc_attr__( 'Unique', 'sportspress' ),
						'id'       => 'sportspress_widget_unique',
						'default'  => 'no',
						'type'     => 'checkbox',
						'desc_tip' => esc_attr__( 'Hide widget when same as content.', 'sportspress' ),
					),
				)
			);

			if ( apply_filters( 'sportspress_enable_header', false ) ) {
				$options[] = array(
					'title'             => esc_attr__( 'Header Offset', 'sportspress' ),
					'id'                => 'sportspress_header_offset',
					'class'             => 'small-text',
					'default'           => null,
					'placeholder'       => esc_attr__( 'Auto', 'sportspress' ),
					'desc'              => 'px',
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => 1,
					),
				);
			}

			$settings = array_merge(
				$settings,
				apply_filters( 'sportspress_script_styling_options', $options ),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'script_styling_options',
					),
				)
			);

			return apply_filters( 'sportspress_general_settings', $settings ); // End general settings
		}

		/**
		 * Save settings
		 */
		public function save() {
			if ( isset( $_POST['sportspress_sport'] ) && ! empty( $_POST['sportspress_sport'] ) && get_option( 'sportspress_sport', null ) !== $_POST['sportspress_sport'] ) :
				$sport = sanitize_key( $_POST['sportspress_sport'] );
				SP_Admin_Sports::apply_preset( $sport );
				delete_option( '_sp_needs_welcome' );
				update_option( 'sportspress_installed', 1 );
			endif;

			if ( isset( $_POST['add_sample_data'] ) ) :
				SP_Admin_Sample_Data::delete_posts();
				SP_Admin_Sample_Data::insert_posts();
			endif;

			$settings = $this->get_settings();
			SP_Admin_Settings::save_fields( $settings );

			// Map UTC+- timezones to gmt_offsets and set timezone_string to empty.
			if ( ! empty( $_POST['timezone_string'] ) && preg_match( '/^UTC[+-]/', $_POST['timezone_string'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				$_POST['gmt_offset']      = preg_replace( '/UTC\+?/', '', sanitize_text_field( wp_unslash( $_POST['timezone_string'] ) ) );
				$_POST['timezone_string'] = '';
			}

			if ( isset( $_POST['timezone_string'] ) ) {
				update_option( 'timezone_string', sanitize_option( 'timezone_string', wp_unslash( $_POST['timezone_string'] ) ) );
			}

			if ( isset( $_POST['gmt_offset'] ) ) {
				update_option( 'gmt_offset', sanitize_option( 'gmt_offset', wp_unslash( $_POST['gmt_offset'] ) ) );
			}

			if ( isset( $_POST['sportspress_frontend_css_primary'] ) ) {

				// Save settings
				$primary    = ( ! empty( $_POST['sportspress_frontend_css_primary'] ) ) ? sp_format_hex( sanitize_text_field( wp_unslash( $_POST['sportspress_frontend_css_primary'] ) ) ) : '';
				$background = ( ! empty( $_POST['sportspress_frontend_css_background'] ) ) ? sp_format_hex( sanitize_text_field( wp_unslash( $_POST['sportspress_frontend_css_background'] ) ) ) : '';
				$text       = ( ! empty( $_POST['sportspress_frontend_css_text'] ) ) ? sp_format_hex( sanitize_text_field( wp_unslash( $_POST['sportspress_frontend_css_text'] ) ) ) : '';
				$heading    = ( ! empty( $_POST['sportspress_frontend_css_heading'] ) ) ? sp_format_hex( sanitize_text_field( wp_unslash( $_POST['sportspress_frontend_css_heading'] ) ) ) : '';
				$link       = ( ! empty( $_POST['sportspress_frontend_css_link'] ) ) ? sp_format_hex( sanitize_text_field( wp_unslash( $_POST['sportspress_frontend_css_link'] ) ) ) : '';
				$customize  = ( ! empty( $_POST['sportspress_frontend_css_customize'] ) ) ? 1 : '';

				$colors = array(
					'primary'    => $primary,
					'background' => $background,
					'text'       => $text,
					'heading'    => $heading,
					'link'       => $link,
					'customize'  => $customize,
				);

				// Merge with existing options if available
				$options = get_option( 'themeboy' );
				if ( is_array( $options ) ) {
					$colors = array_merge( $options, $colors );
				}

				update_option( 'themeboy', $colors );
			}
		}

		/**
		 * Timezone settings
		 *
		 * @access public
		 * @return void
		 */
		public function timezone_setting() {
			$current_offset = get_option( 'gmt_offset' );
			$tzstring       = get_option( 'timezone_string' );

			$check_zone_info = true;

			// Remove old Etc mappings. Fallback to gmt_offset.
			if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
				$tzstring = '';
			}

			if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
				$check_zone_info = false;
				if ( 0 == $current_offset ) {
					$tzstring = 'UTC+0';
				} elseif ( $current_offset < 0 ) {
					$tzstring = 'UTC' . $current_offset;
				} else {
					$tzstring = 'UTC+' . $current_offset;
				}
			}
			$class = 'chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' );
			?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="timezone_string"><?php esc_html_e( 'Timezone', 'sportspress' ); ?> <i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php esc_attr_e( 'Choose a city in the same timezone as you.', 'sportspress' ); ?>"></i></label>
			</th>
			<td class="forminp">
				<legend class="screen-reader-text"><span><?php esc_html_e( 'Timezone', 'sportspress' ); ?></span></legend>
				<select id="timezone_string" name="timezone_string" class="<?php echo esc_attr( $class ); ?>">
					<?php echo wp_kses( wp_timezone_choice( $tzstring ), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
				</select>
			   </td>
		   </tr>
			<?php
		}

		/**
		 * Output the frontend styles settings.
		 *
		 * @access public
		 * @return void
		 */
		public function colors_setting() {
			// Define color schemes each with 5 colors: Primary, Background, Text, Heading, Link
			$color_schemes = apply_filters(
				'sportspress_color_schemes',
				array(
					'ThemeBoy' => array( '2b353e', 'f4f4f4', '222222', 'ffffff', '00a69c' ),
					'Gold'     => array( '333333', 'f7f7f7', '333333', 'd8bf94', '9f8958' ),
					'Denim'    => array( '0e2440', 'eae5e0', '0e2440', 'ffffff', '2b6291' ),
					'Patriot'  => array( '0d4785', 'ecedee', '333333', 'ffffff', 'c51d27' ),
					'Metro'    => array( '3a7895', '223344', 'ffffff', 'ffffff', 'ffa800' ),
					'Stellar'  => array( '313150', '050528', 'ffffff', 'ffffff', 'e00034' ),
					'Carbon'   => array( '353535', '191919', 'ededed', 'ffffff', 'f67f17' ),
					'Avocado'  => array( '00241e', '013832', 'ffffff', 'ffffff', 'efb11e' ),
				)
			);
			?>
		<tr valign="top" class="themeboy">
			<th scope="row" class="titledesc">
				<?php esc_html_e( 'Color Scheme', 'sportspress' ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<?php foreach ( $color_schemes as $name => $colors ) { ?>
						<div class="color-option sp-color-option">
							<label data-sp-colors="<?php echo implode( ',', array_map( 'esc_attr', $colors ) ); ?>"><?php echo esc_html( $name ); ?></label>
							<table class="color-palette">
								<tbody>
									<tr>
										<td style="background-color: #<?php echo esc_attr( $colors[0] ); ?>">&nbsp;</td>
										<td style="background-color: #<?php echo esc_attr( $colors[0] ); ?>">&nbsp;</td>
										<td style="background-color: #<?php echo esc_attr( $colors[4] ); ?>">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</fieldset>
				<fieldset>
					<div class="sp-custom-colors">
						<label data-sp-colors="<?php echo implode( ',', array_map( 'esc_attr', $colors ) ); ?>"><?php esc_html_e( 'Customize', 'sportspress' ); ?></label><br>
						<?php
						// Get settings
						$colors = array_map( 'esc_attr', (array) get_option( 'themeboy', array() ) );
						if ( empty( $colors ) ) {
							$colors = array_map( 'esc_attr', (array) get_option( 'sportspress_frontend_css_colors', array() ) );
						}

						// Fallback
						if ( ! isset( $colors['customize'] ) ) {
							$colors['customize'] = ( 'yes' == get_option( 'sportspress_enable_frontend_css', 'no' ) );
						}

						// Defaults
						if ( empty( $colors['primary'] ) ) {
							$colors['primary'] = '#2b353e';
						}
						if ( empty( $colors['background'] ) ) {
							$colors['background'] = '#f4f4f4';
						}
						if ( empty( $colors['text'] ) ) {
							$colors['text'] = '#222222';
						}
						if ( empty( $colors['heading'] ) ) {
							$colors['heading'] = '#ffffff';
						}
						if ( empty( $colors['link'] ) ) {
							$colors['link'] = '#00a69c';
						}

						// Show inputs
						$this->color_picker( esc_attr__( 'Primary', 'sportspress' ), 'sportspress_frontend_css_primary', $colors['primary'] );
						$this->color_picker( esc_attr__( 'Background', 'sportspress' ), 'sportspress_frontend_css_background', $colors['background'] );
						$this->color_picker( esc_attr__( 'Text', 'sportspress' ), 'sportspress_frontend_css_text', $colors['text'] );
						$this->color_picker( esc_attr__( 'Heading', 'sportspress' ), 'sportspress_frontend_css_heading', $colors['heading'] );
						$this->color_picker( esc_attr__( 'Link', 'sportspress' ), 'sportspress_frontend_css_link', $colors['link'] );

						if ( ( $styles = SP_Frontend_Scripts::get_styles() ) && array_key_exists( 'sportspress-general', $styles ) ) :
							?>
							<br>
							<label for="sportspress_frontend_css_customize">
								<input name="sportspress_frontend_css_customize" id="sportspress_frontend_css_customize" type="checkbox" value="1" <?php checked( $colors['customize'] ); ?>>
								<?php esc_html_e( 'Enable', 'sportspress' ); ?>
							</label>
						<?php endif; ?>
					</div>
				</fieldset>
			</td>
		</tr>
			<?php
		}

		/**
		 * Output a colour picker input box.
		 *
		 * @access public
		 * @param mixed $name
		 * @param mixed $id
		 * @param mixed $value
		 * @return void
		 */
		function color_picker( $name, $id, $value ) {
			echo '<div class="sp-color-box"><strong>' . esc_html( $name ) . '</strong>
	   		<input name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
	    </div>';
		}
	}

endif;

return new SP_Settings_General();
