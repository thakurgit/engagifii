<?php
 $obj =  new Engagifii_API();
$tags = $obj->legislationTagsFilter();
$options = get_option( 'ebt_api_settings' );

$lbt_visib_tags_list   = $options['lbt_visib_tags_list']  ?? array();
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


}*/

function sort_list($a, $b) {
    return strcmp(ucfirst(trim($a->text)), ucfirst(trim($b->text)));
}

usort($tags, "sort_list");

?>

 <div class=" mb-4 mb-md-0">
  <div class="row">
      <div class="col-sm-12">
      	<select class="form-control eq-height legis-tags" name="bill_tags" size="5">

   <?php foreach($tags as $tag){
          if(in_array($tag->tagId, $lbt_visib_tags_list))
            {
        ?>
        <option class="text-break pb-1" data-title="<?php echo base64_encode($tag->text);?>" data-id="<?php echo $tag->tagId;?>" onclick="filterTag('<?php echo $tag->tagId; ?>')" >
        
       <?php  
         echo $tag->text.' ('.$tag->count.')';
      ?>
        </option>
        <?php } }?>
		</select>
    </div>
    </div>
  </div>

  <script type="text/javascript">
  var sessionId='';
	optionhover();
	$('.session-tab li button').click(function(){
		$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6);"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').insertBefore(".legis-tags"); 
		sessionId = $(this).attr('id');
		getLegislativeTags();
	});
function getLegislativeTags()
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislativeissuedata'
      },
      success: function(response) {    
	  		var data = response.api_response;
			data = JSON.parse(data);
			var html='';
			$.each(data, function(i, item) {
				if(item.count>0){
					var cevent = 'onclick="filterTag('+item.tagId+')"';
				}else {
					var cevent = '';
				}
				 html +=' <option class="text-break pb-1" data-title="'+btoa(item.text)+'" data-id="'+item.tagId+'" '+cevent+'>'+item.text+' ('+item.count+')';
			});			
			
        	$('.legis-tags').html(html);
			$('.legis-tags').siblings('.issue-loader').remove();
			optionhover();
            
         }
    });
}	
    function filterTag(id) {
      var tag = $('select[name="bill_tags"]').find(':selected').data('title');
      $("body").removeClass('loaded');
	  if(sessionId==''){
   	   var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?tag='+id+'&'+tag;
	  }else {
   	   var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?tag='+id+'&'+tag+'&sessionId='+sessionId;
	  }
      window.location.href= redirect_url;

    }
</script>