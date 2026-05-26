<?php 
// Only show group members settings if the group_directory module is enabled
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !engagifii_should_show_module_settings('group_directory')) {
    return;
}
?>
<div data-tab="settings" class="wrap groupmembers-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Group Members Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $options = get_option( 'ebt_api_settings' );
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
    if($options['engagifii_apis']['crmUrl']=='' || $options['dashboard_tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else{ ?>
        	<!--Groups columns list-->
			<div class="cols-wrapper">
            	<h3><span class="dashicons dashicons-list-view"></span>&nbsp;&nbsp;Manage Column Visibility (List View)</h3><i>Check the columns that should be visible on the page. <strong>Max 6 Custom fields allowed.</strong></i><hr>
            <?php renderColumnsUI(['group_members_settings', 'list', 'visible_column_list'],'groupColumns'); ?>
                </div>
                <!--Groups columns grid-->
                <div class="cols-wrapper groups-grid">
                	<h3><span class="dashicons dashicons-grid-view"></span>&nbsp;&nbsp;Manage Column Visibility (Grid View)</h3><i>Check the columns that should be visible on the page. <strong>Maximum 6 fields are allowed.</strong></i><hr>
            <?php renderColumnsUI(['group_members_settings', 'grid', 'visible_column_list'],'groupColumns'); ?>
                </div>

                <!--Cards Per Row (Grid View)-->
                <div class="cols-wrapper">
                    <h3><span class="dashicons dashicons-grid-view"></span>&nbsp;&nbsp;Cards Per Row (Grid View)</h3><i>Choose how many member cards appear in each row.</i><hr>
                    <?php
                    $current_gm_cards_per_row = isset($options['group_members_settings']['grid']['classic_cards_per_row'])
                        ? intval($options['group_members_settings']['grid']['classic_cards_per_row']) : 4;
                    ?>
                    <div style="margin-top: 10px; padding: 15px; background: #f0f8ff; border: 1px solid #bde; border-radius: 8px;">
                        <label style="font-weight: 600; font-size: 14px; display: block; margin-bottom: 8px;">
                            <span class="dashicons dashicons-grid-view" style="vertical-align: middle;"></span>&nbsp;
                            Cards Per Row
                        </label>
                        <p style="color: #666; font-size: 13px; margin-bottom: 10px;">Choose how many member cards appear in each row in grid view.</p>
                        <select name="ebt_api_settings[group_members_settings][grid][classic_cards_per_row]"
                                style="width: 120px; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc;">
                            <?php foreach ([2, 3, 4] as $n) : ?>
                                <option value="<?php echo $n; ?>" <?php selected($current_gm_cards_per_row, $n); ?>>
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
                    $current_gm_cards_per_page = isset($options['group_members_settings']['grid']['cards_per_page'])
                        ? intval($options['group_members_settings']['grid']['cards_per_page']) : 12;
                    ?>
                    <div style="margin-top: 10px; padding: 15px; background: #f0f8ff; border: 1px solid #bde; border-radius: 8px;">
                        <label style="font-weight: 600; font-size: 14px; display: block; margin-bottom: 8px;">
                            <span class="dashicons dashicons-screenoptions" style="vertical-align: middle;"></span>&nbsp;
                            Cards Per Page
                        </label>
                        <p style="color: #666; font-size: 13px; margin-bottom: 10px;">Choose how many member cards are shown per page in grid view.</p>
                        <select name="ebt_api_settings[group_members_settings][grid][cards_per_page]"
                                style="width: 120px; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc;">
                            <?php foreach ([8, 12, 16, 24, 32, 64] as $n) : ?>
                                <option value="<?php echo $n; ?>" <?php selected($current_gm_cards_per_page, $n); ?>>
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
                    $gm_guest_hidden = array_key_exists('guest_hidden_fields', $options['group_members_settings'] ?? [])
                        ? ($options['group_members_settings']['guest_hidden_fields'] ?? [])
                        : ['email', 'phone'];

                    // Build union of list + grid cols
                    $gm_list_cols = isset($options['group_members_settings']['list']['visible_column_list'])
                        ? $options['group_members_settings']['list']['visible_column_list'] : [];
                    $gm_grid_cols = isset($options['group_members_settings']['grid']['visible_column_list'])
                        ? $options['group_members_settings']['grid']['visible_column_list'] : [];

                    $gm_all_cols_raw = array_merge($gm_list_cols, $gm_grid_cols);
                    $gm_seen_cols    = [];
                    $gm_all_cols     = [];
                    foreach ($gm_all_cols_raw as $col_json) {
                        $col = json_decode(stripslashes($col_json), true);
                        if (!$col || empty($col['colName'])) continue;
                        if (strtolower($col['colName']) === 'name') continue;
                        if (in_array($col['colName'], $gm_seen_cols)) continue;
                        $gm_seen_cols[] = $col['colName'];
                        $gm_all_cols[]  = $col;
                    }

                    if (!empty($gm_all_cols)) {
                        echo '<input type="hidden" name="ebt_api_settings[group_members_settings][guest_hidden_fields_submitted]" value="1">';
                        echo '<div class="guest-fields-list" style="margin-top:12px;display:flex;flex-wrap:wrap;gap:4px 0;">';
                        foreach ($gm_all_cols as $col) {
                            $checked = in_array($col['colName'], $gm_guest_hidden) ? 'checked' : '';
                            $label   = !empty($col['displayName']) ? $col['displayName'] : $col['colName'];
                            echo '<label style="display:inline-flex;align-items:center;gap:6px;min-width:220px;margin:5px 15px 5px 0;font-size:13px;cursor:pointer;">'
                               . '<input type="checkbox" class="guest-field-check"'
                               . ' name="ebt_api_settings[group_members_settings][guest_hidden_fields][]"'
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
<?php /*?><div data-tab="settings" class="wrap groupmembers-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Group Members Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $options = get_option( 'ebt_api_settings' );
	 $tenantCode = $options['dashboard_tenant_code'];
    $nonce = wp_create_nonce('save_groups_nonce');
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">';
		if($options['engagifii_apis']['crmUrl']=='' || $options['dashboard_tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage Group Members page settings</i></b>';	
		} else { 
		  $response = wp_remote_get("{$options['engagifii_apis']['crmUrl']}/PeopleColumnList/".$tenantCode, [
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
		  $withoutFieldId = [];
		  $withFieldId = [];
		  foreach ($response as $item) {
			  if (!isset($item['fieldId'])) {
				  $withoutFieldId[] = $item;
			  } else {
				  $withFieldId[] = $item;
			  }
		  }
		  $response = array_merge($withoutFieldId, $withFieldId);
		  if (!empty($response)) {
			  $response = array_map(function($item) {
			  $object = (object)[
				  'colName' => $item['colName'],
				  'displayName' => $item['displayName'],
				  'controlTypeId' => $item['controlTypeId'] ?? 3
			  ];
		  
			  if (isset($item['fieldId'])) {
				  $object->fieldId = $item['fieldId'];
			  }
			  return $object;
		  }, $response);
		//render UI
		function render_group_columns_ui($context, $response, $visible_columns, $options) {
			$visible_columns = array_map(function ($json) {
				$decoded = json_decode(stripslashes($json), true); 
				return $decoded['colName'] ?? null;
			}, $visible_columns);
			$input_suffix = $context === 'grid' ? '_grid' : '';
			$order_value = htmlspecialchars($options['group_members_settings'][$context]['order']);
			$input_name_prefix = "ebt_api_settings[group_members_settings][$context][visible_column_list][]";
			?>
			<div class="groups-<?= $context ?>" style="<?= $context === 'grid' ? 'position:relative' : '' ?>">
				<div class="cols-dropdown bdrs" data-option="group_members_settings">
					<button type="button" class="bdrs">Select <i class="dashicons-before dashicons-arrow-down-alt2"></i></button>
					<div class="cols-list-wrapper bdrs" style="display:none">
						<input type="text" class="cols-list-search bdrs" placeholder="search">
						<ul class="<?= $context === 'list' ? 'ebt-grid-column-list' : '' ?>">
							<?php $counter = 1;
							$counter = 1;
							$custom_fields_separator_inserted = false;
							foreach ($response as $row) {
								//if (isset($row->fieldId)) { // Custom field
								$hasFieldId = !empty($row->fieldId);
								if ($hasFieldId && !$custom_fields_separator_inserted) {
									echo '</ul><ul class="custom-fields-list"><span class="custom-fields-separator">
											<strong style="color: #666; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; display: block; text-align: Left;">Custom Fields (Max 5 fields allowed)</strong></span>';
									$custom_fields_separator_inserted = true;
								}
									$checked = in_array($row->colName, $visible_columns) ? ' checked' : '';
									if ($row->colName == 'name') $checked .= ' readonly';
									
									$value_data = [
										'colName' => $row->colName,
										'displayName' => $row->displayName
									];
									 $customField = ''; 
									if (!empty($row->fieldId)) {
										$value_data['fieldId'] = $row->fieldId;
										$customField = '<span class="cfield">Custom Field</span>';
									}
									
									if (!empty($row->controlTypeId)) {
										$value_data['controlTypeId'] = $row->controlTypeId;
									}
									
									$input_value = htmlspecialchars(json_encode($value_data), ENT_QUOTES, 'UTF-8');
									echo '<li data-order="' . $counter . '">
										<input id="' . $row->colName . $input_suffix . '" type="checkbox" ' . $checked . ' value=\'' . $input_value . '\'>
										<label for="' . $row->colName . $input_suffix . '">' . $row->displayName . '</label>
										'.$customField.'
									</li>';
									$counter++;
							} ?>
						</ul>
					</div>
				</div>
		
			<ul class="checked-cols">
    <?php foreach ($response as $key => $row) {
        if (in_array($row->colName, $visible_columns)) {
            if ($row->colName === 'name') {
                echo '<li data-order="' . ($key + 1) . '">' . $row->displayName . '<button title="Name column cannot be deleted" class="uncheck-cols" disabled style="opacity: 0.5;"><i class="dashicons dashicons-no-alt"></i></button></li>';
            } else {
                echo '<li data-order="' . ($key + 1) . '">' . $row->displayName . '<button title="Delete Column" class="uncheck-cols"><i class="dashicons dashicons-no-alt"></i></button></li>';
            }
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
							<button class="button button-primary colsSave" type="button">Save</button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		//list view settings
		$group_member_visible_column_list = array();
		if (!empty($options['group_members_settings']['list']['visible_column_list'])) {
		$group_member_visible_column_list = $options['group_members_settings']['list']['visible_column_list'];
		  } else {
			  $group_member_visible_column_list = ['{"colName":"name","displayName":"Name"}'];
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
		  $group_member_visible_column_grid = ['{"colName":"name","displayName":"Name"}'];
	  }
			?>
			<h3><span class="dashicons dashicons-grid-view"></span>&nbsp;&nbsp;Manage Column Visibility (Grid View)</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for <strong>grid</strong> view only. <strong>Maximum 6 fields are allowed.</strong></i><hr>			
           <?php
			render_group_columns_ui('grid', $response, $group_member_visible_column_grid, $options);
	 } else {
		echo '<b style="color:red"><i>Columns not found!</i></b>'; 
	 }
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
      visibleCols.push(decodeHtmlEntities($(this).val()));
    });
    $containerGrid.find('li input').each(function() {
      visibleColsGrid.push(decodeHtmlEntities($(this).val()));
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

</script><?php */?>
