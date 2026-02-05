<?php
 /**
 * Plugin Name: Engagifii Module
 * Description: Engagifii API to fetch Bills, events, courses and classes
 * Plugin URI:  https://engagifii.com/
 * Author:      Engagifii
 * Author URI:  https://engagifii.com/
 * Version:     2.2.1
 * Text Domain: engagifii
 * Domain Path: /languages/
 * License:     GPLv3 or later (license.txt)
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

define('ENGAGIFII_VERSION','2.2.1'); 

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
//pages creation
function engagifii_get_default_pages() {

    return [
        'bill-tracking' => [
            'option_key'   => 'bills_page',
            'post_title'   => 'Bill Tracking',
            'post_content' => '[legislation-list]',
        ],
        'engagifii-detail' => [
            'option_key'   => 'bills_detail_page',
            'post_title'   => 'Bill Detail',
            'post_content' => "[legislation-details Id='bill-id']",
        ],
		'public-official' => [
            'option_key'   => 'public_official_page',
            'post_title'   => 'Public Officials',
            'post_content' => '[public-officials]',
        ],
        'public-official-detail' => [
            'option_key'   => 'public_official_detail_page',
            'post_title'   => 'Public Official Detail',
            'post_content' => "[public-officials-detail]",
        ],
        'classes' => [
            'option_key'   => 'classes_page',
            'post_title'   => 'Classes',
            'post_content' => '[classes-list-calendar-class-name calendarclassname=true]',
        ],
        'class-details' => [
            'option_key'   => 'classes_detail_page',
            'post_title'   => 'Class Details',
            'post_content' => "[class-details Id='class-id']",
        ],
        'courses' => [
            'option_key'   => 'courses_page',
            'post_title'   => 'Courses',
            'post_content' => '[courses-list]',
        ],
        'course-details' => [
            'option_key'   => 'courses_detail_page',
            'post_title'   => 'Course Details',
            'post_content' => "[course-details Id='course-id']",
        ],
		'events' => [
            'option_key'   => 'events_page',
            'post_title'   => 'Events',
            'post_content' => '[events-list-calendar calendar=true]',
        ],
        'event-detail' => [
            'option_key'   => 'events_detail_page',
            'post_title'   => 'Event Detail',
            'post_content' => "[events-details Id='event-id']",
        ],
		'events-classes' => [
            'option_key'   => 'events_classes_page',
            'post_title'   => 'Events Classes',
            'post_content' => "[training-calendar]",
        ],
    ];
}

add_action( 'admin_post_engagifii_create_default_page', 'engagifii_create_default_page' );
function engagifii_create_default_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }

    check_admin_referer( 'engagifii_create_default_page' );

    $slug  = sanitize_text_field( $_GET['slug'] ?? '' );
    $pages = engagifii_get_default_pages();

    if ( ! isset( $pages[ $slug ] ) ) {
        wp_redirect( wp_get_referer() );
        exit;
    }

    $page_def = $pages[ $slug ];

    // Prevent duplicates
    $page = get_page_by_path( $slug );
    if ( $page ) {
        $page_id = $page->ID;
    } else {
        $page_id = wp_insert_post( [
            'post_title'   => $page_def['post_title'],
            'post_name'    => $slug,
            'post_content' => $page_def['post_content'],
            'post_type'    => 'page',
            'post_status'  => 'publish',
        ] );
    }

    if ( ! is_wp_error( $page_id ) ) {
        $options = get_option( 'ebt_api_settings', [] );
        $options['front_pages'][ $page_def['option_key'] ] = $page_id;
        update_option( 'ebt_api_settings', $options );
    }

    wp_redirect( wp_get_referer() );
    exit;
}

add_filter( 'display_post_states', function ( $states, $post ) {

    if ( $post->post_type !== 'page' ) {
        return $states;
    }

    $options = get_option( 'ebt_api_settings', [] );
    $pages   = $options['front_pages'] ?? [];

    $labels = [
        'bills_page'         => 'Engagifii Bills',
        'bills_detail_page'  => 'Engagifii Bill Detail',
        'public_official_page'         => 'Engagifii Public Official',
        'public_official_detail_page'  => 'Engagifii Public Official Detail',
        'classes_page'       => 'Engagifii Classes',
        'classes_detail_page'       => 'Engagifii Class Detail',
        'courses_page'       => 'Engagifii Courses',
        'courses_detail_page'       => 'Engagifii Course Detail',
        'events_page'       => 'Engagifii Events',
        'events_classes_page'       => 'Engagifii Classes & Events',
        'events_detail_page'       => 'Engagifii Event Detail',
    ];

    foreach ( $labels as $key => $label ) {
        if ( isset( $pages[ $key ] ) && (int) $pages[ $key ] === (int) $post->ID ) {
            $states[] = $label;
            break;
        }
    }

    return $states;
}, 10, 2 );


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
