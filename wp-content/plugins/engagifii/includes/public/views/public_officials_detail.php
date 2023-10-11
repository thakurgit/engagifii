<?php
$apiurl = 'https://builtin-crm.azurewebsites.net/api/v1/Advocacy/elected/officials-all-tabs-list/people';
 $peopleurl = 'https://engagifiwebstg.wpengine.com/gsba/wp-content/plugins/wp-front-end-profile/views/official_detail.txt';
	$pJSON = file_get_contents($peopleurl);
	 $response   = json_decode($pJSON);
	//print_r($response);
	
	
?>
<div class="container-fluid bg-light border p-4">
	<div class="d-flex">
    	<div class="rounded-circle overflow-hidden mr-4" style="width:100px; height:100px">
        	<img src="<?php echo $response->profilePic; ?>" alt="" class="img-fluid">
        </div>
        <div>
        	<h4><?php echo $response->firstName.' '.$response->middleName.' '.$response->lastName; ?> (<?php echo $response->officialNameLabel; ?>)</h4>
            <div class="row">
            	<div class="col-3"><strong>District: </strong><?php echo $response->districtCode; ?></div>
            	<div class="col-3"><strong>City of Residence: </strong><?php echo $response->residence; ?></div>
            	<div class="col-3"><strong>Role: </strong><?php echo $response->role; ?></div>
            	<div class="col-3"><strong>Party: </strong><?php echo $response->party; ?></div>
            	<div class="col-3"><strong>County: </strong><?php echo $response->counties[0]->countyName; 
					if(count($response->counties) > 1){
						echo ' <div class="dropdown dropright"><a type="button" data-toggle="dropdown">'.count($response->counties) .' more</a><div class="dropdown-menu py-0"><ul class="list-group list-group-flush">';	
						foreach ($response->counties as $key => $value) {
							echo '<li class="list-group-item px-2 py-1">'.$value->countyName.'</li>';	
						}
						echo '</ul></div></div>';
					}
				 ?>
                </div>
            </div>
        </div>
    </div>
</div>
