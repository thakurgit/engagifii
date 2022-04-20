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
        add_action('wp_ajax_nopriv_endorsement', array($this, 'endorsementLoadGridData'));
        add_action('wp_ajax_endorsement', array($this, 'endorsementLoadGridData'));

        add_action('wp_ajax_nopriv_legislation', array($this, 'legislationLoadGridData'));
        add_action('wp_ajax_legislation', array($this, 'legislationLoadGridData'));

        add_action('wp_ajax_nopriv_courses', array($this, 'courseLoadGridData'));
        add_action('wp_ajax_courses', array($this, 'courseLoadGridData'));

        add_action('wp_ajax_nopriv_classes', array($this, 'classLoadGridData'));
        add_action('wp_ajax_classes', array($this, 'classLoadGridData'));

        add_action('wp_ajax_nopriv_filtercountdata', array($this, 'countFilterData'));
        add_action('wp_ajax_filtercountdata', array($this, 'countFilterData'));

        add_action('wp_ajax_nopriv_coursecountdata', array($this, 'courseCountFilterData'));
        add_action('wp_ajax_coursecountdata', array($this, 'courseCountFilterData'));

        add_action('wp_ajax_nopriv_classcountdata', array($this, 'classCountFilterData'));
        add_action('wp_ajax_classcountdata', array($this, 'classCountFilterData'));

        add_action('wp_ajax_nopriv_legislationfiltercountdata', array($this, 'countLegislationFilterData'));
        add_action('wp_ajax_legislationfiltercountdata', array($this, 'countLegislationFilterData'));

        add_action('wp_ajax_nopriv_getbillids', array($this, 'legislationbillids'));
        add_action('wp_ajax_getbillids', array($this, 'legislationbillids'));

        add_action('wp_ajax_nopriv_calendar', array($this, 'classCalendar'));
        add_action('wp_ajax_calendar', array($this, 'classCalendar'));

        add_action('wp_ajax_nopriv_getcalendar', array($this, 'getCalendar'));
        add_action('wp_ajax_getcalendar', array($this, 'getCalendar'));
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
        $postedData  = $this->_prepareClassData();
        $dataResponse = $this->submitApiRequest("Public/ClassPagingList", $postedData, "POST", 'classes');
        
        $collection   = json_decode($dataResponse['api_response'])->result;
        $totalcount   = json_decode($dataResponse['api_response'])->totalCount;
        $totalRecords  = json_decode($dataResponse['api_response'])->itemCount;
        $data         = array();

        $options = get_option('ebt_api_settings');
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
        
         foreach ($collection as $key => $value) {
            
            #nested data
            $nestedData = array();
            $instructorPopOver = '';
            $classPopover      = '';

            if(count($value->classInstructors))
                $instructorPopOver = $this->_popOverInstructorData($key, $value->classInstructors);

            if(count($value->classSessions))
                $classPopover  = $this->_popOverClassData($key, $value->classSessions);
            ## row data
            $class_schedule = '';
            if($value->classDuration > 1)
            {
                $class_schedule = '<br/><span style="white-space:normal;">'.date('d M Y', strtotime($value->startDate)).' - '.date('d M Y', strtotime($value->endDate)).'</span>';
            }
            $nestedData['sectionname'] = '<div class="d-flex"><div class="col-2 col-sm-2 m-auto p-0"><img alt="'.$value->sectionName.'" src="'.$value->parentCourse->iconReference.'" class="img-responsive img-icon-lg p-0"></div><div class="Col-10 col-sm-10 m-auto p-3"><span><a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'<br/><span style="white-space:normal;">'.date('d M Y', strtotime($value->startDate)).' at '.date('h:i A', strtotime($value->startDate)).' - '.date('h:i A', strtotime($value->endDate)).'</span></div></div>';
            $nestedData['classDuration'] = $value->classDuration.' '.$value->classDurationType;
            $nestedData['objectType'] = $value->objectType;

             $nestedData['startdate'] = '<div class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '""><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg" alt="class-icon"><span class="bg-grey badge-count">'.count($value->classSessions).'</span></div>'.$classPopover;

            $nestedData['classInstructorsCount'] = '<div class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg" alt="instructor-icon"><span class="bg-grey badge-count">'.($value->classInstructorsCount).'</span></div>'.$instructorPopOver;  
            $nestedData['credithours'] = $value->parentCourse->creditHours;          
           
            $classTag = $value->classTag;
            $allTags = array();
            foreach ($classTag as $index => $tag) {
                
                    if(count($classTag) > 1 && $index == 0)
                    {   
                        $tagPopover =  $this->_popOverTagData($key, $value->classTag);

                           $tagCount   = count($classTag) - 1;
                    $allTags[] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;">'.$tag->tagName.'</div><span class="badge badge-sm bg-primary-400 ml-2 tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span></div>'.$tagPopover;
                    }
                    elseif(count($classTag) == 1)
                        $allTags[] = $tag->tagName;
            }

            $nestedData['classTag'] = implode(" ", $allTags);
              if($value->isClassRegistrationAllow || $value->registrationWorkFlowId){
                        if($value->locationType->name=="onlocation"){
                            $nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                        }elseif($value->locationType->name=="online"){
                            $nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                        }
            //$nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
            }else{
            $nestedData['register'] = ' ';
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
    public function courseLoadGridData(){
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
            $nestedData['name'] = '<a href="'.site_url().'/course-details/?courseId='.$value->id.'"><img src="'.$value->courseIcon.'" class="img-responsive img-icon-lg" alt="course-icon">'.$value->courseName.'</a>';
            $nestedData['objectType'] = $value->objectType;
            $nestedData['creditHours'] = $value->creditHours;
            $nestedData['instructor'] = '<div class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '""><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" alt="instructor-icon" class="img-icon-lg"><span class="bg-grey badge-count">'.($value->courseInstructorsCount ).'</span></div>'.$instructorPopOver;            
            $nestedData['class'] = '<div class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" alt="class-icon" class="img-icon-lg"><span class="bg-grey badge-count">'.$value->courseClassesCount.'</span></div>'.$classPopover;
            $courseTag = $value->courseTags;
            $allTags = array();
            foreach ($courseTag as $index => $tag) {

                if(count($courseTag) > 1 && $index == 0)
                {   
                    $tagPopover =  $this->_popOverTagData($key, $courseTag);

                     $tagCount   = count($courseTag) - 1;
                    $allTags[] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;">'.$tag->tagName.'</div><span class="badge badge-sm bg-primary-400 ml-2 tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span></div>'.$tagPopover;
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


    public function endorsementLoadGridData(){
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
            $default_Detailpage .= '<img src="'.$row->icon.'" class="img-responsive img-icon-lg" alt="award-icon"><a href=' . site_url() . '/endorsement-detail?endId=' . $default_Id . ' >' . $default_Title . '</a>';
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
            $nestedData['createdOn'] = '<div class="d-flex" style="justify-content:center;"><div class="text-center"><img src="'.$instructor_img.'" class="img-icon-lg" alt="instructor-img"></div><div class="text-center"><a href="#" class="m-auto text-break"> '.$row->createdBy->name.'</a><p class="lead">'.$new_Date.'</p></div></div>';
            
            $nestedData['validity'] = $row->validity;
            
            $default_Courses = $row->courses;
            if ($default_Courses) {
                $nestedData['courseCount'] = $default_Courses;
            }else{
                $nestedData['courseCount'] = '<div class="course-badge"><img src="'.ENGAGIFII_ASSETS_URL.'/images/course-icon.png" class="img-circle" alt="course-icon"></div>';
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
                    $allTags[] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;">'.$value->tagName.'</div><span class="badge badge-sm bg-primary-400 ml-2 tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span></div>'.$tagPopover;
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

    public function legislationLoadGridData()
    {

        $postedData = $this->_prepareLegislationPostData();
        $dataResponse = $this->submitApiRequest("legislative/public-bills/list",$postedData,"POST",'legislation');

        $collection = json_decode($dataResponse['api_response']);
        
        header("Content-Type: application/json");
        $request = $_GET;

        $options = get_option('ebt_api_settings');
        $lbt_api_url = $options['lbt_api_url'];

        $lbt_visib_datacol_list = $options['lbt_visib_datacol_list'];

        $data = array();
        $sponsorsName = array();
        $bill_array   = array();
        foreach ($collection->collection as $key => $row) {

            $nestedData = array();

            $lastActionOndefault_Date = $row->lastActionOn;
            $lastActionOnconvert_Date = strtotime($lastActionOndefault_Date);
            $lastActionOnnew_Date = date('M d, Y', $lastActionOnconvert_Date);

            $default_Date = $row->introducedDate;
            $convert_Date = strtotime($default_Date);
            $new_Date = date('M d, Y', $convert_Date);

            $bill_array[$key] = $row->id;
            
            $billHtml = "";

            if(isset($options['lbt_title_display_setting']))
            {
                if($options['lbt_title_display_setting'] == 'alternate'){
                    if($row->alternateTitle){
                        $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id .' ><u>' . $row->alternateTitle . '</u></a>';
                    }
                    else
                    {
                        $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id . ' ><u>' . $row->title . '</u></a>';
                    }
                    

                }elseif($options['lbt_title_display_setting'] == 'alternate-top'){
                    if($row->alternateTitle){
                        $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id .' ><u>' . $row->alternateTitle . '</u><br/>'.$row->title.'</a>';
                    }
                    else
                    {
                        $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id .' ><u>' . $row->title . '</u></a>';
                    }

                }elseif($options['lbt_title_display_setting'] == 'title-top'){
                    $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id .' ><u>' . $row->title . '</u><br/>'.$row->alternateTitle.'</a>';

                }
                elseif($options['lbt_title_display_setting'] == 'title'){
                    $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id . ' ><u>' . $row->title . '</u></a>';
                }
            }
            else
            {
                $billHtml = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id . ' ><u>' . $row->title . '</u></a>';
            }
            
            $pdf = '<a href=' . $lbt_api_url . '/file/' . $row->fileId . '> <img class="full-text-img" alt="pdf-icon" src="' . ENGAGIFII_ASSETS_URL . '/images/pdf.png' . '"> </a>';

            if (!empty($row->sponsors)) {
                if (count($row->sponsors) > 1) {
                    
                    if ($row->sponsors[0]->profilePic) {
                        $pichere = $row->sponsors[0]->profilePic;
                    } else {
                        $pichere = ENGAGIFII_ASSETS_URL . '/images/staff-list-grey.png';
                    }

                    $personLists = $this->_popOverHtml($row->id, $row->sponsors);
                    $countSponsors = count($row->sponsors) - 1;
                    $sponsors_string = '<div class="flex-1"> '.$row->sponsors[0]->name . '</div>' . '<span class="badge badge-sm bg-primary-400 ml-2 sponsors-click_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $countSponsors . ' </span>' . $personLists;

                    $nestedData["sponsors"] = '<div class="sponsors-middle"><div class="user-image-square flex-1"><img src = ' . $pichere . ' alt="'.$row->sponsors[0]->name.'" > </div> ' . $sponsors_string . '</div>';
                } else {
                     if ($row->sponsors[0]->profilePic) {
                        $pichere = $row->sponsors[0]->profilePic;
                    } else {
                        $pichere = ENGAGIFII_ASSETS_URL . '/images/staff-list-grey.png';
                    }
                    $nestedData["sponsors"] = '<div class="sponsors-middle"><div class="user-image-square"><img src = ' . $pichere . ' alt="'.$row->sponsors[0]->name.'"> </div> ' . '<div class="flex-1" style="width:100px;"> '.$row->sponsors[0]->name . '</div>' . '</div>';

                }
            } else {
                $nestedData["sponsors"] = "None";
            }

            $nestedData["trackingLevelColorCode"] = $row->trackingLevelColorCode;
            $nestedData["BillType"] = $row->billTypeAbbr;
            $nestedData["billNumber"] = '<a class="bill-title" href=' . site_url() . '/engagifii-detail/?billId=' . $row->id . ' >'.$row->billNumber.'</a>';
            $nestedData["state"] = $row->state;
            $nestedData["fileId"] = $pdf;
            $nestedData["title"] = $billHtml;
            $nestedData["IntroducedDate"] = $new_Date;
            $nestedData["status"] = $row->status;
            $nestedData["lastActionTaken"] = $row->lastActionTaken;
            

            $billHtml1 = '<span style="background-color:'.$row->trackingLevelColorCode.'; width: 13px;height: 13px;border-radius: 50%;display: inline-block;margin-left: 8px; vertical-align: middle;"></span>';

            $nestedData["trackingLevel"] = $billHtml1 . '&nbsp;'  .$row->trackingLevel;

            $nestedData["lastActionOn"] = '<p class="text-left" style="white-space:normal;">'.$lastActionOnnew_Date.'<br/>'.$row->lastActionTaken.'</p>';

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
                        $houseCom = '<div class="flex-1" > '.$house_name . '</div>' . '<span class="badge badge-sm bg-primary-400 ml-2 house_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $houseCommitteesCount . ' </span>' . $houseLists;


                        $nestedData["houseCommittees"] = $houseCom;
                    }
                    else
                    {
                        $nestedData["houseCommittees"] = '<p style="white-space:normal;">'.$row->houseCommittees[0].'</p>';
                    }

            } else {
                $nestedData["houseCommittees"] = "None";
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

                        $senateCom = '<div class="d-flex justify-content-center"><div class="flex-1"> '.$senate_name . '</div>' . '<span class="badge badge-sm bg-primary-400 ml-2 senate_' . $row->id . '" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' . $senateCommitteesCount . ' </span></div>' . $senateLists;


                        $nestedData["senateCommittees"] = $senateCom;
                }
                else
                    $nestedData["senateCommittees"] = '<p style="white-space:normal;">'.$row->senateCommittees[0].'</p>';
            } else {
                $nestedData["senateCommittees"] = "None";
            }
            if(count($row->assignedTo)){
                if(count($row->assignedTo) > 1){
                    $assignCount              = count($row->assignedTo) - 1;
                    $assignList               = $this->_popOverAssignHtml($row->id, $row->assignedTo);
                    $nestedData['assignedto'] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;"> '.$row->assignedTo[0].'</div><span class="badge badge-sm bg-primary-400 ml-2 assign_'.$row->id.'" data-placement="left" data-containerid="' . $row->id . '" id=' . $row->id . '> +' .$assignCount .'</span></div>'.$assignList;
                }
                else{
                    $nestedData['assignedto'] = $row->assignedTo[0];
                }
            }
            else
                $nestedData['assignedto'] = '';

            if(count($row->tags)){
              
                if(count($row->tags) > 1){
                    $tagCount              = count($row->tags) - 1;
                  
                    $tagList               = $this->_popoverTagsHtml($row->id, $row->tags);
                    $nestedData['tags'] = '<div class="d-flex justify-content-center"><div class="flex-1" style="white-space:normal;"> <a href="'.site_url().'/bill-tracking/?tag='.$row->tags[0]->value.'&'.base64_encode($row->tags[0]->text).'">'.$row->tags[0]->text.'</a></div><span class="badge badge-sm bg-primary-400 ml-2 tag_leg_'.$row->id.'" data-placement="left" data-containerid="' . $row->id . '" id="' . $row->id . '"> +' . $tagCount .'</span></div>'.$tagList;
                }else{
                    
                    $nestedData['tags']       = '<a href="'.site_url().'/bill-tracking/?tag='.$row->tags[0]->value.'&'.base64_encode($row->tags[0]->text).'">'.$row->tags[0]->text;
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
        

        $title = $_POST['columns'][0]['search']['value'];

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
        $postData['selectedDate'] = $getCurrentdate;

        echo json_encode($postData);
        return $postData;
    }

    private function _prepareLegislationPostData()
    {

        $search = '';
        if (strlen($_POST['search']['value']) > 1) {
            $search = $_POST['search']['value'];
        }

        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
       

        //$searchText = $_POST['search']['value'];
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
        //$jsonSponsors = json_encode($sponsors) ;
        $jsonSponsors = ($sponsors);
        //print_r(($jsonSponsors));exit("dfsdf");

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
            //$trackingLevels = @explode(",", $_POST['trackingLevels']);
            $trackingLevels = $_POST['trackingLevels'];
           
        }     
        
        
        if (isset($_POST['sponsors']) && !empty($_POST['sponsors'])) {
           // exit("ddd");
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
         

        $postData = array();
        $postData['introducedDate'] = date('m/d/Y');
        $postData['trackingLevels'] = $trackingLevels;
        $postData['sponsors'] = $sponsors22;
        $postData['houseCommittees'] = $houseCommittees;
        $postData['senateCommittees'] = $senateCommittees;
        
     
        $postData['title'] = $_POST['columns'][1]['search']['value'];
        $postData['billNumber'] = $_POST['columns'][0]['search']['value'];

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
        
        //echo json_encode($postData); die;
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
        //$searchName = json_encode(array_values($rowName));

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

    private function _popOverAssignHtml($id, $assignedto){
        $rowName = array();
        $popOverHtml .= '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="assign_' . $id . '">';
        $subItems = "";
        //s($assignedto);
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
        //array_shift($persondata);
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

    private function _popOverClassData($id, $classData){
        //print_r($classData);
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
    private function _prepareLegislationPostCountData()
    {

        $serachTxt = '';
        if (strlen($_POST['search']['value']) > 1) {
            $serachTxt = $_POST['search']['value'];
        }

        $startPageNum = (int) (($_POST['start'] / $_POST['length']) + 1);
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
        if(!empty($_POST['courses']))
        {
            $postData['filterBody']['courses'] = $_POST['courses'];
        }
        if(!empty($_POST['instructors']))
        {
            $postData['filterBody']['instructors'] = $_POST['instructors'];
        }
         if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['filterBody']['createdDateRange']['startDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['filterBody']['createdDateRange']['endDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }
        return $postData;
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

    public function _classPostCountData(){

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

     public function classCalendar(){
        $options = get_option('ebt_api_settings');
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
        $postedData = $this->_classPostCountData();
        $dataResponse = $this->submitApiRequest("Public/Class/FilteredRecordCount", $postedData, "POST", 'classes');
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
        $postData['filterBody'] = array('searchText'=>'',  'selectedDate' => date('Y-m-d'));
        //echo json_encode($postData);
        $dataResponse = $this->submitApiRequest("Public/ClassPagingList", $postData, "POST", 'classes');

        $collection   = json_decode($dataResponse['api_response'])->result;
        $data         = array();
        $classData    = array();
        //print_r($collection); die;
        foreach ($collection as $key => $value) {
        	if(count($value->classSessions))
        	{
        		foreach ($value->classSessions as $index => $class) {
        			$data['title'] = '<a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a>';
		            $data['id']    = $value->id;
		            $data['start'] = date('Y-m-d', strtotime($class->sessionDate));
		            $data['end']   = date('Y-m-d', strtotime($class->sessionDate));
		            $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
		            $data['objectType'] = $value->objectType;
		            $data['hours']      = $value->parentCourse->creditHours;
		            $data['icon']       = $value->parentCourse->iconReference;
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

		            if($value->isClassRegistrationAllow)
		            {
		                if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
                        {
                           if($value->locationType->name=="onlocation"){
                                $data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                            }elseif($value->locationType->name=="online"){
                                $data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                            }

		                    //$data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';

		                }
		                else{
		                    $data['register'] = ' ';
		                }
		                
		            }
		            $classData[] = $data; 
        		}
        	}
        	else
        	{
        		$data['title'] = '<a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a>';
	            $data['id']    = $value->id;
	            $data['start'] = date('Y-m-d', strtotime($value->classSessionSettings[0]->sessionStartTime));
	            $data['end']   = date('Y-m-d', strtotime($value->classSessionSettings[0]->sessionEndTime));
	            $data['classDuration'] = $value->classDuration.' '.$value->classDurationType;
	            $data['objectType'] = $value->objectType;
	            $data['hours']      = $value->parentCourse->creditHours;
	            $data['icon']       = $value->parentCourse->iconReference;
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

	            if($value->isClassRegistrationAllow)
	            {
	                if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out'){
                        if($value->locationType->name=="onlocation"){
                            $data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                        }elseif($value->locationType->name=="online"){
                            $data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                        }
	                    //$data['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/online/overview" class="btn btn-primary px-3 py-1" target="_blank">Registers</a>';

	                }
	                else{
	                    $data['register'] = ' ';
	                }
	                
	            }
	            $classData[] = $data; 
        	} 
        }
        return $classData;
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
                <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text bg-white rounded-0"><i class="fa fa-calendar-alt"></i></div>
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

        <div class="calendar__days col-md-8 pb-4 pt-5 border mb-4 mb-md-0 calendar-background" id="monthView">

            <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3 text-uppercase small" style="left: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>','<?php echo date("d",strtotime($date.' - 1 Month')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo date("F",strtotime($date.' - 1 Month')); ?></span></a>
                <h3 class="text-center text-uppercase"><?php echo date("F Y",strtotime($date)); ?></h3>
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small" style="right: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>','<?php echo date("d",strtotime($date.' + 1 Month')); ?>');"><span class="mr-2"><?php echo date("F",strtotime($date.' + 1 Month')); ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__top-bar mt-4 mt-lg-5 text-uppercase d-flex text-center pb-4">
                <span class="top-bar__days">Mon</span>
                <span class="top-bar__days">Tue</span>
                <span class="top-bar__days">Wed</span>
                <span class="top-bar__days">Thu</span>
                <span class="top-bar__days">Fri</span>
                <span class="top-bar__days">Sat</span>
                <span class="top-bar__days">Sun</span>
            </div>

            <?php
                $dayCount = 1;
                $classdata = $this->classCalendar();

                echo '<div class="calendar__week text-center d-flex justify-content-around">';
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
                                <div class="calendar__day event col flex-column d-flex p-0 today bg-light border border-success rounded" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($filteredItems)) {echo json_encode($filteredItems);}else{ echo "no-data"; } ?>'>
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
                                <div class="calendar__day event col flex-column d-flex p-0 rounded" data-event="<?php echo $currentDate; ?>" data-start='<?php echo json_encode($filteredItems); ?>' onclick="getEvents('<?php echo $currentDate; ?>');">
                                    <span class="calendar__date mt-auto calendar-text"><?php echo $dayCount; ?></span>
                                    <span class="calendar__task small pt-lg-2 mb-auto calendar-text"><?php echo count($filteredItems).' class'; if(count($filteredItems) >1) {echo "es"; } ?></span>
                                </div>
                                <?php
                            
                        }else{
                            echo '
                                <div class="calendar__day no-event col flex-column d-flex p-0 rounded" data-event="'.$currentDate.'" data-start="no-data">
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
                            <div class="calendar__day no-event col flex-column d-flex p-0 rounded inactive">
                                <span class="calendar__date my-auto">'.$inactiveCalendarDay.'</span>
                               
                            </div>
                        ';
                    }
                    echo ($cb%7 == 0 && $cb != $boxDisplay)?'</div><div class="calendar__week text-center d-flex justify-content-around">':'';
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
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small" style="left: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' - 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' - 7 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2">Prev</span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small" style="right: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("m",strtotime($week_start_date.' + 7 day')); ?>','<?php echo date("d",strtotime($week_start_date.' + 7 day')); ?>');"><span class="mr-2">Next</span><i class="fa fa-chevron-right"></i></a>
            
            <div class="calendar__top-bar mt-4 mt-lg-5 text-uppercase d-flex text-center bg-light">
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Mon</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Tue</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Wed</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Thu</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Fri</span>
                <span class="top-bar__days border border-right-0  py-2 py-md-4">Sat</span>
                <span class="top-bar__days border   py-2 py-md-4">Sun</span>
            </div>
            <div class="calendar__week text-center d-flex justify-content-around pt-3">
            <?php 
                for ($i=0; $i <7 ; $i++) { 
                   
                        $currentDate = $week_array[$i];

                        // Get number of events based on the current date
                        
                        $weekfilteredItems = array_filter($classdata, function($item) use ($currentDate) {
                            return $currentDate >= $item['start'] && $currentDate <= $item['end'];
                        });
                        sort($weekfilteredItems);
            ?>			
            
                        <div class="calendar__day <?php if(count($weekfilteredItems) > 0){ echo 'event'; } else { echo 'no-event';}; ?>  col flex-column d-flex p-0 rounded <?php  if(strtotime($currentDate) == strtotime(date("Y-m-d"))){ echo 'today bg-light border border-success'; } ?>" data-event='<?php echo $currentDate; ?>' onclick="getEvents('<?php echo $currentDate; ?>');" data-start='<?php if(count($weekfilteredItems)) {echo json_encode($weekfilteredItems);}else{ echo "no-data"; } ?>'>
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
             <a href="javascript:void(0);" class="title-bar__prev position-absolute border-right border-bottom p-2 p-lg-3  text-uppercase small" style="left: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' - 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' - 1 day')); ?>');"><i class="fa fa-chevron-left"></i><span class="ml-2"><?php echo $prev_date; ?></span></a>
                
            <a href="javascript:void(0);" class="title-bar__next position-absolute border-left border-bottom p-2 p-lg-3 text-uppercase small" style="right: 0; top: 0" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("m",strtotime($postedDate.' + 1 day')); ?>','<?php echo date("d",strtotime($postedDate.' + 1 day')); ?>');"><span class="mr-2"><?php echo $next_date; ?></span><i class="fa fa-chevron-right"></i></a>
            <div class="calendar__week text-center justify-content-around" id="today_event">

            </div>

        </div>
    </div>
</div>
    </main>

<?php
wp_die();
}
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