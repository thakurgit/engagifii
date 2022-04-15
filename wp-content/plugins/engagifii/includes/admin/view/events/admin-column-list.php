<div class="<?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h2 class="m-tlr-20 bg-grey bordered">Events Columns Visibility</h2>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getEventsColumnData();
    $options = get_option( 'ebt_api_settings' );
	//print_r($response);
    $events_visible_column_list = array();
    if(isset($options['events_visible_column_list']))
	{
	$events_visible_column_list = $options['events_visible_column_list'];   
	}
		if(is_array ($response)){
    
    	echo '<div class="engagifii-setting">';
    	echo '<ul class="ebt-grid-column-list">';
    	$counter=0;
		foreach ($response as $key => $row) {
			//print_r($events_visible_column_list[$counter]);

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
				echo '</ul>';
				echo '<ul class="ebt-grid-column-list">'; 			 
			}			 
			echo '<li> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[events_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
	    	$counter++;	  
    	}
    	echo '</ul></div>';				
    }
?>
</div>