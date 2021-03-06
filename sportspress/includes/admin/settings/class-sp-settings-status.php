<?php
/**
 * SportsPress System Status
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Settings_Status' ) ) :

	/**
	 * SP_Settings_Status
	 */
	class SP_Settings_Status extends SP_Settings_Page {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id    = 'status';
			$this->label = esc_attr__( 'System Status', 'sportspress' );

			if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && current_user_can( 'manage_options' ) ) {
				add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 99 );
			}

			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		}

		/**
		 * Output system status
		 *
		 * @access public
		 * @return void
		 */
		public function output() {
			?>
		<h3><?php esc_html_e( 'System Status', 'sportspress' ); ?></h3>
		<div class="updated sportspress-message">
			<p><?php esc_html_e( 'Please include this information when requesting support:', 'sportspress' ); ?> </p>
			<p class="submit"><a href="#" class="button-primary debug-report"><?php esc_html_e( 'Get System Report', 'sportspress' ); ?></a></p>
			<div id="debug-report"><textarea readonly="readonly"></textarea></div>
		</div>
		<br/>
		<table class="sp-status-table widefat" cellspacing="0">

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Environment', 'sportspress' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php esc_html_e( 'Home URL', 'sportspress' ); ?>:</td>
					<td><?php echo esc_url( home_url() ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Site URL', 'sportspress' ); ?>:</td>
					<td><?php echo esc_url( site_url() ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'SP Version', 'sportspress' ); ?>:</td>
					<td><?php echo esc_html( SP()->version ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WP Version', 'sportspress' ); ?>:</td>
					<td><?php bloginfo( 'version' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WP Multisite Enabled', 'sportspress' ); ?>:</td>
					<td>
					<?php
					if ( is_multisite() ) {
						esc_html_e( 'Yes', 'sportspress' );
					} else {
						esc_html_e( 'No', 'sportspress' );
					}
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Web Server Info', 'sportspress' ); ?>:</td>
					<td><?php echo esc_html( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'PHP Version', 'sportspress' ); ?>:</td>
					<td>
					<?php
					if ( function_exists( 'phpversion' ) ) {
						echo esc_html( phpversion() );}
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WP Memory Limit', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$memory = sp_let_to_num( WP_MEMORY_LIMIT );

					if ( $memory < 67108864 ) {
						echo '<mark class="error">' . wp_kses_post( sprintf( __( '%1$s - We recommend setting memory to at least 64MB. See: <a href="%2$s">Increasing memory allocated to PHP</a>', 'sportspress' ), esc_html( size_format( $memory ) ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( size_format( $memory ) ) . '</mark>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WP Debug Mode', 'sportspress' ); ?>:</td>
					<td>
					<?php
					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						echo '<mark class="yes">' . esc_html__( 'Yes', 'sportspress' ) . '</mark>';
					} else {
						echo '<mark class="no">' . esc_html__( 'No', 'sportspress' ) . '</mark>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WP Language', 'sportspress' ); ?>:</td>
					<td>
					<?php
					if ( defined( 'WPLANG' ) && WPLANG ) {
						echo esc_html( WPLANG );
					} else {
						esc_html_e( 'Default', 'sportspress' );
					}
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WP Max Upload Size', 'sportspress' ); ?>:</td>
					<td><?php echo esc_html( size_format( wp_max_upload_size() ) ); ?></td>
				</tr>
					<?php if ( function_exists( 'ini_get' ) ) : ?>
					<tr>
						<td><?php esc_html_e( 'PHP Post Max Size', 'sportspress' ); ?>:</td>
						<td><?php echo esc_html( size_format( sp_let_to_num( ini_get( 'post_max_size' ) ) ) ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'PHP Time Limit', 'sportspress' ); ?>:</td>
						<td><?php echo esc_html( ini_get( 'max_execution_time' ) ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'PHP Max Input Vars', 'sportspress' ); ?>:</td>
						<td><?php echo esc_html( ini_get( 'max_input_vars' ) ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'SUHOSIN Installed', 'sportspress' ); ?>:</td>
						<td><?php echo extension_loaded( 'suhosin' ) ? esc_html__( 'Yes', 'sportspress' ) : esc_html__( 'No', 'sportspress' ); ?></td>
					</tr>
				<?php endif; ?>
				<tr>
					<td><?php esc_html_e( 'Default Timezone', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$default_timezone = date_default_timezone_get();
					if ( 'UTC' !== $default_timezone ) {
						echo '<mark class="error">' . sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'sportspress' ), esc_attr( $default_timezone ) ) . '</mark>';
					} else {
						echo '<mark class="yes">' . sprintf( esc_html__( 'Default timezone is %s', 'sportspress' ), esc_attr( $default_timezone ) ) . '</mark>';
					}
					?>
					</td>
				</tr>
					<?php
					$posting = array();

					// fsockopen/cURL
					$posting['fsockopen_curl']['name'] = esc_attr__( 'fsockopen/cURL', 'sportspress' );
					if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
						if ( function_exists( 'fsockopen' ) && function_exists( 'curl_init' ) ) {
							$posting['fsockopen_curl']['note'] = esc_attr__( 'Your server has fsockopen and cURL enabled.', 'sportspress' );
						} elseif ( function_exists( 'fsockopen' ) ) {
							$posting['fsockopen_curl']['note'] = esc_attr__( 'Your server has fsockopen enabled, cURL is disabled.', 'sportspress' );
						} else {
							$posting['fsockopen_curl']['note'] = esc_attr__( 'Your server has cURL enabled, fsockopen is disabled.', 'sportspress' );
						}
						$posting['fsockopen_curl']['success'] = true;
					} else {
						$posting['fsockopen_curl']['note']    = esc_attr__( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'sportspress' ) . '</mark>';
						$posting['fsockopen_curl']['success'] = false;
					}

					// SOAP
					$posting['soap_client']['name'] = esc_attr__( 'SOAP Client', 'sportspress' );
					if ( class_exists( 'SoapClient' ) ) {
						$posting['soap_client']['note']    = esc_attr__( 'Your server has the SOAP Client class enabled.', 'sportspress' );
						$posting['soap_client']['success'] = true;
					} else {
						$posting['soap_client']['note']    = wp_kses_post( sprintf( __( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'sportspress' ), 'http://php.net/manual/en/class.soapclient.php' ) ) . '</mark>';
						$posting['soap_client']['success'] = false;
					}

					$posting = apply_filters( 'sportspress_debug_posting', $posting );

					foreach ( $posting as $post ) {
						$mark = ( isset( $post['success'] ) && $post['success'] == true ) ? 'yes' : 'error';
						?>
						<tr>
							<td><?php echo esc_html( $post['name'] ); ?>:</td>
							<td>
								<mark class="<?php echo esc_attr( $mark ); ?>">
								<?php echo wp_kses_data( $post['note'] ); ?>
								</mark>
							</td>
						</tr>
						<?php
					}
					?>
			</tbody>

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Plugins', 'sportspress' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php esc_html_e( 'Installed Plugins', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$active_plugins = (array) get_option( 'active_plugins', array() );

					if ( is_multisite() ) {
						$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
					}

						$sp_plugins = array();

					foreach ( $active_plugins as $plugin ) {

						$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$dirname        = dirname( $plugin );
						$version_string = '';

						if ( ! empty( $plugin_data['Name'] ) ) {

							// link the plugin name to the plugin url if available
							$plugin_name = $plugin_data['Name'];
							if ( ! empty( $plugin_data['PluginURI'] ) ) {
								$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'sportspress' ) . '">' . $plugin_name . '</a>';
							}

							if ( strstr( $dirname, 'sportspress' ) ) {

								if ( false === ( $version_data = get_transient( md5( $plugin ) . '_version_data' ) ) ) {
									$changelog = wp_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $dirname . '/changelog.txt' );
									$cl_lines  = explode( "\n", wp_remote_retrieve_body( $changelog ) );
									if ( ! empty( $cl_lines ) ) {
										foreach ( $cl_lines as $line_num => $cl_line ) {
											if ( preg_match( '/^[0-9]/', $cl_line ) ) {

												$date         = str_replace( '.', '-', trim( substr( $cl_line, 0, strpos( $cl_line, '-' ) ) ) );
												$version      = preg_replace( '~[^0-9,.]~', '', stristr( $cl_line, 'version' ) );
												$update       = trim( str_replace( '*', '', $cl_lines[ $line_num + 1 ] ) );
												$version_data = array(
													'date' => $date,
													'version' => $version,
													'update' => $update,
													'changelog' => $changelog,
												);
												set_transient( md5( $plugin ) . '_version_data', $version_data, 60 * 60 * 12 );
												break;
											}
										}
									}
								}

								if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '>' ) ) {
									$version_string = ' &ndash; <strong style="color:red;">' . $version_data['version'] . ' ' . esc_attr__( 'is available', 'sportspress' ) . '</strong>';
								}
							}

							$sp_plugins[] = $plugin_name . ' ' . esc_attr__( 'by', 'sportspress' ) . ' ' . $plugin_data['Author'] . ' ' . esc_attr__( 'version', 'sportspress' ) . ' ' . $plugin_data['Version'] . $version_string;

						}
					}

					if ( sizeof( $sp_plugins ) == 0 ) {
						echo '-';
					} else {
						echo implode( ', <br/>', array_map( 'wp_kses_post', $sp_plugins ) );
					}

					?>
					</td>
				</tr>
			</tbody>

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'SP Configuration', 'sportspress' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php esc_html_e( 'Sport', 'sportspress' ); ?>:</td>
					<td><?php echo esc_attr( get_option( 'sportspress_sport', __( 'None', 'sportspress' ) ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Event Outcomes', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_posts = array();
						$posts         = get_posts(
							array(
								'post_type'      => 'sp_outcome',
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'posts_per_page' => -1,
								'post_status'    => 'any',
							)
						);
					foreach ( $posts as $post ) {
						$display_posts[] = $post->post_title . ' (' . $post->post_name . ') [' . $post->menu_order . ']';
					}
						echo implode( ', ', array_map( 'esc_html', $display_posts ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Event Results', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_posts = array();
						$posts         = get_posts(
							array(
								'post_type'      => 'sp_result',
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'posts_per_page' => -1,
								'post_status'    => 'any',
							)
						);
					foreach ( $posts as $post ) {
						$display_posts[] = $post->post_title . ' (' . $post->post_name . ') [' . $post->menu_order . ']';
					}
						echo implode( ', ', array_map( 'esc_html', $display_posts ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player Performance', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_posts = array();
						$posts         = get_posts(
							array(
								'post_type'      => 'sp_performance',
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'posts_per_page' => -1,
								'post_status'    => 'any',
							)
						);
					foreach ( $posts as $post ) {
						$display_posts[] = $post->post_title . ' (' . $post->post_name . ') [' . $post->menu_order . ']';
					}
						echo implode( ', ', array_map( 'esc_html', $display_posts ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Table Columns', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_posts = array();
						$posts         = get_posts(
							array(
								'post_type'      => 'sp_column',
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'posts_per_page' => -1,
								'post_status'    => 'any',
							)
						);
					foreach ( $posts as $post ) {
						$sort_order      = sp_get_post_order( $post->ID );
						$display_posts[] = $post->post_title . ' (' . $post->post_name . ' = ' . get_post_meta( $post->ID, 'sp_equation', true ) . ') [' . $post->menu_order . ']' . ( '&mdash;' == $sort_order ? '' : ' {' . $sort_order . '}' );
					}
						echo implode( ', ', array_map( 'esc_html', $display_posts ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player Metrics', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_posts = array();
						$posts         = get_posts(
							array(
								'post_type'      => 'sp_metric',
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'posts_per_page' => -1,
								'post_status'    => 'any',
							)
						);
					foreach ( $posts as $post ) {
						$display_posts[] = $post->post_title . ' (' . $post->post_name . ') [' . $post->menu_order . ']';
					}
						echo implode( ', ', array_map( 'esc_html', $display_posts ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player Statistics', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_posts = array();
						$posts         = get_posts(
							array(
								'post_type'      => 'sp_statistic',
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'posts_per_page' => -1,
								'post_status'    => 'any',
							)
						);
					foreach ( $posts as $post ) {
						$display_posts[] = $post->post_title . ' (' . $post->post_name . ' = ' . get_post_meta( $post->ID, 'sp_equation', true ) . ') [' . $post->menu_order . ']';
					}
						echo implode( ', ', array_map( 'esc_html', $display_posts ) );
					?>
					</td>
				</tr>
			</tbody>

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'SP Taxonomies', 'sportspress' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php esc_html_e( 'Leagues', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_terms = array();
						$terms         = get_terms( 'sp_league', array( 'hide_empty' => 0 ) );
					foreach ( $terms as $term ) {
						$display_terms[] = $term->name . ' (' . $term->slug . ')';
					}
						echo implode( ', ', array_map( 'esc_html', $display_terms ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Seasons', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_terms = array();
						$terms         = get_terms( 'sp_season', array( 'hide_empty' => 0 ) );
					foreach ( $terms as $term ) {
						$display_terms[] = $term->name . ' (' . $term->slug . ')';
					}
						echo implode( ', ', array_map( 'esc_html', $display_terms ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Venues', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_terms = array();
						$terms         = get_terms( 'sp_venue', array( 'hide_empty' => 0 ) );
					foreach ( $terms as $term ) {
						$display_terms[] = $term->name . ' (' . $term->slug . ')';
					}
						echo implode( ', ', array_map( 'esc_html', $display_terms ) );
					?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Positions', 'sportspress' ); ?>:</td>
					<td>
					<?php
						$display_terms = array();
						$terms         = get_terms( 'sp_position', array( 'hide_empty' => 0 ) );
					foreach ( $terms as $term ) {
						$display_terms[] = $term->name . ' (' . $term->slug . ')';
					}
						echo implode( ', ', array_map( 'esc_html', $display_terms ) );
					?>
					</td>
				</tr>
			</tbody>

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'SP Post Types', 'sportspress' ); ?></th>
				</tr>
			</thead>

			<tbody>
					<?php
					$post_types = sp_post_types();
					foreach ( $post_types as $post_type ) :
						?>
				<tr>
					<td>
						<?php
						$object = get_post_type_object( $post_type );
						echo esc_html( $object->labels->name );
						?>
						:
					</td>
					<td>
						<?php $count = wp_count_posts( $post_type ); ?>
						<?php echo esc_html( $count->publish ); ?> publish, <?php echo esc_html( $count->future ); ?> future, <?php echo esc_html( $count->draft ); ?> draft, <?php echo esc_html( $count->private ); ?> private, <?php echo esc_html( $count->trash ); ?> trash, <?php echo esc_html( $count->{'auto-draft'} ); ?> auto-draft, <?php echo esc_html( $count->inherit ); ?> inherit
					</td>
				</tr>
					<?php endforeach; ?>
			</tbody>

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Theme', 'sportspress' ); ?></th>
				</tr>
			</thead>

					<?php
					$active_theme = wp_get_theme();
					if ( $active_theme->{'Author URI'} == 'http://themeboy.com' ) :

						$theme_dir = substr( strtolower( str_replace( ' ', '', $active_theme->Name ) ), 0, 45 );

						if ( false === ( $theme_version_data = get_transient( $theme_dir . '_version_data' ) ) ) :

							$theme_changelog = wp_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $theme_dir . '/changelog.txt' );
							$cl_lines        = explode( "\n", wp_remote_retrieve_body( $theme_changelog ) );
							if ( ! empty( $cl_lines ) ) :

								foreach ( $cl_lines as $line_num => $cl_line ) {
									if ( preg_match( '/^[0-9]/', $cl_line ) ) :

										$theme_date         = str_replace( '.', '-', trim( substr( $cl_line, 0, strpos( $cl_line, '-' ) ) ) );
										$theme_version      = preg_replace( '~[^0-9,.]~', '', stristr( $cl_line, 'version' ) );
										$theme_update       = trim( str_replace( '*', '', $cl_lines[ $line_num + 1 ] ) );
										$theme_version_data = array(
											'date'      => $theme_date,
											'version'   => $theme_version,
											'update'    => $theme_update,
											'changelog' => $theme_changelog,
										);
										set_transient( $theme_dir . '_version_data', $theme_version_data, 60 * 60 * 12 );
										break;

									endif;
								}

							endif;

						endif;

					endif;
					?>
			<tbody>
					<tr>
						<td><?php esc_html_e( 'Theme Name', 'sportspress' ); ?>:</td>
						<td>
						<?php
							echo esc_html( $active_theme->Name );
						?>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Theme Version', 'sportspress' ); ?>:</td>
						<td>
						<?php
							echo esc_html( $active_theme->Version );

						if ( ! empty( $theme_version_data['version'] ) && version_compare( $theme_version_data['version'], $active_theme->Version, '!=' ) ) {
							echo ' &ndash; <strong style="color:red;">' . esc_html( $theme_version_data['version'] ) . ' ' . esc_html__( 'is available', 'sportspress' ) . '</strong>';
						}
						?>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Author URL', 'sportspress' ); ?>:</td>
						<td>
						<?php
							echo esc_url( $active_theme->{'Author URI'} );
						?>
						</td>
					</tr>
			</tbody>

			<thead>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Templates', 'sportspress' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
						<?php

						$status = include SP()->plugin_path() . '/includes/admin/class-sp-admin-status.php';

						$template_paths = apply_filters( 'sportspress_template_overrides_scan_paths', array( 'SportsPress' => SP()->plugin_path() . '/templates/' ) );
						$found_files    = array();

						foreach ( $template_paths as $plugin_name => $template_path ) {
							$scanned_files[ $plugin_name ] = $status->scan_template_files( $template_path );
						}

						foreach ( $scanned_files as $plugin_name => $files ) {
							foreach ( $files as $file ) {
								if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
									$theme_file = get_stylesheet_directory() . '/' . $file;
								} elseif ( file_exists( get_stylesheet_directory() . '/sportspress/' . $file ) ) {
									$theme_file = get_stylesheet_directory() . '/sportspress/' . $file;
								} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
									$theme_file = get_template_directory() . '/' . $file;
								} elseif ( file_exists( get_template_directory() . '/sportspress/' . $file ) ) {
									$theme_file = get_template_directory() . '/sportspress/' . $file;
								} else {
									$theme_file = false;
								}

								if ( $theme_file ) {
									$core_version  = $status->get_file_version( SP()->plugin_path() . '/templates/' . $file );
									$theme_version = $status->get_file_version( $theme_file );

									if ( $core_version && ( empty( $theme_version ) || version_compare( $theme_version, $core_version, '<' ) ) ) {
										$found_files[ $plugin_name ][] = wp_kses_post( sprintf( __( '<code>%1$s</code> version <strong style="color:red">%2$s</strong> is out of date. The core version is %3$s', 'sportspress' ), basename( $theme_file ), $theme_version ? $theme_version : '-', $core_version ) );
									} else {
										$found_files[ $plugin_name ][] = sprintf( '<code>%s</code>', basename( $theme_file ) );
									}
								}
							}
						}

						if ( $found_files ) {
							foreach ( $found_files as $plugin_name => $found_plugin_files ) {
								?>
								<td><?php esc_html_e( 'Template Overrides', 'sportspress' ); ?> (<?php echo wp_kses_post( $plugin_name ); ?>):</td>
								<td><?php echo implode( ', <br/>', array_map( 'wp_kses_post', $found_plugin_files ) ); ?></td>
								<?php
							}
						} else {
							?>
							<td><?php esc_html_e( 'Template Overrides', 'sportspress' ); ?>:</td>
							<td><?php esc_html_e( 'No overrides present in theme.', 'sportspress' ); ?></td>
							<?php
						}
						?>
				</tr>
			</tbody>

		</table>

		<script type="text/javascript">
			/*
			@var i string default
			@var l how many repeat s
			@var s string to repeat
			@var w where s should indent
			*/
			jQuery.sp_strPad = function(i,l,s,w) {
				var o = i.toString();
				if (!s) { s = '0'; }
				while (o.length < l) {
					// empty
					if(w == 'undefined'){
						o = s + o;
					}else{
						o = o + s;
					}
				}
				return o;
			};


			jQuery('a.debug-report').click(function(){

				var report = "";

				jQuery('.sp-status-table thead, .sp-status-table tbody').each(function(){

					if ( jQuery( this ).is('thead') ) {

						report = report + "\n### " + jQuery.trim( jQuery( this ).text() ) + " ###\n\n";

					} else {

						jQuery('tr', jQuery( this )).each(function(){

							var the_name    = jQuery.sp_strPad( jQuery.trim( jQuery( this ).find('td:eq(0)').text() ), 25, ' ' );
							var the_value   = jQuery.trim( jQuery( this ).find('td:eq(1)').text() );
							var value_array = the_value.split( ', ' );

							if ( value_array.length > 1 ){

								// if value have a list of plugins ','
								// split to add new line
								var output = '';
								var temp_line ='';
								jQuery.each( value_array, function(key, line){
									var tab = ( key == 0 )?0:25;
									temp_line = temp_line + jQuery.sp_strPad( '', tab, ' ', 'f' ) + line +'\n';
								});

								the_value = temp_line;
							}

							report = report +''+ the_name + the_value + "\n";
						});

					}
				} );

				try {
					jQuery("#debug-report").slideDown();
					jQuery("#debug-report textarea").val( report ).focus().select();
					jQuery(this).fadeOut();
					return false;
				} catch(e){ console.log( e ); }

				return false;
			});
		</script>
			<?php
		}
	}

endif;

return new SP_Settings_Status();
