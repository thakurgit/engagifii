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
 	add_action('profileSettings', array($this,'profile_Settings'));	
	}

 	// function show_datatable_column()
 	// {
		
 	// 	$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
	// 	include_once( __DIR__.'/view/endorsement/admin-column-list.php' );
    // include_once( __DIR__.'/view/courses/admin-column-list.php' );
    // include_once( __DIR__.'/view/classes/admin-column-list.php' );

	// include_once( __DIR__.'/view/events/admin-column-list.php' );
	// 	include_once( __DIR__.'/view/legislation/admin-column-list.php' );
 	// }
     function show_datatable_column() {
        $tab = $_GET['tab'] ?? null;
        /*$views = [
          'endorsement/admin-column-list.php',
          'courses/admin-column-list.php',
          'classes/admin-column-list.php',
          'events/admin-column-list.php',
          'legislation/admin-column-list.php'
        ];*/
        $enabledModules = get_option('engagifii_modules');
		if(!$enabledModules || in_array('training-and-accreditation',$enabledModules)){
			$views[] = 'endorsement/admin-column-list.php';
			$views[] = 'courses/admin-column-list.php';
			$views[] = 'classes/admin-column-list.php';
		}
		if(!$enabledModules || in_array('events',$enabledModules)){
			$views[] = 'events/admin-column-list.php';
		}
		if(!$enabledModules || in_array('legislation',$enabledModules)){
			$views[] = 'legislation/admin-column-list.php';
		}
      	if (isset($views)) {
		  foreach ($views as $view) {
			include_once(__DIR__ . '/view/' . $view);
		  }
		}
      }
      
	function engagifii_Customizer()
 	{
		
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		include_once( __DIR__.'/view/customizer.php' );
 	}
	function profile_Settings()
 	{
		
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		$enabledModules = get_option('engagifii_modules');
		if(!$enabledModules || in_array('dashboard',$enabledModules)){
		  include_once( __DIR__.'/view/dashboard-settings.php' );
		}
 	}
	function ebt_api_add_admin_menu() {
	//$options = get_option( 'ebt_api_settings' );
	//$tenant_url= $options['ebt_tenant_code']['engagifii_url'];
				add_menu_page( 'Engagifii', 'Engagifii', 'manage_options', 'engagifii-module-api', array($this,'engagifii_settings_api_view'),plugins_url('engagifii/assets/images/logo-icon.png'), 4 );
				$parent = site_url().'/wp-admin/admin.php?page=engagifii-module-api';
				add_submenu_page( 'engagifii-module-api', 'API settings', 'API settings', 'manage_options', $parent.'&tab=settings',  $callback = '');
				add_submenu_page( 'engagifii-module-api', 'Shortcodes', 'Shortcodes', 'manage_options', $parent.'&tab=shortcode',  $callback = '');
				add_submenu_page( 'engagifii-module-api', 'Page Settings', 'Page Settings', 'manage_options', $parent.'&tab=page-settings',  $callback = '');
					add_submenu_page( 'engagifii-module-api', 'Profile Settings', 'Profile Settings', 'manage_options', $parent.'&tab=dashboard-settings',  $callback = '');
					//add_submenu_page( 'engagifii-module-api', 'Modules Settings', 'Module Settings', 'manage_options', 'engagifii-module-settings',  array($this,'engagifii_modules'));
	}
	
    function ebt_api_settings_init() {
        if (!current_user_can('manage_options')) {
          return;
        }
      
        register_setting('engagifiiPlugin', 'ebt_api_settings');
        register_setting('engagifiiModules', 'engagifii_modules');
      
        add_settings_section(
          'ebt_api_ebtPlugin_section',
          sprintf('<div class="center"> <img class="w-25" src="%s/images/engagifii-logo.png"></div><h1 class="center"> Engagifii Settings </h1>', ENGAGIFII_ASSETS_URL),
          array($this, 'ebt_api_settings_section_callback'),
          'engagifiiPlugin'
        );
      }
      



/* List Page */

function ebt_api_shortocde_description() {
    $shortcodes = array(
        array(
            'title' => 'Class Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Class List',
                    'shortcode'   => '[classes-list]'
                ),
                array(
                    'name'        => 'Class Details',
                    'shortcode'   => '[class-details Id=\'class-id\']'
                ),
                array(
                    'name'        => 'Class Calendar',
                    'shortcode'   => '[class-calendar]'
                ),
                array(
                    'name'        => 'Class List & Calendar',
                    'shortcode'   => '[classes-list-calendar calendar=true]'
                ),
                array(
                    'name'        => 'Class List & Calendar (With Class Name)',
                    'shortcode'   => '[classes-list-calendar-class-name calendarclassname=true]'
                ),
                array(
                    'name'        => 'Class Calendar (With Class Name)',
                    'shortcode'   => '[class-calendar-class-name]'
                )
            )
        ),
        array(
            'title' => 'Legislation Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Legislation List',
                    'shortcode'   => '[legislation-list]'
                ),
                array(
                    'name'        => 'Legislation Details',
                    'shortcode'   => '[legislation-details Id=\'bill-id\']'
                ),
                array(
                    'name'        => 'Last Action Type',
                    'shortcode'   => '[legislation-lastaction]'
                ),
                array(
                    'name'        => 'Tracking Levels',
                    'shortcode'   => '[legislation-tracking]'
                ),
                array(
                    'name'        => 'Staff Members',
                    'shortcode'   => '[legislation-staffmember]'
                ),
                array(
                    'name'        => 'Bill Tags',
                    'shortcode'   => '[legislation-tags]'
                ),
                array(
                    'name'        => 'Search By Bill Number',
                    'shortcode'   => '[legislation-search-billnumber]'
                ),
                array(
                    'name'        => 'Total bills count',
                    'shortcode'   => '[bill-count]'
                ),
                array(
                    'name'        => 'Legislative Reports',
                    'shortcode'   => '[legislative-reports]'
                )
            )
        ),
        array(
            'title' => 'Event Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Event List',
                    'shortcode'   => '[event-list]'
                ),
                array(
                    'name'        => 'Event Detail',
                    'shortcode'   => '[events-details Id=\'event-id\']'
                ),
                array(
                    'name'        => 'Event Calendar',
                    'shortcode'   => '[events-calendar]'
                ),
                array(
                    'name'        => 'Event List & Calendar',
                    'shortcode'   => '[events-list-calendar calendar=true]'
                )
            )
        ),
		array(
            'title' => 'Public Official Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Public Official List',
                    'shortcode'   => '[public-officials]'
                ),
                array(
                    'name'        => 'Public Official Details',
                    'shortcode'   => '[public-officials-detail]'
                ),
            )
        ),
		array(
            'title' => 'My Engagifii Dashboard',
            'list'  => array(
                array(
                    'name'        => 'My Profile',
                    'shortcode'   => '[engagifii-profile]'
                ),
                array(
                    'name'        => 'My profile edit',
                    'shortcode'   => '[engagifii-profile-edit]'
                ),
                array(
                    'name'        => 'My Events',
                    'shortcode'   => '[engagifii-myEvents]'
                ),
                array(
                    'name'        => 'My Transcript',
                    'shortcode'   => '[engagifii-myTranscript]'
                ),
                array(
                    'name'        => 'My Transactions',
                    'shortcode'   => '[engagifii-myTransactions]'
                ),
            )
        ),
        array(
            'title' => 'Courses Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Course List',
                    'shortcode'   => '[courses-list]'
                ),
                array(
                    'name'        => 'Course Details',
                    'shortcode'   => '[course-details Id=\'course-id\']'
                ),
            )
        ),
        array(
            'title' => 'Endorsement Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Endorsement List',
                    'shortcode'   => '[endorsement-grid-list]'
                ),
                array(
                    'name'        => 'Endorsement Detail',
                    'shortcode'   => '[endorsement-details Id=\'endorsement-id\']'
                )
            )
        )
    );

    echo '<div class="engagifii-setting shortcode-list">';

    foreach ($shortcodes as $shortcode) {
        echo "<div class='bg-grey bordered' style='margin-bottom:20px'><h3 class='' style='margin-top:0'>{$shortcode['title']}</h3>";
        echo "<ul class='list'>";

        foreach ($shortcode['list'] as $item) {
            echo "<li><strong>{$item['name']}</strong>: <code>{$item['shortcode']}</code></li>";
        }

        echo "</ul></div>";
    }

    echo "<hr>";

    echo "<ul><li>Create a <a href='post-new.php?post_type=page' target='_blank'>page</a> or <a href='post-new.php' target='_blank'>Post</a> or use existing pages/posts</li><li>Place the shortcode where you want to display data</li></ul>";
    echo '</div>';
}

function ebt_api_url_setings(){

	
}

/*function engagifii_font_family_render(){
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
}*/


function ebt_api_url_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    if(isset($options['ebt_api_url']))
      $ebt_api_url = $options['ebt_api_url'];
    else
      $ebt_api_url ='';
    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_api_url]" class="postbox tnaUrl" value="'.$ebt_api_url.'">';
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

    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_tenant_code][engagifii_url]" class="postbox tenantCode" value="'.$engagifii_url.'"  oninput="getTenantCode(this.value, this)" >&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$code.'</span>';

      echo $_inputHtml.$_inputHtmlHidden;
}

 

	function ebt_api_settings_section_callback( ) {
  		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
	//$options = get_option( 'ebt_api_settings' );
	//$tenant_url= $options['ebt_tenant_code']['engagifii_url'];
    	?>
		<!-- Our admin page content should all be inside .wrap -->
  		<div class="wrap">
    		<!-- Here are our tabs -->
    		<nav class="nav-tab-wrapper wp-clearfix">
      			<a href="?page=engagifii-module-api" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Customize CSS</a>
      			<a href="?page=engagifii-module-api&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">API Settings</a>
                <a href="?page=engagifii-module-api&tab=page-settings" class="nav-tab <?php if($tab==='page-settings'):?>nav-tab-active<?php endif; ?>">Page Settings</a>
                <a href="?page=engagifii-module-api&tab=dashboard-settings" class="nav-tab <?php if($tab==='dashboard-settings'):?>nav-tab-active<?php endif; ?>">Profile Settings</a>
      			<a href="?page=engagifii-module-api&tab=shortcode" class="nav-tab <?php if($tab==='shortcode'):?>nav-tab-active<?php endif; ?>">Shortcode Usage</a>
    		</nav>

    		<div class="tab-content">
    			<?php switch($tab) :
      			case 'settings':
      				$this->engagifii_api_settings('show');
      				//$this->engagifii_apply_css_ebt_render('hide');
        		break;
      			case 'shortcode':
        			$this->ebt_api_shortocde_description();
        		break;
				case 'page-settings':
        			$this->engagifii_api_settings('hide');
        			//$this->engagifii_apply_css_ebt_render('hide');
        		break;
				case 'profile-settings':
        			$this->engagifii_api_settings('hide');
        			//$this->engagifii_apply_css_ebt_render('hide');
        		break;
      			default:
      				$this->engagifii_api_settings('hide');
        			//$this->engagifii_apply_css_ebt_render('show');

        		break;
    			endswitch; ?>
    		</div>
  		</div>
        <!--API Urls accordion ends--> 
    <?php
	}

	function lbt_api_url_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    $lbt_api_url = '';
    if(isset($options['lbt_api_url']))
      $lbt_api_url = $options['lbt_api_url']; 
    $_inputHtml = '<input type="text"  class="postbox legisUrl"  name="ebt_api_settings[lbt_api_url]" value="'.$lbt_api_url.'">';
      echo $_inputHtml;
	}

	//API url for Events
	function evt_api_url_render(  ) {
		$options = get_option( 'ebt_api_settings' );
		$evt_api_url = '';
		if(isset($options['evt_api_url']))
		  $evt_api_url = $options['evt_api_url']; 
		$_inputHtml = '<input type="text"  class="postbox eventUrl"  name="ebt_api_settings[evt_api_url]" value="'.$evt_api_url.'">';
		  echo $_inputHtml;
		}
	//


	function engagifii_api_settings($html_class)
	{
		?>
			<div class="<?php echo $html_class; ?>" data-tab="settings">
			<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">API URLs <i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
            <div class="engagifii-setting api-urls accordion-content" style="display:none;">
            <?php
			$options = get_option( 'ebt_api_settings' );
			$engagifii_apis = $options['engagifii_apis']?? [];
			$engagifii_apis['tenant']=$engagifii_apis['tenant']??'';
			$update_manually = $engagifii_apis['update_manually'] ?? null;
    		$update_manually_setting = ($update_manually == 1) ? 'checked' : '';
			?>
            	<div style=" position:relative; ">
                <div <?php //if($update_manually == 1){ echo 'style="display:none"'; } ?>>
                  <div class="form-group">
                          <label style="width: 150px;">Select Environment</label>
                          <select name="ebt_api_settings[engagifii_apis][environment]" class="select-env <?php if(!$options) { echo 'nullenv'; } ?>" >
                               <?php
                                  $envs = [
                                    '' => 'Production',
                                    '-qa' => 'QA',
                                    '-support' => 'Support',
                                    '-hotfix' => 'Hotfix',
                                    '-preview3' => 'Preview3',
                                    '-preview4' => 'Preview4',
                                    '-preview6' => 'Preview6',
                                    '-preview9' => 'Preview9'
                                  ];
                                
                                  foreach ($envs as $value => $label) {
                                    $selected = $engagifii_apis['environment'] === $value ? ' selected' : '';
                                    echo "<option value='$value'$selected>$label</option>";
                                  }
                              ?>
                          </select>
                          <span class="env-loading" style="display:none"><img style="max-width:100%" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/loader.gif';?>" alt=""></span><span class="env-loading-msg"></span>
                          <?php
                              $apiSettings = ['crmUrl','reportUrl','revenueUrl','doUrl', 'authUrl','tnaUrl','eventUrl','legisUrl','resourceUrl'];
                              foreach ($apiSettings as $settingName) {
                                  $value = htmlspecialchars($engagifii_apis[$settingName], ENT_QUOTES, 'UTF-8');
                                  $inputField = "<input name='ebt_api_settings[engagifii_apis][$settingName]' class='$settingName' type='hidden' value='$value' />";
                                  echo $inputField;
                              }
                              ?>
                      </div>
                  <div class="form-group" >
                          <label style="width: 142px;">Tenant Code</label>
                         <input oninput="getTenantCode(this.value, this)" type="text" name="ebt_api_settings[engagifii_apis][tenant]" class="postbox tenantInput" value="<?php echo $engagifii_apis['tenant'];?>">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview"><?php echo $engagifii_apis['tenant'];?></span><input type="hidden"  class="postbox"  name="ebt_api_settings[engagifii_apis][tenant]" id="" value="<?php echo $engagifii_apis['tenant'];?>" required>
                      </div>
                 </div>
                 <div class="form-check form-switch" style="position:absolute; top:0; right:0">
                  <input class="form-check-input" type="checkbox" name="ebt_api_settings[engagifii_apis][update_manually]" id="update_manually" value="1" <?php echo $update_manually_setting; ?>> 
                   <label style="float:none" for="update_manually" class="form-check-label"><strong>update APIs separately</strong></label>
                </div>
               </div>
               <div class="apiUrls" <?php if($update_manually != 1){ echo 'style="display:none"'; } ?>>
               <hr>
            	<h4>Training & Accreditation API Settings</h4>
                <div class="form-group">
      					<label>API URL</label>
  						<?php $this->ebt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Tenant Code</label>
  						<?php $this->ebt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Legislation API Settings</h4>
                    <div class="form-group">
      					<label>API URL</label>
  						<?php $this->lbt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Tenant code</label>
  						<?php $this->lbt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Events API Settings</h4>
                    <div class="form-group">
      					<label>API URL</label>
  						<?php $this->evt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Tenant code</label>
  						<?php $this->evt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Dashboard API Settings</h4>
  					<div class="form-group">
  						<label>Tenant code</label>
  						<?php echo '<input oninput="getTenantCode(this.value, this)" type="text" name="ebt_api_settings[dashboard_tenant_code]" class="postbox tenantCode" value="'.$options['dashboard_tenant_code'].'">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$options['dashboard_tenant_code'].'</span><input type="hidden"  class="postbox"  name="ebt_api_settings[dashboard_tenant_code]" id="" value="'.$options['dashboard_tenant_code'].'" required>'; ?>
  					</div>
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
    	$_inputHtml = '<input type="text"  class="postbox tenantCode"  name="ebt_api_settings[lbt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" oninput="getTenantCode(this.value, this)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="lbt_tenantcode_preview">'.$code.'</span>';
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
    	$_inputHtml = '<input type="text"  class="postbox tenantCode"  name="ebt_api_settings[evt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" oninput="getTenantCode(this.value, this)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="evt_tenantcode_preview">'.$code.'</span>';
      	echo $_inputHtml.$_inputHtmlHidden;
	}

	//



	function engagifii_settings_api_view(  ) {	
		if ( ! current_user_can( 'manage_options' ) ) {
    		return;
  		}
		
		include_once( __DIR__.'/view/admin-settings-form.php' );
	}
	function engagifii_modules(){
		if ( ! current_user_can( 'manage_options' ) ) {
    		return;
  		}
		
		include_once( __DIR__.'/view/engagifii_modules.php' );
	}

}

new ebtAdminConfigSettings();
