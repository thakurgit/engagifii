<?php
    $options  = get_option( 'ebt_api_settings' );
    $class_visible_column_list = $options['class_visible_column_list'];
  $classStates =['Upcoming'];
  if($options['allClasses']==1) { 
  	$classStates = [];
   }
    $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[1]);
    unset($collection[7]);
    unset($collection[8]);
//Datatable javascript
function _prepareClassData1($classStates){  
    $postData = array();  
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = '';    
    $postData['pageNumber'] = 1;    
    $postData['sortDirection'] = "desc";
    $postData['filterBody'] = array('searchText'=>'','selectedDate' => date('Y-m-d'),'classStates'=>$classStates); //'searchText'=>$title,  
    return $postData;
}
	$zz =_prepareClassData1($classStates);
     $xxx = $this->submitApiRequest("Public/ClassPagingList",  $zz, "POST", 'classes');
	 //$url = ENGAGIFII_ASSETS_URL.'/classdata.txt';
	//$JSON = file_get_contents($url);
	// $xx   = json_decode($JSON)->result;
	 $xx   = json_decode($xxx['api_response'])->result;
	// print_r($data);
	// die;
?>
<div class="container-fluid">
<table  id="ex" class="table table-bordered border-0 table-striped" style="width: 100% !important;">
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
      <tbody>
      
      	<?php $k=1;
		 $counter = 0; 
		 $class_schedule = '';
		 
			 foreach ($xx as $key => $value) {
            $instructorPopOver = '';
            $classPopover      = '';
				  ?>
			
            <tr>
            	<td><?php 
				$class_icon = $value->parentCourse->iconReference;
            if($siteURL == "https://engagifiwebstg.wpengine.com/oresa" || $siteURL == "https://engagifiiweb.com/oresa" || $siteURL == "https://oconeeresa.org"){
                $class_icon = ENGAGIFII_ASSETS_URL.'/images/oconee-logo.png';
                
            }
			if(count($value->classSessions)){
                $classPopover  =  dd_header('Class Dates');
				$subItems = "";
				$li=1;
				foreach ($value->classSessions as $key => $rowData) {
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
				$classPopover .= $subItems;
				$classPopover.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
			}
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
                    //$classSessionTime = date('M d, Y', strtotime($rowData->sessionDate)).' At '.$classSessionStartTime.' - '.$classSessionEndTime;
                    $classSessionTime = date('M d, Y', strtotime($classSessionStartDate)).' - '.date('M d, Y', strtotime($classSessionEndDate));
                    $class_schedule = '<small class="d-block" style="white-space:normal;">'.$classSessionTime.' <br>'.$classSessionStartTime.'-'.$classSessionEndTime.'</small>';
                    $counter = $counter + 1;
                }
               echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'</div></div>';
            }else{
            echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><div class="d-flex align-items-center"><img alt="'.$value->sectionName.'" src="'.$class_icon.'" class="img-fluid img-icon-lg p-0 mr-3 rounded-circle"><div><span class="d-block"><a href="'.site_url().'/class-details/?classId='.$value->id.'">'.$value->sectionName.'</a></span>'.$class_schedule.'<small class="d-block" style="white-space:normal;">'.date('M d, Y', strtotime($value->startDate)).' at '.date('h:i A', strtotime($value->startDate)).' - '.date('h:i A', strtotime($value->endDate)).' </small></div></div>';
            }
				 ?></td>
                <td><?php echo $value->classDuration.' '.$value->classDurationType; ?></td>
                <td><?php echo $value->objectType; ?></td>
                <td><?php 
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
            	 echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->classSessions).'</span></div>'.$classPopover.'</div>';
			}else {
				echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($value->startDate))).'</span><img src="'.ENGAGIFII_ASSETS_URL.'/images/class.png" class="img-icon-lg img-fluid" alt="class-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Dates Available" >';	
			}

				 ?></td>
                 <td>
                 	<?php
					if($value->classInstructorsCount>0){
				echo '<div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover instructor_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.($value->classInstructorsCount).'</span></div>'.$instructorPopOver.'</div>';  
			} else {
				echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/instructor.png" class="img-icon-lg img-fluid" alt="instructor-icon" style="filter:grayscale(1)" data-toggle="tooltip" data-placement="top" title="No Instructors Available" >';
			}
					?>
                 </td>
                 <td>
                 	<?php
					if($value->isCreditTypeSingle =="true"){
            echo number_format($value->courseCreditMapping[0]->credits, 2);//($value->courseCreditMapping[0]->credits);          
            }else{
                echo number_format($value->courseCreditMapping[0]->credits, 2);      
            }
					 ?>
                 </td>
                 <td>
                 	<?php
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

            echo implode(" ", $allTags);
					?>
                 </td>
                 <td>
                 	<?php
					if($value->isClassRegistrationAllow || $value->registrationWorkFlowId)
            {
              if($value->registrationState !== 'Registration Not Setup' && $value->registrationState !== 'Registration Closed' && $value->registrationState!== 'Sold Out' && $value->registrationState !== 'Registration Scheduled' && $value->registrationState !== 'Early Sold Out' && $value->registrationState !== 'Standard Sold Out')
              {
                   if($value->locationType->name=="onlocation")
                      { 
                      echo '<a href="'.$value->registrationUrlOnLocation.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      //$nestedData['register'] = '<a href="'.$tenant_url.'/pages/classes/'. $value->id .'/signup/onlocation/overview" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      }
                      elseif($value->locationType->name=="online"){
                          echo '<a href="'.$value->registrationUrlOnLine.'" class="btn btn-primary px-3 py-1" target="_blank">Register</a>';
                      }
                      elseif($value->locationType->name=="onlocationandonline"){
                      echo '<a style="white-space:nowrap" href="'.$value->registrationUrlOnLine.'" id="onlineclass" class="btn btn-primary px-3 py-1 mb-2" target="_blank" >Register Online</a><br/><a style="white-space:nowrap" href="'.$value->registrationUrlOnLocation.'" id="onlocation" class="btn btn-primary px-3 py-1" target="_blank" >Register in person</a>';
                      }
                  else{
                     echo '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Class Location not defined"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
                  }
              }
              else{
              echo '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
              }
          }else{
            echo '<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="'.$value->registrationState.'"><button type="button" id="onlocation" class="btn btn-primary  px-3 py-1"  disabled style="pointer-events: none;">Register</button></span>';
		  }
					?>
                 </td>
            </tr>
			<?php $k++;
		}
		?>
      </tbody>
    </table>	
    
</div>
<script>
var table;
$(document).ready(function () {
     table = $('#ex').DataTable({
        "pageLength": 10,
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
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
        },        
		 
         "drawCallback": function( settings ) {
			 
			 dt_dropdown();
			 <?php if($dt_respnsive==''){ ?>
           dt_scroll();
			   <?php } ?>
			   $('[data-toggle="tooltip"]').tooltip() ;
         },
		 
		  "initComplete": function(settings, json) {
		//

    }
		
		
		
    });
});

<?php
  if($title_key > -1){
?>

  $('#ex thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {

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
        $(this).html( '<div class="position-relative"><label class="d-none" for="searchclass">search</label><input type="text" id="searchclass" placeholder="Search classes" class="form-control form-control-sm search-endorsement pr-4" value=""/> <button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:0; top:0px; display:none"><i class="far fa-times"></i></button></div>' );

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
    $('#searchclass').on('click', function(e){
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
</script>
