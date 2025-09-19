<?php 
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
	$id 		= $_REQUEST['endId'] ?? null;
	$workflowid 		= $_REQUEST['wId'] ?? null;
	$roleid 		= $_REQUEST['rId'] ?? null;
	$attendeesCount 		= $_REQUEST['attendeeCount'] ?? null;
	$obj 			=  new Engagifii_API();
	 $enabled_modules = get_option('engagifii_enabled_modules', array()); 
   if (!in_array('events', $enabled_modules)) {   
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}
	$response       =  $obj->getEventDetailsByID($id);
	$postData=array();
		$responseArray = array();
		$apiUrl = 'public/eventactivity/list';
		$postData['pageNumber'] = 1;
		$postData['pagesize'] = 10;
		$postData['eventId']              = $id;
		$postData['sortBy']        = 'StartDateTime';
	
		$eventClassesCount = isset($response->eventClasses) && is_array($response->eventClasses) ? count($response->eventClasses) : 0;
	
	$classesData = $obj->getRelatedClassByEvents($id, $eventClassesCount);
	$eventBundlesCount = isset($response->eventBundles) && is_array($response->eventBundles) ? count($response->eventBundles) : 0;

	$bundleData = $obj->getRelatedBundleByEvents($id, $eventBundlesCount);
	$dataResponse = $this->submitApiRequest("public/eventactivity/list",$postData,"POST",'event');
	$collections  = json_decode($dataResponse['api_response'])->collection;
	
	$options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $events_page = $front_pages['events_page'];
    $events_detail_page = $front_pages['events_detail_page'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($events_page){
	$events_page=get_permalink( $events_page );	
	}else{
	$events_page= site_url() .'/events/';	
	}
	if($events_detail_page){
	$events_detail_page_link=get_permalink( $events_detail_page );	
	}else{
	$events_detail_page_link= site_url() .'/event-detail/';	
	}
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
	if($course_detail_page){
		$course_detail_page_link=get_permalink( $course_detail_page );	
	}else{
		$course_detail_page_link= site_url() .'/course-details/';	
	}
    $api_url = $options['ebt_api_url'];
	$env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
    $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii.com';
	$evn_url = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii'.$env.'.com';
	$tenantCode = $options['ebt_tenant_code']['tenant_code'];
	
	$apiUrl = $api_url.$env.'/public/eventactivity/list';
	$postData['pageNumber'] = 1;
	$options = get_option('ebt_api_settings');
    //$events_visible_column_list = $options['events_visible_column_list'];
	$columnNames=[];
		if (!empty(EVENTS_COLS) && isArrayOfJsonStrings(EVENTS_COLS)) {
			  $columnNames = extractColNames(EVENTS_COLS);
		}
	$loggedInUserId = $_SESSION['pid'];
    //$tenantCode = $options['ebt_tenant_code']['tenant_code'];
	$tenantCode = $options['dashboard_tenant_code'];
	$env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
	$contactPersons = $response->contacts ?? [];
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
	if($_COOKIE['courseids']){
	$class_array = @json_decode(stripslashes($_COOKIE['courseids']), true);
  $class_key = array_search ($_GET['courseId'], $class_array);
  $class_count = count($class_array)-1;
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
}

?>
<div class="mb-2">
<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    $url.= $_SERVER['HTTP_HOST'];   
    $url.= $_SERVER['REQUEST_URI'];    
if ( strpos($url,'my-profile') !== false ) {
		$classes_detail_page_link= site_url() .'/my-profile/my-transcript/class-detail/'; 
		$course_detail_page_link= site_url() .'/my-profile/my-transcript/course-details/';?>
    <a href="<?php echo site_url().'/my-profile/events/';?>" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<?php } else { ?>
    <a onclick="window.history.back();" style="cursor: pointer; color: #2568EF;" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<?php } 
?>

</div>

<div class="engagifii-box border border-bottom-0 p-2 p-lg-3">
	<div class="row">
        <div class="col-md-10 d-flex align-items-center">
            <img class="img-circle img-icon-lg img-fluid mr-3" src="<?php echo $response->imageUrl; ?>" style="max-width:78px;">
			
        <div>
            <h3 class="mb-0 pb-1"><?php echo $response->name;?> </h3>
			<?php if(in_array('eventType', $columnNames)) { ?>
			<div class="mb-2">
                <span>Event type: </span>
                <span class="pl-1 pr-1"> <?php echo $response->eventType;  ?></span>
</div>
<?php } ?>
            <?php if(in_array('tags', $columnNames) && is_array($response->tags) && count($response->tags)>0) {
            ?>
            <div class="">
                <span><i class="fas fa-tags mr-1"></i>Tag(s):</span>
                <span class="pl-1"> <?php echo count($response->tags)-1;  ?></span>
                <?php
                	foreach ($response->tags as $key => $value) {
                		?>
                			<span class="badge badge-pill badge-light text-capitalize border mr-2 font-weight-normal"><?php echo $value->tags; ?></span>
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
           <div class="d-flex align-items-center mb-2">
        	<?php
                if($prev){
              ?>
              <a class="text-muted <?php if($next){echo 'pr-2'; }?>" href="<?php echo $events_detail_page_link;?>?courseId=<?php echo $prev; ?>"><i class="fal fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a class="text-muted" href="<?php echo $events_detail_page_link;?>?courseId=<?php echo $next; ?>"> <i class="fal fa-arrow-right"></i></a>
              <?php
                }
              ?>
            <a href="<?php echo $events_page;?>/courses/" class="p-2 mr-2 text-muted d-none"><i class="fa fa-times"></i></a>
        </div>
		
          <?php 
		  $event_status = $response->eventStatus;
		  $registration_state = $response->eventRegistrationState;
		  $isAlreadyRegistered = $response->registrationWorkflows[0]->isAlreadyRegistered;
		  $default_RegisterBtn = "";
		  if(in_array('register', $columnNames)) {
			if (($event_status == 'Completed' || $registration_state == 'RegistrationClosed' || $registration_state == 'RegistrationNotStarted' || $registration_state == 'RegistrationScheduled') && ($registerOverride=='false')) {
			 if($registration_state == 'RegistrationScheduled'){
			 $tooltip = 'Registration opens from '.date('M d, Y', strtotime($response->registrationStartFrom)); ?>
		  <div class="mt-auto">				
			<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip; ?>"><button type="button" id="onlocation12" class="btn btn-primary  px-3 py-1"  disabled >Register</button></span>
		  </div>
			<?php }else{
			 $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $registration_state); ?>
		  <div class="mt-auto">				
			<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip; ?>"><button type="button" id="onlocation12" class="btn btn-primary  px-3 py-1"  disabled >Register</button></span>
		  </div>
        	<?php  }
		   
		   }  else if (($isAlreadyRegistered) && ($registerOthers=='false')) {
                $alreadyRegisteredText = "Already Registered";
                $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $alreadyRegisteredText); ?>
                <div class="mt-auto"><span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip;?>"><button type="button"  class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span></div>
		 <?php } else{
			  //$tooltip = 'Registration opens from '.date('M d, Y', strtotime($response->registrationStartFrom));
			  ?>
			<div class="mt-auto">	
            <?php if ( strpos($url,'my-profile') !== false ) { 
				if($workflowid==''){
                    $tooltip = 'You are not authorized to register for this event. Please contact the event contact.'; ?>
					<div class="mt-auto"><span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip;?>"><button type="button"  class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span></div>
                 <?php } else{ 
					if($response->registrantsCapacity > $attendeesCount){ 
						?>
			           <button 
        data-url="<?php echo $evn_url; ?>/auth-callback/pages/home#access_token=<?php echo $_SESSION['accesstoken']; ?>&source=external&tpath=pages/events/<?php echo $id; ?>/<?php echo $workflowid; ?>/<?php echo $roleid; ?>/eventregpub/signup/overview" 
        class="btn btn-primary px-3 py-1 open-pop">
        Register
    </button>
			<?php } 
			else{
				$tooltip = 'Sold out'; ?>
				<div class="mt-auto"><span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="<?php echo $tooltip;?>"><button type="button"  class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span></div>
			<?php }}}
			else { ?>
			<a class="btn btn-primary " target="_blank" href="<?php echo $tenant_url.'/pages/events/'. $id .'/general'; ?>">Register</a></div>  
            <?php } 
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
				<?php if(is_array($classesData->collection) && count($classesData->collection)){ ?>
			  	<li class="nav-item">
			    	<a class="nav-link rounded-0 px-0 mx-3 text-dark" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Classes</a>
			  	</li>
				<?php } if(($collections) && count($collections)){ ?>
			  	<li class="nav-item">
			    	<a class="nav-link rounded-0 px-0 mx-3 text-dark" id="session-tab" data-toggle="pill" href="#session" role="tab" aria-controls="session" aria-selected="false">Sessions</a>
			  	</li>
				<?php } if(($bundleData)){?>
				  <li class="nav-item">
				  <a class="nav-link rounded-0 px-0 mx-3 text-dark" id="bundles-tab" data-toggle="pill" href="#bundles" role="tab" aria-controls="bundle" aria-selected="false">Bundles</a>
			    	
			  	</li>
				<?php } ?>
				  <!--<li class="nav-item">
			    	<a class="nav-link" id="speaker-tab" data-toggle="tab" href="#speaker" role="tab" aria-controls="speaker" aria-selected="false">Speakers</a>
			  	</li> -->
			</ul>
            <div class="p-3">
               <div class="tab-content" id="pills-tabContent">
			  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			  		<div class="row">
					  		<div class="col-sm-7 mb-3 ">
					  			<div class="border rounded shadow-sm h-100">
					  			<div class="panel-title bg-light p-2  border-bottom">
                                    <h6 class="mb-0 font-weight-normal">Event Details</h6>
		                        </div>
                                <div class="p-3">
		                        <?php
		                        	if(trim($response->description)){
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <!-- <div class="col-sm-4 p-0">Description:</div> -->
		                            <div class="col-sm-12"><?php echo trim($response->description); ?></div>
		                        </div>
		                        <?php
		                        	}
									if(in_array('eventType', $columnNames) && $response->eventType) {
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Event Type:</div>
		                            <div class="col-sm-8"><?php echo $response->eventType; ?></div>
		                        </div>
		                        <?php
		                        	}
								
									if(in_array('startDateTime', $columnNames) && $response->startDateTime) {
										$defaulget_Date = $response->startDateTime;
										$convert_Date = strtotime($defaulget_Date);
										$date = date('M d, Y', $convert_Date);
										$time = date('g:i A', $convert_Date);
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                        	<div class="col-sm-4 ">Event Start Date:</div>
		                        	<div class="col-sm-8"><?php echo $date." at ".$time; ?></div>
		                        </div>
		                        <?php
		                        	
		                        	if($response->endDateTime){
										$defaulget_Date = $response->endDateTime;
										$convert_Date = strtotime($defaulget_Date);
                                            $date = date('M d, Y', $convert_Date);
											$time = date('g:i A', $convert_Date); //$convert_Date->format('h:i:s A');
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Event End Date:</div>
		                            <div class="col-sm-8"><?php echo $date." at ".$time; ?></div>
		                        </div>
		                        <?php
		                        	}
								}
								if(in_array('register', $columnNames)) {
									if ($response->registrationStartFrom){ ?>
								<div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Registration Start Date:</div>
		                            <div class="col-sm-8"><?php echo date('M d, Y', strtotime($response->registrationStartFrom)); ?> at <?php echo date('g:i A', strtotime($response->registrationStartFrom)); ?></div>
		                        </div>
                                <?php } if($response->registrationEndDate) { ?>
								<div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Registration End Date:</div>
		                            <div class="col-sm-8"><?php echo date('M d, Y', strtotime($response->registrationEndDate)); ?> at <?php echo date('g:i A', strtotime($response->registrationEndDate)); ?></div>
		                        </div>

							<?php 	} } if(isset($response->skills) && count($response->skills) > 0){
		                        ?>	
		                         <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Skills:</div>
		                            <div class="col-sm-8"><?php foreach ($response->skills as $key => $value) {
		                            ?>
		                            		<span class="border round-tag p-2 text-capitalize"><?php echo $value->name; ?></span>
		                            <?php
		                            } ?></div>
		                        </div>
		                        <?php
		                        	}
		                        ?>
		                    </div>
		                    </div>
					  		</div>
					  		<div class="col-sm-5 mb-3">
					  			<div class="border rounded shadow-sm h-100"> 
						  			<div class="panel-title bg-light p-2  border-bottom">
                                    <h6 class="mb-0 font-weight-normal">Event Schedule</h6>
			                        </div>
                                   <div class="p-3">
                                   	<?php
									//if(in_array('startDateTime', $columnNames) && count($response->eventDates)){
										if (is_array($columnNames) && in_array('startDateTime', $columnNames) &&
											isset($response->eventDates) && is_array($response->eventDates) && count($response->eventDates)
										) {	
		                    			foreach ($response->eventDates as $key => $value) {
		                    				$position  = $value->position;
		                    				$department = $value->$department;
											$sessionStart_Date = strtotime($value->sessionStartTime);
											$startDate = date('M d, Y', $sessionStart_Date);
											$startTime = date('g:i A', $sessionStart_Date);
											$sessionEnd_Date = strtotime($value->sessionEndTime);
											$endDate = date('M d, Y', $sessionEnd_Date);
											$endTime = date('g:i A', $sessionEnd_Date);

		                    		?>
		                    			<div class="border rounded p-3 pt-xl-2 mb-3 bg-light">
		                    					<h6 class="pb-2 mb-2 border-bottom"> Day <?php echo $key+1 ?> </h6>
                                                <ul class="list-unstyled mb-0 lh-lg">
                                                	<li><?php echo $value->sessionDay.", ".$startDate.' at '.$startTime." To ".$endTime; ?></li>
													<?php if($value->name) { ?>
                                                    <li><strong>Venue:</strong> <?php echo $value->name; ?></li>
												 <?php } if($value->addressLine) { ?>
                                                    <li><strong>Address: </strong>
												<?php echo $value->addressLine." ".$value->city." ".$value->state." ".$value->zip; ?>, <?php echo $value->country; ?></li>
												<?php } 
												if($value->latitude && $value->longitude){
												echo '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q='.$value->latitude.','.$value->longitude.'&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div>';
												}
												?>
												
                                                </ul>
		                    			</div>
		                    		<?php
		                    			}
		                    		}else{
									echo '<h6 class="pb-2 mb-2 border-bottom">It looks like the option to show an event schedule has been disabled. Please contact the admin for assistance. </h6>';
								}
		                    		?>
                                   </div>
		                    	</div>
					  		</div>
			  		</div>
			  		<div class="row" style="display:none;">
			  			<div class="col-12">
			  				<div class="border rounded shadow-sm">
			  					<div class="panel-title bg-light p-2  border-bottom">
                                      <h6 class="mb-0 font-weight-normal">Contacts </h6>
                                  </div>
		                    	 <div class="p-3">
                                  	<div class="row">
		                    		


		                    		<?php
		                    		if(count($contactPersons)){
		                    			
		                    			foreach ($contactPersons as $key => $value) {
		                    				$position  = $value->position;
		                    				$department = $value->$department;

		                    		?>
                                    <div class="col-md-4">
		                    			<div class="card">
		                    				<div class="card-body d-flex py-3 px-2 py-lg-4 align-items-start instructor-detail">
		                    					<div class="col-3">
		                    					<?php if (filter_var($value->imageUrl, FILTER_VALIDATE_URL)) { ?>
		                    						<img src="<?php echo $value->imageUrl;?>" class="img-fluid mr-2">
		                    					<?php
		                    						}
		                    						else if($value->imageUrl)
		                    						{
		                    					?>
		                    							<img src="<?php echo $tenant_url.$value->imageUrl;?>" class=" img-fluid mr-2">
		                    					<?php		
		                    						}else{
		                    					?>
		                    								<img src="<?php echo ENGAGIFII_ASSETS_URL.'/images/user-default.png'; ?>" class="img-fluid mr-2">
		                    					<?php
		                    						}
		                    					?>
		                    					</div>
		                    					<div class="col-6">
                                                <p class="card-title mb-2"><?php echo $value->fisrtName;?></p>
                                                <ul class="list-unstyled mb-0 ml-0 d-flex flex-wrap">
                                                <li class="position-relative inst-ac ml-0">
                                                	<div class="dropdown">
														    <!-- <span class="badge badge-secondary position-absolute rounded-circle"><?php //echo count($courses); ?></span> -->
														  </button>
														  <ul class="dropdown-menu p-1" aria-labelledby="course_<?php echo $key; ?>">
														  	<?php
														  			foreach ($courses as $index => $val) {
														  	?>
														  				 <li class="position-relative p-1 inst-ac ml-0 d-inline-flex align-items-center justify-content-start"><img src="<?php echo $val->icon->iconReference ?>" class="img-fluid mr-2"> <span style="font-size: 14px; white-space:nowrap; padding: 0px 15px 0 0;"><?php echo $val->name; ?></span></li>
														  	<?php
														  			}
														  	?>
														   
														    
														  </ul>
														</div>
                                                        </li>
                                                	
                                                    <!-- <li class="position-relative inst-ac ml-0">

                                                	<div class="dropdown">
														  <button class="btn shadow-none no-border dropdown-toggle" type="button" id="skills_<?php echo $key; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
														  	style="background:url('<?php echo ENGAGIFII_ASSETS_URL; ?>/images/class.png');background-size:contain;background-repeat:no-repeat;width:35px;height:35px">
														    <span class="badge badge-secondary position-absolute rounded-circle"><?php //echo count($skills); ?></span>
														  </button>
														  <ul class="dropdown-menu p-1" aria-labelledby="skills_<?php echo $key; ?>">
														  	<?php
														  			foreach ($skills as $index => $val) {
														  	?>
														  				 <li class="position-relative p-1 inst-ac ml-0 d-inline-flex align-items-center justify-content-start"><img src="<?php echo $val->icon->iconReference ?>" class="img-fluid mr-2"> <span style="font-size: 14px; white-space:nowrap; padding: 0px 15px 0 0;"><?php echo $val->name; ?></span></li>
														  	<?php
														  			}
														  	?>
														   
														    
														  </ul>
														</div>
                                                    	
                                                        
                                                    </li> -->
                                                </ul>
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
			  		</div>
			  	</div>
			  	<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			  		<div class="p-3">
                    <style>
					table.class-table th:nth-child(1) {
  width: 300px !important;
}

table.class-table td:nth-child(1) {
  width: 300px !important;
}
					</style>
			  		<table class="table table-bordered border-0 table-striped class-table" id="ebtmaintable" width="100%">
			  			<thead>
			  				<tr>
			  					<th class="class">Class</th>
			  					<th class="duration">Duration</th>
			  					<!-- <th class="classType">Class Type</th> -->
			  					<th class="classDates">Class Dates</th>
			  					<th class="instructor">Instructor</th>
			  					<th class="creditHours">Credit Hours</th>
			  					
			  				</tr>
			  			</thead>
			  			<tbody>
			  				<?php
			  					if(is_array($classesData->collection) && count($classesData->collection)){
			  						
			  						foreach ($classesData->collection as $key => $value) {
			  				?>
			  					<tr class="bg-white">
			  						<td><span><a href="<?php echo $course_detail_page_link; ?>?courseId=<?php echo $value->parentCourse->id; ?>" style="color: rgb(189, 16, 224);"><?php echo mb_substr($value->parentCourse->name, 0,25); ?></a><br>
									<a href="<?php echo $classes_detail_page_link; ?>?classId=<?php echo $value->id; ?>"><?php echo $value->sectionName; ?></a><br/>
			  							<small><?php 
			  								if(!empty($value->startDate) ){
			  									echo date('M d, Y', strtotime($value->startDate)); 
			  									if($value->classDuration > 1)
			  									{
			  										echo ' - '.date('M d, Y', strtotime($value->endDate));
			  									}
			  									 echo ' at '.date("g:i A",strtotime($value->startDate)).' - '.date("g:i A",strtotime($value->endDate));
			  								}
			  							 ?></small>
			  						</span></td>
			  						<td><?php echo $value->classDuration.' '.$value->classDurationType; ?></td>
			  						<!-- <td><?php //echo $value->objectType; ?></td> -->
			  						<td> <?php
									if(count($value->classSessionSettings)){
										$rowName = array();
										$popOverHtml = dd_header('Class Dates');
										$subItems = "";
										$li=1;
										foreach ($value->classSessionSettings as $key => $rowData) {
											$classTime = '';
											$classTime = date('M d, Y', strtotime($rowData->sessionStartTime)).' At '.date("g:i A",strtotime($rowData->sessionStartTime)).' - '.date("g:i A",strtotime($rowData->sessionEndTime));
											$class='';
											if($li%2==1){
											$class='bg-light';	
											}
											$subItems .= '<li class="px-2 py-1 border-bottom align-items-center small '.$class.'" style="display:flex"><img style="max-width:25px" src="'. ENGAGIFII_ASSETS_URL.'/images/class.png' .'" class="img-fluid mr-2"/>' . $classTime . '</li>';
											$li++;
										}
								
										$popOverHtml .= $subItems;
										$popOverHtml.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
										$searchName = json_encode(array_values($rowName));
								
										$vars = "";
										$popOverHtml .= '</ul></span>';
										$popOverHtml .= '</div>';
										$popOverHtml .= '</div>';
										$popOverHtml .= '</div>';
										$popOverHtml .= '</div> ';
										echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover" data-placement="left" data-containerid="" id=""><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->classSessionSettings).'</span></div>'.$popOverHtml.'</div>';	
									}else{
										echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" >';	
									}
									?>
			  								
			  						</td>
			  						<td> <?php if($value->classInstructorsCount>0){
										$rowName = array();
										$popOverHtml =  dd_header('Instructors','Search Instructors..');
										$subItems = "";
										$li=1;
										foreach ($value->classInstructors as $key => $rowData) {
											
											$rowName[$rowData->id] = $rowData->fullName;
											
											if($rowData->thumbnailUrl)
											{
												if (filter_var($rowData->thumbnailUrl, FILTER_VALIDATE_URL)) { 
													$instructor_img = $rowData->thumbnailUrl;
												}
												else
												{
													$instructor_img = $tenant_url.$rowData->thumbnailUrl;
												}
												
											}
											else
											{
												$instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';
								
											}
								
											$class='';
											if($li%2==1){
											$class='bg-light';	
											}
											$subItems .= '<li class="px-2 py-1 border-bottom  small '.$class.'">' . $rowData->fullName . '</li>';
											$li++;
										}
										
										$popOverHtml .= $subItems;
										$popOverHtml.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
								
										$vars = "";
										$popOverHtml .= '</ul></span>';
										$popOverHtml .= '</div>';
								
										$popOverHtml .= '</div>';
										$popOverHtml .= '</div>';
										$popOverHtml .= '</div> ';
				echo  '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_ " data-placement="left" data-containerid="" id=""><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.($value->classInstructorsCount).'</span></div>'.$popOverHtml.'</div>';  
                                    }else{
                                    	echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Instructors Available" >';
                                    } ?>
			  						<td><?php echo $value->courseCreditMapping[0]->credits; ?></td>
			  						
			  					</tr>
			  				<?php
			  					}
			  				}
			  				?>
			  			</tbody>
			  		</table>
			  	</div>
			  	</div>
			  	<div class="tab-pane fade" id="session" role="tabpanel" aria-labelledby="session-tab">
				  <div class="p-3">
			  		<table class="table table-bordered border-0 table-striped session-table" id="ebtmaintable" width="100%">
			  			<thead>
			  				<tr>
			  					<th>Session Name </th>
			  					<th>Date/Time</th>
			  					<th>Type</th>
			  					<th>Price</th>
			  					<th>Speakers</th>
			  					<th>Session Status</th>
			  					
			  				</tr>
			  			</thead>
			  			<tbody>
			  				<?php
							  //print_r(count($sessionsData));
							  //print_r(count($collections));
							  //$i=0;
			  					//if(($collection) && count($collection)){
			  						//$sessionData = $sessionsData[0];
			  						foreach ($collections as $key => $value) {
										 $instructorPopOver = '';

										if(count($value->speakers)){
											$instructorPopOver = $obj->_popOverSpeakerData3($key, $value->speakers);
										}
			  							
			  							//$classPopover   = $obj->_popOverClassesDate($key, $value->classSessionSettings);
			  				?>
			  					<tr class="bg-white">
			  						<td><span><?php echo $value->name; ?><br/><a href="<?php echo site_url(); ?>/class-details/?classId=<?php echo $value->id; ?>"><?php //echo mb_substr($value->name, 0,10); ?></a><br/>
									</td><td><?php 
			  								if(!empty($value->startDateTime) ){
			  									echo date('M d, Y', strtotime($value->startDateTime)); //date('M d, Y', $convert_Date);
			  									
			  									 echo ' at '.date("g:i A",strtotime($value->startDateTime)).' - '.date("g:i A",strtotime($value->endDateTime));
			  								}
			  							 ?>
			  						</span></td>
			  						
			  						<td><?php echo $value->type; ?></td>
			  						<td>
									  <?php echo '$'.$value->defaultPrice; ?>
			  						</td>
			  						<td><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_<?php echo $key ?> " data-placement="left" data-containerid="<?php echo $key ?>" id="<?php echo $key ?>"><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo count($value->speakers); ?></span></div><?php echo $instructorPopOver; ?></div>
                                    </td>
			  						<td><?php 
									if($value->activityStatus==1){
										$sessionStatus = "Upcoming Session";
									}
									if($value->activityStatus==2){
										$sessionStatus = "Session In-Play";
									}
									if($value->activityStatus==3){
										$sessionStatus = "Session Completed";
									}
									echo $sessionStatus; ?></td>
                                    
                                    
			  						
			  					</tr>
			  				<?php 
			  					}
			  			//	}
			  				?>
			  			</tbody>
			  		</table>
			  	</div>
				
			  	</div>

				  <div class="tab-pane fade" id="bundles" role="tabpanel" aria-labelledby="bundles-tab">
			  			<div class="p-3">
			  				<table class="table table-bordered border-0 table-striped bundle-table" id="ebtmaintable" width="100%">
			  					<thead>
			  						<tr>
			  							<th>S.no</th>
			  							<th>Bundle Name</th>
										 <th>Bundle Price</th>

			  						</tr>
			  					</thead>
			  					<tbody>
								  <?php
							 
			  						foreach ($bundleData->collection as $key => $value) {
			  				?>
			  					<tr class="">
									<td><?php echo $key+1; ?></td>
									
			  						<td>
									  <?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/bundle-icon-bigger.png" class="img-icon-lg img-fluid" alt="bundle-icon" >'; ?>										
										<span><?php echo $value->name; ?>	</span>	</td>							
			  						<td>
									  <?php echo '$'.$value->defaultPrice; ?>
			  						</td>
			  						
			  					</tr>
			  				<?php 
			  					}
			  			
			  				?>
			  					</tbody>
			  				</table>
			  			</div>
			  	</div>

			</div>
            </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.class-table').DataTable({
			"pageLength": 10,
			"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',
			"bInfo":false,
			"processing": true,
			"searching": false,
			"language": {
		    	processing: '<span>&nbsp;</span>',
		    	search:'',
		    	searchPlaceholder: "Search..."
		   	},
			"ordering":true,
			"order": [[2, 'asc']],
			"columnDefs": [ 
					{ "targets": ['duration','classType','instructor','creditHours'],
					  "orderable": false
					},
					{ width: 400, targets: 0 },
					{ className: "text-center", "targets": ["duration","classType","instructor","creditHours","classDates","instructor"] },
				  ],
			"drawCallback": function( settings ) {
					   dt_dropdown();
						 $('[data-toggle="tooltip"]').tooltip() ;
				   },
			});
		$('.session-table').DataTable({
			"pageLength": 10,
			"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',
			"bInfo":false,
			"processing": true,
			"searching": false,
			"language": {
		    	processing: '<span>&nbsp;</span>',
		    	search:'',
		    	searchPlaceholder: "Search..."
		   	},
			"ordering":false,
			});
		$('.bundle-table').DataTable({
			"pageLength": 10,
			"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',
			"bInfo":false,
			"processing": true,
			"searching": false,
			"language": {
		    	processing: '<span>&nbsp;</span>',
		    	search:'',
		    	searchPlaceholder: "Search..."
		   	},
			"ordering":false,
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
                table.draw();
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
                location.reload();
            }
        });

        e.preventDefault();
    });
</script>
