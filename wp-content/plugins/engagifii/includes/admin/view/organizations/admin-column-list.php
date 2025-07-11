<div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Organization Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
	//   $response = [
    //     (object)['colName' => 'OrganizationName',   'displayName' => 'Organization Name'],    
    //     (object)['colName' => 'Active/totalmember', 'displayName' => 'Active/Total Member'],
    //     (object)['colName' => 'Location',           'displayName' => 'Location'],
    //     (object)['colName' => 'organizationTags',   'displayName' => 'Tags'],
    //     (object)['colName' => 'Status',             'displayName' => 'Status'],
	// 	(object)['colName' => 'added',             'displayName' => 'Added'],
    // 	(object)['colName' => 'lastUpdated',       'displayName' => 'Last Updated'],
    //     (object)['colName' => 'phoneNumbers',       'displayName' => 'Phone Numbers'],
    //     (object)['colName' => 'OrganizationType',   'displayName' => 'Organization Type'],
    //     (object)['colName' => 'Email',              'displayName' => 'Email'],
    //     (object)['colName' => 'Tags',               'displayName' => 'Tags'],
    // ];
    $nonce = wp_create_nonce('save_org_nonce');
    $options = get_option( 'ebt_api_settings' );
	$tenantCode = $options['dashboard_tenant_code'];
	//print_r(json_encode($options));
    $organization_visible_column_list = array();
    if (!empty($options['organization_settings']['list']['visible_column_list'])) {
    $organization_visible_column_list = $options['organization_settings']['list']['visible_column_list'];
	  } else {
		  $organization_visible_column_list = ['{"colName":"name","displayName":"Name"}'];
	  }
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['engagifii_apis']['crmUrl']=='' || $options['dashboard_tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage Organization page settings</i></b>';	
		} else { 
		  $response = wp_remote_get("{$options['engagifii_apis']['crmUrl']}/OrganizationColumnList/".$tenantCode, [
		  'headers' => [
			'accept'        => 'application/json',
			'tenant-code'   => $options['dashboard_tenant_code'],
		  ]
		]);
		   if (is_wp_error($response)) {
			echo '<div class="error">API Response not found!</div>';
		  }
		  $body = stripslashes(wp_remote_retrieve_body($response));
		  $response  = json_decode($body, true) ?? [];
		  if (!empty($response)) {
			  $response = array_map(function($item) {
			  $object = (object)[
				  'colName' => $item['colName'],
				  'displayName' => $item['displayName']
			  ];
		  
			  if (isset($item['fieldId'])) {
				  $object->fieldId = $item['fieldId'];
			  }
			  return $object;
		  }, $response);
		//render UI
		function render_org_columns_ui($context, $response, $visible_columns, $options) {
			$visible_columns = array_map(function ($json) {
				$decoded = json_decode(stripslashes($json), true); 
				return $decoded['colName'] ?? null;
			}, $visible_columns);
			$input_suffix = $context === 'grid' ? '_grid' : '';
			$order_value = htmlspecialchars($options['organization_settings'][$context]['order']);
			$input_name_prefix = "ebt_api_settings[organization_settings][$context][visible_column_list][]";
			?>
			<div class="org-<?= $context ?>" style="<?= $context === 'grid' ? 'position:relative' : '' ?>">
				<div class="cols-dropdown bdrs" data-option ="organization_settings">
					<button type="button" class="bdrs">Select <i class="dashicons-before dashicons-arrow-down-alt2"></i></button>
					<div class="cols-list-wrapper bdrs" style="display:none">
						<input type="text" class="cols-list-search bdrs" placeholder="search">
						<ul class="<?= $context === 'list' ? 'ebt-grid-column-list' : '' ?>">
							<?php $counter = 1;
							foreach ($response as $row) {
								$checked = in_array($row->colName, $visible_columns) ? ' checked' : '';
								if ($row->colName == 'Name') $checked .= ' readonly';
								$value_data = [
									'colName' => $row->colName,
									'displayName' => $row->displayName
								];
								
								$input_value = htmlspecialchars(json_encode($value_data), ENT_QUOTES, 'UTF-8');
								echo '<li data-order="' . $counter . '">
									<input id="' . $row->colName . $input_suffix . '" type="checkbox" ' . $checked . ' value=\'' . $input_value . '\'>
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
						<input type="hidden" class="cls" name="ebt_api_settings[organization_settings][<?= $context ?>][order]" value="<?= $order_value ?>" />
						<ul class="colsListBody sortable-cols">
							<?php foreach ($response as $key => $row) {
									if (in_array($row->colName, $visible_columns)) {
									$value_data = [
									'colName' => $row->colName,
									'displayName' => $row->displayName
								];
								if (isset($row->fieldId)) {
									$value_data['fieldId'] = $row->fieldId;
								}
								$input_value = htmlspecialchars(json_encode($value_data), ENT_QUOTES, 'UTF-8');
									echo '<li data-order="' . ($key + 1) . '">
										<input type="hidden" value="' . $input_value . '" name="' . $input_name_prefix . '" />
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
							<button class="button button-primary colsSaveOrg" type="button">Save</button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
			<h3><span class="dashicons dashicons-list-view"></span>&nbsp;&nbsp;Manage Column Visibility (List View)</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for <b>list</b> view only.</i><hr>
           <?php
			  render_org_columns_ui('list', $response, $organization_visible_column_list, $options);
			
			//grid view settings
				$organization_visible_column_grid = array();
    if (!empty($options['organization_settings']['grid']['visible_column_list'])) {
    $organization_visible_column_grid = $options['organization_settings']['grid']['visible_column_list'];
	  } else {
		  $organization_visible_column_grid = ['{"colName":"name","displayName":"Name"}'];
	  }
			?>
			<h3><span class="dashicons dashicons-grid-view"></span>&nbsp;&nbsp;Manage Column Visibility (Grid View)</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for <strong>grid</strong> view only. <strong>Maximum 6 fields are allowed.</strong></i><hr>			
           <?php
			render_org_columns_ui('grid', $response, $organization_visible_column_grid, $options);
	 } }
			?>
</div>
</div>
<script>
	jQuery(document).ready(function($) {
  $('.colsList').on('click', '.colsSaveOrg', function(e) {
    e.preventDefault();

    const $button = $(this);
    const $container = $('.org-list').find('.colsListBody');
    const $containerGrid = $('.org-grid').find('.colsListBody');
    const originalText = $button.text();
    $button.text('Updating...').prop('disabled', true);
    const order = $container.siblings('.cls').val();
    const orderGrid = $containerGrid.siblings('.cls').val();
    const visibleCols = [];
    const visibleColsGrid = [];
    $container.find('li input').each(function() {      
	   visibleCols.push(decodeHtmlEntities($(this).val()));
    });
    $containerGrid.find('li input').each(function() {
      visibleColsGrid.push(decodeHtmlEntities($(this).val()));
    });
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'save_organization_cols',
        visible_column_list: visibleCols,
        column_order: order,
        visible_column_grid: visibleColsGrid,
        column_order_grid: orderGrid,
		security: '<?php echo esc_js($nonce); ?>'
      },
      success: function(response) {
         console.log(response.data?.message);
		$('<p style="color:var(--e-context-success-color)"><strong><em>Columns saved successfully!</em></strong></p>').insertAfter($button);
        setTimeout(() => {
            $button.siblings('p').remove();
        }, 2000);
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
