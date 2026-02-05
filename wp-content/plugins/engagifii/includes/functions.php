<?php
function engagifii_scripts() { ?> 
<style>
.login-btn {
	background: #ea8b2e;
	color: white !important;
	border-radius: 5px;
	padding: 5px 10px !important;
}	
</style>
<script>    
function clearAllCookies(newtab='') {
	  localStorage.clear();  
     var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
	if(newtab==''){
 	function myWindow(){
 		 //window.open('https://engagifii-preview4-identity.azurewebsites.net/Account/SignOut?ReturnUrl=%2Fconnect%2Fauthorize%2Fcallback%3Fclient_id%3Dng.EngagifiiUI%26redirect_uri%3Dhttps%253A%252F%252Fpsba.engagifii-preview4.com%252Fauth-callback%26response_type%3Did_token%2520token%26scope%3Dopenid%2520profile%2520email%2520UsersAPI%2520AccreditationAPI%2520BilltrackingApi%2520CommentApi%2520NotesApi%26state%3D2f9558adbd6147b0acdd08d1aa46c79c%26nonce%3D43ea3bf67eef475ca04ea79b328fd000','_self');
 			 <?php 
 			 $sso_settings = get_option('engagifii_sso_settings');
 			 $logout_url = (is_array($sso_settings) && isset($sso_settings['logout_url'])) ? $sso_settings['logout_url'] : '';
 			 ?>
 			 window.open('<?php echo $logout_url; ?>?ReturnUrl=<?php echo site_url(); ?>','_self');
 }
   setTimeout(function() {
 	  myWindow();
 	  }, 300);
 	}
	
}
function clearCookies() {
	  localStorage.clear();  
     var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
	}
    		function moOAuthLoginNew(app_name) {
			
			window.location.href = '<?php echo site_url(); ?>' + '/wp-login.php?action=engagifii_sso';
		}
    const accessToken = '<?php echo isset($_SESSION['accesstoken']) ? $_SESSION['accesstoken'] : ''; ?>';
    
    if (accessToken) {
    document.cookie = 'peopleToken=' + encodeURIComponent(accessToken) + '; expires=' + new Date(new Date().getTime() + 720 * 60 * 60 * 1000).toUTCString() + '; path=/';
        // Save the access token to localStorage
        const jsonValue = {
  		"id_token": accessToken
		};
	const jsonValueString = JSON.stringify(jsonValue);
	localStorage.setItem('userLogin', jsonValueString);
    }
	//$(document).ready(function(){
		if(window.location.href=='<?php echo site_url();?>/' && localStorage.getItem("userLogin")!==null && localStorage.getItem("logged_in_user")===null){
window.location.href = "<?php echo site_url();?>/my-profile";	
		}
	//});
	document.addEventListener('DOMContentLoaded', function() {
        var loginEngagifii = document.getElementById('login_engagifii');

        if (loginEngagifii) { 
            loginEngagifii.addEventListener('click', function() {
				if(localStorage.getItem("userLogin")!==null){
					window.location.href="<?php echo site_url();?>/my-profile";
				}else{
               		 moOAuthLoginNew('Engagifii');
				}
            });
        }
    });
   /* document.addEventListener('DOMContentLoaded', function() {
    var psbaLoginElements = document.getElementsByClassName('psba-login');

    if (psbaLoginElements.length > 0) {
        var psbaLogin = psbaLoginElements[0];

        psbaLogin.addEventListener('click', function() {
           // clearAllCookies();
        });
    }
});*/
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('a[id^="macosession-"]');
    links.forEach(link => {
        link.addEventListener('click', function (event) {
            const id = this.id;
            const year = id.split('-')[1];
            localStorage.setItem('sessionname', (year - 1) + ' Regular Session');
            });
    });
});
</script>
    <?php
}
add_action('wp_footer', 'engagifii_scripts');

    $options  = get_option( 'ebt_api_settings' );
	$tenant_url = '';
	if ( is_array( $options ) && ! empty( $options['dashboard_tenant_code'] ) ) {
    $tenant_url = $options['dashboard_tenant_code'];
}
	if($tenant_url){
/*function custom_login_redirect( $redirect_to, $request, $user ) {
    // Get the current user's role
    $user_role = $user->roles[0];
 
    // Set the URL to redirect users to based on their role
    if ( $user_role == 'subscriber' ) {
        $redirect_to = site_url().'/my-profile/';
    } 
 
    return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );*/
add_action('wp_logout','engagifii_logout');

function engagifii_logout(){
  wp_safe_redirect( home_url() );
  exit;
}
add_action('after_setup_theme', 'hide_admin_bar_for_subscribers');
function hide_admin_bar_for_subscribers() {
    $user = wp_get_current_user();

    if (in_array('subscriber', (array) $user->roles, true)) {
        show_admin_bar(false);
    }
}

session_start();
//add_action('mo_oauth_logged_in_user_token', 'GetToken' , 10, 2);   

function GetToken( $user, $token ){                
		?>
		ID Token
		<?php
		print_r($token['id_token']);
}
}

// remove whitespace from html
function removeWhitespace($buffer)
{
    return preg_replace('/\s+/', ' ', $buffer);
}
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    $user = wp_get_current_user();
	$user_role = (!empty($user->roles) && isset($user->roles[0])) ? $user->roles[0] : '';
	$options  = get_option( 'ebt_api_settings' );
    $login_btn = isset($options['dash_menus']['login_btn']) ? $options['dash_menus']['login_btn'] : false;

	
	$login = "moOAuthLoginNew('Engagifii')";
    if (is_user_logged_in() && $args->theme_location == 'primary' &&   $user_role == 'subscriber' && $login_btn ) {
        $items .= '<li class="nav-item"><a title="Logout" class="nav-link login-btn" onclick="clearAllCookies()" href="'. wp_logout_url() .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'primary' && $login_btn ) {
        $items .= '<li class="nav-item"><a onClick="clearCookies(); '.$login.'" class="nav-link login-btn" title="Login with Engagifii" href="javascript:void">Log In</a></li>';
    }
    return $items;
}
//save group member columns on ajax save
/*add_action('wp_ajax_save_groupmember_cols', 'save_groupmember_cols');
function save_groupmember_cols() {
	check_ajax_referer('save_groups_nonce', 'security');
    if (!isset($_POST['security'])) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    // Sanitize inputs
    $visible = isset($_POST['visible_column_list']) ? array_map('sanitize_text_field', (array) $_POST['visible_column_list']) : [];
    $order = isset($_POST['column_order']) ? sanitize_text_field($_POST['column_order']) : '';
    $visibleGrid = isset($_POST['visible_column_grid']) ? array_map('sanitize_text_field', (array) $_POST['visible_column_grid']) : [];
    $orderGrid = isset($_POST['column_order_grid']) ? sanitize_text_field($_POST['column_order_grid']) : '';
    // Get existing settings
    $settings = get_option('ebt_api_settings', []);
    if (!isset($settings['group_members_settings'])) {
        $settings['group_members_settings'] = [];
    }
    // Update only relevant parts
    $settings['group_members_settings']['list']['visible_column_list'] = $visible;
    $settings['group_members_settings']['list']['order'] = $order;
    $settings['group_members_settings']['grid']['visible_column_list'] = $visibleGrid;
    $settings['group_members_settings']['grid']['order'] = $orderGrid;
    // Save updated settings
    update_option('ebt_api_settings', $settings);

    wp_send_json_success(['message' => 'Group Column settings saved successfully.']);
}*/
// Save organization columns on AJAX save
/*add_action('wp_ajax_save_organization_cols', 'save_organization_cols');
function save_organization_cols() {
	check_ajax_referer('save_org_nonce', 'security');

	if (!isset($_POST['security'])) {
		wp_send_json_error(['message' => 'Security check failed.']);
	}

	// Sanitize inputs
	 $visible = isset($_POST['visible_column_list']) ? array_map('sanitize_text_field', (array) $_POST['visible_column_list']) : [];
    $order = isset($_POST['column_order']) ? sanitize_text_field($_POST['column_order']) : '';
    $visibleGrid = isset($_POST['visible_column_grid']) ? array_map('sanitize_text_field', (array) $_POST['visible_column_grid']) : [];
    $orderGrid = isset($_POST['column_order_grid']) ? sanitize_text_field($_POST['column_order_grid']) : '';
	// Get existing settings
	$settings = get_option('ebt_api_settings', []);
	if (!isset($settings['organization_settings'])) {
		$settings['organization_settings'] = [];
	}

	// Update settings
	  // Update only relevant parts
    $settings['organization_settings']['list']['visible_column_list'] = $visible;
    $settings['organization_settings']['list']['order'] = $order;
    $settings['organization_settings']['grid']['visible_column_list'] = $visibleGrid;
    $settings['organization_settings']['grid']['order'] = $orderGrid;

	// Save updated settings
	update_option('ebt_api_settings', $settings);

	wp_send_json_success(['message' => 'Organization column settings saved successfully.']);
}*/
//ajax save columns
function set_nested_array_value(&$array, $path, $value) {
    $keys = preg_split('/\]\[|\[|\]/', trim($path, '[]'));
    foreach ($keys as $key) {
        if (!isset($array[$key]) || !is_array($array[$key])) {
            $array[$key] = [];
        }
        $array = &$array[$key];
    }
    $array = $value;
}
add_action('wp_ajax_save_cols', 'save_cols');
function save_cols() {
	check_ajax_referer('save_cols_nonce', 'security');
    if (!isset($_POST['security'])) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }
	if (!isset($_POST['column_namearray'])) {
        wp_send_json_error(['message' => 'Option name not defined.']);
    }
    // Sanitize inputs
    $raw_visible = isset($_POST['visible_column_list']) ? (array) $_POST['visible_column_list'] : [];
	$visible = array_map(function($item) {
		return sanitize_text_field(stripslashes($item));
	}, $raw_visible);
    $settings = get_option('ebt_api_settings', []);
	$columnPath = $_POST['column_namearray'] ?? '';
    if (strpos($columnPath, '[') !== false) {
	  set_nested_array_value($settings, $columnPath, $visible);
	} else {
		$settings[$columnPath] = $visible;
	}
    update_option('ebt_api_settings', $settings);

    wp_send_json_success(['message' => 'Column settings saved successfully.']);
}
//columns render function
function renderColumnsUI($optionKey,$action){
	$nameString = 'ebt_api_settings';
	$saveOptionName = '';
    if (is_string($optionKey)) {
        $nameString .= '[' . $optionKey . ']';
		$saveOptionName .= $optionKey;
    } elseif (is_array($optionKey)) {
        foreach ($optionKey as $key) {
            $nameString .= '[' . $key . ']';
			$saveOptionName .= '[' . $key . ']';
        }
    }
    $nameString .= '[]'; 
	
	$options = get_option( 'ebt_api_settings' );
	$visible_columns = array();
	if (!empty($optionKey)) {
	  if (is_array($optionKey)) {
		  // Traverse nested keys
		  $temp = $options;
		  foreach ($optionKey as $keyPart) {
			  if (isset($temp[$keyPart])) {
				  $temp = $temp[$keyPart];
			  } else {
				  $temp = [];
				  break;
			  }
		  }
		  $visible_columns = $temp;
	  } elseif (is_string($optionKey) && isset($options[$optionKey])) {
		  // Flat key
		  $visible_columns = $options[$optionKey];
	  }
  }
	$colNames = [];
	foreach ($visible_columns as $row) {
		if (is_string($row)) {
			$rowData = json_decode(stripslashes($row));
		} else {
			$rowData = $row;
		}
		if (!empty($rowData->colName)) {
			$colNames[] = $rowData->colName;
		}
	}
	?>	
    <div class="cols-dropdown bdrs">
					<button type="button" class="bdrs">Select <i class="dashicons-before dashicons-arrow-down-alt2"></i></button>
					<div class="cols-list-wrapper bdrs" style="display:none">
                    	<button type="button" class="refreshCols button" title="Refresh List"><span class="dashicons dashicons-update"></span></button>
						<input type="text" class="cols-list-search bdrs" placeholder="search">
						<ul class="ebt-grid-column-list" id="<?php echo $action; ?>" data-endpoint="<?php echo $action; ?>" data-visibleCols="<?php echo htmlspecialchars(json_encode($colNames)); ?>" data-cols-array="<?php echo $nameString; ?>">
                        <li><span class="env-loading"><img style="max-width:100%" src="<?php echo  ENGAGIFII_ASSETS_URL; ?>/images/loader.gif" alt=""></span></li>
                        </ul>
                        </div>
                        </div>
      <ul class="checked-cols"> 
                        <?php $sortedList =[];  
						foreach ($visible_columns as $key => $row) {
						  if (is_string($row)) {
							  $rowData = json_decode(stripslashes($row));
						  } else {
							  $rowData = $row;
						  }
						  if (!$rowData || empty($rowData->colName)) {
							  continue; // Skip if colName is missing or invalid JSON
						  }
						  $rowData->displayName = !empty($rowData->displayName) ? $rowData->displayName : $rowData->colName;
						  $rowData->colOrder  = isset($rowData->colOrder) ? $rowData->colOrder : 9999;
						  $sortedList[] = $rowData;
						}
						  usort($sortedList, function($a, $b) {
							  return $a->colOrder <=> $b->colOrder;
						  });
						  foreach ($sortedList as $rowData) {
							echo '<li data-order="' . esc_attr($rowData->colOrder) . '">' . esc_html($rowData->displayName) . 
								 '<button title="Delete Column" class="uncheck-cols"><i class="dashicons dashicons-no-alt"></i></button></li>';
						  }
						  if(empty($sortedList)){
							  echo '<span class="placeholder">No columns selected.</span>';
						  }
 ?>
    </ul>
      <button type="button" class="button manageColOrder">Manage Column Order</button> 
	<div class="colsOrderModal" style="display:none;">
					<div class="colsList bdrs">
						<div class="colsListHeader">
							<h3>Reorder Visible Columns</h3>
							<button class="close" type="button"><i class="dashicons dashicons-no-alt"></i></button>
						</div>
						<hr>
                        <div style="padding:0 10px"><i>Drag the field names to the order in which they should be displayed. Ordering is available for <strong>list</strong> view only.</i></div>
						<ul class="colsListBody sortable-cols">
							 <?php foreach ($visible_columns as $key => $row) {
							if (is_string($row)) {
							  $rowData = json_decode(stripslashes($row));
						  } else {
							  $rowData = $row;
						  }
						  if (!$rowData || empty($rowData->colName)) {
							  continue; // Skip if colName is missing or invalid JSON
						  }
						  $displayName = !empty($rowData->displayName) ? $rowData->displayName : $rowData->colName;
						  $colOrder = isset($rowData->colOrder) ? $rowData->colOrder : '';
									$value_data = [
									'colName' => $rowData->colName,
									'displayName' => $displayName,
									'colOrder' => $colOrder
								];
								if (!empty($rowData->fieldId)) {
									$value_data['fieldId'] = $rowData->fieldId;
								}
								if (!empty($rowData->controlTypeId)) {
									$value_data['controlTypeId'] = $rowData->controlTypeId;
								}
								$input_value = htmlspecialchars(json_encode($value_data), ENT_QUOTES, 'UTF-8');
									echo '<li data-order="' . $colOrder . '">
										<input type="hidden" value="' . $input_value . '" name="' . esc_attr($nameString) . '" />
										<span class="dashicons dashicons-sort"></span>
										<div class="bdrs">' . $displayName. '</div>
									</li>';
							} ?>
						</ul>
						<hr>
						<div class="colsListFooter">
							<button class="button close" type="button">Cancel</button>&nbsp;&nbsp;
							<button class="button button-secondary resetOrder" type="button">Reset to Default Order</button>&nbsp;&nbsp;
							<button data-columnsname="<?php echo $saveOptionName; ?>" class="button button-primary colsSave" type="button">Save</button>
						</div> 
					</div>
				</div>
      <?php if (isset($options['debug_mode']) && $options['debug_mode']==1) {
			echo '<div class="option-saved-value">';
		echo '<ul>';
		echo '<li>'.$saveOptionName.' -<pre>' . htmlspecialchars(print_r($options[$saveOptionName], true)) . '</pre></li>';
		echo '</ul>';
		echo '</div>'; 
		}
}
//convert columns  array of json strings into array of objects
function convertToObjectArray(array $inputArray): array {
    $result = [];
    foreach ($inputArray as $item) {
        $decoded = json_decode($item);
        if ($decoded instanceof stdClass) {
            $result[] = $decoded;
        }
    }
    return $result;
}
//check if columns array is simple array or array of json strings
function isJsonString($string) {
    if (!is_string($string)) {
        return false;
    }
    json_decode($string);
    return (json_last_error() === JSON_ERROR_NONE);
}
function isArrayOfJsonStrings(array $input) {
    foreach ($input as $item) {
        if (!isJsonString($item)) {
            return false; // Found non-JSON string
        }
    }
    return true; // All items are valid JSON
}
//create a colNames array from array of json strings
function extractColNames($jsonStrings){
	$columnNames = [];
  foreach ($jsonStrings as $jsonStr) {
	$decoded = json_decode($jsonStr);
	if (is_object($decoded) && isset($decoded->colName)) {
		$columnNames[] = $decoded->colName;
	}
  }
	return $columnNames;
}
//debug mode handler 
add_action('wp_ajax_update_debug_mode_setting', 'update_debug_mode_setting_callback');
function update_debug_mode_setting_callback() {
    $debug_mode = isset($_POST['debug_mode']) ? (int) $_POST['debug_mode'] : 0;
    $options = get_option('ebt_api_settings', []);
    $options['debug_mode'] = $debug_mode;
    update_option('ebt_api_settings', $options);
    wp_send_json_success(['message' => 'Debug mode updated']);
}



