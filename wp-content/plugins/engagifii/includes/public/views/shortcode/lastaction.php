<?php
    
    $obj =  new Engagifii_API();
    $lastResponse = $obj->lastAction();
    $lastResponses = json_decode($lastResponse['api_response']);
?>
<style type="text/css">
  .fixed-list{ overflow: auto;}
  .eq-height {
	max-height: 216px !important;  
  }
  @media (min-width:1200px) {
	  .eq-height {
	height: 216px !important;  
  }
}
</style>

 
  <div class="mb-4 mb-md-0">
  	<div class="row">
    
      <div class="col-sm-12">
      <select class="form-control eq-height legis-actions" size="5">

   <?php foreach($lastResponses as $last){?>
        <option class="text-break pb-1" data-title="<?php echo $last->text;?>" data-id="<?php echo $last->value;?>" onclick="filterLastAction('<?php echo $last->value; ?>')" >
        
        <?php echo $last->text;?>
        </option>
        <?php }?>
</select>
    </div>
    

  </div>
  </div>

<script type="text/javascript">
var sessionId='';
	optionhover();
	$('.session-tab li button').click(function(){
		$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6);"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').insertBefore(".legis-actions"); 
		sessionId = $(this).attr('id');
		getLegislativeActions();
	});
function getLegislativeActions()
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'legislativeactionsdata'
      },
      success: function(response) {    
	  		var data = response.api_response;
			data = JSON.parse(data);
			var html='';
			$.each(data, function(i, item) {
				var items = "'"+item.value+"'";
				 html +=' <option class="text-break pb-1" data-title="'+item.text+'" data-id="'+item.value+'" onclick="filterLastAction('+items+')">'+item.text+'</option>';			
			});
        	$('.legis-actions').html(html);
			$('.legis-actions').siblings('.issue-loader').remove();
			optionhover();
            
         }
    });
}	
    function filterLastAction(id) {
      $("body").removeClass('loaded');
	  if(sessionId==''){
    	  var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?actionType='+id;
	  } else {
   		   var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?actionType='+id+'&sessionId='+sessionId;
	  }
      window.location.href = redirect_url;

    }

    
</script>