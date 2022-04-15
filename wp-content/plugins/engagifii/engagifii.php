<?php
/**
 * Plugin Name: Engagifii Module
 * Description: Engagifii API to fetch blling details, courses and classes
 * Version: 1.0.0
 * Author: Engagifii
 * Requires at least: 4.4
 * Tested up to: 5.3
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
	}

	public function engagifii_load_js_script(){
		if(!is_admin()){			

			//wp_enqueue_script( 'calendar_theme-scrollbar', plugin_dir_url( __FILE__ )  . 'assets/js/scrollbar.js', array(), $this->version, 'all' );
			
			wp_enqueue_script( 'engagifii-jquery-main', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-2.1.0.min.js', array(), $this->version, false );
			wp_enqueue_script( 'engagifii-jquery-classlocation', plugin_dir_url( __FILE__ ) . 'assets/js/classlocation.js', array(), $this->version, false );

			wp_enqueue_script( 'engagifii-tz-searchfilter',  plugin_dir_url( __FILE__ ) . 'assets/js/tzsearchfilter.js', array("engagifii-jquery-main"), $this->version, false );
			wp_enqueue_script( 'engagifii-popper-js', plugin_dir_url( __FILE__ ) . 'assets/js/popper.min.js', array(), $this->version, false );	
			wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );

			wp_enqueue_script( 'engagifii-jquerydatatable', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.dataTables.min.js', array("engagifii-jquery-main"), $this->version, false );
			wp_enqueue_script( 'engagifii-select2jstz', plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', array("engagifii-jquery-main"), $this->version, false );
			wp_enqueue_script( 'engagifii-datatable-bootstrap', plugin_dir_url( __FILE__ ) . 'assets/js/dataTables.bootstrap4.min.js', array(), $this->version, false );
			
			//wp_enqueue_script( 'font-awesome-js-ebt', plugin_dir_url( __FILE__ ) . 'assets/js/all.js', array(), $this->version, false );
			
			wp_localize_script( 'engagifii-jquerydatatable', 'ajax_url_ebt', admin_url('admin-ajax.php?action=endorsement') );
			wp_localize_script( 'engagifii-jquerydatatable', 'ajax_url_evt', admin_url('admin-ajax.php?action=events') );
			wp_localize_script( 'engagifii-jquerydatatable', 'ajax_url_lbt', admin_url('admin-ajax.php?action=legislation') );
			wp_localize_script( 'engagifii-jquerydatatable', 'engagifiiUrl_ajaxurl', admin_url('admin-ajax.php') );
			wp_enqueue_script( 'custom-engagifii-js-ebt', plugin_dir_url( __FILE__ ) . 'assets/js/custom-engagifii.js', array(), '1.0.01', true );
			
			wp_register_script( 'bootstrap-slider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js' );
			wp_enqueue_script('bootstrap-slider');

			//wp_register_script( 'mcustom-scrollbar', 'https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.concat.min.js' );
			//wp_enqueue_script('mcustom-scrollbar');

			wp_register_script( 'moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js' ); 
			wp_enqueue_script('moment-js');

			wp_register_script( 'datepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js' );
			wp_enqueue_script('datepicker-js');

			}else{

			//wp_enqueue_script( 'calendarr_theme-scrollbar', plugin_dir_url( __FILE__ )  . 'assets/js/scrollbar.js', array(), $this->version, 'all' );
			

			  wp_enqueue_style( 'wp-color-picker-ebt' );
              wp_enqueue_script( 'custom-admin-engagifii-js-ebt', plugin_dir_url( __FILE__ ) . 'assets/js/custom-admin-engagifii.js', array('wp-color-picker'), $this->version, true );
              wp_enqueue_style( 'engagifii-admin-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/engagifii-admin.css', array(), $this->version, 'all' );
			}
	}

	public function engagifii_load_css(){
		if(!is_admin()){

//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js
//wp_register_style( 'main-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );
			//wp_enqueue_style( 'calendar_theme-scrollbar-style', plugin_dir_url( __FILE__ )  .'assets/css/scrollbar.css', array(), $this->version, 'all' );


			wp_register_style( 'twitter-bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css' );
			wp_enqueue_style('twitter-bootstrap');
			


			wp_register_style( 'slider-bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css' );
			wp_enqueue_style('slider-bootstrap');
			wp_enqueue_style( 'engagifiidatatablecss-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/dataTables.bootstrap4.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'sleect2css-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', array(), $this->version, 'all' );
			
			//wp_enqueue_style( 'fa-css-ebt', plugin_dir_url( __FILE__ ) . 'assets/css/font-awesome.min.css', array(), $this->version, 'all' );
			wp_register_style( 'fa-css', 'https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css' );
			wp_enqueue_style('fa-css');
			wp_register_style( 'datepicker-css', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css' );
			wp_enqueue_style('datepicker-css');
			//wp_register_style( 'mcustomscrollbar-css', 'https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.min.css' );
			//wp_enqueue_style('mcustomscrollbar-css');

			wp_register_style( 'range-selector', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
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