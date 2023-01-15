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
		legislationtagsdata();
		$('.sessions-tracking a').each(function(i) {
			 $(this).attr('href',sessionTrackingUrl[i]+'&sessionId='+sessionId);
			 i++;
		});
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
function legislationtagsdata()
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislationtagsdata'
      },
      success: function(response) {       
        //$('#bill-count').html('(Total '+response.api_response+' bills)');
            
         }
    });
}
</script>