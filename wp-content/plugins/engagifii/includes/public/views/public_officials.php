<?php
 $peopleurl = 'https://engagifiwebstg.wpengine.com/psba/wp-content/plugins/wp-front-end-profile/views/official.txt';
	$pJSON = file_get_contents($peopleurl);
	 $peopleDATA   = json_decode($pJSON);
	print_r($response);
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
         				
                 <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0 active" id="" data-toggle="pill" data-target="#tab-1" type="button" role="tab" aria-controls="home" aria-selected="true">State Senate(12)</button>
  </li>
  <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0" id="" data-toggle="pill" data-target="#tab-2" type="button" role="tab" aria-controls="home" aria-selected="true">State House(7)</button>
  </li>
  <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0" id="" data-toggle="pill" data-target="#tab-2" type="button" role="tab" aria-controls="home" aria-selected="true">State Senate Committees(5)</button>
  </li>
  <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0" id="" data-toggle="pill" data-target="#tab-2" type="button" role="tab" aria-controls="home" aria-selected="true">State House Committees(6)</button>
  </li>
   <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0" id="" data-toggle="pill" data-target="#tab-2" type="button" role="tab" aria-controls="home" aria-selected="true">County Delegations(4)</button>
  </li>
  <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0" id="" data-toggle="pill" data-target="#tab-2" type="button" role="tab" aria-controls="home" aria-selected="true">Congressional Delegations(11)</button>
  </li>
       
     
      </ul>
      <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade border bg-light rounded-2 p-4 show active" id="nav-header" role="tabpanel" aria-labelledby="nav-home-tab">
      
      </div>
      </div>
