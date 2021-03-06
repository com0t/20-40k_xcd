<?php
/**
 * Official importer - import officials into SportsPress.
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Importers
 * @version     2.7.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Official_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page  = 'sp_official_csv';
			$this->import_label = esc_attr__( 'Import Officials', 'sportspress' );
			$this->columns      = array(
				'post_title' => esc_attr__( 'Name', 'sportspress' ),
			);
			parent::__construct();
		}

		/**
		 * import function.
		 *
		 * @access public
		 * @param array $array
		 * @param array $columns
		 * @return void
		 */
		function import( $array = array(), $columns = array( 'post_title' ) ) {
			$this->imported = $this->skipped = 0;

			if ( ! is_array( $array ) || ! sizeof( $array ) ) :
				$this->footer();
				die();
			endif;

			$rows = array_chunk( $array, sizeof( $columns ) );

			foreach ( $rows as $row ) :

				$row = array_filter( $row );

				if ( empty( $row ) ) {
					continue;
				}

				$meta = array();

				foreach ( $columns as $index => $key ) :
					$meta[ $key ] = sp_array_value( $row, $index );
				endforeach;

				$name = sp_array_value( $meta, 'post_title' );

				if ( ! $name ) :
					$this->skipped++;
					continue;
				endif;

				$args = array(
					'post_type'   => 'sp_official',
					'post_status' => 'publish',
					'post_title'  => wp_strip_all_tags( $name ),
				);

				$id = wp_insert_post( $args );

				$this->imported++;

			endforeach;

			// Show Result
			echo '<div class="updated settings-error below-h2"><p>
				' . wp_kses_post( sprintf( __( 'Import complete - imported <strong>%1$s</strong> officials and skipped <strong>%2$s</strong>.', 'sportspress' ), esc_html( $this->imported ), esc_html( $this->skipped ) ) ) . '
			</p></div>';

			$this->import_end();
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end() {
			echo '<p>' . esc_html__( 'All done!', 'sportspress' ) . ' <a href="' . esc_url( admin_url( 'edit.php?post_type=sp_official' ) ) . '">' . esc_html__( 'View Officials', 'sportspress' ) . '</a>' . '</p>';

			do_action( 'import_end' );
		}

		/**
		 * header function.
		 *
		 * @access public
		 * @return void
		 */
		function header() {
			echo '<div class="wrap"><h2>' . esc_html__( 'Import Officials', 'sportspress' ) . '</h2>';
		}

		/**
		 * greet function.
		 *
		 * @access public
		 * @return void
		 */
		function greet() {
			echo '<div class="narrow">';
			echo '<p>' . esc_html__( 'Hi there! Choose a .csv file to upload, then click "Upload file and import".', 'sportspress' ) . '</p>';
			echo '<p>' . wp_kses_post( sprintf( __( 'Officials need to be defined with columns in a specific order. <a href="%s">Click here to download a sample</a>.', 'sportspress' ), esc_url( plugin_dir_url( SP_PLUGIN_FILE ) ) . 'dummy-data/officials-sample.csv' ) ) . '</p>';
			wp_import_upload_form( 'admin.php?import=sp_official_csv&step=1' );
			echo '</div>';
		}
	}
}
