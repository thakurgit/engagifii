<?php
/**
 * Installation related functions and actions.
 *
 * @author   Engagifii
 * @category engagifii 
 * @package  engagifii-api
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engagifii_Install{


	/**
	 * Install engagifii.
	 */
	public static function install() {
		self::create_tables();
		
		// Only create pages if modules are enabled
		$enabled_modules = get_option('engagifii_enabled_modules', array());
		
		// Ensure $enabled_modules is always an array
		if (!is_array($enabled_modules)) {
		    $enabled_modules = array();
		}			
	}


	/**
	 * Uninstall engagifii.
	 */
	public static function uninstall() {
		self::drop_tables();
	}

	 
	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( self::get_schema() );
	}

	/**
	 * Get Table schema.
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		 
	}

	/**
	 * drop Table.
	 */

	private static function drop_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		
	}	

}