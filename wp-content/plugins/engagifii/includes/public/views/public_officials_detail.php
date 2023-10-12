<?php
$detailUrl = site_url();
$parts = parse_url($detailUrl);
parse_str($parts['query'], $query);
print_r($parts);
$peopleId = $query['id'];
$tenant_code = "gsba";
$peopleId='1414';
$url = 'https://engagifii-preview6-billtracking.azurewebsites.net/api/1/legislative/public-bills/official-detail/'.$peopleId;
$curl = curl_init();
// Append any necessary query parameters to the URL
$queryParameters = array(
    // Add your query parameters here
);
$queryString = http_build_query($queryParameters);
if (!empty($queryString)) {
    $url .= '?' . $queryString;
}
//$payload='{}';
curl_setopt_array($curl, array(  
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
 // CURLOPT_POST => true,  // Set request type to POST
 // CURLOPT_POSTFIELDS => $payload,  // Set the payload data
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",   
    "tenant-code:".$tenant_code, 
   
  ),
));
$response = curl_exec($curl);
$response = json_decode($response);
// Close the cURL session
curl_close($curl);
// $peopleurl = 'https://engagifiwebstg.wpengine.com/gsba/wp-content/plugins/wp-front-end-profile/views/official_detail.txt';
	//$pJSON = file_get_contents($peopleurl);
	// $response   = json_decode($pJSON);
	print_r($response);
	
	
?>
<div class="container-fluid  mb-4">
	<div class="bg-light border p-4">
	<div class="d-flex">
    	<div class="rounded-circle overflow-hidden mr-4" style="max-width:100px; flex:0 0 100px; height:100px">
        	<img src="<?php echo $response->profilePic; ?>" alt="" class="img-fluid">
        </div>
        <div>
        	<h4><?php echo $response->firstName.' '.$response->middleName.' '.$response->lastName; ?> (<?php echo $response->officialNameLabel; ?>)</h4>
            <div class="row">
            	<div class="col-auto pr-lg-5"><strong>District: </strong><?php echo $response->districtCode; ?></div>
            	<div class="col-auto pr-lg-5"><strong>City of Residence: </strong><?php echo $response->residence; ?></div>
            	<div class="col-auto pr-lg-5"><strong>Role: </strong><?php echo $response->role; ?></div>
            	<div class="col-auto pr-lg-5"><strong>Party: </strong><?php echo $response->party; ?></div>
            	<div class="col-auto pr-lg-5"><strong>County: </strong><?php echo $response->counties[0]->countyName; 
					if(count($response->counties) > 1){
						echo ' <span class="dropdown dropright"><a type="button" data-toggle="dropdown">+'.(count($response->counties)-1) .' more</a><div class="dropdown-menu py-0"><ul class="list-group list-group-flush">';	
						foreach ($response->counties as $key => $value) {
							echo '<li class="list-group-item px-2 py-1">'.$value->countyName.'</li>';	
						}
						echo '</ul></div></span>';
					}
				 ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<div class="container-fluid">
	<div class="border">
	<h5 class="bg-light py-2 px-3 border-bottom">Biography</h5>
    <div class="p-3">
    	<?php echo $response->bio; ?>
    </div>
    <h5 class="bg-light py-2 px-3 border-bottom border-top">Counties</h5>
    <div class="p-3">
    	<ul class="list-group list-group-flush">	
			<?php foreach ($response->counties as $key => $value) {
                echo '<li class="list-group-item py-2 px-0">'.$value->countyName.'</li>';	
            } ?>
		</ul>
    </div>
    <h5 class="bg-light py-2 px-3 border-bottom border-top">Address</h5>
    <div class="p-3">
			<?php foreach ($response->address as $key => $value) {
                echo '<h6 class="mb-1">'.$value->addressType.'</h6><p>'.$value->addressLine1.' '.$value->addressLine2.'</p><hr>';	
            } ?>
    </div>
    <h5 class="bg-light py-2 px-3 border-bottom border-top">Contact Details</h5>
    <div class="p-3">
    	<div class="row">
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">Email</h6>
                <a href="mailto:<?php echo $response->email; ?>"><?php echo $response->email; ?></a>
            </div>
            <?php foreach ($response->phones as $key => $value) {
                echo '<div class="col-md-3 mb-3"><h6 class="mb-1">'.$value->type.'</h6>'.$value->number.'</div>';	
            } ?>
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">Facebook</h6>
                <a href="<?php echo $response->facebookUrl; ?>"><?php echo $response->facebookUrl; ?></a>
            </div>
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">Twitter</h6>
                <a href="<?php echo $response->twitterUrl; ?>"><?php echo $response->twitterUrl; ?></a>
            </div>
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">LinkedIn</h6>
                <a href="<?php echo $response->linkedInUrl; ?>"><?php echo $response->linkedInUrl; ?></a>
            </div>
        </div>
    </div>
    </div>
</div>
