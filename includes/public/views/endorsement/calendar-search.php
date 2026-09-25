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


function _prepareEndorsementData($searchtext){    
    $title = $searchtext;
    $postData = array();  
    $sortBy       = "";
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = $sortBy;    
    $postData['pageNumber'] = 1;    
    $postData['pageSize'] = 1000;
    $postData['sortDirection'] = "desc";
    $postData['filterBody'] = array('searchText'=>$title,'selectedDate' => date('Y-m-d')); //'searchText'=>$title,  
    return $postData;
}


$postedData = _prepareEndorsementData($searchtext);
//print_r(json_encode($postedData));
$dataResponse = $this->submitApiRequest("Public/AwardListPublic",$postedData,"POST",'endorsement');
$collection   = json_decode($dataResponse['api_response'])->result;
//print_r(json_encode($collection));
$options = get_option('ebt_api_settings');
$engagifii_url          = $options['evt_tenant_code']['engagifii_url'];
?>

<table class="table calendarlist table-hover table-sm table-bordered "> 
<?php
    $classData = array();

foreach ($collection as $key => $value) { 
                $awardName = $value->name;
                $awardId = $value->id;
                $icon = $value->icon;
                
                $register = '<a href="'.$engagifii_url.'/pages/awards/'. $awardId .'/signup/overview" target="_blank" class="btn btn-primary px-3 py-1" >Register</a>';
                        $id = $value->id;
                        $classData[$id]['title'] = '<a href="'.site_url().'/arards/'.$awardId.'">'.$className.'</a>';
                        $classData[$id]['titleNoLink'] = $awardName;
                        $classData[$id]['validity']      = $value->validity;
                        $classData[$id]['objectType'] = $value->objectType;
                        $classData[$id]['price'] = $value->price;
                        $classData[$id]['date'] = date('Y-m-d', strtotime($value->createdOn));//$result->sessionDate;
                        $classData[$id]['classId'] .= $awardId;
                        $classData[$id]['Icon'] .= $icon;
                        $classData[$id]['className'] .= $className;
                        $classData[$id]['sessionStatus'] .=$result->sessionStatus;
                        $classData[$id]['startTime'] .= $result->startTime;
                        $classData[$id]['endTime'] .= $result->endTime;
                        $classData[$id]['register'] = $register;
                        $classData[$id]['instructors'] = $value->classInstructors;
                      
   
    //}
}
$result2 = array();
    foreach ($classData as $element) {
        $result2[$element['date']][] = $element;
    }


ksort($result2);
//print_r(json_encode($result2));

$i=0;
foreach ($result2 as $key => $res) { 

    $classSessionDate = $res['date'];
    $classDate = $key;
    $todayDate = date('Y-m-d');
	
    ?>
    <tr>        
        <?php if($classDate==$todayDate){ ?>
            <th scope="row"><span class="dayofmonth" id="digitofday"><?php echo date('j', strtotime($classDate)).' '; ?> </span> <?php echo date("M Y", strtotime($classDate));?></th>
       <?php } else { ?>
    <th class="text-nowrap px-xl-3" scope="row"><span id="digitofday"><?php echo date('j', strtotime($classDate)).' '; ?> </span> <?php echo date("M Y", strtotime($classDate));?></th>
    <?php } ?>
    <td>
<?php

usort($res, function($a, $b) {
    return strtotime($a['startTime']) <=> strtotime($b['startTime']);
});
    foreach($res as $keyobj => $data){
        $testing = $data['instructors'];
?>
	<div class="row mb-2 px-xl-4">
    <div class="col-md-4"><span class="calendarsearch text-nowrap"><?php echo 'Validity: '.$data['validity'];?></span></div>
	<div class="col-md-6 pt-2">
    	<div> <a data-toggle="modal" data-target="#exampleModal2<?php echo $data['classId'];echo $i; ?>" href="" ><?php echo $data['titleNoLink'];?></a></div>
    	
    </div>
    <div class="col-md-4 pt-2">
    	<div class="">
    <?php 
    $inc = 1;
    // foreach( $testing as $keyval => $instructor){
    //     if($inc==1){
	// 		echo "<span>Instructor(s) : </span>";
	// 	}
    //      echo '<span class="badge badge-light mr-1"><small>'.$instructor->fullName.'</small></span>'; 
	// 	$inc++; 
	//   }
?>    </div>
    </div>
    
    
    <div class="modal fade" id="exampleModal2<?php echo $data['classId'];echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center pr-5">
                   <img src="<?php echo $data['Icon']; ?>" class="img-fluid img-icon-lg mr-2 mCS_img_loaded"><h5 class="modal-title"  id="exampleModalLabel2"><?php echo $data['titleNoLink']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <p><strong>Created On :</strong> <?php echo date("j M Y", strtotime($data['date']));?></p>
                    <p><strong>Validity : </strong><?php echo $data['validity']; ?></p>
                    <p><strong>Award Type :</strong> <?php echo $data['objectType']; ?></p>
                    <p><strong>Price : </strong><?php echo '$'.$data['price']; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="../endorsement-detail?endId=<?php echo $data['classId']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
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
	$noresults ='No session available of any class.'; 
 }?>
<div class="d-none no-results"><h4 class="text-center text-secondary"><?php echo $noresults; ?></h4></div>



