<div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Group Members Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
	$response = [
    (object)['colName' => 'name',              'displayName' => 'Name'],
    (object)['colName' => 'position',          'displayName' => 'Position'],
    (object)['colName' => 'organization',      'displayName' => 'Organization'],
    (object)['colName' => 'email',             'displayName' => 'Email'],
    (object)['colName' => 'phone',             'displayName' => 'Phone'],
    (object)['colName' => 'department', 'displayName' => 'Current Department'],   
    (object)['colName' => 'term',              'displayName' => 'Term'],
    (object)['colName' => 'region',            'displayName' => 'Region'],
    (object)['colName' => 'status',            'displayName' => 'Status'],
    (object)['colName' => 'added',             'displayName' => 'Added'],
    (object)['colName' => 'lastUpdated',       'displayName' => 'Last Updated'],
    (object)['colName' => 'invitationStatus',  'displayName' => 'Invitation Status'],
    (object)['colName' => 'roles',             'displayName' => 'Roles'],
    (object)['colName' => 'lastLogin',         'displayName' => 'Last Login'],
    (object)['colName' => 'totalTimeWorked',   'displayName' => 'Total Time Worked'],    
    (object)['colName' => 'personType',        'displayName' => 'Person Type'],
    (object)['colName' => 'tags',              'displayName' => 'Tags'],
    (object)['colName' => 'action',            'displayName' => 'Action'],
    (object)['colName' => 'age',               'displayName' => 'Age'],
    (object)['colName' => 'birthdate',         'displayName' => 'Birthdate'],
];
    
    $options = get_option( 'ebt_api_settings' );
	//$required_column_array = ['name', 'position', 'imageThumbUrl', 'organization', 'email', 'phone'];
	//print_r($response);
	
    $group_member_visible_column_list = array();
    if(isset($options['group_members_settings']['visible_column_list']))
	{
	$group_member_visible_column_list = $options['group_members_settings']['visible_column_list'];   
	}
	//$event_class_col_order   = isset($options['event_class_col_order']) ? $options['event_class_col_order']: array();
	//print_r(json_encode($group_member_visible_column_list));
    
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['evt_api_url']=='' || $options['evt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';	
		} else if(is_array ($response)){
			echo '<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; 
    	echo '<input type="hidden" class="cls" name="ebt_api_settings[event_class_col_order]" value="'.$options['event_class_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="eventList">';
    	$counter=1;
		foreach ($response as $key => $row) {
			//if(in_array($row->colName, $required_column_array)){
			//print_r($row->colName)."<br>";
			
			$checked = "";
			if(in_array($row->colName, $group_member_visible_column_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='name')
			{
				$checked .= " checked readonly";
			}
			
		
			echo '<li  data-order="'.$counter.'"> <input id="'.$row->colName.'" class="" type="checkbox" name="ebt_api_settings[group_members_settings][visible_column_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
				
				$counter++;
		//}
    	}
    	echo '</ul>';
		
		
    } ?>
	

</div>


</div>
<script type="text/javascript">
	function searchGroup() {
	var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchGroups");
	filter = input.value.toUpperCase();
    ul = document.getElementById("searchGroupsList");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("label")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "inline-block";
        } else {
            li[i].style.display = "none";
        }
    }
}
	</script>