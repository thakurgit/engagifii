<?php
/*
*Engagifii abstract class for handling AJAX request
* since v1.0.0
*/ 



class abstractModelEngagifii extends Engagifii_API {
    protected $dbObj;

    public function __construct() {
    global $wpdb;
    $this->dbObj = $wpdb;

    $ajax_actions = [
        ['endorsement', 'endorsementLoadGridData'],
        ['legislation', 'legislationLoadGridData'],
        ['legilslationFilters', 'legilslationFilters'],
        ['LegislationStaffanalysis', 'LegislationStaffanalysis'],
        ['LegislationVersions', 'LegislationVersions'],
		['LegislationVotes', 'LegislationVotes'],
        ['get_rollcall_details', 'get_rollcall_details'],
		['LegislationHistory', 'LegislationHistory'],
		['LegislationQuick', 'LegislationQuick'],
		['LegislationMaco', 'LegislationMaco'],
		['courses', 'courseLoadGridData'],
        ['coursesByPerson', 'courseLoadGridDataByPerson'],
        ['peopleList', 'peopleLoadGridData'],
        ['peopleFilters', 'peopleFilters'],
        //['peoplefiltercountdata', 'peoplefiltercountdata'],
        ['downloadsByPerson', 'downloadDataByPerson'],
        ['generateDownloads', 'generateDownloadsByPerson'],
        ['generateDownloadsByMemberIds', 'generateDownloadsByMemberIds'],
        ['generateAwardsDownloadsByMemberIds', 'generateAwardsDownloadsByMemberIds'],
        ['isCreditEarnedByParticipant', 'isCreditEarnedByParticipant'],
        ['checkPeopleRegistered', 'checkPeopleRegistered'],
        ['clearDownloads', 'clearDownloadsByPerson'],
        ['allReports', 'allReportsByPerson'],
        ['updateProfile', 'updateProfileByMember'],
        ['classes', 'classLoadGridData'],
        ['classesJS', 'classesDataJS'],
        ['classsearch', 'classSearchLoadGridData'],
        ['events', 'eventsLoadGridData'],
        ['eventFilters', 'eventFilters'],
        ['eventClassFilters', 'eventClassFilters'],
        ['eventsbyperson', 'eventsLoadGridDataByPerson'],
        ['classesbyperson', 'classesLoadGridDataByPerson'],
        ['eventfiltercountdata', 'eventCountFilterData'],
        ['eventClassFilterData', 'eventClassCountFilterData'],
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
        ['apisJson', 'apisJson'], //apis json url
        ['trainingCalendarGridData', 'trainingCalendarGridData'], 
        //end here
    ];

    foreach ($ajax_actions as $action) {
        add_action('wp_ajax_nopriv_' . $action[0], [$this, $action[1]]);
        add_action('wp_ajax_' . $action[0], [$this, $action[1]]);
    }
}
public function apisJson(){
	$env = $_POST['env'];
        $dataResponse = "https://engagifii.engagifii".$env.".com/assets/environment-config-1.0.json";
		$pJSON = file_get_contents($dataResponse);
        header("Content-Type: application/json");
		echo $pJSON;
        wp_die();	
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

        $start = isset($_POST['start']) && is_numeric($_POST['start']) ? (int) $_POST['start'] : 0;
        $length = isset($_POST['length']) && is_numeric($_POST['length']) && (int) $_POST['length'] > 0 ? (int) $_POST['length'] : 1;
        $startPageNum = (int) (($start / $length) + 1);
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
    public function eventClassCountFilterData()
    {

        //$postedData = $this->_eventsClassPostCountData();
        $postedData = $this->_prepareTrainingCalendarData();
		//print_r(json_encode($postedData)); die;
        $dataResponse = $this->submitApiRequest("Public/EventAndClassFilteredCount", $postedData, "POST", 'classes');
        header("Content-Type: application/json");   
        echo json_encode($dataResponse);
        wp_die();
    }
    
    public function _eventsClassPostCountData(){
       
        $searchValue = '';
       if (strlen($_POST['search']['value']) > 1) {
           $searchValue = $_POST['search']['value'];
       }
       
       $startPageNum = 1;//(int) (($_POST['start'] / $_POST['length']) + 1);
       //print_r("tesst");die;
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
        //print_r(json_encode($postedData)); die;
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
		// print_r(json_encode($postedData));
		// die;
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
        $classTypesShow = get_option( 'ebt_api_settings' )['class_type_visible_column_list'];
        $allclass = get_option( 'ebt_api_settings' )['allClasses'];
		if (empty($allclass)) {
    $allclass = ["Upcoming"];
} elseif ($allclass == 1) {
    $allclass = [];
}
        //print_r($allclass); die;
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
        $postData['filterBody']['classTypes'] = $classTypesShow;
        if (!empty($_POST['classTypes'])) {
            $filteredTypes = [];
            foreach ($classTypesShow as $type) {
                if (in_array($type, $_POST['classTypes'])) {
                    $filteredTypes[] = $type;
                }
            }
            $postData['filterBody']['classTypes'] = $filteredTypes;
        }
        $postData['filterBody']['registrationDateRange']['startDate'] = isset($_POST['minReg']) ? $_POST['minReg'] : '';
        $postData['filterBody']['registrationDateRange']['endDate'] = isset($_POST['maxReg']) ? $_POST['maxReg'] : '';
        $postData['filterBody']['createdDateRange']['startDate'] = isset($_POST['class_start_date']) ? $_POST['class_start_date'] : '';
        $postData['filterBody']['createdDateRange']['endDate'] = isset($_POST['class_end_date']) ? $_POST['class_end_date'] : '';
        $postData['filterBody']['classStates'] = $allclass;
        $postData['filterBody']['creditHour']['min'] = isset($_POST['minRange']) ? $_POST['minRange'] : '';
        $postData['filterBody']['creditHour']['max'] = isset($_POST['maxRange']) ? $_POST['maxRange'] : '';
        //print_r(json_encode($postData)); die;
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
        $courses_detail_page_link= site_url() .'/my-profile/my-transcript/course-details/';	 
		$classes_detail_page_link= site_url() .'/my-profile/my-transcript/class-detail/';	
        
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$titleColumn = $_POST['titleColumn'];
		$title = $_POST['columns'][$titleColumn]['search']['value'];
		$sortByColumn = $_POST['order'][0]['column'];
        $sortBy       = $_POST['columns'][$sortByColumn]['data'];
        $sortDirection = $_POST["order"][0]["dir"];
		$postedData = '{"itemCount":100,"pageNumber":1,"pageSize":10,"sortBy":"'.$sortBy.'","sortDirection":"'.$sortDirection.'","filterBody":{"filterRules":[],"searchText":"'.$title.'","startDate":"'.$startDate.'","endDate":"'.$endDate.'","groupById":"'.$_POST['profileId'].'","groupByType":3},"includeTotal":true}';
        $dataResponse = $this->submitApiRequest("CourseReport/CourseCreditPagingList", json_decode($postedData), "POST", 'reports');
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
        $courses_detail_page_link= site_url() .'/my-profile/my-transcript/course-details/';	 
		$classes_detail_page_link= site_url() .'/my-profile/my-transcript/class-detail/';	
		$postedData = $this->_preparePeopleData();
        //print_r(json_encode($postedData)); die;
        $dataResponse = $this->submitApiRequest("People/NewPeoplePagingList/", $postedData, "POST", 'dashboard');
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        //print_r(json_encode($collection)); die;
		if($_POST['countResult']=='true'){
			echo json_encode($totalcount);
        	wp_die();
			return;		
		}
        $request = $_GET;
        $data    = array();
        foreach ($collection as $key => $value) { 
            $nestedData = array();
            $classPopover      = '';
			$nestedData['people-select']='<input  type="checkbox" class="select-row" value="'.$value->people->id.'"/>';
			$nestedData['peoplename']='<div class="d-flex align-items-center">';
			if($value->people->imageThumbUrl && filter_var($value->people->imageThumbUrl, FILTER_VALIDATE_URL)){
				$nestedData['peoplename'].='<img style="max-width:40px; flex:0 0 40px" alt="'.$value->people->fullName.'" class="rounded-circle img-fluid mr-2" src="'.$value->people->imageThumbUrl.'">';	
			}else{
				$nestedData['peoplename'].='<i class="fas fa-user-circle mr-2" style="font-size:40px; color:#979797"></i>';
			}
            $nestedData['peoplename'] .= '<div><a class="text-nowrap" href="'.site_url().'/my-profile/?member='.$value->people->id.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->people->fullName.'</a></div>';
            $nestedData['email'] = '<a href="mailto:'.$value->people->email.'" style="text-decoration: none;" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">'.$value->people->email.'</a>';
            $nestedData['currentdepartment'] ='';	
			$nestedData['persontype'] =$value->people->personTypes[0]->name;
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
    public function _preparePeopleData(){
        // $postData = '{"itemCount":"50","pageNumber":1,"sortBy":"updated","sortDirection":"desc","filterBody":{"selectedDate":"2024-03-19","onlyFavorite":false,
        //     "searchText":"","search":[{"searchText":"","searchType":"searchText"},{"searchText":"","searchType":"searchEmailText"},{"searchText":"",
        //         "searchType":"searchContactText"}],"searchEmailText":"","searchContactText":"","pageNumber":1,"pageSize":"50",
        //         "allPeoplePermission":{"viewInstructor":false,"viewAllMembers":true,"viewStaff":false,"viewNonMembers":false,
        //             "viewOwnOrganizationMembers":true,"viewChildOrganizationMembers":false,
        //             "viewDeactivatedPeople":false,"sendEmailPer":true,"deletePer":false,"invitePersonPer":false,
        //             "addRemoveTagsPer":false,"viewDetail":true,"deactivatePeople":true,"viewExhibitor":false,"viewPublic":false},"filterRules":[]}}';
        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
		$titleColumn = $_POST['titleColumn'];
        $emailColumn = $_POST['emailColumn'];//emailColumn
       	$sortByColumn = $_POST['order'][0]['column'];
		
        $filterRules = array();
        if($_POST['positions']){
            $filterRules[]= array(                
                    'fieldId' => 'positions',
                    'filterType' => 1,
                    "selectedValues"=> $_POST['positions'],               
            );
        }
        if($_POST['departments']){
            $filterRules[]= array(                
                    'fieldId' => 'departments',
                    'filterType' => 1,
                    "selectedValues"=> $_POST['departments'],               
            );
        }
        if($_POST['orgs']){
            $filterRules[]= array(                
                    'fieldId' => 'currentOrganization',
                    'filterType' => 1,
                    "selectedValues"=> $_POST['orgs'],               
            );
        }
        if($_POST['status']){
            $filterRules[]= array(                
                    'fieldId' => 'status',
                    'filterType' => 4,
                    "selectedValues"=> $_POST['status'],               
            );
        }
		if($_POST['totalTime']){
			$filterRules[]= array(                
                    'fieldId' => 'totalTimeWorked',
                    'filterType' => 202,
                    "selectedValues"=> $_POST['totalTime'],               
            );
		}

        $postData = array(
            'itemCount' => $_POST['length'],
            'sortBy' => $_POST['columns'][$sortByColumn]['data'],
            //'sortBy' => "updated",
            'pageNumber' => $startPageNum,
            'pageSize' => ((int) $_POST['length']),
            'sortDirection' => $_POST['order'][0]['dir'],
            'filterBody' => array(
                'selectedDate' => "2024-03-19",
                'onlyFavorite' => false,
       			'searchText' => $_POST['columns'][$titleColumn]['search']['value'],
                'search' => array(
                    array(
                        'searchText' => '',
                        'searchType' => "searchText"
                    ),
                    array(
                        'searchText' => $_POST['columns'][$emailColumn]['search']['value'],
                        'searchType' => "searchEmailText"
                    ),
                    array(
                        'searchText' => '',
                        'searchType' => "searchContactText"
                    )
                ),
                'searchEmailText' => $_POST['columns'][$emailColumn]['search']['value'],
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
                'filterRules' => $filterRules

            )
        );
        
      
    return $postData;
    }
	/* public function peoplefiltercountdata(){
        $postedData = $this->_preparePeopleData();
        $dataResponse = $this->submitApiRequest("People/NewPeoplePagingList/", $postedData, "POST", 'dashboard');
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        header("Content-Type: application/json");
		echo json_encode($totalcount);
        wp_die();	 
	 } */   
    public function peopleFilters(){
        $postData=array();
        $htmlArray = array();
          $filterParams = $_POST['filterParams'];
          //print_r($filterParams); die;
          $apiUrl='';
          $date = date('Y-m-d');
          foreach ($filterParams as $keys => $values) {
			  $response = '';
			  $apiUrl = '';
              if($values =='Department'){
                $apiUrl='tenantdepartment/GetAllTenantDepartmentsLite/'.$date; 
              }else if($values =='Position'){
                $apiUrl='Organization/GetAllOrganizationPositionsLite/'.$date;   
              }else if($values =='Organization'){
                $apiUrl='Organization/GetOrganizationListWithIdForFilters/'.$date;   
              }else if($values =='Total Time'){
				  continue;
			  }
             if($values =='Status'){
				$response =[
                'api_response' => '[
                    {
                        "id" : "1",
                        "name" : "Active"
				 },
                    {
                        "id" : "2",
                        "name" : "Deactivated"
					}
                ]'
            ]; 
			 }else{
           		 $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
			 }
            
              if($response['api_response']){
                $response = json_decode($response['api_response'], true);                
                if($response){
                  foreach ($response as $key => $value) {
                      if($values =='Department'){
                          $html[$values].='<li><div class="form-check"><input id="department_'.$key.'" class="form-check-input" type="checkbox" name="peopleDepartement[]" value="'.$value['id'].'"> <label class="form-check-label" class="" for="department_'.$key.'"><small> '.addslashes($value['name']).'</small></label></div></li>';	
                      }else if($values =='Position'){
                          $html[$values].= '<li><div class="form-check"><input  type="checkbox" name="peoplePosition[]" id="position_'.$key.'" value="'.$value['id'].'" class="form-check-input"> <label class="form-check-label" for="position_'.$key.'"><small>'.addslashes($value['name']).'</small></label></div></li>';	
                      }else if($values=='Organization'){
                        $html[$values].= '<li><div class="form-check"><input type="checkbox" name="peopleOrganization[]" id="organization_'.$key.'" value="'.$value['id'].'" class="form-check-input"> <label class="form-check-label" for="organization_'.$key.'"><small> '.addslashes($value['name']).'</small></label></div></li>';
                      }
                      else if($values=='Status'){
                        $html[$values].= '<li><div class="form-check"><input type="checkbox" name="peopleStatus[]" id="status_'.$key.'" value="'.$value['name'].'" class="form-check-input"> <label class="form-check-label" for="status_'.$key.'"><small> '.addslashes($value['name']).'</small></label></div></li>';
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
   public function generateDownloadsByPerson(){
        
        $postedData = array();
		$postedData['itemCount']=0;
		$postedData['sortBy']='name';
		$postedData['sortDirection']='asc';
        $postedData['sourceType']=2;
		$postedData['filterBody']['groupById']=$_POST['groupById'];
		$postedData['filterBody']['filterRules'][0]['fieldId']='courses';
		$postedData['filterBody']['filterRules'][0]['filterType']=1;
		$postedData['filterBody']['filterRules'][0]['selectedValues']=$_POST['CourseId'];;
		$postedData['filterBody']['startDate']= $_POST['selectedStartDate']; 
		$postedData['filterBody']['endDate']= $_POST['selectedEndDate'];
		$postedData['filterBody']['groupByType']=3;
		//print_r(json_encode($postedData));
		//die;
        $dataResponse = $this->submitApiRequest("CourseReport/GenerateCreditsEarnedGroupByCoursesPDFReport", $postedData, "POST", 'reports');
        $collection = json_decode($dataResponse['api_response'])->result;
        echo json_encode($dataResponse);
        wp_die();
    }
    public function generateDownloadsByMemberIds(){
        $postedData = array();
        $postedData['itemCount'] = 100;
        $postedData['sortBy'] = 'name';
        $postedData['sortDirection'] = 'asc';
        $postedData['sourceType']=2;
        $postedData['filterBody']['groupByType'] = 1;
        $postedData['filterBody']['reporttype'] = 2;
        //$postedData['filterBody']['filterRules'][0]['fieldId'] = 'peopleids';
        //$postedData['filterBody']['filterRules'][0]['filterType'] = 1;
        $postedData['filterBody']['groupById'] = $_POST['molOrgId'];
        //$postedData['filterBody']['filterRules'][0]['selectedValues'] = $_POST['memberIds'];
		if($_POST['memberIds']=='all'){
			$postedData['filterBody']['filterRules'][0]['selectedValues']='';	
			$postedData['filterBody']['filterRules'][0]['fieldId'] = '';
		} 
        $postedData['filterBody']['startDate']= $_POST['selectedStartDate']; 
		$postedData['filterBody']['endDate']= $_POST['selectedEndDate'];
		//print_r(json_encode($postedData));
		//die;
        $dataResponse = $this->submitApiRequest("PeopleReport/GenerateCreditsEarnedGroupByParticipantsPDFReport", $postedData, "POST", 'reports');
        $collection = json_decode($dataResponse['api_response'])->result;
        echo json_encode($dataResponse);
        wp_die();
    } 
    public function generateAwardsDownloadsByMemberIds(){
        $postedData = array();
        $postedData['pageNumber'] = 1; 
        $postedData['pageSize'] = 10; 
        $postedData['itemCount'] = 100; 
        $postedData['sortBy'] = 'asc'; 
        $postedData['sortDirection'] = 'asc';         
        $postedData['filterBody']['peopleIds'] = $_POST['memberIds'];
        if($_POST['memberIds']=='all'){
			$postedData['filterBody']['peopleIds']='';	
		} 
		$postedData['isFiscalYearAvailable']  = "false";
        $postedData['filterBody']['sourceType']= 2;
        //print_r(json_encode($postedData));
		//die;
        $dataResponse = $this->submitApiRequest("Awards/GenerateAllCertificationForPeoplePDFReport", $postedData, "POST", 'awards');
        $collection = json_decode($dataResponse['api_response'])->result;
        echo json_encode($dataResponse);
        wp_die();
    }
    

    public function isCreditEarnedByParticipant(){
		$postData=array();
		$responseArray = array();
		$postData['participantsIds'] = $_POST['participantsIds'];
        if($_POST['participantsIds']=='all'){
            $postData['participantsIds'] = [];
        }
		$postData['startDate'] = $_POST['startDate']; 
		$postData['endDate'] = $_POST['endDate']; 
		$apiUrl = 'registration/IsCreditsAvailableByParticipants/';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'POST', 'awards');
		//$responseArray = json_decode($response['api_response'], true);
		echo json_encode($response);
        wp_die();
	} 
    
    public function checkPeopleRegistered(){
		$postData=array();
		$responseArray = array();
		$postData['peopleId'] = $_POST['peopleId'];
        if($_POST['peopleId']=='all'){
            $postData['peopleId'] = [];
        }
		$apiUrl = 'AwardsRegistration/CheckPeopleRegistered/';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'POST', 'awards');
		//$responseArray = json_decode($response['api_response'], true);
		echo json_encode($response);
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
		$postedData = '{"itemCount":10,"sortBy":"'.$sortBy.'","sortDirection":"'.$sortDirection.'","pageNumber":"'.$pageNumber.'","filterBody":{"sourceType" :2,"reportName":"'.$title.'","status":[],"fromDate":"","toDate":""}}';
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
			$nestedData['download-select']='<div class="dropdown"><button type="button"  class="btn btn-sm shadow-none" data-toggle="dropdown"><img src="'.$download_icon.'" class="img-icon-lg rounded-circle img-fluid mr-2" alt="downlaod" style="width:20px;"/ ></button><div class="dropdown-menu py-1"><a class="dropdown-item px-2" target="_blank" style="color:#2196F3;"  href="'.$value->reportLink.'">Download</a><a class="dropdown-item px-2 deleteReport" href="" style="color:#2196F3;"  report-id="'.$value->id.'">Delete</a></div></div>';
            $nestedData['filename'] = '<a class="d-flex align-items-center" target="_blank" href="'.$value->reportLink.'">'.$value->reportName.'</a>';
			}else{
			$nestedData['download-select']='<div class="dropdown"><button type="button" report-id="'.$value->id.'" class="btn btn-sm shadow-none" data-toggle="dropdown"><img src="'.$download_icon.'" class="img-icon-lg rounded-circle img-fluid mr-2" alt="downlaod" style="width:20px;"/ ></button><div class="dropdown-menu py-1"><a class="dropdown-item px-2 deleteReport" style="color:#2196F3;"  href="" report-id="'.$value->id.'">Delete</a></div></div>';
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
        //$postedData = '{"filterBody":{sourceType:2,"peopleId":"'.$_POST['profileId'].'","awardId":"'.$_POST['awardId'].'","courseSortDirection":"asc"}}'; 
        $postedData['filterBody']['sourceType']= 2;
		$postedData['filterBody']['peopleId'] =$_POST['profileId'];
		$postedData['filterBody']['awardId']=$_POST['awardId'];
        $postedData['filterBody']['courseSortDirection'] = "asc";
       	 $dataResponse = $this->submitApiRequest("Awards/generateCertificationPDFReport", $postedData, "POST", 'awards');
		}else{
        //$postedData ='{"pageNumber":1,"pageSize":10,"itemCount":100,"sortBy":"name","sortDirection":"asc","filterBody":{"peopleId":"'.$_POST['profileId'].'","courseSortDirection":"asc","isFiscalYearAvailable":false, sourceType:2}}';
        $postedData['pageNumber'] = 1; 
        $postedData['pageSize'] = 10; 
        $postedData['itemCount'] = 100; 
        $postedData['sortBy'] = 'name'; 
        $postedData['sortDirection'] = 'asc';         
        $postedData['filterBody']['peopleId'] = $_POST['profileId'];
		$postedData['courseSortDirection'] = 'asc';
        $postedData['isFiscalYearAvailable']  = "false";
        $postedData['filterBody']['sourceType']= 2;
        $dataResponse = $this->submitApiRequest("Awards/GenerateAllCertificationPDFReport/".$_POST['profileId']."", $postedData, "POST", 'awards');
		}
		
         //print_r($postedData); die;
        $response = json_decode($dataResponse['api_response']);
       
        //print_r($dataResponse); die;
        return $response;
        wp_die();
    }
	public function updateProfileByMember(){
		$postedData =array();
		$postedData =stripslashes($_POST['payload']);
		$postedData = json_decode($postedData, true);
         //print_r($postedData); die;
       	 $dataResponse = $this->submitApiRequest("PeopleApproval/CreateRequest", $postedData, "POST", 'dynamicobject');
        $response = json_decode($dataResponse);
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
		//print_r(json_encode($postedData));
		//die;     
        $dataResponse = $this->submitApiRequest("public/listEventsByFilter", $postedData, "POST", 'event');
        $collection = json_decode($dataResponse['api_response'])->collection;
        $totalcount   = json_decode($dataResponse['api_response'])->pagingModel->totalRecords;
        
        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
        $env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
	    $front_pages = $options['front_pages'];
        $events_detail_page = $front_pages['events_detail_page'];
	    if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	    }else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	    }
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii'.$env.'com';
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
           
             $filterTag = $row->tags;
            // $allTags = array_diff($filter, array('PUBLIC', 'public', 'Public'));
            // $filterTag = array_values($allTags);
             $default_Tags = array();
             if (is_array($filterTag) && count($filterTag)) {

                 $allTags = array();
                
                 foreach ($filterTag as $index => $tag) {
						$default_Tags[$index] = new stdClass();
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
                 $nestedData['tags'] = [];
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
    public function trainingCalendarGridData(){
        $postedData = $this->_prepareTrainingCalendarData();
      // print_r(json_encode($postedData)); die;
        $dataResponse = $this->submitApiRequest("public/EventClassPagingList", $postedData, "POST", 'classes');
        $collection = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        
        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
        $env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
	    $front_pages = $options['front_pages'];
        $events_detail_page = $front_pages['events_detail_page'];
	    if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	    }else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	    }
        $classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii'.$env.'com';
        $endorsement_visib_datacol_list = $options['endorsement_visib_datacol_list'];

        $data = array(); 
        $nestedData = array();
 //print_r($classes_detail_page_link); die;

 foreach ($collection as $key => $row) {
   // print_r($row->entity);die;
    $contactPopOver = '';
    $locationPopOver = '';
    $entity = $row->entity;
    $locationCount = 0;
    foreach($row->eventDates as $key => $location) {
        if($location->cityName){
            $locationCount += 1;
        }
    }
if($entity=="Event"){
    if(count($row->eventDates)) {
       $locationPopOver = $this->_popOverTrainingCalLocationData($key, $row->eventDates);
       $classPopover = $this->_popOverTrainingData1($key, $row->eventDates);
    }
}else{  
   if (count($row->classSessions)) {
       $classPopoverClass = $this->_popOverClassData1($key, $row->classSessions);
       
   }else{
    $trainingDataVal = [
        'startDateTime' => $row->startDateTime,
        'endDateTime' => $row->endDateTime
    ];
    $classPopoverClass = $this->_popOverTrainingData1($key, $trainingDataVal);
   // $classPopoverClass = $this->_popOverTrainingData1($key, $row->startDateTime);
   }
}
   
    $default_Title = $row->name;
    $default_Id = $row->id;
    $default_Detailpage = '<div class="d-flex align-items-center">
                           <img src="'.$row->imageUrl.'" class="img-fluid img-icon-lg mr-3" alt="Image icon">';
                           
            if ($entity == 'Event') {
                $default_Detailpage .= '<a href="' . $events_detail_page_link . '?endId=' . $default_Id . '">' . $default_Title . '</a>';
            } elseif ($entity == 'Class') {
                $default_Detailpage .= '<a href="' . $classes_detail_page_link . '?classId=' . $default_Id . '">' . $default_Title . '</a>';
            }

$default_Detailpage .= '</div>';

    $nestedData['name'] = $default_Title ? $default_Detailpage : "N/A";
    $nestedData['Type'] = $row->type;
    $nestedData['entity'] = $entity;
    
    //$nestedData['eventWithClass'] = "<span class='text-center'>".$eventDetails->eventWithClass."</span>";
    //$nestedData['class'] = '<div class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '">
                           // <img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" alt="class-icon" class="img-icon-lg">
                            //<span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$value->courseClassesCount.'</span></div>'.$classPopover;

    // Dates processing
    if ($row->startDateTime) {
        $startDate = date('M d, Y', strtotime($row->startDateTime));
        $startTime = date('g:i A', strtotime($row->startDateTime));
    }
    if ($row->endDateTime) {
        $endDate = date('M d, Y', strtotime($row->endDateTime));
        $endTime = date('g:i A', strtotime($row->endDateTime));
    }
    //$nestedData['startDateTime'] = '<span style="display:none;">'.strtotime($startDate).'</span>'. $startDate." at ".$startTime." - ".$endDate." at ".$endTime;
    if($entity=="Event"){
    $nestedData['startDateTime'] = '<span style="display:none;">' . strtotime(date('M d, Y', strtotime($startDateTime))) . '</span>
<div class="dropdown">
   <div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_1" data-placement="left" data-containerid="" id="">
       <img src="' . ENGAGIFII_ASSETS_URL . '/images/class.png" class="img-icon-lg img-fluid" alt="class-icon">
       <span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">' . count($row->eventDates) . '</span>
   </div>' . $classPopover . '
</div>';
    }
    else{
if (count($row->classSessions)) {
           $nestedData['startDateTime'] = '<span style="display:none;">' . strtotime(date('M d, Y', strtotime($classSessionStartDate))) . '</span>
               <div class="dropdown">
                   <div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_' . $key . '" data-placement="left" data-containerid="' . $key . '" id="' . $key . '">
                       <img src="' . ENGAGIFII_ASSETS_URL . '/images/class.png" class="img-icon-lg img-fluid" alt="class-icon">
                       <span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">' . count($row->classSessions) . '</span>
                   </div>' . $classPopoverClass . '
               </div>';
       }
       else{
        $sesionCountDefault  = 1;
         //$nestedData['startdate'] = '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($row->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" >';
         $nestedData['startDateTime'] = '<span style="display:none;">' . strtotime(date('M d, Y', strtotime($row->startDateTime))) . '</span>
                <div class="dropdown">
                    <div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_' . $key . '" data-placement="left" data-containerid="' . $key . '" id="' . $key . '">
                        <img src="' . ENGAGIFII_ASSETS_URL . '/images/class.png" class="img-icon-lg img-fluid" alt="class-icon">
                        <span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">' . $sesionCountDefault .'</span>
                    </div>' . $classPopoverClass . '
                </div>';
        }
    }
    if($locationCount>0){
        $nestedData['city'] = '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.$locationCount.'</span></div>'.$locationPopOver.'</div>';
    }
    else{
    $nestedData['city'] = '<div class="dropdown"><div class=" instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/Location_Specified.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter: grayscale(1);"><span style="visibility: hidden;" class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center"></span></div></div>';
    }
    // Courses and status
    //$nestedData['courseCount'] = $eventDetails->courses ?: '<div class="course-badge"><img src="'.ENGAGIFII_ASSETS_URL.'/images/course-icon.png" class="img-circle" alt="course-icon"></div>';
    $nestedData['eventStatus'] = preg_replace('/(?<!\ )[A-Z]/', ' $0', $row->status);
    //print_r($eventDetails->eventStatus);die;
    // Registration button logic
    $registration_state = $row->registrationState;
    $event_status = $nestedData['eventStatus'];
    $default_RegisterBtn = '';
    if($entity=="Event"){
    if ($event_status == 'Completed' || in_array($registration_state, ['RegistrationClosed', 'RegistrationNotStarted'])) {
        $tooltip = preg_replace('/(?<!\ )[A-Z]/', ' $0', $registration_state);
        $default_RegisterBtn .= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$tooltip.'">
                                 <button type="button" id="eventsregister" class="btn btn-primary px-3 py-1" disabled style="pointer-events: none;">Register</button></span>';
    } else {
        $default_RegisterBtn .= '<a href="'.$tenant_url.'/pages/events/'. $default_Id .'/general" target="_blank" class="btn btn-primary px-3 py-1">Register</a>';
    }
    $nestedData['register'] = $default_RegisterBtn;
    }
    if($entity=="Class"){
         # Registration Button Logic
         if($row->isClassRegistrationAllow ) //|| $row->registrationWorkFlowId
         {
           if($row->registrationState !== 'Registration Not Setup' && $row->registrationState !== 'Registration Closed' && $row->registrationState!== 'Sold Out' && $row->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
           {
                if($row->locationType->name=="onlocation")
                   { 
                   $nestedData['register'] = '<a href="'.$row->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                   //$nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                   }
                   elseif($row->locationType->name=="online"){
                       $nestedData['register'] = '<a href="'.$row->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                   }
                   elseif($row->locationType->name=="onlocationandonline"){
                   $nestedData['register'] = '<a style="white-space:nowrap" href="'.$row->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1 mb-2" target="_blank" >Register Online</a><br/><a style="white-space:nowrap" href="'.$row->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank" >Register in person</a>';
                   }
               else{
                   $nestedData['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Class Location not defined"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
               }
           }
           else{
           $nestedData['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$row->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
           }
       }else{
         $nestedData['register'] = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$row->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
         //$data[] = $nestedData;    
       }
    }

    // Tags processing
    // $filter = $eventDetails->tags;
    // $allTags = array_diff($filter, ['PUBLIC', 'public', 'Public']);
    // if (count($allTags)) {
    //     $nestedData['tags'] = $this->_generateTagPopover($key, $allTags);
    // } else {
    //     $nestedData['tags'] = "";
    // }

    $data[] = $nestedData;

 }
   // print_r($data); die;
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
    $options = get_option('ebt_api_settings');
	$events_type_visible_column_list = $options['events_type_visible_column_list']??array();
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
                    if(in_array($value['value'], $events_type_visible_column_list)){
					$html[$values].= '<li class="d-flex align-items-start"><input type="checkbox" name="eventsType[]" id="event_'.$key.'" value="'.$value['value'].'" class="mr-2 mt-1"> <label for="event_'.$key.'"><small> '.addslashes($value['text']).'</small></label></li>';
                    }
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

public function eventClassFilters(){
	$postData=array();
	$htmlArray = array();
	  $filterParams = $_POST['filterParams'];
      $module = "event";
	  $apiUrl='';
	  $date = date('Y-m-d');
	  foreach ($filterParams as $keys => $values) {
		  if($values =='startDateTime'){
			$apiUrl='event/GetMinMaxEventDate/'.$date; 
		  }else if($values =='tags'){
			$apiUrl='public/tags/1/1';  
		  }else if($values =='Type'){
			$apiUrl='public/GetEventClassTypesForFilter/'.$date;
            $module = "classes";
		  }else if($values =='city'){
			$apiUrl='public/venues';  
            $module = "event";
		  }
          elseif($values =='classType'){
			//$apiUrl='public/GetObjectTypesForFilter/'.$date;
		  }
		  $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', $module);
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
				  }else if($values=='Type'){
					$html[$values].= '<li class="d-flex align-items-start"><input type="checkbox" name="eventsType[]" id="event_'.$key.'" value="'.$value['text'].'" class="mr-2 mt-1"> <label for="event_'.$key.'"><small> '.addslashes($value['text']).'</small></label></li>';
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
      //print_r($htmlArray); die;
		echo json_encode($htmlArray);
        wp_die();
	
}
public function classesLoadGridDataByPerson(){
    $siteURL= site_url();
        
        $postedData  = $this->_prepareClassData();
		// print_r(json_encode($postedData));
		// die;
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
		$classes_detail_page_link= site_url() .'/my-profile/classes/class-details/';	
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
    //Load event list by person
    public function eventsLoadGridDataByPerson(){
    $options = get_option('ebt_api_settings');
    $loggedInUserId = $_SESSION['pid'];
    //$tenantCode = $options['ebt_tenant_code']['tenant_code'];
	$tenantCode = $options['dashboard_tenant_code'];
	$env = $options['engagifii_apis']['environment']? $options['engagifii_apis']['environment'] : '';
    //print_r($loggedInUserId); die;
	
	$front_pages = $options['front_pages'];
    $events_detail_page = $front_pages['events_detail_page'];
	$events_detail_page_link= site_url() .'/my-profile/events/event-detail/';	 
	     $postedData = $this->_prepareEventsData();
		// print_r(json_encode($postedData));
		// die;
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
        //$dataResponse = $this->submitApiRequest("event/list", $postedData, "POST", 'event');
		 //print_r($userPermissionArray);
		 //die;
        //if(in_array('sessions', $class_visible_column_list))
        $dataResponse = $this->submitApiRequest("event/list", $postedData, "POST", 'event');
		 //print_r($dataResponse['api_response']);
		// die;
        $collection = json_decode($dataResponse['api_response'])->collection;
        $totalcount   = json_decode($dataResponse['api_response'])->pagingModel->totalRecords;
        
        header("Content-Type: application/json");
        $request = $_GET;

        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = 'https://'.$options['evt_tenant_code']['engagifii_url'].'.engagifii'.$env.'com';
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
            $default_Detailpage .= '<div class="d-flex align-items-center"><img src="'.$row->imageUrl.'" class="img-fluid img-icon-lg mr-3" alt="award-icon"><a href=' . $events_detail_page_link.'?endId=' . $default_Id . '&wId='.$row->registrationWorkflows[0]->registrationWorkflowId.'&rId='.$row->registrationWorkflows[0]->roleId.'&attendeeCount='.$row->attendeesCount.' >' . $default_Title . '</a></div>';
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
                        $url = 'https://'.$tenantCode.'.engagifii'.$env.'.com/auth-callback/pages/home#access_token='.$_SESSION['accesstoken'].'&source=external&tpath=pages/events/'. $default_Id .'/'.$row->registrationWorkflows[0]->registrationWorkflowId.'/'.$row->registrationWorkflows[0]->roleId.'/eventregpub/signup/overview';
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
           
            // $filter = $row->tags;
            // $allTags = array_diff($filter, array('PUBLIC', 'public', 'Public'));
            // $filterTag = array_values($allTags);
            // $default_Tags = array();
            // if (count($filterTag)) {

            //     $allTags = array();
                
            //     foreach ($filterTag as $index => $tag) {

            //         $default_Tags[$index]->tagName = $tag;
            //         $default_Tags[$index]->id =$index;
            //     }
                
            //     foreach ($default_Tags as $index => $value) {
                   
            //         if(count($default_Tags) > 1 && $index == 0)
            //         {   
                        
            //             $tagPopover =  $this->_popOverTagData1($key, $default_Tags);
            //              $tagCount   = count($default_Tags) - 1;
       		// 	$allTags[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$value->tagName.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span>'.$tagPopover.'</div>';
					
            //         }
            //         elseif(count($default_Tags) == 1)
            //             $allTags[] = $value->tagName;

            //     }
            //    $nestedData['tags'] = $allTags;
            // }else{
            //     $nestedData['tags'] = "";
            // }

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
        //print_r($data);die;
        echo json_encode($json_data);
        wp_die();
    }

    // Events Grid End here

    public function legislationLoadGridData() {
        $postedData = $this->_prepareLegislationPostData();
		$dataResponse = $this->submitApiRequest("legislative/public-bills/list",$postedData,"POST",'legislation');
        $collection = json_decode($dataResponse['api_response']);
        //print_r($dataResponse); die;
        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
        $tenantCode = $options['dashboard_tenant_code'];
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
    if($tenantCode=="clemson"){
        $bill_detail_link="https://www.scstatehouse.gov/billsearch.php";
        //$billIdText = ""
    }
        $lbt_api_url = $options['lbt_api_url'];


        $data = array();
        $sponsorsName = array();
        $bill_array   = array();
        foreach ($collection->collection as $key => $row) {
            $fBillNumber = preg_replace('/[^0-9]/', '', $row->billNumber);
        
            $nestedData = array();
            if ($row->lastActionOn == null) {
                $lastActionOnnew_Date = '';
            } else {
                $lastActionOndefault_Date = $row->lastActionOn;
                $lastActionOnconvert_Date = strtotime($lastActionOndefault_Date);
                $lastActionOnnew_Date = date('M d, Y', $lastActionOnconvert_Date);
            }
        
            $default_Date = $row->introducedDate;
            $convert_Date = strtotime($default_Date);
            $new_Date = date('M d, Y', $convert_Date);
        
            $bill_array[$key] = $row->id;
        
            $billHtml = "";
        
            // Check if tenantCode is 'clemson'
            if ($tenantCode == "clemson") {
                $billIdParam = $fBillNumber;
                $billQueryParam = 'billnumbers';
            } else {
                $billIdParam = $row->id;
                $billQueryParam = 'billId';
            }
        
            if (isset($options['lbt_title_display_setting'])) {
                if ($options['lbt_title_display_setting'] == 'alternate') {
                    if ($row->alternateTitle) {
                        $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->alternateTitle . '</a>';
                    } else {
                        $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->title . '</a>';
                    }
                } elseif ($options['lbt_title_display_setting'] == 'alternate-top') {
                    if ($row->alternateTitle) {
                        $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->alternateTitle . '<br/>' . $row->title . '</a>';
                    } else {
                        $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->title . '</a>';
                    }
                } elseif ($options['lbt_title_display_setting'] == 'title-top') {
                    $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->title . '<br/>' . $row->alternateTitle . '</a>';
                } elseif ($options['lbt_title_display_setting'] == 'title') {
                    $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->title . '</a>';
                }
            } else {
                $billHtml = '<a class="bill-title" href="' . $bill_detail_link . '?' . $billQueryParam . '=' . $billIdParam . '" >' . $row->title . '</a>';
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
            if($tenantCode=="clemson"){
                $nestedData["billNumber"] = '<a class="bill-title" href=' . $bill_detail_link.'?billnumbers=' . $fBillNumber . ' >'.$row->billNumber.'</a>';
            }else{
                $nestedData["billNumber"] = '<a class="bill-title" href=' . $bill_detail_link.'?billId=' . $row->id . ' >'.$row->billNumber.'</a>';
            }
           
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
                         $houseCom = '<div class="d-flex align-items-center"><div class="flex-1 pr-2" > '.$house_name . '</div>' . '<span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-auto  house_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $houseCommitteesCount . ' </span></div>' . $houseLists;


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

                        $senateCom = '<div class="d-flex align-items-center"><div class="flex-1 pr-2"> '.$senate_name . '</div>' . '<span class="badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle ml-auto  senate_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $senateCommitteesCount . ' </span></div>' . $senateLists;


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
    public function legilslationFilters(){
        $postData=array();
        $htmlArray = array();
          $filterParams = $_POST['filterParams'];
          $session = '';
		  if($_POST['session']){
			$session = '?sessionId='.$_POST['session'];  
		  }
		  if(in_array("assignedto", $filterParams)){
			$filterParams[]='assignedtoGroups';  
			$filterParams[]='assignedtoTags';
		  }
		  $options = get_option('ebt_api_settings');
		  $lbt_visib_tags_list = $options['lbt_visib_tags_list']??array();
		  $lbt_visib_members_list = $options['lbt_visib_members_list']??array();
		  $lbt_visib_groups_list   = $options['lbt_visib_groups_list'] ?? array();
		  $lbt_visib_members_tags_list = $options['lbt_visib_members_tags_list'] ?? array();
          $apiUrl='';
          $date = date('Y-m-d');
          foreach ($filterParams as $keys => $values) {
			  $response = '';
			  $apiUrl = '';
              if($values =='trackingLevel'){
                $apiUrl='legislative/public-bills/trackinglevels'.$session;  
              }else if($values =='billType'){
                $apiUrl='legislative/public-bills/billtypes'.$session;   
              }else if($values =='status'){
                $apiUrl='legislative/public-bills/status'.$session;     
              }else if($values =='sponsors'){
                $apiUrl='legislative/public-bills/sponsors'.$session;   
              }else if($values =='houseCommittees'){
                $apiUrl='legislative/public-bills/committees/house'.$session;   
              }else if($values =='senateCommittees'){
                $apiUrl='legislative/public-bills/committees/senate'.$session;   
              }else if($values =='assignedto'){
               $apiUrl='legislative/public-bills/filter/billusers'.$session;  
              }else if($values =='assignedtoGroups'){
				 $apiUrl='legislative/public-bills/filter/groups'.$session; 
              }else if($values =='assignedtoTags'){
				 $apiUrl='legislative/public-bills/filter/billusertags'.$session; 
              }else if($values =='tags'){
                $apiUrl='legislative/public-bills/filter/tags'.$session;  
              }else if($values =='lastActionOn'){
                $apiUrl='legislative/public-bills/lastactions'.$session;   
              }else if($values =='introducedDate'){
				  continue;
			  }
           	$response =  $this->submitApiRequestWithGet($apiUrl, $postData, 'legislation'); 
             /*if($values =='assignedto'){
				$response =[
                'api_response' => '[
                    {
                        "id" : "1",
                        "name" : "Active"
				 },
                    {
                        "id" : "2",
                        "name" : "Deactivated"
					}
                ]'
            ]; 
			 }else{
           		 $response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
			 }*/
            if($response['api_response']){
                $response = json_decode($response['api_response'], true);                
                if($response){
                  foreach ($response as $key => $value) {
                      if($values =='billType'){
                          $html[$values].='<li data-title="'.$value['text'].'" data-id="'.$value['value'].'"><label class="d-none" for="item_id_'.$value['value'].'">bill type</label><input type="checkbox" name="enggafifilterdata[]" value="'.$value['value'].'" id="item_id_'.$value['value'].'"> '.$value['text'].'</li>';	
                      }else if($values =='trackingLevel' && $value['count']>0){
						  	$checked = $_POST['chkdTracking'] == $value['trackingLevelId'] && $_POST['chkdTracking'] != null ? 'checked disabled' : '';
							$html[$values].= '<li data-count="'.$value['count'].'" data-title="'.$value['title'].'" data-id=""><input type="checkbox" name="enggafifilterdata[]" value="'.$value['trackingLevelId'].'" id="tracking_item_id_'.$value['trackingLevelId'].'"  '.$checked.'><span style="background-color:'.$value['colorCode'].'; width: 13px;height: 13px;border-radius: 50%;display: inline-block;margin-left: 8px;"></span> '.$value['title'].'</li>';	
                      }else if($values=='status' || $values=='sponsors'){
                        $html[$values].= '<li data-title="'.$value['text'].'" data-id="'.$value['value'].'"><input type="checkbox" name="enggafifilterdata[]" value="'.$value['value'].'" id="'.$values.'_item_id_'.$value['value'].'" > '.$value['text'].'</li>';
                      }else if($values=='houseCommittees' || $values =='senateCommittees'){
                        $html[$values].= '<li data-title="'.$value['text'].'" data-id="'.$value['value'].'"><input type="checkbox" name="enggafifilterdata[]" value="'.$value['value'].'" id="item_id_'.$value['value'].'" > '. $value['text'].' '.'('.$value['count'].')'.' </li>';
                      }else if($values=='tags'){
						  if(in_array($value['tagId'], $lbt_visib_tags_list)){
							$checked = $_POST['chkdTags'] == $value['tagId'] && $_POST['chkdTags'] != null ? 'checked disabled' : '';
							$html[$values].= '<li data-title="'.$value['text'].'" data-id="'.$value['tagId'].'"><input '.$checked.' type="checkbox" name="enggafifilterdata[]" value="'.$value['tagId'].'" id="item_id_'.$value['tagId'].'"> '.$value['text'].'</li>';
						  }
                      }else if($values=='lastActionOn'){
						$checked = $_POST['chkdAction'] == $value['value'] && $_POST['chkdAction'] != null ? 'checked disabled' : '';
                        $html[$values].= '<li data-title="'.$value['text'].'" data-id="'.$value['value'].'"><input '.$checked.' type="checkbox" name="enggafifilterdata[]" value="'.$value['value'].'" id="item_id_'.$value['value'].'"> '.$value['text'].'</li>';
                      }else if($values=='assignedto'){
						  if(in_array($value['personId'], $lbt_visib_members_list)){
							$checked = $_POST['chkdAssign'] == $value['personId'] && $_POST['chkdAssign'] != null ? 'checked disabled' : '';
							$html[$values].= '<li data-title="'.$value['fullName'].'" data-id="'.$value['personId'].'"><input '.$checked.' type="checkbox" name="enggafifilterdata[]" data-type="members" value="'.$value['personId'].'" id="item_id_'.$value['personId'].'"> '.$value['fullName'].'</li>';
						  }
                      }else if($values=='assignedtoGroups'){
						  if(in_array($value['value'], $lbt_visib_groups_list)){
							$checked = $_POST['chkdAssignGroups'] == $value['value'] && $_POST['chkdAssignGroups'] != null ? 'checked disabled' : '';
							$html['assignedto'].= '<li data-title="'.$value['text'].'" data-id="'.$value['value'].'"><input '.$checked.' type="checkbox" name="enggafifilterdata[]" data-type="groups" value="'.$value['value'].'" id="item_id_'.$value['value'].'"> '.$value['text'].'</li>';
						  }
                      }else if($values=='assignedtoTags'){
						  if(in_array($value['value'], $lbt_visib_members_tags_list)){
							$checked = $_POST['chkdAssignTags'] == $value['value'] && $_POST['chkdAssignTags'] != null ? 'checked disabled' : '';
							$html['assignedto'].= '<li data-title="'.$value['text'].'" data-id="'.$value['value'].'"><input '.$checked.' type="checkbox" name="enggafifilterdata[]" data-type="tags" value="'.$value['value'].'" id="item_id_'.$value['value'].'"> '.$value['text'].'</li>';
						  }
                      }
                    }
                 }else{
                    $html[$values] ='<h6 class="text-center mt-3">Data not found</h6>';
                 }
			} else {
				$html[$values]='<h6 class="text-center mt-3">Data not found</h6>';	
			}
			  $htmlArray=$html;
		}
		  echo json_encode($htmlArray);
		  wp_die();
        
    }
public function LegislationStaffanalysis(){
	$options = get_option('ebt_api_settings');
	$lbt_api_url = $options['lbt_api_url'];
	  $postData = array();
	  $billid =  $_POST['billId'];
	  $apiUrl = 'legislative/public-bills/'.$billid.'/analysis/';
	  $analysisResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  $analysisResponses= json_decode($analysisResponse['api_response']);
	   if(!empty($analysisResponses)){
		  $analysis = $analysisResponses[0];
		foreach($analysisResponses as $analysis){
		  $new_Date = date('m/d/Y',strtotime($analysis->createdDate));
		$ip =$_SERVER['REMOTE_ADDR'];  
		$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
		$ipInfo = json_decode($ipInfo);
		$timezone = $ipInfo->timezone;
		date_default_timezone_set($timezone);
		$date = strtotime($analysis->createdDate.' UTC');
		?>
		<div class="row mb-3 pb-3 border-bottom">
			<div class="col-12 d-flex align-items-center pb-3">
                	<?php $img = str_replace(' ', '%20', $analysis->createdByImage);
					 if($img && filter_var($img, FILTER_VALIDATE_URL)) { ?>
                    <span class="overflow-hidden rounded-circle mr-3 " style="flex :0 0 60px; height:60px; max-width:60px">
						<img class="img-fluid" src="<?php echo $analysis->createdByImage;?>" alt="instructor">
                     </span>
                    <?php } else { ?>
                    	<span class="mr-3 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:35px; background:#d0d0d0"><i class="fa fa-user"></i></span>
                    <?php } ?>
                    <div>
				<h5><?php echo $analysis->createdBy;?></h5>
				<span class="text-muted"><?php echo date('m/d/Y', $date); ?> at <?php echo date('h:i A', $date); ?></span>
                </div>
				<span class="btn btn-sm text-white ml-auto" style="background-color:<?php echo $analysis->billPositionColor; ?>"><?php echo $analysis->billPosition; ?></span>
			</div>

		<div class="col-12 pb-3">
				<div class="bill-detail-summary-content no-border">
				   <p><?php echo $analysis->text;?></p>
				</div>
		</div>
					<?php if(count($analysis->links) || count($analysis->files)){
						if(count($analysis->files)){ ?>
                          <div class="col-12 py-2 border-top ">
                            <h6 class="mb-0">Attachments (<?php echo count($analysis->files) + count($analysis->links); ?>)</h6>
                          </div>
					  <?php foreach ($analysis->files as  $file) {
						$file_url = $lbt_api_url.'/resource/view/'.$file->id.'/'.$file->displayName;
						echo '<div class="col-4 pb-2"><a href="'.$file_url.'" target="_blank"><i style="font-size: 19px;" class="far fa-file-pdf mr-2"></i> '. $file->displayName.'</a></div>';
					  }
					  
					}
					if(count($analysis->links)){
						foreach ($analysis->links as  $attachment) {
						  echo '<div class="col-4 pb-2"><a href="'.$attachment->url.'" target="_blank"><i style="font-size: 19px;" class="fa fa-link mr-2"></i>'.$attachment->title.'</a></div>';
						}
					  }
					}
				   ?>
	  </div>
	  <?php } } else {?>  
		  <div class="p-2"> Data not available</div>
	  <?php }
        wp_die();
}
public function LegislationVersions(){
	$options = get_option('ebt_api_settings');
	$lbt_api_url = $options['lbt_api_url'];
	  $postData = array();
	  $billid =  $_POST['billId'];
	  $fileid =  $_POST['fileid'];
	  $apiUrl = 'legislative/public-bills/'.$billid.'/version/';
	  $versionResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  $versionResponses= json_decode($versionResponse['api_response']);
	  if(!empty($versionResponses)){
	  foreach ($versionResponses as $version => $allVersions) { 
		if($allVersions->billDraftDateTime != ""){
		$defaulget_Date = $allVersions->billDraftDateTime;
		$convert_Date = strtotime($defaulget_Date);
		$new_Date = date('M d, Y', $convert_Date);
		}else{
		  $new_Date ="Date Not Available";
		}
  ?>
  <tr>
	  <td> <?php echo $allVersions->type;?> </td>
	  <td> <?php echo $new_Date;?> </td>
	  <td class="text-center">
		<a  class="text-underline" target="_blank" href="<?php echo $allVersions->url;?>"><?php echo $allVersions->url;?></a>
	  </td>
	  <td class="text-center">
		<a class="text-underline" target="_blank" href="<?php echo $lbt_api_url;?>/file/<?php echo $fileid;?>">Download Text</a>
	  </td>
	</tr>
	<?php }} 
        wp_die();
}
public function LegislationVotes(){
	$options = get_option('ebt_api_settings');
	$lbt_api_url = $options['lbt_api_url'];
	  $postData = array();
	  $billid =  $_POST['billId'];
	  $apiUrl = 'legislative/public-bills/'.$billid.'/rollcall/';
	  $voteResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  $voteResponses= json_decode($voteResponse['api_response']);
	  if(!empty($voteResponses)){
	  foreach($voteResponses as $vote){ 
		$defaulget_Date = $vote->dateOfRollCall;
		$convert_Date = strtotime($defaulget_Date);
		$new_Date = date('M d, Y', $convert_Date);
		?>
		<tr>
			<td> <?php echo $vote->chamberType;?></td>
			<td> <a href="javascript:void(0);" 
                       class="view-details" 
                       data-id="<?php echo $vote->id; ?>">
                        <?php echo $vote->description; ?>
                    </a></td>
			<td> <?php echo $new_Date;?></td>
			<td> <?php echo $vote->votesCountInFavor;?></td>
			<td> <?php echo $vote->votesCountAgainst;?></td>
			<td> <?php echo $vote->countOfNoVotes;?></td>
			<td> <?php echo $vote->countOfAbsentess;?></td>
			<td> <?php echo $vote->totalVoteCount;?></td>
			<td> <?php echo $vote->rollCallPassed;?></td>
			<td> <a href="<?php echo "$vote->sourceUrl";?>" target="_blank"><?php echo "Source";?></a></td>
		</tr>

	  <?php }} 
        wp_die();
}

function get_rollcall_details() {
    $options = get_option('ebt_api_settings');
    $lbt_api_url = $options['lbt_api_url'];
    $postData = array();
    $rollCallId = $_POST['rollCallId']; 
    $description = $_POST['description']; 
    $apiUrl = 'legislative/public-bills/rollcall/' . $rollCallId . '/detail';
    $responseJson =  $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');    
    $response = json_decode($responseJson['api_response']);
    $voteDetails = $response->voteDetails;
    $rollcallDetails = $response->legislativeRollcallDetail;

if (!empty($rollcallDetails)) {    
    echo '<div style="max-height: 450px; overflow-y: auto; padding: 10px;">';
    // Summary Table
    echo '<table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border: 1px solid #ddd;">';
    echo '<thead style="background-color: #007BFF; text-align: center; color: white; border: 1px solid #00897b;">';
    echo '<tr>';
    echo '<th>Yea</th>'; echo '<th>Nay</th>'; echo '<th>NV</th>'; echo '<th>Abs</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr style="text-align:center;">';
    echo '<td>' . htmlspecialchars($voteDetails->yea, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($voteDetails->nay, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($voteDetails->nv, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($voteDetails->abs, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';

    // Detailed Table
    echo '<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
    echo '<thead style="background-color: #007BFF; color: white; border: 1px solid #00897b;">';
    echo '<tr>';
    echo '<th style="padding-left: 20px; text-align: left;">Name</th>';
    echo '<th style="padding-left: 20px; text-align: left;">Response</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($rollcallDetails as $detail) {
        $name = !empty($detail->name) ? $detail->name : $detail->firstName . ' ' . $detail->lastName;
        $voteType = $detail->voteType;
        echo '<tr style="line-height: .5;">';
        echo '<td style="padding-left: 20px; border: 1px solid #ddd;">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td style="padding-left: 20px; border: 1px solid #ddd;">' . htmlspecialchars($voteType, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    echo '</div>'; 
} else {
    echo '<p>No data available for the selected roll call.</p>';
}
    wp_die();
}

public function LegislationHistory(){
	$options = get_option('ebt_api_settings');
	$lbt_api_url = $options['lbt_api_url'];
	  $postData = array();
	  $billid =  $_POST['billId'];
	  $apiUrl = 'legislative/public-bills/'.$billid.'/actionhistory/';
	  $historyResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  $historyResponses= json_decode($historyResponse['api_response']);
	   if(!empty($historyResponses)){
	   foreach ($historyResponses as $history => $histories) {
		 $defaulget_Date = $histories->actionDate;
		 $convert_Date = strtotime($defaulget_Date);
		 $new_date = date('M d, Y',$convert_Date); //$new_Date = date('M d, Y', $convert_Date);
	  ?>
		<tr>
			<td> <?php echo $new_date;?></td>
			<td> <?php echo $histories->billChamberType;?> </td>
			<td> <?php echo $histories->actionText;?> </td>
		</tr>
	  <?php }}  
        wp_die();
}
public function LegislationQuick(){
	$options = get_option('ebt_api_settings');
	$lbt_api_url = $options['lbt_api_url'];
	  $postData = array();
	  $billid =  $_POST['billId'];
	  $apiUrl = 'legislative/public-bills/'.$billid.'/source/';
	  $quicklinkResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  $quicklinkResponses= json_decode($quicklinkResponse['api_response']);
	  if(!empty($quicklinkResponses)){
	  foreach($quicklinkResponses as $links){?>
		<tr>
		  <td> <?php echo $links->type;?> </td>
		  <td>
			<a target="_blank" href="<?php echo $links->url;?>"><?php echo $links->url;?>  </a>
		  </td>
		</tr>
	<?php }}   
        wp_die();
}
public function LegislationMaco(){
	$options = get_option('ebt_api_settings');
	$lbt_api_url = $options['lbt_api_url'];
	  $postData = array();
	  $billid =  $_POST['billId'];
	  $apiUrl = 'legislative/public-bills/'.$billid.'/maco/publicanalysis';
	  $publicanalysisResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  $publicanalysisResponses= json_decode($publicanalysisResponse['api_response']);
 		if(!empty($publicanalysisResponses)){
			  $trackingColor = $publicanalysisResponses->publicTrackingLevelColor;
			  $trackingLevel = $publicanalysisResponses->publicTrackingLevelText;
			  if($trackingColor!='' && $trackingLevel!=''){
			  ?>
			<div class="col-sm-12 mb-4">
				<span  style="font-weight: bold;">MACo Tracking Level: </span>
				<span class="p-1 m-1" style="background-color:<?php echo $trackingColor; ?>"></span>
				<span><?php echo $trackingLevel; ?></span>
			  </div>
			  
		   <?php } }   if(!empty($publicanalysisResponses->clientBillAnalysis)){
			  $analysis = $publicanalysisResponses->clientBillAnalysis[0];
			  //foreach($analysisResponses as $analysis){

				$new_Date = date('m/d/Y',strtotime($analysis->createdDate));

				 if($analysis->createdByImage)
			  {
				  if (filter_var($analysis->createdByImage, FILTER_VALIDATE_URL)) { 
					  $instructor_img = $analysis->createdByImage;
				  }
				  else
				  {
					  $instructor_img = $tenant_url.$analysis->createdByImage;
				  }
				  
			  }
			  else
			  {
				  $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

			  }
   
			  $ip =$_SERVER['REMOTE_ADDR'];  
			  $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
			  $ipInfo = json_decode($ipInfo);
			  $timezone = $ipInfo->timezone;
			  date_default_timezone_set($timezone);
			  $date = strtotime($analysis->createdDate.' UTC');
			  //echo $date->format('Y-m-d h:i:s A'); 

			  ?>
		  <div class="col-sm-12">
			  <div class="row">
				  <div class="col-sm-10">
					  <a href="javascript:void(0)">
						  <img class="img-circle img-xs mx-1 inline-block" src="<?php echo $instructor_img;?>" alt="instructor">
						  <span class="text mx-1"><?php echo $analysis->createdBy;?></span>
					  </a>
					  <div class="text-muted mx-5 pt-2 pb-2"><?php echo date('m/d/Y', $date); ?> at <?php echo date('h:i A', $date); ?></div>
					  
				  </div>
				  <div class="col-sm-2">
					  <div class="p-2 m-2 text-white text-center" style="background-color:<?php echo $analysis->billPositionColor; ?>"><?php echo $analysis->billPosition; ?></div>
				  </div>
	  
				  <div class="col-sm-12">
					  <div class="lead" >
						  <div class="bill-detail-summary-content no-border mx-5" style="height: 100%;">
							 <p class="no-margin"><?php echo $analysis->text;?></p>
							 
						  </div>
					  </div>
				  </div>

						  <?php

						  if(count($analysis->links) || count($analysis->files)){

						  if(count($analysis->files))
						  {
							?>

							<div class="col-sm-12 panel-title p-2 border-bottom mt-2">
							  <p class="d-inline mb-0">Attachments (<?php echo count($analysis->files) + count($analysis->links); ?>)</p>
							</div>
							
							<?php
							foreach ($analysis->files as  $file) {
							  $file_url = $lbt_api_url.'/resource/view/'.$file->id.'/'.$file->displayName;
							  echo '<div class="col-4 pt-2"><i class="fa fa-file-pdf-o"></i> <a href="'.$file_url.'" target="_blank"> '. $file->displayName.'</a></div>';
							}
							
						  }
						  if(count($analysis->links)){
							  foreach ($analysis->links as  $attachment) {
								echo '<div class="col-4 pt-2"><i class="fa fa-link"></i><a href="'.$attachment->url.'" target="_blank">'.$attachment->title.'</a></div>';
							  }
							}
						  }
					} else {?>  
				<div class="col-12"> MACo has not provided an analysis yet.</div> 
			<?php }?>   
			</div>
		  </div>
  <?php
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
					  <h2 class="mb-0 mt-0">
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
		if(!$postData['billNumber']&&$_POST['chkdBillNo']){
		  $postData['billNumber'] = $_POST['chkdBillNo'];
		}
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

    private function _popOverTrainingData1($id, $trainingData) {
        $rowName = array();
        $popOverHtml = ''; // Initialize the variable
        $popOverHtml .= dd_header('Dates');
        $subItems = "";
        $li = 1;
    
        if (is_array($trainingData) && isset($trainingData[0])) {
            // When $trainingData is an array of objects
            foreach ($trainingData as $key => $rowData) {
                $rowName[$rowData->id] = $rowData->id;
                $classTime = '';
                if (!empty($rowData->startDateTime)) {
                    $classTime = date('M d Y', strtotime($rowData->startDateTime)) . ' At ' . date('g:i A', strtotime($rowData->startDateTime));
    
                    if (!empty($rowData->endDateTime)) {
                        $classTime .= ' - ' . date('g:i A', strtotime($rowData->endDateTime));
                    }
                }
    
                $class = ($li % 2 == 1) ? 'bg-light' : '';
                $subItems .= '<li class="d-flex px-2 py-1 border-bottom align-items-center small ' . $class . '" style="width: 100%; margin: 0;">
                                <img style="max-width:25px" src="' . ENGAGIFII_ASSETS_URL . '/images/class.png" class="img-fluid mr-2"/>
                                <span>' . $classTime . '</span>
                              </li>';
                $li++;
            }
        } else {
            // When $trainingData contains startDateTime and endDateTime
            $startDateTime = $trainingData['startDateTime'] ?? null;
            $endDateTime = $trainingData['endDateTime'] ?? null;
    
            $class = 'bg-light'; // Default class
            $classTime = '';
    
            if ($startDateTime) {
                $classTime = date('M d Y', strtotime($startDateTime)) . ' At ' . date('g:i A', strtotime($startDateTime));
    
                if ($endDateTime) {
                    $classTime .= ' - ' . date('g:i A', strtotime($endDateTime));
                }
            }
    
            $subItems .= '<li class="d-flex px-2 py-1 border-bottom align-items-center small ' . $class . '" style="width: 100%; margin: 0;">
                            <img style="max-width:25px" src="' . ENGAGIFII_ASSETS_URL . '/images/class.png" class="img-fluid mr-2"/>
                            <span>' . $classTime . '</span>
                          </li>';
        }
    
        $popOverHtml .= '<ul class="list-unstyled m-0 p-0">' . $subItems . '</ul>'; // Add a reset class to remove padding and margin
        if (empty($subItems)) {
            $popOverHtml .= '<span class="px-2 py-1 text-center small d-block">No results found!</span>';
        }
    
        $popOverHtml .= '</div>'; // Close all remaining tags
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
    
        return $popOverHtml;
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

public function _popOverTrainingCalLocationData($id, $locationData){
    $rowName = array();
 
$popOverHtml = '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Locations</h6><div class="px-2 border-bottom pb-2"></div>';
 $subItems = "";
$li=1;
  foreach ($locationData as $key => $rowData) {
    $days = $key+1;
    $sessionStart_Date = strtotime($rowData->startDateTime);
    $startDate = date('M d, Y', $sessionStart_Date);
    $startTime = date('g:i A', $sessionStart_Date);
    $sessionEnd_Date = strtotime($rowData->endDateTime);
    $endDate = date('M d, Y', $sessionEnd_Date);
    $endTime = date('g:i A', $sessionEnd_Date);
      
      $rowName[$rowData->id] = $rowData->cityName;
        $class='';
        if($li%2==1){
        $class='bg-light';	
        }
      if($rowData->cityName){
      //$subItems .= ' <li  class="px-2 py-1 border-bottom  small '.$class.'"><a class="d-flex align-items-center pr-2"  data-toggle="collapse" href="#loc-'.$rowData->id.'" role="button" aria-expanded="false" aria-controls="collapseExample"><b>Day '.$days.'</b><i class="fa fa-chevron-down ml-auto"></i></a>'; 
      $subItems .= ' <li  class="px-2 py-1 border-bottom  small '.$class.'"><div data-toggle="collapse" href="#loc-'.$rowData->id.'" role="button" aria-expanded="false" aria-controls="collapseExample"><b>Address:</b><br>' .$rowData->addressLine.', '.$rowData->city.', '.$rowData->state.', '.$rowData->zip.', '.$rowData->country.'</div>';
       if($rowData->latitude){
          //$subItems .= '<div class="collapse" id="loc-'.$rowData->id.'">'.$rowData->addressLine.', '.$rowData->cityName.', '.$rowData->stateName.', '.$rowData->zip.', '.$rowData->country.'<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q='.$rowData->latitude.','.$rowData->longitude.'&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div></div>';
            $subItems .=  '<div class="collapse" id="loc-'.$rowData->id.'"><div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q='.$rowData->latitude.','.$rowData->longitude.'&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div></div>';
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

	//Events Date popover 
	public function _popOverLocationData($id, $locationData){
		$rowName = array();
     
 $popOverHtml = '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Locations</h6><div class="px-2 border-bottom pb-2"></div>';
	 $subItems = "";
$li=1;
	  foreach ($locationData as $key => $rowData) {
        $days = $key+1;
        $sessionStart_Date = strtotime($rowData->sessionStartTime);
		$startDate = date('M d, Y', $sessionStart_Date);
		$startTime = date('g:i A', $sessionStart_Date);
		$sessionEnd_Date = strtotime($rowData->sessionEndTime);
		$endDate = date('M d, Y', $sessionEnd_Date);
		$endTime = date('g:i A', $sessionEnd_Date);
		  
		  $rowName[$rowData->id] = $rowData->city;
		    $class='';
            if($li%2==1){
			$class='bg-light';	
			}
		  if($rowData->city){
		  $subItems .= ' <li  class="px-2 py-1 border-bottom  small '.$class.'"><a class="d-flex align-items-center pr-2"  data-toggle="collapse" href="#loc-'.$rowData->id.'" role="button" aria-expanded="false" aria-controls="collapseExample"><b>Day '.$days.'</b><i class="fa fa-chevron-down ml-auto"></i></a>'; 
          //$subItems .= $rowData->addressLine.', '.$rowData->city.', '.$rowData->state.', '.$rowData->zip.', '.$rowData->country; 
		  if($rowData->latitude){
		 	 $subItems .= '<div class="collapse" id="loc-'.$rowData->id.'">'.$rowData->addressLine.', '.$rowData->city.', '.$rowData->state.', '.$rowData->zip.', '.$rowData->country.'<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q='.$rowData->latitude.','.$rowData->longitude.'&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div></div>';
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



    public function _prepareEventsData(){
        $eventTypesShow = get_option( 'ebt_api_settings' )['events_type_visible_column_list'];
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
        $postData['types'] = $eventTypesShow;
        if (!empty($_POST['types'])) {
            $filteredTypes = [];
            foreach ($eventTypesShow as $type) {
                if (in_array($type, $_POST['types'])) {
                    $filteredTypes[] = $type;
                }
            }
            $postData['types'] = $filteredTypes;
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

    public function _prepareTrainingCalendarData(){ //_prepareTrainingCalendarsData
        $allEventsClass = get_option( 'ebt_api_settings' )['allEventsClass'];
        
        if($allEventsClass==1){
        $allEventsClass = 'false';	
        }else{
            $allEventsClass = 'true';
        }
        //print_r($allEventsClass);die;
        $columnsData = [];
        foreach ($_POST['columns'] as $key => $value) {
            if ($value['orderable'] == "true") {
                $columnsData[$value['data']] = $value['data'];
            }
        }
        $startPageNum = 1;
        if($_POST['start']){
            $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
        }
        

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
        $postData['itemCount'] = isset($_POST['length']) ? $_POST['length'] : 0;
        //$postData['isUpcoming'] = $allEventsClass;
        $postData['sortBy'] = ucfirst($sortBy);
        $postData['sortDirection'] = $isAsscend === 'desc' ? 'desc' : 'asc';

        $postData['pageNumber'] = ($startPageNum);
        $postData['pageSize'] = ((int) $_POST['length']);
		$postData['text'] = $title;
        //$postData['sortDirection'] = $_POST["order"][0]["dir"];
        
        $postData['filterBody'] = array('searchText'=>$title,  'selectedDate' => date('Y-m-d'));
        $postData['filterBody']['isUpcoming'] = $allEventsClass; //$allEvents;
        $postData['filterBody']['category'] = $_POST['category']; //$allEvents;
       
        if(!empty($_POST['tags']))
        {
            $postData['tags'] = $_POST['tags'];
        }
        if(!empty($_POST['types']))
        {
            $postData['filterBody']['type'] = $_POST['types'];
        }
		 if(!empty($_POST['locations']))
        {
            $postData['filterBody']['venueId'] = $_POST['locations'];
        }
         if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['filterBody']['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['filterBody']['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }
        if(!empty($_POST['classDate']))
        {
            $dateRange = explode("-", $_POST['classDate']);
            $postData['filterBody']['registrationDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['filterBody']['registrationDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }
    //print_r(json_encode($postData)); die;
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
        $postData['classTypes'] = $_POST['classTypes'];
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