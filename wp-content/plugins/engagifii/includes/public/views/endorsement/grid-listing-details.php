<?php
 if(isset($_REQUEST['endId'])){
    $endId = $_GET['endId'];
  }

  if(!empty($endId)){
  $api =  new Engagifii_API();


  $options = get_option( 'ebt_api_settings' );
  $ebt_api_url = $options['ebt_api_url'];
  $tenant_url = $options['ebt_tenant_code']['engagifii_url'];
  /* General Tab */
  $endResponse = $api->getEndDetails($endId);
  $endResponses = json_decode($endResponse['api_response']);

  $options = get_option( 'ebt_api_settings' );
    $ebt_visib_datacol_list = $options['ebt_visib_datacol_list'];
//print_r($ebt_visib_datacol_list);
  /* Course Curriculam Tab */
  $curriculamResponse = $api->getCurriculam($endId);
  $curriculamResponses = json_decode($curriculamResponse['api_response']);

  $class_array = @json_decode(stripslashes($_COOKIE['awardids']), true);
  //print_r($class_array);
  $class_key = array_search ($_GET['endId'], $class_array);
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
<div class="col-sm-12">
    <a href="<?php echo site_url();?>/endorsements/" class="go-back"> Go Back </a>
</div>
<div class="border">
<div class="engagifii-box py-3">
    <div class="col-sm-12 d-md-flex p-2 align-items-center">
        <div class="col-xl-1 col-md-2 pt-3">
            <img class="img-circle" src="<?php echo $endResponses->icon;?>" style="max-width:78px;">
        </div>
        <div class="col-md-10 col-xl-9 pt-1 pr-3 pb-1 pl-xl-0">
            <h3 class="no-border m-auto"><?php echo $endResponses->name;?> </h3>
            <?php if(in_array('tags', $ebt_visib_datacol_list)){
            if(is_array($endResponses->awardTags) && count($endResponses->awardTags)>0) { ?>
            <div>
                <span>Tag(s): </span>
                <span class="pl-1 pr-1"><i class="fa fa-tags"></i> <?php echo count($endResponses->awardTags);  ?></span>
                <span class="border round-tag p-2 text-capitalize"><?php echo $endResponses->awardTags[0];?></span>
            </div><?php } }?>
        </div>
        <div class="col-md-2 col-xl-2 position-static">
          <div class="clearfix text-right">
           
           
            <?php
                if($prev){
              ?>
            <a href="<?php echo site_url(); ?>/endorsement-detail/?endId=<?php echo $prev; ?>" class=" pr-2 text-muted"><i class="fa fa-arrow-left"></i></a>
            <?php
              }
            ?>
             <?php
                if($next){
              ?>
            <a href="<?php echo site_url(); ?>/endorsement-detail/?endId=<?php echo $next; ?>" class="pl-2 pr-2 text-muted"><i class="fa fa-arrow-right"></i></a>
            <?php
          }
            ?>
             <!-- <a href="<?php echo site_url();?>/endorsement-grid-view/" class="pl-2 mr-2 text-muted"><i class="fa fa-times"></i></a> -->
          </div>
          <?php if(in_array('register', $ebt_visib_datacol_list)){ ?>
          <div class="clearfix pt-4 text-right">
            <a class="btn btn-primary " href="<?php echo $tenant_url;  ?>/pages/awards/<?php echo $endId; ?>/signup/overview" target="_blank">Register</a>
          </div>
          <?php } ?>

        </div>
    </div>      
</div>
        <div class="engagifii-box bg-light p-3 border-top">             
            <div class="bg-white border class-detail-main-nav">
                    <ul class="nav nav-pills mb-0 border-bottom engagifii-tabs" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link rounded-0 px-0 mx-3 text-dark active" data-toggle="pill" role="tab" href="#summary-tab" id="" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-0 px-0 mx-3 text-dark" data-toggle="pill" role="tab" href="#version-tab" id="" aria-selected="false">Course Curriculum</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link ebt-link" data-toggle="tab" role="tab" href="javascript:void(0)" id="document" aria-selected="false">Documents</a>
                        </li> -->
                    </ul>
                    <div class="p-3">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade active show " role="tabpanel" id="summary-tab">
                        	<div class="accordion" id="accordionExample">
                            	<div class="card  mb-3 border-bottom">
                                  <div class="card-header p-0">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link btn-block text-left text-dark d-flex justify-content-between align-items-center collapsed" type="button" data-target="#tab-content1" aria-controls="collapseTwo">
                                            Endorsement Details
                                           </button>
                                        </h2>
                                      </div>                                
                                <div id="tab-content1" class="" data-parent="#accordionExample">
                                      <div class="card-body p-3">
                                        <?php
                                        if($endResponses->objectType){
                                          if(in_array('objectType', $ebt_visib_datacol_list)){
                                      ?>
                                        <div class="summary-content-para-engagiigii row">
                                            <div class="col-sm-2 ">Endorsement Type:</div>
                                            <div class="col-sm-10"><?php echo $endResponses->objectType; ?></div>
                                        </div> 
                                      <?php
                                          } }
                                           if($endResponses->description){
                                      ?>
                                        <div class="summary-content-para-engagiigii row">
                                            <div class="col-sm-2">Description:</div>
                                            <div class="col-sm-10">
                                                <?php 
                                                    $cdesc = $endResponses->description; 
                                                    if ($cdesc)
                                                        echo wp_strip_all_tags($cdesc);
                                                    else
                                                        echo "<p>N/A</p>";
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                      }
                                          if($endResponses->requirement){
                                        ?>
                                      <div class="summary-content-para-engagiigii row">
                                        <div class="col-sm-2">Requirements:</div>
                                        <div class="col-sm-10">
                                          <?php 
                                          $reqrmnt = $endResponses->requirement;
                                          echo wp_strip_all_tags($reqrmnt);
                                          ?>
                                        </div>
                                      </div>
                                      <?php
                                        }
                                        if($endResponses->validity){
                                          if(in_array('validity', $ebt_visib_datacol_list)){
                                      ?>
                                      <div class="summary-content-para-engagiigii row">
                                        <div class="col-sm-2">Valid Till:
                                        </div>
                                        <div class="col-sm-10">
                                          <?php echo $endResponses->validity;?>
                                        </div>
                                      </div>
                                      <?php
                                          }
                                        }
                                          if(count($endResponses->skills)>0)
                                          {
                                      ?>
                                      <div class="summary-content-para-engagiigii row">
                                       <div class="col-sm-2 requir">Course Skills:</div>
                                        <div class="col-sm-10">
                                        
                                             
                                          <?php
                                              foreach ($endResponses->skills as $key => $skill) {
                                                  ?>
                                                   <span class="pl-2 pb-2 text-capitalize"><?php echo $skill; ?></span>
                                                  <?php
                                              }
                                                ?>
                                                  
                                               
                                        </div>
                                      </div>
                                       <?php
                                            }
                                           
                                        ?>
                                    </div>
                                      </div>
                                    </div>                 

                            	<div class="card  mb-3 border-bottom">
                                  <div class="card-header p-0">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link btn-block text-left text-dark d-flex justify-content-between align-items-center collapsed" type="button" data-target="#tab-content2" aria-controls="collapseTwo">
                                            Registration Details & Settings
                                            </button>
                                        </h2>
                                      </div>                                
                                <div id="tab-content2" class="" data-parent="#accordionExample">
                                      <div class="card-body p-3">
                                      <?php if(in_array('price', $ebt_visib_datacol_list)){ ?>
                                    <div class="summary-content-para-engagiigii row">
                                        <div class="col-sm-2">Price:
                                        </div>
                                        <div class="col-sm-10">
                                          $<?php echo $endResponses->price; ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                     <?php
                                                if($endResponses->preRequisiteCourses || $endResponses->preRequisiteAwards){
                                            ?>
                                    <div class="summary-content-para-engagiigii row">
                                        <div class="col-sm-2">Pre-Requisite(s): </div>
                                        <div class="col-sm-10">
                                          <?php
                                                if($endResponses->preRequisiteCourses){
                                                  if(in_array('courseCount', $ebt_visib_datacol_list)){
                                            ?>
                                            <span>Courses</span>
                                            <ul class="list-group">
                                                <?php
                                                    foreach ($endResponses->preRequisiteCourses as $crskey => $crsvalue) {?>
                                                        <li class="list-group-item border-0 d-flex p-0"><img src="<?php echo $crsvalue->icon; ?>" class="img-circle img-icon-lg"> <span class="p-1 text-capitalize"><a href="<?php echo site_url().'/course-details/?courseId='.$crsvalue->id; ?>"><?php echo $crsvalue->name; ?></a></span></li>
                                                <?php
                                                    }
                                                  }
                                                }
                                                ?>

                                            
                                            
                                            </ul>
                                            <?php
                                                if($endResponses->preRequisiteAwards){
                                            ?>
                                            <span>Awards</span>
                                            <ul class="list-group">
                                                <?php
                                                    foreach ($endResponses->preRequisiteAwards as $crskey => $crsvalue) {?>
                                                        <li class="list-group-item border-0 d-flex p-0"><img src="<?php echo $crsvalue->icon; ?>" class="img-circle img-icon-lg"> <span class="p-1 text-capitalize"><a href="<?php echo site_url().'/endorsement-detail/?endId='.$crsvalue->id; ?>"><?php echo $crsvalue->name; ?></a></span></li>
                                                <?php
                                                    }
                                                  }
                                                ?>

                                            
                                            
                                            </ul>  
                                        </div>
                                    </div>
                                    <?php
                                      }
                                    ?>
                                        </div>
                                      </div>
                                    </div>  
                                 <?php
                                    if(count($endResponses->contactPersons)){
                                ?>                  
                            	<div class="card  mb-3 border-bottom">
                                  <div class="card-header p-0">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link btn-block text-left text-dark d-flex justify-content-between align-items-center collapsed" type="button" data-target="#tab-content3" aria-controls="collapseTwo">
                                            Contact Person
                                           </button>
                                        </h2>
                                      </div>                                
                                <div id="tab-content3" class="" data-parent="#accordionExample">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <?php 
                                          foreach ($endResponses->contactPersons as $cntctkey => $cntctvalue) {
                                        ?>
                                        <div class="col-sm-4">
                                       <div class="card">
                                           <div class="card-body d-flex p-1">
                                               <div class="col-sm-3">
                                                <?php
                                                   if (filter_var($cntctvalue->thumbnailUrl, FILTER_VALIDATE_URL)) {
                                                ?>
                                                  <img src="<?php echo $cntctvalue->thumbnailUrl;?>" class="img-icon-lg">

                                                <?php
                                                   }
                                                    elseif($cntctvalue->thumbnailUrl){
                                                ?>
                                                <img src="<?php echo $tenant_url.$cntctvalue->thumbnailUrl;?>" class="img-icon-lg">
                                                <?php
                                                    }
                                                    else
                                                    {
                                                      ?>
                                                       <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/staff-list-grey.png" class="img-icon-lg">
                                                       <?php
                                                    }
                                                ?>
                                              </div>
                                                <div class="col-sm-9"><p class="card-title pt-3 text-right"><?php echo $cntctvalue->fullName;?></p></div>
                                            </div>
                                            <div class="card-body p-1"> 
                                               
                                               
                                               <div class="col-sm-12 card-text text-right">Position: <?php echo $cntctvalue->position;?></div>
                                               <div class="col-sm-12 card-text text-right">Department: <?php echo $cntctvalue->department;?> </div>
                                           </div>
                                       </div>
                                     </div>
                                       <?php
                                        }
                                       ?>
                                    </div>
                                </div>
                                      </div>
                                    </div> 
                                     <?php
                                    }
                                ?>                
                                    </div>
                            </div>
                           
                           
                        <div class="tab-pane fade " role="tabpanel" id="version-tab">
                                <table class="table table-striped table-bordered  nowrap" id="course_table">
                                    <thead>
                                        <tr>
                                            <th class="">Course Name</th>
                                            <th class="text-center">Course Type</th>
                                            <th class="text-center" >Credit Hours</th>
                                            <th class="text-center">Instructors</th>
                                            <th class="text-center">Classes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(is_array($curriculamResponses) && count($curriculamResponses) >0 ){
                                                foreach ($curriculamResponses as $curriculam => $allcurriculams) { 
                                                    $instructorPopOver = $api->_popOverInstructorData1($curriculam, $allcurriculams->certifiedInstructors);
                                                     $classPopover   = $api->_popOverClass1($curriculam, $allcurriculams->courseClasses);
                                                ?>
                                                <tr>
                                                    <td>
                                                    	<div class="d-flex align-items-center">
                                                    <img class="img-icon-lg img-fluid mr-2" style="display:inline-block" src="<?php echo $allcurriculams->courseIcon;?>">
                                                      <?php $cname = preg_replace('/\s+/', ' ', $allcurriculams->courseName);?><?php echo $cname; ?></div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($allcurriculams->objectTypeId == 0) { echo "General"; } else{ echo "N/A"; } ?>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <?php echo $allcurriculams->creditHours;?> 
                                                    </td>
                                                    <td class="text-center"><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_<?php echo $curriculam; ?> " data-placement="left" data-containerid="<?php echo $curriculam; ?>" id=" <?php echo $curriculam; ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo count($allcurriculams->certifiedInstructors); ?></span></div><?php echo $instructorPopOver; ?></div></td>

                                                    <td class="text-center">
                                                        <div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_<?php echo $curriculam; ?> " data-placement="left" data-containerid="<?php echo $curriculam; ?>" id="<?php echo $curriculam; ?>">
                      <img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/cal-1.png" class="img-icon-lg img-fluid"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"><?php echo count($allcurriculams->courseClasses); ?></span></div><?php echo $classPopover; ?></div>
                                                       
                                                    </td>
                                                </tr>
                                                <?php 
                                                }
                                                } ?>         
                                        </tbody>
                                    </table>
                            </div>
                           
                            
                        </div>
                        </div>
                </div>
            </div>  
            </div>            
        <?php } ?>

        <script type="text/javascript">
          $(document).ready(function() {
              $('table#course_table').DataTable({
                "pageLength": 10,
                "dom": '<"row"<"col-sm-12 "f">>ti<"row"<"col-sm-5 p-4"l><"col-sm-7"p>>',
                "bInfo":false,
                "processing": true,
                "searching": false,
                "language": {
                    processing: '<span>&nbsp;</span>',
                    search:'',
                    searchPlaceholder: "Search..."
                   },
				   "drawCallback": function( settings ) {
			 dt_dropdown();
				   },
                "ordering":false});
              

              // $('table#documentTable').DataTable({
              //   "pageLength": 10,
              //   "dom": '<"row"<"col-sm-12 pull-right"f">>ti<"row"<"col-sm-6 pt-2"l><"col-sm-6 text-right"p>>',
              //   "bInfo":false,
              //   "processing": true,
              //   "searching": true,
              //   "language": {
              //       processing: '<span>&nbsp;</span>',
              //       search:'',
              //       searchPlaceholder: "Search..."
              //      },
              //   "ordering":true});


          } );


        </script>