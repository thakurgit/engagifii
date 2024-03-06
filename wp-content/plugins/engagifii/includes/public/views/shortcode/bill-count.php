<?php
/*$obj =  new Engagifii_API();
$sessionResponse = $obj->sessions();
$sessionResponses = json_decode($sessionResponse['api_response']);*/
$options = get_option( 'ebt_api_settings' );
   $sessionsetting = '';
  $sessionlist = array();
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
  $sessionlist = $options['lbt_visib_session_list']?? array();
 }
?>

<span id="bill-count"><span class="content-loader"></span></span>
<script type="text/javascript">
var viewAll;
var sessionId;
window.addEventListener("load", function () {
 sessionId='0';
	viewAll = $('#bill-count').siblings('a').attr('href');
	<?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		sessionId = $('.session-tab li:first-child button').attr('id');
		$('.session-tab li:first-child button').trigger('click') ;
		getCountSelected(sessionId);
		localStorage.setItem("sessionname", $('.session-tab li:first-child button').text());
		$('.session-tab li button').click(function(){
			$('#bill-count').html('<span class="content-loader"></span>');
			sessionId = $(this).attr('id');
			getCountSelected(sessionId);
			localStorage.setItem("sessionname", $(this).text());
		});
	<?php } else { ?>
	  getCountSelected(sessionId);
	  localStorage.setItem("sessionname", "");	
	<?php } ?>
	
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
        $('#bill-count').html('(Total '+response.api_response+' bills)');
		<?php if($sessionsetting==1 && count($sessionlist)>0) {?>
       	 $('#bill-count').siblings('a').attr('href',viewAll+'?sessionId='+sessionId);  
		<?php } ?>
       	   
         }
    });
}
</script>