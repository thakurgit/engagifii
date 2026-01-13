<?php
 $enabled_modules = get_option('engagifii_enabled_modules', array()); 
  $setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('classes', $enabled_modules)) {   
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

// Retrieve necessary data
$id = $_REQUEST['classId'] ?? null;
$options = get_option('ebt_api_settings');
$front_pages = $options['front_pages'] ?? [];
$classes_page = $front_pages['classes_page'] ?? null;
$classes_detail_page = $front_pages['classes_detail_page'] ?? null;

// Generate page links
$classes_page_link = $classes_page ? get_permalink($classes_page) : site_url() . '/classes/';
$classes_detail_page_link = $classes_detail_page ? get_permalink($classes_detail_page) : site_url() . '/class-details/';

// API and tenant configurations
$api_url = $options['ebt_api_url'] ?? '';
$tenant_url = $options['ebt_tenant_code']['engagifii_url'] ?? '';
if (!str_contains($tenant_url, 'http')) {
    $tenant_url = 'https://' . $tenant_url . '.engagifii.com';
}
//$class_visible_column_list = $options['class_visible_column_list'] ?? [];
$columnNames=[];
	if (!empty(CLASS_COLS) && isArrayOfJsonStrings(CLASS_COLS)) {
		  $columns = convertToObjectArray(CLASS_COLS);
		  $columnNames = extractColNames(CLASS_COLS);
	}
$loggedInUserId = $_SESSION['pid'] ?? null;
$tenantCode = $options['dashboard_tenant_code'] ?? '';
$env = $options['engagifii_apis']['environment'] ?? '';
$evn_url = 'https://' . ($options['evt_tenant_code']['engagifii_url'] ?? '') . '.engagifii' . $env . '.com';
$siteURL= site_url();
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$isMyProfile = strpos($url, 'my-profile') !== false;
// Fetch class details
$obj = new Engagifii_API();
if($isMyProfile){
    $response = $obj->getClassDetailsByIDForPerson($id);
}else{
    $response = $obj->getClassDetailsByID($id);
}

$classesData = $obj->getRelatedClassByClass($response->parentCourse->id, $id, 10);

// Handle navigation for previous and next classes
$class_array = json_decode(stripslashes($_COOKIE['classids'] ?? ''), true);
$class_key = $class_array ? array_search($id, $class_array) : null;
$class_count = $class_array ? count($class_array) - 1 : null;

$prev = $next = 0;
if ($class_key !== null) {
    $prev = $class_key > 0 ? $class_array[$class_key - 1] : 0;
    $next = $class_key < $class_count ? $class_array[$class_key + 1] : 0;
}

if (!function_exists('renderDisabledButton')) {
    function renderDisabledButton($tooltip) {
        ?>
        <div class="mt-auto">
            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo htmlspecialchars($tooltip); ?>">
                <button type="button" class="btn btn-primary px-3 py-1" disabled>Register</button>
            </span>
        </div>
        <?php
    }
}

if (!function_exists('renderRegisterButton')) {
    function renderRegisterButton($url, $label = 'Register', $classes = 'btn btn-primary px-3 py-1') {
        if (strpos($url, 'auth-callback') !== false) {
            echo "<button data-url=\"{$url}\" class=\"{$classes} open-pop\">{$label}</button>";
        } else {
            echo "<a class=\"{$classes}\" target=\"_blank\" href=\"{$url}\">{$label}</a>";
        }
    }
}

if ($loggedInUserId !== null && $loggedInUserId !== '') {
    $permissions = $obj->getUserPermissions($tenantCode, $loggedInUserId);
    $registerOthers = $permissions['registerOthers'];
    $registerOverride = $permissions['registerOverride'];
} else {
    $registerOthers = false;
    $registerOverride = false;
}
 
  $class_icon = $response->parentCourse->icon->iconReference;
 
?>
<div class="mb-2">
    <?php
    // Determine the current URL
   

    // Set the classes detail page link based on the URL
    if (strpos($url, 'my-profile') !== false) {
        $classes_detail_page_link = site_url() . '/my-profile/my-transcript/class-detail/';
    }
    ?>
    <a onclick="window.history.back();" style="cursor: pointer; color: #2568EF;" class="go-back">
        <i class="fal fa-arrow-left mr-2"></i> Go Back
    </a>
</div>

<div class="engagifii-box border border-bottom-0 p-2 p-lg-3">
    <div class="row">
        <div class="col-md-10 d-flex align-items-center">
            <img class="rounded-circle  mr-3  p-0" src="<?php echo $class_icon; ?>" style="max-width:78px; flex:0 0 78px">
            <div>
             <h3 class="mb-0 pb-1"><?php echo $response->parentCourse->name;?> </h3>
            <p  class="mb-2"> <?php echo $response->sectionName; ?></p>
            <?php if(is_array($response->classTag) && count($response->classTag)>0 && in_array('classTag', $columnNames)) {?>
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
          //print_r($response);
          if ($response->isClassRegistrationAllow && in_array('register', $columnNames)) {
            $registration_state = $response->registrationState;
            
            // Helper function to render disabled button with tooltip
            
        
            // Helper function to render registration button
            
            // Handle disabled states first
            $disabledStates = [
                'Registration Scheduled' => 'Registration opens from ' . date('M d, Y', strtotime($response->registrationStartFrom)),
                'Registration Not Setup' => 'Registration is not set up for this class.',
            ];
        
            if (isset($disabledStates[$registration_state])) {
                renderDisabledButton($disabledStates[$registration_state]);
            }
            // Handle completed/closed states
            elseif (in_array($registration_state, ['Completed', 'Registration Closed', 'Early Sold Out', 'Standard Sold Out']) && $registerOverride == 'false') {
                renderDisabledButton(preg_replace('/(?<!\ )[A-Z]/', ' $0', $registration_state));
            }
            // Handle already registered state
            elseif ($isAlreadyRegistered && $registerOthers == 'false') {
                renderDisabledButton('Already Registered');
            }
            // Handle active registration states
            else {
                $isMyProfile = strpos($url, 'my-profile') !== false;
                
                // Check capacity for my-profile
                if ($isMyProfile && $response->registrantsCapacity <= $response->classAttendeesCount) {
                    renderDisabledButton('Sold out');
                } else {
                    echo '<div class="mt-auto">';
                    
                    if ($response->classLocationType->name == "onlocationandonline") {
                        echo '<span id="locationButon" style="display:grid;">';
                        if ($isMyProfile) {
                            $baseUrl = "{$evn_url}/auth-callback/pages/home#access_token={$_SESSION['accesstoken']}&source=external&tpath=pages/classes/{$id}/classregpub/signup";
                            renderRegisterButton($baseUrl . '/onlocation/overview', 'Register In Person');
                            renderRegisterButton($baseUrl . '/online/overview', 'Register Online');
                        } else {
                            renderRegisterButton($response->registrationUrlOnLine, 'Register Online', 'btn btn-primary mb-2');
                            renderRegisterButton($response->registrationUrlOnLocation, 'Register In Person', 'btn btn-primary');
                        }
                        echo '</span>';
                    } else {
                        $locationType = $response->classLocationType->name;
                        if ($isMyProfile) {
                            $url = "{$evn_url}/auth-callback/pages/home#access_token={$_SESSION['accesstoken']}&source=external&tpath=pages/classes/{$id}/classregpub/signup/{$locationType}/overview";
                        } else {
                            $url = ($locationType == "online") ? $response->registrationUrlOnLine : $response->registrationUrlOnLocation;
                        }
                        renderRegisterButton($url);
                    }
                    
                    echo '</div>';
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
                <li class="nav-item">
			    	<a class="nav-link rounded-0 px-0 mx-3 text-dark" id="participants-tab" data-toggle="pill" href="#participants" role="tab" aria-controls="participants" aria-selected="false">Participants</a>
			  	</li>
			  <!-- 	<li class="nav-item">
			    	<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Document</a>
			  	</li> -->
			</ul>
            <div class="p-3">
               <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="row">
                      		<?php if(in_array('sessions', $columnNames)) {?>
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
                                      if($response->objectType && in_array('objectType', $columnNames)){
                                  ?>
                                  <div class="summary-content-para-engagiigii row mb-2">
                                      <div class="col-md-4 col-xl-3  mb-3 mb-md-0"><strong>Class Type:</strong></div>
                                      <div class="col-md-8 col-xl-9"><?php echo $response->objectType; ?></div>
                                  </div>
                                  <?php 
                                      } 
                                      //print_r(json_encode($response->courseCreditMapping[0]->credits));
                                      if($response->courseCreditMapping && in_array('credithours', $columnNames)){
                                        
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
                          <?php if(in_array('classInstructorsCount', $columnNames)){ ?>
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
                                  <?php if(in_array('classDuration', $columnNames)){ ?>
			  					<th class="duration">Duration</th>
                                  <?php } 
                                    if(in_array('objectType', $columnNames)){ ?>
			  					<th class="classType">Class Type</th>
                                  <?php } 
								   if(in_array('sessions', $columnNames)){ ?>
			  					<th class="classDates">Class Dates</th>
                                  <?php }
								   if(in_array('classInstructorsCount', $columnNames)){ ?>
			  					<th class="instructor">Instructor</th>
                                  <?php } 
                                   if(in_array('credithours', $columnNames)){ ?>
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
                                      <?php if(in_array('classDuration', $columnNames)){ ?>
                                      <td><?php echo $value->classDuration.' '.$value->classDurationType; ?></td>
                                      <?php }
									   if(in_array('objectType', $columnNames)){ ?>
                                      <td><?php echo $value->objectType; ?></td>
                                      <?php }
									   if(in_array('sessions', $columnNames)){?>
                                      <td>
                                          <div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_<?php echo $key; ?> " data-placement="left" data-containerid="<?php echo $key; ?>" id="<?php echo $key; ?>">
                                          <img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/Agenda.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo count($value->classSessionSettings); ?></span></div><?php echo $classPopover; ?></div>
                                              
                                      </td>
                                      <?php }
									   if(in_array('classInstructorsCount', $columnNames)){ ?>
                                      <td><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_<?php echo $key ?> " data-placement="left" data-containerid="<?php echo $key ?>" id=" <?php echo $key ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo $value->classInstructorsCount; ?></span></div><?php echo $instructorPopOver; ?></div></td> 
									  <?php }
									  if(in_array('credithours', $columnNames)){?>
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
                  
                  <!-- Participants Tab -->
                  <div class="tab-pane fade" id="participants" role="tabpanel" aria-labelledby="participants-tab">
                      <style>
                          /* Participants Table Styles */
                          #participantsTable th.participant-select,
                          #participantsTable td.participant-select {
                              width: 50px !important;
                              text-align: center;
                          }
                          
                          #participantsTable th.searchparticipant,
                          #participantsTable td.searchparticipant {
                              min-width: 200px !important;
                          }
                          
                          #participantsTable th.searchorganization,
                          #participantsTable td.searchorganization {
                              min-width: 180px !important;
                          }
                          
                          #participantsTable th.actions,
                          #participantsTable td.actions {
                              width: 100px !important;
                              text-align: center;
                          }
                          
                          #participants-overlay {
                              position: absolute;
                              top: 0;
                              left: 0;
                              width: 100%;
                              height: 100%;
                              background: rgba(255, 255, 255, 0.8);
                              display: none;
                              z-index: 999;
                          }
                          
                          #participants-overlay .spinner {
                              position: absolute;
                              top: 50%;
                              left: 50%;
                              transform: translate(-50%, -50%);
                              border: 4px solid #f3f3f3;
                              border-top: 4px solid #3498db;
                              border-radius: 50%;
                              width: 40px;
                              height: 40px;
                              animation: spin 1s linear infinite;
                          }
                          
                          @keyframes spin {
                              0% { transform: translate(-50%, -50%) rotate(0deg); }
                              100% { transform: translate(-50%, -50%) rotate(360deg); }
                          }
                          
                          .po-filter-participants .filter-toggle span {
                              font-size: 10px;
                              min-width: 18px;
                              height: 18px;
                              padding: 2px 4px;
                          }
                          
                          .po-filter-participants.ft-selected .filter-toggle {
                              background-color: #007bff !important;
                              color: white !important;
                          }
                          
                          .po-filter-participants .ft-active {
                              background-color: #f8f9fa;
                          }
                          
                          .po-filter-participants .ft-counter {
                              color: #007bff;
                          }
                          
                          #participantsTable tr.selected {
                              background-color: #e3f2fd !important;
                          }
                      </style>
                      <div class="p-3">
                          <div class="mb-3 d-flex align-items-center justify-content-between">
                              <div class="d-flex align-items-center">
                                  <h5 class="mb-0 mr-3">
                                      <button type="button" title="Refresh Participants" class="refresh-participants btn shadow-none p-2 mr-1">
                                          <i class="fas fa-sync"></i>
                                      </button>
                                      <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/Member-Icon.png" class="img-fluid" alt="participant-icon" style="max-width:40px">
                                  </h5>
                                  <h6 class="mb-0">Class Participants</h6>
                              </div>
                               <button  type="button" class="btn btn-primary btn-sm  ml-auto ga"  title="Select Member" disabled><i class="far fa-file-pdf mr-2"></i>Generate Badges</button>
				<button  type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm  ml-2 gt"  title="Select Member" disabled><i class="far fa-file-pdf mr-2"></i>Generate Transcripts</button>
                              
                              <!-- Filters -->
                              <div class="dropdown dropleft po-filter-participants d-flex justify-content-end ml-3">
                                  <button class="btn border rounded-circle filter-toggle bg-light d-flex align-items-center justify-content-center position-relative" type="button" data-toggle="dropdown" aria-expanded="false">
                                      <i class="far fa-filter"></i>
                                  </button>
                                  <div class="dropdown-menu py-0" style="min-width: 300px;">
                                      <div class="filter-top-bg py-2 px-3 bg-dark text-white d-flex align-items-center">
                                          <span class="filter-title"><i class="far fa-filter mr-2"></i> Filter</span>
                                          <span class="clear-all-participants ml-auto" title="Reset Filter" style="cursor:pointer;">Clear All</span>
                                      </div>
                                      <div class="accordion" id="accordionParticipantFilter">
                                          <!-- Filter options will be added here dynamically via JS -->
                                          <div class="filter-list border-bottom" data-filter="completionstatus">
                                              <h5 class="mb-0">
                                                  <button class="btn btn-block text-left d-flex align-items-center shadow-none px-3 py-1" type="button" data-toggle="collapse" data-target="#filter-completion">
                                                      Completion Status <span class="ml-2 font-weight-bold ft-counter text-black"></span><i class="fal fa-chevron-down ml-auto"></i>
                                                  </button>
                                              </h5>
                                              <div id="filter-completion" class="collapse px-3" data-parent="#accordionParticipantFilter">
                                                  <ul class="list-group td-dropdown mb-3" style="overflow:auto; max-height:200px">
                                                      <div class="loaders text-center py-3">
                                                          <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                                      </div>
                                                  </ul>
                                              </div>
                                          </div>
                                          
                                          <div class="filter-list border-bottom bg-light" data-filter="registrationstatus">
                                              <h5 class="mb-0">
                                                  <button class="btn btn-block text-left d-flex align-items-center shadow-none px-3 py-1" type="button" data-toggle="collapse" data-target="#filter-registration">
                                                      Registration Status <span class="ml-2 font-weight-bold ft-counter text-black"></span><i class="fal fa-chevron-down ml-auto"></i>
                                                  </button>
                                              </h5>
                                              <div id="filter-registration" class="collapse px-3" data-parent="#accordionParticipantFilter">
                                                  <ul class="list-group td-dropdown mb-3" style="overflow:auto; max-height:200px">
                                                      <div class="loaders text-center py-3">
                                                          <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                                      </div>
                                                  </ul>
                                              </div>
                                          </div>
                                          
                                          <div class="filter-list border-bottom" data-filter="classformat">
                                              <h5 class="mb-0">
                                                  <button class="btn btn-block text-left d-flex align-items-center shadow-none px-3 py-1" type="button" data-toggle="collapse" data-target="#filter-format">
                                                      Class Format <span class="ml-2 font-weight-bold ft-counter text-black"></span><i class="fal fa-chevron-down ml-auto"></i>
                                                  </button>
                                              </h5>
                                              <div id="filter-format" class="collapse px-3" data-parent="#accordionParticipantFilter">
                                                  <ul class="list-group td-dropdown mb-3" style="overflow:auto; max-height:200px">
                                                      <div class="loaders text-center py-3">
                                                          <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                                      </div>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="text-center py-2">
                                          <button class="btn btn-primary py-1" type="button" id="apply-participant-filter">
                                              Apply<span class="mx-1" id="participantFilterResult"></span>
                                              <div class="spinner-border spinner-border-sm d-none mb-1" role="status"><span class="sr-only">Loading...</span></div>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          
                          <!-- Participants Table -->
                          <div class="engagifii-box position-relative">
                              <table id="participantsTable" class="table table-bordered border-0 table-striped main-list-here nowrap" style="width: 100% !important;">
                                  <thead>
                                      <tr>
                                          <th class="participant-select"><input type="checkbox"></th>
                                          <th class="searchparticipant">Search Participant</th>
                                          <th class="searchinvoices">Search Invoices</th>
                                          <th class="completionstatus">Completion Status (Access Class)</th>
                                          <th class="timespent">Time Spent (Access Class)</th>
                                          <th class="markedcredithours">Marked Credit Hours</th>
                                          <th class="registeredon">Registered On</th>
                                          <th class="searchorganization">Search Organization</th>
                                          <th class="earnedcredithours">Earned Credit Hours (Finalized)</th>
                                          <th class="classformat">Class Format</th>
                                          <th class="currentposition">Current Position</th>
                                          <th class="currentdepartment">Current Department</th>
                                          <th class="title">Title</th>
                                          <th class="grantedby">Granted By</th>
                                          <th class="registrationstatus">Registration Status</th>
                                          <th class="actions">Actions</th>
                                      </tr>
                                  </thead>
                              </table>
                              <div id="participants-overlay"><span class="spinner"></span></div>
                          </div>
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
			"order": [[<?php echo array_search('sessions',$columnNames);?>, 'asc']],
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

    // ==================== PARTICIPANTS TAB FUNCTIONALITY ====================
    
    // Initialize variables for participants table
    var participantFilters = {
        completionStatus: [],
        registrationStatus: [],
        classFormat: [],
        selectedParticipants: []
    };
    var participantsTable;
    var classId = '<?php echo $id; ?>';
    var totalParticipants = 0;
    
    // Initialize Participants DataTable
    function initParticipantsTable() {
        if (!$.fn.DataTable.isDataTable('#participantsTable')) {
            participantsTable = $('#participantsTable').DataTable({
                "pageLength": 10,
                "dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
                "bInfo": false,
                "processing": true,
                "searching": true,
                "ordering": true,
                "order": [[1, 'asc']], // Sort by participant name
                "columnDefs": [
                    {
                        "targets": ['participant-select', 'searchinvoices', 'timespent', 'markedcredithours', 
                                   'earnedcredithours', 'currentposition', 'currentdepartment', 'grantedby', 
                                   'registrationstatus', 'actions'],
                        "orderable": false
                    },
                    { className: "text-center", "targets": ['participant-select', 'completionstatus', 'timespent', 
                                                             'markedcredithours', 'earnedcredithours', 'classformat', 
                                                             'registrationstatus', 'actions'] }
                ],
                "language": {
                    processing: '<span>&nbsp;</span>',
                    "emptyTable": 'No participants found',
                    search: '',
                    searchPlaceholder: "Search participants"
                },
                "oLanguage": {
                    "sLengthMenu": "Show _MENU_ records per page"
                },
                "serverSide": true,
                "ajax": {
                    "url": engagifiiUrl_ajaxurl,
                    "type": "POST",
                    "data": function(d) {
                        // TODO: Replace with actual action when API is ready
                        d.action = 'getClassParticipants'; // Placeholder action
                        d.classId = classId;
                        d.completionStatus = participantFilters.completionStatus;
                        d.registrationStatus = participantFilters.registrationStatus;
                        d.classFormat = participantFilters.classFormat;
                    }
                },
                "columns": [
                    { "data": "select", "render": function(data, type, row) {
                        return '<input type="checkbox" class="select-participant" value="' + row.participantId + '">';
                    }},
                    { "data": "participantName" },
                    { "data": "invoiceNumber" },
                    { "data": "completionStatus" },
                    { "data": "timeSpent" },
                    { "data": "markedCreditHours" },
                    { "data": "registeredOn" },
                    { "data": "organization" },
                    { "data": "earnedCreditHours" },
                    { "data": "classFormat" },
                    { "data": "currentPosition" },
                    { "data": "currentDepartment" },
                    { "data": "title" },
                    { "data": "grantedBy" },
                    { "data": "registrationStatus" },
                    { "data": "actions", "render": function(data, type, row) {
                        return '<button class="btn btn-sm btn-primary view-participant" data-id="' + row.participantId + '">View</button>';
                    }}
                ],
                "drawCallback": function(settings) {
                    totalParticipants = settings._iRecordsTotal;
                    dt_dropdown();
                    $('[data-toggle="tooltip"]').tooltip();
                    
                    // Handle select all checkbox
                    $('.participant-select :checkbox').off('change').on('change', function() {
                        if ($(this).is(':checked')) {
                            $('.select-participant').prop('checked', true).change();
                        } else {
                            $('.select-participant').prop('checked', false).change();
                        }
                    });
                    
                    // Handle individual participant selection
                    $('.select-participant').off('change').on('change', function() {
                        var participantId = $(this).val();
                        if ($(this).is(':checked')) {
                            if (!participantFilters.selectedParticipants.includes(participantId)) {
                                participantFilters.selectedParticipants.push(participantId);
                            }
                            $(this).parents('tr').addClass('selected');
                        } else {
                            var index = participantFilters.selectedParticipants.indexOf(participantId);
                            if (index > -1) {
                                participantFilters.selectedParticipants.splice(index, 1);
                            }
                            $(this).parents('tr').removeClass('selected');
                        }
                        
                        // Update select all checkbox state
                        var totalChecked = $('.select-participant:checked').length;
                        var totalCheckboxes = $('.select-participant').length;
                        
                        if (totalChecked === 0) {
                            $('.participant-select :checkbox').prop('checked', false).prop('indeterminate', false);
                        } else if (totalChecked === totalCheckboxes) {
                            $('.participant-select :checkbox').prop('checked', true).prop('indeterminate', false);
                        } else {
                            $('.participant-select :checkbox').prop('indeterminate', true);
                        }
                    });
                },
                "initComplete": function(settings, json) {
                    $('#participants-overlay').css('display', 'none');
                    
                    // Add column search for participant name and organization
                    dt_columnSearch(1, 'Search Participant');
                    dt_columnSearch(7, 'Search Organization');
                }
            });
            
            // Handle processing indicator
            $('#participantsTable').on('processing.dt', function(e, settings, processing) {
                $('#participants-overlay').css('display', processing ? 'block' : 'none');
            });
        }
    }
    
    // Load participants table when tab is shown
    $('#participants-tab').on('shown.bs.tab shown.bs.pill', function(e) {
        if (!participantsTable) {
            initParticipantsTable();
        } else {
            participantsTable.draw();
        }
    });
    
    // Refresh participants table
    $('.refresh-participants').on('click', function() {
        if (participantsTable) {
            participantsTable.draw();
        }
    });
    
    // Apply participant filters
    $('#apply-participant-filter').on('click', function() {
        $(this).attr('disabled', '');
        $('#apply-participant-filter .spinner-border').removeClass('d-none');
        
        // Collect filter values
        participantFilters.completionStatus = $.map($('input[name="completionStatus[]"]:checked'), function(c) {
            return c.value;
        });
        participantFilters.registrationStatus = $.map($('input[name="registrationStatus[]"]:checked'), function(c) {
            return c.value;
        });
        participantFilters.classFormat = $.map($('input[name="classFormat[]"]:checked'), function(c) {
            return c.value;
        });
        
        // Update filter UI
        if ($(".po-filter-participants ul input:checkbox:checked").length > 0) {
            $('.po-filter-participants').addClass('ft-selected');
            var activeFilters = $('.po-filter-participants .ft-active').length;
            if ($('.po-filter-participants .filter-toggle span').length === 0) {
                $('.po-filter-participants .filter-toggle').append('<span class="badge badge-danger position-absolute" style="right:-6px; top:-6px">' + activeFilters + '</span>');
            } else {
                $('.po-filter-participants .filter-toggle span').text(activeFilters);
            }
        } else {
            $('.po-filter-participants').removeClass('ft-selected');
            $('.po-filter-participants .filter-toggle span').remove();
        }
        
        // Redraw table with filters
        if (participantsTable) {
            participantsTable.draw();
        }
        
        $('#apply-participant-filter').removeAttr('disabled');
        $('#apply-participant-filter .spinner-border').addClass('d-none');
    });
    
    // Clear all participant filters
    $('.clear-all-participants').on('click', function() {
        participantFilters.completionStatus = [];
        participantFilters.registrationStatus = [];
        participantFilters.classFormat = [];
        
        $('.po-filter-participants input[type=checkbox]').prop('checked', false);
        $('.po-filter-participants .ft-active').removeClass('ft-active');
        $('.po-filter-participants .ft-counter').text('');
        $('.po-filter-participants').removeClass('ft-selected');
        $('.po-filter-participants .filter-toggle span').remove();
        $('#participantFilterResult').text('');
        
        if (participantsTable) {
            participantsTable.draw();
        }
    });
    
    // Filter list functionality for participants
    $('.po-filter-participants ul').each(function() {
        $('input', this).on('change', function() {
            var ftSelected = $(this).parents('ul').find('input:checkbox:checked').length;
            if (ftSelected > 0) {
                $(this).parents('.border-bottom').addClass('ft-active').find('.ft-counter').text('(' + ftSelected + ')');
            } else {
                $(this).parents('.border-bottom').removeClass('ft-active').find('.ft-counter').text('');
            }
        });
    });
    
    // Handle view participant action
    $(document).on('click', '.view-participant', function() {
        var participantId = $(this).data('id');
        // TODO: Implement view participant details functionality
        console.log('View participant:', participantId);
    });
    
    // Prevent dropdown from closing when clicking inside
    $(document).on('click', '.po-filter-participants .dropdown-menu', function(e) {
        e.stopPropagation();
    });
    
    // ==================== END PARTICIPANTS TAB FUNCTIONALITY ====================
</script>

