<?php 
$obj      =  new Engagifii_API();
$collection   = array();
  $forDatatable   =   array();
  $date           =   date('Y-m-d');
  $options  = get_option( 'ebt_api_settings' );
  $class_visible_column_list = $options['class_visible_column_list'];

  //print_r($class_visible_column_list);
  $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
  //print_r($dataResponse);

  $collection   = json_decode($dataResponse['api_response']);
  unset($collection[0]);
  unset($collection[1]);
  unset($collection[7]);
  unset($collection[8]);
?>
<table  id="ebtmaintable" class="table table-bordered table-striped main-list-here classes-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
      <thead> 
        <tr>    
<?php
  if(is_array($collection) && count($collection)>0){
    $i = 0;
    foreach ($collection as $key => $value) {
       if(in_array($value->colName, $class_visible_column_list)){
      
        if($value->displayName == 'Class Type')
        {            $value->displayName = "Type";
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

function _prepareClassData($searchtext){    
    $title = $searchtext;
    $postData = array();  
    $sortBy       = "";
    $postData['itemCount'] = 10000;
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


<?php
    $classData = array();

foreach ($collection as $key => $value) { 
    ?>
    <tr>
        <td>
        
        </td>
   </tr> 

     <?php          
}

 ?>
   
</table>
<div class="d-none no-results"><h4 class="text-center text-secondary">Oops! No data found!! Try some other keyword</h4></div>



