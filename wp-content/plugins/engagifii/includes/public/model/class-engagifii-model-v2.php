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
            $nestedData['name'] .= '<div><a class="text-nowrap" href="'.site_url().'/my-profile/?member='.$value->people->id.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->people->firstName.' '.$value->people->lastName.'</a></div>';
            $nestedData['email'] = '<a href="mailto:'.$value->people->email.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->people->email.'</a>';
            $nestedData['position'] =$value->people->peoplePosition[0]->positionName;	
			$nestedData['organization']='<div class="d-flex align-items-center">';
			if($value->people->organization->imageThumbUrl && filter_var($value->people->organization->imageThumbUrl, FILTER_VALIDATE_URL)){
				$nestedData['organization'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->organization->imageThumbUrl.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->organization->imageThumbUrl.'">';	
			}else{
				$nestedData['organization'].='<i class="fas fa-landmark mr-2" style="font-size:30px; color:#979797"></i>';
			}
            $nestedData['organization'] .= '<div>'.$value->people->organization->name.'</div>';
            $nestedData['phone'] = !empty($value->people->primaryPhoneNumber->value) ? 
    '<a href="tel:' . $value->people->primaryPhoneNumber->value . '" style="text-decoration: none;">' . $value->people->primaryPhoneNumber->value . '</a>' : 'N/A';
			/*$nestedData['persontype'] =$value->people->personTypes[0]->name;
            $nestedData['status'] =$value->people->status;
            if($value->people->primaryPhoneNumber->value){
                $phoneNumber = $value->people->primaryPhoneNumber->value;               
                $phoneNumber = preg_replace('/\D/', '', $phoneNumber);               
                if (strlen($phoneNumber) == 10) {                    
                    $formattedPhoneNumber = '(' . substr($phoneNumber, 0, 3) . ') ' . substr($phoneNumber, 3, 3) . '-' . substr($phoneNumber, 6);
                    $nestedData['officephone'] = '<span class="text-nowrap">'.$formattedPhoneNumber.'</span>';
                } else {                    
                    $nestedData['officephone'] = '<span class="text-nowrap">'.$value->people->primaryPhoneNumber->value.'</span>';
                }
            }else{
                $nestedData['officephone'] ='--';
            }
            if($value->people->lastLogin){
                    $timestamp = strtotime($value->people->lastLogin);
                    $nestedData['lastlogin'] = date('M d, Y', $timestamp);
            }else{ $nestedData['lastlogin'] ='--';}
            
            if($value->people->modifiedDate){
                $timestamp = strtotime($value->people->modifiedDate);
                $nestedData['lastupdated'] = date('M d, Y', $timestamp);
            }else{ $nestedData['lastupdated'] ='--';}

            
            if ($value->people->totalTimeWorked) {
                $totalMonths = $value->people->totalTimeWorked;
                $years = floor($totalMonths / 12);
                $remainingMonths = $totalMonths % 12;
                
                // Construct the output string
                $output = '';
                if ($years > 0) {
                    $output .= $years . ' yr';
                    if ($years > 1) {
                        $output .= 's';
                    }
                    $output .= ' ';
                }
                if ($remainingMonths > 0) {
                    $output .= $remainingMonths . ' mo';
                    if ($remainingMonths > 1) {
                        $output .= 's';
                    }
                }
                
                $nestedData['totaltime'] = $output;
            } else {
                $nestedData['totaltime'] = '--';
            }
               

			//$nestedData['organization'] = $value->people->organization->name;
			if($value->people->peoplePosition){
				if(count($value->people->peoplePosition)==1){
					$nestedData['organization']='<div class="d-flex align-items-center" data-orgId="'.$value->people->peoplePosition[0]->organizationId.'">';
					if($value->people->peoplePosition[0]->imageThumbUrl && filter_var($value->people->peoplePosition[0]->imageThumbUrl, FILTER_VALIDATE_URL)){
						$nestedData['organization'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->peoplePosition[0]->organizationName.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->peoplePosition[0]->imageThumbUrl.'">';	
					}else{
						$nestedData['organization'].='<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
					}
					$nestedData['organization'] .=$value->people->peoplePosition[0]->organizationName.'</div>';
					$nestedData['position'] =$value->people->peoplePosition[0]->positionName;
				}else{
					//positions
					$classPopover = dd_header('Positions');
					$subItems = "";
					$li=1;
					foreach ($value->people->peoplePosition as $key => $rowData) {
						$class='';
						if($li%2==1){
						  $class='bg-light';	
						}
						$subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">'.$rowData->positionName.'<span class="pl-2 d-block" style="color:#21086b;">'.$rowData->organizationName.'</span></li>';
                        $subItems .= ' ';
						$li++;
					}
					$classPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
					 $nestedData['position'] = '<div class="dropdown"><a href="" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" data-offset="60,0" data-toggle="dropdown" class="class_'.$key.' " data-placement="left">'.count($value->people->peoplePosition).' Positions</a>'.$classPopover.'</div>';
					//organizations
					$classPopover = dd_header('Organizations');
					$subItems = "";
					$li=1;
                    $orgCount = 0;
                    $seenOrgs = [] ;
					foreach ($value->people->peoplePosition as $key => $rowData) {
                      	$class='';
						if($li%2==1){
						  $class='bg-light';	
						}
						if (!in_array($rowData->organizationName, $seenOrgs)) {
                            $subItems .= ' <li class="px-2 py-1 border-bottom  small ' . $class . '">' . $rowData->organizationName . '</li>';
                            $seenOrgs[] = $rowData->organizationName; // add org name to the array
                            $orgCount++;
                        }
						$li++;
					}
                    if($orgCount>1){
                        $classPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
                        $nestedData['organization'] = '<div class="dropdown"><a href="" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" data-offset="60,0" data-toggle="dropdown" class="class_'.$key.' " data-placement="left">'.$orgCount.' Organizations</a>'.$classPopover.'</div>';
                    }else{
                                $nestedData['organization']='<div class="d-flex align-items-center">';	
                            if($rowData->imageThumbUrl && filter_var($rowData->imageThumbUrl, FILTER_VALIDATE_URL)){
                                $nestedData['organization'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$seenOrgs[0].'" class="rounded-circle img-fluid mr-2" src="'.$rowData->imageThumbUrl.'">';	
                            }else{
                                $nestedData['organization'].='<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
                            }
                            $nestedData['organization'] .=$seenOrgs[0].'</div>';
                        }
				}
			}else{
				$nestedData['organization'] ='--';
				$nestedData['position'] = '--';	
			}
             if($value->people->organization->name){
             $nestedData['primaryorganization'] = $value->people->organization->name;
            }
             else{
                 $nestedData['primaryorganization'] = '--';
             }
            if($value->people->peopleDepartment){
				if(count($value->people->peopleDepartment)==1){
					$nestedData['department']='<div class="d-flex align-items-center">';	
					if($value->people->peopleDepartment[0]->imageThumbUrl && filter_var($value->people->peopleDepartment[0]->imageThumbUrl, FILTER_VALIDATE_URL)){
						$nestedData['department'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->peopleDepartment[0]->organizationName.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->peopleDepartment[0]->imageThumbUrl.'">';	
					}else{
						$nestedData['department'].='<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
					}
					$nestedData['department'] .=$value->people->peopleDepartment[0]->organizationName.'</div>';
					$nestedData['department'] =$value->people->peopleDepartment[0]->departmentName;
				}else{
					//positions
					$classPopover = dd_header('Departments','Search departments..');
					$subItems = "";
					$li=1;
					foreach ($value->people->peopleDepartment as $key => $rowData) {
						$class='';
						if($li%2==1){
						  $class='bg-light';	
						}
						$subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">'.$rowData->departmentName.'</li>';
                        $subItems .= ' <span class="dropdown-item pr-2 pl-4 mb-2 py-0 small" style="color:#21086b;"><li class="px-2 py-1 border-bottom  small '.$class.'">'.$rowData->organizationName.'</li></span>';
						$li++;
					}
					$classPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
					 $nestedData['department'] = '<div class="dropdown"><a href="" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" data-offset="60,0" data-toggle="dropdown" class="class_'.$key.' " data-placement="left">'.count($value->people->peopleDepartment).' Departments</a>'.$classPopover.'</div>';
					
					//$classPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
					// $nestedData['organization'] = '<div class="dropdown"><a href="" data-offset="60,0" data-toggle="dropdown" class="class_'.$key.' " data-placement="left">'.count($value->people->peoplePosition).' Organizations</a>'.$classPopover.'</div>';
				}
			}else{
				//$nestedData['organization'] ='--';
				$nestedData['department'] = '--';	
			}
            $nestedData['totaltimecommittiee'] ='';
            $nestedData['roles'] ='';
            $nestedData['totaltimeworked'] ='';*/
			
            
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
 }
