<div class="wrap eng-customizer <?php if($tab == 'customizer'){ echo 'show';}else {echo 'hide'; }?>" >
<div class="engagifii-setting m-tlr-20">
<?php 
function pages_list(){
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
  $html= '<select>';
foreach($pages as $page){ 
  $html.= ' <option value="'.$page->ID.'">'.$page->post_title.'</option>';
  $child_args = array(
    'parent' =>$page->ID, 
    'post_type'   => 'page',
    'post_status' => 'publish'
  );
  $childPages = get_pages($child_args);
  foreach($childPages as $page){
	$html.= ' <option value="'.$page->ID.'">&nbsp;&nbsp;&nbsp;--'.$page->post_title.'</option>';  
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
        <?php echo pages_list(); ?>
    </div>
    <div class="form-group">
    	<label for="">Detail page</label>
        <?php echo pages_list(); ?>
    </div>
<hr>    
    
</div>
        <div>
        <?php
		 $options = get_option( 'ebt_api_settings' );
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