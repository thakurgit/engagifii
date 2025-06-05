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
    (object)['colName' => 'department',        'displayName' => 'Current Department'],   
    (object)['colName' => 'terms',             'displayName' => 'Terms'],
    (object)['colName' => 'region',            'displayName' => 'Region'],
    (object)['colName' => 'status',            'displayName' => 'Status'],
    (object)['colName' => 'added',             'displayName' => 'Added'],
    (object)['colName' => 'lastUpdated',       'displayName' => 'Last Updated'],
    (object)['colName' => 'userStatus',        'displayName' => 'Invitation Status'],
    (object)['colName' => 'roles',             'displayName' => 'Roles'],
    (object)['colName' => 'lastLogin',         'displayName' => 'Last Login'],
    (object)['colName' => 'totalTimeWorked',   'displayName' => 'Total Time Worked'],    
    (object)['colName' => 'personType',        'displayName' => 'Person Type'],
    (object)['colName' => 'tags',              'displayName' => 'Tags'],
    (object)['colName' => 'action',            'displayName' => 'Action'],
    (object)['colName' => 'age',               'displayName' => 'Age'],
    (object)['colName' => 'birthdate',         'displayName' => 'Birthdate'],
];
    $nonce = wp_create_nonce('save_groups_nonce');
    $options = get_option( 'ebt_api_settings' );
    $group_member_visible_column_list = array();
    if(isset($options['group_members_settings']['visible_column_list']))
	{
	$group_member_visible_column_list = $options['group_members_settings']['visible_column_list'];   
	}
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['evt_api_url']=='' || $options['evt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';	
		} else if(is_array ($response)){
			echo '<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i><hr>'; ?>
            	<div class="cols-dropdown bdrs">
                	<button type="button" class="bdrs">Select <i class="dashicons-before dashicons-arrow-down-alt2"></i></button>
					<div class="cols-list-wrapper bdrs" style="display:none">
                    	<input type="text" class="cols-list-search bdrs" placeholder="search">
                        <ul class="ebt-grid-column-list">
    	<?php $counter=1;
		foreach ($response as $key => $row) {
			$checked = "";
			if(in_array($row->colName, $group_member_visible_column_list))
			{ 
				$checked .= " checked";
			}
			if($row->colName=='name')
			{
				$checked .= " checked readonly";
			}
			
		
			echo '<li  data-order="'.$counter.'"> <input id="'.$row->colName.'" class="" type="checkbox" name="" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
				
				$counter++;
    	}
    } 
	?>		
    </ul>
				</div>
            </div>
            <ul class="checked-cols">
            	<?php
					foreach ($response as $key => $row) {
						  if(in_array($row->colName, $group_member_visible_column_list)){ 
							echo '<li data-order="'.($key + 1).'">'.$row->displayName.'<button title="Delete Column" class="uncheck-cols"><i class="dashicons dashicons-no-alt"></i></button></li>'		;
						  }
							  
					  }
				?>
            </ul>
            <button type="button" class="btn manageColOrder">Manage Column Order</button>
            <div class="colsOrderModal" style="display:none;">
            	<div class="colsList bdrs">
                	<div class="colsListHeader">
                		<h3>Reorder Visible Columns</h3>
                        <button class="close" type="button"><i class="dashicons dashicons-no-alt"></i></button>
                    </div>
                    <hr>
                        <input type="hidden" class="cls" name="ebt_api_settings[group_members_settings][order]" value="<?php echo $options['group_members_settings']['order']; ?>" />
                    <ul class="colsListBody sortable-cols">
                    	<?php
					foreach ($response as $key => $row) {
						  if(in_array($row->colName, $group_member_visible_column_list)){ 
							echo '<li data-order="'.($key + 1).'"><input type="hidden" value="'.$row->colName.'" name="ebt_api_settings[group_members_settings][visible_column_list][]" /><span class="dashicons dashicons-sort"></span><div class="bdrs">'.$row->displayName.'</div></li>';
						  }
							  
					  }
				?>
                    </ul>
                    <hr>
                    <div class="colsListFooter">
                    	<button class="button close" type="button">Cancel</button>&nbsp;&nbsp;
                    	<button class="button button-secondary resetOrder" type="button">Reset to Default Order</button>&nbsp;&nbsp;
                    	<button class="button button-primary colsSave" type="button">Save</button>
                    </div>
                </div>
            </div>
</div>


</div>
<script>
	jQuery(document).ready(function($) {
  $('.colsList').on('click', '.colsSave', function(e) {
    e.preventDefault();

    const $button = $(this);
    const $container = $button.parent().siblings('.colsListBody');
    const originalText = $button.text();
    $button.text('Updating...').prop('disabled', true);
    const order = $container.siblings('.cls').val();
    const visibleCols = [];
    $container.find('li input').each(function() {
      visibleCols.push($(this).val());
    });
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'save_groupmember_cols',
        visible_column_list: visibleCols,
        column_order: order,
		security: '<?php echo esc_js($nonce); ?>'
      },
      success: function(response) {
        console.log(response.data?.message);
      },
      error: function() {
        alert('Error saving column settings.');
      },
      complete: function() {
        $button.text(originalText).prop('disabled', false);
      }
    });
  });
});

</script>
