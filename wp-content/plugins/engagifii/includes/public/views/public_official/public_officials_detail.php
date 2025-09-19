<?php
// Check if legislation module is enabled (public officials is part of legislation)
$enabled_modules = get_option('engagifii_enabled_modules', array());
if (!in_array('legislation', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}

if (isset($_GET['id'])) {
    $paramValue = $_GET['id'];
   // echo "Value of 'param' parameter: " . $paramValue;
}  else{
echo'<h5 class="text-center pt-5">Public official ID not available</h5>';
return;	
}
$options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
    $public_official_page = $front_pages['public_official_page'];
	if($public_official_page){
		$public_official_page_link=get_permalink( $public_official_page );	
	}else{
		$public_official_page_link= site_url() .'/public-officials/';	
	}
$tenant_code          = $options['lbt_tenant_code']['tenant_code'];
$url = $options['lbt_api_url'].'/legislative/public-bills/official-detail/'.$paramValue;
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
	//print_r($response);
	
	
?>
<style>
.po-header {
background:#082971; 
border-radius:10px	
}
.po-header .profile-pic{
max-width:100px; 
flex:0 0 100px; 
height:100px; 
border:3px solid white	
}
.pp-detail > div > span {
	background: rgba(255,255,255,0.2);
	padding: 2px 10px;
	display: inline-block;
	border-radius: 5px;
}
</style>
<div class="container-fluid mb-3">
<a href="<?php echo $public_official_page_link;?>" class="border border-dark btn" style="border-radius:10px"><i class="fal fa-arrow-left mr-2"></i>Go Back</a>
</div>
<div class="container-fluid  mb-4">
	<div class=" p-3 text-white po-header" >
	<div class="d-flex align-items-center">
    	<div class="rounded-circle overflow-hidden mr-4 profile-pic" style="">
        	<img src="<?php echo $response->profilePic; ?>" alt="" class="img-fluid">
        </div>
        <div>
        	<h4 class="mb-1"><?php echo $response->firstName.' '.$response->middleName.' '.$response->lastName; ?> (<?php echo $response->officialNameLabel; ?>)</h4>
            <div class="row pp-detail">
            	<div class="col-auto pr-0 mb-2"><span><strong>District: </strong><?php echo $response->districtCode; ?></span></div>
            	<div class="col-auto pr-0 mb-2"><span><strong>City of Residence: </strong><?php echo $response->residence; ?></span></div>
            	<div class="col-auto pr-0 mb-2"><span><strong>Role: </strong><?php echo $response->role; ?></span></div>
            	<div class="col-auto pr-0 mb-2"><span><strong>Party: </strong><?php echo $response->party; ?></span></div>
            	<div class="col-auto pr-0 "><span><strong>County: </strong><?php echo $response->counties[0]->countyName; 
					if(count($response->counties) > 1){
						echo ' <span class="dropdown dropright small"><a type="button" data-toggle="dropdown">+'.(count($response->counties)-1) .' more</a><div class="dropdown-menu py-0"><ul class="list-group list-group-flush">';	
						foreach ($response->counties as $key => $value) {
							echo '<li class="list-group-item px-2 py-1">'.$value->countyName.'</li>';	
						}
						echo '</ul></div></span>';
					}
				 ?>
                </span></div>
            </div>
        </div>
    </div>
    </div>
</div> 
<div class="container-fluid">
	<div class="border overflow-hidden mb-4" style="border-radius:10px">
	<h5 class="bg-light py-2 px-3 border-bottom">Biography</h5>
    <div class="p-3">
    	<?php echo $response->bio; ?>
    </div>
    </div>
	<div class="border overflow-hidden mb-4" style="border-radius:10px">
    <h5 class="bg-light py-2 px-3 border-bottom border-top">Counties</h5>
    <div class="p-3">
    	<ul class="list-group list-group-flush">	
			<?php foreach ($response->counties as $key => $value) {
                echo '<li class="list-group-item py-2 px-0">'.$value->countyName.'</li>';	
            } ?>
		</ul>
    </div>
    </div>
	<div class="border overflow-hidden mb-4" style="border-radius:10px">
    <h5 class="bg-light py-2 px-3 border-bottom border-top">Address</h5>
    <div class="p-3">
			<?php foreach ($response->address as $key => $value) {
                echo '<h6 class="mb-1">'.$value->addressType.'</h6><p>'.$value->addressLine1.' '.$value->addressLine2.'</p><hr>';	
            } ?>
    </div>
    </div>
	<div class="border overflow-hidden mb-4" style="border-radius:10px">
    <h5 class="bg-light py-2 px-3 border-bottom border-top">Contact Details</h5>
    <div class="p-3">
    	<div class="row">
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">Email</h6>
                <a style="word-wrap:break-word" href="mailto:<?php echo $response->email; ?>"><?php echo $response->email; ?></a>
            </div>
            <?php foreach ($response->phones as $key => $value) {
                echo '<div class="col-md-3 mb-3"><h6 class="mb-1">'.$value->type.'</h6>'.$value->number.'</div>';	
            } ?>
            <?php if($response->facebookUrl){ ?>
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">Facebook</h6>
                <a  style="word-wrap:break-word" href="<?php echo $response->facebookUrl; ?>"><?php echo $response->facebookUrl; ?></a>
            </div>
            <?php } if($response->twitterUrl){ ?>
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">Twitter</h6>
                <a style="word-wrap:break-word" href="<?php echo $response->twitterUrl; ?>"><?php echo $response->twitterUrl; ?></a>
            </div>
            <?php } if($response->linkedInUrl){ ?>
        	<div class="col-md-3 mb-3">
            	<h6 class="mb-1">LinkedIn</h6>
                <a style="word-wrap:break-word" href="<?php echo $response->linkedInUrl; ?>"><?php echo $response->linkedInUrl; ?></a>
            </div>
            <?php } ?>
        </div>
    </div>
    </div>
</div>
