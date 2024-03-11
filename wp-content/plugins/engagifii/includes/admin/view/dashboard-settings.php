<div class="wrap dashoboard-settings <?php if($tab == 'dashboard-settings'){ echo 'show';}else {echo 'hide'; }?>" >
<div class="engagifii-setting m-tlr-20">
<?php 
//API setting
	$options = get_option( 'ebt_api_settings' );
	$dashboard_apis=array();
    if(isset($options['dashboard_apis'])){
    	$dashboard_apis = $options['dashboard_apis']; 
	}
	echo '<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Dashboard API Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3><div class="engagifii-setting accordion-content api-urls" style="display:none">';
		echo '<div class="form-group"><label>API URL</label><input type="text" name="ebt_api_settings[dashboard_apis][url]" class="postbox" value="'.$dashboard_apis['url'].'"></div>';
		echo '<div class="form-group"><label>Tenant Code</label><input oninput="getTenantCode(this.value, this)" type="text" name="ebt_api_settings[dashboard_apis][tenant]" class="postbox" value="'.$dashboard_apis['tenant'].'">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$dashboard_apis['tenant'].'</span><input type="hidden"  class="postbox"  name="ebt_api_settings[dashboard_apis][tenant]" id="" value="'.$dashboard_apis['tenant'].'"></div>';
	echo '</ul></div>';
//dashboard navigation
	$navdata ='[{"label":"Home","url":"engagifii-profile/welcome-to-mypsba","icon":"fas fa-home"},{"label":"My Profile","url":"engagifii-profile","icon":"fas fa-user"},{"label":"My Downloads","url":"engagifii-profile/my-transcript/downloads","icon":"fas fa-download"},	{"label":"Event Registration","url":"engagifii-profile/events","icon":"far fa-calendar-alt"},{"label":"My Transcript","url":"engagifii-profile/my-transcript","icon":"fas fa-file"},{"label":"Members","url":"","icon":"fas fa-child"},	{"label":"Resources","url":"","icon":"fas fa-book"},	{"label":"Signature Events","url":"","icon":"far fa-calendar-alt"}]';
	$response = json_decode($navdata);

	//$response = array();
    
    $dash_menus = array();
	$dash_menus_label = array();
    if(isset($options['dash_menus'])){
    	$dash_menus = $options['dash_menus']; 
		foreach ($dash_menus['items'] as $key=>$row) {
			if(array_key_exists("label",$row)){
				array_push($dash_menus_label,$row['label']);	
			}
				
		}
	}
   	echo '<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Dashboard Navigation Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3><div class="engagifii-setting accordion-content" style="display:none"><h3>Manage Dashboard Menu items</h3> <i>Check the menu items that should be visible on the profile page and drag the menu items to the order in which they should be displayed.</i><hr><input type="hidden" class="cls" name="ebt_api_settings[dash_menus][menu_order]" value="'.$dash_menus['menu_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="">';
    	$counter=1;
		foreach ($response as $key => $row) {
			$checked = "";
		if(in_array($row->label, $dash_menus_label)){
			$checked .= " checked";
		}			
			echo '<li  data-order="'.$counter.'"><input name="ebt_api_settings[dash_menus][items]['.$key.'][icon]" type="hidden" value="'.$row->icon.'"/><input name="ebt_api_settings[dash_menus][items]['.$key.'][url]" type="hidden" value="'.$row->url.'"/> <input  id="'.$row->label.'" class="'.$row->label.'" type="checkbox" name="ebt_api_settings[dash_menus][items]['.$key.'][label]" '.$checked.' value="'.$row->label.'"><label for="'.$row->label.'">'.$row->label.'</label></li>';
			$counter++;	  
    	}
    	echo '</ul></div>';
		
//manage dashboard fields	
	$tenant_code = $options['dashboard_apis']['tenant'];	
    $obj =  new adminDataColumn();
    $fielddata = $obj->getDashboardFieldData($tenant_code);
	
	$dashboard_fields = array();
	$dashboard_fields_list=array();
    if(isset($options['dashboard_fields'])){
    	$dashboard_fields = $options['dashboard_fields']; 
		if(array_key_exists("fields",$dashboard_fields)){
			$dashboard_fields_list = $dashboard_fields['fields'];	
		}
		
	}
	
   	echo '<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Dashboard Fields Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3><div class="engagifii-setting accordion-content" style="display:none"><h3>Manage Dashboard field items</h3> <i>Check the field items that should be visible on the profile page and drag the field items to the order in which they should be displayed.</i><hr><input type="hidden" class="cls" name="ebt_api_settings[dashboard_fields][order]" value="'.$dashboard_fields['order'].'" /><ul class="ebt-grid-column-list sortable-list" id="">'; 
	if(!$tenant_code){
		echo '<b style="color:red">oops! Dashboard Tenant code not found.</b>';	
	}else{
	$counter=1;
	foreach(json_decode($fielddata,true) as $key=>$row){
		
		
			$checked = "";
			if(in_array($row['fieldId'], $dashboard_fields_list)){
				$checked .= " checked";
			}
			echo '<li  data-order="'.$counter.'"><input  id="'.$row['fieldId'].'" class="" type="checkbox" name="ebt_api_settings[dashboard_fields][fields][]" '.$checked.' value="'.$row['fieldId'].'"><label for="'.$row['fieldId'].'">'.$row['fieldName'].'</label></li>';	
			$counter++;	
		}
	}
	
echo '</ul></div>';
 ?>
</div>
</div>