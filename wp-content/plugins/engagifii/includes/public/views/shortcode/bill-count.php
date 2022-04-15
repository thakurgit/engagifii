<span id="bill-count"></span>
<script type="text/javascript">

$(document).ready(function(){
	getCountSelected();
})
function getCountSelected()
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
        action:'legislationfiltercountdata'
      },
      success: function(response) {      
        $('#bill-count').html('(Total '+response.api_response+' bills)');
            
         }
    });
}
</script>