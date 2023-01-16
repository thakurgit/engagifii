<?php

  $obj =  new Engagifii_API();
  $trackingResponse = $obj->getTrackingLevels();
  $trackingResponses = json_decode($trackingResponse['api_response']);
?>

<div class="mb-4 mb-md-0">
  <div class="row">
    <div class="col-sm-12 eq-height sessions-tracking" style="overflow: auto;">
      <?php 
        $track_count = false;
        if(count($trackingResponses)){
        foreach($trackingResponses as $tracking){
            
            if($tracking->count > 0) { 
              $track_count = true;
            ?>
                 <a href="<?php echo get_site_url(); ?>/bill-tracking/?tracking=<?php echo $tracking->trackingLevelId; ?>&<?php echo base64_encode($tracking->title); ?>">
      <div class="alert  regular d-fw mb-2 col-12 p-2" data-id="<?php echo $tracking->trackingLevelId; ?>" style="border: 2px solid <?php echo $tracking->colorCode;  ?> !important;">
            <div class="d-flex align-items-center">
              <p class="text-dark"><?php echo $tracking->title; ?></p>
            </div>
          </div></a>
            <?php
            }
          }
          }
          if($track_count === false || count($trackingResponses) == 0){
            ?>
              <div class="box border">
                <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/empty_data.png" class="img-responsive center" style="height: 213px !important">
              </div>
            <?php
          }
          ?>
      </div>
    </div>
  </div>
  <script>
 var sessionId='';
	$('.session-tab li button').click(function(){
		$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:1"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".sessions-tracking"); 
		sessionId = $(this).attr('id');
		getTrackingLevels();
	}); 
	function getTrackingLevels()
{
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
		sessionId : sessionId,
        action:'trackingleveldata'
      },
      success: function(response) {    
	  		var data = response.api_response;
			data = JSON.parse(data);
			var html='';
			$.each(data, function(i, item) {
				if(item.count>0){
				html +='<a href="<?php echo get_site_url(); ?>/bill-tracking/?tracking='+item.trackingLevelId+'&'+btoa(item.title)+'"><div class="alert  regular d-fw mb-2 col-12 p-2" data-id="'+item.trackingLevelId+'" style="border: 2px solid '+item.colorCode+' !important;"><div class="d-flex align-items-center"><p class="text-dark">'+item.title+'</p></div></div></a>';
			}
			});			
			
        	$('.sessions-tracking').html(html);
			$(".sessions-tracking .issue-loader").remove();
            
         }
    });
}	

  </script>