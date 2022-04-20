<?php
	
	$id 		= $_REQUEST['courseId'] ?? null;
	$obj 			=  new Engagifii_API();
	$response       =  $obj->getCourseDetailsByID($id);
	$classesData        = $obj->getRelatedClassBycourse($id, count($response->classes));

	$options = get_option('ebt_api_settings');
    $api_url = $options['ebt_api_url'];
    $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
	//$documentData  =  $obj->getCourseDocument($id, $response->name);
?>
<div class="col-sm-12">
    <a href="<?php echo site_url();?>/courses/" class="go-back"> Go Back </a>
</div>

<div class="engagifii-box border border-bottom-0">
    <div class="col-sm-12 d-flex p-2">
        <div class="col-md-1 col-sm-2 pt-3">
            <img class="img-circle" src="<?php echo $response->icon->iconReference; ?>">
        </div>
        <div class="col-md-9 col-sm-12 pt-3 pr-3 pb-3 pl-0">
            <div class="no-border m-auto" style="font-size: 30px; color:#777;"><?php echo $response->name;?> </div>
            <?php if(is_array($response->courseTags) && count($response->courseTags)>0) {
            ?>
            <div class="pt-2">
                <span class="pt-2">Tag(s): </span>
                <span class="pl-1 pr-1"><i class="fa fa-tags"></i> <?php echo count($response->courseTags);  ?></span>
                <?php
                	foreach ($response->courseTags as $key => $value) {
                		?>
                			<span class="border round-tag p-2 text-capitalize"><?php echo $value->tagName; ?></span>
                		<?php
                	}
                 ?>
            </div> 
            <?php
            		}
            ?>
        </div>
        <div class="col-md-2 col-sm-12 pull-right pt-3">
          <div class="clearfix">
            <a href="<?php echo site_url();?>/courses/" class="pull-right pl-2 close-link text-muted"></a>
            
          </div>
        </div>
    </div>      
</div>
<div class="engagifii-box border p-4 bg-light">             
    <div class="border bg-white class-detail-main-nav">
        <div class="tabbable  box-shadow">
        	<ul class="nav nav-tabs" id="myTab" role="tablist">
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
			  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			  		<div class="row m-2">
			  			<div class="col-sm-12 d-md-flex p-0">
					  		<div class="col-sm-6 p-0">
					  			<div class="m-1 border rounded box-shadow">
					  			<div class="panel-title p-3 bg-light border-bottom">
		                          <h5 class="heading d-inline">Course Details</h5>
		                        </div>
		                        <?php
		                        	if(trim($response->description)){
		                        ?>
		                        <div class="summary-content-para-engagiigii">
		                            <div class="col-sm-4 p-0">Description:</div>
		                            <div class="col-sm-8"><?php echo trim($response->description); ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->objectType){
		                        ?>
		                        <div class="summary-content-para-engagiigii">
		                            <div class="col-sm-4 p-0">Course Type:</div>
		                            <div class="col-sm-8"><?php echo $response->objectType; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->creditHours){
		                        ?>
		                        <div class="summary-content-para-engagiigii">
		                        	<div class="col-sm-4 p-0">Credit Hours</div>
		                        	<div class="col-sm-8"><?php echo $response->creditHours; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if($response->secondaryUnits[0]->value){
		                        ?>
		                        <div class="summary-content-para-engagiigii">
		                            <div class="col-sm-4 p-0">PLU:</div>
		                            <div class="col-sm-8"><?php echo $response->secondaryUnits[0]->value; ?></div>
		                        </div>
		                        <?php
		                        	}
		                        	if(isset($response->skills) && count($response->skills) > 0){
		                        ?>	
		                         <div class="summary-content-para-engagiigii">
		                            <div class="col-sm-4 p-0">Skills:</div>
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
					  		<div class="col-sm-6 p-0">
					  			<div class="m-1 border rounded box-shadow">
						  			<div class="panel-title bg-light p-3 border-bottom">
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
									  				<div class="summary-content-para-engagiigii">
									  					<div class="col-sm-4 p-0">Room Name:</div>
					                            		<div class="col-sm-8"><?php echo $response->location->classRoom->roomNumber; ?></div>
					                            	</div>
					                            	<div class="summary-content-para-engagiigii">
					                            		<div class="col-sm-4 p-0">Address:</div>
					                            		<div class="col-sm-8"><?php echo $response->location->address->addressLine1; ?><br/><?php echo $response->location->address->city.' '.$response->location->address->state.', '.$response->location->address->zipCode; ?><br/><?php echo $response->location->address->country; ?></div>
					                            	</div> 
					                            	<div class="summary-content-para-engagiigii">
					                            		<iframe src = "https://maps.google.com/maps?q=<?php echo urlencode($response->location->address->addressLine1); ?>&hl=en;z=14&amp;output=embed" width="100%" height="200"></iframe>

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
									  	<div class="tab-pane fade show <?php if($response->courseLocationType == 'online') {echo "active"; } ?>" id="online" role="tabpanel" aria-labelledby="online-tab">
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
			  					<div class="panel-title bg-light p-3 border-bottom">
		                        	<h5 class="heading d-inline">Instructor</h5>
		                    	</div>
		                    	<div class="card-box">
		                    		<?php
		                    		if(count($response->certifiedInstructors)){
		                    			//print_r($response->certifiedInstructors);
		                    			foreach ($response->certifiedInstructors as $key => $value) {
		                    		?>
		                    		<div class="p-3">
		                    			<div class="card">
		                    				<div class="card-body d-flex">
		                    					  <div class="col-sm-3">
		                    					<?php if (filter_var($value->thumbnailUrl, FILTER_VALIDATE_URL)) { ?>
		                    						<img src="<?php echo $value->thumbnailUrl;?>" class="img-icon-lg">
		                    					<?php
		                    						}
		                    						else if($value->thumbnailUrl)
		                    						{
		                    					?>
		                    							<img src="<?php echo $tenant_url.$value->thumbnailUrl;?>" class="img-icon-lg">
		                    					<?php		
		                    						}else{
		                    					?>
		                    								<img src="<?php echo ENGAGIFII_ASSETS_URL.'/images/user-default.png'; ?>" class="img-icon-lg">
		                    					<?php
		                    						}
		                    					?>
		                    				</div>
		                    					<div class="col-sm-9"><p class="card-title pt-3 text-right"><?php echo $value->fullName;?></p></div>
                                             </div>
                                             <div class="card-body p-2 pt-0"> 	
                                               	<div class="col-sm-12 card-text text-right"></div>
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
			  		<div class="col-sm-12 pt-4">
			  		<div class="table-responsive">
			  		<table class="table table-hover table-bordered light-background nowrap" id="class_table" width="100%">
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
