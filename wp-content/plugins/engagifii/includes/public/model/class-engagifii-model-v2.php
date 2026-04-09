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
        ['groupMemberFilters', 'groupMemberFilters'],
        ['getCustomFieldFilterData', 'getCustomFieldFilterData'],
        ['groupCountFilterData', 'groupCountFilterData'],
        //['organizationFilters', 'organizationFilters'],
        ['organizationCountFilterData', 'organizationCountFilterData'],
        ['getOrganizationFilterConfiguration', 'getOrganizationFilterConfiguration'],
        ['getFilterItemsFromServiceUrl', 'getFilterItemsFromServiceUrl']
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
    //$events_visible_column_list = $options['events_visible_column_list'];
	$columnNames=[];
		if (!empty(EVENTS_COLS) && isArrayOfJsonStrings(EVENTS_COLS)) {
			  $columnNames = extractColNames(EVENTS_COLS);
		}
	
	
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
                $eventsdata = $this->eventsClassCalendar() ?? [];
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
                                                   <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle" alt="image icon"><h5 class="modal-title mt-0"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?><span class="badge badge-secondary ml-2"><?php echo $filteredItems[$fi]['entity']; ?></span></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                               </div>
                                               <div class="modal-body text-left" >
                                               
                                               <table class="table table-borderless table-sm text-left">
                                               		<?php if(in_array('startDateTime', $columnNames)){ ?>
                                                   <tr><td><strong>Date :</strong> </td><td><?php echo $filteredItems[$fi]['schedule']; ?></td></tr>
                                                   <?php }  if(in_array('eventType', $columnNames)){ ?>
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
                                               <?php if(in_array('register', $columnNames)) { echo $filteredItems[$fi]['register']; } ?>
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
                                        <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle" alt="image icon"><h5 class="modal-title mt-0"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5><span class="badge badge-secondary ml-2"><?php echo $filteredItems[$fi]['entity']; ?></span>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-left">
                                         <table class="table table-sm text-left">
                                                   <?php if(in_array('startDateTime', $columnNames)){ ?>
                                                   <tr><td><strong>Date :</strong></td><td> <?php echo $filteredItems[$fi]['schedule']; ?></td></tr>
                                                   <?php } if($filteredItems[$fi]['entity']=='Class' ) { ?>
                                                   <tr><td><strong>Duration : </strong></td><td><?php echo $filteredItems[$fi]['classDuration']; ?></td></tr>
                                                   <?php } if(in_array('eventType', $columnNames)){ ?>
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
                                        <?php if(in_array('register', $columnNames)) { echo $filteredItems[$fi]['register']; } ?>
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
                                                   <img src="<?php echo $weekfilteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle" alt="image icon"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $weekfilteredItems[$fi]['title']; ?></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                               </div>
                                               <div class="modal-body text-left" >
                                                   <p><strong>Dates :</strong> <?php echo $weekfilteredItems[$fi]['schedule']; ?></p>
                                                   <p><strong>Type : </strong><?php echo $weekfilteredItems[$fi]['objectType']; ?></p>
                                                   <p><strong>Prices :</strong> <?php echo '$'.$weekfilteredItems[$fi]['price']; ?></p>
                                                   <!-- <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p> -->
                                                   
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
        $tenantCode = $options['dashboard_tenant_code'];
        $context = 'list'; // or 'list', depending on your use-case
$visible_columns = $options['group_members_settings'][$context]['visible_column_list'] ?? [];
//print_r($visible_columns); die;
$custom_fields = [];
foreach ($visible_columns as $col_json) {
    $col = json_decode(stripslashes($col_json), true);
    if (!empty($col['fieldId'])) {
        $custom_fields[] = [
            'fieldId' => $col['fieldId'],
            'controlTypeId' => isset($col['controlTypeId']) ? (int)$col['controlTypeId'] : 3,
            'fieldType' => $col['fieldType'] ?? null,
            'isCustom' => true
        ];
    }
}
        $front_pages = $options['front_pages'];
        $groupId = $_POST['groupId'];
        $viewMode = $_POST['viewMode'];
        $sortDirection = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : 'asc';
        $searchText = isset($_POST["columns"][$_POST['titleColumn']]["search"]["value"]) ? $_POST["columns"][$_POST['titleColumn']]["search"]["value"] : '';
        
        // Get filter parameters
        $departments = isset($_POST['departments']) && is_array($_POST['departments']) ? $_POST['departments'] : [];
        $positions = isset($_POST['positions']) && is_array($_POST['positions']) ? $_POST['positions'] : [];
        $personTypes = isset($_POST['personTypes']) && is_array($_POST['personTypes']) ? $_POST['personTypes'] : [];
        $organizations = isset($_POST['organizations']) && is_array($_POST['organizations']) ? $_POST['organizations'] : [];
        $roles = isset($_POST['roles']) && is_array($_POST['roles']) ? $_POST['roles'] : [];
       // print_r($organizations); die;
        // Build filter rules for API
        $filterRules = [];
        
        if (!empty($departments)) {
            $filterRules[] = [
                "fieldId" => "departments", // Try departmentId instead of department
                "filterType" => 4, // Assuming 4 is for 'contains' or 'in' filter
                "selectedValues" => $departments
            ];
        }
        
        if (!empty($positions)) {
            $filterRules[] = [
                "fieldId" => "positions", // Try positionId instead of position
                "filterType" => 4,
                "selectedValues" => $positions
            ];
        }
        
        if (!empty($personTypes)) {
            $filterRules[] = [
                "fieldId" => "personaTypeIds", // Try personTypeId instead of personType
                "filterType" => 1,
                "selectedValues" => $personTypes
            ];
        }
        
        if (!empty($roles)) {
            $filterRules[] = [
                "fieldId" => "roles", // Try roleId instead of roles
                "filterType" => 4,
                "selectedValues" => $roles
            ];
        }
        if (!empty($organizations)) {
            $filterRules[] = [
                "fieldId" => "currentOrganization", // Try organizationId instead of organization
                "filterType" => 4,
                "selectedValues" => $organizations
            ];
        }
    
// Add filterRules for custom fields
if (isset($_POST['customFields']) && is_array($_POST['customFields'])) {
    foreach ($_POST['customFields'] as $customFieldId => $selectedValues) {
        if (!empty($selectedValues) && is_array($selectedValues)) {
            $filterRules[] = [
                "fieldId" => $customFieldId,
                "filterType" => 301, // 'in' filter for custom fields
                "selectedValues" => $selectedValues
            ];
        }
    }
}
       $postedData = [
    "groupId" => $groupId,
    "itemCount" => (int)$_POST['length'],
    "sortBy" => "name",
    "sortDirection" => $sortDirection,
    "pageNumber" => (int)(($_POST['start'] / $_POST['length']) + 1),
    "filterBody" => [
        "pageSize" => 10,
        "pageNumber" => 1,
        "searchText" => $searchText,
        "filterRules" => $filterRules
    ],
    "fields" => $custom_fields
];

//print_r(json_encode($postedData)); die;
        $dataResponse = $this->submitApiRequest("GroupPeopleList/".$tenantCode."/".$groupId, $postedData, "POST", 'dashboard');       
                
        $api_response = json_decode($dataResponse['api_response']);
        $collection   = $api_response->result;
        $totalcount   = $api_response->totalCount;
     //print_r($collection); die;
        $data = array();
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
            $nestedData['name'] = '<div class="d-flex align-items-center">';
            if($value->people->imageThumbUrl && filter_var($value->people->imageThumbUrl, FILTER_VALIDATE_URL)){
                $nestedData['name'] .= '<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->fullName.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->imageThumbUrl.'">';	
            }else{
                $nestedData['name'] .= '<i class="fas fa-user-circle mr-2" style="font-size:40px; color:#979797"></i>';
            }
            $nestedData['name'] .= '<div>'.$value->people->firstName.' '.$value->people->lastName.'</div>';
            $nestedData['email'] = '<a href="mailto:'.$value->people->email.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->people->email.'</a>';

            // Positions and Organizations (keep as is for now, as logic is complex)
           if ($value->people->peoplePosition) {
                    if (count($value->people->peoplePosition) == 1) {
                        $pos = $value->people->peoplePosition[0];
                        $nestedData['primaryorganization'] = '<div class="d-flex align-items-center" data-orgId="' . $pos->organizationId . '">';
                        if ($pos->imageThumbUrl && filter_var($pos->imageThumbUrl, FILTER_VALIDATE_URL)) {
                            $nestedData['primaryorganization'] .= '<img style="max-width:40px; flex:0 0 40px" alt="' . $pos->organizationName . '" class="rounded-circle img-fluid mr-2" src="' . $pos->imageThumbUrl . '">';
                        } else {
                            $nestedData['primaryorganization'] .= '<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
                        }
                        $nestedData['primaryorganization'] .= $pos->organizationName . '</div>';
                        $nestedData['currentposition'] = $pos->positionName;
                    } else {
                        // Positions popover
                        $nestedData['currentposition'] = $this->buildPopoverList(
                            $key,
                            $value->people->peoplePosition,
                            'Positions',
                            'Positions',
                            function($rowData, $class, $li) {
                                return '<li class="px-2 py-1 border-bottom small ' . $class . '">'
                                    . '<strong>' . $rowData->positionName . '</strong>'
                                    . '<span class="pl-2 d-block" style="color:#21086b;">' . $rowData->organizationName . '</span>'
                                    . '</li>';
                            }
                        );
                        // Organizations popover (unique organizations only)
                        $orgs = [];
                        foreach ($value->people->peoplePosition as $rowData) {
                            if (!in_array($rowData->organizationName, $orgs)) {
                                $orgs[] = $rowData->organizationName;
                            }
                        }
                        if (count($orgs) > 1) {
                            $nestedData['primaryorganization'] = $this->buildPopoverList(
                                $key,
                                array_map(function($orgName) { return (object)['organizationName' => $orgName]; }, $orgs),
                                'Organizations',
                                'Organizations',
                                function($rowData, $class, $li) {
                                    return '<li class="px-2 py-1 border-bottom small ' . $class . '">' . $rowData->organizationName . '</li>';
                                }
                            );
                        } else {
                            // Only one unique organization, show as icon + name
                            $orgObj = $value->people->peoplePosition[0];
                            $nestedData['primaryorganization'] = '<div class="d-flex align-items-center">';
                            if ($orgObj->imageThumbUrl && filter_var($orgObj->imageThumbUrl, FILTER_VALIDATE_URL)) {
                                $nestedData['primaryorganization'] .= '<img style="max-width:40px; flex:0 0 40px" alt="' . $orgObj->organizationName . '" class="rounded-circle img-fluid mr-2" src="' . $orgObj->imageThumbUrl . '">';
                            } else {
                                $nestedData['primaryorganization'] .= '<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
                            }
                            $nestedData['primaryorganization'] .= $orgObj->organizationName . '</div>';
                        }
                    }
                } else {
                    $nestedData['primaryorganization'] = '--';
                    $nestedData['currentposition'] = '--';
                }
            $nestedData['primaryorganization'] = '<div class="d-flex align-items-center">';
            if($value->people->organization->imageThumbUrl && filter_var($value->people->organization->imageThumbUrl, FILTER_VALIDATE_URL)){
                $nestedData['primaryorganization'] .= '<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->organization->imageThumbUrl.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->organization->imageThumbUrl.'">';	
            }else{
                $nestedData['primaryorganization'] .= '<i class="fas fa-landmark mr-2" style="font-size:30px; color:#979797"></i>';
            }
            $nestedData['primaryorganization'] .= '<div>'.$value->people->organization->name.'</div>';

            // Department (keep as is for now)
           if ($value->people->peopleDepartment) {
                if (count($value->people->peopleDepartment) == 1) {
                    $dept = $value->people->peopleDepartment[0];
                    $nestedData['currentdepartment'] = '<div class="d-flex align-items-center">';
                    if ($dept->imageThumbUrl && filter_var($dept->imageThumbUrl, FILTER_VALIDATE_URL)) {
                        $nestedData['currentdepartment'] .= '<img style="max-width:40px; flex:0 0 40px" alt="' . $dept->organizationName . '" class="rounded-circle img-fluid mr-2" src="' . $dept->imageThumbUrl . '">';
                    } else {
                        $nestedData['currentdepartment'] .= '<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
                    }
                    $nestedData['currentdepartment'] .= $dept->organizationName . '</div>';
                    $nestedData['currentdepartment'] = $dept->departmentName;
                } else {
                    $nestedData['currentdepartment'] = $this->buildPopoverList(
                        $key,
                        $value->people->peopleDepartment,
                        'Departments',
                        'Departments',
                        function($rowData, $class, $li) {
                            return '<li class="px-2 py-1 border-bottom small ' . $class . '">'
                                . '<strong>' . $rowData->departmentName . '</strong>'
                                . '<span class="pl-2 d-block" style="color:#21086b;">' . $rowData->organizationName . '</span>'
                                . '</li>';
                        }
                    );
                }
            } else {
                $nestedData['currentdepartment'] = '--';
            }

            // Terms (keep as is for now)
            if (!empty($value->people->terms)) {
                $terms = $value->people->terms;
                $termCount = count($terms);
                $linkText = ($termCount === 1) ? $terms[0]->electionTermName : $termCount . ' Terms';
                $nestedData['term'] = $this->buildPopoverList(
                    $key,
                    $terms,
                    'Terms',
                    'Terms',
                    function($term, $class, $li) {
                        $termName = $term->electionTermName ?? '';
                        $orgName = $term->position->organizationName ?? '';
                        $positionName = $term->position->positionName ?? '';
                        return '<li class="px-2 py-1 border-bottom small ' . $class . '">'
                            . '<strong>' . $termName . '</strong>'
                            . '<div><em style="color:#21086b;">' . $orgName . '</em></div>'
                            . '<div>' . $positionName . '</div>'
                            . '</li>';
                    },
                    'class_' . $key // dropdownClass
                );
                // Replace the link text in the dropdown anchor
                $nestedData['term'] = preg_replace(
                    '/>\d+ Terms</',
                    '>' . $linkText . '<',
                    $nestedData['term'],
                    1
                );
            } else {
                $nestedData['term'] = '--';
            }

            $nestedData['status'] =  $value->people->status;
            $nestedData['modifieddate'] = $this->formatDateField($value->people->modifiedDate);
            $nestedData['createddate'] = $this->formatDateField($value->people->createdDate);
            $nestedData['lastlogin'] = $this->formatDateField($value->people->lastLogin);
            $nestedData['totaltimeworked'] = $this->formatMonthsToYearsAndMonths($value->people->totalTimeWorked);          
            $nestedData['roles'] = $this->buildPopoverColumn($key, $value->people->roles ?? [], 'Roles', 'name');
            $nestedData['tags'] = $this->buildPopoverColumn($key, $value->people->tags ?? [], 'Tags', 'tagName');
            $nestedData['persontype'] = $this->buildPopoverColumn($key, $value->people->personTypes ?? [], 'Person Types', 'name');
            $nestedData['age'] = $this->getCustomFieldValue($value->people->customFields ?? [], 'age');
//$nestedData['birthdate'] = '';
            $nestedData['action'] = '';
            $nestedData['currentorganizations'] = '';
     $regions = [];
if (!empty($value->people->terms) && is_array($value->people->terms)) {
    foreach ($value->people->terms as $term) {
        if (!empty($term->regionName)) {
            $regions[] = (object)[
                'regionName' => $term->regionName,
                'organizationName' => $term->position->organizationName ?? '',
                'electionTermName' => $term->electionTermName ?? ''
            ];
        }
    }
}


$regions = array_values($regions);

if (count($regions) > 0) {
    $customLinkText = (count($regions) === 1) ? htmlspecialchars($regions[0]->regionName) : null;
    $nestedData['region'] = $this->buildPopoverList(
        $key,
        $regions,
        'Regions',
        'Regions',
        function($rowData, $class, $li) {
            return '<li class="px-2 py-1 border-bottom small ' . $class . '">'
                . htmlspecialchars($rowData->regionName)
                . '<br><span class="d-block" style="color:#2176d2;">'
                . htmlspecialchars($rowData->organizationName)
                . '</span>'
                . '<span class="d-block">'
                . 'Term: ' . htmlspecialchars($rowData->electionTermName)
                . '</span>'
                . '</li>';
        },
        '',
        $customLinkText // Pass custom link text for single region
    );
} else {
    $nestedData['region'] = '--';
}

        
            $expiration = $this->formatDateField($value->people->invitationExpirationDate);          
            $userStatusMap = [
                0 => 'Not Invited',
                1 => 'Invite Sent',
                2 => 'Invite Resent',
                3 => 'Invitation Expired',
                4 => 'Login Created'
            ];
            $status = $userStatusMap[$value->people->userStatus] ?? 'Unknown';
            if (in_array($value->people->userStatus, [1, 2, 3]) && $expiration) {
                $suffix = ($value->people->userStatus == 3) ? ' (Expired on ' : ' (Expires on ';
                $status .= $suffix . $expiration . ')';
            }
            $nestedData['invitationstatus'] = $status;
            $nestedData['phone'] = $this->formatPhoneNumber($value->people->primaryPhoneNumber->value ?? '');
            // Custom fields (badges)
            $customFields = $value->people->customFields ?? [];
if (!empty($customFields) && is_array($customFields)) {
    $fieldCounts = [];
    // First, count occurrences for duplicate field names
    foreach ($customFields as $field) {
        $fname = $field->fieldName ?? $field->title ?? $field->name ?? '';
        if ($fname) {
            if (!isset($fieldCounts[$fname])) {
                $fieldCounts[$fname] = 1;
            } else {
                $fieldCounts[$fname]++;
            }
        }
    }
    // Now, add fields to $nestedData with unique keys and handle controlTypeId == 9
    $fieldIndex = [];
    //print_r($customFields); 
    foreach ($customFields as $field) {
        $fname = $field->fieldName ?? $field->title ?? $field->name ?? '';
        $fvalue = $field->selectedValue ?? $field->value ?? '';
        if ($fname) {
            if (!isset($fieldIndex[$fname])) {
                $fieldIndex[$fname] = 1;
            } else {
                $fieldIndex[$fname]++;
            }
        $keyBase = preg_replace('/\s+/', '', strtolower($fname));
$keyName = ($fieldCounts[$fname] > 1) ? $keyBase . '_' . $fieldIndex[$fname] : $keyBase;

            // Special handling for address fields (controlTypeId == 9)
            if (isset($field->controlTypeId) && $field->controlTypeId == 9 && !empty($fvalue)) {
                $address = is_string($fvalue) ? json_decode($fvalue, true) : $fvalue;
                
                if (is_array($address)) {
                    $parts = [];
                    if (!empty($address['address'])) $parts[] = $address['address'];
                    if (!empty($address['addressLine2'])) $parts[] = $address['addressLine2'];
                    if (!empty($address['city'])) $parts[] = $address['city'];
                    if (!empty($address['state'])) $parts[] = $address['state'];
                    if (!empty($address['zipCode'])) $parts[] = $address['zipCode'];
                    $formatted = implode(', ', array_filter($parts));       
                    $nestedData[$keyName] = htmlspecialchars($formatted ?: '--');
                } else {
                    $nestedData[$keyName] = '--';
                }
            } elseif(isset($field->controlTypeId) && $field->controlTypeId == 11 && !empty($fvalue)){
                    $nestedData[$keyName] = $this->formatPhoneNumber($fvalue);
            }
            elseif(isset($field->controlTypeId) && $field->controlTypeId == 1 && !empty($fvalue)){
                    $nestedData[$keyName] = $this->formatDateField($fvalue);
            }
            else {
                $nestedData[$keyName] = htmlspecialchars($fvalue);
            }
        }
    }
}
 $allColumnKeys = [];
    foreach ($visible_columns as $col_json) {
        $col = json_decode(stripslashes($col_json), true);
        if (!empty($col['colName'])) {
            $allColumnKeys[] = preg_replace('/\s+/', '', strtolower($col['colName']));
        }
    }
   
    // 2. Ensure every key exists in $nestedData
    foreach ($allColumnKeys as $colKey) {
        if (!isset($nestedData[$colKey])) {
            $nestedData[$colKey] = '--';
        }
    }
       $data[] = $nestedData;
        }
        $draw = $_POST['draw'];
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
         $tenantCode = $options['dashboard_tenant_code'];
        $front_pages = $options['front_pages'];
        //$postedData = $this->_preparePeopleData();        
        $viewMode = $_POST['viewMode'];
       $sortDirection = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : 'asc';
	   $searchText = isset($_POST["columns"][$_POST['titleColumn']]["search"]["value"]) ? $_POST["columns"][$_POST['titleColumn']]["search"]["value"] : '';

        // Get filter parameters from POST
        $organizationTypes = isset($_POST['organizationTypes']) && is_array($_POST['organizationTypes']) ? $_POST['organizationTypes'] : [];
        $statuses = isset($_POST['statuses']) && is_array($_POST['statuses']) ? $_POST['statuses'] : [];
        $locations = isset($_POST['locations']) && is_array($_POST['locations']) ? $_POST['locations'] : [];
        $organizationTags = isset($_POST['organizationTags']) && is_array($_POST['organizationTags']) ? $_POST['organizationTags'] : [];
        $customFields = isset($_POST['customFields']) && is_array($_POST['customFields']) ? $_POST['customFields'] : [];

        // Build filter rules dynamically
        $filterRules = [];
        
        // Always add Active status as default if no statuses provided
        if (empty($statuses)) {
            $filterRules[] = [
                "fieldId" => "status",
                "filterType" => 4,
                "selectedValues" => ["Active"]
            ];
        } else {
            $filterRules[] = [
                "fieldId" => "status",
                "filterType" => 4,
                "selectedValues" => $statuses
            ];
        }

        if (!empty($organizationTypes)) {
            $filterRules[] = [
                "fieldId" => "organizationType",
                "filterType" => 4,
                "selectedValues" => $organizationTypes
            ];
        }

        if (!empty($locations)) {
            $filterRules[] = [
                "fieldId" => "locations",
                "filterType" => 4,
                "selectedValues" => $locations
            ];
        }

        if (!empty($organizationTags)) {
            $filterRules[] = [
                "fieldId" => "tags",
                "filterType" => 1,
                "selectedValues" => $organizationTags
            ];
        }

        // Add custom field filters
        foreach ($customFields as $customFieldId => $selectedValues) {
            if (!empty($selectedValues) && is_array($selectedValues)) {
                $filterRules[] = [
                    "fieldId" => $customFieldId,
                    "filterType" => 4,
                    "selectedValues" => $selectedValues
                ];
            }
        }

        $postedData = [
            "itemCount" => (int)$_POST['length'],
            "pageNumber" => (int)(($_POST['start'] / $_POST['length']) + 1),
            "sortBy" => "name",
            "sortDirection" => $sortDirection,
            "filterBody" => [
                "filterRules" => $filterRules,
                "searchText" => $searchText,
                "search" => [],
                "selectedDate" => date('Y-m-d'),
                "IsShowPastOrg" => false,
                "pageSize" => (int)$_POST['length'],
                "pageNumber" => (int)(($_POST['start'] / $_POST['length']) + 1),
                "isFlatView" => true,
                "allOrganizationPermission" => [
                    "viewClientOrganization" => true,
                    "viewAllMemberOrganizations" => true,
                    "viewOwnOrganization" => true,
                    "viewChildOrganization" => true
                ]
            ]
        ];
        //xprint_r(json_encode($postedData)); die;
        $dataResponse = $this->submitApiRequest("OrganizationPagingListWithCF/".$tenantCode."/", $postedData, "POST", 'dashboard'); 
        $api_response = json_decode($dataResponse['api_response']);
        $collection   = $api_response->result;
        $totalcount   = $api_response->totalCount;
    //print_r(json_encode($collection)); die;
		/*if($_POST['countResult']=='true'){
			echo json_encode($totalcount);
        	wp_die();
			return;		
		}*/
        $isLoggedIn = is_user_logged_in();
        $loginUrl   = wp_login_url( home_url( $_SERVER['REQUEST_URI'] ) );
        // Admin-configurable guest hidden fields (default: phone + email)
        $guest_hidden_fields = array_key_exists('guest_hidden_fields', $options['organization_settings'] ?? [])
            ? ($options['organization_settings']['guest_hidden_fields'] ?? [])
            : ['phoneNumbers', 'primaryEmail'];

        // Build a dynamic map: custom fieldId => ['colClass', 'controlTypeId'] from saved columns.
        // A column is a custom field when its fieldId differs from its colName (system fields have fieldId === colName).
        $cfFieldIdMap = [];
        foreach (ORGANIZATION_COLS as $col_json) {
            $col = json_decode(stripslashes($col_json), true);
            if (empty($col['fieldId']) || empty($col['colName'])) continue;
            if (strcasecmp($col['fieldId'], $col['colName']) === 0) continue; // skip system fields
            $colClass = preg_replace('/\s+/', '', strtolower($col['colName']));
            $cfFieldIdMap[strtolower($col['fieldId'])] = [
                'colClass'      => $colClass,
                'colName'       => $col['colName'],
                'controlTypeId' => isset($col['controlTypeId']) ? (int)$col['controlTypeId'] : null,
            ];
        }

        $request = $_GET;
        $data    = array();
		if($viewMode=='Grid'){
			// For grid view, strip configured fields for non-logged-in users (server-side security)
			if ( ! $isLoggedIn ) {
				foreach ( $collection as $item ) {
					foreach ( $guest_hidden_fields as $fieldName ) {
						if ( ! isset( $item->$fieldName ) ) continue;
						// Clear arrays to empty array, scalars to empty string
						$item->$fieldName = is_array( $item->$fieldName ) ? [] : '';
						// When primaryEmail is hidden, also clear secondary emails
						if ( $fieldName === 'primaryEmail' ) {
							$item->secondaryEmails = [];
						}
					}
				}
			}
		  $response = [
			  'count' => $totalcount,
			  'data' => $collection,
			  'isLoggedIn' => $isLoggedIn,
			  'loginUrl'   => $loginUrl,
		  ];
		  echo json_encode($response);
		  wp_die();
		}
        foreach ($collection as $key => $value) { 
            $nestedData = array();
            $locationPopOver      = '';
           if(count($value->locations)) {
                $locationPopOver  = $this->_popOverLocationData($key, $value->locations, ['isSession' => false, 'useFieldName' => true ]);   
			}
            $locationCount = 0 ;
            foreach($value->locations as $key => $location){

                if($location->fieldName){
                    $locationCount = $locationCount+1;
                }
            }
			$nestedData['name']='<div class="d-flex align-items-center">';
			$orgImgSrc = ($value->imageThumbUrl && filter_var($value->imageThumbUrl, FILTER_VALIDATE_URL)) ? $value->imageThumbUrl : ENGAGIFII_ASSETS_URL . '/images/org-list-grey.png';
			$nestedData['name'].='<img style="max-width:40px; flex:0 0 40px" alt="'.esc_attr($value->name).'" class="rounded-circle img-fluid mr-2" src="'.esc_url($orgImgSrc).'">';
            $nestedData['name'] .= '<div><a class="text-nowrap" href="#" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->name.'</a></div>';
            $nestedData['status'] = ($value->status === 'Active') 
                ? '<span style="color: #28a745; font-weight: 600;">' . $value->status . '</span>' 
                : $value->status;
            $nestedData['totalmembers'] = $value->totalMembers."/".$value->activeMembers;	
            //$nestedData['activemembers'] = $value->activeMembers;	
			//$nestedData['Location']= $value->locationInfo[0]->locationValue ? $value->locationInfo->locationValue : 'N/A';
           if ((!empty($value->locations) && count($value->locations) > 0)) {             
                $nestedData['locations'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$locationCount.'</span></div>'.$locationPopOver.'</div>';
            } else {
                $nestedData['locations'] = '<div class="dropdown"><div class=" instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter: grayscale(1);"><span style="visibility: hidden;" class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"></span></div></div>';
            }
         
            if ( $isLoggedIn ) {
                $nestedData['phonenumbers'] = $this->formatPhoneNumber($value->phoneNumbers[0]->value ?? '');
                $nestedData['primaryemail'] = $value->primaryEmail 
                    ? '<a href="mailto:' . $value->primaryEmail . '">' . $value->primaryEmail . '</a>' 
                    : ((isset($value->secondaryEmails) && count($value->secondaryEmails) > 0 && isset($value->secondaryEmails[0]->value)) 
                        ? '<a href="mailto:' . $value->secondaryEmails[0]->value . '">' . $value->secondaryEmails[0]->value . '</a>' 
                        : '--');
            } else {
                $nestedData['phonenumbers'] = $this->formatPhoneNumber($value->phoneNumbers[0]->value ?? '');
                $nestedData['primaryemail'] = $value->primaryEmail 
                    ? '<a href="mailto:' . $value->primaryEmail . '">' . $value->primaryEmail . '</a>' 
                    : ((isset($value->secondaryEmails) && count($value->secondaryEmails) > 0 && isset($value->secondaryEmails[0]->value)) 
                        ? '<a href="mailto:' . $value->secondaryEmails[0]->value . '">' . $value->secondaryEmails[0]->value . '</a>' 
                        : '--');
            }
            $nestedData['organizationtype'] = $value->organizationType ? $value->organizationType : '';
           // $nestedData['organizationTags'] = '';
            $nestedData['website'] = !empty($value->website) 
    ? '<a href="' . (strpos($value->website, 'http') === 0 ? $value->website : 'https://' . $value->website) . '" target="_blank" rel="noopener noreferrer" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">' . $value->website . '</a>' 
    : '--';
            $nestedData['modifiedon'] = $this->formatDateField($value->modifiedOn);
            $nestedData['createdon'] = $this->formatDateField($value->createdOn);
            // $organizationTags = $value->organizationTags;
             $nestedData['organizationtags'] = $this->buildPopoverColumn($key, $value->organizationTags ?? [], 'Tags', 'tagName');

            // Dynamic custom fields — resolved via admin-saved column map (no hardcoded fieldIds)
            foreach ($value->customFields ?? [] as $cf) {
                if (empty($cf->fieldId)) continue;
                $cfIdLower = strtolower($cf->fieldId);
                if (!isset($cfFieldIdMap[$cfIdLower])) continue;
                $meta     = $cfFieldIdMap[$cfIdLower];
                $colClass = $meta['colClass'];
                $cfValue  = $cf->fieldValue ?? null;
                if (empty($cfValue)) {
                    continue; // leave empty; missing keys filled below
                }
                $ctId = $meta['controlTypeId'];
                if ($ctId === 11) {
                    $nestedData[$colClass] = $this->formatPhoneNumber($cfValue);
                } elseif ($ctId === 1) {
                    $nestedData[$colClass] = $this->formatDateField($cfValue);
                } elseif (filter_var($cfValue, FILTER_VALIDATE_URL)) {
                    if (preg_match('/\.(png|jpg|jpeg|gif|webp|svg)(\?.*)?$/i', $cfValue)) {
                        $nestedData[$colClass] = '<img src="' . esc_url($cfValue) . '" alt="' . esc_attr($meta['colName']) . '" style="max-width:70px;max-height:45px;object-fit:contain;" />';
                    } else {
                        $nestedData[$colClass] = '<a href="' . esc_url($cfValue) . '" target="_blank" rel="noopener noreferrer">' . esc_html($cfValue) . '</a>';
                    }
                } elseif (filter_var($cfValue, FILTER_VALIDATE_EMAIL)) {
                    $nestedData[$colClass] = '<a href="mailto:' . esc_attr($cfValue) . '">' . esc_html($cfValue) . '</a>';
                } elseif (preg_match('/^\d{10}$/', preg_replace('/\D/', '', $cfValue)) && strlen(preg_replace('/\D/', '', $cfValue)) === 10) {
                    $nestedData[$colClass] = $this->formatPhoneNumber($cfValue);
                } else {
                    $nestedData[$colClass] = esc_html($cfValue);
                }
            }

            // Fill any custom-field columns not present in the API response with '--'
            foreach ($cfFieldIdMap as $meta) {
                if (!isset($nestedData[$meta['colClass']])) {
                    $nestedData[$meta['colClass']] = '--';
                }
            }

            // Apply guest field masking for list view (admin-configurable)
            if ( ! $isLoggedIn && ! empty( $guest_hidden_fields ) ) {
                $maskStyle  = 'filter:blur(3.5px);user-select:none;letter-spacing:1px;';
                $lockIcon   = '<i class="fas fa-lock" style="font-size:0.8em;opacity:0.6;"></i> ';
                $maskedPhone   = '<a href="' . esc_url($loginUrl) . '" title="Login to view" style="text-decoration:none;color:inherit;">' . $lockIcon . '<span style="' . $maskStyle . '">(•••)&nbsp;•••-••••</span></a>';
                $maskedEmail   = '<a href="' . esc_url($loginUrl) . '" title="Login to view" style="text-decoration:none;color:inherit;">' . $lockIcon . '<span style="' . $maskStyle . '">••••@•••••.•••</span></a>';
                $maskedGeneric = '<a href="' . esc_url($loginUrl) . '" title="Login to view" style="text-decoration:none;color:inherit;">' . $lockIcon . '<span style="' . $maskStyle . '">• • • • •</span></a>';
                foreach ( $guest_hidden_fields as $fieldName ) {
                    $colClass = preg_replace('/\s+/', '', strtolower($fieldName));
                    if ( isset( $nestedData[ $colClass ] ) ) {
                        $nestedData[ $colClass ] = ( $fieldName === 'phoneNumbers' ) ? $maskedPhone
                            : ( ( $fieldName === 'primaryEmail' ) ? $maskedEmail : $maskedGeneric );
                    }
                }
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
       //print_r(($json_data));
        echo json_encode($json_data);
        wp_die();
    }
    public function formatMonthsToYearsAndMonths($totalMonths) {
    $years = floor($totalMonths / 12);
    $months = $totalMonths % 12;

    $yearText = $years > 0 ? $years . ' yr' . ($years > 1 ? 's' : '') : '';
    $monthText = $months > 0 ? $months . ' mo' . ($months > 1 ? 's' : '') : '';

    if ($yearText && $monthText) {
        return $yearText . ' and ' . $monthText;
    } elseif ($yearText) {
        return $yearText;
    } elseif ($monthText) {
        return $monthText;
    } else {
        return '--';
    }
}
// Helper for popover columns (roles, tags, person types)
    public function buildPopoverColumn($key, $items, $label, $field) {
        $count = is_array($items) ? count($items) : 0;
        $result = [];
        if ($count > 1) {
            $popover = $this->_popOverGenericData($key, $items, $label, $field);
            $remaining = $count - 1;
            $first = htmlspecialchars($items[0]->$field);
            $result[] = '<div class="dropdown pr-4 text-left">
                <span class="d-inline-block pr-2">' . $first . '</span>
                <span data-toggle="dropdown" style="right:0; top:0; bottom:0"
                      class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_' . $key . '"
                      data-placement="left"
                      data-containerid="' . $key . '"
                      id="' . $key . '"> +' . $remaining . '</span>' . $popover . '
            </div>';
        } elseif ($count === 1) {
            $result[] = htmlspecialchars($items[0]->$field);
        }
        return $result;
    }

    // Helper for extracting custom field value
    public function getCustomFieldValue($customFields, $field) {
        if (!empty($customFields) && is_array($customFields)) {
            foreach ($customFields as $customField) {
                if (
                    (isset($customField->title) && strtolower($customField->title) === strtolower($field)) ||
                    (isset($customField->fieldName) && strtolower($customField->fieldName) === strtolower($field))
                ) {
                    return $customField->selectedValue ?? '';
                }
            }
        }
        return '';
    }

// Helper to format date fields
    public function formatDateField($dateValue) {
    return !empty($dateValue) ? date('M j, Y', strtotime($dateValue)) : '';
    }

// Helper to build a popover list for items
public function buildPopoverList($key, $items, $label, $countLabel, $itemCallback, $dropdownClass = '', $customLinkText = null) {
    $count = is_array($items) ? count($items) : 0;
    if ($count === 0) {
        return '--';
    }
    $classPopover = dd_header($label . ' (' . $count . ')');
    $subItems = '';
    $li = 1;
    foreach ($items as $item) {
        $class = ($li % 2 == 1) ? 'bg-light' : '';
        $subItems .= $itemCallback($item, $class, $li);
        $li++;
    }
    $classPopover .= $subItems . '<span class="px-2 py-1 text-center small d-none">No results found!</span></div>';

    // Use custom link text if provided, otherwise default to count/countLabel
    $linkText = $customLinkText ?? ($count . ' ' . $countLabel);

    return '<div class="dropdown">'
        . '<a href="" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" '
        . 'data-offset="60,0" data-toggle="dropdown" class="' . $dropdownClass . ' class_' . $key . '" data-placement="left">'
        . $linkText . '</a>'
        . $classPopover . '</div>';
}

// Helper to format phone numbers
  public function formatPhoneNumber($rawPhone) {
    if (!empty($rawPhone)) {
        // Extract 10 digits and extension (supports ext, x, extension)
        $digits = preg_replace('/\D/', '', $rawPhone);
        $digits = substr($digits, 0, 10);
        $ext = '';
        if (preg_match('/(?:ext|x|extension)\s*\.?\s*(\d+)/i', $rawPhone, $matches)) {
            $ext = $matches[1];
        }
        if (strlen($digits) === 10) {
            $formattedPhone = preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $digits);
            if ($ext) {
                $formattedPhone .= ' ext ' . $ext;
            }
            $tel = 'tel:' . $digits . ($ext ? ',,' . $ext : '');
            return '<a href="' . $tel . '" style="text-decoration: none;">' . $formattedPhone . '</a>';
        }
    }
    return '--';
}
//events filters
public function groupMemberFilters(){
	$postData=array();
	$htmlArray = array();
    $options = get_option('ebt_api_settings');
	//$events_type_visible_column_list = $options['events_type_visible_column_list']??array();
	$events_type_visible_column_list=[];
	if (!empty(GROUP_MEMBERS_COLS) && isArrayOfJsonStrings(GROUP_MEMBERS_COLS)) {
		  $events_type_visible_column_list = extractColNames(GROUP_MEMBERS_COLS);
	}
	  $filterParams = $_POST['filterParams'];
       $groupId = $_POST['groupId'];
      // print_r($filterParams); die;
	  $apiUrl='';
	  $date = date('Y-m-d');
	  foreach ($filterParams as $keys => $values) {
		  if($values =='currentDepartment'){
			$apiUrl='list/people/department/'.$date.'/'.$groupId;
             $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
		  } 
           elseif($values =='currentPosition'){
			$apiUrl='list/people/position/'.$date.'/'.$groupId; 
             $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
		  } 
        elseif($values =='personType'){
		 	$apiUrl='list/people/persontype/'.$date.'/'.$groupId; 
             $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
		   } 
           elseif($values =='organization'){
			$apiUrl='list/people/organization/'.$date.'/'.$groupId; 
             $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
		  }
        //    //print_r($apiUrl); 
		  //$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
         // print_r($response); 
		  if($response['api_response']){
			$response = json_decode($response['api_response'], true);
			if($response){
			  foreach ($response as $key => $value) {
				 if($values =='currentDepartment'){
					  $html[$values].='<li class="d-flex align-items-start"><input id="tag_'.$key.'" class="mr-2 mt-1" type="checkbox" name="memberDepartments[]" value="'.$value['id'].'"> <label class="" for="tag_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';	
				  } 
                  elseif($values =='currentPosition'){
				  $html[$values].='<li class="d-flex align-items-start"><input id="position_'.$key.'" class="mr-2 mt-1" type="checkbox" name="eventsPositions[]" value="'.$value['id'].'"> <label class="" for="position_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';	
				 } 
                elseif($values =='personType'){
				 	  $html[$values].='<li class="d-flex align-items-start"><input id="persontype_'.$key.'" class="mr-2 mt-1" type="checkbox" name="eventsPersonTypes[]" value="'.$value['id'].'"> <label class="" for="persontype_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';	
				   } 
                   elseif($values =='organization'){
				 	  $html[$values].='<li class="d-flex align-items-start"><input id="organization_'.$key.'" class="mr-2 mt-1" type="checkbox" name="eventsOrganizations[]" value="'.$value['id'].'"> <label class="" for="organization_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';	
				  }
				}
			  }else{
				$html[$values] ='<h6 class="text-center mt-3">data not found</h6>';
			  }
	  		} else {
				$html[$values]='<h6 class="text-center mt-3">data not found</h6>';	
			}
			$htmlArray=$html;
	  }
		echo json_encode($htmlArray);
        wp_die();
	
}
public function getCustomFieldFilterData() {
    //print_r("test"); die;// Debugging line, can be removed later
    // Check if required parameters are provided
    if (!isset($_POST['fieldConfigurationId'], $_POST['selectedDate'], $_POST['groupId'])) {
        wp_send_json_error(['message' => 'Missing required parameters.']);
        return;
    }

    $fieldConfigurationId = sanitize_text_field($_POST['fieldConfigurationId']);
    $selectedDate = sanitize_text_field($_POST['selectedDate']);
    $groupId = sanitize_text_field($_POST['groupId']);
//print_r($fieldConfigurationId); // Debugging line, can be removed later

    // Call the API
    $apiEndpoint = "GetCustomFieldFilterData/{$fieldConfigurationId}/{$selectedDate}/{$groupId}";
//print_r($apiEndpoint); // Debugging line, can be removed later
    $response = $this->submitApiRequest($apiEndpoint, [], 'GET', 'dashboard');
//print_r($response); // Debugging line, can be removed later

    if (isset($response['api_response'])) {
        wp_send_json_success(json_decode($response['api_response']));
    } else {
        wp_send_json_error(['message' => 'Failed to fetch custom field filter data.']);
    }
}

public function groupCountFilterData() {
        $selectedDate = sanitize_text_field($_POST['selectedDate']);
        $groupId = sanitize_text_field($_POST['groupId']);

    $postedData = $this->_groupPostCountData();
     $apiEndpoint = "GroupPeopleListCount/meams/{$groupId}";
    $dataResponse = $this->submitApiRequest($apiEndpoint, $postedData, "POST", 'dashboard');
    // print_r($dataResponse); die;
    header("Content-Type: application/json");
    echo json_encode($dataResponse);
    wp_die();
}

private function _groupPostCountData() {
    $searchValue = '';
    if (!empty($_POST['search']['value'])) {
        $searchValue = $_POST['search']['value'];
    }

    $start = isset($_POST['start']) && is_numeric($_POST['start']) ? (int) $_POST['start'] : 0;
    $length = isset($_POST['length']) && is_numeric($_POST['length']) && (int) $_POST['length'] > 0 ? (int) $_POST['length'] : 10;
    $startPageNum = (int) (($start / $length) + 1);

    $columnsData = [];
    foreach ($_POST['columns'] as $key => $value) {
        if ($value['orderable'] == "true") {
            $columnsData[$value['data']] = $value['data'];
        }
    }

    $sortBy = '';
    if (isset($columnsData['name'])) {
        $sortBy = 'name';
    }

    $departments = isset($_POST['departments']) ? $_POST['departments'] : [];
    $positions = isset($_POST['positions']) ? $_POST['positions'] : [];
    $personTypes = isset($_POST['personTypes']) ? $_POST['personTypes'] : [];
    $roles = isset($_POST['roles']) ? $_POST['roles'] : [];
    $organizations = isset($_POST['organizations']) ? $_POST['organizations'] : [];
    $customFields = isset($_POST['customFields']) ? $_POST['customFields'] : [];

    $filterRules = [];

    if (!empty($departments)) {
        $filterRules[] = [
            'fieldId' => 'departments',
            'filterType' => 4,
            'selectedValues' => $departments
        ];
    }

    if (!empty($positions)) {
        $filterRules[] = [
            'fieldId' => 'positions',
            'filterType' => 4,
            'selectedValues' => $positions
        ];
    }

    if (!empty($personTypes)) {
        $filterRules[] = [
            'fieldId' => 'personaTypeIds',
            'filterType' => 1,
            'selectedValues' => $personTypes
        ];
    }

    if (!empty($roles)) {
        $filterRules[] = [
            'fieldId' => 'roles',
            'filterType' => 4,
            'selectedValues' => $roles
        ];
    }

    if (!empty($organizations)) {
        $filterRules[] = [
            'fieldId' => 'currentOrganization',
            'filterType' => 4,
            'selectedValues' => $organizations
        ];
    }

    foreach ($customFields as $customFieldId => $selectedValues) {
        if (!empty($selectedValues)) {
            $filterRules[] = [
                'fieldId' => $customFieldId,
                'filterType' => 301,
                'selectedValues' => $selectedValues
            ];
        }
    }

    $postData = [
        'groupId' => $_POST['groupId'],
        'searchText' => $searchValue,
        'sortBy' => $sortBy,
        'pageNumber' => $startPageNum,
        'pageSize' => $length,
        'filterRules' => $filterRules
    ];

    return $postData;
}

//organization filters
public function organizationFilters(){
	$postData=array();
	$htmlArray = array();
    $options = get_option('ebt_api_settings');
    $tenantCode = $options['dashboard_tenant_code'];
    
	$events_type_visible_column_list=[];
	if (!empty(ORGANIZATION_COLS) && isArrayOfJsonStrings(ORGANIZATION_COLS)) {
		  $events_type_visible_column_list = extractColNames(ORGANIZATION_COLS);
	}
    
	  $filterParams = isset($_POST['filterParams']) ? $_POST['filterParams'] : $events_type_visible_column_list;
	  $apiUrl='';
	  $date = date('Y-m-d');
	  foreach ($events_type_visible_column_list as $keys => $values) {
		  if($values =='OrganizationType'){
		     $apiUrl = "OrganizationTypeFilterList/".$tenantCode;
			 $dataResponse = $this->submitApiRequest($apiUrl,array(),"GET",'dashboard');
			 if (isset($dataResponse['api_response'])) {
                 $decoded = json_decode($dataResponse['api_response']);
                 $htmlArray['OrganizationType'] = $decoded;
             } else {
                 $htmlArray['OrganizationType'] = [];
             }
		  } 
        elseif($values =='OrganizationTags'){
            $apiUrl = "OrganizationTagFilterList/".$tenantCode;
			$dataResponse = $this->submitApiRequest($apiUrl,array(),"GET",'dashboard');
			if (isset($dataResponse['api_response'])) {
                $decoded = json_decode($dataResponse['api_response']);
                $htmlArray['OrganizationTags'] = $decoded;
            } else {
                $htmlArray['OrganizationTags'] = [];
            }
        }
        elseif($values =='Locations'){
            $apiUrl = "OrganizationLocationFilterList/".$tenantCode;
			$dataResponse = $this->submitApiRequest($apiUrl,array(),"GET",'dashboard');
			if (isset($dataResponse['api_response'])) {
                $decoded = json_decode($dataResponse['api_response']);
                $htmlArray['Locations'] = $decoded;
            } else {
                $htmlArray['Locations'] = [];
            }
        }
        elseif($values =='Status'){
            $apiUrl = "OrganizationStatusFilterList/".$tenantCode;
			$dataResponse = $this->submitApiRequest($apiUrl,array(),"GET",'dashboard');
			if (isset($dataResponse['api_response'])) {
                $decoded = json_decode($dataResponse['api_response']);
                $htmlArray['Status'] = $decoded;
            } else {
                $htmlArray['Status'] = [];
            }
        }
	  }
    
	echo json_encode($htmlArray);
    wp_die();
}

public function organizationCountFilterData() {
    $options = get_option('ebt_api_settings');
    $tenantCode = $options['dashboard_tenant_code'];

    $postedData = $this->_organizationPostCountData();
    $apiEndpoint = "PublicFilteredRecordCount/".$tenantCode;
    $dataResponse = $this->submitApiRequest($apiEndpoint, $postedData, "POST", 'dashboard');
    // print_r($dataResponse); die;
    header("Content-Type: application/json");
    echo json_encode($dataResponse);
    wp_die();
}

private function _organizationPostCountData() {
    $searchValue = '';
    if (!empty($_POST['search']['value'])) {
        $searchValue = $_POST['search']['value'];
    }

    $start = isset($_POST['start']) && is_numeric($_POST['start']) ? (int) $_POST['start'] : 0;
    $length = isset($_POST['length']) && is_numeric($_POST['length']) && (int) $_POST['length'] > 0 ? (int) $_POST['length'] : 10;
    $startPageNum = (int) (($start / $length) + 1);

    $columnsData = [];
    if (isset($_POST['columns']) && is_array($_POST['columns'])) {
        foreach ($_POST['columns'] as $key => $value) {
            if (isset($value['orderable']) && $value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }
    }

    $sortBy = '';
    if (isset($columnsData['name'])) {
        $sortBy = 'name';
    }

    $organizationTypes = isset($_POST['organizationTypes']) ? $_POST['organizationTypes'] : [];
    $statuses = isset($_POST['statuses']) ? $_POST['statuses'] : [];
    $locations = isset($_POST['locations']) ? $_POST['locations'] : [];
    $organizationTags = isset($_POST['organizationTags']) ? $_POST['organizationTags'] : [];
    $customFields = isset($_POST['customFields']) ? $_POST['customFields'] : [];

    $filterRules = [];

    if (!empty($organizationTypes)) {
        $filterRules[] = [
            'fieldId' => 'organizationType',
            'filterType' => 4,
            'selectedValues' => $organizationTypes
        ];
    }

    if (!empty($statuses)) {
        $filterRules[] = [
            'fieldId' => 'status',
            'filterType' => 4,
            'selectedValues' => $statuses
        ];
    }

    if (!empty($locations)) {
        $filterRules[] = [
            'fieldId' => 'locations',
            'filterType' => 4,
            'selectedValues' => $locations
        ];
    }

    if (!empty($organizationTags)) {
        $filterRules[] = [
            'fieldId' => 'tags',
            'filterType' => 1,
            'selectedValues' => $organizationTags
        ];
    }

    foreach ($customFields as $customFieldId => $selectedValues) {
        if (!empty($selectedValues)) {
            $filterRules[] = [
                'fieldId' => $customFieldId,
                'filterType' => 4,
                'selectedValues' => $selectedValues
            ];
        }
    }

    $postData = [
        'searchText' => $searchValue,
        'sortBy' => $sortBy,
        'pageNumber' => $startPageNum,
        'pageSize' => $length,
        'filterRules' => $filterRules
    ];

    return $postData;
}

/**
 * Get organization filter configuration from API
 */
public function getOrganizationFilterConfiguration() {
    $options = get_option('ebt_api_settings');
    $tenantCode = $options['dashboard_tenant_code'];
    
    $apiEndpoint = "PublicFilterConfiguration/".$tenantCode."/organizationlist";
    
    $dataResponse = $this->submitApiRequest($apiEndpoint, array(), "GET", 'dashboard');
    
    if (isset($dataResponse['api_response'])) {
        $data = json_decode($dataResponse['api_response'], true);
        
        if ($data !== null) {
            // Exclude 'Logo' from the filter list — it is display-only, not filterable
            if (is_array($data)) {
                $data = array_values(array_filter($data, function($filter) {
                    $displayName = isset($filter['displayName']) ? strtolower($filter['displayName']) : '';
                    $fieldName   = isset($filter['fieldName'])   ? strtolower($filter['fieldName'])   : '';
                    return $displayName !== 'logo' && $fieldName !== 'logo';
                }));
            }
            wp_send_json_success($data);
        } else {
            wp_send_json_error(['message' => 'Invalid JSON response from API', 'raw' => $dataResponse['api_response']]);
        }
    } else {
        wp_send_json_error(['message' => 'Failed to fetch filter configuration', 'error' => $dataResponse]);
    }
    wp_die();
}

/**
 * Get filter items from serviceUrl
 */
public function getFilterItemsFromServiceUrl() {
    $options = get_option('ebt_api_settings');
    $tenantCode = $options['dashboard_tenant_code'];
    
    $serviceUrl = isset($_POST['serviceUrl']) ? sanitize_text_field($_POST['serviceUrl']) : '';
    $fieldName = isset($_POST['fieldName']) ? sanitize_text_field($_POST['fieldName']) : '';
    
    if (empty($serviceUrl)) {
        wp_send_json_error(['message' => 'Service URL is required']);
        wp_die();
    }
    
    // Remove leading slash if present
    $apiEndpoint = ltrim($serviceUrl, '/');
    
    // Strip 'api/v1/' or 'api/v1.0/' or 'v1/' prefix if present since base URL already includes it
    if (strpos($apiEndpoint, 'api/v1.0/') === 0) {
        $apiEndpoint = substr($apiEndpoint, 9); // Remove 'api/v1.0/'
    } elseif (strpos($apiEndpoint, 'api/v1/') === 0) {
        $apiEndpoint = substr($apiEndpoint, 7); // Remove 'api/v1/'
    } elseif (strpos($apiEndpoint, 'v1/') === 0) {
        $apiEndpoint = substr($apiEndpoint, 3); // Remove 'v1/'
    }
    error_log("Processed API Endpoint: " . $apiEndpoint); // Debugging line, can be removed later
    // Add tenant code only if the URL contains {tenantCode} placeholder
    if (strpos($apiEndpoint, '{tenantCode}') !== false) {
        $apiEndpoint = str_replace('{tenantCode}', $tenantCode, $apiEndpoint);
    }
    
    // Append current date to all service URLs in MM-DD-YYYY format
    $currentDate = date('m-d-Y'); // Format: MM-DD-YYYY (e.g., 02-06-2026)
    //$apiEndpoint .= '/' . $currentDate;
    
    $dataResponse = $this->submitApiRequest($apiEndpoint, array(), "GET", 'dashboard');
        
    if (isset($dataResponse['api_response']) && !empty($dataResponse['api_response'])) {
        $data = json_decode($dataResponse['api_response'], true);
        if ($data !== null && $data !== false) {
            // Special-case: when the service returns all organization tags,
            // use the tag 'name' as the checkbox value (frontend expects id by default)
            if (stripos($apiEndpoint, 'GetAllOrganizationTagsPublic') !== false) {
                if (is_array($data)) {
                    foreach ($data as $k => $item) {
                        if (is_array($item) && isset($item['name']) && $item['name'] !== '') {
                            // Ensure frontend picks up name as the value by populating id/value
                            $data[$k]['id'] = $item['name'];
                            $data[$k]['value'] = $item['name'];
                        }
                    }
                }
                error_log('Transformed GetAllOrganizationTagsPublic items to use name as id/value');
            }

            wp_send_json_success($data);
        }
    }
    wp_die();
}
 
}
