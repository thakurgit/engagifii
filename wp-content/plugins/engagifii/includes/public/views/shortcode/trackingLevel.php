<?php

  $obj =  new Engagifii_API();
  $trackingResponse = $obj->getTrackingLevels();
  $trackingResponses = json_decode($trackingResponse['api_response']);
?>

<div class="mb-4 mb-md-0">
  <div class="row">
    <div class="col-sm-12 eq-height" style="overflow: auto;">
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