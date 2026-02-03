<?php
$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('legislation', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center">Legislation module is deactivated. Please contact the admin.</div>';
    return;
}
 /*$obj =  new Engagifii_API();
$assignto = $obj->legislationAssignToFilter();
$groups = $obj->legislationGroupsFilter();
$assignTags = $obj->legislationAssignToTagFilter();

$options = get_option( 'ebt_api_settings' );

$lbt_visib_members_list  = $options['lbt_visib_members_list'] ?? array();
$lbt_visib_groups_list   = $options['lbt_visib_groups_list'] ?? array();
$lbt_visib_members_tags_list = $options['lbt_visib_members_tags_list'] ?? array();*/
$options = get_option( 'ebt_api_settings' );
$lbt_visib_members_list  = $options['lbt_visib_members_list'] ?? array();
$lbt_visib_groups_list   = $options['lbt_visib_groups_list'] ?? array();
$lbt_visib_members_tags_list = $options['lbt_visib_members_tags_list'] ?? array();
  $sessionsetting = '';
  $sessionlist = array(); 
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
  $sessionlist = $options['lbt_visib_session_list']?? array();
 }
$columns='';
$columnNames=[]; 
if (!empty($lbt_visib_members_list) && isArrayOfJsonStrings($lbt_visib_members_list)) {
		  $columns = convertToObjectArray($lbt_visib_members_list);
		  $columnNames = extractColNames($lbt_visib_members_list);
}
/*if(site_url() == 'http://engagifiiweb.com')
{
  $members_list = $options['members_list'] ?? array();
  $groups_list  = $options['groups_list'] ?? array();
  $member_tags_list = $options['member_tags_list'] ?? array();

   global $wpdb;
    $prepared_query = $wpdb->prepare("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'ebt_api_settings' ");
    $results = $wpdb->get_results( $prepared_query );
    $option_value = $results[0]->option_value;
    $decode_option_value = unserialize($option_value);
    if(count($members_list) > count($decode_option_value['members_list'])){
        $decode_option_value['members_list'] = $members_list;
        $decode_option_value['lbt_visib_members_list'] = $lbt_visib_members_list;
        update_option('ebt_api_settings', $decode_option_value);
    }
    if(count($groups_list) > count($decode_option_value['groups_list'])){
        $decode_option_value['groups_list'] = $groups_list;
        $decode_option_value['lbt_visib_groups_list'] = $lbt_visib_groups_list;
        update_option('ebt_api_settings', $decode_option_value);
    }

    if(count($member_tags_list) > count($decode_option_value['member_tags_list'])){
        $decode_option_value['member_tags_list'] = $member_tags_list;
        $decode_option_value['lbt_visib_members_tags_list'] = $lbt_visib_members_tags_list;
        update_option('ebt_api_settings', $decode_option_value);
    }
}*/

?>


  <div class="row">
      <div class="col-sm-12">
<div class="list-group border eq-height legis-members position-relative" style="overflow: auto;">
<?php if (empty($sessionsetting)|| empty($sessionlist)) {
	$html = '';
	$site_url = get_site_url();
	foreach ($columns as $item) {
		$member = $item->colName;
		$name = $item->displayName;
		$encoded = base64_encode($name);
		$html .= '<a href="' . BILLS_PAGE_LINK . '?member=' . urlencode($member) . '&' . $encoded . '" class="list-group-item list-group-item-action py-1 px-2 border-0">' . htmlspecialchars($name) . '</a>';
	}
	echo !empty($html) ? $html : '<h6 class="p-3">No data found</h6>';
 } else { ?>
<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:1"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>
  <script type="text/javascript">
  var allmembers = <?php echo json_encode( $columnNames);  ?>;
  function toNumber(value) {
	 return Number(value);
		}
  allmembers  = allmembers.map(toNumber);
  if (typeof window.sessionId === 'undefined') {
    window.sessionId = 0;
}
 window.addEventListener("load", function () {
		getStaffMembers(sessionId);
		$('.session-tab li button').click(function(){
			$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:2"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".legis-members"); 
			getStaffMembers(sessionId);
		});
});
function getStaffMembers(sessionId)
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislativestaffmembers'
      },
      success: function(response) {    
	  		var data = response.api_response;
			data = JSON.parse(data);
			var html='';
			
			$.each(data, function(i, item) {
				if($.inArray(item.personId, allmembers) != -1) {
					if(item.count>-1){
						if(sessionId==0){
						  html += '<a href="<?php echo BILLS_PAGE_LINK; ?>?member='+item.personId+'&'+btoa(item.fullName)+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.fullName+' ('+item.count+')</a>';
						}else{
						  html += '<a href="<?php echo BILLS_PAGE_LINK; ?>?member='+item.personId+'&'+btoa(item.fullName)+'&sessionId='+sessionId+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.fullName+' ('+item.count+')</a>';
						}
					}
				}
			});			
			
			if(html){
        		$('.legis-members').html(html);
			}else{
        		$('.legis-members').html('<h6 class="p-3">No data found</h6>');
			}
			$('.legis-members').siblings('.issue-loader').remove();
         }
    });
}	


   
</script>
<?php } ?>
       <?php /*?> <?php
         $site = site_url();
           if(is_array($assignto) && count($assignto) > 0){
        ?>
      	<select class="form-control eq-height legis-members" name="staff_member" size="5">

   <?php 
  
   foreach($assignto as $assign){

        if(in_array($assign->personId, $lbt_visib_members_list))
          {
    ?>
        <option data-title="<?php echo base64_encode($assign->fullName);?>" data-type="member" data-id="<?php echo $assign->personId;?>" onclick="filterStaff('<?php echo $assign->personId; ?>')" >
        
        <?php 
           echo $assign->fullName.' ('.$assign->count.')';
        ?>
        </option>
        <?php } 

        
        }?>

           <?php 
  
   foreach($groups as $group){

        if(in_array($group->value, $lbt_visib_groups_list))
          {
    ?>
        <option data-title="<?php echo base64_encode($group->text);?>" data-type="groups" data-id="<?php echo $group->value;?>" onclick="filterStaff('<?php echo $group->value; ?>')" >
        
        <?php 

          if($site == 'http://engagifiiweb.com')
              echo $group->text.' ('.$group->count.')';
          else 
            echo $group->text;
        ?>
        </option>
        <?php } 

        
        }?>

         <?php 
  
   foreach($assignTags as $assign){

        if(in_array($assign->value, $lbt_visib_members_tags_list))
          {
    ?>
        <option data-title="<?php echo base64_encode($assign->text);?>" data-type="membertags" data-id="<?php echo $assign->value;?>" onclick="filterStaff('<?php echo $assign->value; ?>')" >
        
        <?php 
        if($site == 'http://engagifiiweb.com')
          echo $assign->text.' ('.$assign->count.')';
        else
          echo $assign->text;
        ?>
        </option>
        <?php } 

        
        }?>
		</select>

    <?php
      }
      else
      {
    ?>

        <div class="box border">
        <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/empty_data.png" class="img-responsive center eq-height">
      </div>
    <?php
      }
    ?><?php */?>
    </div>
    </div>
    </div>
