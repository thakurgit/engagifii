<?php
    $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
    

    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[1]);
    unset($collection[7]);
    unset($collection[8]);


?>
<?php 	function _prepareClassData1(){   

            $upcomingClasses = [];
        
    $title = '';
    $postData = array();  
    $sortBy       = "";
    $postData['itemCount'] = 1000;
    $postData['sortBy'] = $sortBy;    
    $postData['pageNumber'] = 1;    
    $postData['sortDirection'] = "desc";
    $postData['filterBody'] = array('searchText'=>$title,'selectedDate' => date('Y-m-d'),'classStates'=>$upcomingClasses); //'searchText'=>$title,  
    return $postData;
}
	$zz =_prepareClassData1();
     $xxx = $this->submitApiRequest("Public/ClassPagingList",  $zz, "POST", 'classes');
	 $xx   = json_decode($xxx['api_response'])->result;
	 print_r(count($xx));
	 //die;
?>
<div class="container">
<table  id="ex" class="table table-bordered border-0 table-striped" style="width: 100% !important;">
      <thead> 
        <tr>        
          <?php
            if(is_array($collection) && count($collection)>0){
              foreach ($collection as $key => $value) {
                ?>
                  <th class="<?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
                <?php
              }
            }
          ?>    

        </tr> 
      </thead> 
      <tbody>
      
      	<?php $k=1; $counter = 0; $class_schedule = ''; foreach ($xx as $key => $value) { ?>
			
            <tr>
            	<td><?php echo $value->sectionName; ?></td>
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
            	 echo '<span style="display:none;">'.strtotime(date('M d, Y', strtotime($classSessionStartDate))).'</span><div class="dropdown"><div data-offset="60,0" data-toggle="dropdown" class="instructor-popover class_'.$key.' " data-placement="left" data-containerid="' . $key . '" id="' . $key . '"><img src="/images/class.png" class="img-icon-lg img-fluid" alt="class-icon"><span class="bg-dark badge-count d-inline-block rounded-circle position-relative text-white d-inline-flex align-items-center justify-content-center">'.count($value->classSessions).'</span></div></div>';
			}

				 ?></td>
            </tr>
			<?php $k++;
		}
		?>
      </tbody>
    </table>	
</div>
