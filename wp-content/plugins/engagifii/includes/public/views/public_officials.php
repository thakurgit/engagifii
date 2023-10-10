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
			if($key=='relatedOfficials'){
				return;	
			}
			$class=''; 
			if($i==1){
				$class =' active';
			}
    echo '<li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$key.'<b>('.count($value).')</b></button></li>';
 $i++; }
	  ?>   		
      </ul>
      <div class="tab-content" id="nav-tabContent">
      <?php $k=1; 
	  	foreach ($peopleDATA as $key => $value) {
			$class=''; 
			if($k==1){
				$class =' show active';
			}
			$list = $value;
			?>
      <div class="tab-pane fade <?php echo $class; ?>" id="tab-<?php echo $k; ?>" role="tabpanel" aria-labelledby="nav-home-tab">
      	<div class="row">
        	<?php foreach ($list as $key => $value) { ?>
        	<div class="col-md-6 col-lg-4 mb-3">
            	<div class="border bg-light rounded-2 p-3">
                	<div class="d-flex">
                    	<div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px">
                        		<img src="<?php echo $value->profilePic; ?>" alt="" class="img-fluid">
                        </div>
                        <div>
                	<?php echo $value->legalName; ?><br>
                    (<?php echo $value->officialNameLabel ; ?>)<br>
                    <?php echo $value->legislativeRole; ?><br>
                    <?php echo $value->districtCode; ?><br>
                    <?php echo $value->residence; ?><br>
                    <?php echo $value->party; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
      </div>
      <?php $k++; } ?>
      </div>
