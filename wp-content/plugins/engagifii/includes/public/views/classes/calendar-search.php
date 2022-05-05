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
            $(".calendarlist tr td a li").each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				
			 });

            const yoffset = -400;
            target_offset = $('.dayofmonth').offset(),
            target_top = target_offset.top + yoffset;
            $('html, body').animate({
                scrollTop: target_top
            }, 2000);
});
             


            
</script>
<style>
   
    .modal-body{
	color: black;
    text-align: left;
    font-size: 14px;
}
h5#exampleModalLabel2 {    
    margin-right: 50px;
    font-size: medium;
}
.modal-header {
        padding: 1rem .4rem;
        justify-content: flex-start;
}

    ul.calendarsearch {
  list-style: none; /* Remove default bullets */
}
li.calendarsearch:hover{
        background-color: #f1f2f3;
        border-radius: 5px;
        
    }

li.calendarsearch::before {
  content: "\2022";  
  color: #1a73e8; 
  font-weight: bold; 
  display: inline-block; 
  width: 1em; 
  margin-left: -1em; 
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
    font-family: Google Sans,Roboto,Arial,sans-serif;
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
$searchtext1 = $_POST['dummy']; 
$searchtext2 = $_GET['calendar-search'];  
$searchtext3 = $_REQUEST['calendar-demo']; 
$searchtext4 = $_POST['calendar-demo']; 
$searchtext5 = $_GET['calendar-demo'];  
$searchtext6 = $_REQUEST['search-demo']; 
$searchtext7 = $_POST['search-demo']; 
$searchtext8 = $_GET['search-demo'];  
echo $searchtext;
echo $searchtext1;
echo $searchtext2;
echo $searchtext3;
echo $searchtext4;
echo $searchtext5;
echo $searchtext6;
echo $searchtext7;
echo $searchtext8;


function _prepareClassData($searchtext){    
    $title = $searchtext;
    $postData = array();  
    $sortBy       = "";
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = $sortBy;    
    $postData['pageNumber'] = 1;    
    $postData['sortDirection'] = "desc";
    $postData['filterBody'] = array('searchText'=>$title,'selectedDate' => date('Y-m-d')); //'searchText'=>$title,  
    return $postData;
}


$postedData = _prepareClassData($searchtext);
$dataResponse = $this->submitApiRequest("Public/ClassPagingList",$postedData,"POST",'classes');
$collection   = json_decode($dataResponse['api_response'])->result;
//print_r($collection);
?>

<table class="table calendarlist table table-bordered "> 
<?php
    $classData = array();

foreach ($collection as $key => $value) { 
                $className = $value->sectionName;
                $classId = $value->id;
                $icon = $value->parentCourse->iconReference;
                if($value->isClassRegistrationAllow)
                    {
                    if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
                        {
                        if($value->locationType->name=="onlocation")
                            {
                              $register = '<a href="'.$value->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                            }
                           elseif($value->locationType->name=="online"){
                                 $register = '<a href="'.$value->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                            }
                           elseif($value->locationType->name=="onlocationandonline")
                            {
                              $register ='<span id="classlocationButton" style="display: flex;"><a href="'.$value->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1" target="_blank" style="margin-right:2px;">Register online</a><br/><a href="'.$value->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank">Register in person</a></span>';
                            }
                            else{
                                  $register = ' ';
                                }
                        }
                        else{
                             $register = '<a href="#" id="onlocation" class="btn btn-primary px-3 py-1" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'" disabled>Register</a> ';
                            }
                    } 
                
    foreach ($value->classSessions as $key => $result) {
                        $id = $result->id;
                        $classData[$id]['title'] = '<a href="'.site_url().'/class-details/?classId='.$classId.'">'.$className.'</a>';
                        $classData[$id]['titleNoLink'] = $className;
                        $classData[$id]['hours']      = $value->parentCourse->creditHours;
                        $classData[$id]['objectType'] = $value->objectType;
                        $classData[$id]['classDuration'] = $value->classDuration.' '.$value->classDurationType;
                        $classData[$id]['date'] = date('Y-m-d', strtotime($result->sessionDate));//$result->sessionDate;
                        $classData[$id]['classId'] .= $classId;
                        $classData[$id]['Icon'] .= $icon;
                        $classData[$id]['className'] .= $className;
                        $classData[$id]['sessionStatus'] .=$result->sessionStatus;
                        $classData[$id]['startTime'] .= $result->startTime;
                        $classData[$id]['endTime'] .= $result->endTime;
                        $classData[$id]['register'] = $register;
                        $classData[$id]['instructors'] = $value->classInstructors;
                      
   
    }
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
    <th scope="row"><span id="digitofday"><?php echo date('j', strtotime($classDate)).' '; ?> </span> <?php echo date("M Y", strtotime($classDate));?></th>
    <?php } ?>
    <td><ul class="calendarsearch">
<?php

    foreach($res as $keyobj => $data){
        $testing = $data['instructors'];
      // print_r(json_encode($data));
?>
    <a data-toggle="modal" data-target="#exampleModal2<?php echo $data['classId'];echo $i; ?>" href="" style="color:black !important; font-size:smaller;" >
     <li class="calendarsearch"><?php echo $data['startTime']; echo "  -  ".$data['endTime'];?></span>
    <span class="class-time-li" style="margin-left: 80px";><?php echo $data['titleNoLink'];?>  
    <ul>
    <?php 
    $inc = 1;
    foreach( $testing as $keyval => $instructor){
        //print_r($instructor->fullName);
         ?>
       <?php echo '<li>(Instructors : '.$instructor->fullName.')</li>'; ?>  
   <?php  }
?>    </ul>
</li></a>
    
    <div class="modal fade" id="exampleModal2<?php echo $data['classId'];echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                   <img src="<?php echo $data['Icon']; ?>" class="img-responsive img-icon-lg mr-2 mCS_img_loaded"><h5 class="modal-title" style ="color:blue;" id="exampleModalLabel2"><?php echo $data['title']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style ="color:black;">
                    <p><strong>Date :</strong> <?php echo date("j M Y", strtotime($data['date'])).' at '; echo $data['startTime']; echo "  -  ".$data['endTime'];?></p>
                    <p><strong>Duration : </strong><?php echo $data['classDuration']; ?></p>
                    <p><strong>Type :</strong> <?php echo $data['objectType']; ?></p>
                    <p><strong>Credit Hours : </strong><?php echo $data['hours']; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="../class-details/?classId=<?php echo $data['classId']; ?>" class="btn btn-secondary px-3 py-1">View Detail </a>
                    <?php echo $data['register']; ?>
                </div>
            </div>
        </div>
    </div> 

<?php $i++;}
} ?>
    <ul></td>
    </tr>
</table>



