<?php
	
	$id 		= $_REQUEST['courseId'] ?? null;
	$obj 			=  new Engagifii_API();
	$response       =  $obj->getCourseDetailsByID($id);
	$classesData        = $obj->getRelatedClassBycourse($id, count($response->classes));

	$options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $courses_page = $front_pages['courses_page'];
    $courses_detail_page = $front_pages['courses_detail_page'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($courses_page){
	$courses_page_link=get_permalink( $courses_page );	
	}else{
	$courses_page_link= site_url() .'/courses/';	
	}
	if($courses_detail_page){
	$courses_detail_page_link=get_permalink( $courses_detail_page );	
	}else{
	$courses_detail_page_link= site_url() .'/course-details/';	
	}
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
    $api_url = $options['ebt_api_url'];
    $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
	$class_visible_column_list = $options['class_visible_column_list'];

	$certifiedInsturctor = $obj->getCertifiedInstructor($id);
	$class_array = json_decode(stripslashes($_COOKIE['courseids']), true);
	if($class_array){
  $class_key = array_search ($_GET['courseId'], $class_array);
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
   

?>
<div class="mb-2">
<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    $url.= $_SERVER['HTTP_HOST'];   
    $url.= $_SERVER['REQUEST_URI'];    
if ( strpos($url,'engagifii-profile') !== false ) {
	$classes_detail_page_link= site_url() .'/engagifii-profile/my-transcript/class-detail/';?> 
    <a href="<?php echo site_url().'/engagifii-profile/my-transcript/?tab=credits';?>" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<?php } else { ?>
    <a href="<?php echo $courses_page_link;?>" class="go-back"><i class="fal fa-arrow-left mr-2"></i> Go Back </a>
<?php } 
?>
</div>
<div class="engagifii-box border border-bottom-0">
    <div class="col-sm-12 d-md-flex p-1">
        <div class="col-xl-1 col-md-2 pt-2 text-center">
            <img class="img-circle img-icon-lg p-0 m-auto" src="<?php echo $response->icon->iconReference; ?>" style="max-width:78px;">
        </div>
        <div class="col-md-8 col-xl-9 pl-xl-0 pt-2">
            <h3 class="no-border m-auto"><?php echo $response->name;?> </h3>
            <?php if(is_array($response->courseTags) && count($response->courseTags)>0) {
            ?>
            <div class="pt-2">
                <span>Tag(s): </span>
                <span class="pl-1 pr-1"><i class="fa fa-tags"></i> <?php echo count($response->courseTags);  ?></span>
                <?php
                	foreach ($response->courseTags as $key => $value) {
                		?>
                			<span class="border round-tag py-2 px-4	 text-capitalize"><?php echo $value->tagName; ?></span>
                		<?php
                	}
                 ?>
            </div> 
            <?php
            		}
            ?>
        </div>
        	<div class="col-md-2 col-xl-2 text-md-right pr-0" >
          <div class="clearfix">
        	<?php
                if($prev){
              ?>
              <a class="text-muted <?php if($next){echo 'pr-2'; }?>" href="<?php echo $courses_detail_page_link;?>?courseId=<?php echo $prev; ?>"><i class="fa fa-arrow-left"></i> </a>
              <?php
                }if($next){
              ?>
              <a class="text-muted" href="<?php echo $courses_detail_page_link;?>?courseId=<?php echo $next; ?>"> <i class="fa fa-arrow-right"></i></a>
              <?php
                }
              ?>
            <a href="<?php echo $courses_page_link;?>" class="p-2 mr-2 text-muted"><i class="fa fa-times"></i></a>
        </div>
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
			    	<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Classes</a>
			  	</li>
			  	<!-- <li class="nav-item">
			    	<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Document</a>
			  	</li> -->
			</ul>
			<div class="tab-content" id="myTabContent">
			  	<div class="p-3 tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			  		<div class="row">
					  		<div class="col-sm-6 mb-4">
					  			<div class="border rounded box-shadow h-100">
					  			<div class="panel-title p-2 bg-light border-bottom">
		                          <h5 class="heading d-inline">Course Details</h5>
		                        </div>
		                        <?php
		                        	if(trim($response->description)){
		                        ?>
		                        <div class=" row p-2">
		                            <div class="col-sm-4">Description:</div>
		                            <div class="col-sm-8"><?php echo trim($response->description); ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->objectType){
		                        ?>
		                        <div class=" row p-2">
		                            <div class="col-sm-4">Course Type:</div>
		                            <div class="col-sm-8"><?php echo $response->objectType; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->creditHours){
		                        ?>
		                        <div class=" row p-2">
		                        	<div class="col-sm-4 ">Credit Hours</div>
		                        	<div class="col-sm-8"><?php echo $response->creditHours; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->secondaryUnits[0]->value){
		                        ?>
		                        <div class=" row p-2">
		                            <div class="col-sm-4 ">PLU:</div>
		                            <div class="col-sm-8"><?php echo $response->secondaryUnits[0]->value; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if(isset($response->skills) && count($response->skills) > 0){
		                        ?>	
		                         <div class=" row p-2">
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
					  		<div class="col-sm-6 mb-4">
					  			<div class="border rounded box-shadow h-100"> 
						  			<div class="panel-title bg-light p-2 border-bottom">
			                          <h5 class="heading d-inline">Class Location</h5>
			                        </div>
			                        <ul class="nav nav-tabs" id="myTab" role="tablist">
									  	<li class="nav-item">
										    <a class="nav-link <?php if($response->courseLocationType != 'online') {echo "active"; } ?>" id="offline-tab" data-toggle="tab" href="#offline" role="tab" aria-controls="offline" aria-selected="true">In Person Class</a>
									  	</li>
									  	<li class="nav-item">
									    	<a class="nav-link  <?php if($response->courseLocationType == 'online') {echo "active"; } ?>" id="online-tab" data-toggle="tab" href="#online" role="tab" aria-controls="online" aria-selected="false">Online Class</a>
									  	</li>
									 </ul>
									 <div class="tab-content">
									  	<div class="tab-pane fade show  <?php if($response->courseLocationType != 'online') {echo "active"; } ?>" id="offline" role="tabpanel" aria-labelledby="offline-tab">
									  		
									  			<?php
									  				if(!empty($response->location) && isset($response->location->address))
									  				{
									  			?>
									  				<div class=" row p-2">
									  					<div class="col-sm-4 ">Room Name:</div>
					                            		<div class="col-sm-8"><?php echo $response->location->classRoom->roomNumber; ?></div>
					                            	</div>
					                            	<div class=" row p-2">
					                            		<div class="col-sm-4">Address:</div>
					                            		<div class="col-sm-8"><?php echo $response->location->address->addressLine1; ?><br/><?php echo $response->location->address->city.' '.$response->location->address->state.', '.$response->location->address->zipCode; ?><br/><?php echo $response->location->address->country; ?></div>
					                            	</div> 
					                            	<div class=" col-12">
					                            		<iframe src = "https://maps.google.com/maps?q=<?php echo urlencode($response->location->address->addressLine1); ?>&hl=en;z=14&amp;output=embed" width="100%" height="200"></iframe>

					                            	</div>
									  			<?php
									  				}
									  				else{
									  				?>
									  						<div class=" col-12">No class room is selected now</div>
									  				<?php	
									  				}
									  			?>
					                            
					                        
									  	</div>
									  	<div class="tab-pane fade show <?php if($response->courseLocationType == 'online') {echo "active"; } ?>" id="online" role="tabpanel" aria-labelledby="online-tab">
									  		<div class=" row p-2">
			                            		<div class="col-sm-4">Online Class Location:</div>
			                            		<div class="col-sm-8"><?php echo $response->locationUrl ?? 'N/A'; ?></div>
			                        		</div> 
			                        		<div class=" row p-2">
			                            		<div class="col-sm-4">Login Steps:</div>
			                            		<div class="col-sm-8"><?php echo $response->locationAccessDetail ?? 'N/A'; ?></div>
			                        		</div> 
									  	</div>
			                       	</div>
		                    	</div>
					  		</div>
			  			<div class="col-sm-12 mb-4">
			  				<div class="border rounded box-shadow">
			  					<div class="panel-title bg-light p-2 border-bottom">
		                        	<h5 class="heading d-inline">Instructor</h5>
		                    	</div>
		                    	<div class="p-2 row">
		                    		<?php
		                    		if(count($certifiedInsturctor)){
		                    			
		                    			foreach ($certifiedInsturctor as $key => $value) {
		                    				$courses  = $value->coursesTaught;
		                    				$skills = $value->coursesSkilledToTeach;

		                    		?>
		                    			<div class="col-md-4">
		                    			<div class="card p-2">
                                            <div class="row instructor-detail">
		                    					<div class="col-auto">
		                    					<?php if (filter_var($value->imageThumbUrl, FILTER_VALIDATE_URL)) { ?>
		                    						<img src="<?php echo $value->imageThumbUrl;?>" class="img-fluid" style="max-width:100px">
		                    					<?php
		                    						}
		                    						else if($value->imageThumbUrl)
		                    						{
		                    					?>
		                    							<img src="<?php echo $tenant_url.$value->imageThumbUrl;?>" class=" img-fluid "  style="max-width:100px">
		                    					<?php		
		                    						}else{
		                    					?>
		                    								<img src="<?php echo ENGAGIFII_ASSETS_URL.'/images/user-default.png'; ?>" class="img-fluid"  style="max-width:100px">
		                    					<?php
		                    						}
		                    					?>
		                    					</div>
		                    					<div class="col-6">
                                                <p class="card-title mb-2"><?php echo $value->fullName;?><small class="d-block">Since <?php echo date('m/d/Y', strtotime($value->createdDate)); ?></small></p>
                                                <ul class="list-unstyled mb-0 ml-0 d-flex flex-wrap">
                                                <li class="position-relative inst-ac ml-0">
                                                	<div class="dropdown">
														  <button class="btn shadow-none no-border dropdown-toggle" type="button" id="course_<?php echo $key; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
														  	style="background:url('<?php echo ENGAGIFII_ASSETS_URL; ?>/images/trophy.png');background-size:contain;background-repeat:no-repeat;width:35px;height:35px">
														    <span class="badge badge-secondary position-absolute rounded-circle"><?php echo count($courses); ?></span>
														  </button>
														  <ul class="dropdown-menu p-1" aria-labelledby="course_<?php echo $key; ?>" style="max-height:250px; overflow:scroll">
														  	<?php
														  			foreach ($courses as $index => $val) {
														  	?>
														  				 <li class="position-relative p-1 inst-ac ml-0 d-flex align-items-center justify-content-start"><img src="<?php echo $val->icon->iconReference ?>" class="img-fluid mr-2"> <span style="font-size: 14px; white-space:nowrap; padding: 0px 15px 0 0;"><?php echo $val->name; ?></span></li>
														  	<?php
														  			}
														  	?>
														   
														    
														  </ul>
														</div>
                                                        </li>
                                                	
                                                    <li class="position-relative inst-ac ml-2">

                                                	<div class="dropdown">
														  <button class="btn shadow-none no-border dropdown-toggle" type="button" id="skills_<?php echo $key; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
														  	style="background:url('<?php echo ENGAGIFII_ASSETS_URL; ?>/images/class.png');background-size:contain;background-repeat:no-repeat;width:35px;height:35px">
														    <span class="badge badge-secondary position-absolute rounded-circle"><?php echo count($skills); ?></span>
														  </button>
														  <ul class="dropdown-menu p-1" aria-labelledby="skills_<?php echo $key; ?>" style="max-height:250px; overflow:scroll">
														  	<?php
														  			foreach ($skills as $index => $val) {
														  	?>
														  				 <li class="position-relative p-1 inst-ac ml-0 d-flex align-items-center justify-content-start"><img src="<?php echo $val->icon->iconReference ?>" class="img-fluid mr-2"> <span style="font-size: 14px; white-space:nowrap; padding: 0px 15px 0 0;"><?php echo $val->name; ?></span></li>
														  	<?php
														  			}
														  	?>
														   
														    
														  </ul>
														</div>
                                                    	
                                                        
                                                    </li>
                                                </ul>
                                                </div>
                                                 <div class="ml-auto text-right col-3"> 
                                                 <?php 
                                                 	if($value->isLead){
                                                 ?>	
                                               	<span class="badge badge-success">Lead</span>
                                               	<?php
                                               		}
				                               	?>
                                               		
                                               	<span class="badge badge-warning">Certified</span>
                                                
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
			  	<div class="p-3 tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			  		<div class="col-sm-12 pt-4">
			  		<div class="table-responsive">
			  		<table class="table table-hover table-bordered nowrap" id="ebtmaintable" width="100%">
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
			  						<td><span><?php //echo $response->name; ?><a href="<?php echo $classes_detail_page_link;?>?classId=<?php echo $value->id; ?>"><?php echo mb_substr($value->sectionName, 0,15); ?></a><br/>
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
                                      <td>
                                      	<?php if($value->classInstructorsCount<1){
											echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Instructors Available" >';
										}else {?>
                                      <div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_<?php echo $key ?> " data-placement="left" data-containerid="<?php echo $key ?>" id=" <?php echo $key ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo $value->classInstructorsCount; ?></span></div><?php echo $instructorPopOver; ?></div></td> 
										<?php }
									   }
									  if(in_array('credithours', $class_visible_column_list)){?>
			  						<td><?php echo $response->creditHours; ?></td>
                                    <?php } ?>
			  						
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
</script>
