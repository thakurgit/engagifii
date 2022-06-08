<div class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Events Columns Visibility<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getEventsColumnData();
    $options = get_option( 'ebt_api_settings' );
	$required_column_array = ['name', 'city', 'tags', 'eventClasses', 'register', 'eventStatus', 'eventType', 'eventDates'];
	//print_r(json_encode($response));
	
    $events_visible_column_list = array();
    if(isset($options['events_visible_column_list']))
	{
	$events_visible_column_list = $options['events_visible_column_list'];   
	}
	//print_r(json_encode($events_visible_column_list));
		if(is_array ($response)){
    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    	echo '<ul class="ebt-grid-column-list">';
    	$counter=0;
		foreach ($response as $key => $row) {
			if(in_array($row->colName, $required_column_array)){
			//print_r($row->colName)."<br>";

			$checked = "";
			if(in_array($row->colName, $events_visible_column_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='name')
			{
				$checked .= " checked readonly";
			}
			if($counter >1 && $counter%3==0)
			{
				//echo '</ul>';
				//echo '<ul class="ebt-grid-column-list">'; 			 
			}			 
		
			echo '<li> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[events_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
				
				$counter++;
		}
    	}
    	echo '</ul>';
		if(isset($options['upcomingEvents'])){
			$upcomingEvents = $options['upcomingEvents'];
		   }else{
			   $upcomingEvents = 0;
		   }
		
			$up_events = '';
			if($upcomingEvents==1)
			{
				 $up_events  = 'checked';
				 $all_events = '';
			} else {
				 $up_events  = '';
				 $all_events = 'checked';
			}
		echo '<span> <input type="radio" name="ebt_api_settings[upcomingEvents]" id="upcomingEvents" value="1" '.$up_events.'/> Show only Upcoming Events</span><br>';
		echo '<span><input type="radio" name="ebt_api_settings[upcomingEvents]" id="allEvents" value="0" '.$all_events.'/> Show All Events</span>
		</div>';	
		
    }
?>
</div>