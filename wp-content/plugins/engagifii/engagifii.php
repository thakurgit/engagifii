<?php
/**
 * Plugin Name: Engagifii Module
 * Description: Engagifii API to fetch blling details, courses and classes
 * Version: 1.2.0
 * Date: 12-04-2023
 * Author: Engagifii
 * Requires at least: 4.4
 * Tested up to: 6.2
 *
 * Text Domain: engagifii
 *
 * @package engagifii-api
 * @category wordpress
 * @author Engagifii
 */
 

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}



Final Class Engagifii {
	/**
	* Engagifii version, shortcode, wp object,plugin path
	*
	* @var string
	*/
	public $version = '1.0.0';
	public $engagifiiShortcode;
	public $wpdbObject;
	public $pluginBasePath;

	/**
	* The single instance of the class.
	* @since 1.0.0
	*/
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	* Engagifii Constructor.
	*/
	public function __construct() {
		global $wpdb;
		$this->wpdbObject=$wpdb;
		$this->pluginBasePath=plugin_dir_path(__FILE__);
		$this->includes();
		$this->init_hooks();
	}

	/**
	* Hook into actions and filters.
	* @since  2.3
	*/
	private function init_hooks() {
		register_activation_hook( __FILE__, array( 'Engagifii_Install', 'install' ) );
		register_uninstall_hook( __FILE__, array( 'Engagifii_Install','uninstall'));
		add_action('init',array($this,'engagifii_load_js_script'));
		add_action('init',array($this,'engagifii_load_css'));
		add_action('init', array( $this->engagifiiShortcode, 'init' ) );
		add_action('admin_init',array($this,'engagifii_adm_settings'));
		define( 'ENGAGIFII_ASSETS_URL', esc_url( plugins_url( '/assets', __FILE__ ) ) );
		define('_WORKSPACE_', get_bloginfo( 'name' ));
		add_action('wp_head',array($this,'engagifii_include_custom_css'));			
 	}

 	public function engagifii_include_custom_css(){
		include( plugin_dir_path( __FILE__ ) . '/engagifii_css.php');
	}

	public function engagifii_adm_settings()
	{
		register_setting('general', 'tenant_code', 'esc_attr');
		add_settings_field('tenant_code', '<label for="tenant_code">'.__('Tenant Code' , 'tenant_code' ).'</label>' , array($this,'engagifii_adm_settings_html'), 'general');
	}

	public function engagifii_adm_settings_html()
	{

		echo $value = get_option( 'tenant_code', '' );
		echo '<input type="text" id="tenant_code" name="tenant_code" value="' . $value . '" />';
	}

	/**
	* Include required core files used in the frontend 
	*/
	public function includes() {
		include_once('includes/public/model/class-engagifii-api.php');
		include_once( 'includes/sql/class-engagifii-install.php' );
		if ( is_admin() ) {
			include_once( 'includes/admin/admin-config-area.php' );	
			include_once( 'includes/admin/class-adm-data-col.php' );
		}		 
		$this->frontend_includes_ebt();
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes_ebt() {
		include_once('includes/public/model/class-engagifii-model.php');
		
		include_once('includes/public/model/class-engagifii-shortcodes.php');
		include_once('includes/public/views/blocks.php');
	}

	public function engagifii_load_js_script(){
		if(!is_admin()){			

			
			wp_enqueue_script( 'engagifii-jquery-classlocation', plugin_dir_url( __FILE__ ) . 'assets/js/classlocation.js', array('jquery'), $this->version, false );

			wp_enqueue_script( 'engagifii-tz-searchfilter',  plugin_dir_url( __FILE__ ) . 'assets/js/tzsearchfilter.js', array("jquery"), $this->version, false );
wp_enqueue_script( 
			'scrollbar', 
			'https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.concat.min.js', 
			array('jquery'), 
			'1.0.0', 
			false  
		);
			wp_enqueue_script( 'engagifii-select2jstz', plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', array("jquery"), $this->version, false );
			wp_enqueue_script( 'engagifii-jquerydatatable', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.dataTables.min.js', array("jquery"), $this->version, false );
			wp_enqueue_script( 'engagifii-datatable-bootstrap', plugin_dir_url( __FILE__ ) . 'assets/js/dataTables.bootstrap4.min.js', array('engagifii-jquerydatatable'), $this->version, false );
			wp_enqueue_script( 'dt-responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js', array('engagifii-jquerydatatable'), $this->version, false );
			wp_enqueue_script( 'dt-bs-responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js', array('engagifii-datatable-bootstrap'), $this->version, false );
			wp_enqueue_script( 'dt-fixedHeader', 'https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js', array('engagifii-datatable-bootstrap'), $this->version, false );
			//wp_enqueue_script( 'dt-checkbox', 'https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js', array('engagifii-datatable-bootstrap'), $this->version, false );
			
		//	wp_enqueue_script( 'dt-reorder', 'https://cdn.datatables.net/colreorder/1.6.1/js/dataTables.colReorder.min.js', array('engagifii-datatable-bootstrap'), $this->version, false );
			//wp_enqueue_script( 'dt-date', 'https://cdn.datatables.net/plug-ins/1.12.1/sorting/date-eu.js', array('engagifii-jquerydatatable'), $this->version, false );
			wp_enqueue_script( 'range-slider', 'https://code.jquery.com/ui/1.13.1/jquery-ui.js', array('jquery'), $this->version, false );
			wp_enqueue_script( 'custom-engagifii', plugin_dir_url( __FILE__ ) . 'assets/js/custom-engagifii.js', array('jquery','bootstrap'), '1.0.01', false );
			wp_register_script( 'moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js' ); 
			wp_enqueue_script('moment-js');

			wp_register_script( 'datepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array(), $this->version, false );
			wp_enqueue_script('datepicker-js');
			wp_localize_script( 'engagifii-jquerydatatable', 'ajax_url_ebt', admin_url('admin-ajax.php?action=endorsement') );
			wp_localize_script( 'engagifii-jquerydatatable', 'ajax_url_evt', admin_url('admin-ajax.php?action=events') );
			wp_localize_script( 'engagifii-jquerydatatable', 'ajax_url_lbt', admin_url('admin-ajax.php?action=legislation') );
			wp_localize_script( 'engagifii-jquerydatatable', 'engagifiiUrl_ajaxurl', admin_url('admin-ajax.php') );
			


			}else{			

			  wp_enqueue_style( 'wp-color-picker-ebt' );
              wp_enqueue_script( 'custom-admin-engagifii-js-ebt', plugin_dir_url( __FILE__ ) . 'assets/js/custom-admin-engagifii.js', array('wp-color-picker'), $this->version, true );
              wp_enqueue_script( 'sortable', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array('custom-admin-engagifii-js-ebt'), $this->version, true );
              wp_enqueue_style( 'engagifii-admin-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/engagifii-admin.css', array(), $this->version, 'all' );
			}
	}

	public function engagifii_load_css(){
		if(!is_admin()){
			 wp_enqueue_style( 'mcustomsrollbar', 'https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.min.css', array(  ), wp_get_theme()->get('Version')    );

			wp_enqueue_style( 'dt-bs', plugin_dir_url( __FILE__ ) . 'assets/css/dataTables.bootstrap4.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'dt-bs-responsive', 'https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css', array('dt-bs'), $this->version, 'all' );
			//wp_enqueue_style( 'dt-scheckbox', 'https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css', array('dt-bs'), $this->version, 'all' );
			wp_enqueue_style( 'sleect2css-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', array(), $this->version, 'all' );
			
			
			wp_deregister_style('font-awesome-5-all');
			wp_enqueue_style( 'fontawesome','https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css',array(),wp_get_theme()->get('Version')  );
			wp_register_style( 'datepicker-css', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css' );
			wp_enqueue_style('datepicker-css');
			wp_register_style( 'range-selector', 'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
			wp_enqueue_style('range-selector');

			$load_script = get_option('ebt_api_settings'); 
			if(isset($load_script['engagifii_apply_css_ebt'])){
    			$specific_script= $load_script['engagifii_apply_css_ebt'];
   			}else{
   			$specific_script = 0;
			}



				wp_enqueue_style( 'engagifii-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/engagifii.css', array(), $this->version, 'all' );
			
		}

	}


}
//create pages
define( 'PLUGIN_FILE_PATH', __FILE__ );
 
register_activation_hook( PLUGIN_FILE_PATH, 'insert_page_on_activation' );
 
function insert_page_on_activation() {
  if ( ! current_user_can( 'activate_plugins' ) ) return;
 
    $page1_slug = 'bill-tracking'; // Slug of the Post
    $page1 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Bill Tracking',    // Title of the Content
        //'post_content'  => 'Test Page Content',  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page1_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page1_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page1_id = wp_insert_post($page1);
    }
	
	$page2_slug = 'engagifii-detail'; // Slug of the Post
    $page2 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Bill Detail',    // Title of the Content
        //'post_content'  => 'Test Page Content',  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page2_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page2_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page2_id = wp_insert_post($page2);
    }
	
	$page3_slug = 'legislative-tracking-database'; // Slug of the Post
    $page3 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Legislative tracking database',    // Title of the Content
        //'post_content'  => 'Test Page Content',  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page3_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page3_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page3_id = wp_insert_post($page3);
    }
	
		$page4_slug = 'classes'; // Slug of the Post
		$page4 = array(
			'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
			'post_title'    => 'Classes',    // Title of the Content
			//'post_content'  => 'Test Page Content',  // Content
			'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page4_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page4_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page4_id = wp_insert_post($page4);
    }
	
	$page5_slug = 'class-details'; // Slug of the Post
    $page5 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Class Details',    // Title of the Content
        //'post_content'  => 'Test Page Content',  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page5_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page5_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page5_id = wp_insert_post($page5);
    }
	
	$page6_slug = 'courses'; // Slug of the Post
    $page6 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Courses',    // Title of the Content
        //'post_content'  => 'Test Page Content',  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page6_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page6_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page6_id = wp_insert_post($page6);
    }
	
	$page7_slug = 'course-details'; // Slug of the Post
    $page7 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Course Details',    // Title of the Content
        //'post_content'  => 'Test Page Content',  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page7_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page7_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page7_id = wp_insert_post($page7);
    }
	
	
}


/**
 * Main instance of EngagifiiAPI.
 * @since  1.0.0
 * @return EngagifiiAPIebt
 */

function initializeEngagifii() {
	return Engagifii::instance();
}

// Global for backwards compatibility.
$GLOBALS['engagifii'] = initializeEngagifii();