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
  if($lbt_vsbl_tag_list==null){
  	$lbt_vsbl_tag_list = array();
  }

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
  $lastAction_new_date = date('M d, Y',$last_k);

  $introduced = $billResponses->introducedDate;
  $last_intro = strtotime($introduced);
  $intro_new_date = date('M d, Y',$last_intro); 

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
 
$siteURL= site_url();
?>
<div class="bill-detail-page">
<div class="mb-2 d-flex align-items-center justify-content-between">
<a href="<?php echo site_url();?>/bill-tracking/" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<div class="d-flex align-items-center">
	<small><strong>Share:</strong></small>
    <a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $siteURL; ?>/engagifii-detail/?billId=<?php echo $billId; ?>&title=<?php echo $billResponses->billNumber.' - '.$billResponses->title; ?>" class="ml-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:25px; height:25px" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;" target="_blank" rel="noopener" data-share-network="Facebook" data-share-action="Share" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>

    <a href="http://twitter.com/intent/tweet?text=<?php echo $billResponses->billNumber.' - '.$billResponses->title; ?>+<?php echo $siteURL; ?>/engagifii-detail/?billId=<?php echo $billId; ?>&via=<?php echo get_bloginfo( 'name' ); ?>"  class="ml-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:25px; height:25px" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;"  target="_blank" rel="noopener" data-share-network="Twitter" data-share-action="Share" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
    <small class="ml-4"><strong>Views:</strong>&nbsp;<span></span></small>
</div>
</div>

<div class="engagifii-box border border-bottom-0" style="border-left:7px solid <?php echo $billResponses->trackingLevelColorCode;?> !important;">
  <div class="position-relative p-3">
    <span style="background-color:<?php echo $billResponses->trackingLevelColorCode;?>;" class="bg-span"></span>
  <div class="row">
        
        <div class="col-md-10 d-flex align-items-center">
        <img class="rounded-circle  mr-3" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/all-state-bill-icon.png';?>" alt="bill-icon" style="max-width:78px; flex:0 0 78px">
        <div>
          <h3 class="mb-0 pb-1">
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
          <div class=""><?php echo $billResponses->alternateTitle; ?></div>
          <?php
            }
          ?>

          <?php 

            if($title_settings == 'alternate-top'){
              if($billResponses->alternateTitle){
          ?>
          <div class=""><?php echo $billResponses->title; ?></div>
          <?php
              }
            }
            if ($siteURL == "https://engagifiiweb.com/capitolreports-nc"){ 
              ?>
         
          <div class="pt-1 text-size-medium">
            <span class="pt-2 text-bold"><strong>Introduced Date: </strong></span><span><?php echo $intro_new_date;?> </span>
          </div>
          <div class="pt-1 text-size-medium">
          <span class="pt-2 text-bold"><strong>Status:</strong> </span><span><?php echo $billResponses->status; ?></span>
          </div>
          <div class="pt-1 text-size-medium">
            <!-- <span class="pt-2 text-bold">State: </span><span><?php echo $billResponses->state;?></span> -->
            <span class="pt-2 text-bold"><strong>Last Action: </strong></span><span><?php echo $lastAction_new_date;?> - <?php echo $billResponses->lastActionTaken;?></span>
          </div>
          <?php }
                else{ ?>
            <div class="pt-1 small">
            <span class=""><strong>State:</strong> <?php echo $billResponses->state;?></span><br>
            <span class=""><strong>Last Action:</strong> <?php echo $lastAction_new_date;?> - <?php echo $billResponses->lastActionTaken;?></span><br>
         
            <span class=""><strong>Introduced Date:</strong> <?php echo $intro_new_date;?> </span><br>
            <span class=""><strong>Status:</strong> <?php echo $billResponses->status; ?></span><br>
         
            <span class=""><strong>Session:</strong> <?php echo $billResponses->session; ?></span>
          </div>
          <?php
          }
         
                if(count($billResponses->clientTags)){
                  usort($billResponses->clientTags, "sort_associative_array");
                  $countTag = 0;
                 ?> 
                 <div id="tag-order" class="pt-1 d-flex align-items-center small"> 
                 <i class="fas fa-tags mr-1"></i><strong>Tag(s):</strong> 
                 <span class="d-flex align-items-center">
				 <?php
                  foreach ($billResponses->clientTags as $key => $tag) {
                    $tagMatch = $tag->tagId;
                  if (in_array($tagMatch, $lbt_vsbl_tag_list)){
                    $countTag = $countTag+1;
                     echo '<span class="badge badge-pill badge-light text-capitalize border mr-1 order-2"><a href="'.site_url().'/bill-tracking/?tag='.$tag->tagId.'&'.base64_encode($tag->text).'">'.$tag->text."</a></span>";
						
                  }
                
                    }
					echo '<span class="order-1 mx-1">'.$countTag.'</span>';
					 ?>
                    
                    </span>
                      </div>
					  <?php
                }
              ?>

          
         

          <?php if(count($billResponses->clientUsers) || count($billResponses->clientUserTags) || count($billResponses->clientGroups)){ 
              $total_assign_to = (int)count($billResponses->clientUsers) + (int)count($billResponses->clientUserTags) + (int)count($billResponses->clientGroups);
            ?>
          <div class="pt-1 small">
            <i class="fa fa-users mr-1"></i><strong>Assign To:</strong><span class="mx-1"> <?php if($total_assign_to){ echo $total_assign_to;}else {echo '<span class="text-muted"><em>No Member assigned</em></span>'; } ?></span>
              <?php
                if(count($billResponses->clientUsers)){
                  foreach ($billResponses->clientUsers as $key => $assignto) {
                   
                     echo '<span class="badge badge-pill badge-light text-capitalize border mr-1"><a href="'.site_url().'/bill-tracking/?member='.$assignto->personId.'&'.base64_encode($assignto->firstName.' '.$assignto->lastName).'">'.$assignto->firstName.' '.$assignto->lastName."</a></span>";
                  }
                }
              ?>
              <?php if(count($billResponses->clientUserTags)){ 
                  foreach ($billResponses->clientUserTags as $key => $assignto) {
                  
                     echo '<span class="badge badge-pill badge-light text-capitalize border mr-1"><a href="'.site_url().'/bill-tracking/?membertags='.$assignto->tag.'&'.base64_encode($assignto->tag).'">'.$assignto->tag."</a></span>";
                  }
             } ?>

              <?php if(count($billResponses->clientGroups)){ 
                  foreach ($billResponses->clientGroups as $key => $assignto) {
                    
                     echo '<span class="badge badge-pill badge-light text-capitalize border mr-1"><a href="'.site_url().'/bill-tracking/?groups='.$assignto->id.'&'.base64_encode($assignto->name).'">'.$assignto->name."</a></span>";
                  }
             } ?>
  
          </div>
          <?php } ?>

         </div>
        </div>
        <div class="col-md-2 text-md-right">
            
            <div class="d-flex align-items-center mb-3 justify-content-end">
              <?php
                if($prev){
              ?>
              <a class="<?php if($next){echo 'pr-2'; }?>" href="<?php echo site_url(); ?>/engagifii-detail/?billId=<?php echo $prev; ?>"><i class="fal fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a href="<?php echo site_url(); ?>/engagifii-detail/?billId=<?php echo $next; ?>"> <i class="fal fa-arrow-right"></i></a>
              <?php
                }
              ?>
            </div>
            <div class="">
              <span class="btn btn-sm btn-danger tracking-state" style="background-color: <?php echo $billResponses->trackingLevelColorCode;?>; border-color: <?php echo $billResponses->trackingLevelColorCode;?>;"> <?php echo $billResponses->trackingLevel;?> </span>
            </div>
            <div class="d-flex align-items-center justify-content-lg-end py-3">
              
              <a class="text-underline pl-3  download-detail order-2 " href="<?php echo $lbt_api_url;?>/file/<?php echo $billResponses->fileId;?>">Download Full Text</a>
              <img class="inline-block  " src="<?php echo ENGAGIFII_ASSETS_URL.'/images/pdf.png';?>" alt="pdf">
            </div>
 <?php 
              if ($siteURL == "https://engagifiiweb.com/maco"){ 
                $siteLink = $quicklinkResponses[0]->url;
                
                ?>
                <div class="mt-auto">
                <a class="btn btn-success btn-sm" href="<?php echo $siteLink ?>">MGA Site </a>
              </div>
                <?php
               
             } ?>
        </div>
</div>
</div>
</div>
  
                      
                      
                <div class="engagifii-box border p-2 p-lg-4 bg-light">             
                  <div class="border bg-white class-detail-main-nav">
                    <ul class="nav nav-pills mb-0 border-bottom engagifii-tabs" id="pills-tab" role="tablist">
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
                             <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#staffanalysis" id="">ACCG Analysis</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" href="#summary" id="">State Summary</a></li>
                             
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#versions" id="">Versions</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#votes" id="">Votes</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#history" id="">History</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#quick" id="">Quick Links</a></li>
                              <?php
                                }
                                elseif($site == 'https://engagifiiweb.com/baltimorecountymd') {?>
                                  <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" href="#summary" id="">State Summary</a></li>
                                  <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#staffanalysis" id="">Baltimore City Analysis</a></li>
                                   <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#versions" id="">Versions</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#votes" id="">Votes</a></li>
                                  <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#history" id="">History</a></li>
                                 <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#quick" id="">Quick Links</a></li>
                                   <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#macoanalysis" id="">MACo Analysis</a></li>
                                  <?php
                                    }
                                    elseif($site == 'https://engagifiiweb.com/princegeorgescountymd') {?>
                                      
                                       <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" href="#summary" id="">State Summary</a></li>
                                      <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#staffanalysis" id="">Prince Georges County Analysis</a></li>
                                     <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#versions" id="">Versions</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#votes" id="">Votes</a></li>
                                      <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#history" id="">History</a></li>
                                      <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#quick" id="">Quick Links</a></li>
                                      <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#macoanalysis" id="">MACo Analysis</a></li>
                                      <?php
                                        }
                                        elseif($site == 'https://engagifiiweb.com/howardcountymd') {?>
                                          
                                          <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" href="#summary" id="">State Summary</a></li>
                                         <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#staffanalysis" id="">Howard County Analysis</a></li>
                                        <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#versions" id="">Versions</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#votes" id="">Votes</a></li>
                                          <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#history" id="">History</a></li>
                                          <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#quick" id="">Quick Links</a></li>
                                           <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#macoanalysis" id="">MACo Analysis</a></li>
                                          <?php
                                            }
                                            elseif($site == 'https://engagifiiweb.com/mcmd') {?>
                                              
                                               <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" href="#summary" id="">State Summary</a></li>
                                             <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#staffanalysis" id="">Montgomery County Analysis</a></li>
                                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#versions" id="">Versions</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#votes" id="">Votes</a></li>
                                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#history" id="">History</a></li>
                                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#quick" id="">Quick Links</a></li>
                                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#macoanalysis" id="">MACo Analysis</a></li>

                                              <?php
                                                }
  
                              else
                              {
                            ?>
                            <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" href="#summary" >Summary</a></li>
                            <?php  if (count($analysisResponses)>0) {?>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#staffanalysis" >Staff Analysis</a></li>
                              <?php } ?>
                          <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#versions" id="">Versions</a></li>
                              <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#votes" id="">Votes</a></li>
                            <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#history" >History</a></li>
                            <li class="nav-item"><a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" href="#quick" >Quick Links</a></li>
                            <!--li class="nav-item"><a class="nav-link lbt-link" data-toggle="tab" href="javascript:void(0)" id="macoanalysis">MACo Analysis</a></li-->

                            <?php
                              }
                            ?>
                           
                        </ul>
                        <div class="p-3">
                        <div class="tab-content" id="pills-tabContent">
                          <div class="tab-pane fade active show"  role="tabpanel" id="summary">
                              <div class="row">
                                  <div class="col-md-4 order-2">
                                  <div class="border rounded shadow-sm h-100">
                                    <div class="panel-title bg-light p-2  border-bottom">
                                      <h6 class="mb-0 font-weight-normal">House Committees</h6>
                                    </div>
                                      <div class="p-3">
                                        <?php if(!empty($billResponses->houseCommittees)){

                                          ?>
                                        <?php foreach($billResponses->houseCommittees as $houseComittee){?>
                                          <p class="summary-content-para particular-font mb-2"> <?php echo $houseComittee;?> </p>
                                        <?php } } else { ?>
                                          <p class="summary-content-para no-border text-capitalize"> None </p>
                                        <?php }?> 

                                  </div>

                                       <div class="panel-title bg-light p-2  border-bottom border-top">
                                      <h6 class="mb-0 font-weight-normal">Senate Committees</h6>
                                      </div>
                                      <div class="p-3">
                                      <?php if(!empty($billResponses->senateCommittees)){?>
                                      <?php foreach($billResponses->senateCommittees as $senateCommittee){?>
                                      <p class="summary-content-para no-border mb-2"> <?php echo $senateCommittee;?>  </p>
                                      <?php } } else {?>
                                        <p class="summary-content-para text-capitalize"> None </p>
                                        <?php }?>
                                      </div>
                                  </div>
                                  </div>
                                  <div class="col-md-8">
                                  <div class="border rounded shadow-sm h-100">
                                       <div class="panel-title bg-light p-2  border-bottom">
                                      <h6 class="mb-0 font-weight-normal"> <?php echo $billResponses->title;?> </h6>
                                      </div>
                                      <div class="p-3">
                                      <p class="summary-content-para-engagiigii "> <?php echo $billResponses->summary;?> </p>
                                         </div>

                                      <?php $countSponsors =  count($billResponses->sponsors); ?>
                                       <div class="panel-title bg-light p-2  border-bottom border-top">
                                      <h6 class="mb-0 font-weight-normal">Sponsors (<?php echo $countSponsors;?>) </h6>
                                      </div>
										<div class="p-3">
                                      <div class="row sponsor-list-wrapper">



                                        <?php
                                         foreach($billResponses->sponsors as $sponser) { 

                                          ?>
                                          <div class="col-lg-4 col-md-6 d-flex align-items-center mb-3">
                                                  <?php
                                                    if($sponser->profilePic){
                                                  ?>
                                                 <span class="rounded-circle overflow-hidden mr-2" style="max-width:44px; flex:0 0 44px; height:44px">
                                                 <img src="<?php echo $sponser->profilePic;?>" class="img-fluid" alt="sponsors">
                                                 </span>
												                            <?php
                                                      }
                                                      else
                                                      {
                                                    ?>
                                                         <span class="rounded-circle overflow-hidden mr-2" style="max-width:44px; flex:0 0 44px; height:44px">
                                                 <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/user-default.png" class="img-fluid" alt="user">
                                                 </span>
                                                    <?php
                                                      }
                                                    ?>
                                                   <div>
                                                    <p class="title"><?php echo $sponser->name;?></p>
                                                    <?php
                                                          
                                                          if($sponser->involvedAs == 'Primary Sponsor'){
                                                    ?>
                                                    <p class="badge badge-pill badge-warning "><small>Primary</small></p>
                                                    <?php
                                                        }
                                                      
                                                    ?>
                                                      
                                                    </div>
                                          </div>

                                        <?php } ?>
                                          
                                          
                                        
                                      </div>
                                     </div>
                                  </div>
                                  </div>
                              </div>
                          
                          </div>
                          <div class="tab-pane fade " id="staffanalysis">
                          

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
                                          $instructor_img = $analysis->createdByImage;
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
                           
                            <div class="tab-pane fade " id="versions">
                                    <table class="table table-bordered border-0 table-striped" id="">
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
                                                  //$convert_Date = $allVersions->billDraftDateTime;
                                                  $defaulget_Date = $allVersions->billDraftDateTime;
                                                  $convert_Date = strtotime($defaulget_Date);
                                                  $new_Date = date('M d, Y', $convert_Date);
                                            ?>
                                            <tr>
                                              

                                                <td> <?php echo $allVersions->type;?> </td>
                                                <td> <?php echo $new_Date;?> </td>
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
                            <div class="tab-pane fade" id="votes">
                                    <table class="table table-bordered border-0  table-striped" id="">
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
                                          foreach($voteResponses as $vote){ 
                                            $defaulget_Date = $vote->dateOfRollCall;
                                            $convert_Date = strtotime($defaulget_Date);
                                            $new_Date = date('M d, Y', $convert_Date);
                                            ?>
                                            <tr>
                                                <td> <?php echo $vote->chamberType;?></td>
                                                <td> <?php echo $vote->totalVoteCount;?></td>
                                                <td> <?php echo $new_Date;?></td>
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
                            <div class="tab-pane fade" id="history">
                                    <table class="table table-bordered border-0 table-striped" id="">
                                        <thead>
                                            <tr>
                                                <th class="alpha-teal" width="150">Date</th>
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
                                             $new_date = date('M d, Y',$convert_Date); //$new_Date = date('M d, Y', $convert_Date);
                                          ?>
                                            <tr>
                                                <td> <?php echo $new_date;?></td>
                                                <td> <?php echo $histories->billChamberType;?> </td>
                                                <td> <?php echo $histories->actionText;?> </td>
                                            </tr>
                                          <?php }} ?> 
                                            
                                          </tbody>
                                        </table>
                                </div>
                                
                            <div class="tab-pane fade" id="quick">
                                        
                                        <table class="table table-bordered border-0 table-striped" id="">
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
                                                    <tr>
                                                      <td> <?php echo $links->type;?> </td>
                                                      <td>
                                                        <a target="_blank" href="<?php echo $links->url;?>"><?php echo $links->url;?>  </a>
                                                      </td>
                                                    </tr>
                                                <?php }} ?>

                                            </tbody>
                                        </table>
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
                "dom": '<"row"<"col-sm-12 pull-left"f">><"row custom-scroll border-left border-right border-bottom"t>i<"row"<"col-sm-5 pt-2"l><"col-sm-7 text-right"p>>',
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