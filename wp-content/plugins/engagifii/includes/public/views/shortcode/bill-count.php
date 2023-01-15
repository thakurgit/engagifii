<span id="bill-count"></span>
<script type="text/javascript">
var sessionId='';
$(document).ready(function(){
	
	getCountSelected();
	$('.session-tab li button').click(function(){
		sessionId = $(this).attr('id');
		//alert(sessionId);
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
            
         }
    });
}
</script>