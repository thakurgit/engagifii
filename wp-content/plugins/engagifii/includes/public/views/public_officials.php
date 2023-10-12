<?php
$apiurl = 'https://builtin-crm.azurewebsites.net/api/v1/Advocacy/elected/officials-all-tabs-list/people';
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
 mark, .mark {
	background-color: #F9D276;
}
</style>
<div class="container-fluid mb-4">
<div class="row">
	<div class="col-md-4">
      <input type="text" placeholder="Search Public Official..." class="form-control search-official">
    </div>
</div>
</div>
     <ul class="nav nav-pills mb-3 justify-content-center session-tab border-bottom" id="pills-tab" role="tablist">
      <?php $i=1; 
	  	foreach ($peopleDATA as $key => $value) {
			if($key=='relatedOfficials' || $key=='myOfficials' || $key=='stateBoardOfEducationMemberList' || $key=='stateWideElectedMemberList'){
				continue;	
			}
			if($key=='stateSenateMemberList'){
				$name='State Senate';
			}
			elseif($key=='stateHouseMemberList'){
				$name='State House';
			}
			elseif($key=='stateSenateCommittees'){
				$name='State Senate Committees';
			}
			elseif($key=='stateHouseCommittees'){
				$name='State House Committees';
			}
			elseif($key=='countyDeligationList'){
				$name='County Delegations';
			}
			elseif($key=='congressionalDelegationMemberList'){
				$name='Congressional Delegations';
			}else{
				$name=$Key;
			}
			$class=''; 
			if($i==1){
				$class =' active';
			}
    echo '<li class="nav-item mr-4" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0 px-0'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$name.' <b>('.count($value).')</b></button></li>';
 $i++; }
	  ?>   		
      </ul>
      <div class="tab-content" id="nav-tabContent">
      <?php $k=1; 
	  	foreach ($peopleDATA as $key => $value) {
			if($key=='relatedOfficials' || $key=='myOfficials' || $key=='stateBoardOfEducationMemberList' || $key=='stateWideElectedMemberList'){
				continue;	
			}
			$class=''; 
			if($k==1){
				$class =' show active';
			}
			$list = $value;
			?>
      <div class="tab-pane fade <?php echo $class; ?>" id="tab-<?php echo $k; ?>" role="tabpanel" aria-labelledby="nav-home-tab">
      	<div class="row">
        	<?php if($key=='stateSenateCommittees' || $key=='stateHouseCommittees' || $key=='countyDeligationList'){ 
				echo '<div class="accordion col-12" id="accordionExample">';
				foreach ($list as $key => $value) { ?>
					  <div class="card">
                        <div class="card-header px-0" id="heading<?php echo $value->id; ?>">
                          <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left py-0" type="button" data-toggle="collapse" data-target="#collapse<?php echo $value->id; ?>" aria-expanded="true" aria-controls="collapseOne">
                              <i class="fal fa-plus mr-3"></i><?php echo $value->name; ?>
                            </button>
                          </h2>
                        </div>
                        <div id="collapse<?php echo $value->id; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                          <div class="card-body">
                            <div class="row">
                            	<?php //if($key=='countyDeligationList'){
									//print_r($value->countyOfficals);
									 foreach ($value->countyOfficals as $key => $values) { ?>
                                 	 <div class="col-md-6 col-lg-4 mb-3 ">
                                  <div class="border bg-light rounded-2 p-3">
                                      <div class="d-flex">
                                          <div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px">
                                                  <img src="<?php echo $values->profilePic; ?>" alt="" class="img-fluid">
                                          </div>
                                          <div>
                                      <?php echo $values->legalName; ?><br>
                                      (<?php echo $values->officialNameLabel ; ?>)<br>
                                      <?php echo $values->legislativeRole; ?><br>
                                      <?php echo $values->districtCode; ?><br>
                                      <?php echo $values->residence; ?><br>
                                      <?php echo $values->party; ?>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                                	<?php }
								//}
								//if($key=='stateSenateCommittees' || $key=='stateHouseCommittees') {
									foreach ($value->committeeOfficals as $key => $values) { ?>
                                       <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border bg-light rounded-2 p-3">
                                        <div class="d-flex">
                                            <div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px">
                                                    <img src="<?php echo $values->profilePic; ?>" alt="" class="img-fluid">
                                            </div>
                                            <div>
                                        <?php echo $values->legalName; ?><br>
                                        (<?php echo $values->officialNameLabel ; ?>)<br>
                                        <?php echo $values->legislativeRole; ?><br>
                                        <?php echo $values->districtCode; ?><br>
                                        <?php echo $values->residence; ?><br>
                                        <?php echo $values->party; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
									<?php }
								//}?>	
                            </div>
                          </div>
                        </div>
                      </div>
				<?php }
				echo '</div>';
			 } else {
        		foreach ($list as $key => $value) { ?>
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
           	 <?php }
			 } ?>
        </div>
      </div>
      <?php $k++; } ?>
      </div>
      <script>
	  
	function replaceText() {


    var searchword = jQuery(".search-official").val();

    var custfilter = new RegExp(searchword, "ig");
    var repstr = "<span class='mark px-0'>" + searchword + "</span>";

    if (searchword != "") {
        jQuery('.tab-pane .col-md-6 div').each(function() {
            jQuery(this).html(jQuery(this).html().replace(custfilter, repstr));
        })
    }
}

jQuery(".search-official").on("keyup", function() {
    var value = jQuery(this).val().toLowerCase();
         var val = value.trim();
         val = val.replace(/\s+/g, '');
		 
	if(val.length > 3) { //for checking 3 characters
   	 jQuery(".tab-pane .col-md-6 div").filter(function() {
		if(jQuery(this).text().toLowerCase().indexOf(value) > -1) {
			jQuery(this).parents('.col-md-6').removeClass('d-none');	
		} else {
			jQuery(this).parents('.col-md-6').addClass('d-none');	
		}
    });
replaceText();
} else{
	 jQuery(".tab-pane .col-md-6").removeClass('d-none');
	 jQuery(".mark").each(function() {
    jQuery(this).replaceWith(this.childNodes);
  });	
}

		 
		

  });
  

	  </script>
