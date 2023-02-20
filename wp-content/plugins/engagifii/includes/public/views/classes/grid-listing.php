<?php
  $datatableJS=false;
    $options  = get_option( 'ebt_api_settings' );
  $classStates =['Upcoming'];
  if($options['allClasses']==1) { 
  	$classStates = [];
   }
function classdataJS($classStates){  
    $postData = array();  
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = '';    
    $postData['pageNumber'] = 1;    
    $postData['sortDirection'] = "desc";
    $postData['filterBody'] = array('searchText'=>'','selectedDate' => date('Y-m-d'),'classStates'=>$classStates); //'searchText'=>$title,  
    return $postData;
}
	$classdatJS =classdataJS($classStates);
     $classdatJS = $this->submitApiRequest("Public/ClassPagingList",  $classdatJS, "POST", 'classes');
	 //$url = ENGAGIFII_ASSETS_URL.'/classdata.txt';
	//$JSON = file_get_contents($url);
	 //$xx   = json_decode($JSON)->result;
	 $classdatJS   = json_decode($classdatJS['api_response'])->result;
	// print_r($);
	// die;
	if(json_decode($classdatJS['api_response'])->totalCount<100 ){
		$datatableJS=true;
	}
	//datatable JS data
if($datatableJS){
//echo json_decode($xxx['api_response'])->totalCount;
$dataa=array();
$k=1;
$counter = 0; 
$class_schedule = '';
function registrationEnabled($registrationurl='',$registrationBtn='Register'){
return '<a style="white-space:nowrap" href="'.$registrationurl.'" class="btn btn-primary px-3 py-1" target="_blank">'.$registrationBtn.'</a>';	
}
function registrationDisabled($registrationTitle='',$registrationBtn='Register'){ 
	return '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$registrationTitle.'"><button type="button" id="" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">'.$registrationBtn.'</button></span>';
}
foreach ($classdatJS as $key => $value) {
$nestedData = array();
$instructorPopOver = '';
$class_icon = $value->parentCourse->iconReference;
if($siteURL == "https://engagifiwebstg.wpengine.com/oresa" || $siteURL == "https://engagifiiweb.com/oresa" || $siteURL == "https://oconeeresa.org"){
  $class_icon = ENGAGIFII_ASSETS_URL.'/images/oconee-logo.png';

}
//sessions dropdown html
$classPopover = '';
if(count($value->classSessions)){
  $classPopover  =  dd_header('Class Dates');
  $subItems = "";
  $li=1;
  foreach ($value->classSessions as $key => $rowData) {
	$rowName[$rowData->id] = $rowData->id;
	$classTime = '';
	if($rowData->sessionDate){
	  $classTime = date('M d Y', strtotime($rowData->sessionDate)).' At '.$rowData->startTime.' - '.$rowData->endTime;
	}
	$class='';
	if($li%2==1){
	  $class='bg-light';	
	}
	$subItems .= '<li class="px-2 py-1 border-bottom align-items-center small '.$class.'" style="display:flex"><img style="max-width:25px" src="'. ENGAGIFII_ASSETS_URL.'/images/class.png' .'" class="img-fluid mr-2"/>' . $classTime . '</li>';
	$li++;
  }
  $classPopover .= $subItems;
  $classPopover.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
}
//class name
if(count($value->classSessions)){
  foreach ($value->classSessions as $key => $rowData) {
	$counter = 0; 
	$class_schedule = '';
	$classSessionTime = '';
	if( $counter == 0 ) {         
		$classSessionStartTime = $rowData->startTime;
		$classSessionStartDate = $rowData->sessionDate;
	}                  
	if( $counter == count( $value->classSessions ) - 1) {
		 $classSessionEndTime = $rowData->endTime;
		 $classSessionEndDate = $rowData->sessionDate;
	}
	$classSessionTime = date('M d, Y', strtotime($classSessionStartDate)).' - '.date('M d, Y', strtotime($classSessionEndDate));
	$class_schedule = '<small class="d-block" style="white-space:normal;">'.$classSessionTime.' <br>'.$classSessionStartTime.'-'.$classSessionEndTime.'</small>';
	$counter = $counter + 1;
  }
  $nestedData['sectionname'] = '<div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'</div></div>';
}else{
  $nestedData['sectionname'] = '<div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'<small class="d-block" style="white-space:normal;">'.date('M d, Y', strtotime($value->startDate)).' at '.date('h:i A', strtotime($value->startDate)).' - '.date('h:i A', strtotime($value->endDate)).' </small></div></div>';
}

$nestedData['classDuration'] = $value->classDuration.' '.$value->classDurationType;
$nestedData['objectType'] = $value->objectType;
//session dropdown
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
$nestedData['startdate']= '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->classSessions).'</span></div>'.$classPopover.'</div>';
}else {
$nestedData['startdate']= '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" >';	
}


if($value->classInstructorsCount>0){
$nestedData['classInstructorsCount']= '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.($value->classInstructorsCount).'</span></div>'.$instructorPopOver.'</div>';  
} else {
$nestedData['classInstructorsCount']= '<img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Instructors Available" >';
}

if($value->isCreditTypeSingle =="true"){
$nestedData['credithours']= number_format($value->courseCreditMapping[0]->credits, 2);//($value->courseCreditMapping[0]->credits);          
}else{
$nestedData['credithours']= number_format($value->courseCreditMapping[0]->credits, 2);      
}
 
$classTag = $value->classTag;
$allTags = array();
foreach ($classTag as $index => $tag) {

if(count($classTag) > 1 && $index == 0)
{   
	$tagPopover = '';

	   $tagCount   = count($classTag) - 1;

$allTags[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$tag->tagName.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$key.'" data-placement="left" data-containerid="' . $key . '" id="' . $key . '"> +' . $tagCount .'</span>'.$tagPopover.'</div>';
}
elseif(count($classTag) == 1)
	$allTags[] = $tag->tagName;
}

$nestedData['classTag']= implode(" ", $allTags);

/*if($value->isClassRegistrationAllow || $value->registrationWorkFlowId){
if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
{
if($value->locationType->name=="onlocation")
  { 
   $nestedData['register']= '<a href="'.$value->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
  //$nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
  }
  elseif($value->locationType->name=="online"){
	  $nestedData['register']= '<a href="'.$value->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
  }
  elseif($value->locationType->name=="onlocationandonline"){
  $nestedData['register']= '<a style="white-space:nowrap" href="'.$value->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1 mb-2" target="_blank" >Register Online</a><br/><a style="white-space:nowrap" href="'.$value->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank" >Register in person</a>';
  }
else{
 $nestedData['register']= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Class Location not defined"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
}
}
else{
$nestedData['register']= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
}
}else{
$nestedData['register']= '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
}*/
//Registration button
if($value->isClassRegistrationAllow || $value->registrationWorkFlowId){
	  if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out'){
	  if($value->locationType->name=="onlocation")	{ 
		 	$nestedData['register']= registrationEnabled($value->registrationUrlOnLocation);
		}
		elseif($value->locationType->name=="online"){
			$nestedData['register']= registrationEnabled($value->registrationUrlOnLine);
		}elseif($value->locationType->name=="onlocationandonline"){
			$nestedData['register']= registrationEnabled($value->registrationUrlOnLine,'Register Online');
			$nestedData['register'].= '<div class="mb-2"></div>';
			$nestedData['register'].= registrationEnabled($value->registrationUrlOnLocation,'Register in person');;
		}
		else{
			$nestedData['register']= registrationDisabled('Class Location not defined');
		}
	  }
	  else{
	    $nestedData['register']= registrationDisabled($value->registrationState);
	  }
}else{
  		$nestedData['register']= registrationDisabled($value->registrationState);
}
 $k++;
 $dataa[] = $nestedData;

}

}
	
	




  $default_length = '10';
  $calendar_view = false;
  $calendar_view_classname = false;
  if(isset($attr['records'])){
    $default_length = $attr['records'];
  }
  if(isset($attr['calendar'])){
    $calendar_view = $attr['calendar'];
  }
  if(isset($attr['calendarclassname'])){
    $calendar_view_classname = $attr['calendarclassname'];
  }
  
  $obj      =  new Engagifii_API();
  $collection   = array();
    $forDatatable   =   array();
    $date           =   date('Y-m-d');
    //print_r($date);
    $options  = get_option( 'ebt_api_settings' );
    $class_visible_column_list = $options['class_visible_column_list'];
	 
//print_r($options['upcomingClasses']);
	//die;
   // print_r($class_visible_column_list);
    $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
    

    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[1]);
    unset($collection[7]);
    unset($collection[8]);



    $classes = $obj->getAllClassCourses($date);
    $creditFilter    = $obj->getCreditHoursFilter($date);
    $instructor = $obj->classAllInstructors($date);
    $dateRange  = $obj->classRegDateFilters($date);
 	   $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
  	  $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
    $classdateRange  = $obj->classdateFilters($date);
 	   $class_start_date   = date('m/d/Y',strtotime($classdateRange['minStartDate']));
  	 $class_end_date = date('m/d/Y',strtotime($classdateRange['maxEndDate']));
     $class_start_date = date("Y-m-d",strtotime ( '-1 day' , strtotime ( $class_start_date ) )) ;	
     //print_r($class_start_date);
	
    $title_key = -1;
    
?>
   <?php $placeholder_text = 'Search by class name';
   echo do_shortcode('[view_mode search="on" placeholder="'.$placeholder_text.'"]');  ?>
<?php

  if($calendar_view){
?>

  <?php //echo do_shortcode('[class-calendar]'); ?>
<?php
  }
  else if($calendar_view_classname){
	  
    ?>
    
      <?php echo do_shortcode('[class-calendar-class-name]'); ?>
    <?php
      }
?>
<?php 
$dt_class=' ';
$dt_respnsive = '';
$dt_respnsive = get_option( 'ebt_api_settings' )['dt_responsive'];
if($dt_respnsive==1){
$dt_class = 'dt-responsive nowrap ';	
}
$dt_darktheme = '';
$dt_darktheme = get_option( 'ebt_api_settings' )['dt_darktheme'];
if($dt_darktheme==1){
$dt_class .= 'table-dark ';	
}
?>
<div class="containerEngagii ff" id="list_div" <?php if($calendar_view || $calendar_view_classname){ echo 'style="display:none"'; } ?>>
  <div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
    <table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here classes-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
      <thead> 
        <tr>        
          <?php
            if(is_array($collection) && count($collection)>0){
              $i = 0;
              foreach ($collection as $key => $value) {
                 if(in_array($value->colName, $class_visible_column_list)){
                
                  if($value->displayName == 'Class Type')
                  {
                     $value->displayName = "Type";
                  }
                  if($value->colName == 'sessions')
                  {
                      $value->colName = 'startdate';
                  }
				  if($value->displayName == 'Class dates')
                  {
                      $value->displayName = 'Class Dates';
                  }

                  if($value->colName == 'sectionname'){
                    $title_key = $i;
                  }
                  $forDatatable[]['data'] = $value->colName;
                ?>
                  <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
                <?php
                $i++;
                }
              }
            }
          ?>    

        </tr> 
      </thead>
    </table>
    <div id="eng-overlay"><span class="spinner"></span></div>
</div>

</div>
<?php
function removeWhitespace($buffer)
{
    return preg_replace('/\s+/', ' ', $buffer);
}

ob_start();
?>
<div class="filter-content">
	<div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative bg-light border"><i class="far fa-filter click-filter"></i><span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span></div> 
  <div class="filter-border">
  <div class="filter-area d-none">
    <div class="Engagiirow filter-top-bg col-sm-12 py-2 bg-dark text-white">
    <div class="row">
      <div class="col-6 text-left">
        <span class="filter-title">
          <i class="far fa-filter mr-2"></i> Filter
          <span id="blockedchecked"></span> 
        </span>
      </div>
      <div class="col-6 text-right">
        <span class="clear-all" id="clear-all"> <i class="fal fa-sync"></i> </span>
      </div>
      </div>
    </div>
    <div class="">
      <input type="hidden" id="isApplyACtive" value="0">
      <?php if(in_array('sessions', $class_visible_column_list)){ ?>
      <div class="filter-list border-bottom px-2">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Class Dates <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative pb-2">
          <input type="text" name="classdates" id="classdates"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>
      <?php } if($classes){ ?>
      <div class="filter-list border-bottom px-2">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between">Course Name <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none">
          <ul class="list-group m-0">
          <?php foreach ($classes as $key => $value) { echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" id="class_'.$key.'" name="courseClass[]" value="'.addslashes($value['name']).'"><label class="" for="class_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';} ?>
          </ul>
        </div>
      </div>
<?php } //credit Hour filters
 if(in_array('credithours', $class_visible_column_list)){ ?>
<div class="filter-list border-bottom px-2">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between" for="creditFilter"> Credit Hours <i class="far fa-angle-down "></i></div>
        <div class="content-area d-none">
          <?php

              echo '<input id="creditFilter" name="creditFilter" type="text" class="span2 form-control form-control-sm mb-3 shadow-none" readonly value="" data-slider-min="'.$creditFilter['minRange'].'" data-slider-max="'.$creditFilter['maxRange'].'" data-slider-step="5" data-slider-value="['.$creditFilter['minRange'].','.$creditFilter['maxRange'].']"/><div id="slider-range" class="mx-2"></div>';
          ?>  
        </div>
      </div>
      <?php } 
	  if(in_array('classInstructorsCount', $class_visible_column_list)){
	  ?>
      <div class="filter-list border-bottom px-2">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Instructors <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php

            foreach ($instructor as $key => $value) {
              echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" id="instructor_'.$key.'" name="courseInstrutor[]" value="'.$value['id'].'"> <label  for="instructor_'.$key.'"><small>'.addslashes($value['name']).'</small></label></li>';
            }
          ?>  
        </ul></div>
      </div>
	<?php } ?>
      
      

      <div class="filter-list border-bottom px-2">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Registration Date <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative pb-2">
          <input type="text" name="createdbetween" id="createdbetween"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>

      
    </div>
    <div class="apply-filter">
        <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data">Apply 
          <span id="countFilterResult"></span>
        </button>
      </div>
  </div>
</div>
</div>
<?php
$filter_content =ob_get_contents();
ob_end_clean();
$filter_content = removeWhitespace($filter_content);
?>
<script type="text/javascript">
  var courses = '';
  var instructor = '';
 
  var class_start_date     = '<?php echo $class_start_date; ?>';
  var class_end_date     = '<?php echo $class_end_date; ?>';
  var classDates     = '';
  //var endDate     = '';
  var createdDate = '';
  var minRange ='<?php echo (int)$creditFilter['minRange']; ?>';
  var maxRange ='<?php echo (int)$creditFilter['maxRange']; ?>';
  var minReg ='<?php echo $min_date; ?>';
  var maxReg ='<?php echo $max_date; ?>';
  var classStates =["Upcoming"];
  <?php if($options['allClasses']==1) { ?>
  	classStates = [];
  <?php  } ?>
  

  var fv = 0;
  
   $( document ).ready(function() {
	  if(localStorage.getItem("view_mode")=='list'){
		$('#list').trigger("click");
	} 
   });
 
 
   $('#list').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#calendar').removeClass('btn-primary').addClass('btn-light');
            $('#list_div').show();
			$('.flt-btn').fadeIn(300);
            $('#calendar_div, #calendar_filter, #calendarsearch_div, .calendarsearch-form, .new-search').hide();
 localStorage.setItem("view_mode",$('.view-m .btn-primary').attr('id'));
        });
        $('#calendar').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#list').removeClass('btn-primary').addClass('btn-light');
            $('#calendar_div, #calendar_filter, .calendarsearch-form, .new-search').show();
            $('#list_div, #calendarsearch_div, .filter-border').hide();
			$('.flt-btn').fadeOut(100);
        $('.filter-area').toggleClass('d-none');
 localStorage.setItem("view_mode",$('.view-m .btn-primary').attr('id'));
        });
  var table = $('#ebtmaintable').DataTable( {
        "pageLength": '<?php echo $default_length; ?>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        //"dom": '<"row"<"col-md-11 col-10"><"top-filter col-md-1 col-2 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
        "bInfo":false,
        "processing": true,
        "searching": true,
        "ordering":true,
		"search": {regex: true},
		<?php if(in_array('sessions', $class_visible_column_list)){ ?>
		"order": [[<?php echo array_search('sessions',$class_visible_column_list);?>, 'asc']],
		 <?php } ?>
        "columnDefs": [ 
          { "targets": ['objectType','classDuration',  'credithours', 'classTag', 'classInstructorsCount', 'register'],
            "orderable": false
          },
          //{ width: 200, targets: 3 },
		  { className: "title-col", "targets": "classes" },
		  { className: "text-center", "targets": ["startdate","instructors","credithours","register","duration","objectType","classTag"] },
		  { responsivePriority: 1, targets: 'sectionname' },
		  <?php if(in_array('sessions', $class_visible_column_list)){ ?>
		  {'targets': <?php echo array_search('sessions',$class_visible_column_list);?>, 'createdCell':  function (td, cellData, rowData, row, col) {
			  var html = $(cellData);
			  var editor = $("<p>").append(html);
			  var cell = editor.find("span:first-child").html();
           $(td).attr('data-order', cell ); 
       		 }
    	 }
		 <?php } ?>
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-'
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
		<?php if(!$datatableJS){ ?>
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
              d.action='classes'; 
              d.courses = courses;
              d.instructors = instructor;  
             // d.createdDate = createdDate;   
			   d.minRange = minRange; 
            d.maxRange = maxRange;
			d.minReg = minReg;
			d.maxReg = maxReg;
			d.class_start_date = class_start_date;
			d.class_end_date = class_end_date;
			d.classStates=classStates;
            }, 
        },
		 <?php } if($datatableJS){ ?>  
		"data": <?php echo json_encode($dataa);  ?>,
		<?php } ?>
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
        },  
        "columns":<?php echo (json_encode($forDatatable)); ?>,
         "drawCallback": function( settings ) {
			 
			 dt_dropdown();
			 <?php if($dt_respnsive==''){ ?>
           dt_scroll();
			   <?php } ?>
			   $('[data-toggle="tooltip"]').tooltip() ;
         },
		 
		  "initComplete": function(settings, json) {
			  $('#eng-overlay').css( 'display', 'none' );
		/* $('.dataTables_filter label').append('<button type="button" class="btn text-muted shadow-none bg-transparent position-absolute blank"><i class="fa fa-times"></button>');
		 $('.dataTables_filter input').keyup(function(){
			if($(this).val()==''){
				$(this).parent('label').removeClass('has-data');
			} else {
				$(this).parent('label').addClass('has-data');
			}
		 });
		 */

    }
		
		
		
    });
<?php
  if($title_key > -1){
?>

  $('#ebtmaintable thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {

$('.list-search-btn').click(function(e){
	var ttitle= $('.list-search').val();
	if(ttitle!=''){
		$('#list').trigger('click');	
		table.column(i).search(ttitle).draw();
		 $( '#searchclass' ).val($('.list-search').val());
		$('.clear-search').show();
	} else {
		alert("search field can't be empty");	
	}
	e.stopPropagation();
 });
$('.list-search').on("keydown", function(event) {
  if(event.which == 13){
	$('.list-search-btn').trigger('click');  
  }  
});
 
         var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" id="searchclass" placeholder="Search classes" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:3;display:none"><i class="fal fa-times"></i></button></div>' );

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
  $( 'input', this ).keyup(delay(function (e) {
	  var titlesearch = this.value;
            if ( table.column(i).search() !== titlesearch ) {
				table.column(i).search(titlesearch).draw();
            }
}, 500));


 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$('.clear-search').show();
			} else {
				$('.clear-search').hide();
			} 
 });
$('th .clear-search').click(function(e){
	 $('#searchclass').val('');
	$('.clear-search').hide();
	e.stopPropagation();
	table.column(i).search('').draw();
 });

    } );
	
	$(document).ready(function (){    
    $('#searchclass, .search-dt span').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchclass').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});
});
  <?php
}
  ?>

   	/*$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});*/
  $('div.flt-btn').html('<?php echo $filter_content; ?>');


    $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();

    $('.filter-icon').click(function(e){
        e.stopPropagation();
        $('.filter-border').show();
        $('.filter-area').toggleClass('d-none');
        $('#isApplyACtive').val(1);
		jQuery(".filter-area .list-group").mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:"minimal-dark",
		 			scrollbarPosition:"outside"
		 			});
    })

    $('.heading-title').click(function(){
		$(this).next('.content-area').toggleClass('d-none');
		$(this).parent().siblings('.filter-list').find('.content-area').addClass('d-none');
	});

  
 $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
	$('input[name="classdates"]').val('');
});
$('input[name="createdbetween"]').daterangepicker({
   minDate:'<?php echo $min_date; ?>',
    maxDate: '<?php echo $max_date; ?>',
    autoApply: true
  }, function(start, end) {
      createdDate = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
		var regDate = createdDate.split("-");
	 	  minReg = $.trim(regDate[0]);
		maxReg = $.trim(regDate[1]);
     countFilterData();
 if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
    });
<?php  if(in_array('sessions', $class_visible_column_list)) { ?>
$('input[name="classdates"]').daterangepicker({
   minDate:'<?php echo $class_start_date; ?>',
    maxDate: '<?php echo $class_end_date; ?>',
    autoApply: true
  }, function(start, end) {
      classDates = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
		var classDate = classDates.split("-");
	 	  class_start_date = $.trim(classDate[0]);
		class_end_date = $.trim(classDate[1]);
     countFilterData();
 if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
    });
<?php } ?>
$( '.cleardate' ).click(function() {
	if($(this).siblings().attr('id')=='createdbetween'){
		 $('input[name="createdbetween"]').val('');	
		 minReg = '<?php echo $min_date; ?>';
		maxReg = '<?php echo $max_date; ?>';
	} else if($(this).siblings().attr('id')=='classdates'){
		 $('input[name="classdates"]').val('');	
		class_start_date     = '<?php echo $class_start_date; ?>';
		class_end_date     = '<?php echo $class_end_date; ?>';
	} 
   
	 if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
    countFilterData();
});
$('.clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
			$('input[name="classdates"]').val('');

            $('input[name="creditFilter"]').val('<?php echo (int)$creditFilter['minRange']; ?>'+'-'+'<?php echo (int)$creditFilter['maxRange']; ?>');
			var $slider = $("#slider-range");
  				$slider.slider("values", 0, <?php echo (int)$creditFilter['minRange']; ?>);
  				$slider.slider("values", 1, <?php echo (int)$creditFilter['maxRange']; ?>);
            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active');  
            courses = '';
            instructor = '';
            minReg = '<?php echo $min_date; ?>';
			maxReg = '<?php echo $max_date; ?>';
            minRange = '<?php echo (int)$creditFilter['minRange']; ?>';
			 maxRange = '<?php echo (int)$creditFilter['maxRange']; ?>';
			class_start_date     = '<?php echo $class_start_date; ?>';
			class_end_date     = '<?php echo $class_end_date; ?>';
			  $(".filter-area").toggleClass('d-none');
            table.draw();

      })

//filter
    $('#apply-filter-data').click(function(){
      courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
	  if($('input[name="createdbetween"]').val()!=''){
		var regDate = $('input[name="createdbetween"]').val().split("-");
	 	  minReg = $.trim(regDate[0]);
		maxReg = $.trim(regDate[1]);
	  }
	  <?php if(in_array('sessions', $class_visible_column_list)) { ?>
	  if($('input[name="classdates"]').val()!=''){
		var classDate = $('input[name="classdates"]').val().split("-");
	 	  class_start_date = $.trim(classDate[0]);
		class_end_date = $.trim(classDate[1]);
	  }
      <?php } if(in_array('credithours', $class_visible_column_list)) { ?>
	  var range = $('#creditFilter').val().split("-");
	  minRange = range[0];
	   maxRange = range[1];
	   <?php } ?>
      $(".filter-area").toggleClass('d-none');
      table.draw();

    });
	  
	  $(document).on('click', function (e) {
 $('.filter-area').addClass('d-none');
});
$(document).on('click', '.filter-area', function (e) {
  e.stopPropagation();
});
$(document).on('click', 'th.prev', function (e) {
  e.stopPropagation();
});
$(document).on('click', 'th.next', function (e) {
  e.stopPropagation();
});
$(document).on('click', '.daterangepicker ', function (e) {
  e.stopPropagation();
});
	  


	
	

	  $("#apply-filter-data").click(function () {
 
  
  $('.filter-list').each(function() {
	 if ($(this).find('input[type=checkbox]').is(':checked')) {
		$(this).addClass('checked');
	 } else {
		$(this).removeClass('checked');
	 }
  });
  fv = $('.filter-list.checked').length;
  if($('input[name="createdbetween"]').val()!=''){
	fv += 1;  
  }
  if($('input[name="classdates"]').val()!=''){
	fv += 1;  
  }
  console.log(fv);
  if(fv>0){
	$('.filter-icon').addClass('active');
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active');  
  }
 });  


     $('.filter-list input[type=checkbox]').change(function(){
		 if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
          countFilterData();
      })


    function countFilterData()  {

      var courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      var instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
	  <?php  if(in_array('credithours', $class_visible_column_list)) { ?>
      var range = $('#creditFilter').val().split("-");
	  minRange = range[0];
	   maxRange = range[1];
      <?php } ?>
          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'classcountdata',
              courses : courses,
              instructors : instructor,
              minReg : minReg,
			  maxReg : maxReg,
			  class_start_date : class_start_date,
			  class_end_date : class_end_date,   
              minRange : minRange,
			  maxRange:maxRange,
			  classStates:classStates
        
          },
          success: function(response) {       
            var element  = document.getElementById("countFilterResult");
	  $('#apply-filter-data .spinner-border').remove();
	  $('#apply-filter-data').removeAttr('disabled');
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
            }    
          }
        });
      }
$(document).ready(function(){
  <?php
  if($calendar_view || $calendar_view_classname){
  ?>
   //$('#list_div').hide();
   <?php
    }
   ?>
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
	
	
});

<?php  if(in_array('credithours', $class_visible_column_list)) { ?>
$(document).ready(function(){

$("#slider-range").slider({
        range: true,
        min: <?php echo (int)$creditFilter['minRange']; ?>,
        max: <?php echo (int)$creditFilter['maxRange']; ?>,
        values: [<?php echo (int)$creditFilter['minRange']; ?>, <?php echo (int)$creditFilter['maxRange']; ?>],
		step: 5,
        
     slide: function(event, ui ) {
	    $( "#creditFilter" ).val(  ui.values[ 0 ] +'-'+  ui.values[ 1 ] );
		if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
      countFilterData();  

      }

});
    $( "#creditFilter" ).val(  $( "#slider-range" ).slider( "values", 0 ) +'-'+
       $( "#slider-range" ).slider( "values", 1 ) );
});
<?php } ?>
</script>