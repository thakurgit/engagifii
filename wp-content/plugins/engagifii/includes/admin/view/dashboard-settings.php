<div class="wrap dashoboard-settings <?php if($tab == 'dashboard-settings'){ echo 'show';}else {echo 'hide'; }?>" >
<div class="engagifii-setting m-tlr-20">
<?php 	$options = get_option( 'ebt_api_settings' );
	$tenant_url= $options['ebt_tenant_code']['engagifii_url'];
 if($tenant_url!='psba'){
echo '<h4>Profile settings is not allowed</h4>';
} else { 
echo 'In Progress..';
	/*$response = json_decode('[{"label":"Home","url":"","icon":"fas fa-home"},
	{"label":"My Profile","url":"engagifii-profile","icon":"fas fa-user"},
	{"label":"My Downloads","url":"engagifii-profile/my-transcript/downloads","icon":"fas fa-download"},
	{"label":"Event Registration","url":"engagifii-profile/events","icon":"far fa-calendar-alt"},
	{"label":"My Transcript","url":"engagifii-profile/my-transcript","icon":"fas fa-file"},
	{"label":"Members","url":"","icon":"fas fa-child"},
	{"label":"Resources","url":"","icon":"fas fa-book"},
	{"label":"Signature Events","url":"","icon":"far fa-calendar-alt"}]');
	$response = array();
    $options = get_option( 'ebt_api_settings' );
    $dash_menus = array();
    if(isset($options['dash_menus'])){
    	$dash_menus = $options['dash_menus']; 
		$dash_menus_label = array();
		foreach ($dash_menus['items'] as $key=>$row) {
			array_push($dash_menus_label,$row['label']);	
		}
	}
   	echo '<div class="engagifii-setting">';
			echo '<h3>Manage Dashboard Menu items</h3> <i>Check the menu items that should be visible on the profile page and drag the menu items to the order in which they should be displayed.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[dash_menus][menu_order]" value="'.$dash_menus['menu_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="">';
    	$counter=1;
		foreach ($response as $key => $row) {
			$checked = "";
		if(in_array($row->label, $dash_menus_label)){
			$checked .= " checked";
		}			
			echo '<li  data-order="'.$counter.'"><input name="ebt_api_settings[dash_menus][items]['.$key.'][icon]" type="hidden" value="'.$row->icon.'"/><input name="ebt_api_settings[dash_menus][items]['.$key.'][url]" type="hidden" value="'.$row->url.'"/> <input  id="'.$row->label.'" class="'.$row->label.'" type="checkbox" name="ebt_api_settings[dash_menus][items]['.$key.'][label]" '.$checked.' value="'.$row->label.'"><label for="'.$row->label.'">'.$row->label.'</label></li>';
			$counter++;	  
    	}
    	echo '</ul>';
		
		echo '</div>';*/				

 } ?>
</div>
</div>