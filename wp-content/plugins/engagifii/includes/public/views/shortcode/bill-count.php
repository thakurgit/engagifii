<?php
$obj =  new Engagifii_API();
$sessionResponse = $obj->sessions();
$sessionResponses = json_decode($sessionResponse['api_response']);
$sessionsetting = get_option('ebt_api_settings')['sessionsetting'];
$sessionlist = get_option('ebt_api_settings')['lbt_visib_session_list']?? array();
?>
<span id="bill-count"></span>
<script type="text/javascript">
var viewAll;
$(document).ready(function(){
var sessionId='0'; 
	viewAll = $('#bill-count').siblings('a').attr('href');
	<?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		sessionId = $('.session-tab li:first-child button').attr('id');
		$('.session-tab li button').click(function(){
			sessionId = $(this).attr('id');
			getCountSelected(sessionId);
		});
	<?php } else { ?>
	getCountSelected(sessionId);
	localStorage.setItem("sessionname", "");	
	<?php }?>
	
});
function getCountSelected(sessionId)
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislationfiltercountdata'
      },
      success: function(response) { 
	  <?php //if($tenant_url =="gsba") {?>
       // $('#bill-count').html(response.api_response);
	  <?php //} else { ?>
        $('#bill-count').html('(Total '+response.api_response+' bills)');
	  <?php //} ?>      
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