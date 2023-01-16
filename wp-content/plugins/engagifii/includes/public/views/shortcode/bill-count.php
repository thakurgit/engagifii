<span id="bill-count"></span>
<script type="text/javascript">
var sessionId='';
var sessionTrackingUrl=[];
var viewAll;
$(document).ready(function(){
	$('.sessions-tracking a').each(function() {
			 sessionTrackingUrl.push($(this).attr('href'));
		});
		viewAll = $('#bill-count').siblings('a').attr('href');
	getCountSelected();
	$('.session-tab li button').click(function(){
		sessionId = $(this).attr('id');
		//alert(sessionId);
		getCountSelected();
		alerts();
		$('.sessions-tracking a').each(function(i) {
			
			 $(this).attr('href',sessionTrackingUrl[i]+'&sessionId='+sessionId);
			 i++;
		});
		$('#bill-count').siblings('a').attr('href',viewAll+'?sessionId='+sessionId);
		
	});
});
function alerts(){
alert();	
}
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
 $(window).on('load', function() {
     $('.session-tab li:first-child button').trigger('click') ;
	
});
</script>