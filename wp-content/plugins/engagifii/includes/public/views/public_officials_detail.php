<?php
$apiurl = 'https://builtin-crm.azurewebsites.net/api/v1/Advocacy/elected/officials-all-tabs-list/people';
 $peopleurl = 'https://engagifiwebstg.wpengine.com/gsba/wp-content/plugins/wp-front-end-profile/views/official_detail.txt';
	$pJSON = file_get_contents($peopleurl);
	 $response   = json_decode($pJSON);
	print_r($response);
	
	
?>
<div class="container-fluid bg-light border p-4">
	<div class="d-flex">
    	<div class="rounded-circle overflow-hidden mr-4" style="width:100px; height:100px">
        	<img src="<?php echo $response->profilePic; ?>" alt="" class="img-fluid">
        </div>
        <div>
        	<h4><?php echo $response->firstName.' '.$response->middleName.' '.$response->lastName; ?> (<?php echo $response->officialNameLabel; ?>)</h4>
        </div>
    </div>
</div>
