<?php
$apiurl = 'https://builtin-crm.azurewebsites.net/api/v1/Advocacy/elected/officials-all-tabs-list/people';
 $peopleurl = 'https://engagifiwebstg.wpengine.com/gsba/wp-content/plugins/wp-front-end-profile/views/official_detail.txt';
	$pJSON = file_get_contents($peopleurl);
	 $peopleDATA   = json_decode($pJSON);
	//print_r($peopleDATA);
	echo 'abc';
	
?>
