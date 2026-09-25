<?php 
// Only show classes settings if the classes module is enabled
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !engagifii_should_show_module_settings('classes')) {
    return; 
}
?>
<div data-tab="settings" class="wrap class-column <?= $tab == 'settings' ? 'show' : 'hide' ?>">
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Classes Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $options = get_option( 'ebt_api_settings' );
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    if($options['ebt_api_url']=='' || $options['ebt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else{ ?>
        	<!--classes columns list-->
			<div class="cols-wrapper" style="position:relative">
            	<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page.</i><hr>
            <?php renderColumnsUI('class_visible_column_list','classesList'); ?>
                </div>
                <!--classes types-->
                <div class="cols-wrapper" style="position:relative">
                	<h3>Manage Class Type Visibility</h3><i>Check the columns that should be visible on the page.</i><hr>
            <?php renderColumnsUI('class_type_visible_column_list','classesType'); ?>
                </div>
                <?php
				$tenantCode = $options['dashboard_tenant_code'];
				if(isset($options['tLMSClasses'])){
			$tLMSClasses = $options['tLMSClasses'];
		   }else{
			   $tLMSClasses = null;
		   }
		
			$tLMS_Classes = '';
			if($tLMSClasses==1)
			{
				 $tLMS_Classes  = 'checked';
			}
			
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
				?>
                <div class="cols-wrapper" style="position:relative">
                	<h3>Manage Class Listing</h3><hr>
                    <?php if($tenantCode == 'psba'){ ?>
                    <div style=" padding-bottom:8px"> <input type="checkbox" name="ebt_api_settings[tLMSClasses]" id="tLMSClasses" value="1" <?php echo $tLMS_Classes; ?>/> <label for="tLMSClasses"><strong>Include Talent LMS Linked Classes</strong></label></div>
                    <?php } ?>
                    <div> <input type="checkbox" name="ebt_api_settings[allClasses]" id="allClasses" value="1" <?php echo $all_classes;?>/> <label for="allClasses"><strong>Show All classes</strong></label><br><i>Note: When unchecked, only upcoming classes will be displayed.</i></div>

              </div>
    	<?php 
	}
		echo '</div>';				
?>

</div>
<?php /*?><div data-tab="settings" class="wrap class-column <?= $tab == 'settings' ? 'show' : 'hide' ?>">
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Classes Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
	$date = date('Y-m-d');
    //$response = $obj->getClassColumnData();
	$options = get_option('ebt_api_settings');
   	$tenantCode = $options['dashboard_tenant_code'];
	if($tenantCode == 'psba'){
		$talentLmsObj = new stdClass();
		$talentLmsObj->colName = "talentLms";
		$talentLmsObj->displayName = "Access Class";
		$response[] = $talentLmsObj;
	}
	
	
	$classTypes = $obj->classTypes($date);	
    $class_visible_column_list = array();
    if(isset($options['class_visible_column_list'])){
    	$class_visible_column_list = $options['class_visible_column_list'];   
	}
	$class_type_visible_column_list = array();
    if(isset($options['class_type_visible_column_list']))
	{
	$class_type_visible_column_list = $options['class_type_visible_column_list'];   
	}
	$class_col_order   = isset($options['class_col_order']) ? $options['class_col_order']: array();
	$class_type_col_order   = isset($options['class_type_col_order']) ? $options['class_type_col_order']: array();
   	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['ebt_api_url']=='' || $options['ebt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else if(!is_array ($response)){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else if(is_array ($response)){
			echo '<h3>Manage Column Visibility</h3> <i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[class_col_order]" value="'.$options['class_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="classList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			$checked = "";
		if(in_array($row->colName, $class_visible_column_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='sectionname'){
 			$checked .= " checked readonly";
			}
			
			echo '<li  data-order="'.$counter.'"> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[class_visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
			$counter++;	  
    	}
    	echo '</ul>';
		echo '<h3>Manage Class Type Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[class_type_col_order]" value="'.$options['class_type_col_order'].'" /><ul class="ebt-grid-column-list " id="">';
    	//$counter=1;
		
		foreach ($classTypes as $key => $row) {
			//if(in_array($row['text'], $required_column_array)){
			//print_r($row->colName)."<br>";
			
			$checked = "";
			if(in_array($row['id'], $class_type_visible_column_list))
			{
				$checked .= " checked";
			}
			// if($row->text=='name')
			// {
			// 	$checked .= " checked readonly";
			// }
			
		
			echo '<li  data-order="'.$counter.'"> <input id="'.$row['name'].'" class="'.$row['name'].'" type="checkbox" name="ebt_api_settings[class_type_visible_column_list][]" '.$checked.' value='.$row['id'].'><label for="'.$row['name'].'">'.$row['name'].'</label></li>'		;
				
				$counter++;
		//}
    	}
		echo '</ul>';
		if(isset($options['tLMSClasses'])){
			$tLMSClasses = $options['tLMSClasses'];
		   }else{
			   $tLMSClasses = null;
		   }
		
			$tLMS_Classes = '';
			if($tLMSClasses==1)
			{
				 $tLMS_Classes  = 'checked';
			}
			
		
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
			echo '<h3>Manage Class Listing</h3><hr>';
			//echo '<div style="padding-left:7px"> <input type="checkbox" name="ebt_api_settings[tLMSClasses]" id="tLMSClasses" value="1" '.$all_classes.'/> <strong>Include Talent LMS Linked Classes</strong><br></div>';
		echo '<div style="padding-left:7px"> <input type="checkbox" name="ebt_api_settings[allClasses]" id="allClasses" value="1" '.$all_classes.'/> <strong>Show All classes</strong><br><i>Note: When unchecked, only upcoming classes will be displayed.</i></div>';

		
    }
	
		echo '</div>';				
?>

</div><?php */?>