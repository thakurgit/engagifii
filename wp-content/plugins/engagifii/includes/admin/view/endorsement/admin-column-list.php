<div class="wrap endorsement-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Endorsement Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getColumnData();
	if(!$response){
		echo 'est';
	}
    $options = get_option( 'ebt_api_settings' );
    $ebt_visib_datacol_list = array();
    if(isset($options['ebt_visib_datacol_list'])){
    	$ebt_visib_datacol_list = $options['ebt_visib_datacol_list'];  
	}
	$endorsement_col_order   = isset($options['endorsement_col_order']) ? $options['endorsement_col_order']: array();
    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    if($options['ebt_api_url']=='' || $options['ebt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else if(is_array ($response)){
			echo '<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>';
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[endorsement_col_order]" value="'.$options['endorsement_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="endorsementList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			$checked = "";
			if(in_array($row->colName, $ebt_visib_datacol_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='name')
			{
				$checked .= " checked readonly";
			}
					 
			echo '<li data-order="'.$counter.'"> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[ebt_visib_datacol_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
	    	$counter++;	  
    	}
    	echo '</ul>';
	}
		echo '</div>';				
?>
</div>