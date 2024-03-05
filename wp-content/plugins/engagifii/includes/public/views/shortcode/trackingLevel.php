<?php

 /* $obj =  new Engagifii_API();
  $trackingResponse = $obj->getTrackingLevels();
  $trackingResponses = json_decode($trackingResponse['api_response']);*/
$options = get_option( 'ebt_api_settings' );
   $sessionsetting = '';
  $sessionlist = array();
 if(isset($options['sessionsetting'])){	 
   $sessionsetting = $options['sessionsetting'];
  $sessionlist = $options['lbt_visib_session_list']?? array();
 }
?>

<div class="mb-4 mb-md-0">
  <div class="row">
    <div class="col-sm-12 eq-height sessions-tracking position-relative" style="overflow: auto;">
    	<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:1"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>
     <?php /*?> <?php 
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
          ?><?php */?>
      </div>
    </div>
  </div>
  <script>
 window.addEventListener("load", function () {
	 <?php if($sessionsetting==1 && count($sessionlist)>0) { ?>
		getTrackingLevels(sessionId);
		$('.session-tab li button').click(function(){
			$('<div class="d-flex justify-content-center issue-loader position-absolute w-100 h-100 align-items-center" style="background:rgba(255,255,255,0.6); z-index:2"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span></div></div>').prependTo(".sessions-tracking"); 
			getTrackingLevels(sessionId);
		}); 
	 <?php } else { ?>
		getTrackingLevels(sessionId);	 
	 <?php } ?>
});	 
	 
	function getTrackingLevels(sessionId){
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
      let allZero = true;

      for (let i = 0; i < data.length; i++) {
       if (data[i].count !== 0) {
          allZero = false;
          break;
        }
      }
      var htmlValue='';
      if (allZero) {
        html += "No bill has been assigned to a tracking level for this legislative session."; // msg change
      }
			$.each(data, function(i, item) {
				if(item.count>0){
					if(sessionId==0){
						var sessionParam = '';	
					}else{
						var sessionParam = '&sessionId='+sessionId;	
					}
				html +='<a href="<?php echo get_site_url(); ?>/bill-tracking/?tracking='+item.trackingLevelId+'&'+btoa(item.title)+sessionParam+'"><div class="alert  regular d-fw mb-2 col-12 p-2" data-id="'+item.trackingLevelId+'" style="border: 2px solid '+item.colorCode+' !important;"><p class="text-dark mb-0">'+item.title+'</p></div></a>';
			}
      });			
			
        	$('.sessions-tracking').html(html);
			$(".sessions-tracking .issue-loader").remove();
            
         }
    });
}	

  </script>