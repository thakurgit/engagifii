<?php 
// Get enabled modules and setup status
$enabledModules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');

// Ensure $enabledModules is always an array
if (!is_array($enabledModules)) {
    $enabledModules = array();
}
?>
<div data-tab="page-settings" class="wrap eng-customizer <?php if($tab == 'page-settings'){ echo 'show';}else {echo 'hide'; }?>" >
    <!-- Header is now handled by the main admin-settings-form.php -->
<div class="engagifii-content-wrapper">
<?php 
$options = get_option( 'ebt_api_settings' );
$front_pages = $options['front_pages'];
if(isset($front_pages)){
    $classes_page = $front_pages['classes_page'] ?? '';
    $classes_detail_page = $front_pages['classes_detail_page'] ?? '';
    $bills_page = $front_pages['bills_page'] ?? '';
    $bills_detail_page = $front_pages['bills_detail_page'] ?? '';
    $events_page = $front_pages['events_page'] ?? '';
    $events_detail_page = $front_pages['events_detail_page'] ?? '';
    $events_classes_page = $front_pages['events_classes_page'] ?? '';
    $courses_page = $front_pages['courses_page'] ?? '';
    $courses_detail_page = $front_pages['courses_detail_page'] ?? '';
    $endorse_page = $front_pages['endorse_page'] ?? '';
    $endorse_detail_page = $front_pages['endorse_detail_page'] ?? '';
    $public_official_page = $front_pages['public_official_page'] ?? '';
    $public_official_detail_page = $front_pages['public_official_detail_page'] ?? '';
   }else{
   	$classes_page= $classes_detail_page =$bills_page=$bills_detail_page=$events_page=$events_detail_page=$courses_page =$courses_detail_page=$endorse_page=$endorse_detail_page=$public_official_page=$public_official_detail_page= '';
   }
function pages_list($module = '', $selected = '') {

    $html  = '<select name="ebt_api_settings[front_pages][' . esc_attr($module) . ']">';
    $html .= '<option value="">-- Select Page --</option>';

    $html .= pages_list_recursive(0, $selected);

    $html .= '</select>';

    return $html;
}

function pages_list_recursive($parent_id = 0, $selected = '', $depth = 0) {

    $args = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'parent'      => $parent_id,
        'sort_order'  => 'asc',
        'sort_column' => 'post_title',
    );

    $pages = get_pages($args);
    $html  = '';

    foreach ($pages as $page) {

        // indentation for child levels
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $depth);

        $html .= '<option value="' . esc_attr($page->ID) . '" ' .
                 selected($selected, $page->ID, false) . '>' .
                 $indent . esc_html($page->post_title) .
                 '</option>';

        // recursive call for children
        $html .= pages_list_recursive($page->ID, $selected, $depth + 1);
    }

    return $html;
}
function engagifii_create_page_button( $slug, $page_option ) {
	if(empty( $page_option )) {
		$url = wp_nonce_url(
        admin_url( 'admin-post.php?action=engagifii_create_default_page&slug=' . $slug ),
        'engagifii_create_default_page'
    	);

  	  	return ' <a class="button button-primary" href="' . esc_url( $url ) . '">Create Default</a>';	
	} else {
		return ' <a target="_blank" href="'.get_permalink($page_option).'">View</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.get_edit_post_link( $page_option, 'raw' ).'">Edit</a>';	
	}
    
} 
?>

<?php if (!$setupCompleted || !empty($enabledModules) && count($enabledModules) > 0){ ?>
<div class="engagifii-setting api-urls">
	  <?php if (!$setupCompleted || in_array('classes', $enabledModules)): ?>
          <h3>Classes</h3>
          <div class="form-group">
              <label for="">Listing page</label>
              <?php  echo pages_list('classes_page',$classes_page); 
			   echo engagifii_create_page_button( 'classes', $classes_page );?>
          </div>
          <div class="form-group">
              <label for="">Detail page</label>
               <?php  echo pages_list('classes_detail_page',$classes_detail_page); 
			    echo engagifii_create_page_button( 'class-details', $classes_detail_page );?>
          </div>
      <?php endif; ?>
      
      <?php if (!$setupCompleted || in_array('legislation', $enabledModules)): ?>
          <h3>Legislation</h3>
          <div class="form-group">
              <label for="">Listing page</label>
              <?php  echo pages_list('bills_page',$bills_page); 
			  		echo engagifii_create_page_button( 'bill-tracking', $bills_page );
			  ?>
          </div>
          <div class="form-group">
              <label for="">Detail page</label>
               <?php  echo pages_list('bills_detail_page',$bills_detail_page); 
			   echo engagifii_create_page_button( 'engagifii-detail', $bills_detail_page );
			  ?>
          </div>
          <h3>Public Official</h3>
          <div class="form-group">
              <label for="">Listing page</label>
              <?php  echo pages_list('public_official_page',$public_official_page); 
			  echo engagifii_create_page_button( 'public-official', $public_official_page );
			  ?>
          </div>
          <div class="form-group">
              <label for="">Detail page</label>
               <?php  echo pages_list('public_official_detail_page',$public_official_detail_page); 
			   echo engagifii_create_page_button( 'public-official-detail', $public_official_detail_page );
			   ?>
          </div>
      <?php endif; ?>
      
      <?php if (!$setupCompleted || in_array('events', $enabledModules)): ?>
          <h3>Events</h3>
          <div class="form-group">
              <label for="">Listing page</label>
              <?php  echo pages_list('events_page',$events_page); 
			  echo engagifii_create_page_button( 'events', $events_page );?>
          </div>
          <div class="form-group">
              <label for="">Detail page</label>
               <?php  echo pages_list('events_detail_page',$events_detail_page); 
			   echo engagifii_create_page_button( 'event-detail', $events_detail_page );?>
          </div>
          <div class="form-group">
              <label for="">Events/Classes page</label>
               <?php  echo pages_list('events_classes_page',$events_classes_page); 
			   echo engagifii_create_page_button( 'events-classes', $events_classes_page );?>
          </div>
      <?php endif; ?>
      
      <?php if (!$setupCompleted || in_array('courses', $enabledModules)): ?>
          <h3>Courses</h3>
          <div class="form-group">
              <label for="">Listing page</label>
              <?php  echo pages_list('courses_page',$courses_page); 
			  echo engagifii_create_page_button( 'courses', $courses_page );?>
          </div>
          <div class="form-group">
              <label for="">Detail page</label>
               <?php  echo pages_list('courses_detail_page',$courses_detail_page); 
			   echo engagifii_create_page_button( 'course-details', $courses_detail_page );?>
          </div>
      <?php endif; ?>
      
      <?php if (!$setupCompleted || in_array('awards', $enabledModules)): ?>
          <h3>Awards</h3>
          <div class="form-group">
              <label for="">Listing page</label>
              <?php  echo pages_list('endorse_page',$endorse_page); ?> 
          </div>
          <div class="form-group">
              <label for="">Detail page</label>
               <?php  echo pages_list('endorse_detail_page',$endorse_detail_page); ?>
          </div>
      <?php endif; ?>
      
      <?php if (!$setupCompleted || in_array('organization_directory', $enabledModules)): ?>
          <!--org pages option-->
      <?php endif; ?>
</div>

        <div>
        <?php
		 
		 if(isset($options['dt_responsive'])){
    $dt_responsive = $options['dt_responsive'];
   }else{
   	$dt_responsive = 0;
   }

    $chkd = '';
    if($dt_responsive==1)
    {
    	 $chkd  = ' checked';
    }
	
	 ?>
          <span>Enable table responsive</span>
          <span><input type="checkbox" name="ebt_api_settings[dt_responsive]" id="dt-responsive" value="1" <?php echo $chkd; ?>></span>
        </div>
         <div>
        <?php
		
		 if(isset($options['dt_darktheme'])){
    $dt_darktheme = $options['dt_darktheme'];
   }else{
   	$dt_darktheme = 0;
   }

    $dark = '';
    if($dt_darktheme==1)
    {
    	 $dark  = ' checked';
    }
	 ?>
          <span>Enable Dark theme datatable</span>
          <span><input type="checkbox" name="ebt_api_settings[dt_darktheme]" id="dt_darktheme" value="1" <?php echo $dark; ?>></span>
        </div>
<?php } else { ?>
    <div class="notice notice-warning"> 
        <p><strong>No modules are currently enabled.</strong></p>
        <p>Please go to <a href="<?php echo admin_url('admin.php?page=engagifii-settings'); ?>">Engagifii Settings</a> to enable the modules you want to use, then return here to configure page settings.</p>
    </div>
<?php } ?>
    


</div>
</div>