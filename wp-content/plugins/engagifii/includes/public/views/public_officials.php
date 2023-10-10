<?php
 $peopleurl = 'https://engagifiwebstg.wpengine.com/gsba/wp-content/plugins/wp-front-end-profile/views/official.txt';
	$pJSON = file_get_contents($peopleurl);
	 $peopleDATA   = json_decode($pJSON);
	//print_r($peopleDATA);
	
?>
<style type="text/css">
  
 .session-tab button {
	border-bottom:3px solid transparent !important;
	color:#333 !important;
 }
  .session-tab button.active{
	border-bottom-color:#002474  !important;
 }
</style>
     <ul class="nav nav-pills mb-3 justify-content-center session-tab border-bottom" id="pills-tab" role="tablist">
      <?php $i=1; 
	  	foreach ($peopleDATA as $key => $value) {
			$class=''; 
			if($i==1){
				$class =' active';
			}
    echo '<li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$key.'('.count($value).')</button></li>';
 $i++; }
	  ?>   		
      </ul>
      <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade border bg-light rounded-2 p-4 show active" id="nav-header" role="tabpanel" aria-labelledby="nav-home-tab">
      
      </div>
      </div>
