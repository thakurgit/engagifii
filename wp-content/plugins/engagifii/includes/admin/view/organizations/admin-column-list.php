<?php 
// Only show organizations settings if the organization_directory module is enabled
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !engagifii_should_show_module_settings('organization_directory')) {
    return;
}
?>
<div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Organization Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $options = get_option( 'ebt_api_settings' );
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    if($options['engagifii_apis']['crmUrl']=='' || $options['dashboard_tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else{ ?>
        	<!--Org columns list-->
			<div class="cols-wrapper">
            	<h3><span class="dashicons dashicons-list-view"></span>&nbsp;&nbsp;Manage Column Visibility (List View)</h3><i>Check the columns that should be visible on the page.</i><hr>
            <?php renderColumnsUI(['organization_settings', 'list', 'visible_column_list'],'orgColumns'); ?>
                </div>
                <!--Org columns grid-->
                <div class="cols-wrapper groups-grid">
                	<h3><span class="dashicons dashicons-grid-view"></span>&nbsp;&nbsp;Manage Column Visibility (Grid View)</h3><i>Check the columns that should be visible on the page. <strong>Maximum 6 fields are allowed.</strong></i><hr>
            <?php renderColumnsUI(['organization_settings', 'grid', 'visible_column_list'],'orgColumns'); ?>
                </div>
                
                <!--Card Layout Template Selection-->
                <div class="cols-wrapper card-layout-selection" style="display:none;">
                	<h3><span class="dashicons dashicons-screenoptions"></span>&nbsp;&nbsp;Card Layout Template</h3><i>Select the card layout style for grid view.</i><hr>
                    <div class="layout-options" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px;">
                        <?php 
                        $current_layout = isset($options['organization_settings']['grid']['card_layout']) ? $options['organization_settings']['grid']['card_layout'] : 'classic';
                        $layouts = array(
                            'classic' => array(
                                'name' => 'Classic',
                                'description' => 'Traditional card with image on top and details below',
                                'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-classic.png'
                            ),
                            'modern' => array(
                                'name' => 'Modern',
                                'description' => 'Clean design with side image and horizontal layout',
                                'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-modern.png'
                            ),
                            'minimal' => array(
                                'name' => 'Minimal',
                                'description' => 'Simple card with icon and minimal details',
                                'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-minimal.png'
                            ),
                            'detailed' => array(
                                'name' => 'Detailed',
                                'description' => 'Comprehensive card with all information displayed',
                                'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-detailed.png'
                            )
                        );
                        
                        foreach ($layouts as $layout_key => $layout_info) {
                            $checked = ($current_layout === $layout_key) ? 'checked' : '';
                            ?>
                            <div class="layout-option" style="flex: 0 0 calc(50% - 10px); border: 2px solid #ddd; padding: 15px; border-radius: 8px; cursor: pointer; <?php echo $checked ? 'border-color: #0073aa; background-color: #f0f8ff;' : ''; ?>" data-layout="<?php echo $layout_key; ?>">
                                <label style="cursor: pointer; display: block;">
                                    <input type="radio" 
                                           name="ebt_api_settings[organization_settings][grid][card_layout]" 
                                           value="<?php echo $layout_key; ?>" 
                                           <?php echo $checked; ?>
                                           style="margin-right: 10px;">
                                    <strong><?php echo $layout_info['name']; ?></strong>
                                    <p style="margin: 10px 0; color: #666; font-size: 13px;"><?php echo $layout_info['description']; ?></p>
                                    <div class="layout-preview" style="background: #f9f9f9; padding: 20px; border-radius: 4px; text-align: center; min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: #999; font-size: 12px;"><?php echo $layout_info['name']; ?> Layout Preview</span>
                                    </div>
                                </label>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <script>
                    jQuery(document).ready(function($) {
                        $('.layout-option').click(function() {
                            $('.layout-option').css({'border-color': '#ddd', 'background-color': 'transparent'});
                            $(this).css({'border-color': '#0073aa', 'background-color': '#f0f8ff'});
                            $(this).find('input[type="radio"]').prop('checked', true);
                            // Show/hide cards-per-row setting based on selected layout
                            var selectedLayout = $(this).data('layout');
                            $('#org-classic-cards-per-row-wrapper').toggle(selectedLayout === 'classic');
                        });
                    });
                    </script>

                    <?php
                    $current_cards_per_row = isset($options['organization_settings']['grid']['classic_cards_per_row'])
                        ? intval($options['organization_settings']['grid']['classic_cards_per_row']) : 4;
                    $show_cards_per_row = ($current_layout === 'classic') ? 'block' : 'none';
                    ?>
                    <!--Cards Per Row (Classic layout only)-->
                    <div id="org-classic-cards-per-row-wrapper" style="display:<?php echo $show_cards_per_row; ?>; margin-top: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #bde; border-radius: 8px;">
                        <label style="font-weight: 600; font-size: 14px; display: block; margin-bottom: 8px;">
                            <span class="dashicons dashicons-grid-view" style="vertical-align: middle;"></span>&nbsp;
                            Cards Per Row (Classic View)
                        </label>
                        <p style="color: #666; font-size: 13px; margin-bottom: 10px;">Choose how many cards appear in each row for the Classic card layout.</p>
                        <select name="ebt_api_settings[organization_settings][grid][classic_cards_per_row]"
                                style="width: 120px; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc;">
                            <?php foreach ([2, 3, 4] as $n) : ?>
                                <option value="<?php echo $n; ?>" <?php selected($current_cards_per_row, $n); ?>>
                                    <?php echo $n; ?> per row
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!--Cards Per Page (Grid View)-->
                <div class="cols-wrapper">
                    <h3><span class="dashicons dashicons-screenoptions"></span>&nbsp;&nbsp;Grid View Pagination</h3><i>Choose how many cards are shown per page in grid view.</i><hr>
                    <?php
                    $current_cards_per_page = isset($options['organization_settings']['grid']['cards_per_page'])
                        ? intval($options['organization_settings']['grid']['cards_per_page']) : 8;
                    ?>
                    <div style="margin-top: 10px; padding: 15px; background: #f0f8ff; border: 1px solid #bde; border-radius: 8px;">
                        <label style="font-weight: 600; font-size: 14px; display: block; margin-bottom: 8px;">
                            <span class="dashicons dashicons-screenoptions" style="vertical-align: middle;"></span>&nbsp;
                            Cards Per Page
                        </label>
                        <p style="color: #666; font-size: 13px; margin-bottom: 10px;">Choose how many organization cards are shown per page in grid view.</p>
                        <select name="ebt_api_settings[organization_settings][grid][cards_per_page]"
                                style="width: 120px; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc;">
                            <?php foreach ([8, 12, 16, 24, 32, 64] as $n) : ?>
                                <option value="<?php echo $n; ?>" <?php selected($current_cards_per_page, $n); ?>>
                                    <?php echo $n; ?> per page
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!--Guest Field Visibility-->
                <div class="cols-wrapper guest-field-visibility">
                    <h3><span class="dashicons dashicons-visibility"></span>&nbsp;&nbsp;Guest Field Visibility (Non-Logged-in Users)</h3>
                    <i>Check the fields that should be <strong>hidden / blurred</strong> for visitors who are not logged in. Logged-in members always see the full data.</i><hr>
                    <?php
                    $guest_hidden = array_key_exists('guest_hidden_fields', $options['organization_settings'] ?? [])
                        ? ($options['organization_settings']['guest_hidden_fields'] ?? [])
                        : ['phoneNumbers', 'primaryEmail'];

                    // Build union of list + grid cols so every visible field appears here
                    $list_cols = isset($options['organization_settings']['list']['visible_column_list'])
                        ? $options['organization_settings']['list']['visible_column_list'] : [];
                    $grid_cols = isset($options['organization_settings']['grid']['visible_column_list'])
                        ? $options['organization_settings']['grid']['visible_column_list'] : [];

                    $all_cols_raw = array_merge($list_cols, $grid_cols);
                    $seen_cols    = [];
                    $all_cols     = [];
                    foreach ($all_cols_raw as $col_json) {
                        $col = json_decode(stripslashes($col_json), true);
                        if (!$col || empty($col['colName'])) continue;
                        if (strtolower($col['colName']) === 'name') continue;
                        if (in_array($col['colName'], $seen_cols)) continue;
                        $seen_cols[] = $col['colName'];
                        $all_cols[]  = $col;
                    }

                    $guest_nonce = wp_create_nonce('save_cols_nonce');

                    if (!empty($all_cols)) {
                        // Sentinel ensures the key always arrives in the POST even when no boxes are ticked
                        echo '<input type="hidden" name="ebt_api_settings[organization_settings][guest_hidden_fields_submitted]" value="1">';
                        echo '<div class="guest-fields-list" style="margin-top:12px;display:flex;flex-wrap:wrap;gap:4px 0;">';
                        foreach ($all_cols as $col) {
                            $checked = in_array($col['colName'], $guest_hidden) ? 'checked' : '';
                            $label   = !empty($col['displayName']) ? $col['displayName'] : $col['colName'];
                            echo '<label style="display:inline-flex;align-items:center;gap:6px;min-width:220px;margin:5px 15px 5px 0;font-size:13px;cursor:pointer;">'
                               . '<input type="checkbox" class="guest-field-check"'
                               . ' name="ebt_api_settings[organization_settings][guest_hidden_fields][]"'
                               . ' value="' . esc_attr($col['colName']) . '" ' . $checked . ' style="margin:0;width:15px;height:15px;">'
                               . esc_html($label)
                               . '</label>';
                        }
                        echo '</div>';
                        echo '<p style="margin-top:10px;color:#666;font-size:12px;"><em>These settings are saved together with the main <strong>Save Settings</strong> button.</em></p>';
                    } else {
                        echo '<p style="color:#666;margin-top:10px;"><em>No columns have been configured yet. Please set up List View or Grid View column visibility above first.</em></p>';
                    }
                    ?>
                </div>

    	<?php 
	}
		echo '</div>';				
?>

</div>
<?php /*?><div data-tab="settings" class="wrap event-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Organization Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $obj =  new adminDataColumn();
	   //$response = [
//         (object)['colName' => 'OrganizationName',   'displayName' => 'Organization Name'],    
//         (object)['colName' => 'Active/totalmember', 'displayName' => 'Active/Total Member'],
//         (object)['colName' => 'Location',           'displayName' => 'Location'],
//         (object)['colName' => 'organizationTags',   'displayName' => 'Tags'],
//         (object)['colName' => 'Status',             'displayName' => 'Status'],
//	 	(object)['colName' => 'added',             'displayName' => 'Added'],
//     	(object)['colName' => 'lastUpdated',       'displayName' => 'Last Updated'],
//         (object)['colName' => 'phoneNumbers',       'displayName' => 'Phone Numbers'],
//         (object)['colName' => 'OrganizationType',   'displayName' => 'Organization Type'],
//         (object)['colName' => 'Email',              'displayName' => 'Email'],
//         (object)['colName' => 'Tags',               'displayName' => 'Tags'],
//     ];
//	  $response = array_map(function($obj) {
//    return (array) $obj;
//}, $response);
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
		   if ($item['colName'] === 'TotalMembers') {
        $object->displayName = 'Total/Active Members';
    }
			  if (isset($item['fieldId'])) {
				  $object->fieldId = $item['fieldId'];
			  }
			  return $object;
		  }, $response);

		 $excludedCols = ['Id', 'IsFavorite', 'IsTenantDefault', 'TimeZone', 'LocationInfo', 'CreatedBy', 'ActiveMembers', 'ChildCount', 'isCurrent', 'childCount', 'ImageThumbUrl', 'Website', 'SecondaryEmails'];

// Filter the response
$response = array_values(array_filter($response, function ($item) use ($excludedCols) {
    return !in_array($item->colName, $excludedCols);
}));
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
<?php */?>