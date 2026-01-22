<?php
$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('legislation', $enabled_modules)) {
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

<a href="<?php echo BILLS_PAGE_LINK;?>">View All Tracked Bills</a> <span id="bill-count"><span class="content-loader"></span></span> 
<script type="text/javascript">
var viewAll;
if (typeof window.sessionId === 'undefined') {
    window.sessionId = 0;
}
window.addEventListener("load", function () {
	viewAll = $('#bill-count').siblings('a').attr('href');
	<?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		getCountSelected(sessionId);
		$('.session-tab li button').click(function(){
			$('#bill-count').html('<span class="content-loader"></span>');
			getCountSelected(sessionId);
		});
	<?php } else { ?>
	  getCountSelected(sessionId);
	<?php } ?> 
	
});
function getCountSelected(sessionId)
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : (!isNaN(sessionId) && sessionId !== '' && sessionId !== null) ? sessionId : 0,
        action:'legislationfiltercountdata'
      },
      success: function(response) { 
        $('#bill-count').html('(Total '+response.api_response+' bills)');
		<?php if($sessionsetting==1 && count($sessionlist)>0) {?>
		if($('.session-tab li').length>0){
       	 $('#bill-count').siblings('a').attr('href',viewAll+'?sessionId='+sessionId);  
		}
		<?php } ?>
       	   
         }
    });
}
</script>