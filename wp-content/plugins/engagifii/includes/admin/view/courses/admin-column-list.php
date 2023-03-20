<div class="wrap course-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Courses Columns Visibility<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getCourseColumnData();
    $options = get_option( 'ebt_api_settings' );
    $course_visible_column_list = array();

    if(isset($options['course_visible_column_list'])){
    	$course_visible_column_list = $options['course_visible_column_list'];   
	}
		$course_col_order   = isset($options['course_col_order']) ? $options['course_col_order']: array();

    
    if(is_array ($response)){
    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[course_col_order]" value="'.$options['course_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="coursesList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			$checked = "";
			if(in_array($row->colName, $course_visible_column_list))
			{
				$checked .= " checked";
			}
			if($key=='name')
			{
				$checked .= " checked readonly";
			}
			if($counter >1 && $counter%3==0)
			{
				//echo '</ul>';
				//echo '<ul class="ebt-grid-column-list">'; 			 
			}			 
			echo '<li data-order="'.$counter.'"> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[course_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
	    	$counter++;	  
    	}
    	echo '</ul></div>';				
    }
?>
</div>