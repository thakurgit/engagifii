<?php
 /**
 * Plugin Name: Engagifii Module
 * Description: Engagifii API to fetch Bills, events, courses and classes
 * Plugin URI:  https://engagifii.com/
 * Author:      Engagifii
 * Author URI:  https://engagifii.com/
 * Version:     1.6.2
 * Text Domain: engagifii
 * Domain Path: /languages/
 * License:     GPLv3 or later (license.txt)
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

define('ENGAGIFII_VERSION','1.6.2');

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
	public function dequeue_unnecessary_styles() {
			wp_deregister_style('tommusrhodus-style');
			wp_dequeue_style('tommusrhodus-style');
}
	private function init_hooks() {
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		register_uninstall_hook( __FILE__, array( 'Engagifii_Install','uninstall'));
		//add_action( 'wp_print_styles', array($this,'dequeue_unnecessary_styles'));
		add_action('init',array($this,'engagifii_load_js_script'));
		add_action('init',array($this,'includes_variable'));
		add_action('wp_enqueue_scripts',array($this,'engagifii_load_css'),9999);
		add_action('init', array( $this->engagifiiShortcode, 'init' ) );
		add_action('admin_init',array($this,'engagifii_adm_settings'));
		add_action('admin_init', array($this,'redirect_to_settings'));
		define( 'ENGAGIFII_ASSETS_URL', esc_url( plugins_url( '/assets', __FILE__ ) ) );
		define('_WORKSPACE_', get_bloginfo( 'name' ));
		add_action('wp_head',array($this,'engagifii_include_custom_css'));	
		add_action ( 'admin_enqueue_scripts', function () {
		if (is_admin ())
				wp_enqueue_media ();
		} );		
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
			include_once( 'includes/admin/class-engagifii-settings.php' );
		}		 
		$this->frontend_includes_ebt();
		include_once('includes/functions.php');
		require_once ('includes/updater.php');
		
		// Initialize settings class
		if (is_admin()) {
			new Engagifii_Settings();
		}
	}
	/**
	* Include required core files used in the frontend after wp init
	*/
	public function includes_variable() {
		include_once('includes/variable.php');
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes_ebt() {
		include_once('includes/public/model/class-engagifii-model.php');
		include_once('includes/public/model/class-engagifii-model-v2.php');
		include_once('includes/public/model/class-engagifii-shortcodes.php');
		include_once('includes/public/views/blocks.php');
	}

	public function engagifii_load_js_script(){
		if(!is_admin()){	
			$options = get_option( 'ebt_api_settings' );
			if(isset($options['include_bootstrap'])){
				wp_enqueue_script( 'bootstrap-egf', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', array('jquery'), $this->version, false );
			}
			wp_enqueue_script( 'engagifii',  plugin_dir_url( __FILE__ ) . 'assets/js/engagifii.js', array("jquery"), $this->version, false );
wp_enqueue_script( 
			'scrollbar', 
			'https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.concat.min.js', 
			array('jquery'), 
			'1.0.0', 
			false  
		);
			wp_enqueue_script( 'dt-responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js', array('engagifii'), $this->version, false );
			wp_enqueue_script( 'dt-bs-responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js', array('engagifii'), $this->version, false );
			wp_enqueue_script( 'range-slider', 'https://code.jquery.com/ui/1.13.1/jquery-ui.js', array('jquery'), $this->version, false );
			//wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBhzZaLGPYVrMO2zIyP0xkGy8WxnnUmPlc&libraries=places', array('jquery'), $this->version, false );
			wp_enqueue_script( 'custom-engagifii', plugin_dir_url( __FILE__ ) . 'assets/js/custom-engagifii.js', array('jquery','engagifii'), '1.0.01', false );
			wp_register_script( 'moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js' ); 
			wp_enqueue_script('moment-js');

			wp_register_script( 'datepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array(), $this->version, false );
			wp_enqueue_script('datepicker-js');
			wp_localize_script( 'engagifii', 'ajax_url_ebt', admin_url('admin-ajax.php?action=endorsement') );
			wp_localize_script( 'engagifii', 'ajax_url_evt', admin_url('admin-ajax.php?action=events') );
			wp_localize_script( 'engagifii', 'ajax_url_lbt', admin_url('admin-ajax.php?action=legislation') );
			wp_localize_script( 'engagifii', 'engagifiiUrl_ajaxurl', admin_url('admin-ajax.php') );
			


			}else{			

			  wp_enqueue_style( 'wp-color-picker-ebt' );
              wp_enqueue_script( 'custom-admin-engagifii-js-ebt', plugin_dir_url( __FILE__ ) . 'assets/js/custom-admin-engagifii.js', array('wp-color-picker'), $this->version, true );
              wp_enqueue_script( 'sortable', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array('custom-admin-engagifii-js-ebt'), $this->version, true );
              wp_enqueue_style( 'engagifii-admin-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/engagifii-admin.css', array(), $this->version, 'all' );
			wp_localize_script('custom-admin-engagifii-js-ebt', 'engagifiiAjax', [
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('save_cols_nonce')
			]);
			}
	}
	
	public function engagifii_load_css(){
		
		if(!is_admin()){
			$options = get_option( 'ebt_api_settings' );
			if(isset($options['include_bootstrap'])){
			 wp_enqueue_style( 'bootstrap-egf', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(  ), wp_get_theme()->get('Version')    );
			}
			 wp_enqueue_style( 'mcustomsrollbar', 'https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.min.css', array(  ), wp_get_theme()->get('Version')    );

			wp_enqueue_style( 'engagifii', plugin_dir_url( __FILE__ ) . 'assets/css/engagifii_merge.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'dt-bs-responsive', 'https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css', array(), $this->version, 'all' );
			$include_fontawesome = isset($options['include_fontawesome']) ? $options['include_fontawesome'] : null;
			if (!is_array($include_fontawesome)) {
				$include_fontawesome = ['enabled' => $include_fontawesome, 'version' => '5.15.4'];
			}
			$enabled = isset($include_fontawesome['enabled']) ? $include_fontawesome['enabled'] : 1;
			$version = isset($include_fontawesome['version']) ? $include_fontawesome['version'] : '5.15.4';
			if ($enabled == 1 || !isset($options['include_fontawesome'])) {
				$fa_url = "https://kit-pro.fontawesome.com/releases/v{$version}/css/pro.min.css";
				wp_enqueue_style('ef-fontawesome', $fa_url, [], $version);
			}
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

	/**
	 * Plugin activation handler
	 */
	public function plugin_activation() {
		// Create database tables first
		Engagifii_Install::install();
		
		// Set activation flag to redirect to settings
		set_transient('engagifii_activation_redirect', true, 30);
	}

	/**
	 * Redirect to settings page after activation
	 */
	public function redirect_to_settings() {
		// Only redirect on first activation
		if (get_transient('engagifii_activation_redirect')) {
			delete_transient('engagifii_activation_redirect');
			
			// Check if setup is not completed yet
			$setup_completed = get_option('engagifii_setup_completed', false);
			
			if (!$setup_completed && !isset($_GET['activate-multi'])) {
				wp_redirect(admin_url('admin.php?page=engagifii-settings&setup=1'));
				exit;
			}
		}
	}


}
//create pages
define( 'PLUGIN_FILE_PATH', __FILE__ );
//register_activation_hook( PLUGIN_FILE_PATH, 'insert_page_on_activation' );
 
function insert_page_on_activation() {
  if ( ! current_user_can( 'activate_plugins' ) ) return;
 
    $pages = [
        'bill-tracking' => [
            'post_title'   => 'Bill Tracking',
            'post_content' => '[legislation-list]',
        ],
        'engagifii-detail' => [
            'post_title'   => 'Bill Detail',
            'post_content' => "[legislation-details Id='bill-id']",
        ],
        'legislative-tracking-database' => [
            'post_title'   => 'Legislative tracking database',
            'post_content' => '', // optional
        ],
        'classes' => [
            'post_title'   => 'Classes',
            'post_content' => '[classes-list-calendar-class-name calendarclassname=true]',
        ],
        'class-details' => [
            'post_title'   => 'Class Details',
            'post_content' => "[class-details Id='class-id']",
        ],
        'courses' => [
            'post_title'   => 'Courses',
            'post_content' => '[courses-list]',
        ],
        'course-details' => [
            'post_title'   => 'Course Details',
            'post_content' => "[course-details Id='course-id']",
        ],
    ];

    foreach ( $pages as $slug => $data ) {
        if ( ! get_page_by_path( $slug, OBJECT, 'page' ) ) {
            $page = [
                'post_type'    => 'page',
                'post_name'    => $slug,
                'post_title'   => $data['post_title'],
                'post_content' => $data['post_content'],
                'post_status'  => 'publish',
                'post_author'  => 1,
            ];
            wp_insert_post( $page );
        }
    }
/* $page1_slug = 'bill-tracking'; // Slug of the Post
    $page1 = array(
        'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'Bill Tracking',    // Title of the Content
        'post_content'  => '[legislation-list]',  // Content
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
		'post_content'  => "[legislation-details Id='bill-id']",  // Content
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
			'post_content'  => '[classes-list-calendar-class-name calendarclassname=true]',  // Content
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
		'post_content'  => "[class-details Id='class-id']",  // Content
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
        'post_content'  => '[courses-list]',  // Content
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
        'post_content'  => "[course-details Id='course-id']",  // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page7_slug            // Slug of the Post
    );
    if (!get_page_by_path( $page7_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $page7_id = wp_insert_post($page7);
    }
	// Parent page data
	$parent_page_slug = 'my-profile'; // Slug of the parent page
	$parent_page = array(
		'post_type'     => 'page',
		'post_title'    => 'My Profile',
		'post_content'  => '[engagifii-profile]',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_name'     => $parent_page_slug
	);
	
	// Check if parent page exists, if not, create it
	if (!get_page_by_path($parent_page_slug, OBJECT, 'page')) {
		$parent_page_id = wp_insert_post($parent_page);
	} else {
		$parent_page_id = get_page_by_path($parent_page_slug)->ID;
	}
	
	// Child page data
	$child_pages_data = array(
		array(
			'slug' => 'edit',
			'title' => 'Engagifii Profile Edit',
			'content' => '[engagifii-profile-edit]'
		),
		array(
			'slug' => 'events',
			'title' => 'Events',
			'content' => '[engagifii-myEvents]'
		),
		array(
			'slug' => 'welcome-to-dashboard',
			'title' => 'Welcome to Dashboard',
			'content' => 'Welcome to My Profile Dashboard'
		),
		array(
			'slug' => 'my-transcript',
			'title' => 'My Transcript',
			'content' => '[engagifii-myTranscript]'
		),
		array(
			'slug' => 'members',
			'title' => 'Members',
			'content' => '[engagifii-members]'
		)
	);*/
	
	// Loop through child pages data to add each child page
	// Function to check if a page with a given slug exists under a given parent page
/*function is_page_unique($slug, $parent_id) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_parent = %d AND post_type = 'page'", $slug, $parent_id);
    $result = $wpdb->get_var($query);
    return $result ? true : false;
}

foreach ($child_pages_data as $child_data) {
    $child_page_slug = $child_data['slug'];
    $child_page_title = $child_data['title'];
    $child_page_content = $child_data['content'];
    
    $child_page = array(
        'post_type'     => 'page',
        'post_title'    => $child_page_title,
        'post_content'  => $child_page_content,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_name'     => $child_page_slug,
        'post_parent'   => $parent_page_id // Set parent page ID here
    );

    // Check if parent page exists
    $parent_page = get_post($parent_page_id);
    if (!$parent_page || $parent_page->post_type !== 'page') {
        continue; // Skip this child page creation if parent page doesn't exist or is not a page
    }

    // Check if child page exists, if not, create it
    if (!is_page_unique($child_page_slug, $parent_page_id)) {
        $child_page_id = wp_insert_post($child_page);
        
        // If this child page has further child pages
        if ($child_page_slug == 'events' || $child_page_slug == 'my-transcript') {
            // Adding child pages of 'events' and 'my-transcript'
            $child_page_child_pages_data = array();
            if ($child_page_slug == 'events') {
                $child_page_child_pages_data = array(
                    array(
                        'slug' => 'event-detail',
                        'title' => 'Event Detail',
                        'content' => '[engagifii-myEvents-detail]'
                    )
                );
            } elseif ($child_page_slug == 'my-transcript') {
                $child_page_child_pages_data = array(
                    array(
                        'slug' => 'class-detail',
                        'title' => 'Class Detail',
                        'content' => '[engagifii-myTranscript-class-detail]'
                    ),
                    array(
                        'slug' => 'course-details',
                        'title' => 'Course Detail',
                        'content' => '[engagifii-myTranscript-detail]'
                    ),
                    array(
                        'slug' => 'downloads',
                        'title' => 'My Downloads',
                        'content' => '[engagifii-myDownloads]'
                    )
                );
            }
            
            foreach ($child_page_child_pages_data as $child_page_child_data) {
                $child_page_child_slug = $child_page_child_data['slug'];
                $child_page_child_title = $child_page_child_data['title'];
                $child_page_child_content = $child_page_child_data['content'];
                
                $child_page_child = array(
                    'post_type'     => 'page',
                    'post_title'    => $child_page_child_title,
                    'post_content'  => $child_page_child_content,
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_name'     => $child_page_child_slug,
                    'post_parent'   => $child_page_id // Set parent page ID here
                );
                
                // Check if parent page of subchild exists
                if (!is_page_unique($child_page_child_slug, $child_page_id)) {
                    wp_insert_post($child_page_child);
                }
            }
        }
    }
}*/
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



