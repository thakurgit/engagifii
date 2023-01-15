<span id="bill-count"></span>
<script type="text/javascript">
var sessionId='';
var sessionTrackingUrl=[];
$(document).ready(function(){
	$('.sessions-tracking a').each(function() {
			 sessionTrackingUrl.push($(this).attr('href'));
		});
	getCountSelected();
	$('.session-tab li button').click(function(){
		sessionId = $(this).attr('id');
		//alert(sessionId);
		getCountSelected();
		$('.sessions-tracking a').each(function(i) {
			 $(this).attr('href',sessionTrackingUrl[i]+'&sessionId='+sessionId);
			 i++;
		});
		
		
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
            
         }
    });
}
</script>