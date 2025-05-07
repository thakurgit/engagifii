<?php
	ini_set('session.gc_maxlifetime', 86400);
    session_set_cookie_params(86400);
    session_start();
	$id 		= $_REQUEST['classId'] ?? null;
	$options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $classes_page = $front_pages['classes_page'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_page){
	$classes_page_link=get_permalink( $classes_page );	
	}else{
	$classes_page_link= site_url() .'/classes/';	
	}
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
    $api_url = $options['ebt_api_url'];
    $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
	if (str_contains($tenant_url, 'http')) {
	} else {
		$tenant_url = 'https://'.$tenant_url.'.engagifii.com';
	}
	$class_visible_column_list = $options['class_visible_column_list'];
	//print_r($class_visible_column_list);
    $loggedInUserId = $_SESSION['pid'];   
	$tenantCode = $options['dashboard_tenant_code'];
	$env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
    $evn_url = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii'.$env.'.com';
	$obj 			=  new Engagifii_API();
	$response       =  $obj->getClassDetailsByID($id);
	$classesData        = $obj->getRelatedClassByClass($response->parentCourse->id, $id,10);

	$class_array = json_decode(stripslashes($_COOKIE['classids']), true);
	if($class_array){
  $class_key = array_search ($_GET['classId'], $class_array);
  $class_count = count($class_array)-1;
	}
  if($class_key == 0){
      $prev = 0;
      $next = $class_array[$class_key+1];
  }
  if($class_key == $class_count){
     $next = 0;
     $prev = $class_array[$class_key-1];
  }
  if($class_key!= $class_count){
    $prev = $class_array[$class_key-1];
    $next = $class_array[$class_key+1];
  }
  if($loggedInUserId){
	$userPermissionArray = array();
        $postedDataPermission = array();
        $requestedURL = "Subject/GetAssignedRolesPermission?tenantCode=$tenantCode&userId=$loggedInUserId";
        $userPermission = $this->submitApiRequest($requestedURL, $postedDataPermission, "GET", 'auth');  
        $userPermissionResponse = $userPermission['api_response'];
        $userpermissionJson = json_decode($userPermissionResponse,true)['permissions'];
        foreach($userpermissionJson as $key => $permissionValue){
            $userPermissionArray[] = $permissionValue['name'];
        }
        if(in_array('RegisterMembersfromOwnOrganization', $userPermissionArray)){
            $registerOthers = 'true';
        }
        else{
            $registerOthers = 'false';
        }
        if(in_array('OverrideRegistration', $userPermissionArray)){
            $registerOverride = 'true';
        }
        else{
            $registerOverride = 'false';
        }
	}
  $siteURL= site_url();
  $class_icon = $response->parentCourse->icon->iconReference;
  if($siteURL == "https://engagifiwebstg.wpengine.com/oresa" || $siteURL == "https://engagifiiweb.com/oresa" || $siteURL == "https://oconeeresa.org"){
      $class_icon = ENGAGIFII_ASSETS_URL.'/images/oconee-logo.png';
      
  }
//print_r(json_encode($response));
  
	//$documentData  =  $obj->getCourseDocument($id, $response->name);
?>
<div class="mb-2">
<?php if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    $url.= $_SERVER['HTTP_HOST'];   
    $url.= $_SERVER['REQUEST_URI'];    
if ( strpos($url,'my-profile') !== false ) {
	$classes_detail_page_link= site_url() .'/my-profile/my-transcript/class-detail/';?> 
    <a onclick="window.history.back();" style="cursor: pointer; color: #2568EF;" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<?php } else { ?>
    <a onclick="window.history.back();" style="cursor: pointer; color: #2568EF;"  class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<?php } 
?>
</div>

<div class="engagifii-box border border-bottom-0 p-2 p-lg-3">
    <div class="row">
        <div class="col-md-10 d-flex align-items-center">
            <img class="rounded-circle  mr-3  p-0" src="<?php echo $class_icon; ?>" style="max-width:78px; flex:0 0 78px">
            <div>
             <h3 class="mb-0 pb-1"><?php echo $response->parentCourse->name;?> </h3>
            <p  class="mb-2"> <?php echo $response->sectionName; ?></p>
            <?php if(is_array($response->classTag) && count($response->classTag)>0 && in_array('classTag', $class_visible_column_list)) {?>
            <div class="">
                <span><i class="fas fa-tags mr-1"></i>Tag(s): </span>
                <span class="pl-1 pr-1 d-none"> <?php echo count($response->classTag);  ?></span>
                <?php
                	foreach ($response->classTag as $key => $value) {
                		?>
                			<span class="badge badge-pill badge-light text-capitalize border mr-1 "><?php echo $value->tagName; ?></span>
                		<?php
                	}
                ?>
            </div> 
            <?php
            	}
            ?>
            </div>
        </div>
        <div class="col-md-2 text-md-right">
          <div class="d-flex align-items-center mb-2 justify-content-end">
          	 <?php
                if($prev){
              ?>
              <a class=" <?php if($next){echo 'pr-2'; }?>" href="<?php echo $classes_detail_page_link;?>?classId=<?php echo $prev; ?>"><i class="fal fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a class="" href="<?php echo $classes_detail_page_link;?>?classId=<?php echo $next; ?>"> <i class="fal fa-arrow-right"></i></a>
              <?php
                }
              ?>
            
          </div>
          <?php
          $isAlreadyRegistered = $response->isAlreadyRegistered;
          if ($response->isClassRegistrationAllow && in_array('register', $class_visible_column_list)) {
            $registration_state = $response->registrationState;
        
            if (($registration_state == 'Completed' || $registration_state == 'Registration Closed' || $registration_state == 'Registration Not Setup' || $registration_state == 'Registration Scheduled' || $registration_state == 'Early Sold Out' || $registration_state == 'Standard Sold Out') && ($registerOverride == 'false')) {
                
                if ($registration_state == 'Registration Scheduled') {
                    $tooltip = 'Registration opens from ' . date('M d, Y', strtotime($response->registrationStartFrom)); ?>
                    <div class="mt-auto">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip; ?>">
                            <button type="button" class="btn btn-primary px-3 py-1" disabled>Register</button>
                        </span>
                    </div>
                <?php } else {
                    $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $registration_state); ?>
                    <div class="mt-auto">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip; ?>">
                            <button type="button" class="btn btn-primary px-3 py-1" disabled>Register</button>
                        </span>
                    </div>
                <?php }
        
            } else if ($isAlreadyRegistered && $registerOthers == 'false') {
                $alreadyRegisteredText = "Already Registered";
                $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $alreadyRegisteredText); ?>
                <div class="mt-auto">
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip; ?>">
                        <button type="button" class="btn btn-primary px-3 py-1" disabled style="pointer-events: none;">Register</button>
                    </span>
                </div>
            <?php } else {
                // Check for my-profile in URL
                if (strpos($url, 'my-profile') !== false) {
                    if ($response->registrantsCapacity <= $response->classAttendeesCount) {
                        $tooltip = 'Sold out'; ?>
                        <div class="mt-auto">
                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip; ?>">
                                <button type="button" class="btn btn-primary px-3 py-1" disabled style="pointer-events: none;">Register</button>
                            </span>
                        </div>
                    <?php } else {
                        // Show registration buttons
                        ?>
                        <div class="mt-auto">
                            <?php if ($response->classLocationType->name == "onlocation") { ?>
                                 <button 
                                    data-url="<?php echo $evn_url; ?>/auth-callback/pages/home#access_token=<?php echo $_SESSION['accesstoken']; ?>&source=external&tpath=pages/classes/<?php echo $id; ?>/classregpub/signup/onlocation/overview" 
                                    class="btn btn-primary px-3 py-1 open-pop">
                                    Register
                                </button>
                            <?php } elseif ($response->classLocationType->name == "online") { ?>
                                <button 
                                    data-url="<?php echo $evn_url; ?>/auth-callback/pages/home#access_token=<?php echo $_SESSION['accesstoken']; ?>&source=external&tpath=pages/classes/<?php echo $id; ?>/classregpub/signup/online/overview" 
                                    class="btn btn-primary px-3 py-1 open-pop">
                                    Register
                                </button>
                            <?php } elseif ($response->classLocationType->name == "onlocationandonline") { ?>
                                <span id="locationButon" style="display:grid;">
                                <button 
                                    data-url="<?php echo $evn_url; ?>/auth-callback/pages/home#access_token=<?php echo $_SESSION['accesstoken']; ?>&source=external&tpath=pages/classes/<?php echo $id; ?>/classregpub/signup/onlocation/overview" 
                                    class="btn btn-primary px-3 py-1 open-pop">
                                    Register In Person
                                </button>
                                <button 
                                    data-url="<?php echo $evn_url; ?>/auth-callback/pages/home#access_token=<?php echo $_SESSION['accesstoken']; ?>&source=external&tpath=pages/classes/<?php echo $id; ?>/classregpub/signup/online/overview" 
                                    class="btn btn-primary px-3 py-1 open-pop">
                                    Register Online
                                </button>
                                </span>
                            <?php } ?>
                        </div>
                    <?php }
                } else {
                    if ($response->classLocationType->name == "onlocation") { ?>
                        <a class="btn btn-primary" target="_blank" href="<?php echo $response->registrationUrlOnLocation ?>">Register</a>
                    <?php } elseif ($response->classLocationType->name == "online") { ?>
                        <a class="btn btn-primary" target="_blank" href="<?php echo $response->registrationUrlOnLine ?>">Register</a>
                    <?php } elseif ($response->classLocationType->name == "onlocationandonline") { ?>
                        <span id="locationButon" style="display:grid;">
                            <a class="btn btn-primary" style="margin-top: 5px; margin-bottom: 5px;" id="onlineclass" target="_blank" href="<?php echo $response->registrationUrlOnLine ?>">Register Online</a>
                            <a class="btn btn-primary" id="onlocation" target="_blank" href="<?php echo $response->registrationUrlOnLocation ?>">Register In Person</a>
                        </span>
                    <?php }
                }
            }
        }
               			 
		   ?>

	 </div>
 </div>      
</div>
<div class="engagifii-box bg-light p-3 border">             
    <div class="border bg-white class-detail-main-nav">
        	<ul class="nav nav-pills mb-0 border-bottom engagifii-tabs" id="pills-tab" role="tablist">
			  	<li class="nav-item">
				    <a class="nav-link rounded-0 px-0 mx-3 text-dark active" id="home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="home" aria-selected="true">General</a>
			  	</li>
                <?php  if(is_array($classesData->result) && count($classesData->result)){ ?>
			  	<li class="nav-item">
			    	<a class="nav-link rounded-0 px-0 mx-3 text-dark " id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Related Classes</a>
			  	</li>
                <?php } ?>
			  <!-- 	<li class="nav-item">
			    	<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Document</a>
			  	</li> -->
			</ul>
            <div class="p-3">
               <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="row">
                      		<?php if(in_array('sessions', $class_visible_column_list)) {?>
                              <div class="col-12 mb-3">
                                  <div class="border rounded shadow-sm">
                                  <div class="panel-title bg-light p-2  border-bottom">
                                    <h6 class="mb-0 font-weight-normal">Class Schedule</h6>
                                  </div>
                                  <div class="p-3">
                                  <p class="mb-2">Class occurs on the following schedule:-</p>
                                  <table class="table table-borderless small table-sm">
                                  
                                  <?php
                                      if(is_array($response->classSessions) && count($response->classSessions)){
                                          //print_r($response);
                                          foreach ($response->classSessions as $key => $value) {
                                      ?>
                                      <tr>
                                      	<td><?php echo date('M d, Y',strtotime($value->sessionDate)); ?> at  <?php echo $value->startTime.' - '. $value->endTime;?></td>
                                      </tr>
                                      
                                      <?php } } 
                                      else{
                                        $classStartDateTime = $response->parentCourse->classes[0]->startDate;
                                        $classStartTimeVal = date('g:i A',strtotime($classStartDateTime));
                                        $classEndDateTime = $response->parentCourse->classes[0]->endDate;
                                        $classEndTimeVal = date('g:i A',strtotime($classEndDateTime));
                                       

                                        ?>
                                      <tr>
                                      	<td><?php echo date('M d, Y',strtotime($classStartDateTime)).' - '.date('M d, Y',strtotime($classEndDateTime)) ; ?> at  <?php echo $classStartTimeVal.' - '. $classEndTimeVal;?></td>
                                      </tr>
                                      
                                      <?php
                                      }?>
                                      </table>
                                 <?php if($response->classSessions){ ?> <p><strong>Total sessions:</strong> <?php echo count($response->classSessions); }?></p>
                                 
                              </div>
                              </div>
                              </div>
                              <?php } ?>
                              <div class="col-md-7 mb-3">
                                  <div class="border rounded shadow-sm h-100">
                                  <div class="panel-title bg-light p-2  border-bottom">
                                    <h6 class="mb-0 font-weight-normal">Class Details</h6>
                                  </div>
                                  <div class="p-3">
                                  <?php
                                  
                                      if(trim($response->description)){
                                  ?>
                                  <div class="summary-content-para-engagiigii row flex-wrap mb-3">
                                      <div class="col-md-4 col-xl-3 mb-3 mb-md-0"><strong>Description:</strong></div>
                                      <div class="col-md-8 col-xl-9"><?php echo trim($response->description); ?></div>
                                  </div>
                                  <?php
                                      }
                                      if($response->objectType && in_array('objectType', $class_visible_column_list)){
                                  ?>
                                  <div class="summary-content-para-engagiigii row mb-2">
                                      <div class="col-md-4 col-xl-3  mb-3 mb-md-0"><strong>Class Type:</strong></div>
                                      <div class="col-md-8 col-xl-9"><?php echo $response->objectType; ?></div>
                                  </div>
                                  <?php 
                                      } 
                                      //print_r(json_encode($response->courseCreditMapping[0]->credits));
                                      if($response->courseCreditMapping && in_array('credithours', $class_visible_column_list)){
                                        
                                        foreach ($response->courseCreditMapping as $key => $creditHrs){
                                     ?>
                                  <div class="summary-content-para-engagiigii row mb-2">
                                      <div class="col-md-4 col-xl-3  mb-3 mb-md-0"><strong> <?php echo $creditHrs->creditName; ?> </strong></div>
                                      <div class="col-md-8 col-xl-9"><?php echo number_format($creditHrs->credits,2); ?></div>
                                  </div>
                                  <?php
                                      }}
                                  ?>
                                  <div class="summary-content-para-engagiigii row">
                                      <div class="col-md-4 col-xl-3  mb-3 mb-md-0"><strong>Registration Dates:</strong></div>
                                      <div class="col-md-8 col-xl-9">
                                        <?php 
                                        if (!empty($response->classRegistrationSetting->registrationStartDateTime)) {
                                            echo date('M d, Y g:i A', strtotime($response->classRegistrationSetting->registrationStartDateTime)) 
                                                . ' to ' 
                                                . date('M d, Y g:i A', strtotime($response->classRegistrationSetting->regularRegistrationEndDateTime));
                                        } 
                                        ?>
                                    </div>
                                  </div>
                              </div>
                              </div>
                              </div>
                              <div class="col-md-5 mb-3">
                                  <div class="border rounded shadow-sm h-100">
                                      <div class="panel-title bg-light p-2  border-bottom">
                                        <h6 class="mb-0 font-weight-normal">Class Location</h6>
                                      </div>
                                      <ul class="nav nav-pills mb-0 border-bottom engagifii-tabs" id="pills-tab" role="tablist">
                                          <li class="nav-item">
                                              <a class="nav-link rounded-0 px-0 mx-3 text-dark py-1  <?php if($response->classLocationType->name != 'online') {echo "active"; } ?>" id="offline-tab" data-toggle="pill" href="#offline" role="tab" aria-controls="offline" aria-selected="true">In Person Class</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link rounded-0 px-0 mx-3 text-dark py-1   <?php if($response->classLocationType->name == 'online') {echo "active"; } ?>" id="online-tab" data-toggle="pill" href="#online" role="tab" aria-controls="online" aria-selected="false">Online Class</a>
                                          </li>
                                       </ul>
                                       <div class="p-3">
                                        <div class="tab-content" id="pills-tabContent">
                                          <div class="tab-pane fade  <?php if($response->classLocationType->name != 'online') {echo "show active"; } ?>" id="offline" role="tabpanel" aria-labelledby="offline-tab">
                                              
                                                  <?php
                                                      if(!empty($response->location) && isset($response->location->address))
                                                      {
                                                  ?>
                                                      <div class="summary-content-para-engagiigii row mb-2">
                                                          <div class="col-sm-4 "><strong>Room Name:</strong></div>
                                                          <div class="col-sm-8"><?php echo $response->location->classRoom->buildingName; ?></div>
                                                      </div>
                                                      <div class="summary-content-para-engagiigii row mb-2">
                                                          <div class="col-sm-4 "><strong>Address:</strong></div>
                                                          <div class="col-sm-8"><?php echo $response->location->address->addressLine1; ?><br/><?php echo $response->location->address->city.' '.$response->location->address->state.', '.$response->location->address->zipCode; ?><br/><?php echo $response->location->address->country; ?></div>
                                                      </div> 
                                                      <div class="summary-content-para-engagiigii">
                                                          <iframe src = "https://maps.google.com/maps?q=<?php echo urlencode($response->location->address->addressLine1.' '.$response->location->address->city.' '.$response->location->address->state.' '.$response->location->address->zipCode); ?>&hl=en;z=14&amp;output=embed" width="100%" height="300"></iframe>
  
                                                      </div>
                                                  <?php
                                                      }
                                                      else{
                                                      ?>
                                                              <div class="summary-content-para-engagiigii">No class room is selected now</div>
                                                      <?php	
                                                      }
                                                  ?>
                                                  
                                              
                                          </div>
                                          <div class="tab-pane fade <?php if($response->classLocationType->name == 'online') {echo "show active"; } ?>" id="online" role="tabpanel" aria-labelledby="online-tab">
                                              <div class="summary-content-para-engagiigii row mb-2">
                                                  <div class="col-sm-4 "><strong>Online Class Location:</strong></div>
                                                  <div class="col-sm-8"><?php echo $response->locationUrl ?? 'N/A'; ?></div>
                                              </div> 
                                              <div class="summary-content-para-engagiigii row">
                                                  <div class="col-sm-4 "><strong>Login Steps:</strong></div>
                                                  <div class="col-sm-8"><?php echo $response->locationAccessDetail ?? 'N/A'; ?></div>
                                              </div> 
                                          </div>
                                      </div>
                                      </div>
                                  </div>
                              </div>
                      </div>
                      <div class="row">
                          <div class="col-12">
                              <div class="border rounded shadow-sm">
                                  <div class="panel-title bg-light p-2  border-bottom">
                                      <h6 class="mb-0 font-weight-normal">Contact Persons</h6>
                                  </div>
                                  <div class="p-3">
                                  	<div class="row">
                                    	<?php
                                      if(is_array($response->classContactPersons) && count($response->classContactPersons)){
                                        foreach ($response->classContactPersons as $key => $value) {
                                      ?>
                                      
                                      <div class="col-md-4">
                                          <div class="card">
                                              <div class="card-body d-flex py-3 px-0 py-lg-4 align-items-center instructor-detail shadow-sm">
                                              <div class="col-12 d-flex align-items-center">
                                                  <?php if (filter_var($value->thumbnailUrl, FILTER_VALIDATE_URL)) { ?>
                                                      <img style="max-width:90px; flex: 0 0 90px" src="<?php echo $value->thumbnailUrl;?>" class=" rounded-circle mr-3">
                                                  <?php
                                                      }
                                                      else if($value->thumbnailUrl)
                                                      {
                                                  ?>
                                                          <img style="max-width:90px; flex: 0 0 90px" src="<?php echo $tenant_url.$value->thumbnailUrl;?>" class="rounded-circle mr-3">
                                                  <?php		
                                                      }else{
                                                  ?>
                                                              <img style="max-width:90px; flex: 0 0 90px" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/user-default.png'; ?>" class="rounded-circle mr-3">
                                                  <?php
                                                      }
                                                  ?>
                                                  <div>
                                                  <h5 class="card-title mb-2"><?php echo $value->lastName.', '.$value->firstName;?></h5>
                                                  <p class="small"><strong>Position:</strong> <?php echo $value->position;?></p>
                                                  <p class="small "><strong>Department:</strong> <?php echo $value->department;?></p>
                                                  
                                                  </div>
                                                  </div>
                                               </div>
                                              
                                          </div>
                                      </div>
                                      <?php
                                          }
                                      }
                                      else{ ?>
                                        <div class="summary-content-para-engagiigii p-2">No contact person assigned.</div>
                                      <?php }
                                      ?>	
                                    </div>
                                  </div>
                                  
                              </div>
                          </div>
                          <?php if(in_array('classInstructorsCount', $class_visible_column_list)){ ?>
                          <div class="col-12 mt-3">
                              <div class="border rounded shadow-sm">
                                  <div class="panel-title bg-light p-2  border-bottom">
                                      <h6 class="mb-0 font-weight-normal">Instructor</h6>
                                  </div>
                                  <div class="p-3">
                                  	<div class="row">
                                    	<?php
                                      if(is_array($response->classInstructors) && count($response->classInstructors)){
                                          //print_r($response);
                                          foreach ($response->classInstructors as $key => $value) {
                                      
                                      ?>
                                      <div class="col-md-4">
                                          <div class="card">
                                              <div class="card-body d-flex py-3 px-0 py-lg-4 align-items-center instructor-detail shadow-sm">
                                              <div class="col-9 d-flex align-items-center">
                                                  <?php if (filter_var($value->imageThumbUrl, FILTER_VALIDATE_URL)) { ?>
                                                      <img style="max-width:90px; flex: 0 0 90px" src="<?php echo $value->imageThumbUrl;?>" class="inst-thumb rounded-circle mr-3">
                                                  <?php
                                                      }
                                                      else if($value->imageThumbUrl)
                                                      {
                                                  ?>
                                                           <img style="max-width:90px; flex: 0 0 90px" src="<?php echo $tenant_url.$value->imageThumbUrl;?>" class="rounded-circle mr-3">
                                                  <?php		
                                                      }else{
                                                  ?>
                                                              <img style="max-width:90px; flex: 0 0 90px" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/user-default.png'; ?>" class="rounded-circle mr-3">
                                                  <?php
                                                      }
                                                  ?>
                                                  <div>
                                                  <p class="card-title mb-2"><?php echo $value->fullName;?></p>
                                                  <ul class="list-unstyled mb-0 ml-0 d-flex flex-wrap">
                                                      <li class="position-relative mr-2 inst-ac ml-0"><img title="Awards" src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/trophy.png" class="img-fluid" alt=""></li>
                                                      <li class="position-relative mr-2 inst-ac ml-0">
                                                          <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/class.png" title="Classes" class="img-fluid" alt="">
                                                          <!-- <span class="badge badge-secondary position-absolute rounded-circle">2</span> -->
                                                      </li>
                                                  </ul>
                                                  </div>
                                                  </div>
                                                   <div class="ml-auto text-right col-3 pl-0"> 
                                                   <?php 
                                                      if($value->isLead){
                                                   ?>	
                                                  <span class="badge badge-success">Lead</span>
                                                  
                                                  <?php
                                                      }
                                                      if($value->isGuest){
                                                  ?>
                                                      <span class="badge badge-primary">Guest</span>
                                                  <?php
                                                      }
                                                      else{
                                                      ?>
                                                          <span class="badge badge-warning">Certified</span>
                                                      <?php
                                                      }
                                                  ?>
                                                  
                                              </div>
                                               </div>
                                              
                                          </div>
                                      </div>
                                      <?php
                                          }
                                      }
                                      ?>	
                                    </div>
                                  </div>
                                  
                              </div>
                          </div>
                          <?php } ?>
                      </div>
                  </div>
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  	<div class="p-3">
                    <style>
					table#ebtmaintable th:nth-child(1) {
  width: 300px !important;
}

table#ebtmaintable td:nth-child(1) {
  width: 300px !important;
}
					</style>
                      <table class="table table-bordered border-0 table-striped" id="ebtmaintable" width="100%">
                          <thead>
                              <tr>
			  					<th class="class">Class</th>
                                  <?php if(in_array('classDuration', $class_visible_column_list)){ ?>
			  					<th class="duration">Duration</th>
                                  <?php } 
                                    if(in_array('objectType', $class_visible_column_list)){ ?>
			  					<th class="classType">Class Type</th>
                                  <?php } 
								   if(in_array('sessions', $class_visible_column_list)){ ?>
			  					<th class="classDates">Class Dates</th>
                                  <?php }
								   if(in_array('classInstructorsCount', $class_visible_column_list)){ ?>
			  					<th class="instructor">Instructor</th>
                                  <?php } 
                                   if(in_array('credithours', $class_visible_column_list)){ ?>
			  					<th class="creditHours">Credit Hours</th>
                                 <?php } ?>
                                  
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                          
                                  if(is_array($classesData->result) && count($classesData->result)){
                                      
                                      foreach ($classesData->result as $key => $value) {
                                          $instructorPopOver = $obj->_popOverInstructorData1($key, $value->classInstructors);
                                          $classPopover   = $obj->_popOverClassesDate1($key, $value->classSessionSettings);
                              ?>
                                  <tr>
                                      <td><span class="d-block"><a href="<?php echo $classes_detail_page_link;?>?classId=<?php echo $value->id; ?>"><?php echo mb_substr($value->sectionName, 0,15); ?></a></span><small class="d-block">
                                          <?php 
                                              if(!empty($value->startDate) ){
                                                  echo date('M d, Y', strtotime($value->startDate)); 
                                                  if($value->classDuration > 1)
                                                  {
                                                      echo ' - '.date('M d, Y', strtotime($value->endDate));
                                                  }
                                                   echo ' at '.date("g:i A",strtotime($value->startDate)).' - '.date("g:i A",strtotime($value->endDate));
                                              }
                                           ?>
                                           </small>
                                      </td>
                                      <?php if(in_array('classDuration', $class_visible_column_list)){ ?>
                                      <td><?php echo $value->classDuration.' '.$value->classDurationType; ?></td>
                                      <?php }
									   if(in_array('objectType', $class_visible_column_list)){ ?>
                                      <td><?php echo $value->objectType; ?></td>
                                      <?php }
									   if(in_array('sessions', $class_visible_column_list)){?>
                                      <td>
                                          <div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_<?php echo $key; ?> " data-placement="left" data-containerid="<?php echo $key; ?>" id="<?php echo $key; ?>">
                                          <img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/Agenda.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo count($value->classSessionSettings); ?></span></div><?php echo $classPopover; ?></div>
                                              
                                      </td>
                                      <?php }
									   if(in_array('classInstructorsCount', $class_visible_column_list)){ ?>
                                      <td><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_<?php echo $key ?> " data-placement="left" data-containerid="<?php echo $key ?>" id=" <?php echo $key ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo $value->classInstructorsCount; ?></span></div><?php echo $instructorPopOver; ?></div></td> 
									  <?php }
									  if(in_array('credithours', $class_visible_column_list)){?>
                                      <?php foreach ($response->courseCreditMapping as $key => $credithrs){
                                        ?>
                                      <td><?php echo number_format($credithrs->credits, 2);  ?></td>
                                      <?php } } 
                                      ?>
                                  </tr>
                              <?php
                                  }
                              }
                              ?>
                          </tbody>
                      </table>
                      </div>
                  </div>
                  <!-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                      <div class="col-sm-12">
                          <div class="table-responsive">
                              <table class="table table-hover table-bordered light-background nowrap" id="doc_table" width="100%">
                                  <thead>
                                      <tr>
                                          <td>File</td>
                                          <td>Size</td>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr class="bg-white">
                                          <td>Document1</td>
                                          <td>50KB</td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div> -->
              </div>
            </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#ebtmaintable').DataTable({
			"pageLength": 10,
			"dom": '<"row custom-scroll border-left border-right border-bottom"t>i<"row"<"col-sm-5"l><"col-sm-7"p">>',
			"bInfo":false,
			"processing": true,
			"searching": true,
			"language": {
		    	processing: '<span>&nbsp;</span>',
		    	search:'',
		    	searchPlaceholder: "Search..."
		   	},
			"ordering":true,
			"order": [[<?php echo array_search('sessions',$class_visible_column_list);?>, 'asc']],
			"columnDefs": [ 
					{ "targets": ['duration','classType','instructor','creditHours'],
					  "orderable": false
					},
					//{ width: 200, targets: <?php //echo array_search('Name',$seqColumns);?> },
					{ className: "text-center", "targets": ["duration","classType","instructor","creditHours","classDates","instructor"] },
				  ],
			"drawCallback": function( settings ) {
					   dt_dropdown();
						 $('[data-toggle="tooltip"]').tooltip() ;
				   },
			});
	});
    $('.open-pop').click(function(e) {
        var tpath = $(this).attr('data-url');
        $('#iframeContainer').remove();
        var iframe = $('<iframe>', {
            src: tpath,
            id: 'iframeContainer',
            width: 1280,
            height: 700,
            frameborder: 0,
            scrolling: 'auto'
        });
        
        // Optionally create a modal or div to append the iframe
        var modalContainer = $('<div>', {
            id: 'modalContainer',
            css: {
                'width': '1280px',
                'height': '700px',
                'position': 'fixed',
                'top': '50%',
                'left': '50%',
                'transform': 'translate(-50%, -50%)',
                'background': '#fff',
                'z-index': 9999,
                'padding': '20px',
                'box-shadow': '0 0 10px rgba(0,0,0,0.5)',
                'overflow': 'hidden'
            }
        });
 var closeButton = $('<button>', {
            text: 'X',
            css: {
                'position': 'absolute',
                'top': '10px',
                'right': '10px',
                'padding': '5px 10px',
                'background-color': '#f44336',
                'color': '#fff',
                'border': 'none',
                'cursor': 'pointer',
                'font-size': '16px',
                'border-radius': '5px'
            },
            click: function() {
                // Remove modal when the close button is clicked
                $('#modalContainer').remove();
                location.reload();
            }
        });

        // Append iframe to modalContainer
        modalContainer.append(closeButton);
        modalContainer.append(iframe);
        
        // Append modalContainer to the body
        $('body').append(modalContainer);
        
        // Close modal logic when clicking outside the iframe (optional)
        modalContainer.click(function(e) {
            if (!$(e.target).is('iframe')) {
                $('#modalContainer').remove();
                table.draw();
            }
        });

        e.preventDefault();
    });
</script>
