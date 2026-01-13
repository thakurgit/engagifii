<?php
$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('legislation', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center">Legislation module is deactivated. Please contact the admin.</div>';
    return;
}
$options = get_option( 'ebt_api_settings' );
$lbt_visib_tags_list   = $options['lbt_visib_tags_list']  ?? array();
   $sessionsetting = '';
  $sessionlist = array();
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
  $sessionlist = $options['lbt_visib_session_list']?? array();
 }
/*if(site_url() == 'https://engagifiiweb.com'){ 
  $tags_list             = $options['tags_list'] ?? array();

    foreach ($tags as $tag) {
      if($tag->count > 0){
        if(!in_array($tag->tagId, $tags_list)){
            array_push($tags_list, (string)$tag->tagId);
            array_push($lbt_visib_tags_list, (string)$tag->tagId);
        }
      }
    }

    global $wpdb;
    $prepared_query = $wpdb->prepare("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'ebt_api_settings' ");
    $results = $wpdb->get_results( $prepared_query );
    $option_value = $results[0]->option_value;
    $decode_option_value = unserialize($option_value);
    if(count($tags_list) > count($decode_option_value['tags_list'])){
        $decode_option_value['tags_list'] = $tags_list;
        $decode_option_value['lbt_visib_tags_list'] = $lbt_visib_tags_list;
        update_option('ebt_api_settings', $decode_option_value);
    }


}

function sort_list($a, $b) {
    return strcmp(ucfirst(trim($a->text)), ucfirst(trim($b->text)));
}

usort($tags, "sort_list");
*/
?>

 <div class=" mb-4 mb-md-0">
  <div class="row">
      <div class="col-sm-12">
      <div class="list-group border eq-height legis-tags position-relative" style="overflow: auto;">
<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:1"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>

  <?php /*?> <?php foreach($tags as $tag){
          if(in_array($tag->tagId, $lbt_visib_tags_list))
            {
        ?>
        <option class="text-break pb-1" data-title="<?php echo base64_encode($tag->text);?>" data-id="<?php echo $tag->tagId;?>" onclick="filterTag('<?php echo $tag->tagId; ?>')" >
        
       <?php  
         echo $tag->text.' ('.$tag->count.')';
      ?>
        </option>
        <?php } }?><?php */?>
		</div>
    </div>
    </div>
  </div>

  <script type="text/javascript">
 window.addEventListener("load", function () {
	<?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		getLegislativeTags(sessionId);
		$('.session-tab li button').click(function(){
			$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:2"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".legis-tags"); 
			getLegislativeTags(sessionId);
		});
	<?php } else { ?>
		getLegislativeTags(sessionId);
	<?php } ?>	
});
function getLegislativeTags(sessionId){
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislativetagsdata'
      },
      success: function(response) {  
	  		var data = response;
			//data = JSON.parse(data);
			var html='';
			$.each(data, function(i, item) {
				if(sessionId==0){
					html += '<a href="<?php echo get_site_url(); ?>/bill-tracking/?tag='+item.tagId+'&'+btoa(item.text)+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.text+' ('+item.count+')</a>';
				}else{
					html += '<a href="<?php echo get_site_url(); ?>/bill-tracking/?tag='+item.tagId+'&'+btoa(item.text)+'&sessionId='+sessionId+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.text+' ('+item.count+')</a>';
				}
			});			
			
			if(html){
        		$('.legis-tags').html(html);
			}else{
        		$('.legis-tags').html('<h6 class="p-3">No data found</h6>');
			}
			$('.legis-tags').siblings('.issue-loader').remove();
			//optionhover();
            
         }
    });
}	
   /* function filterTag(id) {
      var tag = $('select[name="bill_tags"]').find(':selected').data('title');
      $("body").removeClass('loaded');
	  if(sessionId==''){
   	   var redirect_url = '<?php //echo get_site_url(); ?>/bill-tracking/?tag='+id+'&'+tag;
	  }else {
   	   var redirect_url = '<?php //echo get_site_url(); ?>/bill-tracking/?tag='+id+'&'+tag+'&sessionId='+sessionId;
	  }
      window.location.href= redirect_url;

    }*/
</script>