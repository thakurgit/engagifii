<?php
/**
* Admin Settings
* Since v1.0.0
* Author: Engagifii
*/

class ebtAdminConfigSettings 
{
	
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
 	}

 	function show_datatable_column()
 	{
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		include_once( __DIR__.'/view/endorsement/admin-column-list.php' );
		include_once( __DIR__.'/view/legislation/admin-column-list.php' );
    include_once( __DIR__.'/view/courses/admin-column-list.php' );
    include_once( __DIR__.'/view/classes/admin-column-list.php' );

	include_once( __DIR__.'/view/events/admin-column-list.php' );
		
 	}

	function ebt_api_add_admin_menu() {
				add_menu_page( 'Engagifii', 'Engagifii', 'manage_options', 'engagifii-module-api', array($this,'engagifii_settings_api_view'),plugins_url('engagifii/assets/images/logo-icon.png'), 4 );
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
	echo "<h4 class='m-tlr-20 bg-grey bordered'>Endorsement Shortcodes </h4>";
	echo "<div class='engagifii-setting'>";
	echo "<ul class='list'><li>Endorsement List <strong>[endorsement-grid-list]</strong></li> <li>Endorsement Detail <strong>[endorsement-details Id='endorsement-id']</strong></li><li>Endorsement Calendar <strong>[endorsement-calendar]</strong></li><li>Endorsement List & Calendar <strong>[endorsement-list-calendar calendar=true]</strong></li> </ul>";
	echo "</div>";

	echo "<h4 class='m-tlr-20 bg-grey bordered'>Event Shortcodes </h4>";
	echo "<div class='engagifii-setting'>";
	echo "<ul class='list'><li>Event List <strong>[event-list]</strong></li> <li>Event Detail <strong>[event-details Id='event-id']</strong></li><li>Event Calendar <strong>[events-calendar]</strong></li><li>Event List & Calendar <strong>[events-list-calendar calendar=true]</strong></li> </ul>";
	echo "</div>";

	echo "<h4 class='m-tlr-20 bg-grey bordered'>Legislation Shortcodes </h4>";
	echo "<div class='engagifii-setting'>";
	echo "<ul class='list'><li>Legislation List <strong>[legislation-list]</strong></li><li>Legislation Details <strong>[legislation-details Id='bill-id']</strong></li><li><strong> Filters Shortcode:</strong> </li><li>Last Action Type <strong>[legislation-lastaction]</strong></li><li>Tracking Levels <strong>[legislation-tracking]</strong></li><li>Staff Members <strong>[legislation-staffmember]</strong></li><li>Bill Tags <strong>[legislation-tags]</strong></li><li>Search By Bill Number <strong>[legislation-search-billnumber]</strong></li><li>Total bills count <strong>[bill-count]</strong></li></ul></div>";

  echo "<h4 class='m-tlr-20 bg-grey bordered'>Courses Shortcodes </h4>";
  echo "<div class='engagifii-setting'>";
  echo "<ul class='list'><li>Course List <strong>[courses-list]</strong></li><li>Course Details <strong>[course-details Id='course-id']</strong></li></ul></div>";

   echo "<h4 class='m-tlr-20 bg-grey bordered'>Class Shortcodes </h4>";
  echo "<div class='engagifii-setting'>";
  echo "<ul class='list'><li>Class List <strong>[classes-list]</strong></li><li>Class Details <strong>[class-details Id='class-id']</strong></li><li>Class Calendar <strong>[class-calendar]</strong></li><li>Class List & Calendar <strong>[classes-list-calendar calendar=true]</strong></li>
  <li>Class List & Calendar (With Class Name)<strong> [classes-list-calendar-class-name calendarclassname=true]</strong></li> 
  <li>Class Calendar (With Class Name)<strong> [class-calendar-class-name]</strong></li> 
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
    echo $_inputHtml;
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

    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_tenant_code][engagifii_url]" class="postbox" value="'.$engagifii_url.'" onkeyup="getTenantCode(this.value)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$code.'</span>';

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
			<h3 class="m-tlr-20 bg-grey bordered d-flex justify-content-between accordion-btn">Training & Accreditation API Settings <i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
  				<div class="engagifii-setting accordion-content" style="display:none;">
  					<div class="form-group">
      					<label>API URL</label>
  						<?php $this->ebt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Engagifii URL</label>
  						<?php $this->ebt_tenant_code_render(); ?>
  					</div>
  				</div>
  				<h3 class="m-tlr-20 bg-grey bordered d-flex justify-content-between accordion-btn">Legislation API Settings</h3>
  				<div class="engagifii-setting accordion-content">
  					<div class="form-group">
      					<label>API URL</label>
  						<?php $this->lbt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Engagifii URL</label>
  						<?php $this->lbt_tenant_code_render(); ?>
  					</div>
  				</div>

				  <h3 class="m-tlr-20 bg-grey bordered">Events API Settings</h3>
  				<div class="engagifii-setting">
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
    	$_inputHtml = '<input type="text"  class="postbox"  name="ebt_api_settings[lbt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" onkeyup="getTenantCode_lbt(this.value)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="lbt_tenantcode_preview">'.$code.'</span>';
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
    	$_inputHtml = '<input type="text"  class="postbox"  name="ebt_api_settings[evt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" onkeyup="getTenantCode_evt(this.value)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="evt_tenantcode_preview">'.$code.'</span>';
      	echo $_inputHtml.$_inputHtmlHidden;
	}

	//



	function engagifii_settings_api_view(  ) {	
		if ( ! current_user_can( 'manage_options' ) ) {
    		return;
  		}

		include_once( __DIR__.'/view/admin-settings-form.php' );	
	 
	}
}

new ebtAdminConfigSettings();
?>
<script>
jQuery('.accordion-btn').click(function(){
	jQuery(this).next('.accordion-content').slideToggle();
});
</script>