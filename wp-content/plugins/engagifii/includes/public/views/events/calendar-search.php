 <script type="text/javascript">
   var search="";
    $('#apply-filter-search-cal').click(function(e){
        
		    e.preventDefault(); 
            search = $('#calendar-search').val();
			
			$("#search-demo").val(search);
            $('#calendar_div').hide();
            $('#list_div').hide();
            $('#calendar_filter').hide();
            $("#calendarsearch_div").show();
            $('#calendar').removeClass('btn-primary').addClass('btn-light');
            var value = search.toLowerCase();
            $(".calendarlist tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            $(".calendarlist tr td .row").each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				
			 });
			 if($(".calendarlist tr:visible").length==0){
				 $('.no-results').removeClass('d-none');
			 } else {
				 $('.no-results').addClass('d-none');
			 }

           /* const yoffset = -400;
            target_offset = $('.dayofmonth').offset(),
            target_top = target_offset.top + yoffset;
            $('html, body').animate({
                scrollTop: target_top
            }, 2000);*/
});

 $('#calendar-search').keyup(function(e){
	if(this.value.length!=0){
				$('.clear-search').show();
			} else {
				$('.clear-search').hide();
			} 
 });
$('.apply-search .clear-search').click(function(e){
	 $('#calendar-search').val('');
	 if($('#calendarsearch_div ').is(':visible')){
	  $('#apply-filter-search-cal').trigger('click');
	 }
	$('.clear-search').hide();
 });
             


            
</script>
<style>
   
    .modal-body{
	color: black;
    text-align: left;
    font-size: 14px;
}
h5#exampleModalLabel2 {    
    font-size: medium;
}
.modal-header {
        padding: 1rem .4rem;
        justify-content: flex-start;
}


.calendarsearch::before {
  content: "\2022";  
  color: #1a73e8; 
  font-weight: bold; 
  display: inline-block; 
  width: 1em; 
  font-size: 1.5em;
}
    span.dayofmonth {
    font-size: 18px;
    letter-spacing: -1.8px;
    text-indent: -1.8px;
    font-variant: tabular-nums;
    -webkit-font-feature-settings: "tnum" 1;
    font-feature-settings: "tnum" 1;
    -webkit-border-radius: 100%;
    border-radius: 100%;
    cursor: pointer;
    display: inline-block;
    height: 36px;
    line-height: 36px;
    text-align: center;
    width: 36px;
    margin-top: -2px;
    background-color: #1a73e8;
    color: white;
}
span#digitofday {
    font-size: 24px;
}
    
    </style>
</style>
    
<?php 
$obj      =  new Engagifii_API();
$searchtext = ""; 


function _prepareEventData($searchtext){    
    $upcomingEvents = get_option( 'ebt_api_settings' )['upcomingEvents'];
        if($upcomingEvents==1){
        $upcomingEvents = 'true';	
        }else{
            $upcomingEvents = 'false';
        }
        if(!empty($_POST['createdDate']))
        {
            $dateRange = explode("-", $_POST['createdDate']);
            $postData['eventStartDate'] = date('m-d-Y',strtotime($dateRange[0]));
            $postData['eventEndDate'] = date('m-d-Y',strtotime($dateRange[1]));
        }

    $title = $searchtext;
    $postData = array();  
    $sortBy       = "";
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = $sortBy;    
    $postData['pageNumber'] = 1;    
    $postData['pageSize'] = 12;
    $postData['sortDirection'] = "desc";
    $postData['upcomingEvents'] = $upcomingEvents;
    $postData['filterBody'] = array('searchText'=>$title,'selectedDate' => date('Y-m-d')); //'searchText'=>$title,  
    return $postData;
}

$options = get_option('ebt_api_settings');
        
//$events_api_url = $options['evt_api_url'];
$engagifii_url          = $options['evt_tenant_code']['engagifii_url'];
print_r(json_encode($options));
$postedData = _prepareEventData($searchtext);
//print_r(json_encode($postedData));
$dataResponse = $this->submitApiRequest("Public/listEventsByFilter",$postedData,"POST",'event');
$collection   = json_decode($dataResponse['api_response'])->collection;
//print_r(json_encode($collection));
?>

<table class="table calendarlist table-hover table-sm table-bordered "> 
<?php
    $classData = array();

foreach ($collection as $key => $value) { 
                $eventName = $value->name;
                $eventId = $value->id;
                $icon = $value->imageUrl;
                $eventRegState = preg_replace('/(?<!\ )[A-Z]/', ' $0', $value->eventRegistrationState);
                    if($value->eventRegistrationState == 'Completed' || $value->eventRegistrationState == 'RegistrationClosed' || $value->eventRegistrationState == 'RegistrationNotStarted')
                        {
                            $register = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$eventRegState.'"><button type="button"  class="btn btn-primary px-3 py-1"  disabled style="pointer-events: none;">Register</button></span> ';
                             }
                        else{
                             $register = '<a href="'.$engagifii_url.'/pages/events/'. $eventId .'/signup/overview" target="_blank" class="btn btn-primary px-3 py-1" >Register</a>';
                             //$register = '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="top" title="'.$value->registrationState.'"><button type="button"  class="btn btn-primary px-3 py-1"  disabled style="pointer-events: none;">Register</button></span> ';
                          
                            }
                    
                
    foreach ($value->eventDates as $key => $result) {
                        $id = $result->id;
                        $eventData[$id]['title'] = '<a href="'.site_url().'/event-detail/?endId='.$eventId.'">'.$eventName.'</a>';
                        $eventData[$id]['titleNoLink'] = $eventName;
                        $eventData[$id]['hours']      = $value->parentCourse->creditHours;
                        $eventData[$id]['objectType'] = $value->eventType;
                        $eventData[$id]['price'] = $value->defaultPrice;
                        $eventData[$id]['date'] = date('Y-m-d', strtotime($result->sessionStartTime));//$result->sessionDate;
                        $eventData[$id]['eventId'] .= $eventId;
                        $eventData[$id]['Icon'] .= $icon;
                        $eventData[$id]['eventName'] .= $eventName;
                        $eventData[$id]['sessionStatus'] .=$result->sessionStatus;
                        $eventData[$id]['startTime'] .= date('h:i a', strtotime($result->sessionStartTime));
                        $eventData[$id]['endTime'] .= date('h:i a', strtotime($result->sessionEndTime));
                        $eventData[$id]['register'] = $register;
                        //$eventData[$id]['instructors'] = $value->classInstructors;
                      
   
    }
}
$result2 = array();
    foreach ($eventData as $element) {
        $result2[$element['date']][] = $element;
    }


ksort($result2);
//print_r(json_encode($result2));

$i=0;
foreach ($result2 as $key => $res) { 

    $eventSessionDate = $res['date'];
    $eventDate = $key;
    $todayDate = date('Y-m-d');
	
    ?>
    <tr>        
        <?php if($eventDate==$todayDate){ ?>
            <th scope="row"><span class="dayofmonth" id="digitofday"><?php echo date('j', strtotime($eventDate)).' '; ?> </span> <?php echo date("M Y", strtotime($eventDate));?></th>
       <?php } else { ?>
    <th class="text-nowrap px-xl-3" scope="row"><span id="digitofday"><?php echo date('j', strtotime($eventDate)).' '; ?> </span> <?php echo date("M Y", strtotime($eventDate));?></th>
    <?php } ?>
    <td>
<?php

usort($res, function($a, $b) {
    return strtotime($a['startTime']) <=> strtotime($b['startTime']);
});
    foreach($res as $keyobj => $data){
        //print_r(json_encode($data));
        //$testing = $data['instructors'];
?>
	<div class="row mb-2 px-xl-4">
    <div class="col-md-2"><span class="calendarsearch text-nowrap"><?php echo $data['startTime']; echo "  -  ".$data['endTime'];?></span></div>
	<div class="col-md-6 pt-2">
    	<div> <a data-toggle="modal" data-target="#exampleModal2<?php echo $data['eventId'];echo $i; ?>" href="" ><?php echo $data['titleNoLink'];?></a></div>
    	
    </div>
    <div class="col-md-4 pt-2">
    	<div class="">
    <?php 
    $inc = 1;
    
?>    </div>
    </div>
    
    
    <div class="modal fade" id="exampleModal2<?php echo $data['eventId'];echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center pr-5">
                   <img src="<?php echo $data['Icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded"><h5 class="modal-title"  id="exampleModalLabel2"><?php echo $data['title']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <p><strong>Date :</strong> <?php echo date("j M Y", strtotime($data['date'])).' at '; echo $data['startTime']; echo "  -  ".$data['endTime'];?></p>
                   <p><strong>Type :</strong> <?php echo $data['objectType']; ?></p>
                    <p><strong>Price : $</strong><?php echo $data['price']; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="../event-detail/?endId=<?php echo $data['eventId'] ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                    <?php echo $data['register']; ?>
                </div>
            </div>
        </div>
    </div> 
</div>
<?php $i++;}
} ?>
    </td>
    </tr>
</table>
<?php if($result2) {
$noresults ='Oops! No data found!! Try some other keyword';
 } else {
	$noresults ='No Events Available.'; 
 }?>
<div class="d-none no-results"><h4 class="text-center text-secondary"><?php echo $noresults; ?></h4></div>



