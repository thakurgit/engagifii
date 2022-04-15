<div class="<?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h2 class="m-tlr-20 bg-grey bordered">Classes Columns Visibility</h2>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getClassColumnData();
    $options = get_option( 'ebt_api_settings' );
    $class_visible_column_list = array();
    if(isset($options['class_visible_column_list']))
    	$class_visible_column_list = $options['class_visible_column_list'];   

//print_r($response);
	//echo '<br/>';
	//print_r($class_visible_column_list);

    if(is_array ($response)){
    
    	echo '<div class="engagifii-setting">';
    	echo '<ul class="ebt-grid-column-list">';
    	$counter=0;
		foreach ($response as $key => $row) {
			$checked = "";

//print_r($key);

			if(in_array($row->colName, $class_visible_column_list))
			{
				$checked .= " checked";
			}
			if($key=='sectionname')
			{
				$checked .= " checked readonly";
			}
			if($counter >1 && $counter%3==0)
			{
				echo '</ul>';
				echo '<ul class="ebt-grid-column-list">'; 			 
			}			 
			echo '<li> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[class_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
	    	$counter++;	  
    	}
    	echo '</ul></div>';				
    }
?>

</div>