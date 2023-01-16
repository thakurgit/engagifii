<span id="bill-count"></span>
<script type="text/javascript">
var sessionId='';
var sessionTrackingUrl=[];
var viewAll;
$(document).ready(function(){
	
		viewAll = $('#bill-count').siblings('a').attr('href');
	getCountSelected();
	$('.session-tab li button').click(function(){
		sessionId = $(this).attr('id');
		//alert(sessionId);
		getCountSelected();
		$('#bill-count').siblings('a').attr('href',viewAll+'?sessionId='+sessionId);
		
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
 $(window).on('load', function() {
    // $('.session-tab li:first-child button').trigger('click') ;
	
});
</script>