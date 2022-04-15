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
		self::add_EBT_page();
		self::add_LBT_Page();
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

	private static function add_EBT_page() {
       	global $wpdb;  
      	$checkPageExists =  $wpdb->get_results("select *  from ".$wpdb->prefix."posts WHERE post_name in ('endorsement-grid-view','endorsement-detail') and post_type='page' ");
       	$foundPages = $wpdb->num_rows;       

       	if($foundPages<1)
       	{
			$ebt_pages = array( array(
			'post_title'    => wp_strip_all_tags( 'Endorsement Grid View' ),
			'post_content'  => '<div class="capital-watch-main-contatiner">[endorsement-grid-list]</div>',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'page',
			'post_name'     => 'endorsement-grid-view',
		),array(
			'post_title'    => wp_strip_all_tags( 'Endorsement Detail' ),
			'post_content'  => '<div class="capital-watch-main-contatiner">[endorsement_grid_detail_information]</div>',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'page',
			'post_name'     => 'endorsement-detail',
		));
		foreach ($ebt_pages as $key => $page) {
			 wp_insert_post( $page );
		}
		}  
	}

	private static function add_LBT_Page() {
       	global $wpdb;  
      	$checkPageExists =  $wpdb->get_results("select *  from ".$wpdb->prefix."posts WHERE post_name in ('engagifii-grid-view','engagifii-detail') and post_type='page' ");
       	$foundPages = $wpdb->num_rows;       

       	if($foundPages<1)
       	{
			$lbt_pages = array( array(
				'post_title'    => wp_strip_all_tags( 'Engagifii Grid View' ),
				'post_content'  => '<div class="capital-watch-main-contatiner">[legislation-list]</div>',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
				'post_name'     => 'engagifii-grid-view',
			),array(
				'post_title'    => wp_strip_all_tags( 'Engagifii Detail' ),
				'post_content'  => '<div class="capital-watch-main-contatiner">[legislation_grid_detail_information]</div>',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
				'post_name'     => 'engagifii-detail',
			));
			foreach ($lbt_pages as $key => $page) {
				wp_insert_post( $page );
			}
		}
	}

}