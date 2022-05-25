<?php
	
	$id 		= $_REQUEST['endId'] ?? null;
	$obj 			=  new Engagifii_API();
	$response       =  $obj->getEventDetailsByID($id);
	//print_r(json_encode($response));
	//print_r(count($response->eventClasses));
	//$classesData        = $obj->getRelatedClassBycourse($id, count($response->eventClasses));
	//print_r(json_encode($classesData));
	$options = get_option('ebt_api_settings');
    $api_url = $options['ebt_api_url'];
    $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
	

	$contactPersons = $response->contacts;
	//print_r($contactPersons);
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
   

?>
<div class="mb-2">
    <a href="<?php echo site_url();?>/events/" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
</div>

<div class="engagifii-box border border-bottom-0 p-2 p-lg-3">
	<div class="row">
        <div class="col-md-10 d-flex align-items-center">
            <img class="img-circle img-icon-lg img-fluid mr-3" src="<?php echo $response->imageUrl; ?>" style="max-width:78px;">
        <div>
            <h3 class="mb-0 pb-1"><?php echo $response->name;?> </h3>
			<div class="mb-2">
                <span>Event type: </span>
                <span class="pl-1 pr-1"> <?php echo $response->eventType;  ?></span>
</div>
            <?php if(is_array($response->tags) && count($response->tags)>0) {
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
              <a class="text-muted <?php if($next){echo 'pr-2'; }?>" href="<?php echo site_url(); ?>/course-details/?courseId=<?php echo $prev; ?>"><i class="fal fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a class="text-muted" href="<?php echo site_url(); ?>/course-details/?courseId=<?php echo $next; ?>"> <i class="fal fa-arrow-right"></i></a>
              <?php
                }
              ?>
            <a href="<?php echo site_url();?>/courses/" class="p-2 mr-2 text-muted d-none"><i class="fa fa-times"></i></a>
        </div>
		
          <?php 
		 if($response->isRegistrationAllowed){ ?>
					<div class="mt-auto">
				
					  <a class="btn btn-primary " target="_blank" href="<?php echo $response->registrationUrlOnLocation ?>">Register</a>
					 		
					
					 
					  <!-- <a class="btn btn-primary px-3 py-1" target="_blank" href="<?php echo $tenant_url;  ?>/pages/classes/<?php echo $id; ?>/signup/online/overview">Register</a> -->
					</div>
				<?php } ?>
       </div>
       </div>
       
</div>
<div class="engagifii-box bg-light p-3 border">             
    <div class="border bg-white class-detail-main-nav">
        	<ul class="nav nav-pills mb-0 border-bottom engagifii-tabs" id="pills-tab" role="tablist">
			  	<li class="nav-item">
				    <a class="nav-link rounded-0 px-0 mx-3 text-dark active" id="home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="home" aria-selected="true">General</a>
			  	</li>
			  	<li class="nav-item">
			    	<a class="nav-link rounded-0 px-0 mx-3 text-dark" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Classes</a>
			  	</li>
			  	<li class="nav-item">
			    	<a class="nav-link rounded-0 px-0 mx-3 text-dark" id="session-tab" data-toggle="pill" href="#session" role="tab" aria-controls="session" aria-selected="false">Sessions</a>
			  	</li>
				  <!-- <li class="nav-item">
			    	<a class="nav-link" id="material-tab" data-toggle="tab" href="#material" role="tab" aria-controls="material" aria-selected="false">Event Material</a>
			  	</li>
				  <li class="nav-item">
			    	<a class="nav-link" id="speaker-tab" data-toggle="tab" href="#speaker" role="tab" aria-controls="speaker" aria-selected="false">Speakers</a>
			  	</li> -->
			</ul>
            <div class="p-3">
               <div class="tab-content" id="pills-tabContent">
			  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			  		<div class="row">
					  		<div class="col-sm-7 mb-3 ">
					  			<div class="border rounded shadow-sm h-100">
					  			<div class="panel-title bg-light px-2 py-1 border-bottom">
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
		                        	if($response->eventType){
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Event Type:</div>
		                            <div class="col-sm-8"><?php echo $response->eventType; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->startDateTime){
										$defaulget_Date = $response->startDateTime;
										$convert_Date = strtotime($defaulget_Date);
										$date = date('M d, Y', $convert_Date);
										$time = date('h:i A', $convert_Date);
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                        	<div class="col-sm-4 ">Event Start Date</div>
		                        	<div class="col-sm-8"><?php echo $date." at ".$time; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->endDateTime){
										$defaulget_Date = $response->endDateTime;
										$convert_Date = strtotime($defaulget_Date);
                                            $date = date('M d, Y', $convert_Date);
											$time = date('h:i A', $convert_Date); //$convert_Date->format('h:i:s A');
		                        ?>
		                        <div class="summary-content-para-engagiigii row flex-wrap mb-3">
		                            <div class="col-sm-4 ">Event End Date</div>
		                            <div class="col-sm-8"><?php echo $date." at ".$time; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if(isset($response->skills) && count($response->skills) > 0){
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
						  			<div class="panel-title bg-light px-2 py-1 border-bottom">
                                    <h6 class="mb-0 font-weight-normal">Event Schedule</h6>
			                        </div>
                                   <div class="p-3">
                                   	<?php
									
		                    		if(count($response->eventDates)){
		                    			
		                    			foreach ($response->eventDates as $key => $value) {
		                    				$position  = $value->position;
		                    				$department = $value->$department;
											$sessionStart_Date = strtotime($value->sessionStartTime);
											$startDate = date('M d, Y', $sessionStart_Date);
											$startTime = date('h:i A', $sessionStart_Date);
											$sessionEnd_Date = strtotime($value->sessionEndTime);
											$endDate = date('M d, Y', $sessionEnd_Date);
											$endTime = date('h:i A', $sessionEnd_Date);

		                    		?>
		                    			<div class="border rounded p-3 pt-xl-2 mb-3 bg-light">
		                    					<h6 class="pb-2 mb-2 border-bottom"> Day <?php echo $key+1 ?> </h6>
                                                <ul class="list-unstyled mb-0 lh-lg">
                                                	<li><?php echo $value->sessionDay.", ".$startDate.' at '.$startTime." To ".$endTime; ?></li>
                                                    <li><strong>Venue:</strong> <?php echo $value->name; ?></li>
                                                    <li><strong>Address: </strong>
												<?php echo $value->addressLine." ".$value->city." ".$value->state." ".$value->zip; ?>, <?php echo $value->country; ?></li>
                                                </ul>
		                    			</div>
		                    		<?php
		                    			}
		                    		}
		                    		?>
                                   </div>
		                    	</div>
					  		</div>
			  		</div>
			  		<div class="row">
			  			<div class="col-12">
			  				<div class="border rounded shadow-sm">
			  					<div class="panel-title bg-light px-2 py-1 border-bottom">
                                      <h6 class="mb-0 font-weight-normal">Contacts</h6>
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
														    <!-- <span class="badge badge-secondary position-absolute rounded-circle"><?php echo count($courses); ?></span> -->
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
														    <span class="badge badge-secondary position-absolute rounded-circle"><?php echo count($skills); ?></span>
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
			  		<table class="table table-bordered table-striped" id="" width="100%">
			  			<thead>
			  				<tr>
			  					<th>Class</th>
			  					<th>Duration</th>
			  					<th>Class Type</th>
			  					<th>Class Dates</th>
			  					<th>Instructor</th>
			  					<th>Credit Hours</th>
			  					
			  				</tr>
			  			</thead>
			  			<tbody>
			  				<?php
			  					if(is_array($classesData->result) && count($classesData->result)){
			  						
			  						foreach ($classesData->result as $key => $value) {
			  							$instructorPopOver = $obj->_popOverInstructorData($key, $value->classInstructors);
			  							$classPopover   = $obj->_popOverClassesDate($key, $value->classSessionSettings);
			  				?>
			  					<tr class="bg-white">
			  						<td><span><?php echo $response->name; ?><br/><a href="<?php echo site_url(); ?>/class-details/?classId=<?php echo $value->id; ?>"><?php echo mb_substr($value->sectionName, 0,10); ?></a><br/>
			  							<?php 
			  								if(!empty($value->startDate) ){
			  									echo date('d M Y', strtotime($value->startDate)); 
			  									if($value->classDuration > 1)
			  									{
			  										echo ' - '.date('d M Y', strtotime($value->endDate));
			  									}
			  									 echo '<br/> at '.date("H:i:s",strtotime($value->startDate)).' - '.date("H:i:s",strtotime($value->endDate));
			  								}
			  							 ?>
			  						</span></td>
			  						<td><?php echo $value->classDuration.' '.$value->classDurationType; ?></td>
			  						<td><?php echo $value->objectType; ?></td>
			  						<td>
			  							<div class="instructor-popover class_<?php echo $key; ?> " data-placement="left" data-containerid="<?php echo $key; ?>" id="<?php echo $key; ?>">
			  							<img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/Agenda.png" class="img-icon-lg"><span class=" bg-grey badge-count"><?php echo count($value->classSessionSettings); ?></span></div><?php echo $classPopover; ?>
			  								
			  						</td>
			  						<td><div class="instructor-popover instructor_<?php echo $key ?> " data-placement="left" data-containerid="<?php echo $key ?>" id=" <?php echo $key ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg"><span class="bg-grey badge-count"><?php echo $value->classInstructorsCount; ?></span></div><?php echo $instructorPopOver; ?></td>
			  						<td><?php echo $response->creditHours; ?></td>
			  						
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
			  				<table class="table table-bordered table-striped" id="doc_table" width="100%">
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
				  <div class="tab-pane fade" id="material" role="tabpanel" aria-labelledby="material-tab">
			  			<div class="p-3">
			  				<table class="table table-bordered table-striped" id="doc_table" width="100%">
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

				  <div class="tab-pane fade" id="speaker" role="tabpanel" aria-labelledby="speaker-tab">
			  			<div class="p-3">
			  				<table class="table table-bordered table-striped" id="doc_table" width="100%">
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

			</div>
            </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('table#class_table').DataTable({
			"pageLength": 10,
			"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',
			"bInfo":false,
			"processing": true,
			"searching": true,
			"language": {
		    	processing: '<span>&nbsp;</span>',
		    	search:'',
		    	searchPlaceholder: "Search..."
		   	},
			"ordering":true});

		//$('.dropdown-toggle').dropdown();

		// $('table#doc_table').DataTable({
		// 	"pageLength": 10,
		// 	"dom": '<"row"<"col-sm-12"f">><"row custom-scroll"t>i<"row"<"col-sm-4 pt-2"l><"col-sm-8 text-conter"p">>',
		// 	"bInfo":false,
		// 	"processing": true,
		// 	"searching": true,
		// 	"language": {
		//     	processing: '<span>&nbsp;</span>',
		//     	search:'',
		//     	searchPlaceholder: "Search..."
		//    	},
		// 	"ordering":true});
	});
</script>
