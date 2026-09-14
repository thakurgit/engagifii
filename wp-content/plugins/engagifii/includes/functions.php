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

    $options  = get_option( 'ebt_api_settings', [] );
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
/* add_action('wp_logout','engagifii_logout');

function engagifii_logout(){
  wp_safe_redirect( home_url() );
  exit;
} */
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
	$options  = get_option( 'ebt_api_settings', [] );
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

if (!function_exists('engagifii_org_normalize_field_key')) {
    function engagifii_org_normalize_field_key($key) {
        return preg_replace('/[^a-z0-9]/', '', strtolower((string) $key));
    }
}

if (!function_exists('engagifii_org_guest_hidden_fields_configured')) {
    function engagifii_org_guest_hidden_fields_configured($options) {
        $org_settings = $options['organization_settings'] ?? array();

        if (!empty($org_settings['guest_hidden_fields_configured'])) {
            return true;
        }

        return array_key_exists('guest_hidden_fields', $org_settings);
    }
}

if (!function_exists('engagifii_org_get_saved_guest_hidden_fields')) {
    /**
     * Raw guest-hidden colNames from settings (empty array is valid — do not treat as "unset").
     */
    function engagifii_org_get_saved_guest_hidden_fields($options) {
        $org_settings = $options['organization_settings'] ?? array();

        if (engagifii_org_guest_hidden_fields_configured($options)) {
            $fields = $org_settings['guest_hidden_fields'] ?? array();
            return is_array($fields) ? array_values($fields) : array();
        }

        return array('phoneNumbers', 'primaryEmail');
    }
}

if (!function_exists('engagifii_org_get_guest_mask_keys')) {
    function engagifii_org_get_guest_mask_keys($options) {
        if (is_user_logged_in()) {
            return array();
        }

        $saved = engagifii_org_get_saved_guest_hidden_fields($options);
        if (empty($saved)) {
            return array();
        }

        return engagifii_org_build_guest_mask_keys($saved, $options);
    }
}

if (!function_exists('engagifii_org_get_detail_only_guest_runtime_keys_map')) {
    /**
     * Detail-page pseudo fields (not in column JSON) → runtime keys to mask when checked.
     */
    function engagifii_org_get_detail_only_guest_runtime_keys_map() {
        return array(
            'Overview' => array('Overview', 'Organization Bio', 'Organization Overview', 'Organization/Company Description'),
            'SocialPages' => array('SocialPages', 'Social Pages'),
            'WebsiteContacts' => array('WebsiteContacts', 'Contacts', 'Contact Name', 'Title', 'LinkedIn'),
        );
    }
}

if (!function_exists('engagifii_org_index_organization_columns')) {
    function engagifii_org_index_organization_columns($options) {
        $org_settings = $options['organization_settings'] ?? array();
        $column_json_list = array_merge(
            $org_settings['list']['visible_column_list'] ?? array(),
            $org_settings['grid']['visible_column_list'] ?? array(),
            $org_settings['detail']['visible_field_list'] ?? array()
        );

        $columns_by_name = array();
        foreach ($column_json_list as $col_json) {
            $col = json_decode(stripslashes($col_json), true);
            if (!$col || empty($col['colName'])) {
                continue;
            }
            $columns_by_name[$col['colName']] = $col;
        }

        return $columns_by_name;
    }
}

if (!function_exists('engagifii_org_find_column_for_guest_saved_value')) {
    function engagifii_org_find_column_for_guest_saved_value($saved_value, $columns_by_name) {
        if ($saved_value === '' || !is_array($columns_by_name)) {
            return null;
        }

        if (isset($columns_by_name[$saved_value])) {
            return $columns_by_name[$saved_value];
        }

        foreach ($columns_by_name as $col) {
            if (!empty($col['fieldId']) && $col['fieldId'] === $saved_value) {
                return $col;
            }
            if (!empty($col['displayName']) && $col['displayName'] === $saved_value) {
                return $col;
            }
        }

        return null;
    }
}

if (!function_exists('engagifii_org_should_mask_contact_details')) {
    function engagifii_org_should_mask_contact_details($is_logged_in, $guest_mask_keys) {
        if ($is_logged_in || empty($guest_mask_keys)) {
            return false;
        }

        $contact_section_fields = array(
            'WebsiteContacts',
            'Contacts',
        );

        foreach ($contact_section_fields as $field_key) {
            if (engagifii_org_should_mask_field($field_key, false, $guest_mask_keys)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('engagifii_org_build_guest_mask_keys')) {
    /**
     * Build normalized mask lookup keys from explicitly checked admin colNames only.
     * Does not expand alias groups across separate checkboxes.
     */
    function engagifii_org_build_guest_mask_keys($guest_hidden_fields, $options) {
        $mask_keys = array();
        if (empty($guest_hidden_fields) || !is_array($guest_hidden_fields)) {
            return $mask_keys;
        }

        $register = function($value) use (&$mask_keys) {
            $normalized = engagifii_org_normalize_field_key($value);
            if ($normalized !== '') {
                $mask_keys[$normalized] = true;
            }
        };

        $columns_by_name = engagifii_org_index_organization_columns($options);
        $detail_runtime = engagifii_org_get_detail_only_guest_runtime_keys_map();
        $registered_cols = array();

        foreach ($guest_hidden_fields as $saved_value) {
            if ($saved_value === '') {
                continue;
            }

            $col = engagifii_org_find_column_for_guest_saved_value($saved_value, $columns_by_name);
            if ($col) {
                $col_name = $col['colName'];
                if (isset($registered_cols[$col_name])) {
                    continue;
                }
                $registered_cols[$col_name] = true;
                $register($col['colName']);
                if (!empty($col['displayName'])) {
                    $register($col['displayName']);
                }
                if (!empty($col['fieldId'])) {
                    $register($col['fieldId']);
                }
                continue;
            }

            if (isset($detail_runtime[$saved_value])) {
                foreach ($detail_runtime[$saved_value] as $runtime_key) {
                    $register($runtime_key);
                }
                continue;
            }

            $register($saved_value);
        }

        return $mask_keys;
    }
}

if (!function_exists('engagifii_org_should_mask_field')) {
    function engagifii_org_should_mask_field($fieldKey, $is_logged_in, $guest_mask_keys, $fieldId = '') {
        if ($is_logged_in || empty($guest_mask_keys)) {
            return false;
        }

        foreach (array($fieldKey, $fieldId) as $key) {
            if ($key === '') {
                continue;
            }
            if (!empty($guest_mask_keys[engagifii_org_normalize_field_key($key)])) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('engagifii_org_guest_field_saved_as_checked')) {
    /**
     * Whether a guest-field checkbox should appear checked in admin.
     * Matches only this column's own identifiers (not alias-group siblings).
     */
    function engagifii_org_guest_field_saved_as_checked($col, $guest_hidden) {
        if (empty($guest_hidden) || !is_array($guest_hidden) || empty($col['colName'])) {
            return false;
        }

        if (in_array($col['colName'], $guest_hidden, true)) {
            return true;
        }
        if (!empty($col['fieldId']) && in_array($col['fieldId'], $guest_hidden, true)) {
            return true;
        }
        if (!empty($col['displayName']) && in_array($col['displayName'], $guest_hidden, true)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('engagifii_expand_org_guest_hidden_fields')) {
    /** @deprecated Use engagifii_org_build_guest_mask_keys() — kept for backward compatibility. */
    function engagifii_expand_org_guest_hidden_fields($guest_hidden_fields, $settings) {
        $mask_keys = engagifii_org_build_guest_mask_keys($guest_hidden_fields, $settings);
        return array_keys($mask_keys);
    }
}

if (!function_exists('engagifii_calendar_data_start_attr')) {
    function engagifii_calendar_data_start_attr($items) {
        if (empty($items)) {
            return 'no-data';
        }
        return htmlspecialchars(json_encode($items), ENT_QUOTES, 'UTF-8');
    }
}

// Preserve / normalise guest_hidden_fields when the main WP settings form saves
add_filter('pre_update_option_ebt_api_settings', 'engagifii_preserve_guest_hidden_fields', 10, 2);
function engagifii_preserve_guest_hidden_fields($new_value, $old_value) {
	$submitted = isset($new_value['organization_settings']['guest_hidden_fields_submitted']);
	if ($submitted) {
		// Sentinel was present — the section was in the form, normalise the key
		unset($new_value['organization_settings']['guest_hidden_fields_submitted']);
		$new_value['organization_settings']['guest_hidden_fields_configured'] = 1;
		if (!isset($new_value['organization_settings']['guest_hidden_fields'])) {
			// All boxes were unchecked; save empty array explicitly
			$new_value['organization_settings']['guest_hidden_fields'] = [];
		} else {
			// Save only what the admin explicitly checked (colName values from POST).
			// Alias/fieldId expansion runs at display time, not on save.
			$new_value['organization_settings']['guest_hidden_fields'] = array_values(array_unique(array_map(
				'sanitize_text_field',
				(array) $new_value['organization_settings']['guest_hidden_fields']
			)));
		}
	} elseif (isset($old_value['organization_settings']['guest_hidden_fields'])) {
		// Section was NOT in this form submission — preserve the existing saved value
		$new_value['organization_settings']['guest_hidden_fields'] =
			$old_value['organization_settings']['guest_hidden_fields'];
		if (!empty($old_value['organization_settings']['guest_hidden_fields_configured'])) {
			$new_value['organization_settings']['guest_hidden_fields_configured'] = 1;
		}
	}
	return $new_value;
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
	$visible_unsanitized = array_map(function($item) {
		return sanitize_text_field(stripslashes($item));
	}, $raw_visible);
	// Deduplicate by colName to prevent multiple 'Name' entries from stacking up
	$seen = [];
	$visible = [];
	foreach ($visible_unsanitized as $item) {
		$decoded = json_decode($item, true);
		$key = isset($decoded['colName']) ? strtolower($decoded['colName']) : $item;
		if (!in_array($key, $seen)) {
			$seen[] = $key;
			$visible[] = $item;
		}
	}
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
	
	$options = get_option( 'ebt_api_settings', [] );
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



