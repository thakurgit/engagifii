<?php
/*
*Engagifii abstract class for handling AJAX request
* since v1.0.0
*/ 



class abstractModelEngagifii extends Engagifii_API
{
    protected $dbObj;

    public function __construct()
{
    global $wpdb;
    $this->dbObj = $wpdb;

    $ajax_actions = [
        ['endorsement', 'endorsementLoadGridData'],
        ['legislation', 'legislationLoadGridData'],
        ['courses', 'courseLoadGridData'],
        ['coursesByPerson', 'courseLoadGridDataByPerson'],
        ['peopleList', 'peopleLoadGridData'],
        ['downloadsByPerson', 'downloadDataByPerson'],
        ['generateDownloads', 'generateDownloadsByPerson'],
        ['clearDownloads', 'clearDownloadsByPerson'],
        ['allReports', 'allReportsByPerson'],
        ['classes', 'classLoadGridData'],
        ['classesJS', 'classesDataJS'],
        ['classsearch', 'classSearchLoadGridData'],
        ['events', 'eventsLoadGridData'],
        ['eventFilters', 'eventFilters'],
        ['eventsbyperson', 'eventsLoadGridDataByPerson'],
        ['eventfiltercountdata', 'eventCountFilterData'],
        ['filtercountdata', 'countFilterData'],
        ['coursecountdata', 'courseCountFilterData'],
        ['classcountdata', 'classCountFilterData'],
        ['legislationfiltercountdata', 'countLegislationFilterData'],
        ['legislativeissuedata', 'legislativeIssues'],
        ['legislativetagsdata', 'legislativeTags'],
        ['trackingleveldata', 'trackingLevels'],
        ['legislativestaffmembers', 'staffMembers'],
        ['legislativeactionsdata', 'lastActions'],
        ['getbillids', 'legislationbillids'],
        ['calendar', 'classCalendar'],
        ['getcalendar', 'getCalendar'],
        ['getcalendarclassname', 'getcalendarclassname1'],
        //Added by guru
        ['endorsement', 'endorsementCalendar'],
        ['getendorsementcalendar', 'getendorsementCalendar'],
        ['eventscalendar', 'eventsCalendar'],
        ['geteventscalendar', 'geteventsCalendar'],
        ['publicofficialdata', 'publicOfficalsearchData'], //public official name search
        ['publicOfficialTabs', 'publicOfficialTabs'], //public offcial datatable
        ['poFilter', 'poFilter'], //public offcial filter
        ['publicOfficial', 'publicOfficialLoadData'], //public offcial datatable
        ['publicOfficialCount', 'publicOfficialFilterCount'], //public offcial filter count
        //end here
    ];

    foreach ($ajax_actions as $action) {
        add_action('wp_ajax_nopriv_' . $action[0], [$this, $action[1]]);
        add_action('wp_ajax_' . $action[0], [$this, $action[1]]);
    }
}

/*
 * Generate months options list for select box
 */
public function getMonthList($selected = ''){
    $options = '';
    for($i=1;$i<=12;$i++)
    {
        $value = ($i < 10)?'0'.$i:$i;
        $selectedOpt = ($value == $selected)?'selected':'';
        $options .= '<option value="'.$value.'" '.$selectedOpt.' >'.date("F", mktime(0, 0, 0, $i+1, 0, 0)).'</option>';
    }
    return $options;
}

/*
 * Generate years options list for select box
 */
public function getYearList($selected = ''){
    $yearInit = !empty($selected)?$selected:date("Y");
    $yearPrev = ($yearInit - 5);
    $yearNext = ($yearInit + 5);
    $options = '';
    for($i=$yearPrev;$i<=$yearNext;$i++){
        $selectedOpt = ($i == $selected)?'selected':'';
        $options .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
    }
    return $options;
}
public function calendar_mode(){
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
    $totalDaysOfMonth_Prev = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear); ?>
	        <div class="title-bar col-12 bg-light p-1 border rounded">
            <div class="row align-items-center">
            <div class="title-bar__month col-6 col-md-3 col-lg-2 mb-3 mb-md-0 pr-0">
                <div class="input-group input-group-sm mb-2 mb-md-0">
        <div class="input-group-prepend">
          <div class="input-group-text bg-white rounded-left"><i class="fal fa-calendar-alt"></i></div>
        </div>
                <select class="month-dropdown custom-select-sm custom-select rounded-0">
                    <?php echo $this->getMonthList($dateMonth); ?>
                </select>
                </div>
            </div>
            <div class="title-bar__year col-6 col-md-3 col-lg-2 mb-3 mb-md-0 pl-0">
                <select class="year-dropdown custom-select-sm custom-select rounded-0">
                    <?php echo $this->getYearList($dateYear); ?>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-8 text-center text-md-right text-uppercase">
                <div class="btn-group btn-group-sm calendar-view" role="group" >
                  <button type="button" id="month" class="btn bg-white border shadow-none" aria-pressed="false">Monthly</button>
                  <button type="button" id="week" class="btn bg-white border shadow-none" aria-pressed="false">Weekly</button>
                  <button type="button" id="day" class="btn bg-white border shadow-none" aria-pressed="false">Daily</button>
                </div>
            </div>
        </div>
            
        </div>

<?php  }
     public function classCalendar(){
        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
        $siteURL= site_url();

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

        $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
        $first_date = date("Y-m-d",$first_date_find);

        $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
        $last_date = date("Y-m-d",$last_date_find);
        $class_visible_column_list = $options['class_visible_column_list'];
        
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
		 $classStates = $options['allClasses'];
        if($classStates==1){
       	 $upcomingClasses = [];	
        }else{
            $upcomingClasses = ["Upcoming"];
        }
        $postedData = $this->_classPostCountData();
        $dataResponse = $this->submitApiRequest("Public/Class/FilteredRecordCount", $postedData, "POST", 'classes');
        $classCount = $dataResponse['api_response'];

        $postData = array();    
        $postData['itemCount'] = $classCount;
        $postData['sortBy'] = 'sectionname';
        $postData['pageNumber'] = 1;
        $postData['pageSize'] = 1;//((int) $classCount);
        
        $postData['sortDirection'] = 'asc';
        $postData['filterBody'] = array('searchText'=>'',  'selectedDate' => date('Y-m-d'),'classStates'=>$upcomingClasses);
        $postData['filterBody'] ['sessionDateRange'] = array('startDate'=>$first_date, 'endDate'=>$last_date) ;
    
        if(!empty($_POST['courses']))
        {
            $postData['filterBody']['courses'] = $_POST['courses'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['filterBody']['instructors'] = $_POST['instructors'];
           
        }
		
      
        $dataResponse = $this->submitApiRequest("Public/ClassPagingList", $postData, "POST", 'classes');
        $collection   = json_decode($dataResponse['api_response'])->result;
        $data         = array();
        $classData    = array();
       
        foreach ($collection as $key => $value) {
            
            $class_icon = $value->parentCourse->iconReference;
            if($siteURL == "https://engagifiwebstg.wpengine.com/oresa" || $siteURL == "https://engagifiiweb.com/oresa" || $siteURL == "https://oconeeresa.org"){
                $class_icon = ENGAGIFII_ASSETS_URL.'/images/oconee-logo.png';
                
            }
        		$data['title'] = '<a href="'.$classes_detail_page_link.'?classId='.$value->id.'">'.$value->sectionName.'</a>';
				$data['titleNoLink'] = $value->sectionName;
	            $data['id']    = $value->id;
	            $data['start'] = date('Y-m-d', strtotime($value->classSessionSettings[0]->sessionStartTime));
	            $data['end']   = date('Y-m-d', strtotime($value->classSessionSettings[0]->sessionEndTime));
	            $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
	            $data['objectType'] = $value->objectType;
	            $data['hours']      = number_format($value->courseCreditMapping[0]->credits, 2);//round($value->courseCreditMapping[0]->credits);
	            $data['icon']       = $class_icon;
	            $class_schedule = '';
	            if($value->classDuration > 1){
	                $class_schedule = date('d M Y', strtotime($value->startDate)).' - '.date('d M Y', strtotime($value->endDate));
	            }
	            else{
	                $class_schedule = date('d M Y', strtotime($value->startDate));
	            }

	            $sessionStartTime = date('g:i A',strtotime($value->classSessionSettings[0]->sessionStartTime));
	            $sessionEndTime   = date('g:i A',strtotime($value->classSessionSettings[0]->sessionEndTime));

	            $data['classTime']  =  $class_schedule.' at '.$sessionStartTime.' - '.$sessionEndTime;
	            $classTag = $value->classTag;
	            $allTags = array();
	            foreach ($classTag as $index => $tag) {
	                          
	                $allTags[] = $tag->tagName;
	            }

	            $data['classTag'] = $allTags;
				 $data['viewdetails'] = '<a href="'.$classes_detail_page_link.'?classId='.$value->id.'" class="btn btn-secondary px-3 py-1" target="_blank">View Details</a>';
                if($value->isClassRegistrationAllow)
	            {
                    //echo $value->registrationState;

	                if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
                    {
                        if($value->locationType->name=="onlocation")
                            {
                                $data['register'] = '<a href="'.$value->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                            }
                            elseif($value->locationType->name=="online"){
                                $data['register'] = '<a href="'.$value->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                            }
                            elseif($value->locationType->name=="onlocationandonline")
                            {
                                $data['register'] ='<span id="classlocationButton" style="display: flex;"><a href="'.$value->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1" target="_blank" style="margin-right:2px; ">Register online</a><a href="'.$value->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank" >Register in person</a></span>';
                            }
                            else{
                                $data['register'] = ' ';
                            }

	                    //$data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';

                    }
                    else{
                            $data['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="top" title="'.$value->registrationState.'"><button type="button"  class="btn btn-primary px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                        }
	                
	            }
                else{
                    $data['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="top" title="'.$value->registrationState.'"><button type="button"  class="btn btn-primary px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                        
                }
	            $classData[] = $data; 
        	//} 
        }
        return $classData;
    }
//Class Calendar with Class names
public function getCalendarClassName(){
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

?>

    <main class="calendar-contain row">
    <?php echo $this->calendar_mode(); ?>
        
        <div class="col-12 pt-2">
            <div class="row ">
        <aside class="calendar__sidebar col-md-3 order-2 border  pb-4 class-background" id="event_list">
            
        </aside>

        <div class="calendar__days col-md-9 pt-2 border mb-4 mb-md-0 calendar-background  px-0" id="monthView">

            <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>','<?php echo date("d",strtotime($date.' - 1 Month')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo date("F",strtotime($date.' - 1 Month')); ?></span></a>
                <h3 class="text-center text-uppercase"><?php echo date("F Y",strtotime($date)); ?></h3>
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2  text-uppercase small  btn-primary" style="right: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>','<?php echo date("d",strtotime($date.' + 1 Month')); ?>');"><span class="mr-2"><?php echo date("F",strtotime($date.' + 1 Month')); ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__top-bar bg-light border-top  text-uppercase d-flex text-center ">
                <span class="top-bar__days  py-3 border-right">Mon</span>
                <span class="top-bar__days  py-3 border-right">Tue</span>
                <span class="top-bar__days  py-3 border-right">Wed</span>
                <span class="top-bar__days  py-3 border-right">Thu</span>
                <span class="top-bar__days  py-3 border-right">Fri</span>
                <span class="top-bar__days  py-3 border-right">Sat</span>
                <span class="top-bar__days  py-3">Sun</span>
            </div>

            <?php
                $dayCount = 1;
                $classdata = $this->classCalendar();
                echo '<div class="calendar__week text-center d-flex justify-content-around border-top">';
                for($cb=1;$cb<=$boxDisplay;$cb++){
                    if(($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)){
                    $currentDate = $dateYear.'-'.$dateMonth.'-'.str_pad($dayCount, 2, '0', STR_PAD_LEFT);; 

                        // Get number of events based on the current date
                        
                        $filteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                       sort($filteredItems);
                       // Define date cell color
                        if(strtotime($currentDate) == strtotime(date("Y-m-d")) && count($filteredItems) > 0){
                            ?>
                                <div class=" calendar__day border-right event col flex-column d-flex p-0 today bg-light"  data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>', '<?php json_encode($filteredItems); ?>');" data-start='<?php if(count($filteredItems)) {echo json_encode($filteredItems);}else{ echo "no-data"; } ?>'>
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                                    <?php if(count($filteredItems) > 0){
                                        for($fi=0; $fi<count($filteredItems); $fi++){
                                       
                                            $test = $filteredItems[$fi]['titleNoLink'];
                                            echo '<div class="classNames">';
                                           ?>
                                        <a class="calendar-class badge badge-dark" data-toggle="modal" data-target="#exampleModal<?php echo $filteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php  echo $test; ?>...</a>                                    
                                                <?php
                                        
                                        echo "</div>";
										}
                                    } ?>
                                    </span>
                                </div>
                                <?php  for($fi=0; $fi<count($filteredItems); $fi++){
                                       
                                            $test = $filteredItems[$fi]['titleNoLink'];
                                            
                                           ?>
                                             <!-- Modal -->
                                                <div class="modal fade" id="exampleModal<?php echo $filteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header text-left align-items-center">
                                                        <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left" >
                                                        <p><strong>Date :</strong> <?php echo $filteredItems[$fi]['classTime']; ?></p>
                                                        <p><strong>Duration : </strong><?php echo $filteredItems[$fi]['classDuration']; ?></p>
                                                        <p><strong>Type :</strong> <?php echo $filteredItems[$fi]['objectType']; ?></p>
                                                        <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p>
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                    <a href="<?php echo $classes_detail_page_link;?>?classId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                                    <?php echo $filteredItems[$fi]['register']; ?>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div> 
                                                <?php
                                        
                                           
                                        }
                                     ?>
                            <?php
                        }elseif(count($filteredItems) > 0){
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0"  data-event="<?php echo $currentDate; ?>" data-start='<?php echo json_encode($filteredItems); ?>' onclick="getEvents('<?php echo $currentDate; ?>', '<?php json_encode($filteredItems); ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                                        <?php
					$class_pop='';
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
											$tc =2;
											if(count($filteredItems)<4) {
												$tc = count($filteredItems);	
											}
                                            if($fi<$tc) {
                                            $test = $filteredItems[$fi]['titleNoLink'];
                                        echo '<div class="classNames">';
                                        ?>
                                        <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal<?php echo $filteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?></a>                                    
                                            <?php
                                        echo "</div>";
                                        }
										$test = $filteredItems[$fi]['titleNoLink'];
										$class_pop .= '<div class="classNames"><a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal'. $filteredItems[$fi]['id'].'" href="" style="font-size:11px;" >'.$test.'</a></div>';
										}
										if(count($filteredItems)>3){
											$more = count($filteredItems)-2;
											echo '<div class="classNames"><a id="class-pop" style="font-size:11px;" href="#" class="calendar-class badge">+'.$more.' more</a></div><div class="position-absolute class-pop bg-light py-2" style="display:none;"> <span class="calendar__date mt-auto calendar-text d-block mb-2 text-dark"><strong>'.$dayCount.'</strong></span>'.$class_pop.'</div>';
										} ?>
                                        </span>
                                </div>
                                <?php
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
                                            
                                            $test = $filteredItems[$fi]['titleNoLink'];
                                            //$test = substr($test,0,20);
                                        ?>
                                                <div class="modal fade" id="exampleModal<?php echo $filteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header text-left d-flex align-items-center">
                                                    <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <p><strong>Date :</strong> <?php echo $filteredItems[$fi]['classTime']; ?></p>
                                                        <p><strong>Duration : </strong><?php echo $filteredItems[$fi]['classDuration']; ?></p>
                                                        <p><strong>Type :</strong> <?php echo $filteredItems[$fi]['objectType']; ?></p>
                                                        <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p>
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                    <a href="<?php echo $classes_detail_page_link; ?>?classId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                                    <?php echo $filteredItems[$fi]['register']; ?>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div> 
                                            <?php
                                        
                                        }?>
                                <?php
                            
                        }else{
                            echo '
                                <div class="calendar__day no-event border-right col flex-column d-flex p-0"  data-event="'.$currentDate.'" data-start="no-data">
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
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' - 7 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2">Prev</span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' + 7 day')); ?>');"><span class="mr-2">Next</span><i class="fa fa-chevron-right"></i></a>
            
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
                        
                        $weekfilteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                        sort($weekfilteredItems);
            ?>			
            
                        <div class="calendar__day border-right <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light'; } ?>"  data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
                            <span class="calendar__date mt-auto calendar-text"><?php echo date('d',strtotime($week_array[$i]));  ?></span>
                            <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                            <?php if(count($weekfilteredItems) > 0){
                               for($fi=0; $fi<count($weekfilteredItems); $fi++){
								   $tc =2;
											if(count($weekfilteredItems)<4) {
												$tc = count($weekfilteredItems);	
											}
                                            if($fi<$tc) {
                                $test = $weekfilteredItems[$fi]['titleNoLink'];
                            
                            echo '<div class="classNames">';
                            ?>
                            <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?>...</a>                                    
                                 
                                <?php
                            if(count($weekfilteredItems) >1) { }
                            echo "<br>";
                            echo '</div>';
                            }
							}
							if(count($weekfilteredItems)>3){
											$more = count($weekfilteredItems)-2;
											echo '<div class="classNames"><a style="font-size:11px;" href="javascript:void" class="calendar-class badge">+'.$more.' more</a></div>';
										}
                            } ?>
                            </span>
                        </div>
                        <?php   
						for($fi=0; $fi<count($weekfilteredItems); $fi++){
                                $test = $weekfilteredItems[$fi]['titleNoLink'];
                            ?>
                                    <div class="modal fade" id="exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header text-left d-flex align-items-center pr-5">
                                        <img src="<?php echo $weekfilteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $weekfilteredItems[$fi]['title']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" >
                                            <p><strong>Date :</strong> <?php echo $weekfilteredItems[$fi]['classTime']; ?></p>
                                            <p><strong>Duration : </strong><?php echo $weekfilteredItems[$fi]['classDuration']; ?></p>
                                            <p><strong>Type :</strong> <?php echo $weekfilteredItems[$fi]['objectType']; ?></p>
                                            <p><strong>Credit Hours : </strong><?php echo $weekfilteredItems[$fi]['hours']; ?></p>
                                            
                                        </div>
                                        <div class="modal-footer">
                                        <a href="<?php echo $classes_detail_page_link; ?>?classId=<?php echo $weekfilteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php echo $weekfilteredItems[$fi]['register']; ?>
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
        <div id="dayView" class="calendar__days col-12 pb-4 pt-5 px-lg-5 border">
            <?php
                    $prev_date = date('D', strtotime($postedDate .' -1 day'));
                    $next_date = date('D', strtotime($postedDate .' +1 day'));
            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' - 1 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo $prev_date; ?></span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' + 1 day')); ?>');"><span class="mr-2"><?php echo $next_date; ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__week text-center justify-content-around" id="today_event">

            </div>

        </div>
    </div>
</div>
    </main>

<?php
wp_die();
}
public function getCalendarClassName1(){
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
	$front_pages = $options['front_pages'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
    $class_visible_column_list = $options['class_visible_column_list'];

?>

    <main class="calendar-contain row">
    <?php echo $this->calendar_mode(); ?>
        
        <div class="col-12 pt-2">
            <div class="row ">
        <aside class="calendar__sidebar col-md-3 order-2 border  pb-4 class-background" id="event_list">
            
        </aside>

        <div class="calendar__days col-md-9 pt-2 border mb-4 mb-md-0 calendar-background  px-0" id="monthView">

            <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>','<?php echo date("d",strtotime($date.' - 1 Month')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo date("F",strtotime($date.' - 1 Month')); ?></span></a>
                <h3 class="text-center text-uppercase"><?php echo date("F Y",strtotime($date)); ?></h3>
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2  text-uppercase small  btn-primary" style="right: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>','<?php echo date("d",strtotime($date.' + 1 Month')); ?>');"><span class="mr-2"><?php echo date("F",strtotime($date.' + 1 Month')); ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__top-bar bg-light border-top  text-uppercase d-flex text-center ">
                <span class="top-bar__days  py-3 border-right">Mon</span>
                <span class="top-bar__days  py-3 border-right">Tue</span>
                <span class="top-bar__days  py-3 border-right">Wed</span>
                <span class="top-bar__days  py-3 border-right">Thu</span>
                <span class="top-bar__days  py-3 border-right">Fri</span>
                <span class="top-bar__days  py-3 border-right">Sat</span>
                <span class="top-bar__days  py-3">Sun</span>
            </div>

            <?php
                $dayCount = 1;
                $classdata = $this->classCalendar();
                echo '<div class="calendar__week text-center d-flex justify-content-around border-top">';
                for($cb=1;$cb<=$boxDisplay;$cb++){
                    if(($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)){
                        // Current date
                        $currentDate = $dateYear.'-'.$dateMonth.'-'.str_pad($dayCount, 2, '0', STR_PAD_LEFT);; 

                        // Get number of events based on the current date
                        
                        $filteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                       sort($filteredItems);
                      // Define date cell color
                        if(count($filteredItems) > 0){
							$today='';
							if(strtotime($currentDate) == strtotime(date("Y-m-d"))){
								$today = ' today bg-light';	
							}
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0 <?php echo $today; ?>"  data-event="<?php echo $currentDate; ?>" data-start='<?php echo str_replace("'",'&#39;',json_encode($filteredItems)); ?>' onclick="getEvents('<?php echo $currentDate; ?>', '<?php json_encode($filteredItems); ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                                        <?php
										$class_pop='';
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
											$tc =2;
											if(count($filteredItems)<4) {
												$tc = count($filteredItems);	
											}
                                            if($fi<$tc) {
                                            $test = $filteredItems[$fi]['titleNoLink'];
                                        echo '<div class="classNames">';
                                        ?>
                                        <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal<?php echo $filteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?></a>                                    
                                            <?php
                                        echo "</div>";
                                        }
										$test = $filteredItems[$fi]['titleNoLink'];
										$class_pop .= '<div class="classNames"><a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal'. $filteredItems[$fi]['id'].'" href="" style="font-size:11px;" >'.$test.'</a></div>';
										}
										if(count($filteredItems)>3){
											$more = count($filteredItems)-2;
											echo '<div class="classNames"><a id="class-pop" style="font-size:11px;" href="#" class="calendar-class badge">+'.$more.' more</a></div><div class="position-absolute class-pop bg-light py-2" style="display:none;"> <span class="calendar__date mt-auto calendar-text d-block mb-2 text-dark"><strong>'.$dayCount.'</strong></span>'.$class_pop.'</div>';
										} ?>
                                        </span>
                                </div>
                                <?php
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
                                            
                                            $test = $filteredItems[$fi]['titleNoLink'];
                                            //$test = substr($test,0,20);
                                        ?>
                                                <div class="modal fade" id="exampleModal<?php echo $filteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header text-left d-flex align-items-center pr-5 justify-content-start">
                                                    <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5>
                                                        <button type="button" class="close p-2" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                    <?php if(in_array('sessions', $class_visible_column_list)){ ?>
                                                        <p><strong>Date :</strong> <?php echo $filteredItems[$fi]['classTime']; ?></p>
                                                        <?php }
                                                        if(in_array('classDuration', $class_visible_column_list)){ ?>
                                                        <p><strong>Duration : </strong><?php echo $filteredItems[$fi]['classDuration']; ?></p>
                                                        <?php } 
                                                        if(in_array('objectType', $class_visible_column_list)){ ?>
                                                        <p><strong>Type :</strong> <?php echo $filteredItems[$fi]['objectType']; ?></p>
                                                        <?php } 
                                                        if(in_array('credithours', $class_visible_column_list)){ ?>
                                                        <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <a href="<?php echo $classes_detail_page_link; ?>?classId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                                    <?php if(in_array('register', $class_visible_column_list)) { echo $filteredItems[$fi]['register']; } ?>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div> 
                                            <?php
                                        
                                        }?>
                                <?php
                            
                        }else{
                            echo '
                                <div class="calendar__day no-event border-right col flex-column d-flex p-0"  data-event="'.$currentDate.'" data-start="no-data">
                                    <span class="calendar__date my-auto calendar-text">'.$dayCount.'</span>
                                    
                                    
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
                            <div class="calendar__day no-event border-right col flex-column d-flex p-0 inactive '.$inactiveLabel.'">
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
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' - 7 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2">Prev</span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' + 7 day')); ?>');"><span class="mr-2">Next</span><i class="fa fa-chevron-right"></i></a>
            
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
                        
                        $weekfilteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                        sort($weekfilteredItems);
            ?>			
            
                        <div class="calendar__day border-right <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light'; } ?>"  data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
                            <span class="calendar__date mt-auto calendar-text"><?php echo date('d',strtotime($week_array[$i]));  ?></span>
                            <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                            <?php if(count($weekfilteredItems) > 0){
								$class_pop='';
                               for($fi=0; $fi<count($weekfilteredItems); $fi++){
								   $tc =2;
											if(count($weekfilteredItems)<4) {
												$tc = count($weekfilteredItems);	
											}
                                            if($fi<$tc) {
                                $test = $weekfilteredItems[$fi]['titleNoLink'];
                            
                            echo '<div class="classNames">';
                            ?>
                            <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?></a>                                    
                                 
                                <?php
                            if(count($weekfilteredItems) >1) { }
                            echo "<br>";
                            echo '</div>';
                            }
							$test = $weekfilteredItems[$fi]['titleNoLink'];
										$class_pop .= '<div class="classNames"><a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal'. $weekfilteredItems[$fi]['id'].'" href="" style="font-size:11px;" >'.$test.'</a></div>';
							}
							if(count($weekfilteredItems)>3){
											$more = count($weekfilteredItems)-2;
											
											echo '<div class="classNames"><a id="class-pop" style="font-size:11px;" href="#" class="calendar-class badge">+'.$more.' more</a></div><div class="position-absolute class-pop bg-light py-2" style="display:none;"> <span class="calendar__date mt-auto calendar-text d-block mb-2 text-dark"><strong>'.date('d',strtotime($week_array[$i])).'</strong></span>'.$class_pop.'</div>';
										}
                            } ?>
                            </span>
                        </div>
                        <?php   
						for($fi=0; $fi<count($weekfilteredItems); $fi++){
                                $test = $weekfilteredItems[$fi]['titleNoLink'];
                            ?>
                                    <div class="modal fade" id="exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header text-left d-flex align-items-center pr-5">
                                        <img src="<?php echo $weekfilteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $weekfilteredItems[$fi]['title']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" >
                                        <?php if(in_array('sessions', $class_visible_column_list)){ ?>
                                            <p><strong>Date :</strong> <?php echo $weekfilteredItems[$fi]['classTime']; ?></p>
                                            <?php }
                                            if(in_array('classDuration', $class_visible_column_list)){ ?>
                                            <p><strong>Duration : </strong><?php echo $weekfilteredItems[$fi]['classDuration']; ?></p>
                                            <?php } 
                                            if(in_array('objectType', $class_visible_column_list)){ ?>
                                                <p><strong>Type :</strong> <?php echo $weekfilteredItems[$fi]['objectType']; ?></p>
                                                        <?php } 
                                                        if(in_array('credithours', $class_visible_column_list)){ ?>
                                            <p><strong>Credit Hours : </strong><?php echo $weekfilteredItems[$fi]['hours']; ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="modal-footer">
                                        <a href="<?php echo $classes_detail_page_link; ?>?classId=<?php echo $weekfilteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                        <?php if(in_array('register', $class_visible_column_list)) { echo $weekfilteredItems[$fi]['register']; } ?>
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
        <div id="dayView" class="calendar__days col-12 pb-4 pt-5 px-lg-5 border">
            <?php
                    $prev_date = date('D', strtotime($postedDate .' -1 day'));
                    $next_date = date('D', strtotime($postedDate .' +1 day'));
            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' - 1 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo $prev_date; ?></span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getCalendarClassName('calendar_div','<?php echo date("Y",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' + 1 day')); ?>');"><span class="mr-2"><?php echo $next_date; ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__week text-center justify-content-around" id="today_event">

            </div>

        </div>
    </div>
</div>
    </main>

<?php
wp_die();
}
//End Here - class Calendar with class name

//Display Calendar layout data for Events : Added by Gurpreet 

public function getEventsCalendar(){
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
    <?php echo $this->calendar_mode(); 
	        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $events_detail_page = $front_pages['events_detail_page'];
	if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	}else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	}

	?>
       
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
                $eventsdata = $this->eventsCalendar();
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
                                <div class="calendar__day border-right event col flex-column d-flex p-0 today bg-light border border-success" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($filteredItems)) {echo json_encode($filteredItems);}else{ echo "no-data"; } ?>'>
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text">
                                    <?php if(count($filteredItems) > 0){
                                         for($fi=0; $fi<count($filteredItems); $fi++){
                                            $test = $filteredItems[$fi]['name'];
                                            $test = substr($test,0,20);
                                           //echo $test.'...'; 
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
                                                   <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                               </div>
                                               <div class="modal-body text-left" >
                                                 <?php if(in_array('startDateTime', $events_visible_column_list)){ ?>
                                                   <p><strong>Date :</strong> <?php echo $filteredItems[$fi]['schedule']; ?></p>
                                                   <?php }
                                                   if(in_array('eventType', $events_visible_column_list)){ ?>
                                                   <p><strong>Type : </strong><?php echo $filteredItems[$fi]['objectType']; ?></p>
                                                   <?php } ?>
                                                    <p><strong>Price :</strong> <?php echo '$'.$filteredItems[$fi]['price']; ?></p>
                                                   <!-- <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p> -->
                                                   
                                               </div>
                                               <div class="modal-footer">
                                               <a href="<?php echo $events_detail_page_link;?>?endId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                               <?php if(in_array('register', $events_visible_column_list)) { echo $filteredItems[$fi]['register']; } ?>
                                               </div>
                                               </div>
                                           </div>
                                           </div> 
                                           <?php
                                   
                                      
                                   }
                                ?>
                            <?php
                        }elseif(count($filteredItems) > 0){
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0" data-event="<?php echo $currentDate; ?>" data-start='<?php echo json_encode($filteredItems); ?>' onclick="getEvents('<?php echo $currentDate; ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                                    <?php
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
                                            $test = $filteredItems[$fi]['name'];
                                            $test = substr($test,0,20);
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
                                        <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-left">
                                                   <?php if(in_array('startDateTime', $events_visible_column_list)){ ?>
                                                  <p><strong>Date :</strong> <?php echo $filteredItems[$fi]['schedule']; ?></p>
                                                   <?php }
                                                   if(in_array('eventType', $events_visible_column_list)){ ?>
                                                  <p><strong>Type : </strong><?php echo $filteredItems[$fi]['objectType']; ?></p>
                                                  <?php } ?>
                                                  <p><strong>Price :</strong> <?php echo '$'.$filteredItems[$fi]['price']; ?></p>
                                                   <!-- <p><strong>Credit Hours : </strong><?php echo $filteredItems[$fi]['hours']; ?></p> -->
                                            
                                        </div>
                                        <div class="modal-footer">
                                        <a href="<?php echo $events_detail_page_link;?>?endId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
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
            
                        <div class="calendar__day border-right <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light'; } ?>" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
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
                                               <a href="<?php echo $events_detail_page_link;?>?endId=<?php echo $weekfilteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
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
// End Display Calendar layout data for Events : Added by Gurpreet
    public function eventCountFilterData()
    {

        $postedData = $this->_eventsPostCountData();
		//print_r(json_encode($postedData)); die;
        $dataResponse = $this->submitApiRequest("Public/count", $postedData, "POST", 'event');
        header("Content-Type: application/json");   
        echo json_encode($dataResponse);
        wp_die();
    }
	 public function _eventsPostCountData(){

         $searchValue = '';
        if (strlen($_POST['search']['value']) > 1) {
            $searchValue = $_POST['search']['value'];
        }

        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);

        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }

        if ($columnsData["sectionname"] == "sectionname") {
            $sortBy = "sectionname";
        }else if ($columnsData["startdate"] == "startdate") {
            $sortBy = "startdate";
        }else if ($columnsData["credithours"] == "credithours") {
            $sortBy = "credithours";
        }else {
            $sortBy = "";
        }

        
        $allEvents = get_option( 'ebt_api_settings' )['allEvents'];
        if($allEvents==1){
        $allEvents = 'false';	
        }else{
            $allEvents = 'true';
        }
        $postData = array();
        $postData['title'] = $searchValue;            
        $postData['searchText'] = $searchText;      
        $postData['lastActionStartDate'] = $datepickerstart;
        $postData['lastActionEndDate'] = $datepickerend;
        $postData['sortBy'] = $sortBy;
        $postData['pageNumber'] = $startPageNum;
        $postData['onlyUpcoming'] = $allEvents;
		if(!empty($_POST['eventStartDate'])){
        	$postData['EventStartDate'] = date('m-d-Y',strtotime($_POST['eventStartDate']));
		}
		if(!empty($_POST['eventEndDate'])){
        	$postData['EventEndDate'] = date('m-d-Y',strtotime($_POST['eventEndDate']));;
		}
       // $postData['pageSize'] = $_POST['length'];

        if(!empty($_POST['tags']))
        {
            $postData['tags'] = $_POST['tags'];
        }
        if(!empty($_POST['types']))
        {
            $postData['types'] = $_POST['types'];
        }
		if(!empty($_POST['locations']))
        {
            $postData['locations'] = $_POST['locations'];
        }
      

        if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }

        $getCurrentdate = date("Y-m-d");
        $postData['selectedDate'] = $getCurrentdate;
        return $postData;
    }
    public function countFilterData()
    {

        $postedData = $this->_preparePostCountData();
        $dataResponse = $this->submitApiRequest("Public/Award/PublicFilteredRecordCount", $postedData, "POST", 'endorsement');
        header("Content-Type: application/json");   
        echo json_encode($dataResponse);
        wp_die();
    }


    public function courseCountFilterData(){
		
        $postedData = $this->_coursePostCountData();
        $dataResponse = $this->submitApiRequest("Public/Course/FilteredRecordCount", $postedData, "POST", 'courses');
        header("Content-Type: application/json");   
        echo json_encode($dataResponse);
        wp_die();
    }
    public function _coursePostCountData()
    {

        $searchValue = '';
        if (strlen($_POST['search']['value']) > 1) {
            $searchValue = $_POST['search']['value'];
        }

        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
       
        

        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }

        if ($columnsData["name"] == "name") {
            $sortBy = "name";
        }else if ($columnsData["instructor"] == "instructor") {
            $sortBy = "instructor";
        }else if ($columnsData["class"] == "class") {
            $sortBy = "class";
        }else {
            $sortBy = "";
        }


        $postData = array();
        $postData['title'] = $searchValue;            
        $postData['searchText'] = $searchText;      
        $postData['lastActionStartDate'] = $datepickerstart;
        $postData['lastActionEndDate'] = $datepickerend;
        $postData['sortBy'] = $sortBy;
        $postData['pageNumber'] = $startPageNum;
        $postData['pageSize'] = $_POST['length'];

        if(!empty($_POST['classes']))
        {
            $postData['classes'] = $_POST['classes'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['instructors'] = $_POST['instructors'];
        }
        if(!empty($_POST['tags']))
        {
            $postData['tags'] = $_POST['tags'];
        }

        if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }

        $getCurrentdate = date("Y-m-d");
        $postData['selectedDate'] = $getCurrentdate;
        return $postData;
    }
	
    public function classCountFilterData(){
        $postedData = $this->_classPostCountData();
        $dataResponse = $this->submitApiRequest("Public/Class/FilteredRecordCount", $postedData, "POST", 'classes');
        header("Content-Type: application/json");   
        echo json_encode($dataResponse);
        wp_die();
    }

    public function countLegislationFilterData()
    {
        $postedData = $this->_prepareLegislationPostCountData();
        $dataResponse = $this->submitApiRequest("legislative/public-bills/all-filter-list/count", $postedData, "POST", 'legislation');
        header("Content-Type: application/json");     
       	echo json_encode($dataResponse);
        wp_die();
    }
    private function _prepareLegislationPostCountData()
    {

        $serachTxt = '';
        if (strlen($_POST['search']['value']) > 1) {
            $serachTxt = $_POST['search']['value'];
        }

       /* $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);*/
        $title = $_POST['searchbytitle'];
        $searchText = $_POST['tzdatasearch'];

        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }

        $isAsscend = $_POST["order"][0]["dir"];

        if ($isAsscend == 'asc') {
            $isAsscending = true;
        } else {
            $isAsscending = false;
        }

        if ($columnsData["title"] == "title") {
            $sortBy = "Title";
        } else if ($columnsData["billType"] == "billType") {
            $sortBy = "BillType";
        } else if ($columnsData["billNumber"] == "billNumber") {
            $sortBy = "BillNumber";
        } else if ($columnsData["lastActionOn"] == "lastActionOn") {
            $sortBy = "LastAction";
        } else if ($columnsData["state"] == "state") {
            $sortBy = "State";
        } else {
            $sortBy = "";
        }

        $trackingLevels = array();
        if (isset($_POST['trackingLevels']) && !empty($_POST['trackingLevels'])) {
            $trackingLevels = @explode(",", $_POST['trackingLevels']);
           
        }     
        
        $sponsors = array();
        if (isset($_POST['sponsors']) && !empty($_POST['sponsors'])) {
            $sponsors = @explode(",", $_POST['sponsors']);
           
        }
        $tags = array();
         if (isset($_POST['tags']) && !empty($_POST['tags'])) {
            $tags =  @explode(",",$_POST['tags']);
        }

        $assignTo = array();
         if (isset($_POST['assignTo']) && !empty($_POST['assignTo'])) {
            $assignTo =  @explode(",", $_POST['assignTo']);
        }

         $assignTag = array();
         if (isset($_POST['assignTag']) && !empty($_POST['assignTag'])) {
            $assignTag =  @explode(",", $_POST['assignTag']);
        }

         $assignGroups = array();
         if (isset($_POST['assignGroups']) && !empty($_POST['assignGroups'])) {
            $assignGroups =  @explode(",", $_POST['assignGroups']);
        }

        $houseCommittees = array();
        if (isset($_POST['houseCommittees']) && !empty($_POST['houseCommittees'])) {
            $houseCommittees = @explode(",", $_POST['houseCommittees']);
           
        } 
        $senateCommittees = array();
        if (isset($_POST['senateCommittees']) && !empty($_POST['senateCommittees'])) {
            $senateCommittees = @explode(",", $_POST['senateCommittees']);
           
        }  
        $lastActionTypes = array();
        if (isset($_POST['lastActionTypes']) && !empty($_POST['lastActionTypes'])) {
            $lastActionTypes = @explode(",", $_POST['lastActionTypes']);
           
        }  
        $billTypes = array();
        if (isset($_POST['billTypes']) && !empty($_POST['billTypes'])) {
            $billTypes = @explode(",", $_POST['billTypes']);
           
        }  
        $statusTypes = array();
        if (isset($_POST['statusTypes']) && !empty($_POST['statusTypes'])) {
            $statusTypes = @explode(",", $_POST['statusTypes']);
           
        } 
         

         $datepickerstart = null;
        $datepickerend = null;
        if (isset($_POST['startDate']) && !empty($_POST['startDate'])) {
            $datepickerstart = $_POST['startDate'];

        }

        if (isset($_POST['endDate']) && !empty($_POST['endDate'])) {
            $datepickerend = $_POST['endDate'];

        }
        $sessionId = "";
        if (isset($_POST['sessionId'])) {
            $sessionId = $_POST['sessionId'];

        }

        $postData = array();
        $postData['trackingLevels'] = $trackingLevels;
        $postData['title'] = $title;       
        $postData['sponsors'] = $sponsors;
        $postData['houseCommittees'] = $houseCommittees;
        $postData['senateCommittees'] = $senateCommittees;        
        $postData['searchText'] = $searchText;      
        $postData['lastActionStartDate'] = $datepickerstart;
        $postData['lastActionEndDate'] = $datepickerend;
        $postData['lastActionTypes'] = $lastActionTypes;
        $postData['users'] = $assignTo;
        $postData['tags'] = $tags;
        $postData['usersTags'] = $assignTag;
        $postData['clientPersonGroups'] = $assignGroups;
        $postData['billTypes'] = $billTypes;
        $postData['status'] = $statusTypes;
        $postData['sortBy'] = "";
        $postData['isAsscending'] = $isAsscending;
        $postData['pageNumber'] = 1;
        $postData['pageSize'] = 10;
		$postData['sessionId'] = $sessionId;
        return $postData;
    }
	
	    public function publicOfficalsearchData(){
        $postedData = $this->_publicOfficialSearch();
        $dataResponse = $this->submitApiRequest("legislative/public-bills/elected/officials-all-tabs-list", $postedData, "POST", 'legislation');
        header("Content-Type: application/json");   
        echo $dataResponse['api_response'];
        wp_die();
    }
    public function _publicOfficialSearch(){
        $postData = array();
        $postData['name'] = $_POST['searchText'];      
        return $postData;
    }


	    public function publicOfficialFilterCount(){
        $postedData = $this->_publicOfficialCount();
        $dataResponse = $this->submitApiRequest("legislative/public-bills/elected/officials-all-tabs-list", $postedData, "POST", 'legislation');
        header("Content-Type: application/json");
		$newcount=array();
		foreach (json_decode($dataResponse['api_response'], true) as $key => $value) {
			array_push($newcount,count($value));	
		}
		echo json_encode($newcount);
        wp_die();
    }
    public function _publicOfficialCount(){
        $postData = array();
        $postData['cityofResidence'] = $_POST['cityofResidence'];      
        $postData['committee'] = $_POST['committee'];      
        $postData['politicalParty'] = $_POST['politicalParty'];      
        $postData['role'] = $_POST['role'];      
        $postData['county'] = $_POST['county'];      
        $postData['office'] = $_POST['office'];      
        return $postData;
    }
	
	 public function legislativeTags()
    {
        $postedData = $this->_prepareLegislativeIssuesData();
		$session = $postedData['sessionId'];
        $dataResponse = $this->submitApiRequest("legislative/public-bills/filter/tags?sessionId=".$session,$postedData,"GET", 'legislation');
        header("Content-Type: application/json");  
		$responseArray = array();
		$options = get_option( 'ebt_api_settings' );
		$lbt_visib_tags_list   = $options['lbt_visib_tags_list']  ?? array();
		foreach(json_decode($dataResponse['api_response']) as $tag){
			if($tag->count>0 && in_array($tag->tagId, $lbt_visib_tags_list)){
			  array_push($responseArray, $tag);
			}
		}
        echo json_encode($responseArray);
        wp_die();
    }
	 public function legislativeIssues()
    {
        $postedData = $this->_prepareLegislativeIssuesData();
		$session = $postedData['sessionId'];
        $dataResponse = $this->submitApiRequest("legislative/public-bills/filter/tags?sessionId=".$session,$postedData,"GET", 'legislation');
        header("Content-Type: application/json");  
        echo json_encode($dataResponse);
        wp_die();
    }
	
	 public function trackingLevels()
    {
        $postedData = $this->_prepareLegislativeIssuesData();
		$session = $postedData['sessionId'];
        $dataResponse = $this->submitApiRequest("legislative/public-bills/trackinglevels?sessionId=".$session,$postedData,"GET", 'legislation');
        header("Content-Type: application/json"); 
        echo json_encode($dataResponse);
        wp_die();
    }
	
	 public function staffMembers()
    {
        $postedData = $this->_prepareLegislativeIssuesData();
       // print_r($postedData);
		//die;
		$session = $postedData['sessionId'];
        $dataResponse = $this->submitApiRequest("legislative/public-bills/filter/billusers?sessionId=".$session,$postedData,"GET", 'legislation');
        header("Content-Type: application/json"); 
		//print_r($dataResponse);
		//die;  
        echo json_encode($dataResponse);
        wp_die();
    }
	
	 public function lastActions()
    {
        $postedData = $this->_prepareLegislativeIssuesData();
       // print_r($postedData);
		//die;
		$session = $postedData['sessionId'];
        $dataResponse = $this->submitApiRequest("legislative/public-bills/lastactions?sessionId=".$session,$postedData,"GET", 'legislation');
        header("Content-Type: application/json"); 
		//print_r($dataResponse);
		//die;  
        echo json_encode($dataResponse);
        wp_die();
    }
	


    public function legislationbillids(){
        $postedData['sortBy'] = '';
        $postedData['isAsscending'] = true;
        $postedData['pageNumber'] = 1;
        $postedData['pageSize'] = 50;
        $postedData['introducedDate'] = date('m/d/Y');
        $postedData['title'] = '';
        $postedData['billNumber'] = '';
        $postedData['searchText'] = '';
        $postedData['trackingLevels'] = array();
        $postedData['sponsors'] = array();
        $postedData['houseCommittees'] = array();
        $postedData['senateCommittees'] = array();
        $postedData['lastActionStartDate'] = '';
        $postedData['lastActionEndDate']   = '';
        $postedData['tags']                = array();
        $postedData['lastActionTypes']     = array();
        $postedData['billTypes']           = array();
        $postedData['status']              = array();
        $postedData['usersTags']           = array();
        $postedData['clientPersonGroups']  = array();
        $postedData['users']               = array();
        
        $dataResponse = $this->submitApiRequest("legislative/public-bills/list",$postedData,"POST",'legislation');
        $collection = json_decode($dataResponse['api_response']);
        $bill_array   = array();
        foreach ($collection->collection as $key => $row) {
            $bill_array[$key] = $row->id;
        }
        echo json_encode($bill_array);
        wp_die();
    }

    public function classLoadGridData(){
        $siteURL= site_url();
        
        $postedData  = $this->_prepareClassData();
		//print_r(json_encode($postedData));
		//die;
        $dataResponse = $this->submitApiRequest("Public/ClassPagingList", $postedData, "POST", 'classes');
        
        $collection   = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        $data         = array();

        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
        
         foreach ($collection as $key => $value) {
            
            #nested data
            $nestedData = array();
            $instructorPopOver = '';
            $classPopover      = '';

            $class_icon = $value->parentCourse->iconReference;
            if($siteURL == "https://engagifiwebstg.wpengine.com/oresa" || $siteURL == "https://engagifiiweb.com/oresa" || $siteURL == "https://oconeeresa.org"){
                $class_icon = ENGAGIFII_ASSETS_URL.'/images/oconee-logo.png';
                
            }

            if(count($value->classInstructors)){
                $instructorPopOver = $this->_popOverInstructorData1($key, $value->classInstructors);
			}
            if(count($value->classSessions)){
                $classPopover  = $this->_popOverClassData1($key, $value->classSessions);
                
			}

            

            ## row data
            $class_schedule = '';
            $counter = 0; 
            if(count($value->classSessions))
            {
                foreach ($value->classSessions as $key => $rowData) {
            
                    $classSessionTime = '';
                    if( $counter == 0 ) {         
                        $classSessionStartTime = $rowData->startTime;
                        $classSessionStartDate = $rowData->sessionDate;
                    }                  
                    if( $counter == count( $value->classSessions ) - 1) {
                         $classSessionEndTime = $rowData->endTime;
                         $classSessionEndDate = $rowData->sessionDate;
                    }
                    //$classSessionTime = date('M d, Y', strtotime($rowData->sessionDate)).' At '.$classSessionStartTime.' - '.$classSessionEndTime;
                    $classSessionTime = date('M d, Y', strtotime($classSessionStartDate)).' - '.date('M d, Y', strtotime($classSessionEndDate));
                    $class_schedule = '<small class="d-block" style="white-space:normal;">'.$classSessionTime.' <br>'.$classSessionStartTime.'-'.$classSessionEndTime.'</small>';
                    $counter = $counter + 1;
                }
                $nestedData['sectionname'] = '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.$classes_detail_page_link.'?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'</div></div>';//.$class_schedule.'<small class="d-block" style="white-space:normal;">'.date('d M Y', strtotime($value->startDate)).' </small>
            }else{
            $nestedData['sectionname'] = '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.$classes_detail_page_link.'?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'<small class="d-block" style="white-space:normal;">'.date('M d, Y', strtotime($value->startDate)).' at '.date('g:i A', strtotime($value->startDate)).' - '.date('g:i A', strtotime($value->endDate)).' </small></div></div>';//.$class_schedule.'<small class="d-block" style="white-space:normal;">'.date('d M Y', strtotime($value->startDate)).' </small>
            }
            $nestedData['classDuration'] = $value->classDuration.' '.$value->classDurationType;
            $nestedData['objectType'] = $value->objectType;
			
            $nestedData['startdate'] = '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" >';

			if(count($value->classSessions)){
				 foreach ($value->classSessions as $key => $rowData) {
            
                    $classSessionTime = '';
                    if( $counter == 0 ) {         
                        $classSessionStartTime = $rowData->startTime;
                        $classSessionStartDate = $rowData->sessionDate;
                    }                  
                    if( $counter == count( $value->classSessions ) - 1) {
                         $classSessionEndTime = $rowData->endTime;
                         $classSessionEndDate = $rowData->sessionDate;
                    }
                    //$classSessionTime = date('M d, Y', strtotime($rowData->sessionDate)).' At '.$classSessionStartTime.' - '.$classSessionEndTime;
                    $classSessionTime = date('M d, Y', strtotime($classSessionStartDate)).' - '.date('M d, Y', strtotime($classSessionEndDate));
                    $class_schedule = '<small class="d-block" style="white-space:normal;">'.$classSessionTime.' <br>'.$classSessionStartTime.'-'.$classSessionEndTime.'</small>';
                    $counter = $counter + 1;
                }
            	 $nestedData['startdate'] = '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->classSessions).'</span></div>'.$classPopover.'</div>';
			}
			
			$nestedData['classInstructorsCount']='<img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Instructors Available" >';
			if($value->classInstructorsCount>0){
				$nestedData['classInstructorsCount'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.($value->classInstructorsCount).'</span></div>'.$instructorPopOver.'</div>';  
			}
            if($value->isCreditTypeSingle =="true"){
            $nestedData['credithours'] = number_format($value->courseCreditMapping[0]->credits, 2);//($value->courseCreditMapping[0]->credits);          
            }else{
                $nestedData['credithours'] = number_format($value->courseCreditMapping[0]->credits, 2);      
            }
            $classTag = $value->classTag;
            $allTags = array();
            foreach ($classTag as $index => $tag) {
                
                    if(count($classTag) > 1 && $index == 0)
                    {   
                        $tagPopover =  $this->_popOverTagData1($key, $value->classTag);

                           $tagCount   = count($classTag) - 1;
                    
					$allTags[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$tag->tagName.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span>'.$tagPopover.'</div>';
                    }
                    elseif(count($classTag) == 1)
                        $allTags[] = $tag->tagName;
            }

            $nestedData['classTag'] = implode(" ", $allTags);
            if($value->isClassRegistrationAllow || $value->registrationWorkFlowId)
            {
              if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
              {
                   if($value->locationType->name=="onlocation")
                      { 
                      $nestedData['register'] = '<a href="'.$value->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      //$nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      }
                      elseif($value->locationType->name=="online"){
                          $nestedData['register'] = '<a href="'.$value->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      }
                      elseif($value->locationType->name=="onlocationandonline"){
                      $nestedData['register'] = '<a style="white-space:nowrap" href="'.$value->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1 mb-2" target="_blank" >Register Online</a><br/><a style="white-space:nowrap" href="'.$value->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank" >Register in person</a>';
                      }
                  else{
                      $nestedData['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Class Location not defined"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                  }
              }
              else{
              $nestedData['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
              }
          }else{
            $nestedData['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
            //$data[] = $nestedData;    
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
    public function _prepareClassData(){
        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);

        $isAsscend = $_POST["order"][0]["dir"];

        if ($isAsscend == 'asc') {
            $isAsscending = true;
        } else {
            $isAsscending = false;
        }
        
		$titleColumn = $_POST['titleColumn'];
        $title = $_POST['columns'][$titleColumn]['search']['value'];

        if (strlen($_POST['search']['value']) > 1) {
            $title = $_POST['search']['value'];
        }
        $postData = array();
        $sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
        $postData['itemCount'] = $_POST['length'];
        $postData['sortBy'] = $sortBy;
        //$postData['isAsscending'] = $isAsscending;
        $postData['pageNumber'] = ($startPageNum);
        $postData['pageSize'] = ((int) $_POST['length']);
        $postData['sortDirection'] = $_POST["order"][0]["dir"];
		$postData['filterBody'] = array('searchText'=>$title,  'selectedDate' => date('Y-m-d'));
        
        if(!empty($_POST['courses']))
        {
            $postData['filterBody']['courses'] = $_POST['courses'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['filterBody']['instructors'] = $_POST['instructors'];
        }
            $postData['filterBody']['registrationDateRange']['startDate'] = $_POST['minReg'];
            $postData['filterBody']['registrationDateRange']['endDate'] =$_POST['maxReg'];
            $postData['filterBody']['createdDateRange']['startDate'] = $_POST['class_start_date'];
            $postData['filterBody']['createdDateRange']['endDate'] =$_POST['class_end_date'];
            $postData['filterBody']['classStates'] =$_POST['classStates'];
            $postData['filterBody']['creditHour']['min'] = $_POST['minRange'];
            $postData['filterBody']['creditHour']['max'] = $_POST['maxRange'];
        return $postData;
    }
    public function classesDataJS(){
        $siteURL= site_url();
        
    $postData = array();  
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = '';    
    $postData['pageNumber'] = 1;    
    $postData['sortDirection'] = "desc";
    $postData['filterBody'] = array('searchText'=>'','selectedDate' => date('Y-m-d'),'classStates'=>'');   
        $dataResponse = $this->submitApiRequest("Public/ClassPagingList", $postData, "POST", 'classes');
		//print_r(json_encode($dataResponse));
		//die;
        $collection   = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        $data         = array();

        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
		$dataJS='';
		$i=1;
         foreach ($collection as $key => $value) {
				 
       		 $dataJS.='<tr>';
            #nested data
            $nestedData = array();
			
            $instructorPopOver = '';
            $classPopover      = '';

            $class_icon = $value->parentCourse->iconReference;
            if($siteURL == "https://engagifiwebstg.wpengine.com/oresa" || $siteURL == "https://engagifiiweb.com/oresa" || $siteURL == "https://oconeeresa.org"){
                $class_icon = ENGAGIFII_ASSETS_URL.'/images/oconee-logo.png';
                
            }

            if(count($value->classInstructors)){
                $instructorPopOver = $this->_popOverInstructorData1($key, $value->classInstructors);
			}
            if(count($value->classSessions)){
                $classPopover  = $this->_popOverClassData1($key, $value->classSessions);
                
			}

            

            ## row data
            $class_schedule = '';
            $counter = 0; 
            if(count($value->classSessions))
            {
                foreach ($value->classSessions as $key => $rowData) {
            
                    $classSessionTime = '';
                    if( $counter == 0 ) {         
                        $classSessionStartTime = $rowData->startTime;
                        $classSessionStartDate = $rowData->sessionDate;
                    }                  
                    if( $counter == count( $value->classSessions ) - 1) {
                         $classSessionEndTime = $rowData->endTime;
                         $classSessionEndDate = $rowData->sessionDate;
                    }
                    //$classSessionTime = date('M d, Y', strtotime($rowData->sessionDate)).' At '.$classSessionStartTime.' - '.$classSessionEndTime;
                    $classSessionTime = date('M d, Y', strtotime($classSessionStartDate)).' - '.date('M d, Y', strtotime($classSessionEndDate));
                    $class_schedule = '<small class="d-block" style="white-space:normal;">'.$classSessionTime.' <br>'.$classSessionStartTime.'-'.$classSessionEndTime.'</small>';
                    $counter = $counter + 1;
                }
                $dataJS .= '<td><span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.$classes_detail_page_link.'?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'</div></div></td>';
            }else{
            $dataJS .= '<td><span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.$classes_detail_page_link.'?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'<small class="d-block" style="white-space:normal;">'.date('M d, Y', strtotime($value->startDate)).' at '.date('g:i A', strtotime($value->startDate)).' - '.date('g:i A', strtotime($value->endDate)).' </small></div></div></td>';
            }
            $dataJS .= '<td>'.$value->classDuration.' '.$value->classDurationType.'</td>';
            $dataJS .= '<td>'.$value->objectType.'</td>';
			

			if(count($value->classSessions)){
				 foreach ($value->classSessions as $key => $rowData) {
            
                    $classSessionTime = '';
                    if( $counter == 0 ) {         
                        $classSessionStartTime = $rowData->startTime;
                        $classSessionStartDate = $rowData->sessionDate;
                    }                  
                    if( $counter == count( $value->classSessions ) - 1) {
                         $classSessionEndTime = $rowData->endTime;
                         $classSessionEndDate = $rowData->sessionDate;
                    }
                    //$classSessionTime = date('M d, Y', strtotime($rowData->sessionDate)).' At '.$classSessionStartTime.' - '.$classSessionEndTime;
                    $classSessionTime = date('M d, Y', strtotime($classSessionStartDate)).' - '.date('M d, Y', strtotime($classSessionEndDate));
                    $class_schedule = '<small class="d-block" style="white-space:normal;">'.$classSessionTime.' <br>'.$classSessionStartTime.'-'.$classSessionEndTime.'</small>';
                    $counter = $counter + 1;
                }
            	 $dataJS .= '<td><span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->classSessions).'</span></div>'.$classPopover.'</div></td>';
			} else {
            $dataJS .= '<td><span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" ></td>';
			}
			
			if($value->classInstructorsCount>0){
				$dataJS .= '<td><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.($value->classInstructorsCount).'</span></div>'.$instructorPopOver.'</div></td>';  
			} else {
			$dataJS .='<td><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Instructors Available" ></td>';
			}
            if($value->isCreditTypeSingle =="true"){
            $dataJS .= '<td>'.number_format($value->courseCreditMapping[0]->credits, 2).'</td>';//($value->courseCreditMapping[0]->credits);          
            }else{
                $dataJS .= '<td>'.number_format($value->courseCreditMapping[0]->credits, 2).'</td>';      
            }

            $classTag = $value->classTag;
            $allTags = array();
            foreach ($classTag as $index => $tag) {
                
                    if(count($classTag) > 1 && $index == 0)
                    {   
                        $tagPopover =  $this->_popOverTagData1($key, $value->classTag);

                           $tagCount   = count($classTag) - 1;
                    
					$allTags[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$tag->tagName.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span>'.$tagPopover.'</div>';
                    }
                    elseif(count($classTag) == 1)
                        $allTags[] = $tag->tagName;
            }

            $dataJS .= '<td>'.implode(" ", $allTags).'</td>';
            if($value->isClassRegistrationAllow || $value->registrationWorkFlowId)
            {
              if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
              {
                   if($value->locationType->name=="onlocation")
                      { 
                      $dataJS .= '<td><a href="'.$value->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a></td>';
                      //$nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      }
                      elseif($value->locationType->name=="online"){
                          $dataJS .= '<td><a href="'.$value->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a></td>';
                      }
                      elseif($value->locationType->name=="onlocationandonline"){
                      $dataJS .= '<td><a style="white-space:nowrap" href="'.$value->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1 mb-2" target="_blank" >Register Online</a><br/><a style="white-space:nowrap" href="'.$value->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank" >Register in person</a></td>';
                      }
                  else{
                      $dataJS .= '<td><span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Class Location not defined"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span></td>';
                  }
              }
              else{
              $dataJS .= '<td><span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span></td>';
              }
          }else{
            $dataJS .= '<td><span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span></td>';
            //$data[] = $nestedData;    
          }
		  $dataJS .='</tr>';
          $data[] = $nestedData;
		  $i++;

      }
       
        $draw           = $_POST['draw'];
        $start          = $_POST['start']; //0, 5
        $length         = $_POST['length']; //5, 10 per page.

        $json_data = array(

            "draw" => intval($draw),
            "recordsTotal" => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            //"data" => $data,

            "data" => $dataJS,
        );
        echo json_encode($json_data);
		//$json_data = '<tr><td>11</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        //echo $json_data;
        wp_die();


    }
    /* Class calendar search grid end here*/
    public function courseLoadGridData(){
        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $courses_detail_page = $front_pages['courses_detail_page'];
	if($courses_detail_page){
		$courses_detail_page_link=get_permalink( $courses_detail_page );	
	}else{
		$courses_detail_page_link= site_url() .'/course-details/';	 
	}
        $postedData = $this->_prepareCoursePostData();
        $dataResponse = $this->submitApiRequest("Public/CoursePagingList/", $postedData, "POST", 'courses');
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        $request = $_GET;
        $data    = array();


        foreach ($collection as $key => $value) {
            # datatable
            $nestedData = array();
            $instructorPopOver = '';
            $classPopover      = '';
            
            if(count($value->certifiedInstructors))
                $instructorPopOver = $this->_popOverInstructorData($key, $value->certifiedInstructors);

            if(count($value->courseClasses))
                $classPopover   = $this->_popOverClassesData($key, $value->courseClasses);

            ## row data
            $nestedData['name'] = '<a class="d-flex align-items-center" href="'.$courses_detail_page_link.'?courseId='.$value->id.'"><img src="'.$value->courseIcon.'" class="img-fluid mr-3 img-icon-lg" alt="course-icon">'.$value->courseName.'</a>';
            $nestedData['objectType'] = $value->objectType;
            $nestedData['creditHours'] = $value->creditHours;
            $nestedData['instructor'] = '<div class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '""><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" alt="instructor-icon" class="img-icon-lg"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.($value->courseInstructorsCount ).'</span></div>'.$instructorPopOver;            
            $nestedData['class'] = '<div class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" alt="class-icon" class="img-icon-lg"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$value->courseClassesCount.'</span></div>'.$classPopover;
            $courseTag = $value->courseTags;
            $allTags = array();
            foreach ($courseTag as $index => $tag) {

                if(count($courseTag) > 1 && $index == 0)
                {   
                    $tagPopover =  $this->_popOverTagData($key, $courseTag);

                     $tagCount   = count($courseTag) - 1;
                    $allTags[] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;">'.$tag->tagName.'</div><span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-2  tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span></div>'.$tagPopover;
                }
                elseif(count($classTag) == 1)
                    $allTags[] = $tag->tagName;
            }
            $nestedData['courseTags'] = $allTags;
            
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
    public function courseLoadGridDataByPerson(){
        $options = get_option('ebt_api_settings');
        $front_pages = $options['front_pages'];
        $courses_detail_page = $front_pages['courses_detail_page'];
        $courses_detail_page_link= site_url() .'/engagifii-profile/my-transcript/course-details/';	 
		$classes_detail_page_link= site_url() .'/engagifii-profile/my-transcript/class-detail/';	
        
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$titleColumn = $_POST['titleColumn'];
		$title = $_POST['columns'][$titleColumn]['search']['value'];
		$sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
        $sortDirection = $_POST["order"][0]["dir"];
		$postedData = '{"itemCount":100,"pageNumber":1,"pageSize":10,"sortBy":"'.$sortBy.'","sortDirection":"'.$sortDirection.'","filterBody":{"filterRules":[],"searchText":"'.$title.'","startDate":"'.$startDate.'","endDate":"'.$endDate.'","groupById":"'.$_POST['profileId'].'","groupByType":3},"includeTotal":true}';
        $dataResponse = $this->submitApiRequest("CourseReport/CourseCreditPagingList", json_decode($postedData), "POST", 'mycourses');
		//print_r($postedData); die;
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        $request = $_GET;
        $data    = array();
		if($collection){
		$nestedData = array();
		$nestedData['course-select'] ='';	
				$nestedData['coursename'] ='<b>Total</b>';	
				$nestedData['coursetype'] ='';	
				$nestedData['classes'] ='';	
				$nestedData['completiondate'] ='';	
				$nestedData['totalcreditsearned'] ='<b>'.json_decode($dataResponse['api_response'])->grantedCredits.'/'.json_decode($dataResponse['api_response'])->totalCredit.'</b>';
				$nestedData['tags'] ='';
				$data[] = $nestedData;
		}
        foreach ($collection as $key => $value) {
			//print_r($value->course->name);
			//die;
            $nestedData = array();
            $instructorPopOver = '';
            $classPopover      = '';
            
            //if($count==1){
					
			//}

            ## row data
			$nestedData['course-select']='<input  type="checkbox" class="select-row" value="'.$value->id.'"/>';
            $nestedData['coursename'] = '<a class="d-flex align-items-center" href="'.$courses_detail_page_link.'?courseId='.$value->id.'"><img src="'.$value->icon->iconReference.'" class="img-fluid mr-3 img-icon-lg d-none" alt="course-icon">'.$value->name.'</a>';
            $nestedData['coursetype'] = $value->creditType->subObjectName;
            $nestedData['classes'] = '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" >';
			if(count($value->class)){
				$classPopover = dd_header('Classes','Search classes..');
				$subItems = "";
				$li=1;
				foreach ($value->class as $key => $rowData) {
					$classStart = $rowData->startDate;
					$classEnd = $rowData->endDate;
					$class='';
					if($li%2==1){
					  $class='bg-light';	
					}
					$subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'"><a href="'.$classes_detail_page_link.'?classId='.$rowData->id.'">'.$rowData->name.'</a><br>'.date('M d, Y', strtotime($classStart)).' at '.date('g:i A', strtotime($classStart)).' to '.date('M d, Y', strtotime($classEnd)).' at '.date('g:i A', strtotime($classEnd)).'</li>';
					$li++;
				}
				$classPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
            	 $nestedData['classes'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->class).'</span></div>'.$classPopover.'</div>';
			}
			
			$dt = new DateTime($value->grantedDate);
            $nestedData['completiondate'] =   $dt->format('M d, Y');
            $nestedData['totalcreditsearned'] = $value->grantedCredits."/".$value->totalCredit;
            $courseTag = $value->tags;
            $allTags = array();
            foreach ($courseTag as $index => $tag) {



                if(count($courseTag) > 1 && $index == 0)
                {   
                    $tagPopover =  $this->_popOverTagData($key, $courseTag);

                     $tagCount   = count($courseTag) - 1;
                    $allTags[] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;">'.$tag->tagName.'</div><span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-2  tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span></div>'.$tagPopover;
                }
                elseif(count($classTag) == 1)
                    $allTags[] = $tag->tagName;
            }
            $nestedData['tags'] = $allTags;
            
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

    //PeopleList
    public function peopleloadGridData(){
        $options = get_option('ebt_api_settings');
        $front_pages = $options['front_pages'];
        $courses_detail_page = $front_pages['courses_detail_page'];
        $courses_detail_page_link= site_url() .'/engagifii-profile/my-transcript/course-details/';	 
		$classes_detail_page_link= site_url() .'/engagifii-profile/my-transcript/class-detail/';	
        
		$postedData = $this->_preparePeopleData();
       // print_r($postedData); die;
        $dataResponse = $this->submitApiRequest("People/NewPeoplePagingList/", $postedData, "POST", 'dashboard');
		//print_r($postedData); die;
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        //print_r(json_encode($collection)); die;
        $request = $_GET;
        $data    = array();
		if($collection){
		$nestedData = array();
		$nestedData['people-select'] ='';	
				$nestedData['peoplename'] ='';	
				$nestedData['email'] ='';	
				$nestedData['currentposition'] ='';	
                $nestedData['currentdepartment'] ='';	
				$nestedData['persontype'] ='';
				$nestedData['organization'] ='';
                $nestedData['totaltimecommittiee'] ='';
                $nestedData['roles'] ='';
                $nestedData['totaltimeworked'] ='';


				$data[] = $nestedData;
		}
        foreach ($collection as $key => $value) {
			//print_r($value->course->name);
			//die;
            $nestedData = array();
            $instructorPopOver = '';
            $classPopover      = '';
            
            //if($count==1){
					
			//}

            ## row data
			$nestedData['people-select']='<input  type="checkbox" class="select-row" value="'.$value->people->id.'"/>';
            $nestedData['peoplename'] = '<img _ngcontent-c19="" alt="" class="img-circle img-xs mr-2 localImageURL" src="'.$value->people->imageThumbUrl.'"><a class="align-items-right" href="">'.$value->people->fullName.'</a>';
            $nestedData['email'] = $value->people->email;
            $nestedData['currentposition'] = '';
            $nestedData['currentdepartment'] ='';	
			$nestedData['persontype'] =$value->people->personTypes[0]->name;
			$nestedData['organization'] = $value->people->organization->name;
            $nestedData['totaltimecommittiee'] ='';
            $nestedData['roles'] ='';
            $nestedData['totaltimeworked'] ='';
			
            
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

    

   public function generateDownloadsByPerson(){
        
        $postedData = array();
		$postedData['itemCount']=0;
		$postedData['sortBy']='name';
		$postedData['sortDirection']='asc';
        //$postedData['sourceType']=2;
		$postedData['filterBody']['groupById']=$_POST['groupById'];
		$postedData['filterBody']['filterRules'][0]['fieldId']='courses';
		$postedData['filterBody']['filterRules'][0]['filterType']=1;
		$postedData['filterBody']['filterRules'][0]['selectedValues']=$_POST['CourseId'];;
		$postedData['filterBody']['startDate']='1970-04-01T11:50:40';
		$postedData['filterBody']['endDate']='2050-03-31T11:50:40';
		$postedData['filterBody']['groupByType']=3;
		//print_r(json_encode($postedData));
		//die;
        $dataResponse = $this->submitApiRequest("CourseReport/GenerateCreditsEarnedGroupByCoursesPDFReport", $postedData, "POST", 'reports');
        $collection = json_decode($dataResponse['api_response'])->result;
        echo json_encode($dataResponse);
        wp_die();
    }
	public function downloadDataByPerson(){
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
		$pageNumber = ($startPageNum);
		$sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
        $sortDirection = $_POST["order"][0]["dir"];
		$titleColumn = $_POST['titleColumn'];
		$title = $_POST['columns'][$titleColumn]['search']['value'];
		$postedData = '{"itemCount":10,"sortBy":"'.$sortBy.'","sortDirection":"'.$sortDirection.'","pageNumber":"'.$pageNumber.'","sourceType" :2,"filterBody":{"reportName":"'.$title.'","status":[],"fromDate":"","toDate":""}}';
        $dataResponse = $this->submitApiRequest("exportpeople/allreport", json_decode($postedData), "POST", 'dashboard');
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        $request = $_GET;
        $download_icon = ENGAGIFII_ASSETS_URL.'/images/ellipses-gray.png';
        $data    = array();
        foreach ($collection as $key => $value) {
            $nestedData = array();
            
			if($value->reportLink){
			$nestedData['download-select']='<div class="dropdown"><button type="button"  class="btn btn-sm shadow-none" data-toggle="dropdown"><img src="'.$download_icon.'" class="img-icon-lg rounded-circle img-fluid mr-2" alt="downlaod" style="width:20px;"/ ></button><div class="dropdown-menu py-1"><a class="dropdown-item px-2" target="_blank" href="'.$value->reportLink.'">Download</a><a class="dropdown-item px-2 deleteReport" href="" report-id="'.$value->id.'">Delete</a></div></div>';
            $nestedData['filename'] = '<a class="d-flex align-items-center" target="_blank" href="'.$value->reportLink.'">'.$value->reportName.'</a>';
			}else{
			$nestedData['download-select']='<div class="dropdown"><button type="button" report-id="'.$value->id.'" class="btn btn-sm shadow-none" data-toggle="dropdown"><img src="'.$download_icon.'" class="img-icon-lg rounded-circle img-fluid mr-2" alt="downlaod" style="width:20px;"/ ></button><div class="dropdown-menu py-1"><a class="dropdown-item px-2 deleteReport" href="" report-id="'.$value->id.'">Delete</a></div></div>';
            $nestedData['filename'] = $value->reportName;
			}
            $nestedData['requested'] = date('M d, Y', strtotime($value->createdDate)).' at '.date('g:i A', strtotime($value->createdDate));
			if($value->status=='success'){
				$status = '<span class="text-success">'.ucfirst($value->status).'</span>';	
			}else if($value->status=='failed'){
				$status = '<span class="text-danger">'.ucfirst($value->status).'</span>';
			} else if($value->status=='inprogress'){
                $statusInPrgoress = "In Progress";
				$status = '<span class="text-warning">'.ucfirst($statusInPrgoress).'</span>';
			} else {
				$status = '<span class="text-dark">'.ucfirst($value->status).'</span>';
			}
            $nestedData['status'] = $status;
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
	public function clearDownloadsByPerson(){
		$postedData =array();
		$reportid = $_POST['reportid'];
		if($reportid){
       	 $dataResponse = $this->submitApiRequest("exportpeople/get/deletebyreportid?=".$reportid, $postedData, "GET", 'dashboard');
		}else{
       	 $dataResponse = $this->submitApiRequest("exportpeople/get/clearallbyuser", $postedData, "GET", 'dashboard');
		}
        $response = json_decode($dataResponse['api_response']);
        return $response;
        wp_die();
    }
	public function allReportsByPerson(){
		$postedData =array();
		if($_POST['awardId']){
       	 $dataResponse = $this->submitApiRequest("Awards/generateCertificationPDFReport/".$_POST['profileId']."/".$_POST['awardId']."", $postedData, "GET", 'awards');
		}else{
       	 $dataResponse = $this->submitApiRequest("Awards/GenerateAllCertificationPDFReport/".$_POST['profileId']."", $postedData, "GET", 'awards');
		}
        $response = json_decode($dataResponse['api_response']);
        return $response;
        wp_die();
    }
	 public function endorsementLoadGridData(){
        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $endorse_detail_page = $front_pages['endorse_detail_page'];
	if($endorse_detail_page){
		$endorse_detail_page_link=get_permalink( $endorse_detail_page );	
	}else{
		$endorse_detail_page_link= site_url() .'/endorsement-detail/';	 
	}
        $postedData = $this->_preparePostData();
		$dataResponse = $this->submitApiRequest("Public/AwardListPublic/", $postedData, "POST", 'endorsement');
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;

        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
        $endorsement_visib_datacol_list = $options['endorsement_visib_datacol_list'];

        $data = array();

        foreach ($collection as $key => $row) {
           
            /* getdata for tables */
            $nestedData = array();

            $default_Title = $row->name;
            $default_Id = $row->id;
            $default_Detailpage = "";
            $default_Detailpage .= '<div class="d-flex align-items-center"><img src="'.$row->icon.'" class="img-fluid img-icon-lg p-0 mr-3" alt="award-icon"><a href=' . $endorse_detail_page_link.'?endId=' . $default_Id . ' >' . $default_Title . '</a></div>';
            if ($default_Title) {
                $nestedData['name'] = $default_Detailpage;
            }else{
                $nestedData['name'] = "N/A";
            }

            $nestedData['price'] = '$'.$row->price;
            $nestedData['objectType'] = "<span class='text-center'>".$row->objectType."</span>";
            
            $default_Date = $row->createdOn;
            $convert_Date = strtotime($default_Date);
            $new_Date = date('M d, Y', $convert_Date);
            if($row->createdBy->imageThumbUrl)
            {
                if (filter_var($row->createdBy->imageThumbUrl, FILTER_VALIDATE_URL)) { 
                    $instructor_img = $row->createdBy->imageThumbUrl;
                }
                else
                {
                    $instructor_img = $tenant_url.$row->createdBy->imageThumbUrl;
                }
                
            }
            else
            {
                $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

            }
            $nestedData['createdOn'] = '<div class="d-flex align-items-center"><img src="'.$instructor_img.'" class="img-icon-lg rounded-circle img-fluid mr-2" alt="instructor-img"><div class="text-left">'.$row->createdBy->name.'<small class="d-block">'.$new_Date.'</small></div>';
            
            $nestedData['validity'] = $row->validity;
            
            $default_Courses = $row->courses;
            if ($default_Courses) {
                $nestedData['courseCount'] = $default_Courses;
            }else{
                $nestedData['courseCount'] = '<div class="course-badge"><img src="'.ENGAGIFII_ASSETS_URL.'/images/course-icon.png" class="img-circle img-fluid" alt="course-icon"></div>';
            }
            
            $default_Register = $row->register;
            $default_RegisterBtn = "";
            $default_RegisterBtn .= '<a href="'.$tenant_url.'/pages/awards/'. $default_Id .'/signup/overview" target="_blank" class="btn btn-primary px-3 py-1" >Register</a>';
            if ($default_Register) {
                $nestedData['register'] = $default_RegisterBtn;
            }else{
                $nestedData['register'] = $default_RegisterBtn;
            }
            
            $default_Tags = array();
            if (count($row->tags)) {

                $allTags = array();
                
                foreach ($row->tags as $index => $tag) {

                    $default_Tags[$index]->tagName = $tag;
                    $default_Tags[$index]->id =$index;
                }
                
                foreach ($default_Tags as $index => $value) {

                    if(count($default_Tags) > 1 && $index == 0)
                    {   
                        $tagPopover =  $this->_popOverTagData($key, $default_Tags);
                         $tagCount   = count($default_Tags) - 1;
                    $allTags[] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;">'.$value->tagName.'</div><span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-2  tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span></div>'.$tagPopover;
                    }
                    elseif(count($default_Tags) == 1)
                        $allTags[] = $value->tagName;

                }
                $nestedData['tags'] = $allTags;
            }else{
                $nestedData['tags'] = "";
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


    // Events Grid Data
    public function eventsLoadGridData(){
        $postedData = $this->_prepareEventsData();
        $dataResponse = $this->submitApiRequest("public/listEventsByFilter", $postedData, "POST", 'event');
        $collection = json_decode($dataResponse['api_response'])->collection;
        $totalcount   = json_decode($dataResponse['api_response'])->pagingModel->totalRecords;
        
        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
	    $front_pages = $options['front_pages'];
        $events_detail_page = $front_pages['events_detail_page'];
	    if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	    }else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	    }
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii-preview4.com';
        $endorsement_visib_datacol_list = $options['endorsement_visib_datacol_list'];

        $data = array();

        foreach ($collection as $key => $row) {
           
            /* getdata for tables */
            $nestedData = array();
            $contactPopOver = '';
           $locationPopOver      = '';

           if(count($row->eventDates)) {
                $locationPopOver  = $this->_popOverLocationData($key, $row->eventDates);
			}
            $locationCount = 0 ;
            foreach($row->eventDates as $key => $location){

                if($location->city){
                    $locationCount = $locationCount+1;
                }
            }
           
            $default_Title = $row->name;
            $default_Id = $row->id;
            $default_Detailpage = "";
            $default_Detailpage .= '<div class="d-flex align-items-center"><img src="'.$row->imageUrl.'" class="img-fluid img-icon-lg mr-3" alt="award-icon"><a href=' .$events_detail_page_link.'?endId=' . $default_Id . ' >' . $default_Title . '</a></div>';
            if ($default_Title) {
                $nestedData['name'] = $default_Detailpage;
            }else{
                $nestedData['name'] = "N/A";
            }

            $nestedData['eventType'] = $row->eventType;
            $nestedData['eventWithClass'] = "<span class='text-center'>".$row->eventWithClass."</span>";
            $nestedData['class'] = '<div class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" alt="class-icon" class="img-icon-lg"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$value->courseClassesCount.'</span></div>'.$classPopover;
            $default_Date = $row->createdOn;
            $convert_Date = strtotime($default_Date);
            $new_Date = date('M d, Y', $convert_Date);
            if($row->createdBy->imageThumbUrl)
            {
                if (filter_var($row->createdBy->imageThumbUrl, FILTER_VALIDATE_URL)) { 
                    $instructor_img = $row->createdBy->imageThumbUrl;
                }
                else
                {
                    $instructor_img = $tenant_url.$row->createdBy->imageThumbUrl;
                }
                
            }
            else
            {
                $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

            }
            
            if($locationCount>0){
                $nestedData['city'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$locationCount.'</span></div>'.$locationPopOver.'</div>';

            }
            else{
            $nestedData['city'] = '<div class="dropdown"><div class=" instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter: grayscale(1);"><span style="visibility: hidden;" class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"></span></div></div>';
            }
            $nestedData['eventDates'] = $row->startDateTime; //eventDates
            if($row->startDateTime){
                $default_Date = $row->startDateTime;
                $convert_Date = strtotime($default_Date);
                $startdate = date('M d, Y', $convert_Date);
                $starttime = date('g:i A', $convert_Date);
                //$starttime = ltrim($starttime, '0');
            }
            if($row->endDateTime){
                $default_Date = $row->endDateTime;
                $convert_Date = strtotime($default_Date);
                $enddate = date('M d, Y', $convert_Date);
                $endtime = date('g:i A', $convert_Date);
                //$endtime = ltrim($endtime, '0');
                
            }
            $nestedData['startDateTime'] ='<span style="display:none;">'.strtotime($startdate).'</span>'. $startdate." at ".$starttime." - ".$enddate." at ".$endtime ;
            
            $default_Courses = $row->courses;
            if ($default_Courses) {
                $nestedData['courseCount'] = $default_Courses;
            }else{
                $nestedData['courseCount'] = '<div class="course-badge"><img src="'.ENGAGIFII_ASSETS_URL.'/images/course-icon.png" class="img-circle" alt="course-icon"></div>';
            }
            
            $event_status = $row->eventStatus;
            $event_status = preg_replace('/(?<!\ )[A-Z]/', ' $0', $event_status);
			 $nestedData['eventStatus'] = $event_status;
            $registration_state = $row->eventRegistrationState;
            $default_RegisterBtn = "";
            if ($event_status == 'Completed' || $registration_state == 'RegistrationClosed' || $registration_state == 'RegistrationNotStarted') {
                $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $row->eventRegistrationState);
                $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
            }
            else{
                $default_RegisterBtn .= '<a href="'.$tenant_url.'/pages/events/'. $default_Id .'/general" target="_blank" class="btn btn-primary px-3 py-1" >Register</a>';
            }
            
           
           
                $nestedData['register'] = $default_RegisterBtn;
           
            $filter = $row->tags;
            $allTags = array_diff($filter, array('PUBLIC', 'public', 'Public'));
            $filterTag = array_values($allTags);
            $default_Tags = array();
            if (count($filterTag)) {

                $allTags = array();
                
                foreach ($filterTag as $index => $tag) {

                    $default_Tags[$index]->tagName = $tag;
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
               $nestedData['tags'] = $allTags;
            }else{
                $nestedData['tags'] = "";
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

//events filters
public function eventFilters(){
	$postData=array();
	$htmlArray = array();
	  $filterParams = $_POST['filterParams'];
	  $apiUrl='';
	  $date = date('Y-m-d');
	  foreach ($filterParams as $keys => $values) {
		  if($values =='startDateTime'){
			$apiUrl='event/GetMinMaxEventDate/'.$date; 
		  }else if($values =='tags'){
			$apiUrl='public/tags/1/1';  
		  }else if($values =='eventType'){
			$apiUrl='public/event-type';  
		  }else if($values =='city'){
			$apiUrl='public/venues';  
		  }
		  $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'event');
		  if($response['api_response']){
			$response = json_decode($response['api_response'], true);
			if($response){
			  foreach ($response as $key => $value) {
				  if($values=='startDateTime'){
					  $html[$values]['minStartDate']=date('m/d/Y',strtotime($response['minStartDate']));		
					  $html[$values]['maxEndDate']=date('m/d/Y',strtotime($response['maxEndDate']));	;		
				  }else if($values =='tags'){
					  $html[$values].='<li class="d-flex align-items-start"><input id="tag_'.$key.'" class="mr-2 mt-1" type="checkbox" name="eventsTags[]" value="'.$value['id'].'"> <label class="" for="tag_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';	
				  }else if($values =='city'){
					  $html[$values].= '<li class="d-flex align-items-start"><input  type="checkbox" name="eventsLocation[]" id="location_'.$key.'" value="'.$value['id'].'" class="mr-2 mt-1"> <label for="location_'.$key.'"><small>'.addslashes($value['city']).'</small></label></li>';	
				  }else if($values=='eventType'){
					$html[$values].= '<li class="d-flex align-items-start"><input type="checkbox" name="eventsType[]" id="event_'.$key.'" value="'.$value['value'].'" class="mr-2 mt-1"> <label for="event_'.$key.'"><small> '.addslashes($value['text']).'</small></label></li>';
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


    //Load event list by person
    public function eventsLoadGridDataByPerson(){
    $options = get_option('ebt_api_settings');
    $loggedInUserId = $_SESSION['pid'];
    $tenantCode = $options['ebt_tenant_code']['tenant_code'];

    //print_r($loggedInUserId); die;
	
	$front_pages = $options['front_pages'];
    $events_detail_page = $front_pages['events_detail_page'];
	$events_detail_page_link= site_url() .'/engagifii-profile/events/event-detail/';	 
	     $postedData = $this->_prepareEventsData();
		// print_r(json_encode($postedData));
		//die;
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
        $dataResponse = $this->submitApiRequest("event/list", $postedData, "POST", 'event');
		 //print_r($userPermissionArray);
		 //die;
        //if(in_array('sessions', $class_visible_column_list))
        $dataResponse = $this->submitApiRequest("event/list", $postedData, "POST", 'event');
		 //print_r($dataResponse['api_response']);
		 //die;
        $collection = json_decode($dataResponse['api_response'])->collection;
        $totalcount   = json_decode($dataResponse['api_response'])->pagingModel->totalRecords;
        
        header("Content-Type: application/json");
        $request = $_GET;

        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii-preview4.com';
        $endorsement_visib_datacol_list = $options['endorsement_visib_datacol_list'];

        $data = array();
        foreach ($collection as $key => $row) {
           
            /* getdata for tables */
            $nestedData = array();
            $contactPopOver = '';
           $locationPopOver      = '';

           if(count($row->eventDates)) {
                $locationPopOver  = $this->_popOverLocationData($key, $row->eventDates);
			}
            $locationCount = 0 ;
            foreach($row->eventDates as $key => $location){

                if($location->city){
                    $locationCount = $locationCount+1;
                }
            }
           
            $default_Title = $row->name;
            $default_Id = $row->id;
            $default_Detailpage = "";
            $default_Detailpage .= '<div class="d-flex align-items-center"><img src="'.$row->imageUrl.'" class="img-fluid img-icon-lg mr-3" alt="award-icon"><a href=' . $events_detail_page_link.'?endId=' . $default_Id . '&wId='.$row->registrationWorkflows[0]->registrationWorkflowId.'&rId='.$row->registrationWorkflows[0]->roleId.' >' . $default_Title . '</a></div>';
            if ($default_Title) {
                $nestedData['name'] = $default_Detailpage;
            }else{
                $nestedData['name'] = "N/A";
            }

            $nestedData['eventType'] = $row->eventType;
            $nestedData['eventWithClass'] = "<span class='text-center'>".$row->eventWithClass."</span>";
            $nestedData['class'] = '<div class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" alt="class-icon" class="img-icon-lg"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$value->courseClassesCount.'</span></div>'.$classPopover;
            $default_Date = $row->createdOn;
            $convert_Date = strtotime($default_Date);
            $new_Date = date('M d, Y', $convert_Date);
            if($row->createdBy->imageThumbUrl)
            {
                if (filter_var($row->createdBy->imageThumbUrl, FILTER_VALIDATE_URL)) { 
                    $instructor_img = $row->createdBy->imageThumbUrl;
                }
                else
                {
                    $instructor_img = $tenant_url.$row->createdBy->imageThumbUrl;
                }
                
            }
            else
            {
                $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

            }
            
            if($locationCount>0){
                $nestedData['city'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$locationCount.'</span></div>'.$locationPopOver.'</div>';

            }
            else{
            $nestedData['city'] = '<div class="dropdown"><div class=" instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter: grayscale(1);"><span style="visibility: hidden;" class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"></span></div></div>';
            }
            $nestedData['eventDates'] = $row->startDateTime; //eventDates
            if($row->startDateTime){
                $default_Date = $row->startDateTime;
                $convert_Date = strtotime($default_Date);
                $startdate = date('M d, Y', $convert_Date);
                $starttime = date('h:i A', $convert_Date);
                $starttime = ltrim($starttime, '0');
            }
            if($row->endDateTime){
                $default_Date = $row->endDateTime;
                $convert_Date = strtotime($default_Date);
                $enddate = date('M d, Y', $convert_Date);
                $endtime = date('h:i A', $convert_Date);
                $endtime = ltrim($endtime, '0');
            }
            $nestedData['startDateTime'] ='<span style="display:none;">'.strtotime($startdate).'</span>'. $startdate." at ".$starttime." - ".$enddate." at ".$endtime ;
            
            $default_Courses = $row->courses;
            if ($default_Courses) {
                $nestedData['courseCount'] = $default_Courses;
            }else{
                $nestedData['courseCount'] = '<div class="course-badge"><img src="'.ENGAGIFII_ASSETS_URL.'/images/course-icon.png" class="img-circle" alt="course-icon"></div>';
            }
            
            $event_status = $row->eventStatus;
            $isAlreadyRegistered = $row->registrationWorkflows[0]->isAlreadyRegistered;
            $nestedData['allReadyRegistered'] = $isAlreadyRegistered;
            $event_status = preg_replace('/(?<!\ )[A-Z]/', ' $0', $event_status);
			 $nestedData['eventStatus'] = $event_status;
            $registration_state = $row->eventRegistrationState;
            $default_RegisterBtn = "";
            if (($event_status == 'Completed' || $registration_state == 'RegistrationClosed' || $registration_state == 'RegistrationNotStarted' || $registration_state == 'RegistrationScheduled') && ($registerOverride=='false')) {
				if($registration_state == 'RegistrationScheduled'){
                	$tooltip = 'Registration opens from '.date('M d, Y', strtotime($row->registrationStartFrom));
               		$default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
				}else{
                $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $row->eventRegistrationState);
                $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
				}
            }
            else if (($isAlreadyRegistered) && ($registerOthers=='false')) {
                $alreadyRegisteredText = "Already Registered";
                $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $alreadyRegisteredText);
                $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
            }
            else{
				if($row->registrationWorkflows[0]->registrationWorkflowId==''){
                    $tooltip = 'You are not authorized to register for this event. Please contact the event contact.';
                     $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                  }else{
                    if($row->registrantsCapacity > $row->attendeesCount){
                        $url = 'https://psba.engagifii-preview4.com/auth-callback/pages/home#access_token='.$_SESSION['accesstoken'].'&source=external&tpath=pages/events/'. $default_Id .'/'.$row->registrationWorkflows[0]->registrationWorkflowId.'/'.$row->registrationWorkflows[0]->roleId.'/eventregpub/signup/overview';
                        $default_RegisterBtn .= '<button data-url="'.$url.'"  class="btn btn-primary px-3 py-1 open-pop" >Register</button>';
                    }else{                        
                        $tooltip = 'Sold Out';
                        $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                    }
                 
                  }
				//$url = 'https://psba.engagifii-preview4.com/auth-callback/pages/home#access_token='.$_SESSION['accesstoken'].'&source=external&tpath=pages/events/'. $default_Id .'/'.$row->registrationWorkflows[0]->registrationWorkflowId.'/'.$row->registrationWorkflows[0]->roleId.'/eventregpub/signup/overview';
				//$default_RegisterBtn .= '<button data-url="'.$url.'"  class="btn btn-primary px-3 py-1 open-pop" >Register</button>';
            }
            
           
           
                $nestedData['register'] = $default_RegisterBtn;
           
            $filter = $row->tags;
            $allTags = array_diff($filter, array('PUBLIC', 'public', 'Public'));
            $filterTag = array_values($allTags);
            $default_Tags = array();
            if (count($filterTag)) {

                $allTags = array();
                
                foreach ($filterTag as $index => $tag) {

                    $default_Tags[$index]->tagName = $tag;
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
               $nestedData['tags'] = $allTags;
            }else{
                $nestedData['tags'] = "";
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

    // Events Grid End here

    public function legislationLoadGridData() {

        $postedData = $this->_prepareLegislationPostData();
		$dataResponse = $this->submitApiRequest("legislative/public-bills/list",$postedData,"POST",'legislation');
        $collection = json_decode($dataResponse['api_response']);
        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $bills_page = $front_pages['bills_page'];
    $bills_detail_page = $front_pages['bills_detail_page'];
	if($bills_page){
	$bill_page_link=get_permalink( $bills_page );	
	}else{
	$bill_page_link= site_url() .'/bill-tracking/';	
	}
	if($bills_detail_page){
	$bill_detail_link=get_permalink( $bills_detail_page );	
	}else{
	$bill_detail_link= site_url() .'/engagifii-detail/';	
	}
        $lbt_api_url = $options['lbt_api_url'];

        $lbt_visib_datacol_list = $options['lbt_visib_datacol_list'];

        $data = array();
        $sponsorsName = array();
        $bill_array   = array();
        foreach ($collection->collection as $key => $row) {

            $nestedData = array();
			if($row->lastActionOn==null){
    $lastActionOnnew_Date ='';
}else{
    $lastActionOndefault_Date = $row->lastActionOn;
    $lastActionOnconvert_Date = strtotime($lastActionOndefault_Date);
    $lastActionOnnew_Date = date('M d, Y', $lastActionOnconvert_Date);
}

            $default_Date = $row->introducedDate;
            $convert_Date = strtotime($default_Date);
            $new_Date = date('M d, Y', $convert_Date);

            $bill_array[$key] = $row->id;
            
            $billHtml = "";

            if(isset($options['lbt_title_display_setting']))
            {
                if($options['lbt_title_display_setting'] == 'alternate'){
                    if($row->alternateTitle){
                        $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id .' >' . $row->alternateTitle . '</a>';
                    }
                    else
                    {
                        $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id . ' >' . $row->title . '</a>';
                    }
                    

                }elseif($options['lbt_title_display_setting'] == 'alternate-top'){
                    if($row->alternateTitle){
                        $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id .' >' . $row->alternateTitle . '<br/>'.$row->title.'</a>';
                    }
                    else
                    {
                        $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id .' >' . $row->title . '</a>';
                    }

                }elseif($options['lbt_title_display_setting'] == 'title-top'){
                    $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id .' >' . $row->title . '<br/>'.$row->alternateTitle.'</a>';

                }
                elseif($options['lbt_title_display_setting'] == 'title'){
                    $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id . ' >' . $row->title . '</a>';
                }
            }
            else
            {
                $billHtml = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id . ' >' . $row->title . '</a>';
            }
            
            $pdf = '<a href=' . $lbt_api_url . '/file/' . $row->fileId . '> <img class="full-text-img" alt="pdf-icon" src="' . ENGAGIFII_ASSETS_URL . '/images/pdf.png' . '"> </a>';

            if (!empty($row->sponsors)) {
                if (count($row->sponsors) > 1) {
                    
                    if ($row->sponsors[0]->profilePic) {
                        $pichere = $row->sponsors[0]->profilePic;
                    } else {
                        $pichere = ENGAGIFII_ASSETS_URL . '/images/staff-list-grey.png';
                    }

                    $personLists = $this->_popOverHtml1($row->id, $row->sponsors);
                    $countSponsors = count($row->sponsors) - 1;
                    $sponsors_string = '<span class="col pl-0 pr-2"> '.$row->sponsors[0]->name . '</span>' . '<span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle   sponsors-click_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $countSponsors . ' </span>' . $personLists;

                    $nestedData["sponsors"] = '<div class="dropdown pr-4 text-left d-flex align-items-center"><span class="user-image-square overflow-hidden rounded-circle mr-2"><img class="img-fluid" src = ' . $pichere . ' alt="'.$row->sponsors[0]->name.'" > </span> ' . $sponsors_string . '</div>';
					
					
                } else {
                     if ($row->sponsors[0]->profilePic) {
                        $pichere = $row->sponsors[0]->profilePic;
                    } else {
                        $pichere = ENGAGIFII_ASSETS_URL . '/images/staff-list-grey.png';
                    }
                    $nestedData["sponsors"] = '<div class="sponsors-middle position-relative d-inline-flex align-items-center"><span class="user-image-square overflow-hidden rounded-circle mr-2"><img class="img-fluid" src = ' . $pichere . ' alt="'.$row->sponsors[0]->name.'"> </span> ' . '<span class="col pl-0 pr-2"> '.$row->sponsors[0]->name . '</span>' . '</div>';
					
					

                }
            } else {
                $nestedData["sponsors"] = "None";
            }

            $nestedData["trackingLevelColorCode"] = $row->trackingLevelColorCode;
            $nestedData["BillType"] = $row->billTypeAbbr;
            $nestedData["billNumber"] = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id . ' >'.$row->billNumber.'</a>';
            $nestedData["state"] = $row->state;
            $nestedData["fileId"] = $pdf;
            $nestedData["title"] = $billHtml;
            $nestedData["IntroducedDate"] = $new_Date;
            $nestedData["status"] = $row->status;
            $nestedData["lastActionTaken"] = $row->lastActionTaken;
            

            $billHtml1 = '<span class="rounded-circle d-inline-block mr-1" style="background-color:'.$row->trackingLevelColorCode.'; width: 13px;height: 13px;"></span>';

            $nestedData["trackingLevel"] = $billHtml1 .$row->trackingLevel;

            $nestedData["lastActionOn"] = $lastActionOnnew_Date.'<br/>'.$row->lastActionTaken;

            if (count($row->houseCommittees) > 0) {
                    if(count($row->houseCommittees) > 1)
                    {
                        $houseLists = $this->_popOverHouseHtml($row->id, $row->houseCommittees);
                        $houseCommitteesCount = count($row->houseCommittees) - 1;
                        if(strlen($row->houseCommittees[0]) > 7)
                        {
                            $house_name = substr($row->houseCommittees[0], 0, 7).'...';
                        }
                        else{
                            $house_name = $row->houseCommittees[0];
                        }
                        $houseCom = '<div class="flex-1" > '.$house_name . '</div>' . '<span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-2  house_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $houseCommitteesCount . ' </span>' . $houseLists;


                        $nestedData["houseCommittees"] = $houseCom;
                    }
                    else
                    {
                        $nestedData["houseCommittees"] = '<p style="white-space:normal;">'.$row->houseCommittees[0].'</p>';
                    }

            } else {
                $nestedData["houseCommittees"] = '<em class="text-muted">None</em>';
            }

            if (count($row->senateCommittees) > 0) {
                if(count($row->senateCommittees) > 1)
                {
                    $senateLists = $this->_popOverSenateHtml($row->id, $row->senateCommittees);
                        $senateCommitteesCount = count($row->senateCommittees) - 1;
                         if(strlen($row->senateCommittees[0]) > 7)
                        {
                            $senate_name = substr($row->senateCommittees[0], 0, 7).'...';
                        }
                        else{
                            $senate_name = $row->senateCommittees[0];
                        }

                        $senateCom = '<div class="d-flex justify-content-center"><div class="flex-1"> '.$senate_name . '</div>' . '<span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-2  senate_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $senateCommitteesCount . ' </span></div>' . $senateLists;


                        $nestedData["senateCommittees"] = $senateCom;
                }
                else
                    $nestedData["senateCommittees"] = '<p style="white-space:normal;">'.$row->senateCommittees[0].'</p>';
            } else {
                $nestedData["senateCommittees"] = '<em class="text-muted">None</em>';
            }
            if(count($row->assignedTo)){
                if(count($row->assignedTo) > 1){
                    $assignCount              = count($row->assignedTo) - 1;
                    $assignList               = $this->_popOverAssignHtml1($row->id, $row->assignedTo);
					$nestedData['assignedto'] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2"> '.$row->assignedTo[0].'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle  assign_'.$row->id.'" data-containerid="' . $row->id . '" id=' . $row->id . '> +' .$assignCount .'</span>'.$assignList.'</div>';
                }
                else{
                    $nestedData['assignedto'] = $row->assignedTo[0];
                }
            }
            else
                $nestedData['assignedto'] = '<em class="text-muted">None</em>';

            if(count($row->tags)){
              
                if(count($row->tags) > 1){
                    $tagCount              = count($row->tags) - 1;
                  
                    $tagList               = $this->_popoverTagsHtml1($row->id, $row->tags);
                    $nestedData['tags'] = '<div class="dropdown pr-4"><span class="d-inline-block pr-2"> <a href="' . $bill_page_link.'?tag='.$row->tags[0]->value.'&'.base64_encode($row->tags[0]->text).'">'.$row->tags[0]->text.'</a></span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_leg_'.$row->id.'" data-placement="left" data-containerid="' . $row->id . '" id="' . $row->id . '"> +' . $tagCount .'</span>'.$tagList.'</div>';
                }else{
                    
                    $nestedData['tags']       = '<a href="' . $bill_page_link.'?tag='.$row->tags[0]->value.'&'.base64_encode($row->tags[0]->text).'">'.$row->tags[0]->text;
                }
            }
            else
                $nestedData['tags']       = '';

            $data[] = $nestedData;
        }
         
        setcookie('filterids', json_encode($bill_array), time() + 3600, '/');

        $totalRecords = $collection->pagingModel->totalRecords;
        $totalData = $totalRecords;
        $draw = $_POST['draw'];
        $start = $_POST['start']; //0, 5
        $length = $_POST['length']; //5, 10 per page.

        $json_data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            "data" => $data,
        );

        echo json_encode($json_data);
        wp_die();
    }

//Public official Datatable
public function publicOfficialTabs(){
		$postData='{}';
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/elected/officials-all-tabs-list';
		$response =  $this->submitApiRequest($apiUrl, json_decode($postData), 'POST', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		if($responseArray){
			$html =array();
		  $i=1; 
				  foreach ($responseArray as $key => $value) {
					  if($key=='stateSenateMemberList'){
						  $name='State Senate';
					  }
					  elseif($key=='stateHouseMemberList'){
						  $name='State House';
					  }
					  elseif($key=='stateSenateCommittees'){
						  $name='State Senate Committees';
					  }
					  elseif($key=='stateHouseCommittees'){
						  $name='State House Committees';
					  }
					  elseif($key=='countyDeligationList'){
						  $name='County Delegations';
					  }
					  elseif($key=='congressionalDelegationMemberList'){
						  $name='Congressional Delegations';
					  }else{
						  $name=$Key;
					  }
					  $class=''; 
					  $tabClass ='';
					  if($i==1){
						  $class =' active';
						  $tabClass =' show active';
					  }
			  $html['tabName'].= '<li class="nav-item mr-3 mb-3" role="presentation">
			  <button class="border-dark nav-link bg-transparent'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$name.' <b>('.count($value).')</b></button></li>';
			  $html['tabContent'].='<div class="tab-pane fade '.$tabClass.'" id="tab-'. $i.'" role="tabpanel" data-tab="'.$key.'"></div>';
		   $i++; 
		   }
 		} else {
			$html = '<h6 class="text-center">data not found</h6>';		
		}
		echo json_encode($html);
        wp_die();
}
  public function poFilter(){
	$postData=array();
	$htmlArray = array();
	  $filterParams = $_POST['filterParams'];
	  $apiUrl='';
	  foreach ($filterParams as $key => $values) {
		  if($values =='City of Residence'){
			$apiUrl='legislative/public-bills/residence-list';  
		  }else if($values =='Committies'){
			$apiUrl='legislative/public-bills/committee-list';  
		  }else if($values =='Counties'){
			$apiUrl='legislative/public-bills/county-list';  
		  }else if($values =='District'){
			$apiUrl='legislative/public-bills/districtname-list';  
		  }else if($values =='Role'){
			$apiUrl='legislative/public-bills/legislative-role';  
		  }else if($values =='Political Party'){
			$apiUrl='legislative/public-bills/political-party';  
		  }
		  $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		  if($response['api_response']){
			$response = json_decode($response['api_response'], true);
			$html ='';
			foreach ($response as $key => $value) {
				if($values=='Counties'){
					$chkd = $value['text'];	
				}else{
					$chkd = $value['value'];	
				}
				$html.= '<li><div class="form-check"><input class="form-check-input" type="checkbox" value="'.$chkd.'" id="'.str_replace(array( ' ', ',' ), '', strtolower($value['value'])).'"><label class="form-check-label" for="'.str_replace(array( ' ', ',' ), '', strtolower($value['value'])).'"><small>'.$value['text'].'</small></label></div></li>';
			  }
	  		} else {
				$html='<h6 class="text-center mt-3">data not found</h6>';	
			}
			$htmlArray[]=$html;
	  }
		echo json_encode($htmlArray);
        wp_die();
} 
 public function publicOfficialLoadData(){
        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $public_official_detail_page = $front_pages['public_official_detail_page'];
	if($public_official_detail_page){
		$public_official_detail_page_link=get_permalink( $public_official_detail_page );	
	}else{
		$public_official_detail_page_link= site_url() .'/public-official-detail/';	
	}
$seqColumns=['Name','Counties','City of Residence','District','Committees','Party','Role'];
$tableHeader='';
foreach ($seqColumns as $key => $value) {
	$tableHeader.='<th class="'.str_replace(' ', '', strtolower($value)).'Col">'.$value.'</th>';
}
        $siteURL= site_url();
        $postedData = $this->_publicOfficialCount();
        $postedTab  = $_POST['tab'];
		$postedTabCount  = $_POST['tabCount'];
         $dataResponse = $this->submitApiRequest("legislative/public-bills/elected/officials-all-tabs-list", $postedData, "POST", 'legislation');
       	if($postedTab=='stateSenateCommittees' || $postedTab=='stateHouseCommittees' || $postedTab=='countyDeligationList'){
			if($postedTab=='countyDeligationList'){
			   $collection   = json_decode($dataResponse['api_response'], true)[$postedTab][$postedTabCount]['countyOfficals'];
			}else{
				$collection   = json_decode($dataResponse['api_response'], true)[$postedTab][$postedTabCount]['committeeOfficals'];
			}
		}else{
	   	 $collection   = json_decode($dataResponse['api_response'], true)[$postedTab];
		}
		 $data='';
		 if(($postedTab=='stateSenateCommittees' || $postedTab=='stateHouseCommittees' || $postedTab=='countyDeligationList') && $postedTabCount==''){
			 $cards   = json_decode($dataResponse['api_response'], true)[$postedTab];
			 if($cards){
			   $tabCount='0';
			   $data .='<div class="accordion" id="accordionExample">';
			   foreach ($cards as $key => $value){
				 $counterTab='';
				 if($postedTab=='countyDeligationList'){
					 $counterTab=count($value['countyOfficals']);
				  }else{
					$counterTab=count($value['committeeOfficals']);
				  }
			   $data  .='<div class="card">'; 
			   $data  .='<div class="card-header px-0 " id="heading'.$value['id'] .'">
					  <h2 class="mb-0">
						<button class="btn btn-link btn-block text-left py-0 d-flex align-items-center" type="button" data-toggle="collapse" data-target="#collapse'.$value['id'] .'" aria-expanded="true" aria-controls="collapseOne">
						  <i class="fal fa-plus mr-3"></i>'.$value['name'] .'<span class="text-dark ml-auto">'.$counterTab.' Public Officials</span>
						</button>
					  </h2>
					</div>';
			  $data .='<div data-count="'.$tabCount.'" id="collapse'.$value['id'].'" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
				<div class="card-body"></div>
				</div>';
			   $data  .='</div>';
			   $tabCount++;
			   }
			   $data .='</div>';
			 }else{
				$data  .='<h5 class="text-center pt-5">Data not available</h5>';
			 }
		 }else{
			 if($collection){
        $data  .= '<table id="ebtmaintable"   class=" tabData table table-bordered border-0 table-striped" style="width: 100% !important;"> <thead> <tr>'.$tableHeader.'</tr></thead><tbody>';
       foreach ($collection as $key => $value) { 
                        $data.= '<tr>';
                        //name
                       $data.= '<td><div class="d-flex"><div class="overflow-hidden rounded-circle mr-2" style="height:40px; max-width:40px; flex: 0 0 40px"><img src="'.$value['profilePic'].'" alt="" class="img-fluid"></div><div><a href="'.$public_official_detail_page_link.'?id='.$value['id'].'">'.$value['legalName'].'<br>('.$value['officialNameLabel'].')</a></div></div></td>'; 
					  //counties 
					  $countiesList = $value['counties'];
					  $allCounties = array();
					  if($countiesList){
					  if(count($countiesList)>1){
					  $countyPopover = dd_header('Associated Counties','Search counties..');
					  $subItems = "";
					  $li=1;
					  foreach ($countiesList as $index => $county) {
					  $class='';
					  if($li%2==1){
					  $class='bg-light';	
					  }
					  $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">' . $county .  '</li>';
					  $li++;
					  }
					  $countyPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
					  $allCounties[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$countiesList[0].'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$index.'" data-placement="left" data-containerid="' . $index . '" id="' . $index . '"> +' .(count($countiesList)-1) .'</span>'.$countyPopover.'</div>';
					  }else if(count($countiesList)==1){
					  $allCounties[] = $countiesList[0];
					  }
					  $tdcounties= implode(" ", $allCounties);
					  }else {
					  	$tdcounties='<em class="opacity-50">N/A</em>';
					  }
					  $data.= '<td>'.$tdcounties.'</td>';
					  //residence
					  $data.= '<td>'.$value['residence'].'</td>';
					  $data.= '<td>'.$value['districtCode'].'</td>';
					  //committies
					  $committeesList=$value['committees'];
					  $allCommittees = array();
					  if($committeesList){
					  if(count($committeesList)>1){
					  $committeesPopover = dd_header('Associated Committies','Search committies..');
					  $subItems = "";
					  $li=1;
					  foreach ($committeesList as $index => $committee) {
					  $class='';
					  if($li%2==1){
					  $class='bg-light';	
					  }
					  $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">' . $committee .  '</li>';
					  $li++;
					  }
					  $committeesPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
					  $allCommittees[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$committeesList[0].'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$index.'" data-placement="left" data-containerid="' . $index . '" id="' . $index . '"> +' .(count($committeesList)-1) .'</span>'.$committeesPopover.'</div>';
					  }else if(count($committeesList)==1){
					  $allCommittees[] = $committeesList[0];
					  }
					  $tdcommittees= implode(" ", $allCommittees);
					  }else {
					 	 $tdcommittees='<em class="opacity-50">N/A</em>';
					  }
					  $data.='<td>'.$tdcommittees.'</td>';
					  $data.= '<td>'.$value['party'].'</td>';
					  $data.= '<td>'.$value['legislativeRole'].'</td>';
					  $data.= '</tr>';
			   }
				$data.='</tbody></table>';
			 }else{
				$data  .='<h5 class="text-center pt-5">Data not available</h5>'; 
			 }
		 }
        echo $data;
        wp_die();
    }
    private function _preparePostData()
    {

        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);

        $isAsscend = $_POST["order"][0]["dir"];

        if ($isAsscend == 'asc') {
            $isAsscending = true;
        } else {
            $isAsscending = false;
        }
        
		$titleColumn = $_POST['titleColumn'];
        $title = $_POST['columns'][$titleColumn]['search']['value'];

        if (strlen($_POST['search']['value']) > 1) {
            $title = $_POST['search']['value'];
        }
        $postData = array();
        $sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
           
         
        $postData = array();
        $getCurrentdate = date("Y-m-d");
        $postData['itemCount'] = $_POST['length'];
        $postData['sortBy'] = $sortBy;
        //$postData['isAsscending'] = $isAsscending;
        $postData['pageNumber'] = ($startPageNum);
        $postData['pageSize'] = ((int) $_POST['length']);
        $postData['sortDirection'] = $_POST["order"][0]["dir"];
        
        $postData['filterBody'] = array('searchText'=>$title,  'selectedDate' => $getCurrentdate);

        
        if(!empty($_POST['createdDate']))
        {

            $dateRange = explode("-", $_POST['createdDate']);
            $postData['filterBody']['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['filterBody']['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }
        if($_POST['tags'])
        {
             $postData['filterBody']['tags']=$_POST['tags'];
        }
        
        return $postData;
    }

    private function _preparePostCountData()
    {
        $searchValue = '';
        if (strlen($_POST['search']['value']) > 1) {
            $searchValue = $_POST['search']['value'];
        }

        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }

          
        $lastActionTypes = array();
        if (isset($_POST['lastActionTypes']) && !empty($_POST['lastActionTypes'])) {
            $lastActionTypes = @explode(",", $_POST['lastActionTypes']);
        }  

        $postData = array();          
        $postData['searchText'] = $searchValue;      
        $postData['tags'] = $_POST['tags'];
        if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }

        $getCurrentdate = date("Y-m-d");
        return $postData;
    }
    
    private function _prepareLegislationPostData()
    {

        $search = '';
        if (strlen($_POST['search']['value']) > 1) {
            $search = $_POST['search']['value'];
        }

        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
        $searchText = $_POST['tzdatasearch'];
        $columnsData = [];
        $columnsD = [];
        foreach ($_POST['columns'] as $key => $value) {
            $columnsD[$value['data']] = $key;
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }

        $isAsscend = $_POST["order"][0]["dir"];
        if ($isAsscend == 'asc') {
            $isAsscending = true;
        } else {
            $isAsscending = false;
        }
        
        if ($_POST["order"][0]["column"] == $columnsD["title"]) {
            $sortBy = "Title";
        } else if ($_POST["order"][0]["column"] == $columnsD["billType"]) {
            $sortBy = "BillType";
        } else if ($_POST["order"][0]["column"] == $columnsD["billNumber"]) {
            $sortBy = "BillNumber";
        } else if ($_POST["order"][0]["column"] == $columnsD["lastActionOn"]) {
            $sortBy = "LastAction";
        } else if ($_POST["order"][0]["column"] == $columnsD["state"]) {
            $sortBy = "State";
        } else {
            $column = $_POST['order'][0]['column'];
            $sortBy = $_POST['columns'][$column]['data'];
        }

        $sponsors = array();
        if (isset($_POST['sponsorsList']) && !empty($_POST['sponsorsList'])) {
            $sponso_ = @explode(",", $_POST['sponsorsList']);
            $sponsors = $sponso_;
        }
        $jsonSponsors = ($sponsors);
        $datepickerstart = null;
        $datepickerend = null;
        if (isset($_POST['startDate']) && !empty($_POST['startDate'])) {
            $datepickerstart = date('m-d-Y', strtotime($_POST['startDate']));
        }

        if (isset($_POST['endDate']) && !empty($_POST['endDate'])) {
            $datepickerend = date('m-d-Y', strtotime($_POST['endDate']));

        }
        $trackingLevels = array();
        $sponsors22 = array();
        $houseCommittees = array();
        $senateCommittees = array();
        $lastActionTypes = array();
        $billTypes = array();
        $statusTypes = array();
        $tags        = array();
        $assignTo    = array();
        $assignGroups = array();
        $assignTag  = array();

        if (isset($_POST['isapplyactive']) && !empty($_POST['isapplyactive'])) {
        if (isset($_POST['trackingLevels']) && !empty($_POST['trackingLevels'])) {
            $trackingLevels = $_POST['trackingLevels'];           
        }     
        
        
        if (isset($_POST['sponsors']) && !empty($_POST['sponsors'])) {
              $sponsors22 =  $_POST['sponsors'];
           
        }
        
        if (isset($_POST['tags']) && !empty($_POST['tags'])) {
            $tags =  $_POST['tags'];
        }

         if (isset($_POST['assignTo']) && !empty($_POST['assignTo'])) {
            $assignTo =  $_POST['assignTo'];
        }

         if (isset($_POST['assignTag']) && !empty($_POST['assignTag'])) {
            $assignTag =  $_POST['assignTag'];
        }

        if (isset($_POST['assignGroups']) && !empty($_POST['assignGroups'])) {
            $assignGroups =  $_POST['assignGroups'];
        }


        if (isset($_POST['houseCommittees']) && !empty($_POST['houseCommittees'])) {
            $houseCommittees = $_POST['houseCommittees'];
           
        } 
        
        if (isset($_POST['senateCommittees']) && !empty($_POST['senateCommittees'])) {
            $senateCommittees = $_POST['senateCommittees'];
           
        }  
        
        if (isset($_POST['lastActionTypes']) && !empty($_POST['lastActionTypes'])) {
            $lastActionTypes = $_POST['lastActionTypes'];
           
        }  
        
        if (isset($_POST['billTypes']) && !empty($_POST['billTypes'])) {
            $billTypes = $_POST['billTypes'];
           
        }  
        
        if (isset($_POST['statusTypes']) && !empty($_POST['statusTypes'])) {
            $statusTypes = $_POST['statusTypes'];
           
        } 
		  }        
         
if (isset($_POST['sessionIds'])) {
            $sessionId = $_POST['sessionIds'];
           
        } 


        $postData = array();
        $postData['introducedDate'] = date('m/d/Y');
        $postData['trackingLevels'] = $trackingLevels;
        $postData['sponsors'] = $sponsors22;
        $postData['houseCommittees'] = $houseCommittees;
        $postData['senateCommittees'] = $senateCommittees;
        
     
		$titleColumn = $_POST['titleColumn'];
        $postData['title'] = $_POST['columns'][$titleColumn]['search']['value'];
		$billColumn = $_POST['billColumn'];
        $postData['billNumber'] = $_POST['columns'][$billColumn]['search']['value'];

        $postData['searchText'] = '';
        $postData['lastActionStartDate'] = $datepickerstart;
        $postData['lastActionEndDate'] = $datepickerend;
        $postData['lastActionTypes'] = $lastActionTypes;
        $postData['tags'] = $tags;
        $postData['billTypes'] = $billTypes;
        $postData['status'] = $statusTypes;
        $postData['sortBy'] = $sortBy;
        $postData['isAsscending'] = $isAsscending;
        $postData['pageNumber'] = ($startPageNum);
        $postData['pageSize'] = ((int) $_POST['length']);
        $postData['usersTags'] = $assignTag;
        $postData['clientPersonGroups'] = $assignGroups;
        $postData['users'] = $assignTo;
		$postData['sessionId'] = $sessionId;
		$postData['introducedStartDate'] = $_POST['startDate'];;
		$postData['introducedEndDate'] = $_POST['endDate'];;
       	return $postData;
    }

    private function _popoverTagsHtml($id, $tags){
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="tag_leg_' . $id . '">';
        $subItems = "";
       
        foreach ($tags as $key => $rowData) {            
            
            $subItems .= ' <option value="' . $rowData->text . '" data-capital="' .$rowData->text . '" data-tagid="'.$rowData->value.'" data-tagtext="'.base64_encode($rowData->text).'" >' . $rowData->text . '</option>';}

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $vars = "<script>
                    $(function() {
                        tag_leg_{$id} = $('#tag_leg_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.tag_leg_{$id}', function () {
                            tag_leg_{$id}.select2('open');
                            $('#tag_leg_{$id}').on('select2:select', function (e) {
                                var data = e.params.data;
                                var tag_redirect = data.element.baseURI+'?tag='+data.element.dataset.tagid+'&'+data.element.dataset.tagtext;
                                window.location.href= tag_redirect;
                            });
   
                            setTimeout(function(){ __addExtraDiv('Tags')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }

    private function _popoverTagsHtml1($id, $tags){
	$options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $bills_page = $front_pages['bills_page'];
	if($bills_page){
	$bill_page_link=get_permalink( $bills_page );	
	}else{
	$bill_page_link= site_url() .'/bill-tracking/';	
	}

        $rowName = array();
        $popOverHtml .= '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Tags</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown" placeholder="Search tags.."/></div>';
        $subItems = "";
       $li=1;
        foreach ($tags as $key => $rowData) {            
            $class='';
            if($li%2==1){
			$class='bg-light';	
			}
			$subItems .= '<a style="display:block" href="' . $bill_page_link.'?tag='.$rowData->value.'&'.base64_encode($rowData->text).'" target="_blank" class="px-2 py-1 border-bottom  small '.$class.'">' . $rowData->text . '</a>';
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

        return $popOverHtml . $vars;
    }

    private function _popOverAssignHtml($id, $assignedto){
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="assign_' . $id . '">';
        $subItems = "";
       
        foreach ($assignedto as $key => $rowData) {
            
            $rowName[$rowData->id] = $key;

            
            $subItems .= ' <option value="' . $rowData . '" data-capital="' .$rowData . '" >' . $rowData . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "<script>
                    $(function() {
                        assign_{$id} = $('#assign_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.assign_{$id}', function () {
                            assign_{$id}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Assigned To')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }
    private function _popOverAssignHtml1($id, $assignedto){
        $rowName = array();
        $popOverHtml .= '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Assigned To</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown assign_' . $id . '" placeholder="Search assigned to.."/></div>';
        $subItems = "";
        $li=1;
       foreach ($assignedto as $key => $rowData) {
           $rowName[$rowData->id] = $key;
           $class='';
            if($li%2==1){
			$class='bg-light';	
			}
           $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'"><span>' . $rowData .  '</span></li>';
			$li++;
        }
$popOverHtml .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
        $searchName = json_encode(array_values($rowName));
		$vars = "";
        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }

    private function _popOverSenateHtml($id, $committee){

        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="scom_' . $id . '">';
        $subItems = "";
        
        foreach ($committee as $key => $rowData) {
            
            $rowName[$rowData->id] = $key;

            
            $subItems .= ' <option value="' . $rowData . '" data-capital="' .$rowData . '" >' . $rowData . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "<script>
                    $(function() {
                        senate_{$id} = $('#scom_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.senate_{$id}', function () {
                            senate_{$id}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Seante committee')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;

    }

    private function _popOverHouseHtml($id, $committee){

        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="hcom_' . $id . '">';
        $subItems = "";
        
        foreach ($committee as $key => $rowData) {
            
            $rowName[$rowData->id] = $key;

            
            $subItems .= ' <option value="' . $rowData . '" data-capital="' .$rowData . '" >' . $rowData . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "<script>
                    $(function() {
                        house_{$id} = $('#hcom_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.house_{$id}', function () {
                            house_{$id}.select2('open');
                            setTimeout(function(){ __addExtraDiv('House committee')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;

    }

    private function _popOverHtml($personseGroupId, $persondata)
    {
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $personseGroupId . '"  style="opacity:0;height:0;display:block;"> <select class="form-control abcdfed" id="searchbox_' . $personseGroupId . '">';
        $subItems = "";
        foreach ($persondata as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->name;
            
            if($rowData->profilePic)
                $profilepic = $rowData->profilePic;
            else
                $profilepic = ENGAGIFII_ASSETS_URL.'/images/staff-list-grey.png';
            $subItems .= ' <option value="' . $rowData->name . '" data-capital="' . $rowData->name . '" data-profilepic="' . $profilepic . '">' . $rowData->name . '</option>';
        }

        $popOverHtml .= $subItems.'</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "
                       <script>
                       $(function() {

                        /*var availableTags_{$personseGroupId} = $searchName;
                        $( '.searchbox-sponsors_{$personseGroupId}' ).autocomplete({

                            source: availableTags_{$personseGroupId}
                            });*/

                            hello_{$personseGroupId} = $('#searchbox_{$personseGroupId}').select2({
                                templateResult: function(item) {
                                    return format(item, false);
                                }
                                });

                                $(document).on('click', '.sponsors-click_{$personseGroupId}', function () {

                                    hello_{$personseGroupId}.select2('open');
                                    setTimeout(function(){ __addExtraDiv('Sponsors')},100);
                                    });


                                    });
                                    </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;

    }

    private function _popOverHtml1($personseGroupId, $persondata) {
        $rowName = array();
        $popOverHtml .= '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Sponsors</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown sd_' . $personseGroupId . '" placeholder="Search sponsors.."/></div>';
        $subItems = "";
        $li=1;
        foreach ($persondata as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->name;
            
            if($rowData->profilePic)
                $profilepic = $rowData->profilePic;
            else
                $profilepic = ENGAGIFII_ASSETS_URL.'/images/staff-list-grey.png';
				 $class='';
            if($li%2==1){
			$class='bg-light';	
			}
            $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'"><img style="width:30px; height:30px" src="' . $profilepic . '" class="mr-2 rounded-circle"/><span>' . $rowData->name .  '</span></li>';
			$li++;
        }

        $popOverHtml .= $subItems.'<span class="span_' . $personseGroupId . ' px-2 py-1 text-center   small d-none">No results found!</span></div>';
        $searchName = json_encode(array_values($rowName));

        $vars = "";
        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;

    }

    private function _popOverClassData($id, $classData){
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="classbox_' . $id . '">';
        $subItems = "";
        
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->id;
            $classTime = '';
            if($rowData->sessionDate)
                
                $classTime = date('M d Y', strtotime($rowData->sessionDate)).' At '.$rowData->startTime.' - '.$rowData->endTime;
            
            $subItems .= ' <option value="' . date('M d Y', strtotime($rowData->sessionDate)) . '" data-capital="' . date('M d Y', strtotime($rowData->sessionDate)) . '" data-profilepic="' . ENGAGIFII_ASSETS_URL.'/images/class.png' . '">' . $classTime . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "<script>
                    $(function() {
                        class_{$id} = $('#classbox_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.class_{$id}', function () {
                            class_{$id}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Class Dates')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }


    private function _popOverClassData1($id, $classData){
        $rowName = array();
        $popOverHtml .= dd_header('Class Dates');
        $subItems = "";
        $li=1;
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->id;
            $classTime = '';
            if($rowData->sessionDate)
                
            $classTime = date('M d Y', strtotime($rowData->sessionDate)).' At '.$rowData->startTime.' - '.$rowData->endTime;
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

        return $popOverHtml . $vars;
    }

//Popover events class data

private function _popOverEventsData($id, $eventsData){
    $rowName = array();
    $popOverHtml .= '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center border-bottom mb-0 pb-3">Class Dates</h6>';
    $subItems = "";
    $li=1;
    foreach ($eventsData as $key => $rowData) {
        
        $rowName[$rowData->id] = $rowData->id;
        $classTime = '';
        if($rowData->sessionDate)
            
        $classTime = date('M d Y', strtotime($rowData->sessionDate)).' At '.$rowData->startTime.' - '.$rowData->endTime;
        $class='';
        if($li%2==1){
        $class='bg-light';	
        }
        $subItems .= '<li class="px-2 py-1 border-bottom d-flex align-items-center small '.$class.'"><img style="max-width:25px" src="'. ENGAGIFII_ASSETS_URL.'/images/class.png' .'" class="img-fluid mr-2"/>' . $classTime . '</li>';
        $li++;
    }

    $popOverHtml .= $subItems;
    $popOverHtml.= '</div>';
    $searchName = json_encode(array_values($rowName));

    $vars = "";
    $popOverHtml .= '</ul></span>';
    $popOverHtml .= '</div>';

    $popOverHtml .= '</div>';
    $popOverHtml .= '</div>';
    $popOverHtml .= '</div> ';

    return $popOverHtml . $vars;
}

	//Events Date popover 
	public function _popOverLocationData($id, $locationData){
		$rowName = array();
     
 $popOverHtml = '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Locations</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown" placeholder="Search Locations.."/></div>';
	 $subItems = "";
$li=1;
	  foreach ($locationData as $key => $rowData) {
		  
		  $rowName[$rowData->id] = $rowData->city;
		    $class='';
            if($li%2==1){
			$class='bg-light';	
			}
		  if($rowData->city){
		  $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'"><b>Address:</b><br>' .$rowData->addressLine.', '.$rowData->city.', '.$rowData->state.', '.$rowData->zip.', '.$rowData->country;
		  if($rowData->latitude){
		 	 $subItems .= '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q='.$rowData->latitude.','.$rowData->longitude.'&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div>';
		  }
		  $subItems .= '</li>';
          }
		  $li++;
	  }

	  $popOverHtml .= $subItems;
	  $popOverHtml.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';

	  $vars = "";

	  $popOverHtml .= '</span>';
	  $popOverHtml .= '</div>';

	  $popOverHtml .= '</div>';
	  $popOverHtml .= '</div>';
	  $popOverHtml .= '</div> ';

	  return $popOverHtml . $vars;

  }
//ends here 
     private function _popOverTagData($id, $tagData){
        
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block"> <select class="form-control" id="tagbox_' . $id . '">';
        $subItems = "";
        
        foreach ($tagData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->tagName;
           
            
            $subItems .= ' <option value="' . $rowData->tagName . '" data-capital="' . $rowData->tagName . '" >' . $rowData->tagName . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "<script>
                    $(function() {
                        tag_{$id} = $('#tagbox_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.tag_{$id}', function () {
                            tag_{$id}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Associated Tags')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

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


    private function _popOverClassesData($courseid, $classData){
       
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $courseid . '"  style="opacity:0;height:0;display:block"> <select class="form-control" id="classbox_' . $courseid . '">';
        $subItems = "";
        
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->name;
            $classTime = '';
            if($rowData->name)
                $classTime = $rowData->sectionName;
            
            $subItems .= ' <option value="' . $rowData->sectionName . '" data-capital="' . $rowData->sectionName . '" data-profilepic="' . $rowData->parentCourse->iconReference . '"  >' . $rowData->sectionName . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        $searchName = json_encode(array_values($rowName));

        $vars = "<script>
                    $(function() {
                        class_{$courseid} = $('#classbox_{$courseid}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.class_{$courseid}', function () {
                            class_{$courseid}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Associated Classes')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }


    private function _prepareLegislativeIssuesData()
    {

      
        $sessionId = "";
        if (isset($_POST['sessionId'])) {
            $sessionId = $_POST['sessionId'];

        }

        $postData = array();
       
		$postData['sessionId'] = $sessionId;
        return $postData;
    }


    public function _prepareCoursePostData(){
        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);

        $isAsscend = $_POST["order"][0]["dir"];

        if ($isAsscend == 'asc') {
            $isAsscending = true;
        } else {
            $isAsscending = false;
        }
        

        $title = $_POST['columns'][0]['search']['value'];

        if (strlen($_POST['search']['value']) > 1) {
            $title = $_POST['search']['value'];
        }
        $postData = array();
        $sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
        $postData['itemCount'] = $_POST['length'];
        $postData['sortBy'] = $sortBy;
        //$postData['isAsscending'] = $isAsscending;
        $postData['pageNumber'] = ($startPageNum);
        $postData['pageSize'] = ((int) $_POST['length']);
        $postData['sortDirection'] = $_POST["order"][0]["dir"];
        $postData['filterBody'] = array('searchText'=>$title,  'selectedDate' => date('Y-m-d'));
        if(!empty($_POST['classes']))
        {
            $postData['filterBody']['classes'] = $_POST['classes'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['filterBody']['instructors'] = $_POST['instructors'];
        }
        if(!empty($_POST['tags']))
        {
            $postData['filterBody']['tags'] = $_POST['tags'];

        }
         if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['filterBody']['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['filterBody']['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }
        return $postData;
    }

    public function _preparePeopleData(){
        // $postData = '{"itemCount":"50","pageNumber":1,"sortBy":"updated","sortDirection":"desc","filterBody":{"selectedDate":"2024-03-19","onlyFavorite":false,
        //     "searchText":"","search":[{"searchText":"","searchType":"searchText"},{"searchText":"","searchType":"searchEmailText"},{"searchText":"",
        //         "searchType":"searchContactText"}],"searchEmailText":"","searchContactText":"","pageNumber":1,"pageSize":"50",
        //         "allPeoplePermission":{"viewInstructor":false,"viewAllMembers":true,"viewStaff":false,"viewNonMembers":false,
        //             "viewOwnOrganizationMembers":true,"viewChildOrganizationMembers":false,
        //             "viewDeactivatedPeople":false,"sendEmailPer":true,"deletePer":false,"invitePersonPer":false,
        //             "addRemoveTagsPer":false,"viewDetail":true,"deactivatePeople":true,"viewExhibitor":false,"viewPublic":false},"filterRules":[]}}';
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
        $postData = array(
            'itemCount' => $_POST['length'],
            'sortBy' => "updated",
            'pageNumber' => $startPageNum,
            'pageSize' => ((int) $_POST['length']),
            'sortDirection' => "desc",
            'filterBody' => array(
                'selectedDate' => "2024-03-19",
                'onlyFavorite' => false,
                'searchText' => '',
                'search' => array(
                    array(
                        'searchText' => '',
                        'searchType' => "searchText"
                    ),
                    array(
                        'searchText' => '',
                        'searchType' => "searchEmailText"
                    ),
                    array(
                        'searchText' => '',
                        'searchType' => "searchContactText"
                    )
                ),
                'searchEmailText' => '',
                'searchContactText' => '',
                'allPeoplePermission' => array(
                    'viewInstructor' => false,
                    'viewAllMembers' => true,
                    'viewStaff' => false,
                    'viewNonMembers' => false,
                    'viewOwnOrganizationMembers' => true,
                    'viewChildOrganizationMembers' => false,
                    'viewDeactivatedPeople' => false,
                    'sendEmailPer' => true,
                    'deletePer' => false,
                    'invitePersonPer' => false,
                    'addRemoveTagsPer' => false,
                    'viewDetail' => true,
                    'deactivatePeople' => true,
                    'viewExhibitor' => false,
                    'viewPublic' => false
                ),
                'filterRules' => array()
            )
        );
        
      
    return $postData;
    }


    public function _prepareEventsData(){
        $allEvents = get_option( 'ebt_api_settings' )['allEvents'];
        if($allEvents==1){
        $allEvents = 'false';	
        }else{
            $allEvents = 'true';
        }

        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);

        $isAsscend = $_POST["order"][0]["dir"];

        if ($isAsscend == 'desc') {
            $isAsscending = false;
        } else {
            $isAsscending = true;
        }
        
		$titleColumn=$_POST['titleColumn'];
        $title = $_POST['columns'][$titleColumn]['search']['value'];

        if (strlen($_POST['search']['value']) > 1) {
            $title = $_POST['search']['value'];
        }
        $postData = array();
        $sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
        $postData['itemCount'] = $_POST['length'];
        $postData['onlyUpcoming'] = $allEvents;
        $postData['sortBy'] = ucfirst($sortBy);
        $postData['isAscending'] = $isAsscending;
        $postData['pageNumber'] = ($startPageNum);
        $postData['pageSize'] = ((int) $_POST['length']);
		$postData['text'] = $title;
        //$postData['sortDirection'] = $_POST["order"][0]["dir"];
        $postData['filterBody'] = array('searchText'=>$title,  'selectedDate' => date('Y-m-d'));
        if(!empty($_POST['tags']))
        {
            $postData['tags'] = $_POST['tags'];
        }
        if(!empty($_POST['types']))
        {
            $postData['types'] = $_POST['types'];
        }
		 if(!empty($_POST['locations']))
        {
            $postData['locations'] = $_POST['locations'];
        }
         if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['eventStartDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['eventEndDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }
        return $postData;
    }


    public function _classPostCountData(){
        $year = $_POST['year'];
        $month = $_POST['month'];
         $searchValue = '';
        if (strlen($_POST['search']['value']) > 1) {
            $searchValue = $_POST['search']['value'];
        }
		$startPageNum=1;
		if($_POST['start']) {
		  $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
		}
        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }

        if ($columnsData["sectionname"] == "sectionname") {
            $sortBy = "sectionname";
        }else if ($columnsData["startdate"] == "startdate") {
            $sortBy = "startdate";
        }else if ($columnsData["credithours"] == "credithours") {
            $sortBy = "credithours";
        }else {
            $sortBy = "";
        }


        $postData = array();
        $postData['title'] = $searchValue;            
        $postData['searchText'] = $searchText;      
        $postData['lastActionStartDate'] = $datepickerstart;
        $postData['lastActionEndDate'] = $datepickerend;
        $postData['sortBy'] = $sortBy;
        $postData['pageNumber'] = $startPageNum;
        $postData['pageSize'] = $_POST['length'];

        if(!empty($_POST['courses']))
        {
            $postData['courses'] = $_POST['courses'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['instructors'] = $_POST['instructors'];
        }
      	if(!empty($_POST['class_start_date'])) {
         $postData['createdDateRange']['startDate'] = $_POST['class_start_date'];
         $postData['createdDateRange']['endDate'] =$_POST['class_end_date'];
		}
		if(!empty($_POST['classStates'])) {
			$postData['classStates'] =$_POST['classStates'];
		}
        if(!empty($_POST['minReg']))
        {
           // $dateRange = explode("-", $_POST['createdDate']);
            $postData['registrationDateRange']['startDate'] = $_POST['minReg'];
            $postData['registrationDateRange']['endDate'] = $_POST['maxReg'];
        }

if(!empty($_POST['minRange']))
        {
            $postData['creditHour']['min'] = $_POST['minRange'];
            $postData['creditHour']['max'] = $_POST['maxRange'];
        }

        $getCurrentdate = date("Y-m-d");
        $postData['selectedDate'] = $getCurrentdate;
        $postData['filterBody'] = array('year'=>$year, 'month'=>$month);
        return $postData;
    }

 
    //Endorsement : Get data - Added by Gurpreet

    public function endorsementCalendar(){
        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $endorse_detail_page = $front_pages['endorse_detail_page'];
	if($endorse_detail_page){
		$endorse_detail_page_link=get_permalink( $endorse_detail_page );	
	}else{
		$endorse_detail_page_link= site_url() .'/endorsement-detail/';	 
	}
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

        $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
        $first_date = date("Y-m-d",$first_date_find);

        $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
        $last_date = date("Y-m-d",$last_date_find);
        
    $endorsement_api_url = $options['ebt_api_url'];
    $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
    $postedData = $this->_preparePostCountData();
       
    $dataResponse = $this->submitApiRequest("Public/Award/PublicFilteredRecordCount", $postedData, "POST", 'endorsement');
       $classCount = $dataResponse['api_response'];
       $postData = array();    
       $postData['itemCount'] = $classCount;
       $postData['sortBy'] = 'sectionname';
       $postData['pageNumber'] = 1;
       $postData['pageSize'] = ((int) $classCount);
       $postData['sortDirection'] = 'asc';
       if(!empty($_POST['courses']))
       {
           $postData['courses'] = $_POST['courses'];
       }
       if(!empty($_POST['instructors']))
       {
           $postData['instructors'] = $_POST['instructors'];
       }
       $postData['filterBody'] = array('searchText'=>'',  'selectedDate' => '');
       $postData['filterBody'] ['createdDateRange'] = array('startDate'=>$first_date, 'endDate'=>$last_date) ;
       $dataResponse = $this->submitApiRequest("Public/AwardListPublic", $postData, "POST", 'endorsement');

       $collection   = json_decode($dataResponse['api_response'])->result;
       $data         = array();
       $endorsmentData    = array();
       foreach ($collection as $key => $value) {
               $data['title'] = '<a href="'.$endorse_detail_page_link.'?endId='.$value->id.'">'.$value->name.'</a>';
               $data['id']    = $value->id;
               $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
               $data['objectType'] = $value->objectType;
               $data['name'] = $value->name;
               $data['price'] = $value->price;
               $data['createdOn'] = date('Y-m-d', strtotime($value->createdOn));
               $data['icon']       = $value->icon;
               $data['validity'] = $value->validity;
                $data['createdBy'] = $value->createdBy->name;
               $class_schedule = '';
               $endorsementTag = $value->tags;
               $allTags = array();
               foreach ($endorsementTag as $index => $tag) {
                             
                   $allTags[] = $tag;
               }

               $data['endorsementTag'] = $allTags;
               $data['viewdetails'] = '<a href="'.$endorse_detail_page_link.'?endId='.$value->id.'" class="btn btn-secondary px-3 py-1" target="_blank">View Details</a>';
                  
               if(!$value->isAlreadyRegistered)
               {
                 $data['register'] = '<a href="'.$tenant_url.'/pages/awards/'. $value->id .'/signup/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
               }
               else{
                $data['register'] = ' ';
               }
               $endorsementData[] = $data; 
           //} 
       }
       return $endorsementData;
   }
    // end here guru


    //Events : Get data - Added by Gurpreet

    public function eventsCalendar(){
	        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $events_detail_page = $front_pages['events_detail_page'];
	if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	}else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	}

        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['evt_tenant_code']['engagifii_url'];
        // $upcomingEvents = 'false';
        $allEvents = get_option( 'ebt_api_settings' )['allEvents'];
        if($allEvents==1){
        $allEvents = 'false';	
        }else{
            $allEvents = 'true';
        }
        $postedData = $this->_eventsPostCountData();
       $dataResponse = $this->submitApiRequest("public/count", $postedData, "POST", 'event');
        $classCount = $dataResponse['api_response'];
        $postData = array();    
        $postData['itemCount'] = $classCount;
        $postData['sortBy'] = 'sectionname';
        $postData['onlyUpcoming'] = $allEvents;
        $postData['pageNumber'] = 1;
        $postData['pageSize'] = ((int) $classCount);
        $postData['sortDirection'] = 'asc';
        if(!empty($_POST['courses']))
        {
            $postData['courses'] = $_POST['courses'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['instructors'] = $_POST['instructors'];
        }
        $postData['filterBody'] = array('searchText'=>'',  'selectedDate' => date('Y-m-d'));
        $dataResponse = $this->submitApiRequest("public/listEventsByFilter", $postData, "POST", 'event');
        $collection   = json_decode($dataResponse['api_response'])->collection;
           $data         = array();
           $endorsmentData    = array();
           foreach ($collection as $key => $value) {
            $event_status = $value->eventStatus;
            $registration_state = preg_replace('/(?<!\ )[A-Z]/', ' $0', $value->eventRegistrationState);//$value->eventRegistrationState;
           
            if(count($value->eventDates))
        	{
                //$i=0;
        	 foreach ($value->eventDates as $index => $event) {
                 //session start time and date
                $default_StartDate = $event->sessionStartTime;
                $convert_StartDate = strtotime($default_StartDate);
                $new_StartDate = date('M d, Y', $convert_StartDate);
                $sessionStartTime = date('g:i A', $convert_StartDate);
                //end here

                //session end date and time
                $default_EndDate = $event->sessionEndTime;
                $convert_EndDate = strtotime($default_EndDate);
                $new_EndDate = date('M d, Y', $convert_EndDate);
                $sessionEndTime = date('g:i A', $convert_EndDate);
                // end here

                $startDate = date('Y-m-d', strtotime($value->startDateTime));
                //$sessionEndTime = date('Y-m-d', strtotime($value->endDateTime));
                   $endDate = date('Y-m-d', strtotime($value->endDateTime));
                   $data['title'] = '<a href="'.$events_detail_page_link.'?endId='.$value->id.'">'.$value->name.'</a>';
                   $data['titleNoLink'] = $value->name;
                   $data['id']    = $value->id;
                   $data['start'] = date('Y-m-d', strtotime($event->sessionStartTime));
                   $data['end']   = date('Y-m-d', strtotime($event->sessionStartTime));
                   $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
                   $data['objectType'] = $value->eventType;
                   $data['name'] = $value->name;
                   $data['price'] = $value->defaultPrice;
                   $data['createdOn'] = date('Y-m-d', strtotime($value->startDateTime));
                   //$data['hours']      = $value->parentCourse->creditHours;
                   $data['icon'] = $value->imageUrl;
                   $data['schedule'] = $new_StartDate.' at '.$sessionStartTime.' - '.$sessionEndTime;
                   // $data['firstcondition'] = $i++;
                    $data['location'] = $value->location;
                   $class_schedule = '';
                   $eventsTag = $value->tags;
                   $allTags = array_diff($eventsTag, array('PUBLIC', 'public', 'Public'));
                    $filterTag = array_values($allTags);
                   $allTags = array();
                   if($filterTag){
                    foreach ($filterTag as $index => $tag) {
                                    
                        $allTags[] = $tag;
                    }
                }else{
                    $allTags[] ="NA";
                }
    
                   $data['endorsementTag'] = $allTags;
                   $data['viewdetails'] = '<a href="'.$events_detail_page_link.'?endId='.$value->id.'" class="btn btn-secondary px-3 py-1" target="_blank">View Details</a>';
                  
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
                   
               } 
               else{
                   //session start time and date
                $default_StartDate = $event->sessionStartTime;
                $convert_StartDate = strtotime($default_StartDate);
                $new_StartDate = date('M d, Y', $convert_StartDate);
                $sessionStartTime = date('g:i A', $convert_StartDate);
                //end here

                //session end date and time
                $default_EndDate = $event->sessionEndTime;
                $convert_EndDate = strtotime($default_EndDate);
                $new_EndDate = date('M d, Y', $convert_EndDate);
                $sessionEndTime = date('g:i A', $convert_EndDate);
                // end here
                        $startDate = date('Y-m-d', strtotime($value->startDateTime));
                        $endDate = date('Y-m-d', strtotime($value->endDateTime));
                        $data['title'] = '<a href="'.$events_detail_page_link.'?endId='.$value->id.'">'.$value->name.'</a>';
                        $data['titleNoLink'] = $value->name;
                        $data['id']    = $value->id;
                        $data['start'] = date('Y-m-d', strtotime($value->classSessionSettings[0]->sessionStartTime));
	                    $data['end']   = date('Y-m-d', strtotime($value->classSessionSettings[0]->sessionEndTime));
                        $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
                        $data['objectType'] = $value->eventType;
                        $data['name'] = $value->name;
                        $data['price'] = $value->defaultPrice;
                        $data['createdOn'] = date('Y-m-d', strtotime($value->startDateTime));
                        //$data['hours']      = $value->parentCourse->creditHours;
                        $data['icon'] = $value->imageUrl;
                        $data['schedule'] = $new_StartDate.' at '.$sessionStartTime.' - '.$sessionEndTime;
                        $data['secondCondition'] = $value->createdBy->name;
                        $data['location'] = $value->location;
                        $class_schedule = '';
                        $eventsTag = $value->tags;
                        $allTags = array_diff($eventsTag, array('PUBLIC', 'public', 'Public'));
                        $filterTag = array_values($allTags);
                        $allTags = array();
                        if($filterTag){
                        foreach ($filterTag as $index => $tag) {
                                        
                            $allTags[] = $tag;
                        }
                    }else{
                        $allTags[] ="NA";
                    }

                        $data['endorsementTag'] = $allTags;
                   $default_RegisterBtn = "";
                        if ($event_status == 'Completed' || $registration_state == 'RegistrationClosed') {
                            $registration_state = preg_replace('/(?<!\ )[A-Z]/', ' $0', $value->eventRegistrationState);//$value->eventRegistrationState
                            $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$registration_state.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                        }
                        else{
                            $default_RegisterBtn .= '<a href="'.$tenant_url.'/pages/events/'. $value->id .'/general" target="_blank" class="btn btn-primary px-3 py-1" >Register</a>';
                        }
                        
                            $data['register'] = $default_RegisterBtn;
                    $endorsementData[] = $data; 
               }
           }
           return $endorsementData;
       }
        // end here guru


     



/*
 * Generate event calendar in HTML format
 */
public function getCalendar(){
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

?>
    <main class="calendar-contain row">
        <div class="title-bar col-12 bg-light p-3 border">
            <div class="row">
            <div class="title-bar__month col-6 col-md-3 col-lg-2 mb-3 mb-md-0 pr-0">
                <div class="input-group mb-2 mb-md-0">
        <div class="input-group-prepend">
          <div class="input-group-text bg-white rounded-0"><i class="fal fa-calendar-alt"></i></div>
        </div>
                <select class="month-dropdown form-control custom-select rounded-0">
                    <?php echo $this->getMonthList($dateMonth); ?>
                </select>
                </div>
            </div>
            <div class="title-bar__year col-6 col-md-3 col-lg-2 mb-3 mb-md-0 pl-0">
                <select class="year-dropdown form-control custom-select rounded-0">
                    <?php echo $this->getYearList($dateYear); ?>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-8 text-center text-md-right text-uppercase">
                <div class="btn-group calendar-view" role="group" >
                  <button type="button" id="month" class="btn bg-white border" aria-pressed="false">Monthly</button>
                  <button type="button" id="week" class="btn bg-white border" aria-pressed="false">Weekly</button>
                  <button type="button" id="day" class="btn bg-white border" aria-pressed="false">Daily</button>
                </div>
            </div>
        </div>
            
        </div>
        <div class="col-12 pt-4">
            <div class="row ">
        <aside class="calendar__sidebar col-md-4 order-2 border  pb-4 class-background" id="event_list">
            
        </aside>

        <div class="calendar__days col-md-8 pt-5 border mb-4 mb-md-0 calendar-background  px-0 cc" id="monthView">

            <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>','<?php echo date("d",strtotime($date.' - 1 Month')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo date("F",strtotime($date.' - 1 Month')); ?></span></a>
                <h3 class="text-center text-uppercase"><?php echo date("F Y",strtotime($date)); ?></h3>
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small  btn-primary" style="right: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>','<?php echo date("d",strtotime($date.' + 1 Month')); ?>');"><span class="mr-2"><?php echo date("F",strtotime($date.' + 1 Month')); ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__top-bar bg-light border-top mt-4 mt-lg-5 text-uppercase d-flex text-center">
                <span class="top-bar__days border-right py-3">Mon</span>
                <span class="top-bar__days border-right py-3">Tue</span>
                <span class="top-bar__days border-right py-3">Wed</span>
                <span class="top-bar__days border-right py-3">Thu</span>
                <span class="top-bar__days border-right py-3">Fri</span>
                <span class="top-bar__days  py-3 border-right">Sat</span>
                <span class="top-bar__days  py-3 ">Sun</span>
            </div>

            <?php
                $dayCount = 1;
                $classdata = $this->classCalendar();
                echo '<div class="calendar__week text-center d-flex justify-content-around border-top">';
                for($cb=1;$cb<=$boxDisplay;$cb++){
                    if(($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)){
                        // Current date
                        $currentDate = $dateYear.'-'.$dateMonth.'-'.str_pad($dayCount, 2, '0', STR_PAD_LEFT);; 

                        // Get number of events based on the current date
                        
                        $filteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                       sort($filteredItems);
                        // Define date cell color
                        if(strtotime($currentDate) == strtotime(date("Y-m-d")) && count($filteredItems) > 0){
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0 today bg-light border border-success" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($filteredItems)) {echo json_encode($filteredItems);}else{ echo "no-data"; } ?>'>
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text">
                                    <?php if(count($filteredItems) > 0){
                                        echo count($filteredItems).' class'; if(count($filteredItems) >1) {echo "es"; }
                                    } ?>
                                    </span>
                                </div>
                            <?php
                        }elseif(count($filteredItems) > 0){
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0" data-event="<?php echo $currentDate; ?>" data-start='<?php echo json_encode($filteredItems); ?>' onclick="getEvents('<?php echo $currentDate; ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text"><?php echo count($filteredItems).' class'; if(count($filteredItems) >1) {echo "es"; } ?></span>
                                </div>
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
          <div id="weekView" class="calendar__days col-12 pb-4 pt-5 px-1 px-lg-2 border">
            <?php
                    list($week_start_date, $week_end_date) = $this->x_week_range($postedDate);
                    $week_start_date = date("Y-m-d",strtotime($week_start_date.' +1 day'));
                    $week_end_date = date("Y-m-d",strtotime($week_end_date.' +1 day'));
                    $week_array = $this->date_range($week_start_date, $week_end_date);


            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' - 7 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2">Prev</span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' + 7 day')); ?>');"><span class="mr-2">Next</span><i class="fa fa-chevron-right"></i></a>
            
            <div class="calendar__top-bar bg-light mt-4 mt-lg-5 text-uppercase d-flex text-center bg-light">

                <span class="top-bar__days border border-right-0  py-2 py-md-4">Mon</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Tue</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Wed</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Thu</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Fri</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Sat</span>
                <span class="top-bar__days border   py-2 py-md-4">Sun</span>
            </div>
            <div class="calendar__week text-center d-flex justify-content-around border-top pt-3">
            <?php 
                for ($i=0; $i <7 ; $i++) { 
                   
                        $currentDate = $week_array[$i];

                        // Get number of events based on the current date
                        
                        $weekfilteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                        sort($weekfilteredItems);
            ?>			
            
                        <div class="calendar__day border-right <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light border border-success'; } ?>" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
                            <span class="calendar__date mt-auto calendar-text"><?php echo date('d',strtotime($week_array[$i]));  ?></span>
                            <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text">
                            <?php if(count($weekfilteredItems) > 0){
                                echo count($weekfilteredItems).' class'; if(count($weekfilteredItems) >1) {echo "es"; }
                            } ?>
                            </span>
                        </div>
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
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' - 1 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo $prev_date; ?></span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' + 1 day')); ?>');"><span class="mr-2"><?php echo $next_date; ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__week text-center justify-content-around" id="today_event">

            </div>

        </div>
    </div>
</div>
    </main>

<?php
wp_die();
}


//Display Calendar layout data for Endorsement : Added by Guru 

public function getEndorsementCalendar(){
        $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $endorse_detail_page = $front_pages['endorse_detail_page'];
	if($endorse_detail_page){
		$endorse_detail_page_link=get_permalink( $endorse_detail_page );	
	}else{
		$endorse_detail_page_link= site_url() .'/endorsement-detail/';	 
	}
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

?>
    <main class="calendar-contain row">
    <?php echo $this->calendar_mode(); ?>
        <div class="col-12 pt-4">
            <div class="row ">
        <aside class="calendar__sidebar col-md-4 order-2 border  pb-4 class-background" id="event_list">
            
        </aside>

        <div class="calendar__days col-md-8 pt-5 border mb-4 mb-md-0 calendar-background  px-0" id="monthView">

            <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getEndorsementCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>','<?php echo date("d",strtotime($date.' - 1 Month')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo date("F",strtotime($date.' - 1 Month')); ?></span></a>
                <h3 class="text-center text-uppercase"><?php echo date("F Y",strtotime($date)); ?></h3>
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getEndorsementCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>','<?php echo date("d",strtotime($date.' + 1 Month')); ?>');"><span class="mr-2"><?php echo date("F",strtotime($date.' + 1 Month')); ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__top-bar bg-light border-top mt-4 mt-lg-5 text-uppercase d-flex text-center ">
                <span class="top-bar__days  py-3 border-right">Mon</span>
                <span class="top-bar__days  py-3 border-right">Tue</span>
                <span class="top-bar__days  py-3 border-right">Wed</span>
                <span class="top-bar__days  py-3 border-right">Thu</span>
                <span class="top-bar__days  py-3 border-right">Fri</span>
                <span class="top-bar__days  py-3 border-right">Sat</span>
                <span class="top-bar__days  py-3">Sun</span>
            </div>

            <?php
                $dayCount = 1;
                $endorsementdata = $this->endorsementCalendar();
                echo '<div class="calendar__week text-center d-flex justify-content-around border-top">';
                for($cb=1;$cb<=$boxDisplay;$cb++){
                    if(($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)){
                        // Current date
                        $currentDate = $dateYear.'-'.$dateMonth.'-'.str_pad($dayCount, 2, '0', STR_PAD_LEFT);; 

                        // Get number of events based on the current date
                        
                        $filteredItems = array_filter($endorsementdata, function($item) use ($currentDate) {
                           return $currentDate >=$item['createdOn'] && $currentDate <=$item['createdOn'] ;
                        });
                       sort($filteredItems);
                        // Define date cell color
                        if(count($filteredItems) > 0){
							$today='';
							if(strtotime($currentDate) == strtotime(date("Y-m-d"))){
								$today = ' today bg-light';	
							}
                            ?>
                                <div class="calendar__day border-right event col flex-column d-flex p-0 <?php echo $today; ?>"  data-event="<?php echo $currentDate; ?>" data-start='<?php echo json_encode($filteredItems); ?>' onclick="getEvents('<?php echo $currentDate; ?>', '<?php json_encode($filteredItems); ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text" id="CalendarClassName">
                                        <?php
										$class_pop='';
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
											$tc =2;
											if(count($filteredItems)<4) {
												$tc = count($filteredItems);	
											}
                                            if($fi<$tc) {
                                            $test = $filteredItems[$fi]['name'];
                                        echo '<div class="classNames">';
                                        ?>
                                        <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal<?php echo $filteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?></a>                                    
                                            <?php
                                        echo "</div>";
                                        }
										$test = $filteredItems[$fi]['name'];
										$class_pop .= '<div class="classNames"><a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal'. $filteredItems[$fi]['id'].'" href="" style="font-size:11px;" >'.$test.'</a></div>';
										}
										if(count($filteredItems)>3){
											$more = count($filteredItems)-2;
											echo '<div class="classNames"><a id="class-pop" style="font-size:11px;" href="#" class="calendar-class badge">+'.$more.' more</a></div><div class="position-absolute class-pop bg-light py-2" style="display:none;"> <span class="calendar__date mt-auto calendar-text d-block mb-2 text-dark"><strong>'.$dayCount.'</strong></span>'.$class_pop.'</div>';
										} ?>
                                        </span>
                                </div>
                                <?php
                                        for($fi=0; $fi<count($filteredItems); $fi++){ 
                                            
                                            $test = $filteredItems[$fi]['name'];
                                            //$test = substr($test,0,20);
                                        ?>
                                                <div class="modal fade" id="exampleModal<?php echo $filteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header text-left d-flex align-items-center">
                                                    <img src="<?php echo $filteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $filteredItems[$fi]['title']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <p><strong>Created By :</strong> <?php echo $filteredItems[$fi]['createdBy']; ?></p>
                                                        <p><strong>Validity : </strong><?php echo $filteredItems[$fi]['validity']; ?></p>
                                                        <p><strong>Type :</strong> <?php echo $filteredItems[$fi]['objectType']; ?></p>
                                                        <p><strong>Price : $</strong><?php echo $filteredItems[$fi]['price']; ?></p>
                                                        
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                    <a href="<?php echo $endorse_detail_page_link;?>?endId=<?php echo $filteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                                    <?php echo $filteredItems[$fi]['register']; ?>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div> 
                                            <?php
                                        
                                        }?>
                                <?php
                            
                        }
                        
                        else{
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
          <div id="weekView" class="calendar__days col-12 pb-4 pt-5 px-1 px-lg-2 border">
            <?php
                    list($week_start_date, $week_end_date) = $this->x_week_range($postedDate);
                    $week_start_date = date("Y-m-d",strtotime($week_start_date.' +1 day'));
                    $week_end_date = date("Y-m-d",strtotime($week_end_date.' +1 day'));
                    $week_array = $this->date_range($week_start_date, $week_end_date);


            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getEndorsementCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' - 7 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2">Prev</span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getEndorsementCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' + 7 day')); ?>');"><span class="mr-2">Next</span><i class="fa fa-chevron-right"></i></a>
            
            <div class="calendar__top-bar bg-light mt-4 mt-lg-5 text-uppercase d-flex text-center bg-light">
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Mon</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Tue</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Wed</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Thu</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Fri</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Sat</span>
                <span class="top-bar__days border   py-2 py-md-4">Sun</span>
            </div>
            <div class="calendar__week text-center d-flex justify-content-around border-top pt-3">
            <?php 
                for ($i=0; $i <7 ; $i++) { 
                   
                        $currentDate = $week_array[$i];

                        // Get number of events based on the current date
                        
                        $weekfilteredItems = array_filter($endorsementdata, function($item) use ($currentDate) {
                            return $currentDate >=$item['createdOn'] && $currentDate <=$item['createdOn'] ;
                        });
                        sort($weekfilteredItems);
            ?>			
            
                        <div class="calendar__day border-right <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light border border-success'; } ?>" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
                            <span class="calendar__date mt-auto calendar-text"><?php echo date('d',strtotime($week_array[$i]));  ?></span>
                            <span class="calendar__task calendar__task--today small pt-lg-2 mb-auto calendar-text">
                            <?php if(count($weekfilteredItems) > 0){
                               				$class_pop='';
                                               for($fi=0; $fi<count($weekfilteredItems); $fi++){
                                                   $tc =2;
                                                            if(count($weekfilteredItems)<4) {
                                                                $tc = count($weekfilteredItems);	
                                                            }
                                                            if($fi<$tc) {
                                                $test = $weekfilteredItems[$fi]['name'];
                                            
                                            echo '<div class="classNames">';
                                            ?>
                                            <a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" href="" style="font-size:11px;" ><?php echo $test; ?></a>                                    
                                                 
                                                <?php
                                            if(count($weekfilteredItems) >1) { }
                                            echo "<br>";
                                            echo '</div>';
                                            }
                                            $test = $weekfilteredItems[$fi]['name'];
                                                        $class_pop .= '<div class="classNames"><a class="calendar-class badge" data-toggle="modal" data-target="#exampleModal'. $weekfilteredItems[$fi]['id'].'" href="" style="font-size:11px;" >'.$test.'</a></div>';
                                            }
                                            if(count($weekfilteredItems)>3){
                                                            $more = count($weekfilteredItems)-2;
                                                            
                                                            echo '<div class="classNames"><a id="class-pop" style="font-size:11px;" href="#" class="calendar-class badge">+'.$more.' more</a></div><div class="position-absolute class-pop bg-light py-2" style="display:none;"> <span class="calendar__date mt-auto calendar-text d-block mb-2 text-dark"><strong>'.date('d',strtotime($week_array[$i])).'</strong></span>'.$class_pop.'</div>';
                                                        }
                                            } ?>
                                            </span>
                                        </div>
                                        <?php   
                                        for($fi=0; $fi<count($weekfilteredItems); $fi++){
                                                $test = $weekfilteredItems[$fi]['name'];
                                            ?>
                                                    <div class="modal fade" id="exampleModal1<?php echo $weekfilteredItems[$fi]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header text-left d-flex align-items-center">
                                                    <img src="<?php echo $weekfilteredItems[$fi]['icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded rounded-circle"><h5 class="modal-title"  id="exampleModalLabel"><?php echo $weekfilteredItems[$fi]['title']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <p><strong>Created By :</strong> <?php echo $weekfilteredItems[$fi]['createdBy']; ?></p>
                                                        <p><strong>Validity : </strong><?php echo $weekfilteredItems[$fi]['validity']; ?></p>
                                                        <p><strong>Type :</strong> <?php echo $weekfilteredItems[$fi]['objectType']; ?></p>
                                                        <p><strong>Price : $</strong><?php echo $weekfilteredItems[$fi]['price']; ?></p>
                                                        
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                    <a href="<?php echo $endorse_detail_page_link;?>?endId=<?php echo $weekfilteredItems[$fi]['id']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                                                    <?php echo $weekfilteredItems[$fi]['register']; ?>
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
        <div id="dayView" class="calendar__days col-12 pb-4 pt-5 px-lg-5 border">
            <?php
                    $prev_date = date('D', strtotime($postedDate .' -1 day'));
                    $next_date = date('D', strtotime($postedDate .' +1 day'));
            ?>
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small btn-primary" style="left: 0; top: 0" onclick="getEndorsementCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' - 1 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo $prev_date; ?></span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small btn-primary" style="right: 0; top: 0" onclick="getEndorsementCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' + 1 day')); ?>');"><span class="mr-2"><?php echo $next_date; ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__week text-center justify-content-around" id="today_event">

            </div>

        </div>
    </div>
</div>
    </main>

<?php
wp_die();
}
// End Display Calendar layout data for Endorsement : Added by Gurpreet




public function x_week_range($date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    return array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next saturday', $start)));
}

public function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}


}

