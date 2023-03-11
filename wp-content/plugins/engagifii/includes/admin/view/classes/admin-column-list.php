<div class="wrap class-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Classes Columns Visibility<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getClassColumnData();
    $options = get_option( 'ebt_api_settings' );
    $class_visible_column_list = array();
    if(isset($options['class_visible_column_list'])){
    	$class_visible_column_list = $options['class_visible_column_list'];   
	}
	$class_col_order   = isset($options['class_col_order']) ? $options['class_col_order']: array();


    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['ebt_api_url']=='' || $options['ebt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please check the API URL and Tenant code if they are not left blank.</i></b>';
		} else if(!is_array ($response)){
			echo '<b style="color:red"><i>Please check the API URL and Tenant code have valid inputs.</i></b>';
		} else if(is_array ($response)){
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[class_col_order]" value="'.$options['class_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="classList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			$checked = "";

//print_r($key);

			if(in_array($row->colName, $class_visible_column_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='sectionname'){
 			$checked .= " checked readonly";
			}
			if($counter >1 && $counter%3==0)
			{
				//echo '</ul>';
				//echo '<ul class="ebt-grid-column-list">'; 			 
			}		
			//if($row->colName == "sectionname"){
			//	echo '<li> <input id="'.$row->colName.'" class="'.$row->colName.'"  type="checkbox" name="ebt_api_settings[class_visible_column_list][]" checked   value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
			//}	else{
				echo '<li  data-order="'.$counter.'"> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[class_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
			//} 
			//echo '<li> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[class_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
	    	$counter++;	  
    	}
    	echo '</ul>';
		if(isset($options['allClasses'])){
			$allClasses = $options['allClasses'];
		   }else{
			   $allClasses = null;
		   }
		
			$all_classes = '';
			if($allClasses==1)
			{
				 $all_classes  = 'checked';
			}
		echo '<div style="padding-left:7px"> <input type="checkbox" name="ebt_api_settings[allClasses]" id="allClasses" value="1" '.$all_classes.'/> <strong>Show All classes</strong><br><i>Note:- By default, only upcoming classes will be shown.</i></div>';
    }
		echo '</div>';				
?>

</div>