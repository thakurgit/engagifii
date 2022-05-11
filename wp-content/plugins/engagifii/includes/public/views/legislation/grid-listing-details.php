<?php

header('Expires: Thu, 26 Jul 1997 00:00:01 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if(isset($_REQUEST['billId'])){
  $billId = $_GET['billId'];
}

 if(!empty($billId)){
  $api =  new Engagifii_API();


  $options = get_option( 'ebt_api_settings' );
  $lbt_api_url = $options['lbt_api_url'];
  $lbt_vsbl_tag_list = $options['lbt_visib_tags_list'];

  $tenant_url          = $options['lbt_tenant_code']['engagifii_url'];
  $title_settings      = $options['lbt_title_display_setting'];


  /* Bill Detail*/
  $billResponse = $api->getBillDetails($billId);
  $billResponses = json_decode($billResponse['api_response']);
  if($billResponses->id == 0){
    echo "<div class='engagifii-box border border-bottom-0'><div class='col-sm-12 d-lg-flex  p-2'>"._WORKSPACE_." isn't tracking this bill</div></div>";
    echo "</div></div></div>";
 
  }
  else{

  
  $bill_array = json_decode($_COOKIE['filterids'], true);
  $bill_key = array_search ($_GET['billId'], $bill_array);
  $bill_count = count($bill_array)-1;
  if($bill_key == 0){
      $prev = 0;
      $next = $bill_array[$bill_key+1];
  }
  if($bill_key == $bill_count){
     $next = 0;
     $prev = $bill_array[$bill_key-1];
  }
  if($bill_key!= $bill_count){
    $prev = $bill_array[$bill_key-1];
    $next = $bill_array[$bill_key+1];
  }


  $last = $billResponses->lastActionOn;
  $last_k = strtotime($last);
  $lastAction_new_date = date('M-d-Y',$last_k);

  $introduced = $billResponses->introducedDate;
  $last_intro = strtotime($introduced);
  $intro_new_date = date('M-d-Y',$last_intro);

  /* get tabs with sequence */
  $tabSequence = $api->legislationBillTabSequence();


  /* Version */
  $versionResponse = $api->getVersion($billId);
  $versionResponses = json_decode($versionResponse['api_response']);


  /* Action History */
  $historyResponse = $api->getActionHistory($billId);
  $historyResponses = json_decode($historyResponse['api_response']);


  /* Quick Links */
  $quicklinkResponse   = $api->getQuickLinks($billId);
  $quicklinkResponses = json_decode($quicklinkResponse['api_response']);

  
  /* Staff Analysis */
  $analysisResponse = $api->staffAnalysis($billId);
  $analysisResponses= json_decode($analysisResponse['api_response']); 

/*Public Analysis */
$publicanalysisResponse = $api->publicAnalysis($billId);
$publicanalysisResponses= json_decode($publicanalysisResponse['api_response']);



  /* Rollcall Votes */
  $voteResponse = $api->votesrollCall($billId);
  $voteResponses= json_decode($voteResponse['api_response']);

function sort_associative_array($a, $b) {
    return strcmp(ucfirst(trim($a->text)), ucfirst(trim($b->text)));
}
 

?>
<style type="text/css">
  .badge-warning{color: #fff;}
</style>
<div class="col-sm-12">
<a href="<?php echo site_url();?>/bill-tracking/" class="go-back"> Go Back </a>
</div>

<div class="engagifii-box border border-bottom-0" style="border-left:7px solid <?php echo $billResponses->trackingLevelColorCode;?> !important;">
  <div class="position-relative">
    <span style="background-color:<?php echo $billResponses->trackingLevelColorCode;?>;" class="bg-span"></span>
  <div class="col-sm-12 d-lg-flex  p-2">
        <div class="col-lg-1 pt-3">
            <img class="img-circle img-icon-lg p-0 m-auto" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/all-state-bill-icon.png';?>" alt="bill-icon">
        </div>
        <div class="col-lg-8 pt-3 pr-3 pb-3 pl-0">
          <h3 class="no-border m-auto">
            <?php

              if($title_settings == 'title'){
                  echo $billResponses->billNumber.' - '.$billResponses->title;
              }
              elseif ($title_settings == 'alternate')
              {
                if($billResponses->alternateTitle){
                  echo $billResponses->billNumber.' - '.$billResponses->alternateTitle; 
                }else{
                  echo $billResponses->billNumber.' - '.$billResponses->title; 
                }
              }
              elseif($title_settings == 'title-top')
              {
                  echo $billResponses->billNumber.' - '.$billResponses->title;
              }
              elseif($title_settings  == 'alternate-top')
              {
                  if($billResponses->alternateTitle){
                    echo $billResponses->billNumber.' - '.$billResponses->alternateTitle; 
                  }
                  else{
                    echo $billResponses->billNumber.' - '.$billResponses->title;
                  }
              }
            ?>
            <?php ?> 

          </h3>
          <?php 

            if($billResponses->alternateTitle && $title_settings == 'title-top'){
          ?>
          <div class="text-size-medium"><span class="p-1 bg-light"><?php echo $billResponses->alternateTitle; ?></span></div>
          <?php
            }
          ?>

          <?php 

            if($title_settings == 'alternate-top'){
              if($billResponses->alternateTitle){
          ?>
          <div class="text-size-medium"><span class="p-1 bg-light"><?php echo $billResponses->title; ?></span></div>
          <?php
              }
            }
          ?>
          <div class="pt-1 text-size-medium">
            <span class="pt-2 text-bold">State: </span><span><?php echo $billResponses->state;?></span><span class="pt-2 pl-2 text-bold">Last Action: </span><span><?php echo $lastAction_new_date;?> - <?php echo $billResponses->lastActionTaken;?></span>
          </div>
          <div class="pt-1 text-size-medium">
            <span class="pt-2 text-bold">Introduced Date: </span><span><?php echo $intro_new_date;?> </span><span class="pt-2 pl-2 text-bold">Status: </span><span><?php echo $billResponses->status; ?></span>
          </div>
          <div class="pt-1 text-size-medium">
            <span class="pt-2 text-bold">Session: </span><span><?php echo $billResponses->session; ?></span>
          </div>
         <?php
                if(count($billResponses->clientTags)){
                  usort($billResponses->clientTags, "sort_associative_array");
                  $countTag = 0;
                 ?> <div id="tag-order" class="pt-1 text-size-medium" style="display: flex; flex-flow: row;"> <?php
                  foreach ($billResponses->clientTags as $key => $tag) {
                    $tagMatch = $tag->tagId;
                  if (in_array($tagMatch, $lbt_vsbl_tag_list)){
                    $countTag = $countTag+1;
                     echo '<span id="blockC" style="order:3;" class="border round-tag p-2 m-1 text-capitalize"><a href="'.site_url().'/bill-tracking/?tag='.$tag->tagId.'&'.base64_encode($tag->text).'">'.$tag->text."</a></span>";
                  }
                
                    } ?>
                    
                    <span id="blockA" style="order:1; margin-top:10px;" class="pt-1 text-bold">Tag(s): </span><span id="blockB" style="order:2; margin-top:15px;" class="pl-1"><i class="fa fa-tags"></i>&nbsp;&nbsp;<?php echo $countTag; ?></span>
                      </div>
					  <?php
                }
              ?>

          
         

          <?php if(count($billResponses->clientUsers) || count($billResponses->clientUserTags) || count($billResponses->clientGroups)){ 
              $total_assign_to = (int)count($billResponses->clientUsers) + (int)count($billResponses->clientUserTags) + (int)count($billResponses->clientGroups);
            ?>
          <div class="pt-2 text-size-medium">
            <span class="pt-2 text-bold">Assign To: </span><span class="pl-1"><i class="fa fa-users"></i>&nbsp;&nbsp;<?php if($total_assign_to){ echo $total_assign_to;}else {echo '<span class="text-muted">No Member assigned</span>'; } ?></span>
              <?php
                if(count($billResponses->clientUsers)){
                  foreach ($billResponses->clientUsers as $key => $assignto) {
                   
                     echo '<span class="border round-tag p-2 m-1 text-capitalize"><a href="'.site_url().'/bill-tracking/?member='.$assignto->personId.'&'.base64_encode($assignto->firstName.' '.$assignto->lastName).'">'.$assignto->firstName.' '.$assignto->lastName."</a></span>";
                  }
                }
              ?>
              <?php if(count($billResponses->clientUserTags)){ 
                  foreach ($billResponses->clientUserTags as $key => $assignto) {
                  
                     echo '<span class="border round-tag p-2 m-1 text-capitalize"><a href="'.site_url().'/bill-tracking/?membertags='.$assignto->tag.'&'.base64_encode($assignto->tag).'">'.$assignto->tag."</a></span>";
                  }
             } ?>

              <?php if(count($billResponses->clientGroups)){ 
                  foreach ($billResponses->clientGroups as $key => $assignto) {
                    
                     echo '<span class="border round-tag p-2 m-1 text-capitalize"><a href="'.site_url().'/bill-tracking/?groups='.$assignto->id.'&'.base64_encode($assignto->name).'">'.$assignto->name."</a></span>";
                  }
             } ?>
  
          </div>
          <?php } ?>

         
        </div>
        <div class="col-12 col-lg-3 pt-3">
            
            <div class="col-sm-12 pb-2 text-right navigation-area">
              <?php
                if($prev){
              ?>
              <a class="<?php if($next){echo 'pr-2'; }?>" href="<?php echo site_url(); ?>/engagifii-detail/?billId=<?php echo $prev; ?>"><i class="fa fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a href="<?php echo site_url(); ?>/engagifii-detail/?billId=<?php echo $next; ?>"> <i class="fa fa-arrow-right"></i></a>
              <?php
                }
              ?>
            </div>
            <div class="col-sm-12 pb-2 text-lg-right">
              <span class="btn btn-danger " style="background-color: <?php echo $billResponses->trackingLevelColorCode;?>; border-color: <?php echo $billResponses->trackingLevelColorCode;?>;"> <?php echo $billResponses->trackingLevel;?> </span>
            </div>
            <div class="col-sm-12 text-right col-sm-12 text-right d-flex align-items-center justify-content-lg-end">
              
              <a class="text-underline pl-3 mt-4 download-detail order-2 " href="<?php echo $lbt_api_url;?>/file/<?php echo $billResponses->fileId;?>">Download Full Text</a>
              <img class="inline-block  mt-4" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/pdf.png';?>" alt="pdf">
            </div>
 <?php $siteURL= site_url();
              if ($siteURL == "https://engagifiiweb.com/maco"){ 
                $siteLink = $quicklinkResponses[0]->url;
                
                ?>
                <div class="col-sm-12 text-right col-sm-12 text-right d-flex align-items-center justify-content-lg-end">
                <a class="btn btn-success order-3" style="float:right; color:white; margin-top:20px;" href="<?php echo $siteLink ?>">MGA Site </a>
              </div>
                <?php
               
              } ?>
        </div>
</div>
</div>
</div>
  
                      
                      
                <div class="engagifii-box border p-2 p-lg-4 bg-light">             
                  <div class="border bg-white class-detail-main-nav">
                  <div class="tabbable  box-shadow">            
                    <ul class="nav nav-tabs detail-nav-engagiifii pl-2" role="tablist">
                            <!-- <?php
                            array_multisort(array_column($tabSequence, 'sequence'), SORT_ASC, $tabSequence);
                            if(is_array($tabSequence) && count($tabSequence)){
                              foreach ($tabSequence as $key => $tab) {
                               
                                if(($tab->name == 'Summary') || ($tab->name == 'Votes') || ($tab->name == 'Versions') || ($tab->name == 'History') || ($tab->name == 'Staff Analysis') || ($tab->name == 'MACO Analysis')){
                              
                            ?>
                                <li class="nav-item"><a class="nav-link lbt-link <?php if ($key == 0) {echo 'active';} ?>" data-toggle="tab" href="javascript:void(0)" id="<?php echo str_replace(" ", "", strtolower($tab->name)) ; ?>"><?php echo $tab->name; ?></a></li>
                            <?php
                                }
                              }
                            }
                            ?> -->
                            <?php 
                            $site = site_url();
                            if($site == 'https://engagifiiweb.com/accg') {?>
                             <li class="nav-item"><a class="nav-link lbt-link active" data-toggle="tab" href="javascript:void(0)" id="staffanalysis">ACCG Analysis</a></li>
                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="summary">State Summary</a></li>
                             
                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="versions">Versions</a></li>
                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="votes">Votes</a></li>
                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="history">History</a></li>
                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="quick">Quick Links</a></li>
                              <?php
                                }
                                elseif($site == 'https://engagifiiweb.com/baltimorecountymd') {?>
                                  <li class="nav-item"><a class="nav-link lbt-link active" data-toggle="tab" href="javascript:void(0)" id="summary">State Summary</a></li>
                                  <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="staffanalysis">Baltimore City Analysis</a></li>
                                  <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="versions">Versions</a></li>
                                  <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="votes">Votes</a></li>
                                  <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="history">History</a></li>
                                  <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="quick">Quick Links</a></li>
                                  <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="macoanalysis">MACo Analysis</a></li>
                                  <?php
                                    }
                                    elseif($site == 'https://engagifiiweb.com/princegeorgescountymd') {?>
                                      
                                      <li class="nav-item"><a class="nav-link lbt-link active" data-toggle="tab" href="javascript:void(0)" id="summary">State Summary</a></li>
                                      <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="staffanalysis">Prince Georges County Analysis</a></li>
                                      <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="versions">Versions</a></li>
                                      <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="votes">Votes</a></li>
                                      <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="history">History</a></li>
                                      <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="quick">Quick Links</a></li>
                                      <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="macoanalysis">MACo Analysis</a></li>
                                      <?php
                                        }
                                        elseif($site == 'https://engagifiiweb.com/howardcountymd') {?>
                                          
                                          <li class="nav-item"><a class="nav-link lbt-link active" data-toggle="tab" href="javascript:void(0)" id="summary">State Summary</a></li>
                                          <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="staffanalysis">Howard County Analysis</a></li>
                                          <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="versions">Versions</a></li>
                                          <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="votes">Votes</a></li>
                                          <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="history">History</a></li>
                                          <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="quick">Quick Links</a></li>
                                          <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="macoanalysis">MACo Analysis</a></li>
                                          <?php
                                            }
                                            elseif($site == 'https://engagifiiweb.com/mcmd') {?>
                                              
                                              <li class="nav-item"><a class="nav-link lbt-link active" data-toggle="tab" href="javascript:void(0)" id="summary">State Summary</a></li>
                                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="staffanalysis">Montgomery County Analysis</a></li>
                                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="versions">Versions</a></li>
                                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="votes">Votes</a></li>
                                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="history">History</a></li>
                                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="quick">Quick Links</a></li>
                                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="macoanalysis">MACo Analysis</a></li>

                                              <?php
                                                }
  
                              else
                              {
                            ?>
                            <li class="nav-item"><a class="nav-link lbt-link active" data-toggle="tab" href="javascript:void(0)" id="summary">Summary</a></li>
                              <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="staffanalysis">Staff Analysis</a></li>
                            
                            <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="versions">Versions</a></li>
                            <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="votes">Votes</a></li>
                            <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="history">History</a></li>
                            <li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="quick">Quick Links</a></li>
                            <!--li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="macoanalysis">MACo Analysis</a></li-->

                            <?php
                              }
                            ?>
                           
                        </ul>
                        <div class="tab-content p-4">
                          <div class="collapse summaryPanel"  role="tabpanel">
                              <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12 p-0 order-2 border">
                                    <div class="panel-title bg-light p-2 border-bottom">
                                      <h5 class="heading d-inline mb-0">House Committees</h5></div>
                                      
                                      <div class="col-sm-12">
                                        <?php if(!empty($billResponses->houseCommittees)){

                                          ?>
                                        <?php foreach($billResponses->houseCommittees as $houseComittee){?>
                                          <p class="summary-content-para particular-font p-2"> <?php echo $houseComittee;?> </p>
                                        <?php } } else { ?>
                                          <p class="summary-content-para no-border text-capitalize p-2" style="border-bottom:1px solid #e6e6e6 !important;"> None </p>
                                        <?php }?> 

                                      </div>


                                      <div class="panel-title bg-light p-2 border-bottom">
                                      <h5 class="heading d-inline mb-0">Senate Committees</h5></div>
                                      <div class="col-sm-12">
                                      <?php if(!empty($billResponses->senateCommittees)){?>
                                      <?php foreach($billResponses->senateCommittees as $senateCommittee){?>
                                      <p class="summary-content-para no-border p-2"> <?php echo $senateCommittee;?>  </p>
                                      <?php } } else {?>
                                        <p class="summary-content-para no-border text-capitalize p-2"> None </p>
                                        <?php }?>
                                      </div>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-12 p-0 border">
                                       <div class="panel-title bg-light p-2 border-bottom">
                                      <h5 class="heading d-inline mb-0"> <?php echo $billResponses->title;?> </h5></div>
                                      <p class="summary-content-para-engagiigii"> <?php echo $billResponses->summary;?> </p>


                                      <?php $countSponsors =  count($billResponses->sponsors); ?>
                                       <div class="panel-title bg-light px-3 py-2 border-bottom">
                                      <h5 class="heading d-inline mb-0">Sponsors (<?php echo $countSponsors;?>) </h5></div>

                                      <div class="row sponsor-list-wrapper mt-2 mb-2">



                                        <?php
                                         foreach($billResponses->sponsors as $sponser) { 

                                          ?>
                                          <div class="col-lg-4 col-md-6">
                                              
                                               <div class="p-2 d-flex">
                                                 <div class="col-xl-3 col-3 pr-0">
                                                  <?php
                                                    if($sponser->profilePic){
                                                  ?>
                                                 <span class="rounded-circle overflow-hidden d-block" style="width:44px; height:44px">
                                                 <img src="<?php echo $sponser->profilePic;?>" class="img-fluid" alt="sponsors">
                                                 </span>
												                            <?php
                                                      }
                                                      else
                                                      {
                                                    ?>
                                                         <span class="rounded-circle overflow-hidden d-block" style="width:44px; height:44px">
                                                 <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/user-default.png" class="img-fluid" alt="user">
                                                 </span>
                                                    <?php
                                                      }
                                                    ?>
                                                 </div>
                                                   <div class="col-xl-9 col-9">
                                                    <p class="title"><?php echo $sponser->name;?></p>
                                                    <?php
                                                          
                                                          if($sponser->involvedAs == 'Primary Sponsor'){
                                                    ?>
                                                    <p class="badge badge-pill badge-warning py-1 px-2 "><small>Primary</small></p>
                                                    <?php
                                                        }
                                                      
                                                    ?>
                                                      
                                                    </div>
                                                 </div> 
                                                  
                                              
                                          </div>

                                        <?php } ?>
                                          
                                          
                                        
                                      </div>
                                  </div>
                              </div>
                          
                          </div>
                          <div class="active collapse staffanalysisPanel">
                          

                          <div class="bill-detail-summary-tab staff-analysis-editor">
                              <div class="col-sm-12">

                                  <?php 

                                  if(!empty($analysisResponses)){
                                    $analysis = $analysisResponses[0];

                                  foreach($analysisResponses as $analysis){

                                    $new_Date = date('m/d/Y',strtotime($analysis->createdDate));

                                     if($analysis->createdByImage)
                                  {
                                      if (filter_var($analysis->createdByImage, FILTER_VALIDATE_URL)) { 
                                          $instructor_img = $analysis->createdByImage;
                                      }
                                      else
                                      {
                                          $instructor_img = $tenant_url.$analysis->createdByImage;
                                      }
                                      
                                  }
                                  else
                                  {
                                      $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

                                  }
                       
                                  $ip =$_SERVER['REMOTE_ADDR'];  
                                  $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
                                  $ipInfo = json_decode($ipInfo);
                                  $timezone = $ipInfo->timezone;
                                  date_default_timezone_set($timezone);
                                  $date = strtotime($analysis->createdDate.' UTC');
                                  //echo $date->format('Y-m-d h:i:s A'); 

                                  ?>
                              
                                  <div class="row p-2 border-bottom mt-2" style="margin-bottom: 40px;">
                                      <div class="col-sm-10">
                                          <a href="javascript:void(0)">
                                              <img class="img-circle img-xs mx-1 inline-block" src="<?php echo $instructor_img;?>" alt="instructor">
                                              <span class="text mx-1"><?php echo $analysis->createdBy;?></span>
                                          </a>
                                          <div class="text-muted mx-5 pt-2 pb-2"><?php echo date('m/d/Y', $date); ?> at <?php echo date('h:i A', $date); ?></div>
                                          
                                      </div>
                                      <div class="col-sm-2">
                                          <div class="p-2 m-2 text-white text-center" style="background-color:<?php echo $analysis->billPositionColor; ?>"><?php echo $analysis->billPosition; ?></div>
                                      </div>
                          
                                  <div class="col-sm-12">
                                      <div class="lead" >
                                          <div class="bill-detail-summary-content no-border mx-5" style="height: 100%;">
                                             <p class="no-margin"><?php echo $analysis->text;?></p>
                                             
                                          </div>
                                      </div>
                                  </div>

                                              <?php

                                              if(count($analysis->links) || count($analysis->files)){

                                              if(count($analysis->files))
                                              {
                                                ?>

                                                <div class="col-sm-12 panel-title p-2">
                                                  <p class="d-inline mb-0">Attachments (<?php echo count($analysis->files) + count($analysis->links); ?>)</p>
                                                </div>
                                                
                                                <?php
                                                foreach ($analysis->files as  $file) {
                                                  $file_url = $lbt_api_url.'/resource/view/'.$file->id.'/'.$file->displayName;
                                                  echo '<div class="col-4 pt-2"><i class="fa fa-file-pdf-o"></i> <a href="'.$file_url.'" target="_blank"> '. $file->displayName.'</a></div>';
                                                }
                                                
                                              }
                                              if(count($analysis->links)){
                                                  foreach ($analysis->links as  $attachment) {
                                                    echo '<div class="col-4 pt-2"><i class="fa fa-link"></i><a href="'.$attachment->url.'" target="_blank">'.$attachment->title.'</a></div>';
                                                  }
                                                }
                                              }
                                             ?>
                                </div>
                                <?php }} else {?>  
                                    <div class="bill-detail-summary-content no-border"> None</div>
                                <?php }?>   

                              </div>
                          </div>
                            </div>
                           
                            <div class="collapse versionsPanel">
                                <div class="table-responsive-sm">
                                    <table class="table table-bordered no-table-gapping-detail light-background" id="">
                                        <thead>
                                            <tr>
                                                <th>Version</th>
                                                <th>Date</th>
                                                <th class="text-center">Source</th>
                                                <th class="text-center">Download Text</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php 
                                                if(!empty($versionResponses)){
                                                foreach ($versionResponses as $version => $allVersions) { 
                                            ?>
                                            <tr class="bg-white">
                                              

                                                <td> <?php echo $allVersions->type;?> </td>
                                                <td> <?php echo $allVersions->billDraftDateTime;?> </td>
                                                <td class="text-center">
                                                  <a  class="text-underline ng-star-inserted" target="_blank" href="<?php echo $allVersions->url;?>"><?php echo $allVersions->url;?></a>
                                                </td>
                                                <td class="text-center">
                                                  <a class="text-underline" href="<?php echo $lbt_api_url;?>/file/<?php echo $billResponses->fileId;?>">Download Text</a>
                                                </td>
                                               
                                                
                                               
                                              </tr>
                                              <?php }} ?> 
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            <div class="collapse votesPanel">
                                <div class="table-responsive-sm">
                                    <table class="table table-bordered no-table-gapping-detail light-background nowrap" id="votetable">
                                        <thead>
                                            <tr>
                                                <th class="alpha-teal">Chamber</th>
                                                <th class="alpha-teal">Vote</th>
                                                <th class="alpha-teal">Date</th>
                                                <th class="text-center alpha-teal">Yea</th>
                                                <th class="text-center alpha-teal">Nay</th>
                                                <th class="text-center alpha-teal">NV</th>
                                                <th class="text-center alpha-teal">Abs</th>
                                                <th class="text-center alpha-teal">Total</th>
                                                <th class="text-center alpha-teal">Result</th>
                                                <th class="text-center alpha-teal">Source</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          

                                          <?php 
                                          if(!empty($voteResponses)){
                                          foreach($voteResponses as $vote){ ?>
                                            <tr class="bg-white">
                                                <td> <?php echo $vote->chamberType;?></td>
                                                <td> <?php echo $vote->totalVoteCount;?></td>
                                                <td> <?php echo $vote->dateOfRollCall;?></td>
                                                <td> <?php echo $vote->votesCountInFavor;?></td>
                                                <td> <?php echo $vote->votesCountAgainst;?></td>
                                                <td> <?php echo $vote->countOfNoVotes;?></td>
                                                <td> <?php echo $vote->countOfAbsentess;?></td>
                                                <td> <?php echo $vote->totalVoteCount;?></td>
                                                <td> <?php echo $vote->rollCallPassed;?></td>
                                                <td> <a href="<?php echo $vote->sourceUrl;?>" target="_blank"><?php echo $vote->sourceUrl;?></a></td>
                                            </tr>

                                          <?php }} ?>
                                            


                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            <div class="collapse historyPanel">
                                <div class="table-responsive-sm">
                                    <table class="table table-bordered no-table-gapping-detail light-background" id="historytable">
                                        <thead>
                                            <tr>
                                                <th class="alpha-teal" width="120">Date</th>
                                                <th class="alpha-teal" width="45%">Chamber</th>
                                                <th class="alpha-teal">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                          <?php
                                           if(!empty($historyResponses)){
                                           foreach ($historyResponses as $history => $histories) {
                                             $defaulget_Date = $histories->actionDate;
                                             $convert_Date = strtotime($defaulget_Date);
                                             $new_date = date('Y-m-d',$convert_Date);
                                          ?>
                                            <tr class="bg-white">
                                                <td> <?php echo $new_date;?></td>
                                                <td> <?php echo $histories->billChamberType;?> </td>
                                                <td> <?php echo $histories->actionText;?> </td>
                                            </tr>
                                          <?php }} ?> 
                                            
                                          </tbody>
                                        </table>
                                </div>
                                </div>
                                
                            <div class="collapse quickPanel">
                                    <div class="table-responsive-sm">
                                        
                                        <table class="table table-bordered light-background" id="quicklinktable">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Source</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                                  <?php 
                                                  if(!empty($quicklinkResponses)){
                                                  foreach($quicklinkResponses as $links){?>
                                                    <tr class="bg-white">
                                                      <td> <?php echo $links->type;?> </td>
                                                      <td>
                                                        <a target="_blank" href="<?php echo $links->url;?>"><?php echo $links->url;?>  </a>
                                                      </td>
                                                    </tr>
                                                <?php }} ?>

                                            </tbody>
                                        </table>
                              </div>
                            </div>
<!-- public analysis -->
<div class="collapse macoPanel">
                          

                          <div class="bill-detail-summary-tab staff-analysis-editor2">
                              <div class="col-sm-12">

                                  <?php 

                                  if(!empty($publicanalysisResponses)){
                                    $analysis = $publicanalysisResponses[0];

                                  //foreach($analysisResponses as $analysis){

                                    $new_Date = date('m/d/Y',strtotime($analysis->createdDate));

                                     if($analysis->createdByImage)
                                  {
                                      if (filter_var($analysis->createdByImage, FILTER_VALIDATE_URL)) { 
                                          $instructor_img = $analysis->createdByImage;
                                      }
                                      else
                                      {
                                          $instructor_img = $tenant_url.$analysis->createdByImage;
                                      }
                                      
                                  }
                                  else
                                  {
                                      $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

                                  }
                       
                                  $ip =$_SERVER['REMOTE_ADDR'];  
                                  $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
                                  $ipInfo = json_decode($ipInfo);
                                  $timezone = $ipInfo->timezone;
                                  date_default_timezone_set($timezone);
                                  $date = strtotime($analysis->createdDate.' UTC');
                                  //echo $date->format('Y-m-d h:i:s A'); 

                                  ?>
                              
                                  <div class="row">
                                      <div class="col-sm-10">
                                          <a href="javascript:void(0)">
                                              <img class="img-circle img-xs mx-1 inline-block" src="<?php echo $instructor_img;?>" alt="instructor">
                                              <span class="text mx-1"><?php echo $analysis->createdBy;?></span>
                                          </a>
                                          <div class="text-muted mx-5 pt-2 pb-2"><?php echo date('m/d/Y', $date); ?> at <?php echo date('h:i A', $date); ?></div>
                                          
                                      </div>
                                      <div class="col-sm-2">
                                          <div class="p-2 m-2 text-white text-center" style="background-color:<?php echo $analysis->billPositionColor; ?>"><?php echo $analysis->billPosition; ?></div>
                                      </div>
                          
                                  <div class="col-sm-12">
                                      <div class="lead" >
                                          <div class="bill-detail-summary-content no-border mx-5" style="height: 100%;">
                                             <p class="no-margin"><?php echo $analysis->text;?></p>
                                             
                                          </div>
                                      </div>
                                  </div>

                                              <?php

                                              if(count($analysis->links) || count($analysis->files)){

                                              if(count($analysis->files))
                                              {
                                                ?>

                                                <div class="col-sm-12 panel-title p-2 border-bottom mt-2">
                                                  <p class="d-inline mb-0">Attachments (<?php echo count($analysis->files) + count($analysis->links); ?>)</p>
                                                </div>
                                                
                                                <?php
                                                foreach ($analysis->files as  $file) {
                                                  $file_url = $lbt_api_url.'/resource/view/'.$file->id.'/'.$file->displayName;
                                                  echo '<div class="col-4 pt-2"><i class="fa fa-file-pdf-o"></i> <a href="'.$file_url.'" target="_blank"> '. $file->displayName.'</a></div>';
                                                }
                                                
                                              }
                                              if(count($analysis->links)){
                                                  foreach ($analysis->links as  $attachment) {
                                                    echo '<div class="col-4 pt-2"><i class="fa fa-link"></i><a href="'.$attachment->url.'" target="_blank">'.$attachment->title.'</a></div>';
                                                  }
                                                }
                                              }
                                             ?>
                                </div>
                                <?php } else {?>  
                                    <div class="bill-detail-summary-content no-border"> MACo has not provided an analysis yet.</div>
                                <?php }?>   

                              </div>
                          </div>
                            </div>
<!-- -->
                        </div>
                    </div>
                </div>
                </div>
                </div>
                

                
            </div>
        </div>
         <script type="text/javascript">
          $(document).ready(function() {
            <?php
            if(!isset($_COOKIE['filterids'])){
              //alert("hello here");

            ?>
              var site_url = '<?php echo site_url(); ?>';
              $.ajax({
                type : "post",
                url: engagifiiUrl_ajaxurl,
                data:{
                  action:'getbillids',
                },
                success: function(response) {       
                  var obj = JSON.parse(response);
                  //console.log("hello here");
                  var index = obj.indexOf(<?php echo $_GET["billId"] ?>);
                  var totat_count = obj.length;
                  totat_count = totat_count -1;
                  if(index != -1){
                      var prev = site_url+'/engagifii-detail/?billId='+obj[index-1];
                      var next = site_url+'/engagifii-detail/?billId='+obj[index+1];
                      if(index == 0)
                      {
                        var prev = '';
                      }
                      if(index == totat_count)
                      {
                        var next = '';
                      }
                      var bill_html = '<a href="'+prev+'" class="pr-2"><i class="fa fa-arrow-left"></i></a> <a href="'+next+'"><i class="fa fa-arrow-right"></i></a>';
                      $('.navigation-area').html(bill_html);
                      
                  }
                  }
              });
            <?php
              }
            ?>

              $('#versiontable').DataTable({
                "pageLength": 10,
                "dom": '<"row"<"col-sm-12 pull-left"f">>ti<"row"<"col-sm-5 pt-2"l><"col-sm-7 text-right"p>>',
                "bInfo":false,
                "processing": true,
                "searching": true,
                 "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
                "language": {
                  "emptyTable": "Nothing here yet! Visit later",
                  search:'',
                    searchPlaceholder: "Search here"
                  
                },
                "ordering":true,});

              $('#historytable').DataTable({
                "pageLength": 10,
                "dom": '<"row"<"col-sm-12 pull-left"f">>ti<"row"<"col-sm-5 pt-2"l><"col-sm-7 text-right"p>>',
                "bInfo":false,
                "processing": true,
                "searching": true,
                 "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
                "language": {
                  "emptyTable": "Nothing here yet! Visit later",
                  search:'',
                    searchPlaceholder: "Search here"
                  
                },
                "ordering":true,});

                $('#votetable').DataTable({
                "pageLength": 10,
                "dom": '<"row"<"col-sm-12 pull-left"f">><"row custom-scroll"t>i<"row"<"col-sm-5 pt-2"l><"col-sm-7 text-right"p>>',
                "bInfo":false,
                "processing": true,
                "searching": true,
                 "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
                "language": {
                  "emptyTable": "Nothing here yet! Visit later",
                  search:'',
                    searchPlaceholder: "Search here"
                  
                },
                "ordering":true,});

                $('#quicklinktable').DataTable({
                "pageLength": 10,
                "dom": '<"row"<"col-sm-12 pull-left"f">>ti<"row"<"col-sm-5 pt-2"l><"col-sm-7 text-right"p>>',
                "bInfo":false,
                "processing": true,
                "searching": true,
                 "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
                "language": {
                  "emptyTable": "Nothing here yet! Visit later",
                  search:'',
                    searchPlaceholder: "Search here"
                  
                },
                "ordering":true,});

          } );
        </script>

<?php }}?>        