<?php
 /*$obj =  new Engagifii_API();
$tags = $obj->legislationTagsFilter();*/
$options = get_option( 'ebt_api_settings' );
  $sessionlist = $options['lbt_visib_session_list']?? array();
 $lbt_visib_legislative_list   = $options['lbt_visib_legislative_list']  ?? array();
   $sessionsetting = '';
  $sessionlist = array();
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
 }
$columns='';
 $columnNames=[];
 if (!empty($lbt_visib_legislative_list) && isArrayOfJsonStrings($lbt_visib_legislative_list)) {
		  $columns = convertToObjectArray($lbt_visib_legislative_list);
		  $columnNames = extractColNames($lbt_visib_legislative_list);
	}
 /*if(site_url() == 'https://engagifiiweb.com'){
  $legislative_tags_list             = $options['legislative_tags_list'] ?? array();

    foreach ($tags as $tag) {
      if($tag->count > 0){
        if(!in_array($tag->tagId, $legislative_tags_list)){
            array_push($legislative_tags_list, (string)$tag->tagId);
            array_push($lbt_visib_legislative_list, (string)$tag->tagId);
        }
      }
    }

    global $wpdb;
    $prepared_query = $wpdb->prepare("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'ebt_api_settings' ");
    $results = $wpdb->get_results( $prepared_query );
    $option_value = $results[0]->option_value;
    $decode_option_value = unserialize($option_value);
    if(count($legislative_tags_list) > count($decode_option_value['legislative_tags_list'])){
        $decode_option_value['legislative_tags_list'] = $legislative_tags_list;
        $decode_option_value['lbt_visib_legislative_list'] = $lbt_visib_legislative_list;
        update_option('ebt_api_settings', $decode_option_value);
    }


}
*/
/*function sort_associative_array($a, $b) {
    return strcmp(ucfirst(trim($a->text)), ucfirst(trim($b->text)));
}

usort($tags, "sort_associative_array");*/

?>

 <div class=" mb-4 mb-md-0">
  <div class="row">
      <div class="col-sm-12 ">
        <div class="list-group border eq-height legis-issues position-relative" style="overflow: auto;">
        <?php if (empty($sessionsetting)|| empty($sessionlist)) {
			$html = '';
	$site_url = get_site_url();
	//$columns = array_slice($columns, 0, 101); 
	foreach ($columns as $item) {
		$tagId = $item->colName;
		$name = $item->displayName;
		$encoded = base64_encode($name);
		$html .= '<a href="' . $site_url . '/bill-tracking/?tag=' . urlencode($tagId) . '&' . $encoded . '" class="list-group-item list-group-item-action py-1 px-2 border-0">' . htmlspecialchars($name) . '</a>';
	}
	echo !empty($html) ? $html : '<h6 class="p-3">No data found</h6>';
		} else { ?>
<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:1"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>
  <script type="text/javascript">
  var allissues = <?php echo json_encode($columnNames);  ?>;
  function toNumber1(value) {
	 return Number(value);
		}
  allissues  = allissues.map(toNumber1);
 window.addEventListener("load", function () {
		getLegislativeIssues(sessionId);
		$('.session-tab li button').click(function(){
			$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:2"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".legis-issues"); 
			getLegislativeIssues(sessionId);
		});
});
function getLegislativeIssues(sessionId){
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
				if($.inArray(item.tagId, allissues) != -1) {
					if(item.count>0){
						if(sessionId==0){
						  html += '<a href="<?php echo get_site_url(); ?>/bill-tracking/?tag='+item.tagId+'&'+btoa(item.text)+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.text+' ('+item.count+')</a>';
						}else{
						  html += '<a href="<?php echo get_site_url(); ?>/bill-tracking/?tag='+item.tagId+'&'+btoa(item.text)+'&sessionId='+sessionId+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.text+' ('+item.count+')</a>';
						}
					}
				}
			});			
			if(html){
        		$('.legis-issues').html(html);
			}else{
        		$('.legis-issues').html('<h6 class="p-3">No data found</h6>');
			}
			$('.legis-issues').siblings('.issue-loader').remove();            
         }
    });
}	

</script>
<?php } ?>
    </div>
    </div>
    </div>
  </div>

