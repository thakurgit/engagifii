<?php
$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('legislation', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center">Legislation module is deactivated. Please contact the admin.</div>';
    return;
}
$options = get_option( 'ebt_api_settings' );
   $sessionsetting = '';
  $sessionlist = array();
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
  $sessionlist = $options['lbt_visib_session_list']?? array();
 }
?>
  <div class="mb-4 mb-md-0">
  	<div class="row">
    
      <div class="col-sm-12">
      <div class="list-group border eq-height position-relative legis-actions" style="overflow: auto;">
<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:1"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>
  
</div>
    </div>
    

  </div>
  </div>

<script type="text/javascript">
	if (typeof window.sessionId === 'undefined') {
    window.sessionId = 0;
}
 window.addEventListener("load", function () {
	<?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		getLegislativeActions(sessionId);
		$('.session-tab li button').click(function(){
		$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:2"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".legis-actions"); 
		getLegislativeActions(sessionId);
	});
	<?php } else { ?>
		getLegislativeActions(sessionId);
	<?php } ?>	 
});
function getLegislativeActions(sessionId)
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislativeactionsdata'
      },
      success: function(response) {    
	  		var data = response.api_response;
			data = JSON.parse(data);
			var html='';
			$.each(data, function(i, item) {
				var items = "'"+item.value+"'";
				if(sessionId==0){
					html +='<a href="<?php echo BILLS_PAGE_LINK; ?>?actionType='+item.value+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.text+'</a>';	
				}else{
					html +='<a href="<?php echo BILLS_PAGE_LINK; ?>?actionType='+item.value+'&sessionId='+sessionId+'" class="list-group-item list-group-item-action py-1 px-2 border-0">'+item.text+'</a>';	
				}
			});
			if(html){
        		$('.legis-actions').html(html);
			}else{
        		$('.legis-actions').html('<h6 class="p-3">No data found</h6>');
			}
			$('.legis-actions').siblings('.issue-loader').remove();
            
         }
    });
}	
</script>