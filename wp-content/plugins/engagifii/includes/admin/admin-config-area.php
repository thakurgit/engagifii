<?php
/**
* Admin Settings
* Since v1.0.0
* Author: Engagifii
*/

class ebtAdminConfigSettings {
	
	protected $dbObj;

	public function __construct(){	 
	 
		global $wpdb;
		$this->dbObj=$wpdb;			
		$this->init_hooks();	 	
	}

	private function init_hooks() {		
	add_action('admin_menu',array($this,'ebt_api_add_admin_menu'));			
	add_action('admin_init',array($this,'ebt_api_settings_init'));
		add_action('engagifiiGetColumnList', array($this,'show_datatable_column'));		
		add_action('engagifiiCustomizer', array($this,'engagifii_Customizer'));		
 	}

 	function show_datatable_column()
 	{
		
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		include_once( __DIR__.'/view/endorsement/admin-column-list.php' );
    include_once( __DIR__.'/view/courses/admin-column-list.php' );
    include_once( __DIR__.'/view/classes/admin-column-list.php' );

	include_once( __DIR__.'/view/events/admin-column-list.php' );
		include_once( __DIR__.'/view/legislation/admin-column-list.php' );
 	}
	function engagifii_Customizer()
 	{
		
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		include_once( __DIR__.'/view/customizer.php' );
 	}

	function ebt_api_add_admin_menu() {
				add_menu_page( 'Engagifii', 'Engagifii', 'manage_options', 'engagifii-module-api', array($this,'engagifii_settings_api_view'),plugins_url('engagifii/assets/images/logo-icon.png'), 4 );
				$parent = site_url().'/wp-admin/admin.php?page=engagifii-module-api';
				add_submenu_page( 'engagifii-module-api', 'API settings', 'API settings', 'manage_options', $parent.'&tab=settings',  $callback = '');
				add_submenu_page( 'engagifii-module-api', 'Shortcodes', 'Shortcodes', 'manage_options', $parent.'&tab=shortcode',  $callback = '');
				add_submenu_page( 'engagifii-module-api', 'Customizer', 'Customizer', 'manage_options', $parent.'&tab=customizer',  $callback = '');
	}

	function ebt_api_settings_init(  ) {

		if ( ! current_user_can( 'manage_options' ) ) {
    		return;
  		}

  		register_setting('engagifiiPlugin', 'ebt_api_settings');
  		add_settings_section(
		'ebt_api_ebtPlugin_section',
		__( '<div class="center"> <img class="w-25" src="' . ENGAGIFII_ASSETS_URL . '/images/engagifii-logo.png' . '"></div><h1 class="center">  Engagifii Settings </h1>', 'engagifii-api' ),
		array($this,'ebt_api_settings_section_callback'),
		'engagifiiPlugin'
		);
	}



/* List Page */

function ebt_api_shortocde_description(){
	echo "<h3 class='mb-0 bg-grey bordered'>Endorsement Shortcodes </h3>";
	echo "<div class='engagifii-setting shortcode-list'>";
	echo "<ul class='list'><li><strong>Endorsement List</strong>: <code>[endorsement-grid-list]</code></li> <li><strong>Endorsement Detail</strong> <code>[endorsement-details Id='endorsement-id']</code></li> </ul>";
	echo "</div>";

	echo "<h3 class='mb-0 bg-grey bordered'>Event Shortcodes </h3>";
	echo "<div class='engagifii-setting shortcode-list'>";
	echo "<ul class='list'><li><strong>Event List</strong> <code>[event-list]</code></li> <li><strong>Event Detail</strong> <code>[events-details Id='event-id']</code></li><li><strong>Event Calendar</strong> <code>[events-calendar]</code></li><li><strong>Event List & Calendar</strong> <code>[events-list-calendar calendar=true]</code></li> </ul>";
	echo "</div>";

	echo "<h3 class='mb-0 bg-grey bordered'>Legislation Shortcodes </h3>";
	echo "<div class='engagifii-setting shortcode-list'>";
	echo "<ul class='list'><li><strong>Legislation List</strong> <code>[legislation-list]</code></li><li><strong>Legislation Details</strong> <code>[legislation-details Id='bill-id']</code></li><li><hr><h4> Filters Shortcode:</h4> </li><li><strong>Last Action Type</strong> <code>[legislation-lastaction]</code></li><li><strong>Tracking Levels</strong> <code>[legislation-tracking]</code></li><li><strong>Staff Members</strong> <code>[legislation-staffmember]</code></li><li><strong>Bill Tags</strong> <code>[legislation-tags]</code></li><li><strong>Search By Bill Number</strong> <code>[legislation-search-billnumber]</code></li><li><strong>Total bills count</strong> <code>[bill-count]</code></li></ul></div>";

  echo "<h3 class='mb-0 bg-grey bordered'>Courses Shortcodes </h3>";
  echo "<div class='engagifii-setting shortcode-list'>";
  echo "<ul class='list'><li><strong>Course List</strong> <code>[courses-list]</code></li><li><strong>Course Details</strong> <code>[course-details Id='course-id']</code></li></ul></div>";

   echo "<h3 class='mb-0 bg-grey bordered'>Class Shortcodes </h3>";
  echo "<div class='engagifii-setting shortcode-list'>";
  echo "<ul class='list'><li><strong>Class List</strong> <code>[classes-list]</code></li><li><strong>Class Details</strong> <code>[class-details Id='class-id']</code></li><li><strong>Class Calendar</strong> <code>[class-calendar]</code></li><li><strong>Class List & Calendar</strong> <code>[classes-list-calendar calendar=true]</code></li>
  <li><strong>Class List & Calendar (With Class Name)</strong> <code> [classes-list-calendar-class-name calendarclassname=true]</code></li> 
  <li> <strong>Class Calendar (With Class Name)</strong> <code> [class-calendar-class-name]</code></li> 
  </ul>";

	echo "<hr>";

	

	echo "<ul><li>Create a <a href='post-new.php?post_type=page' target='_blank'>page</a> or <a href='post-new.php' target='_blank'>Post</a> or use existing pages/posts</li><li>Place the shortcode where you want to display data</li></ul>";
	echo '</div>';
}

function ebt_api_url_setings(){

	
}

function engagifii_font_family_render(){
	$options = get_option( 'ebt_api_settings_s2' );
	include( plugin_dir_path( __FILE__ ) . '/admin-fonts.php');
	

	if ( isset( $options['engagifii_font_family'])){
        $current = $options['engagifii_font_family'];
	}
    else{
        $current = '';
    }
?>
	<select name="ebt_api_settings[engagifii_font_family]">
		<option> </option>
	   <?php foreach( $fonts as $key => $font ):?>

	     <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
	    <?php endforeach; ?>
	</select>


<?php    
}

function engagifii_apply_css_ebt_render($html_class){
	$options = get_option( 'ebt_api_settings' );
  if(isset($options['engagifii_apply_css_ebt'])){
    $engagifii_apply_css_ebt = $options['engagifii_apply_css_ebt'];
   }else{
   	$engagifii_apply_css_ebt = 0;
   }

    $checkedHtml = '';
    if($engagifii_apply_css_ebt==1)
    {
    	 $checkedHtml  = ' checked="checked"';
    }


    $_inputHtml = '<div class="m-tlr-20 bg-grey bordered '.$html_class.'"><input type="checkbox" id="engagii_custom_css" name="ebt_api_settings[engagifii_apply_css_ebt]" value="1" '.$checkedHtml.' >';
    $_inputHtml.= '<span> <strong>Enable Customize CSS</strong></span></div>';
    //echo $_inputHtml;
}


function ebt_api_url_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    if(isset($options['ebt_api_url']))
      $ebt_api_url = $options['ebt_api_url'];
    else
      $ebt_api_url ='';
    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_api_url]" class="postbox" value="'.$ebt_api_url.'">';
      echo $_inputHtml;
}

function ebt_tenant_code_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    $_tenantCode = '';
    $code = '';
    $engagifii_url = '';
    if(isset( $options['ebt_tenant_code']))
    {
      $_tenantCode = $options['ebt_tenant_code'];
      $code = $_tenantCode['tenant_code'];
      $engagifii_url = $_tenantCode['engagifii_url'];
    }
    

    $_inputHtmlHidden ='<input type="hidden" name="ebt_api_settings[ebt_tenant_code][tenant_code]" class="postbox" id="ebt_tenant_code_text" value="'.$code.'">';

    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_tenant_code][engagifii_url]" class="postbox" value="'.$engagifii_url.'" onkeydown="getTenantCode(this.value)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$code.'</span>';

      echo $_inputHtml.$_inputHtmlHidden;
}

 

	function ebt_api_settings_section_callback( ) {

  		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
    	?>
		<!-- Our admin page content should all be inside .wrap -->
  		<div class="wrap">
    		<!-- Here are our tabs -->
    		<nav class="nav-tab-wrapper wp-clearfix">
      			<a href="?page=engagifii-module-api" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Customize CSS</a>
      			<a href="?page=engagifii-module-api&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">API Settings</a>
      			<a href="?page=engagifii-module-api&tab=shortcode" class="nav-tab <?php if($tab==='shortcode'):?>nav-tab-active<?php endif; ?>">Shortcode Usage</a>
                <a href="?page=engagifii-module-api&tab=customizer" class="nav-tab <?php if($tab==='customizer'):?>nav-tab-active<?php endif; ?>">Customizer</a>
    		</nav>

    		<div class="tab-content">
    			<?php switch($tab) :
      			case 'settings':
      				$this->engagifii_api_settings('show');
      				$this->engagifii_apply_css_ebt_render('hide');
        		break;
      			case 'shortcode':
        			$this->ebt_api_shortocde_description();
        		break;
				case 'customizer':
        			$this->engagifii_api_settings('hide');
        			$this->engagifii_apply_css_ebt_render('hide');
        		break;
      			default:
      				$this->engagifii_api_settings('hide');
        			$this->engagifii_apply_css_ebt_render('show');

        		break;
    			endswitch; ?>
    		</div>
  		</div>
    <?php
	}

	function lbt_api_url_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    $lbt_api_url = '';
    if(isset($options['lbt_api_url']))
      $lbt_api_url = $options['lbt_api_url']; 
    $_inputHtml = '<input type="text"  class="postbox"  name="ebt_api_settings[lbt_api_url]" value="'.$lbt_api_url.'">';
      echo $_inputHtml;
	}

	//API url for Events
	function evt_api_url_render(  ) {
		$options = get_option( 'ebt_api_settings' );
		$evt_api_url = '';
		if(isset($options['evt_api_url']))
		  $evt_api_url = $options['evt_api_url']; 
		$_inputHtml = '<input type="text"  class="postbox"  name="ebt_api_settings[evt_api_url]" value="'.$evt_api_url.'">';
		  echo $_inputHtml;
		}
	//


	function engagifii_api_settings($html_class)
	{
		?>
			<div class="<?php echo $html_class; ?>">
			<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">API URLs <i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
            <div class="engagifii-setting api-urls accordion-content" style="display:none;">
            	<h4>Training & Accreditation API Settings</h4>
                <div class="form-group">
      					<label>API URL</label>
  						<?php $this->ebt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Engagifii URL</label>
  						<?php $this->ebt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Legislation API Settings</h4>
                    <div class="form-group">
      					<label>API URL</label>
  						<?php $this->lbt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Engagifii URL</label>
  						<?php $this->lbt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Events API Settings</h4>
                    <div class="form-group">
      					<label>API URL</label>
  						<?php $this->evt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Engagifii URL</label>
  						<?php $this->evt_tenant_code_render(); ?>
  					</div>
            </div>
            

  			</div>
		<?php
	}

	function lbt_tenant_code_render(  ) {
    	$options = get_option( 'ebt_api_settings' );
      $_tenantCode = '';
      $code = '';
      $engagifii_url = '';
      if(isset($options['lbt_tenant_code'])){
          $_tenantCode = $options['lbt_tenant_code'];
          $code = $_tenantCode['tenant_code'];
          $engagifii_url = $_tenantCode['engagifii_url'];
      }
    	
    	$_inputHtmlHidden ='<input type="hidden"  class="postbox"  name="ebt_api_settings[lbt_tenant_code][tenant_code]" id="lbt_tenant_code_text" value="'.$code.'">';
    	$_inputHtml = '<input type="text"  class="postbox"  name="ebt_api_settings[lbt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" onkeydown="getTenantCode_lbt(this.value)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="lbt_tenantcode_preview">'.$code.'</span>';
      	echo $_inputHtml.$_inputHtmlHidden;
	}


	//Events tenant code
	function evt_tenant_code_render(  ) {
    	$options = get_option( 'ebt_api_settings' );
      $_tenantCode = '';
      $code = '';
      $engagifii_url = '';
      if(isset($options['evt_tenant_code'])){
          $_tenantCode = $options['evt_tenant_code'];
          $code = $_tenantCode['tenant_code'];
          $engagifii_url = $_tenantCode['engagifii_url'];
      }
    	
    	$_inputHtmlHidden ='<input type="hidden"  class="postbox"  name="ebt_api_settings[evt_tenant_code][tenant_code]" id="evt_tenant_code_text" value="'.$code.'">';
    	$_inputHtml = '<input type="text"  class="postbox"  name="ebt_api_settings[evt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" onkeydown="getTenantCode_evt(this.value)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="evt_tenantcode_preview">'.$code.'</span>';
      	echo $_inputHtml.$_inputHtmlHidden;
	}

	//



	function engagifii_settings_api_view(  ) {	
		if ( ! current_user_can( 'manage_options' ) ) {
    		return;
  		}
		include_once( __DIR__.'/view/admin-settings-form.php' );
		//if($tab === 'customizer'){
		//include_once( __DIR__.'/view/customizer.php' );
		//}
	 
	}
}

new ebtAdminConfigSettings();
