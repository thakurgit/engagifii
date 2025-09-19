<?php 
// Only show events settings if the events module is enabled
if (!engagifii_should_show_module_settings('events')) {
    return;
}
?>
<div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Events Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $options = get_option( 'ebt_api_settings' );
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    if($options['evt_api_url']=='' || $options['evt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else{ ?>
        <!--events columns list-->
			<div class="cols-wrapper" style="position:relative">
            	<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page.</i><hr>
                <?php renderColumnsUI('events_visible_column_list','eventsList'); ?>
                </div>
                
                <!--events types-->
                <div class="cols-wrapper" style="position:relative">
                	<h3>Manage Event Type Visibility</h3><i>Check the columns that should be visible on the page.</i><hr>
                    <?php renderColumnsUI('events_type_visible_column_list','eventsType'); ?>
                </div>
                <?php
				if(isset($options['allEvents'])){
				  $allEvents = $options['allEvents'];
				 }else{
					 $allEvents = null;
				 }
			  
				  $all_events = '';
				  if($allEvents==1)
				  {
					   $all_events = 'checked';
			  }
				?>
                <div class="cols-wrapper" style="position:relative">
                	<h3>Manage Event Listing</h3><hr>
                    <div style="padding-left:7px"> <input type="checkbox" name="ebt_api_settings[allEvents]" id="allEvents" value="1" <?php echo $all_events;?>/> <strong>Show All Events</strong><br><i>Note: When unchecked, only upcoming events will be displayed.</i></div>

              </div>
    	<?php 
	}
		echo '</div>';				
?>
</div>
<?php /*?><div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Events Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
	$date = date('Y-m-d');
    $response = $obj->getEventsColumnData();
	$eventTypes = $obj->eventTypes($date);
	//print_r($eventTypes);
    $options = get_option( 'ebt_api_settings' );
	$required_column_array = ['name', 'city', 'tags', 'eventClasses', 'register', 'eventStatus', 'eventType', 'startDateTime'];
	//print_r(json_encode($response));
	
    $events_visible_column_list = array();
    if(isset($options['events_visible_column_list']))
	{
	$events_visible_column_list = $options['events_visible_column_list'];   
	}
	$events_type_visible_column_list = array();
    if(isset($options['events_type_visible_column_list']))
	{
	$events_type_visible_column_list = $options['events_type_visible_column_list'];   
	}
	//print_r($events_type_visible_column_list);
	$event_col_order   = isset($options['event_col_order']) ? $options['event_col_order']: array();
	$event_type_col_order   = isset($options['event_type_col_order']) ? $options['event_type_col_order']: array();

	//print_r(json_encode($events_visible_column_list));
    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['evt_api_url']=='' || $options['evt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';	
		} else if(is_array ($response)){
			echo '<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[event_col_order]" value="'.$options['event_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="eventList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			if(in_array($row->colName, $required_column_array)){
			//print_r($row->colName)."<br>";
			if($row->colName == 'startDateTime')
			{            $row->displayName = "Event Schedule";
			}
			$checked = "";
			if(in_array($row->colName, $events_visible_column_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='name')
			{
				$checked .= " checked readonly";
			}
			
		
			echo '<li  data-order="'.$counter.'"> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[events_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
				
				$counter++;
		}
    	}
    	echo '</ul>';
		
		echo '<h3>Manage Event Type Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[event_type_col_order]" value="'.$options['event_type_col_order'].'" /><ul class="ebt-grid-column-list " id="eventTypeList">';
    	//$counter=1;
		
		foreach ($eventTypes as $key => $row) {
			//if(in_array($row['text'], $required_column_array)){
			//print_r($row->colName)."<br>";
			
			$checked = "";
			if(in_array($row['value'], $events_type_visible_column_list))
			{
				$checked .= " checked";
			}
			// if($row->text=='name')
			// {
			// 	$checked .= " checked readonly";
			// }
			
		
			echo '<li  data-order="'.$counter.'"> <input id="'.$row['text'].'" class="'.$row['text'].'" type="checkbox" name="ebt_api_settings[events_type_visible_column_list][]" '.$checked.' value='.$row['value'].'><label for="'.$row['text'].'">'.$row['text'].'</label></li>'		;
				
				$counter++;
		//}
    	}
    	echo '</ul>';
		if(isset($options['allEvents'])){
			$allEvents = $options['allEvents'];
		   }else{
			   $allEvents = null;
		   }
		
			$all_events = '';
			if($allEvents==1)
			{
				 $all_events = 'checked';
			}

			
		echo '<h3>Manage Event Listing</h3><hr>';	
		echo '<span><input type="checkbox" name="ebt_api_settings[allEvents]" id="allEvents" value="1" '.$all_events.'/> <strong>Show All Events</strong></span>
		<br><i>Note: When unchecked, only upcoming events will be displayed.</i>';	
    }
		echo '</div>';
		
?>
</div><?php */?>