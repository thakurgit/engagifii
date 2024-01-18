<?php
$site_url = site_url();
$user_id = get_current_user_id();
$user    = get_userdata($user_id);
$userEmail=$user->user_email;
$args    = array(
    'post_type' => 'get',
    'author'    => $user_id,
);
$tenant_code = "psba";
$tenant_url = "https://psba.engagifii.com";
$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE2MDk5MDcxMTEsImV4cCI6MTYwOTkxNDMxMSwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxIiwiYXV0aF90aW1lIjoxNjA5OTA3MTExLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiJodHRwczovL2VuZ2FnaWZpaWlkc3RvcmFnZS5ibG9iLmNvcmUud2luZG93cy5uZXQ6NDQzL3Byb2ZpbGVwaWNzL3Byb2ZpbGUtcGljODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxLnBuZyIsInBpY3R1cmUtc21hbGwiOiJodHRwczovL2VuZ2FnaWZpaWlkc3RvcmFnZS5ibG9iLmNvcmUud2luZG93cy5uZXQ6NDQzL3Byb2ZpbGVwaWNzLXNtL3Byb2ZpbGUtcGljODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxLnBuZyIsInBpY3R1cmUtaWNvbiI6Imh0dHBzOi8vZW5nYWdpZmlpaWRzdG9yYWdlLmJsb2IuY29yZS53aW5kb3dzLm5ldDo0NDMvcHJvZmlsZXBpY3MtaWNvbi9wcm9maWxlLXBpYzg4MDMwY2M5LWFjMTUtNDc5ZC1iYWNiLTZmMzEwMzQwZDZjMS5wbmciLCJnaXZlbl9uYW1lIjoiRW5nYWdpZmlpIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjEvNi8yMDIxIDQ6MjM6MjIgQU0iLCJjdXJyZW50LWxvZ2luIjoiMS82LzIwMjEgNDoyNToxMSBBTSIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.siQUIA6URga2cwvFDOXdRs1Y2l71KH65hijXt_X-wEgN6o5to-TowYneiYPfdq9zBUilpnoJPsx73m7JUwer7YPMdHOBZCEcNYcOUPpjcTEfut_Bflj_CYfQb-RcUIbsdzoWEDJB-hRg-g-V-1CEWOsFbnRWxbPOliZnnco-YW0GGFZErrXhwb4YixwtjBidyaffomtn1TXN8pjwq2kq3SrpyzCPTs8H5WqXj7sA3AmA9fFWBFZQsbgCxbg_bmeYGE4S9YWt2NUZjT39ld1WrxAVuzx5F1VX0iVYMe0YIMBNB075upMvue1Tj3K7k-1j0oQnl_3anZ2Ph5ysUbEXQQ';

// This is where you run the code and display the output
$curl = curl_init();
//$url = "https://engagifii-billtracking.azurewebsites.net/api/1/legislative/public-bills/column-list";
$url = "https://engagifii-preview9-crm.azurewebsites.net/api/v1/GetPersonDetailByEmail/".$userEmail."/".$tenant_code;
// Append any necessary query parameters to the URL
$queryParameters = array(
    // Add your query parameters here
);
$queryString = http_build_query($queryParameters);
if (!empty($queryString)) {
    $url .= '?' . $queryString;
}
curl_setopt_array($curl, array(  
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",   
    "tenant-code:".$tenant_code, 
    $authentication
  ),
));
$response = curl_exec($curl);
$peopleDATA = json_decode($response);
// Close the cURL session
curl_close($curl);

//       $err = curl_error($curl);
//       curl_close($curl);
//       if ($err) {
//         //Only show errors while testing
//         echo "cURL Error #:" . $err;
//       } else {
//       echo "hello";
//         //The API returns data in JSON format, so first convert that to an array of data objects
//         $responseObj = json_decode($response);
// print_r("response:", $responseObj);
// }
$current_user_posts = get_posts($args);
$total              = count($current_user_posts); ?>
<?php
if (! is_user_logged_in()) {
    echo "<div class='wpfep-login-alert'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</div>';

    return;
}
?>
 <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" >
  <link href="https://site-assets.fontawesome.com/releases/v6.2.1/css/all.css" rel="stylesheet" >-->
  <div class="d-flex justify-content-end">
    
    <a href="<?php echo esc_url(wp_logout_url('')); ?>"><?php esc_html_e('Logout', 'wpfep'); ?></a>
    
</div>
<?php 
	// $peopleurl = 'https://engagifiwebstg.wpengine.com/psba/wp-content/plugins/wp-front-end-profile/views/people.txt';
	//$pJSON = file_get_contents($peopleurl);
	// $peopleDATA   = json_decode($pJSON);
	//print_r($peopleDATA);
	$infoseq='';
	$infotabId = '';
	$groupseq = '';
	$groupId = '';
	foreach ($peopleDATA->tabs as $key => $value) {
     if($value->sequence==1){
		 $infoseq = $key;
		 $infotabId = $value->id;
     }
 }
 	foreach ($peopleDATA->tabs[$infoseq]->groupFields as $key => $value) {
     if($value->sequence==1){
		 $groupseq = $key;
		 $groupId = $value->id;
     }
 }
	

	$addresstabId = $peopleDATA->tabs[1]->id;
	$addresstabGroupId = $peopleDATA->tabs[1]->groupFields[0]->id;
	if($peopleDATA->people->personaTypeId==2){
		$addressTitle = $peopleDATA->tabs[1]->groupFields[0]->fields[4]->name;
		 $address = json_decode($peopleDATA->tabs[1]->groupFields[0]->fields[4]->selectedValue,true);
		$addresstabGroupFieldId = $peopleDATA->tabs[1]->groupFields[0]->fields[4]->id;
	}else if($peopleDATA->people->personaTypeId==4) {
		$addressTitle =$peopleDATA->tabs[1]->groupFields[0]->fields[2]->name;
		 $address = json_decode($peopleDATA->tabs[1]->groupFields[0]->fields[2]->selectedValue,true);
		$addresstabGroupFieldId = $peopleDATA->tabs[1]->groupFields[0]->fields[2]->id;
	}else if($peopleDATA->people->personaTypeId==3) {
		$addressTitle =$peopleDATA->tabs[1]->groupFields[0]->fields[1]->name;
		 $address = json_decode($peopleDATA->tabs[1]->groupFields[0]->fields[1]->selectedValue,true);
		$addresstabGroupFieldId = $peopleDATA->tabs[1]->groupFields[0]->fields[1]->id;
	}
if($peopleDATA->isError==true) { 
echo "<br><br><h5 class='text-center'>A person with this Email ID doesn't exist.</h5>";
 } else { ?>
<div class="temp3 "  style="background-color: #eee; ">

<div class="layout-content">

<!-- Content -->

  <!-- Header -->
  <div class="container-fluid bg-light border mb-4 py-5">
  <div class="row justify-content-center">
  	<div class="col-md-7">
    	<div class="d-flex">
        	<div class="flex-shrink-0 position-relative">
            	<?php if($peopleDATA->people->isStarredMember==true) { ?>
            	<span class="position-absolute <?php if($peopleDATA->people->isFavorite==true){ echo 'text-warning'; } ?>" style="left:-10px; top:-10px"><i class="fa fa-star"></i></span>	
                <?php } ?>
  	<div class="overflow-hidden rounded-circle " style="width:120px;height:130px">
    <img src="<?php echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid">
    </div>
    <a href="<?php echo $site_url ?>/engagifii-profile-edit" class="btn btn-outline-dark" data-mdb-ripple-color="dark"
                style="z-index: 1;">
                Edit profile
              </a>
  </div>
  <div class="flex-grow-1 ms-3">
  	<h4 class="font-weight-bold mb-4"><?php echo $peopleDATA->people->firstName.' '.$peopleDATA->people->middleName.' '.$peopleDATA->people->lastName; ?></h4>
    <?php if($peopleDATA->people->pid) { ?>
        <p class="font-weight-bold mb-4"><strong>PID: </strong><?php echo $peopleDATA->people->pid; ?></p>
    <?php } ?>
    
  
          <div class="text-muted mb-4">
          	<?php $status = 'Inactive';
					$statusColor = 'red';
			 if($peopleDATA->people->isActive==true) {
				$status = 'Active'; 
				$statusColor = 'green';
			 } ?>
            Status: <strong style="color:<?php echo $statusColor; ?>;"><?php echo $status; ?></strong>
          </div>
  			<?php if($peopleDATA->tags){ ?>
          <p class="d-flex align-items-start"><strong>Tags: </strong><span class="tags_all ms-3">
     				<?php 
						foreach ($peopleDATA->tags as $key => $value) {
							echo '<span class="badge rounded-pill text-bg-light border border-dark-subtle me-2 mb-2">'.$value->tagName.'</span>';
					 }		
					 ?>
                    </span>
          <?php } ?>
        
  </div>
        </div>
    </div>
  </div>
  </div>
  <!-- Header -->
<div class="container-fluid">
  <div class="row">
    <div class="col-8">

      <!-- Info -->
      <div class="card mb-4 px-3 mw-100">
        <div class="card-body">

        
		<?php if($address['country']) { ?>
          <div class="row mb-2">
            <div class="col-md-3 text-muted">Country:</div>
            <div class="col-md-9">
              <span class="text-body"><?php echo  $address['country'];?></span>
            </div>
          </div>
          <?php } ?>

          
			<?php if($peopleDATA->people->primaryPhoneNumber->value){ ?>
           <h6 class="my-3">Contacts</h6>
            <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==11){ ?>
              
              <div class="row mb-2">
            <div class="col-md-3 text-muted"><?php echo $value->name;?>:</div>
            <div class="col-md-9">
            <a href="tel:<?php echo $value->selectedValue;?>"><?php echo $value->selectedValue;?></a>
            </div>
          </div>
   <?php  } 
 }
?>
           
         

            <?php } ?>

          <h6 class="my-3">Price List</h6>

          <div class="row mb-2">
            <!-- <div class="col-md-3 text-muted">Favorite music:</div>
            <div class="col-md-9">
              <a href="javascript:void(0)" class="text-body">Rock</a>,
              <a href="javascript:void(0)" class="text-body">Alternative</a>,
              <a href="javascript:void(0)" class="text-body">Electro</a>,
              <a href="javascript:void(0)" class="text-body">Drum &amp; Bass</a>,
              <a href="javascript:void(0)" class="text-body">Dance</a>
            </div> -->
          </div>

          <div class="row mb-2">
            <div class="col-md-3 text-muted">Status:</div>
            <div class="col-md-9">
            Current
            </div>
          </div>

        </div>
        <!-- <div class="card-footer text-center p-0">
          <div class="row no-gutters row-bordered row-border-light">
            <a href="javascript:void(0)" class="d-flex col flex-column text-body py-3">
              <div class="font-weight-bold">24</div>
              <div class="text-muted small">posts</div>
            </a>
            <a href="javascript:void(0)" class="d-flex col flex-column text-body py-3">
              <div class="font-weight-bold">51</div>
              <div class="text-muted small">videos</div>
            </a>
            <a href="javascript:void(0)" class="d-flex col flex-column text-body py-3">
              <div class="font-weight-bold">215</div>
              <div class="text-muted small">photos</div>
            </a>
          </div>
        </div> -->
      </div>
      <!-- / Info -->

      
    </div>
    <div class="col-4">

      <!-- Side info -->
      <div class="card mb-4 px-2 mw-100">
        <div class="card-body">
          <a href="javascript:void(0)" class="btn btn-primary rounded-pill">+&nbsp; Contact</a>
          &nbsp;
          <a href="javascript:void(0)" class="btn icon-btn btn-default md-btn-flat rounded-pill">
            <span class="ion ion-md-mail"></span>
          </a>
        </div>
        <hr class="border-light m-0">
        <div class="card-body">
         <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 $address = json_decode($value->selectedValue,true);
	 ?>
     <p class="mb-2">
 <Strong><?php echo $value->name;?>: </strong> <i class="ion ion-md-desktop ui-w-30 text-center text-lighter"></i> <?php  echo $address['locationName'].'<br>'.  $address['address'].', '.$address['addressLine2'].'<br>'.$address['city'].', '.$address['state'].' '.$address['zipCode'].'<br>'.$address['country'];?><br>          
</p>
     <?php  } 
 }
?>
          
         	<?php if($peopleDATA->people->primaryEmail->value){ ?>
          <p class="mb-0"> <strong><?php echo $peopleDATA->people->primaryEmail->type; ?>: </strong>
            <i class="ion ion-md-globe ui-w-30 text-center text-lighter"></i>
            <a href="mailto:<?php echo $peopleDATA->people->primaryEmail->value; ?>" class="text-body"><?php echo $peopleDATA->people->primaryEmail->value; ?></a>
          </p>
            <?php } ?>
        </div>
        <hr class="border-light m-0">
        
      </div>
      <!-- / Side info -->

      <!-- Skills -->
      <div class="card mb-4 px-2 mw-100">
        <div class="card-header">Organization</div>
        <div class="card-body">

          <div class="mb-1"> <p class="mb-4"><span class="text-primary font-italic me-1"><img src="https://psba.engagifii.com/assets/images/org-list-grey.png" alt="avatar" class="rounded-circle img-fluid me-2" style="width: 30px;">Cumberland Valley S D</span> 
                </p>
          

          <div class="mb-1">Department - <small class="text-muted">District Administration</small></div>
          

          <div class="mb-1">Position - <small class="text-muted">Superintendent</small></div>
         

          <!-- <div class="mb-1">Board Service - <small class="text-muted">Board Service Term 1- 12/01/2019 - 11/30/2023</small></div> -->
         

        </div>
        <!-- <a href="javascript:void(0)" class="card-footer d-block text-center text-body small font-weight-semibold">SHOW ALL SKILLS</a> -->
      </div>
      <!-- / Skills -->

      
    </div>
  </div>

</div>
<!-- / Content -->



</div>
</div>
</div>
<?php } ?>