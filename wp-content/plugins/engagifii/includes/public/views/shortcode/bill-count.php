<?php
$obj =  new Engagifii_API();
$sessionResponse = $obj->sessions();
$sessionResponses = json_decode($sessionResponse['api_response']);
$sessionsetting = get_option('ebt_api_settings')['sessionsetting'];
$sessionlist = get_option('ebt_api_settings')['lbt_visib_session_list']?? array();
?>
<span id="bill-count"></span>
<script type="text/javascript">
var sessionId='';
var viewAll;
$(document).ready(function(){
	viewAll = $('#bill-count').siblings('a').attr('href');
	<?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		sessionId = $('.session-tab li:first-child button').attr('id');
	<?php } else { ?>
	localStorage.setItem("sessionname", "");	
	<?php }?>
	getCountSelected();
		$('.session-tab li button').click(function(){
			sessionId = $(this).attr('id');
			getCountSelected();
		});
	
});
function getCountSelected()
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislationfiltercountdata'
      },
      success: function(response) {       
        $('#bill-count').html('(Total '+response.api_response+' bills)');
		<?php if($sessionsetting==1 && count($sessionlist)>0) {?>
       	 $('#bill-count').siblings('a').attr('href',viewAll+'?sessionId='+sessionId);  
		<?php } ?>
       	   
         }
    });
}
 $(window).on('load', function() {
     $('.session-tab li:first-child button').trigger('click') ;
	
});
</script>