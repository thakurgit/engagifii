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
    if (!empty($options['group_members_settings']['list']['visible_column_list'])) {
    $group_member_visible_column_list = $options['group_members_settings']['list']['visible_column_list'];
	  } else {
		  $group_member_visible_column_list = ['name'];
	  }
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['engagifii_apis']['crmUrl']=='' || $options['dashboard_tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage Group Members page settings</i></b>';	
		} else if(is_array ($response)){ 
		//render UI
		function render_group_columns_ui($context, $response, $visible_columns, $options) {
			$input_suffix = $context === 'grid' ? '_grid' : '';
			$order_value = htmlspecialchars($options['group_members_settings'][$context]['order']);
			$input_name_prefix = "ebt_api_settings[group_members_settings][$context][visible_column_list][]";
			?>
			<div class="groups-<?= $context ?>" style="<?= $context === 'grid' ? 'position:relative' : '' ?>">
				<div class="cols-dropdown bdrs">
					<button type="button" class="bdrs">Select <i class="dashicons-before dashicons-arrow-down-alt2"></i></button>
					<div class="cols-list-wrapper bdrs" style="display:none">
						<input type="text" class="cols-list-search bdrs" placeholder="search">
						<ul class="ebt-grid-column-list">
							<?php $counter = 1;
							foreach ($response as $row) {
								$checked = in_array($row->colName, $visible_columns) ? ' checked' : '';
								if ($row->colName == 'name') $checked .= ' readonly';
								echo '<li data-order="' . $counter . '">
									<input id="' . $row->colName . $input_suffix . '" type="checkbox" ' . $checked . ' value="' . $row->colName . '">
									<label for="' . $row->colName . $input_suffix . '">' . $row->displayName . '</label>
								</li>';
								$counter++;
							} ?>
						</ul>
					</div>
				</div>
		
				<ul class="checked-cols">
					<?php foreach ($response as $key => $row) {
						if (in_array($row->colName, $visible_columns)) {
							echo '<li data-order="' . ($key + 1) . '">' . $row->displayName . '<button title="Delete Column" class="uncheck-cols"><i class="dashicons dashicons-no-alt"></i></button></li>';
						}
					} ?>
				</ul>
		
				<button type="button" class="btn manageColOrder">Manage Column Order</button>
				<div class="colsOrderModal" style="display:none;">
					<div class="colsList bdrs">
						<div class="colsListHeader">
							<h3>Reorder Visible Columns</h3>
							<button class="close" type="button"><i class="dashicons dashicons-no-alt"></i></button>
						</div>
						<hr>
						<input type="hidden" class="cls" name="ebt_api_settings[group_members_settings][<?= $context ?>][order]" value="<?= $order_value ?>" />
						<ul class="colsListBody sortable-cols">
							<?php foreach ($response as $key => $row) {
								if (in_array($row->colName, $visible_columns)) {
									echo '<li data-order="' . ($key + 1) . '">
										<input type="hidden" value="' . $row->colName . '" name="' . $input_name_prefix . '" />
										<span class="dashicons dashicons-sort"></span>
										<div class="bdrs">' . $row->displayName . '</div>
									</li>';
								}
							} ?>
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
			<?php
		}
		?>
			<h3><span class="dashicons dashicons-list-view"></span>&nbsp;&nbsp;Manage Column Visibility (List View)</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for <b>list</b> view only.</i><hr>
           <?php
			  render_group_columns_ui('list', $response, $group_member_visible_column_list, $options);
			
			//grid view settings
				$group_member_visible_column_grid = array();
    if (!empty($options['group_members_settings']['grid']['visible_column_list'])) {
    $group_member_visible_column_grid = $options['group_members_settings']['grid']['visible_column_list'];
	  } else {
		  $group_member_visible_column_grid = ['name'];
	  }
			?>
			<h3><span class="dashicons dashicons-grid-view"></span>&nbsp;&nbsp;Manage Column Visibility (Grid View)</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for <strong>grid</strong> view only.</i><hr>			
           <?php
			render_group_columns_ui('grid', $response, $group_member_visible_column_grid, $options);
	 } 
			?>
</div>
</div>
<script>
	jQuery(document).ready(function($) {
  $('.colsList').on('click', '.colsSave', function(e) {
    e.preventDefault();

    const $button = $(this);
    const $container = $('.groups-list').find('.colsListBody');
    const $containerGrid = $('.groups-grid').find('.colsListBody');
    const originalText = $button.text();
    $button.text('Updating...').prop('disabled', true);
    const order = $container.siblings('.cls').val();
    const orderGrid = $containerGrid.siblings('.cls').val();
    const visibleCols = [];
    const visibleColsGrid = [];
    $container.find('li input').each(function() {
      visibleCols.push($(this).val());
    });
    $containerGrid.find('li input').each(function() {
      visibleColsGrid.push($(this).val());
    });
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'save_groupmember_cols',
        visible_column_list: visibleCols,
        column_order: order,
        visible_column_grid: visibleColsGrid,
        column_order_grid: orderGrid,
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
