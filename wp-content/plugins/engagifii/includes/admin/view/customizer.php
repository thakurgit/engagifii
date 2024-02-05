<div class="wrap eng-customizer <?php if($tab == 'page-settings'){ echo 'show';}else {echo 'hide'; }?>" >
<div class="engagifii-setting m-tlr-20">
<?php 
$options = get_option( 'ebt_api_settings' );
$front_pages = $options['front_pages'];
if(isset($front_pages)){
    $classes_page = $front_pages['classes_page'];
    $classes_detail_page = $front_pages['classes_detail_page'];
    $bills_page = $front_pages['bills_page'];
    $bills_detail_page = $front_pages['bills_detail_page'];
    $events_page = $front_pages['events_page'];
    $events_detail_page = $front_pages['events_detail_page'];
    $courses_page = $front_pages['courses_page'];
    $courses_detail_page = $front_pages['courses_detail_page'];
    $endorse_page = $front_pages['endorse_page'];
    $endorse_detail_page = $front_pages['endorse_detail_page'];
    $public_official_page = $front_pages['public_official_page'];
    $public_official_detail_page = $front_pages['public_official_detail_page'];
   }else{
   	$classes_page= $classes_detail_page =$bills_page=$bills_detail_page=$events_page=$events_detail_page=$courses_page =$courses_detail_page=$endorse_page=$endorse_detail_page=$public_official_page=$public_official_detail_page= '';
   }
function pages_list($module='',$selected=''){
	$args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'meta_key' => '',
    'meta_value' => '',
    'authors' => '',
    'child_of' => 0,
    'parent' => 0,
    'exclude_tree' => '',
    'number' => '',
    'offset' => 0,
    'post_type' => 'page',
    'post_status' => 'publish'
); 
$pages = get_pages($args); 
  $html= '<select name="ebt_api_settings[front_pages]['.$module.']"><option value="">--Select Page--</option>';
foreach($pages as $page){ 
  $html.= ' <option '. ($selected == $page->ID ? 'selected' : '') .' value="'.$page->ID.'">'.$page->post_title.'</option>';
  $child_args = array(
    'parent' =>$page->ID, 
    'post_type'   => 'page',
    'post_status' => 'publish'
  );
  $childPages = get_pages($child_args);
  foreach($childPages as $page){
	$html.= ' <option '. ($selected == $page->ID ? 'selected' : '') .' value="'.$page->ID.'">&nbsp;&nbsp;&nbsp;--'.$page->post_title.'</option>';  
  }

}
  $html.='</select>';
  return $html;
}
?>
<div class="engagifii-setting api-urls">
	<h3>Classes</h3>
    <div class="form-group">
    	<label for="">Listing page</label>
        <?php  echo pages_list('classes_page',$classes_page); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
         <?php  echo pages_list('classes_detail_page',$classes_detail_page); ?>
    </div>
<hr>    
	<h3>Legislation</h3>
    <div class="form-group">
    	<label for="">Listing page</label>
        <?php  echo pages_list('bills_page',$bills_page); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
         <?php  echo pages_list('bills_detail_page',$bills_detail_page); ?>
    </div>
<hr>    
	<h3>Events</h3>
    <div class="form-group">
    	<label for="">Listing page</label>
        <?php  echo pages_list('events_page',$events_page); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
         <?php  echo pages_list('events_detail_page',$events_detail_page); ?>
    </div>
<hr>    
	<h3>Courses</h3>
    <div class="form-group">
    	<label for="">Listing page</label>
        <?php  echo pages_list('courses_page',$courses_page); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
         <?php  echo pages_list('courses_detail_page',$courses_detail_page); ?>
    </div>
<hr>    
	<h3>Endorsement</h3>
    <div class="form-group">
    	<label for="">Listing page</label>
        <?php  echo pages_list('endorse_page',$endorse_page); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
         <?php  echo pages_list('endorse_detail_page',$endorse_detail_page); ?>
    </div>
<hr>    
	<h3>Public Official</h3>
    <div class="form-group">
    	<label for="">Listing page</label>
        <?php  echo pages_list('public_official_page',$public_official_page); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
         <?php  echo pages_list('public_official_detail_page',$public_official_detail_page); ?>
    </div>
<hr>    
    
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

</div>
</div>