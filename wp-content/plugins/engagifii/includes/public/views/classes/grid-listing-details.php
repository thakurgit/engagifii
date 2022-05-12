<?php
	
	$id 		= $_REQUEST['classId'] ?? null;
	$options = get_option('ebt_api_settings');
    $api_url = $options['ebt_api_url'];
    $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];

	$obj 			=  new Engagifii_API();
	$response       =  $obj->getClassDetailsByID($id);
	$classesData        = $obj->getRelatedClassByClass($response->parentCourse->id, $id,10);

	$class_array = @json_decode(stripslashes($_COOKIE['classids']), true);
  $class_key = array_search ($_GET['classId'], $class_array);
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

  
	//$documentData  =  $obj->getCourseDocument($id, $response->name);
?>
<div class="col-sm-12">
    <a href="<?php echo site_url();?>/classes/" class="go-back"> Go Back </a>
</div>

<div class="engagifii-box border border-bottom-0">
    <div class="col-sm-12 d-md-flex p-1">
        <div class="col-md-1 pt-2 text-center">
            <img class="img-circle img-icon-lg p-0 m-auto" src="<?php echo $response->parentCourse->icon->iconReference; ?>" style="max-width:78px;">
        </div>
        <div class="col-md-10 pt-2">
            <h3 class="no-border m-auto"><?php echo $response->parentCourse->name;?> </h3>
            <p> <?php echo $response->sectionName; ?></p>
            <?php if(is_array($response->classTag) && count($response->classTag)>0) {?>
            <div class="pt-1">
                <span class="pt-1">Tag(s): </span>
                <span class="pl-1 pr-1"><i class="fa fa-tags"></i> <?php echo count($response->classTag);  ?></span>
                <?php
                	foreach ($response->classTag as $key => $value) {
                		?>
                			<span class="border round-tag py-2 px-4 text-capitalize"><?php echo $value->tagName; ?></span>
                		<?php
                	}
                ?>
            </div> 
            <?php
            	}
            ?>
        </div>
        <div class="col-md-1 text-md-right d-flex flex-column pb-3 pb-lg-4">
          <div class="clearfix">
          	 <?php
                if($prev){
              ?>
              <a class="text-muted <?php if($next){echo 'pr-2'; }?>" href="<?php echo site_url(); ?>/class-details/?classId=<?php echo $prev; ?>"><i class="fa fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a class="text-muted" href="<?php echo site_url(); ?>/class-details/?classId=<?php echo $next; ?>"> <i class="fa fa-arrow-right"></i></a>
              <?php
                }
              ?>
            <a href="<?php echo site_url();?>/classes/" class="p-2 text-muted"><i class="fa fa-times"></i></a>
            
          </div>
          <?php
          	if($response->isClassRegistrationAllow){

				if($response->registrationState !== 'Registration Not Setup' && $response->registrationState !== 'Registration Closed' && $response->registrationState!== 'Sold Out' && $response->registrationState !== 'Registration Scheduled' && $response->registrationState !== 'Early Sold Out' && $response->registrationState !== 'Standard Sold Out')
				{
				 ?>
				 <div class="clearfix pt-4 pt-md-0 mt-auto">
				 <?php 
				   if($response->classLocationType->name=="onlocation"){ ?>
				   <a class="btn btn-primary px-3 py-1" target="_blank" href="<?php echo $response->registrationUrlOnLocation ?>">Register</a>
				   <?php }
				   elseif($response->classLocationType->name=="online"){
					   ?>
					   <a class="btn btn-primary px-3 py-1" target="_blank" href="<?php echo $response->registrationUrlOnLine?>">Register </a>
					   <?php
					   }
					   elseif($response->classLocationType->name=="onlocationandonline"){ 
						   ?>
						   <span id="locationButon" style="display:grid;">
						 <!-- <a class="btn btn-primary px-3 py-1"  id="hybridLocation" style="color:white;">Register <i class='fa-greater-than'></i></a> -->
						 <a class="btn btn-primary px-3 py-1"  style="margin-top: 5px; margin-bottom: 5px;" id="onlineclass" target="_blank" href="<?php echo $response->registrationUrlOnLine?>">Register Online</a> 
						 <a class="btn btn-primary px-3 py-1"  id="onlocation" target="_blank" href="<?php echo $response->registrationUrlOnLocation ?>">Register In Person</a> 
					   </span>
					 <?php
					   }
				   } 
				   ?>
				   <!-- <a class="btn btn-primary px-3 py-1" target="_blank" href="<?php echo $tenant_url;  ?>/pages/classes/<?php echo $id; ?>/signup/online/overview">Register</a> -->
				 </div>
				 <?php 
				 }
			 
		   ?>

	 </div>
 </div>      
</div>
<div class="engagifii-box border p-4 bg-light">             
    <div class="border bg-white class-detail-main-nav">
        <div class="tabbable  box-shadow">
        	<ul class="nav nav-tabs w-100" id="myTab" role="tablist">
			  	<li class="nav-item">
				    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">General</a>
			  	</li>
			  	<li class="nav-item">
			    	<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Related Classes</a>
			  	</li>
			  <!-- 	<li class="nav-item">
			    	<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Document</a>
			  	</li> -->
			</ul>
			<div class="tab-content" id="myTabContent">
			  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			  		<div class="row m-2">
			  			<div class="col-sm-12 d-md-flex p-0">
					  		<div class="col-sm-6 p-0">
					  			<div class="m-1 border rounded box-shadow h-100">
					  			<div class="panel-title bg-light p-2 border-bottom">
		                          <h5 class="heading d-inline">Class Details</h5>
		                        </div>
		                        <?php
								
		                        	if(trim($response->description)){
		                        ?>
		                        <div class="summary-content-para-engagiigii flex-wrap">
		                            <div class="col-md-4 col-12 p-0 mb-3 mb-md-0">Description:</div>
		                            <div class="col-md-8 col-12"><?php echo trim($response->description); ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->objectType){
		                        ?>
		                        <div class="summary-content-para-engagiigii">
		                            <div class="col-sm-4 p-0">Class Type:</div>
		                            <div class="col-sm-8"><?php echo $response->objectType; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->parentCourse->creditHours){
		                        ?>
		                        <div class="summary-content-para-engagiigii">
		                        	<div class="col-sm-4 p-0">Credit Hours:</div>
		                        	<div class="col-sm-8"><?php echo $response->parentCourse->creditHours; ?></div>
		                        </div>
		                       	<?php
		                       		}
		                       	?>
		                    </div>
					  		</div>
					  		<div class="col-sm-6 p-0">
					  			<div class="m-1 border rounded box-shadow h-100">
						  			<div class="panel-title bg-light p-2 border-bottom">
			                          <h5 class="heading d-inline">Class Location</h5>
			                        </div>
			                        <ul class="nav nav-tabs" id="myTab" role="tablist">
									  	<li class="nav-item">
										    <a class="nav-link <?php if($response->classLocationType->name != 'online') {echo "active"; } ?>" id="offline-tab" data-toggle="tab" href="#offline" role="tab" aria-controls="offline" aria-selected="true">In Person Class</a>
									  	</li>
									  	<li class="nav-item">
									    	<a class="nav-link  <?php if($response->classLocationType->name == 'online') {echo "active"; } ?>" id="online-tab" data-toggle="tab" href="#online" role="tab" aria-controls="online" aria-selected="false">Online Class</a>
									  	</li>
									 </ul>
									 <div class="tab-content">
									  	<div class="tab-pane fade show  <?php if($response->classLocationType->name != 'online') {echo "active"; } ?>" id="offline" role="tabpanel" aria-labelledby="offline-tab">
									  		
									  			<?php
									  				if(!empty($response->location) && isset($response->location->address))
									  				{
									  			?>
									  				<div class="summary-content-para-engagiigii">
									  					<div class="col-sm-4 p-0">Room Name:</div>
					                            		<div class="col-sm-8"><?php echo $response->location->classRoom->roomNumber; ?></div>
					                            	</div>
					                            	<div class="summary-content-para-engagiigii">
					                            		<div class="col-sm-4 p-0">Address:</div>
					                            		<div class="col-sm-8"><?php echo $response->location->address->addressLine1; ?><br/><?php echo $response->location->address->city.' '.$response->location->address->state.', '.$response->location->address->zipCode; ?><br/><?php echo $response->location->address->country; ?></div>
					                            	</div> 
					                            	<div class="summary-content-para-engagiigii">
					                            		<iframe src = "https://maps.google.com/maps?q=<?php echo urlencode($response->location->address->addressLine1.' '.$response->location->address->city.' '.$response->location->address->state.' '.$response->location->address->zipCode); ?>&hl=en;z=14&amp;output=embed" width="100%" height="200"></iframe>

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
									  	<div class="tab-pane fade show <?php if($response->classLocationType->name == 'online') {echo "active"; } ?>" id="online" role="tabpanel" aria-labelledby="online-tab">
									  		<div class="summary-content-para-engagiigii">
			                            		<div class="col-sm-4 p-0">Online Class Location:</div>
			                            		<div class="col-sm-8"><?php echo $response->locationUrl ?? 'N/A'; ?></div>
			                        		</div> 
			                        		<div class="summary-content-para-engagiigii">
			                            		<div class="col-sm-4 p-0">Login Steps:</div>
			                            		<div class="col-sm-8"><?php echo $response->locationAccessDetail ?? 'N/A'; ?></div>
			                        		</div> 
									  	</div>
			                       	</div>
		                    	</div>
					  		</div>
					  	</div>
			  		</div>
			  		<div class="row m-2">
			  			<div class="col-sm-12 p-0">
			  				<div class="m-1 border rounded box-shadow">
			  					<div class="panel-title bg-light p-2 border-bottom">
		                        	<h5 class="heading d-inline">Instructor</h5>
		                    	</div>
		                    	<div class="card-box">
		                    		<?php
		                    		if(is_array($response->classInstructors) && count($response->classInstructors)){
		                    			//print_r($response);
		                    			foreach ($response->classInstructors as $key => $value) {
		                    		
		                    		?>
		                    		<div class="p-3">
		                    			<div class="card">
		                    				<div class="card-body row align-items-start instructor-detail">
		                    					<div class="col-3">
		                    					<?php if (filter_var($value->imageThumbUrl, FILTER_VALIDATE_URL)) { ?>
		                    						<img src="<?php echo $value->imageThumbUrl;?>" class="inst-thumb img-fluid mr-2">
		                    					<?php
		                    						}
		                    						else if($value->imageThumbUrl)
		                    						{
		                    					?>
		                    							<img src="<?php echo $tenant_url.$value->imageThumbUrl;?>" class="img-fluid mr-2">
		                    					<?php		
		                    						}else{
		                    					?>
		                    								<img src="<?php echo ENGAGIFII_ASSETS_URL.'/images/user-default.png'; ?>" class="img-fluid mr-2">
		                    					<?php
		                    						}
		                    					?>
		                    					</div>
		                    					<div class="col-6">
                                                <p class="card-title mb-2"><?php echo $value->fullName;?></p>
                                                <ul class="list-unstyled mb-0 ml-0 d-flex flex-wrap">
                                                	<li class="position-relative mr-2 inst-ac ml-0"><img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/trophy.png" class="img-fluid" alt=""></li>
                                                    <li class="position-relative mr-2 inst-ac ml-0">
                                                    	<img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/class.png" class="img-fluid" alt="">
                                                        <!-- <span class="badge badge-secondary position-absolute rounded-circle">2</span> -->
                                                    </li>
                                                </ul>
                                                </div>
                                                 <div class="ml-auto text-right col-3 px-0"> 
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
			  	</div>
			  	<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			  		<div class="col-sm-12">
			  		<div class="table-responsive pt-4">
			  		<table class="table table-bordered table-striped" id="class_table" width="100%">
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
			  							$instructorPopOver = $obj->_popOverInstructorData1($key, $value->classInstructors);
			  							$classPopover   = $obj->_popOverClassesDate1($key, $value->classSessionSettings);
			  				?>
			  					<tr>
			  						<td><span class="d-block"><a href="<?php echo site_url(); ?>/class-details/?classId=<?php echo $value->id; ?>"><?php echo mb_substr($value->sectionName, 0,10); ?></a></span><small class="d-block">
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
                                         </small>
			  						</td>
			  						<td><?php echo $value->classDuration.' '.$value->classDurationType; ?></td>
			  						<td><?php echo $value->objectType; ?></td>
			  						<td>
			  							<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_<?php echo $key; ?> " data-placement="left" data-containerid="<?php echo $key; ?>" id="<?php echo $key; ?>">
			  							<img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/Agenda.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo count($value->classSessionSettings); ?></span></div><?php echo $classPopover; ?></div>
			  								
			  						</td>
			  						<td><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_<?php echo $key ?> " data-placement="left" data-containerid="<?php echo $key ?>" id=" <?php echo $key ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo $value->classInstructorsCount; ?></span></div><?php echo $instructorPopOver; ?></div></td>
			  						<td><?php echo $response->parentCourse->creditHours; ?></td>
			  						
			  					</tr>
			  				<?php
			  					}
			  				}
			  				?>
			  			</tbody>
			  		</table>
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
		$('table#class_table').DataTable({
			"pageLength": 10,
			"dom": '<"row"<"col-sm-12"f">><"row custom-scroll"t>i<"row"<"col-sm-5 p-4"l><"col-sm-7"p">>',
			"bInfo":false,
			"processing": true,
			"searching": true,
			"drawCallback": function( settings ) {
			 dt_dropdown();
			},
			"language": {
		    	processing: '<span>&nbsp;</span>',
		    	search:'',
		    	searchPlaceholder: "Search..."
		   	},
			"ordering":false});
	});
</script>
