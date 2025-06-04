<?php
/*
*Engagifii abstract class for handling AJAX request
* since v1.0.0
*/ 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


 class abstractModelEngagifii_v2 extends abstractModelEngagifii{
    public function __construct(){
	parent::__construct();
    global $wpdb;
    $this->dbObj = $wpdb;
	  $ajax_actions = [
        ['geteventsClasscalendar', 'geteventsClasscalendar'],
        ['peopleloadGridDataByGroups', 'peopleloadGridDataByGroups'],
        ['getOrganizations', 'getOrganizations'],
    ];

    foreach ($ajax_actions as $action) { 
        add_action('wp_ajax_nopriv_' . $action[0], [$this, $action[1]]);
        add_action('wp_ajax_' . $action[0], [$this, $action[1]]);
    }
 }
	  public function eventsClassCalendar(){
	        $options = get_option('ebt_api_settings');
        $env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
        $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii'.$env.'.com';
        $allEventsClass = $options['allEventsClass'];
        
        if($allEventsClass==1){
        $allEventsClass = 'false';	
        }else{
            $allEventsClass = 'true';
        }
        $postedData = $this->_prepareTrainingCalendarData();
        $dataResponse = $this->submitApiRequest("Public/EventAndClassFilteredCount", $postedData, "POST", 'classes');
		$eventsClassCount = $dataResponse['api_response'];
        $postData = array();    
        $postData['itemCount'] = $eventsClassCount;
        $postData['sortBy'] = 'Name';
        $postData['pageNumber'] = 1;
        $postData['pageSize'] = ((int) $eventsClassCount);
        $postData['sortDirection'] = 'asc';
        
        $postData['filterBody'] = array('searchText'=>'',  'selectedDate' => date('Y-m-d'), 'isUpcoming' => $allEventsClass, 'category' => null);
        $dataResponse = $this->submitApiRequest("public/EventClassPagingList", $postData, "POST", 'classes');
        $collection   = json_decode($dataResponse['api_response'])->result;
           $data         = array();
           $endorsmentData    = array();
           foreach ($collection as $key => $value) {
			   if($value->entity=='Event'){
				  $event_status = $value->status;
				  $registration_state = preg_replace('/(?<!\ )[A-Z]/', ' $0', $value->registrationState);
				   foreach ($value->eventDates as $index => $event) {
					   //session start time and date
					  $default_StartDate = $event->startDateTime;
					  $convert_StartDate = strtotime($default_StartDate);
					  $new_StartDate = date('M d, Y', $convert_StartDate);
					  $sessionStartTime = date('g:i A', $convert_StartDate);
					  //end here
	  
					  //session end date and time
					  $default_EndDate = $event->endDateTime;
					  $convert_EndDate = strtotime($default_EndDate);
					  $new_EndDate = date('M d, Y', $convert_EndDate);
					  $sessionEndTime = date('g:i A', $convert_EndDate);
					  // end here
	  
					  $startDate = date('Y-m-d', strtotime($value->startDateTime));
					  //$sessionEndTime = date('Y-m-d', strtotime($value->endDateTime));
						 $endDate = date('Y-m-d', strtotime($value->endDateTime));
						 $data['entity'] = $value->entity;
						 $data['title'] = '<a href="'.EVENT_DETAIL_LINK.'?endId='.$value->id.'">'.$value->name.'</a>';
						 $data['titleNoLink'] = $value->name;
						 $data['id']    = $value->id;
						 $data['start'] = date('Y-m-d', strtotime($event->startDateTime));
						 $data['end']   = date('Y-m-d', strtotime($event->endDateTime));
						 $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
						 $data['objectType'] = $value->type;
						 $data['name'] = $value->name;
						 $data['price'] = $value->defaultPrice;
						 $data['createdOn'] = date('Y-m-d', strtotime($value->startDateTime));
						 //$data['hours']      = $value->parentCourse->creditHours;
						 $data['icon'] = $value->imageUrl;
						 $data['schedule'] = $new_StartDate.' at '.$sessionStartTime.' - '.$sessionEndTime;
						 // $data['firstcondition'] = $i++;
						  $data['location'] = $value->location;
						 $class_schedule = '';
						 //$eventsTag = $value->tag;
						 //$allTags = array_diff($eventsTag, array('PUBLIC', 'public', 'Public'));
						  $filterTag = $value->tag;
						 $allTags = array();
						 if($filterTag){
						  foreach ($filterTag as $index => $tag) {
										  
							  $allTags[] = $tag;
						  }
					  }else{
						  $allTags[] ="NA";
					  }
		  
						 $data['endorsementTag'] = $allTags;
						 $data['viewdetails'] = '<a href="'.EVENT_DETAIL_LINK.'?endId='.$value->id.'" class="btn btn-secondary px-3 py-1" target="_blank">View Details</a>';
						
						 $default_RegisterBtn = "";
						 if ($event_status == 'Completed' || $registration_state == 'RegistrationClosed') {
							 $default_RegisterBtn = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$registration_state.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
						 }
						 else{
							 $default_RegisterBtn = '<a href="'.$tenant_url.'/pages/events/'. $value->id .'/general" target="_blank" class="btn btn-primary px-3 py-1" >Register</a>';
						 }
						 $data['register'] = $default_RegisterBtn;
						  $endorsementData[] = $data; 
					  }
			   }else{
				  $event_status = $value->status;
				  $registration_state = preg_replace('/(?<!\ )[A-Z]/', ' $0', $value->registrationState);
				   foreach ($value->classSessions as $index => $event) {
					   //session start time and date
					  $default_StartDate = $event->sessionDate;
					  $convert_StartDate = strtotime($default_StartDate);
					  $new_StartDate = date('M d, Y', $convert_StartDate);
					  $sessionStartTime = $event->startTime;//date('g:i A', $convert_StartDate);
					  //end here
	  
					  //session end date and time
					  $default_EndDate = $event->sessionDate;
					  $convert_EndDate = strtotime($default_EndDate);
					  $new_EndDate = date('M d, Y', $convert_EndDate);
					  $sessionEndTime = $event->endTime;//date('g:i A', $convert_EndDate);
					  // end here
	  
					  $startDate = date('Y-m-d', strtotime($value->startDateTime));
					  //$sessionEndTime = date('Y-m-d', strtotime($value->endDateTime));
						 $endDate = date('Y-m-d', strtotime($value->endDateTime));
						 $data['entity'] = $value->entity;
						 $data['title'] = '<a href="'.CLASS_DETAIL_LINK.'?classId='.$value->id.'">'.$value->name.'</a>';
						 $data['titleNoLink'] = $value->name;
						 $data['id']    = $value->id;
						 $data['start'] = date('Y-m-d', strtotime($value->startDateTime));
						 $data['end']   = date('Y-m-d', strtotime($value->endDateTime));
						 $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
						 $data['objectType'] = $value->type;
						 $data['name'] = $value->name;
						 $data['createdOn'] = date('Y-m-d', strtotime($value->startDateTime));
						 //$data['hours']      = $value->parentCourse->creditHours;
						 $data['icon'] = $value->imageUrl;
						 $data['schedule'] = $new_StartDate.' at '.$sessionStartTime.' - '.$sessionEndTime;
						 // $data['firstcondition'] = $i++;
						  $data['location'] = $value->location;
						 $class_schedule = '';
						 //$eventsTag = $value->tag;
						 //$allTags = array_diff($eventsTag, array('PUBLIC', 'public', 'Public'));
						  $filterTag = $value->tag;
						 $allTags = array();
						 if($filterTag){
						  foreach ($filterTag as $index => $tag) {
										  
							  $allTags[] = $tag;
						  }
					  }else{
						  $allTags[] ="NA";
					  }
		  
						 $data['endorsementTag'] = $allTags;
						 $data['viewdetails'] = '<a href="'.CLASS_DETAIL_LINK.'?classId='.$value->id.'" class="btn btn-secondary px-3 py-1" target="_blank">View Details</a>';
							$btnclass ='';
						 if($value->registrationUrlOnLine!==''){
							$RegisterBtn = $value->registrationUrlOnLine; 
						 }else if($value->registrationUrlOnLocation!==''){
							$RegisterBtn = $value->registrationUrlOnLocation; 
						 } else{
							 $RegisterBtn= '#';
							 $btnclass='disabled';
						 }
						 $data['register'] = '<a href="'.$RegisterBtn.'" target="_blank" class="btn btn-primary px-3 py-1 '.$btnclass.'" >Register</a>';
						  $endorsementData[] = $data; 
					  }
			   }
                   
               
           }
           return $endorsementData; 
		   
       }
public function geteventsClasscalendar(){
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day   = $_POST['day'] ? $_POST['day'] :date('d');
    $dateYear = ($year != '')?$year:date("Y");
    $dateMonth = ($month != '')?$month:date("m");
    $postedDate = $year.'-'.$month.'-'.$day;
    $date = $dateYear.'-'.$dateMonth.'-01';
    $currentMonthFirstDay = date("N",strtotime($date));
    $totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN,$dateMonth,$dateYear);
    $totalDaysOfMonthDisplay = ($currentMonthFirstDay == 1)?($totalDaysOfMonth):($totalDaysOfMonth + ($currentMonthFirstDay - 1));
    $boxDisplay = ($totalDaysOfMonthDisplay <= 35)?35:42;

    $prevMonth = date("m", strtotime('-1 month', strtotime($date)));
    $prevYear = date("Y", strtotime('-1 month', strtotime($date)));
    $totalDaysOfMonth_Prev = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);
    
    $options = get_option('ebt_api_settings');
    $events_visible_column_list = $options['events_visible_column_list'];
	
?>
    <main class="calendar-contain row">
    <?php echo $this->calendar_mode(); ?>
       
        <div class="col-12 pt-4">
            <div class="row ">
        <aside class="calendar__sidebar col-md-3 order-2 border  pb-4 class-background" id="event_list">
            
        </aside>

        <div class="calendar__days col-md-9  pt-5 border mb-4 mb-md-0 calendar-background  px-0" id="monthView">

            <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getEventsCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>','<?php echo date("d",strtotime($date.' - 1 Month')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo date("F",strtotime($date.' - 1 Month')); ?></span></a>
                <h3 class="text-center text-uppercase"><?php echo date("F Y",strtotime($date)); ?></h3>
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getEventsCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>','<?php echo date("d",strtotime($date.' + 1 Month')); ?>');"><span class="mr-2"><?php echo date("F",strtotime($date.' + 1 Month')); ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__top-bar bg-light border-top mt-4 text-uppercase d-flex text-center">
                <span class="top-bar__days  py-3 border-right">Mon</span>
                <span class="top-bar__days  py-3 border-right">Tue</span>
                <span class="top-bar__days  py-3 border-right">Wed</span>
                <span class="top-bar__days  py-3 border-right">Thu</span>
                <span class="top-bar__days  py-3 border-right">Fri</span>
                <span class="top-bar__days  py-3 border-right">Sat</span>
                <span class="top-bar__days  py-3 ">Sun</span>
            </div>

            <?php
                $dayCount = 1;
                $eventsdata = $this->eventsClassCalendar();
                echo '<div class="calendar__week text-center d-flex justify-content-around border-top">';
                for($cb=1;$cb<=$boxDisplay;$cb++){
                    if(($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)){
                        // Current date
                        $currentDate = $dateYear.'-'.$dateMonth.'-'.str_pad($dayCount, 2, '0', STR_PAD_LEFT);; 

                        // Get number of events based on the current date
                        
                        $filteredItems = array_filter($eventsdata, function($item) use ($currentDate) {
                           return $currentDate >=$item['start'] && $currentDate <=$item['start'] ;
                        });
                       sort($filteredItems);
                        // Define date cell color
                        if(strtotime($currentDate) == strtotime(date("Y-m-d")) && count($filteredItems) > 0){
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0 today bg-light border border-success" style="overflow: auto;" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($filteredItems)) {echo json_encode($filteredItems);}else{ echo "no-data"; } ?>'>
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text">
                                    <?php if(count($filteredItems) > 0){
                                         for($fi=0; $fi<count($filteredItems); $fi++){
                                            $test = $filteredItems[$fi]['name'];
                                            $test = substr($test,0,20);
                                           echo '<div class="classNames">';
                                           ?>
                                        <a class="calendar-class badge badge-dark" data-toggle="modal" data-target="#exampleModal<?php echo $filteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?>...</a>                                    
                                                <?php
                                        
                                           if(count($filteredItems) >1) {$test; } 
                                         
                                        echo "</div>";
                                        }
                                    } ?>
                                    </span>
                                </div>
                                <?php  for($fi=0; $fi<count($filteredItems); $fi++){
                                       
                                       $test = $filteredItems[$fi]['name'];
                                       $test = substr($test,0,20);
                                      ?>
                                        <!-- Modal -->
                                           <div class="modal fade" id="exampleModal<?php echo $filteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                               <div class="modal-header text-left align-items-center">
                                                   <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title mt-0"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?><span class="badge badge-secondary ml-2"><?php echo $filteredItems[$fi]['entity']; ?></span></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                               </div>
                                               <div class="modal-body text-left" >
                                               
                                               <table class="table table-borderless table-sm text-left">
                                               		<?php if(in_array('startDateTime', $events_visible_column_list)){ ?>
                                                   <tr><td><strong>Date :</strong> </td><td><?php echo $filteredItems[$fi]['schedule']; ?></td></tr>
                                                   <?php }  if(in_array('eventType', $events_visible_column_list)){ ?>
                                                    <tr><td><strong>Type : </strong></td><td><?php echo $filteredItems[$fi]['objectType']; ?></td></tr>
                                                   <?php } ?>
                                                     <tr><td><strong>Price :</strong> </td><td><?php echo '$'.$filteredItems[$fi]['price']; ?></td></tr>
                                                   <!-- <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p> -->	
                                               </table>
                                                 
                                                   
                                               </div>
                                               <div class="modal-footer">
                                              <?php if($filteredItems[$fi]['entity']=='Class'){ ?>
                                        <a href="<?php echo CLASS_DETAIL_LINK;?>?classId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php } else { ?>
                                        <a href="<?php echo EVENT_DETAIL_LINK;?>?endId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php } ?>
                                               <?php if(in_array('register', $events_visible_column_list)) { echo $filteredItems[$fi]['register']; } ?>
                                               </div>
                                               </div>
                                           </div>
                                           </div> 
                                           <?php
                                   
                                      
                                   }
                                ?>
                            <?php
                        }
						elseif(count($filteredItems) > 0){
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0" style="overflow: auto;" data-event="<?php echo $currentDate; ?>" data-start='<?php echo json_encode($filteredItems); ?>' onclick="getEvents('<?php echo $currentDate; ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                                    <?php
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
                                            $test = $filteredItems[$fi]['name'];
                                            //$test = substr($test,0,20);
                                        //echo $test.'...'; 
                                        echo '<div class="classNames">';
                                        ?>
                                        <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal<?php echo $filteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?>...</a>                                    
                                            <?php
                                        if(count($filteredItems) >1) {$test; } 
                                        echo "</div>";
                                        }?>
                                        </span>
                                </div>
                                <?php
                             for($fi=0; $fi<count($filteredItems); $fi++){ 
                                            
                                $test = $filteredItems[$fi]['name'];
                                $test = substr($test,0,20);
                            ?>
                                    <div class="modal fade" id="exampleModal<?php echo $filteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header text-left d-flex align-items-center">
                                        <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title mt-0"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5><span class="badge badge-secondary ml-2"><?php echo $filteredItems[$fi]['entity']; ?></span>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-left">
                                         <table class="table table-sm text-left">
                                                   <?php if(in_array('startDateTime', $events_visible_column_list)){ ?>
                                                   <tr><td><strong>Date :</strong></td><td> <?php echo $filteredItems[$fi]['schedule']; ?></td></tr>
                                                   <?php } if($filteredItems[$fi]['entity']=='Class' ) { ?>
                                                   <tr><td><strong>Duration : </strong></td><td><?php echo $filteredItems[$fi]['classDuration']; ?></td></tr>
                                                   <?php } if(in_array('eventType', $events_visible_column_list)){ ?>
                                                   <tr><td><strong>Type : </strong></td><td><?php echo $filteredItems[$fi]['objectType']; ?></td></tr>
                                                  <?php }  ?>
                                                   <tr><td><strong>Tags :</strong></td><td>
														<?php $tags = $filteredItems[$fi]['endorsementTag'];
													  if (is_array($tags) && count($tags) === 1 && $tags[0] === "NA") {
														  echo "N/A";
													  } 
													  elseif (is_array($tags) && isset($tags[0]->tagName)) {  
														  $tagNames = array_map(fn($tag) => $tag->tagName, $tags); 
														  echo implode(", ", $tagNames);
													  } 
                                                        ?>
                                                    </td></tr>
                                                   
                                                   <!-- <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p> -->
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                        <?php if($filteredItems[$fi]['entity']=='Class'){ ?>
                                        <a href="<?php echo CLASS_DETAIL_LINK;?>?classId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php } else { ?>
                                        <a href="<?php echo EVENT_DETAIL_LINK;?>?endId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php } ?>
                                        <?php if(in_array('register', $events_visible_column_list)) { echo $filteredItems[$fi]['register']; } ?>
                                        </div>
                                        </div>
                                    </div>
                                    </div> 
                                <?php
                            
                            }?>
                    <?php
                        }else{
                            echo '
                                <div class="calendar__day no-event border-right col flex-column d-flex p-0" data-event="'.$currentDate.'" data-start="no-data">
                                    <span class="calendar__date mt-auto calendar-text">'.$dayCount.'</span>
                                    <span class="calendar__task small pt-lg-2 mb-auto"></span>
                                    
                                </div>
                            ';
                        }
                        $dayCount++;
                    }else{
                        if($cb < $currentMonthFirstDay){
                            $inactiveCalendarDay = ((($totalDaysOfMonth_Prev-$currentMonthFirstDay)+1)+$cb);
                            $inactiveLabel = 'expired';
                        }else{
                            $inactiveCalendarDay = ($cb-$totalDaysOfMonthDisplay);
                            $inactiveLabel = 'upcoming';
                        }
                        echo '
                            <div class="calendar__day no-event border-right col flex-column d-flex p-0 inactive">
                                <span class="calendar__date my-auto">'.$inactiveCalendarDay.'</span>
                               
                            </div>
                        ';
                    }
                    echo ($cb%7 == 0 && $cb != $boxDisplay)?'</div><div class="calendar__week text-center d-flex justify-content-around border-top">':'';
                }
                echo '</div>';
            ?>
        </div>
          <div id="weekView" class="calendar__days col-12 pt-5 px-0 border">
            <?php
                    list($week_start_date, $week_end_date) = $this->x_week_range($postedDate);
                    $week_start_date = date("Y-m-d",strtotime($week_start_date.' +1 day'));
                    $week_end_date = date("Y-m-d",strtotime($week_end_date.' +1 day'));
                    $week_array = $this->date_range($week_start_date, $week_end_date);


            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getEventsCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' - 7 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2">Prev</span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getEventsCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' + 7 day')); ?>');"><span class="mr-2">Next</span><i class="fa fa-chevron-right"></i></a>
            
            <div class="calendar__top-bar bg-light border-top mt-4 text-uppercase d-flex text-center">
                <span class="top-bar__days  py-3 border-right">Mon</span>
                <span class="top-bar__days  py-3 border-right">Tue</span>
                <span class="top-bar__days  py-3 border-right">Wed</span>
                <span class="top-bar__days  py-3 border-right">Thu</span>
                <span class="top-bar__days  py-3 border-right">Fri</span>
                <span class="top-bar__days  py-3 border-right">Sat</span>
                <span class="top-bar__days  py-3 ">Sun</span>
            </div>
            <div class="calendar__week text-center d-flex justify-content-around border-top">
            <?php 
                for ($i=0; $i <7 ; $i++) { 
                   
                        $currentDate = $week_array[$i];

                        // Get number of events based on the current date
                        
                        $weekfilteredItems = array_filter($eventsdata, function($item) use ($currentDate) {
                            return $currentDate >=$item['start'] && $currentDate <=$item['start'] ;
                        });
                        sort($weekfilteredItems);
            ?>			
            
                        <div class="calendar__day border-right <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light'; } ?>" style="overflow: auto;" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
                            <span class="calendar__date mt-auto calendar-text"><?php echo date('d',strtotime($week_array[$i]));  ?></span>
                            <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text">
                            <?php if(count($weekfilteredItems) > 0){
                                for($fi=0; $fi<count($weekfilteredItems); $fi++){ 
                                  $test = $weekfilteredItems[$fi]['name'];
                                  $test = substr($test,0,20);
                              //echo $test.'...'; 
                              echo '<div class="classNames">';
                              ?>
                              <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?>...</a>                                    
                                  <?php
                              if(count($weekfilteredItems) >1) {$test; } 
                              echo "</div>";
                                //echo count($weekfilteredItems).' Event'; if(count($weekfilteredItems) >1) {echo "s"; }
                            } }?>
                            </span>
                        </div>
                        
                        
                        
                         <?php  for($fi=0; $fi<count($weekfilteredItems); $fi++){
                                       
                                       $test = $weekfilteredItems[$fi]['name'];
                                       $test = substr($test,0,20);
                                      ?>
                                        <!-- Modal -->
                                           <div class="modal fade" id="exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                               <div class="modal-header text-left align-items-center">
                                                   <img src="<?php echo $weekfilteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $weekfilteredItems[$fi]['title']; ?></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                               </div>
                                               <div class="modal-body text-left" >
                                                   <p><strong>Dates :</strong> <?php echo $weekfilteredItems[$fi]['schedule']; ?></p>
                                                   <p><strong>Type : </strong><?php echo $weekfilteredItems[$fi]['objectType']; ?></p>
                                                   <p><strong>Prices :</strong> <?php echo '$'.$weekfilteredItems[$fi]['price']; ?></p>
                                                   <!-- <p><strong>Credit Hours : </strong><?php echo $weekfilteredItems[$fi]['hours']; ?></p> -->
                                                   
                                               </div>
                                               <div class="modal-footer">
                                               <?php if($filteredItems[$fi]['entity']=='Class'){ ?>
                                        <a href="<?php echo CLASS_DETAIL_LINK;?>?classId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php } else { ?>
                                        <a href="<?php echo EVENT_DETAIL_LINK;?>?endId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php } ?>
                                               <?php echo $weekfilteredItems[$fi]['register']; ?>
                                               </div>
                                               </div>
                                           </div>
                                           </div> 
                                           <?php
                                   
                                      
                                   }
                                ?>
                                
                                
                                
                        <?php                    
                }
            ?>
            </div>
           
        </div>
        <div id="dayView" class="calendar__days col-12 pb-4 pt-5 px-lg-5 border">
            <?php
                    $prev_date = date('D', strtotime($postedDate .' -1 day'));
                    $next_date = date('D', strtotime($postedDate .' +1 day'));
            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getEventsCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' - 1 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo $prev_date; ?></span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getEventsCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' + 1 day')); ?>');"><span class="mr-2"><?php echo $next_date; ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__week text-center justify-content-around" id="today_event">

            </div>

        </div>
    </div>
</div>
    </main>

<?php
wp_die();
}
    //GroupMembers
     public function peopleloadGridDataByGroups(){
        $options = get_option('ebt_api_settings');
        $front_pages = $options['front_pages'];
        //$postedData = $this->_preparePeopleData();
        $groupId = $_POST['groupId'];
        $viewMode = $_POST['viewMode'];
       $sortDirection = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : 'asc';
	   $searchText = isset($_POST["columns"][$_POST['titleColumn']]["search"]["value"]) ? $_POST["columns"][$_POST['titleColumn']]["search"]["value"] : '';
$postedData = '{
  "groupId": "' . $groupId . '",
  "itemCount": ' . $_POST['length'] . ',
  "sortBy": "name",
  "sortDirection": "' . $sortDirection . '",
  "pageNumber": ' . (int)(($_POST['start'] / $_POST['length']) + 1) . ',
  "filterBody": {
    "pageSize": 10,
    "pageNumber": 1,
	"searchText":"'.$searchText.'"
  },
  "fields": [
    {"fieldId": "isFavorite", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "firstName", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "title", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "email", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "phone", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "department", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "position", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "terms", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "status", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "createdDate", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "modifiedDate", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "userStatus", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "roles", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "lastLogin", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "totalTimeWorked", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "organization", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "personas", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "tags", "controlTypeId": 0, "isCustom": false},
    {"fieldId": "action", "controlTypeId": 0, "isCustom": false}
  ]
}';
        $dataResponse = $this->submitApiRequest("groups/GroupPeopleListLite/".$groupId, json_decode($postedData), "POST", 'dashboard'); 
        $collection   = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->totalCount;
        //print_r(json_encode($collection)); die;
		/*if($_POST['countResult']=='true'){
			echo json_encode($totalcount);
        	wp_die();
			return;		
		}*/
        $request = $_GET;
        $data    = array();
		if($viewMode=='Grid'){ 
		  $response = [
			  'count' => $totalcount,
			  'data' => $collection
		  ];
		  echo json_encode($response);
		  wp_die();
		}
        foreach ($collection as $key => $value) { 
            $nestedData = array();
            $classPopover      = '';
			$nestedData['name']='<div class="d-flex align-items-center">';
			if($value->people->imageThumbUrl && filter_var($value->people->imageThumbUrl, FILTER_VALIDATE_URL)){
				$nestedData['name'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->fullName.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->imageThumbUrl.'">';	
			}else{
				$nestedData['name'].='<i class="fas fa-user-circle mr-2" style="font-size:40px; color:#979797"></i>';
			}
            $nestedData['name'] .= '<div>'.$value->people->firstName.' '.$value->people->lastName.'</div>';
            $nestedData['email'] = '<a href="mailto:'.$value->people->email.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->people->email.'</a>';
           $positionName = '';
                if (!empty($value->people->peoplePosition) && is_array($value->people->peoplePosition)) {
                    foreach ($value->people->peoplePosition as $position) {
                        if (!empty($position->isCurrent) && $position->isCurrent === true) {
                            $positionName = $position->positionName;
                            break; // Stop at the first match
                        }
                    }
                }
            $nestedData['position'] = $positionName;
			$nestedData['organization']='<div class="d-flex align-items-center">';
			if($value->people->organization->imageThumbUrl && filter_var($value->people->organization->imageThumbUrl, FILTER_VALIDATE_URL)){
				$nestedData['organization'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->organization->imageThumbUrl.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->organization->imageThumbUrl.'">';	
			}else{
				$nestedData['organization'].='<i class="fas fa-landmark mr-2" style="font-size:30px; color:#979797"></i>';
			}
            $nestedData['organization'] .= '<div>'.$value->people->organization->name.'</div>';
            $nestedData['department'] = $value->people->peopleDepartment[0]->departmentName;

            $rawPhone = $value->people->primaryPhoneNumber->value ?? '';
			if (!empty($rawPhone) && preg_match('/^\d{10}$/', $rawPhone)) {
				$formattedPhone = preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $rawPhone);
				$nestedData['phone'] = '<a href="tel:' . $rawPhone . '" style="text-decoration: none;">' . $formattedPhone . '</a>';
			} else {
				$nestedData['phone'] = 'N/A';
			}
		$data[] = $nestedData;
        }
       
        $draw           = $_POST['draw'];
        $start          = $_POST['start']; //0, 5
        $length         = $_POST['length']; //5, 10 per page.

        $json_data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data" => $data,
        );
        echo json_encode($json_data);
        wp_die();
    }

public function getOrganizations(){
        $options = get_option('ebt_api_settings');
        $front_pages = $options['front_pages'];
        //$postedData = $this->_preparePeopleData();        
        $viewMode = $_POST['viewMode'];
       $sortDirection = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : 'asc';
	   $searchText = isset($_POST["columns"][$_POST['titleColumn']]["search"]["value"]) ? $_POST["columns"][$_POST['titleColumn']]["search"]["value"] : '';

        $postedData = '{
            "itemCount":  ' . $_POST['length'] . ',
            "pageNumber": ' . (int)(($_POST['start'] / $_POST['length']) + 1) . ',
            "sortBy": "createdOn",
            "sortDirection":"' . $sortDirection . '",
            "filterBody": {
                "filterRules": [
                    {
                        "fieldId": "status",
                        "filterType": 4,
                        "selectedValues": ["Active"]
                    }
                ],
                "search": [],
                "selectedDate": "2025-05-28",
                "IsShowPastOrg": false,
                "pageSize": 1000,
                "isFlatView": true,
                "allOrganizationPermission": {
                    "viewClientOrganization": true,
                    "viewAllMemberOrganizations": true,
                    "viewOwnOrganization": true,
                    "viewChildOrganization": true
                },
                "searchText":"'.$searchText.'"
            }
        }';
        $dataResponse = $this->submitApiRequest("Organization/OrganizationPagingList", json_decode($postedData), "POST", 'dashboard'); 
        $collection   = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->totalCount;
    //print_r(json_encode($collection)); die;
		/*if($_POST['countResult']=='true'){
			echo json_encode($totalcount);
        	wp_die();
			return;		
		}*/
        $request = $_GET;
        $data    = array();
		if($viewMode=='Grid'){ 
		  $response = [
			  'count' => $totalcount,
			  'data' => $collection
		  ];
		  echo json_encode($response);
		  wp_die();
		}
        foreach ($collection as $key => $value) { 
            $nestedData = array();
            $locationPopOver      = '';
           if(count($value->locations)) {
                $locationPopOver  = $this->_popOverOrgLocationData($key, $value->locations);
			}
            $locationCount = 0 ;
            foreach($value->locations as $key => $location){

                if($location->fieldName){
                    $locationCount = $locationCount+1;
                }
            }
			$nestedData['OrganizationName']='<div class="d-flex align-items-center">';
			if($value->imageThumbUrl && filter_var($value->imageThumbUrl, FILTER_VALIDATE_URL)){
				$nestedData['OrganizationName'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->name.'" class="rounded-circle img-fluid mr-2" src="'.$value->imageThumbUrl.'">';	
			}else{
				$nestedData['OrganizationName'].='<i class="fas fa-user-circle mr-2" style="font-size:40px; color:#979797"></i>';
			}
            $nestedData['OrganizationName'] .= '<div><a class="text-nowrap" href="'.site_url().'/my-profile/?member='.$value->id.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->name.'</a></div>';
            $nestedData['Status'] = $value->status;
            $nestedData['Active/totalmember'] = $value->totalMembers.'/'.$value->activeMembers;	
			//$nestedData['Location']= $value->locationInfo[0]->locationValue ? $value->locationInfo->locationValue : 'N/A';
           if ((!empty($value->locations) && count($value->locations) > 0)) {             
                $nestedData['Location'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$locationCount.'</span></div>'.$locationPopOver.'</div>';
            } else {
                $nestedData['Location'] = '<div class="dropdown"><div class=" instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter: grayscale(1);"><span style="visibility: hidden;" class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"></span></div></div>';
            }
          $nestedData['phoneNumbers'] = '';
            if($value->phoneNumbers && $value->phoneNumbers[0]->value){
                $rawPhone = $value->phoneNumbers[0]->value;
                if (!empty($rawPhone) && preg_match('/^\d{10}$/', $rawPhone)) {
                    $formattedPhone = preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $rawPhone);
                    $nestedData['phoneNumbers'] = '<a href="tel:' . $rawPhone . '" style="text-decoration: none;">' . $formattedPhone . '</a>';
                } else {
                    $nestedData['phoneNumbers'] = '';
                }
            }
            $nestedData['OrganizationType'] = $value->organizationType ? $value->organizationType : '';
             $nestedData['Email'] = $value->primaryEmail ? $value->primaryEmail : 'N/A';
            // $organizationTags = $value->organizationTags;
             $filterTag = $value->organizationTags;
            // $allTags = array_diff($filter, array('PUBLIC', 'public', 'Public'));
            // $filterTag = array_values($allTags);
             $default_Tags = array();
             if (is_array($filterTag) && count($filterTag)) {

                 $allTags = array();
                
                 foreach ($filterTag as $index => $tag) {
						$default_Tags[$index] = new stdClass();
                     $default_Tags[$index]->tagName = $tag->tagName;
                     $default_Tags[$index]->id =$index;
                 }
                
                 foreach ($default_Tags as $index => $value) {
                   
                     if(count($default_Tags) > 1 && $index == 0)
                     {   
                        
                         $tagPopover =  $this->_popOverTagData1($key, $default_Tags);
                          $tagCount   = count($default_Tags) - 1;
       		 	$allTags[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$value->tagName.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span>'.$tagPopover.'</div>';
					
                     }
                     elseif(count($default_Tags) == 1)
                         $allTags[] = $value->tagName;

                 }
                $nestedData['Tags'] = $allTags;
             }else{
                 $nestedData['Tags'] = [];
             }
          
		$data[] = $nestedData;
        }
       
        $draw           = $_POST['draw'];
        $start          = $_POST['start']; //0, 5
        $length         = $_POST['length']; //5, 10 per page.

        $json_data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data" => $data,
        );
       //print_r($json_data);
        echo json_encode($json_data);
        wp_die();
    }

public function _popOverOrgLocationData($id, $locationData){
$rowName = array();
     
 $popOverHtml = '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Locations</h6><div class="px-2 border-bottom pb-2"></div>';
	 $subItems = "";
$li=1;
	
     foreach ($locationData as $key => $rowData) {
        // Access address fields directly
        $address = isset($rowData->address) ? $rowData->address : '';
        $addressLine2 = isset($rowData->addressLine2) ? $rowData->addressLine2 : '';
        $city = isset($rowData->city) ? $rowData->city : '';
        $state = isset($rowData->state) ? $rowData->state : '';
        $zip = isset($rowData->zipCode) ? $rowData->zipCode : '';
        $country = isset($rowData->country) ? $rowData->country : '';
        $fieldName = isset($rowData->fieldName) && $rowData->fieldName ? $rowData->fieldName : 'Address:';

       $rowName[$rowData->id ?? $key] = $locationName;
        $class = ($li % 2 == 1) ? 'bg-light' : '';

        $subItems .= '<li class="px-2 py-1 border-bottom small '.$class.'">';
        $subItems .= '<a class="d-flex align-items-center pr-2" data-toggle="collapse" href="#loc-'.$key.'" role="button" aria-expanded="false" aria-controls="collapseExample"><b>'.$fieldName.'</b><i class="fa fa-chevron-down ml-auto"></i></a>';
        $subItems .= '<div class="collapse text-wrap" id="loc-'.$key.'">';
        $subItems .= $address;
        if ($addressLine2) $subItems .= ', ' . $addressLine2;
        $subItems .= ', ' . $city . ', ' . $state . ', ' . $zip . ', ' . $country;
        // Optionally add map if lat/lng present
        if (!empty($rowData->lat) && !empty($rowData->lng)) {
            $subItems .= '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q='.$rowData->lat.','.$rowData->lng.'&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div>';
        }
        $subItems .= '</div>';
        $subItems .= '</li>';
        $li++;
    }

    $popOverHtml .= $subItems;
    $popOverHtml .= '<span class="px-2 py-1 text-center small d-none">No results found!</span></div>';
    $vars = "";
    $popOverHtml .= '</span></div></div></div></div> ';
    return $popOverHtml . $vars;
}
private function _popOverTagData1($id, $tagData){
        
        $rowName = array();
        $popOverHtml .= '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Associated Tags</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown" placeholder="Search tags.."/></div>';
        $subItems = "";
		$li=1;
        foreach ($tagData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->tagName;
           
            $class='';
            if($li%2==1){
			$class='bg-light';	
			}
            $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">' . $rowData->tagName .  '</li>';
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

        return $popOverHtml . $vars;
    }
}
