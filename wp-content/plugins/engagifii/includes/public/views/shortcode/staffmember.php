<?php

 $obj =  new Engagifii_API();
$assignto = $obj->legislationAssignToFilter();
$groups = $obj->legislationGroupsFilter();
$assignTags = $obj->legislationAssignToTagFilter();

$options = get_option( 'ebt_api_settings' );

$lbt_visib_members_list  = $options['lbt_visib_members_list'] ?? array();
$lbt_visib_groups_list   = $options['lbt_visib_groups_list'] ?? array();
$lbt_visib_members_tags_list = $options['lbt_visib_members_tags_list'] ?? array();

if(site_url() == 'http://engagifiiweb.com')
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
}

?>


  <div class="row">
      <div class="col-sm-12">
        <?php
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
    ?>
    </div>
    </div>


  <script type="text/javascript">
  var sessionId='';
	$('.session-tab li button').click(function(){
		$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6);"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').insertBefore(".legis-members"); 
		sessionId = $(this).attr('id');
		getStaffMembers();
	});
function getStaffMembers()
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
			var allmembers = <?php echo $lbt_visib_members_list; ?>;
			$.each(data, function(i, item) {
				if(item.count>0){
					if(jQuery.inArray(item.personId, allmembers) !== -1){
				 html +='<option data-title="'+btoa(item.fullName)+'" data-type="member" data-id="'+item.personId+'" onclick="filterStaff('+item.personId+')" >'+item.fullName+' ('+item.count+')</option>';
				}}
			});			
			
        	$('.legis-members').html(html);
			$('.legis-members').siblings('.issue-loader').remove();
            
         }
    });
}	
	
    function filterStaff(id) {
      $("body").removeClass('loaded');
      var name = $('select[name="staff_member"]').find(':selected').data('title');
      var assign_type = $('select[name="staff_member"]').find(':selected').data('type');
	  if(sessionId==''){
      var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?'+assign_type+'='+id+'&'+name;
	  }else {
      var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?'+assign_type+'='+id+'&'+name+'&sessionId='+sessionId;
	  }
      window.location.href = redirect_url;

    }

    $('option').mouseover(function(){
     $(this).addClass('bg-secondary');
});
$('option').mouseout(function(){
     $(this).removeClass('bg-secondary');
});
</script>

