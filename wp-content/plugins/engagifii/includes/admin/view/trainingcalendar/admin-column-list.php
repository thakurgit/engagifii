<div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Training Calendar Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
	$response = $obj->getAllCommonColumnList(); 
    
    $options = get_option( 'ebt_api_settings' );
	$required_column_array = ['name', 'entity', 'city', 'tags', 'register', 'status', 'Type', 'startDateTime'];
	//print_r($response);
	
    $training_calendar_visible_column_list = array();
    if(isset($options['training_calendar_visible_column_list']))
	{
	$training_calendar_visible_column_list = $options['training_calendar_visible_column_list'];   
	}
	$event_class_col_order   = isset($options['event_class_col_order']) ? $options['event_class_col_order']: array();
	//print_r(json_encode($training_calendar_visible_column_list));
    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['evt_api_url']=='' || $options['evt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';	
		} else if(is_array ($response)){
			echo '<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[event_class_col_order]" value="'.$options['event_class_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="eventList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			if(in_array($row->colName, $required_column_array)){
			//print_r($row->colName)."<br>";
			if($row->colName == 'startDateTime')
			{            $row->displayName = "Event Schedule";
			}
			$checked = "";
			if(in_array($row->colName, $training_calendar_visible_column_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='name')
			{
				$checked .= " checked readonly";
			}
			
		
			echo '<li  data-order="'.$counter.'"> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[training_calendar_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
				
				$counter++;
		}
    	}
    	echo '</ul>';
		if(isset($options['allEventsClass'])){
			$allEventsClass = $options['allEventsClass'];
		   }else{
			   $allEventsClass = null;
		   }
		
			$all_events_class = '';
			if($allEventsClass==1)
			{
				 $all_events_class = 'checked';
			}
		echo '<h3>Manage Listing</h3><hr>';	
		echo '<span><input type="checkbox" name="ebt_api_settings[allEventsClass]" id="allEventsClass" value="1" '.$all_events_class.'/> <strong>Show All Events & Class</strong></span>
		<br><i>Note: When unchecked, only upcoming events & classes will be displayed.</i>';	
    }
		echo '</div>';
		
?>
</div>