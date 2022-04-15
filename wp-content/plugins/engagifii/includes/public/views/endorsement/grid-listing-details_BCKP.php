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
  //print_r($endResponses);
  /* Course Curriculam Tab */
  $curriculamResponse = $api->getCurriculam($endId);
  $curriculamResponses = json_decode($curriculamResponse['api_response']);

  $class_array = @json_decode(stripslashes($_COOKIE['awardids']), true);
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
    <a href="<?php echo site_url();?>/endorsement-grid-view/" class="go-back"> Go Back </a>
</div>
<div class="engagifii-box border border-bottom-0">
    <div class="col-sm-12 d-md-flex p-2 align-items-center">
        <div class="col-xl-1 col-md-2 pt-3">
            <img class="img-circle" src="<?php echo $endResponses->icon;?>" style="max-width:78px;">
        </div>
        <div class="col-md-10 col-xl-9 pt-1 pr-3 pb-1 pl-xl-0">
            <h3 class="no-border m-auto"><?php echo $endResponses->name;?> </h3>
            <?php if(is_array($endResponses->awardTags) && count($endResponses->awardTags)>0) { ?>
            <div>
                <span>Tag(s): </span>
                <span class="pl-1 pr-1"><i class="fa fa-tags"></i> <?php echo count($endResponses->awardTags);  ?></span>
                <span class="border round-tag p-2 text-capitalize"><?php echo $endResponses->awardTags[0];?></span>
            </div><?php } ?>
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
             <a href="<?php echo site_url();?>/endorsement-grid-view/" class="pl-2 mr-2 text-muted"><i class="fa fa-times"></i></a>
          </div>
          <div class="clearfix pt-4 text-right">
            <a class="btn btn-primary p-2" href="<?php echo $tenant_url;  ?>/pages/awards/<?php echo $endId; ?>/signup/overview" target="_blank">Register</a>
          </div>
          

        </div>
    </div>      
</div>
        <div class="engagifii-box border p-4 bg-light">             
            <div class="border bg-white class-detail-main-nav">
                <div class="tabbable  box-shadow">            
                    <ul class="nav nav-tabs detail-nav-engagiifii pl-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link ebt-link active" data-toggle="tab" role="tab" href="javascript:void(0)" id="summary" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ebt-link" data-toggle="tab" role="tab" href="javascript:void(0)" id="version" aria-selected="false">Course Curriculum</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link ebt-link" data-toggle="tab" role="tab" href="javascript:void(0)" id="document" aria-selected="false">Documents</a>
                        </li> -->
                    </ul>
                    <div class="tab-content p-4">
                        <div class="active collapse summaryPanel" role="tabpanel">
                            <div class="bill-detail-summary-tab">
                                
                                <div class="bill-detail-summary-content">
                                    <div class="panel-title bg-light p-3 border-bottom">
                                      <h5 class="heading d-inline">Endorsement Details</h5>
                                      <span class="pull-right"><i class="fa fa-angle-up"></i></span>
                                    </div>
                                    <div class="panel-details">
                                      <?php
                                        if($endResponses->objectType){
                                      ?>
                                        <div class="summary-content-para-engagiigii col-sm-12">
                                            <div class="col-sm-2 ">Endorsement Type:</div>
                                            <div class="col-sm-10"><?php echo $endResponses->objectType; ?></div>
                                        </div> 
                                      <?php
                                          }
                                           if($endResponses->description){
                                      ?>
                                        <div class="summary-content-para-engagiigii col-sm-12">
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
                                      <div class="summary-content-para-engagiigii col-sm-12">
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
                                      ?>
                                      <div class="summary-content-para-engagiigii col-sm-12">
                                        <div class="col-sm-2">Valid Till:
                                        </div>
                                        <div class="col-sm-10">
                                          <?php echo $endResponses->validity;?>
                                        </div>
                                      </div>
                                      <?php
                                          }

                                          if(count($endResponses->skills)>0)
                                          {
                                      ?>
                                      <div class="summary-content-para-engagiigii col-sm-12">
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

                                <div class="bill-detail-summary-content mt-20">

                                  <div class="panel-title bg-light p-3 border-bottom">
                                      <h5 class="heading d-inline">Registration Details & Settings</h5>
                                      <span class="pull-right"><i class="fa fa-angle-up"></i></span>
                                    </div>
                                    <div class="panel-details">
                                    <div class="summary-content-para-engagiigii col-sm-12">
                                        <div class="col-sm-2">Price:
                                        </div>
                                        <div class="col-sm-10">
                                          $<?php echo $endResponses->price; ?>
                                        </div>
                                    </div>
                                     <?php
                                                if($endResponses->preRequisiteCourses || $endResponses->preRequisiteAwards){
                                            ?>
                                    <div class="summary-content-para-engagiigii col-sm-12">
                                        <div class="col-sm-2">Pre-Requisite(s): </div>
                                        <div class="col-sm-10">
                                          <?php
                                                if($endResponses->preRequisiteCourses){
                                            ?>
                                            <span>Courses</span>
                                            <ul class="list-group">
                                                <?php
                                                    foreach ($endResponses->preRequisiteCourses as $crskey => $crsvalue) {?>
                                                        <li class="list-group-item border-0 d-flex p-0"><img src="<?php echo $crsvalue->icon; ?>" class="img-circle img-icon-lg"> <span class="p-1 text-capitalize"><a href="<?php echo site_url().'/course-details/?courseId='.$crsvalue->id; ?>"><?php echo $crsvalue->name; ?></a></span></li>
                                                <?php
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
                                    <!-- <div class="summary-content-para-engagiigii col-sm-12">
                                        <div class="col-sm-2">Registration Steps:</div>
                                        <div class="col-sm-10">
                                            <ul id="progressbar">
                                                <li class="steps text-left text-wrap">Overview</li>
                                                <li class="steps text-left text-wrap">User Information</li>
                                                <li class="steps text-left text-wrap">Organization/ People</li>
                                                <li class="steps text-left text-wrap">Activities & Sessions</li>
                                                <li class="steps text-left text-wrap">Select Classes</li>
                                                <li class="steps text-left  text-wrap">Supporting Documents</li>
                                                <li class="steps text-left text-wrap">Review & Submit</li>
                                                <li class="steps text-left text-wrap">Status</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="summary-content-para-engagiigii col-sm-12">
                                        <div class="col-sm-2">Approval Steps:</div>
                                        <div class="col-sm-10">
                                            <ul id="progressbar">
                                                <li class="w-25 number-one text-left text-wrap">Principal</li>
                                                <li class="w-25 number-two text-left text-wrap">District Coordinator</li>
                                                <li class="w-25 number-three text-left text-wrap">RESA Approval</li>
                                            </ul>
                                        </div>
                                    </div> -->
                                  </div>
                                </div>
                                <?php
                                    if(count($endResponses->contactPersons)){
                                ?>
                                <div class="bill-detail-summary-content mt-20">
                                    <div class="panel-title bg-light p-3 border-bottom">
                                      <h5 class="heading d-inline">Contact Person</h5>
                                      <span class="pull-right"><i class="fa fa-angle-up"></i></span>
                                    </div>
                                    <div class="panel-details">
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
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                           
                        <div class="collapse versionPanel" role="tabpanel">
                            <div class="table-responsive-sm pt-4">
                                <table class="table table-hover table-bordered light-background no-table-gapping-detail nowrap" id="course_table">
                                    <thead>
                                        <tr>
                                            <th class="">Course Name</th>
                                            <th class="">Course Type</th>
                                            <th class="" >Credit Hours</th>
                                            <th class="">Instructors</th>
                                            <th class="">Classes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(is_array($curriculamResponses) && count($curriculamResponses) >0 ){
                                                foreach ($curriculamResponses as $curriculam => $allcurriculams) { 
                                                    $instructorPopOver = $api->_popOverInstructorData($curriculam, $allcurriculams->certifiedInstructors);
                                                     $classPopover   = $api->_popOverClass($curriculam, $allcurriculams->courseClasses);
                                                ?>
                                                <tr class="bg-white">
                                                    <td><img class="img-icon-lg" style="display:inline-block" src="<?php echo $allcurriculams->courseIcon;?>">
                                                      <span><?php $cname = preg_replace('/\s+/', ' ', $allcurriculams->courseName);?><?php echo $cname; ?></span>
                                                    </td>
                                                    <td class="">
                                                        <?php if ($allcurriculams->objectTypeId == 0) { echo "General"; } else{ echo "N/A"; } ?>
                                                    </td>
                                                    <td class=""> 
                                                        <?php echo $allcurriculams->creditHours;?> 
                                                    </td>
                                                    <td><div class="instructor-popover instructor_<?php echo $curriculam; ?> " data-placement="left" data-containerid="<?php echo $curriculam; ?>" id=" <?php echo $curriculam; ?> "><img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/instructor.png" class="img-icon-lg"><span class="bg-grey badge-count"><?php echo count($allcurriculams->certifiedInstructors); ?></span></div><?php echo $instructorPopOver; ?></td>

                                                    <td class="">
                                                        <div class="instructor-popover class_<?php echo $curriculam; ?> " data-placement="left" data-containerid="<?php echo $curriculam; ?>" id="<?php echo $curriculam; ?>">
                      <img src="<?php echo ENGAGIFII_ASSETS_URL ; ?>/images/cal-1.png" class="img-icon-lg"><span class="bg-grey badge-count"><?php echo count($allcurriculams->courseClasses); ?></span></div><?php echo $classPopover; ?>
                                                       
                                                    </td>
                                                </tr>
                                                <?php 
                                                }
                                                } ?>         
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- <div class="collapse documentPanel" role="tabpanel">
                                <div class="table-responsive-sm">
                                    <table class="table table-bordered light-background nowrap" id="documentTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Document</th>
                                                <th class="text-center">File Size</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white">
                                                <td class="text-center">Test Document</td>
                                                <td class="text-center">50KB</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->
                            
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
                "searching": true,
                "language": {
                    processing: '<span>&nbsp;</span>',
                    search:'',
                    searchPlaceholder: "Search..."
                   },
                "ordering":true});
              

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

          $(document).ready(function () {
  
  $('.dropdown-toggle').click(function () {
      $('.dropdown-menu').toggle();
    })
  })
        </script><d