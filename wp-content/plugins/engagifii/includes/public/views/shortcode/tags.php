<?php
$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('legislation', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center">Legislation module is deactivated. Please contact the admin.</div>';
    return;
}
$options = get_option( 'ebt_api_settings' );
  $sessionlist = $options['lbt_visib_session_list']?? array();
 $lbt_visib_legislative_list   = $options['lbt_visib_legislative_list']  ?? array();
   $sessionsetting = '';
  $sessionlist = array();
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
  $sessionlist = $options['lbt_visib_session_list']?? array();
 }
$columns='';
 $columnNames=[];
 if (!empty($lbt_visib_legislative_list) && isArrayOfJsonStrings($lbt_visib_legislative_list)) {
		  $columns = convertToObjectArray($lbt_visib_legislative_list);
		  $columnNames = extractColNames($lbt_visib_legislative_list);
	}

?>
 <div class=" mb-4 mb-md-0">
  <div class="row">
      <div class="col-sm-12 ">
        <div class="list-group border eq-height legis-tags position-relative" style="overflow: auto;">
        <?php if (empty($sessionsetting)|| empty($sessionlist)) {
			$html = '';
	$site_url = get_site_url();
	//$columns = array_slice($columns, 0, 101); 
	if (!empty($columns) && is_array($columns)) {
		foreach ($columns as $item) {
			$tagId = $item->colName;
			$name = $item->displayName;
			$encoded = base64_encode($name);
			$html .= '<a href="' . $site_url . '/bill-tracking/?tag=' . urlencode($tagId) . '&' . $encoded . '" class="list-group-item list-group-item-action py-1 px-2 border-0">' . htmlspecialchars($name) . '</a>';
		}
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
		getLegislativeTags(sessionId);
		$('.session-tab li button').click(function(){
			$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:2"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".legis-tags"); 
			getLegislativeTags(sessionId);
		});
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
        		$('.legis-tags').html(html);
			}else{
        		$('.legis-tags').html('<h6 class="p-3">No data found</h6>');
			}
			$('.legis-tags').siblings('.issue-loader').remove();            
         }
    });
}	

</script>
<?php } ?>
    </div>
    </div>
    </div>
  </div>
