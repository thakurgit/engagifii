<?php
    $obj      =  new Engagifii_API();
$site_url = site_url();
$options = get_option('ebt_api_settings');
$user_id = get_current_user_id();
$user    = get_userdata($user_id);
$userEmail=$user->user_email;
$args    = array(
    'post_type' => 'get',
    'author'    => $user_id,
);
//$tenant_code = $options['evt_tenant_code']['engagifii_url'];
$tenant_code = 'psba';
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
  <div class="d-flex justify-content-end px-3 mb-3">
    
    <a class="btn-secondary btn-sm btn-xs" href="<?php echo esc_url(wp_logout_url('')); ?>"><?php esc_html_e('Logout', 'wpfep'); ?></a>
    
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
	

	/*$addresstabId = $peopleDATA->tabs[1]->id;
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
	}*/
if($peopleDATA->isError==true) { 
echo "<br><br><h5 class='text-center'>A person with this Email ID doesn't exist.</h5>";
 } else { ?>
  <!-- Header -->
  
  <div class="container-fluid  mb-4 ">
  <div class="bg-light border py-5 px-4 rounded-3">
  <div class="row justify-content-center">
  	<div class="col-md-7">
    	<div class="d-flex">
        	<div class="flex-shrink-0 position-relative text-center">
            	<?php if($peopleDATA->people->isStarredMember==true) { ?>
            	<span class="position-absolute <?php if($peopleDATA->people->isFavorite==true){ echo 'text-warning'; } ?>" style="left:-10px; top:-10px"><i class="fa fa-star"></i></span>	
                <?php } ?>
  	<div class="overflow-hidden rounded-circle mb-3" style="width:130px;height:130px">
    <img src="<?php echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid">
    </div>
    <a href="<?php echo $site_url ?>/engagifii-profile-edit" class="btn btn-outline-dark" data-mdb-ripple-color="dark"
                style="z-index: 1;">
                Edit profile
              </a>
  </div>
  <div class="flex-grow-1 ml-3">
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
          <p class="d-flex align-items-start"><strong>Tags: </strong><span class="tags_all ml-3">
     				<?php 
						foreach ($peopleDATA->tags as $key => $value) {
							echo '<span class="badge rounded-pill text-bg-light border border-dark-subtle mr-2 mb-2">'.$value->tagName.'</span>';
					 }		
					 ?>
                    </span>
          <?php } ?>
        
  </div>
        </div>
    </div>
  </div>
  </div>
  </div>
  <!-- Header -->
  <div class="container-fluid">
  <div class="accordion" id="accordionExample">
  <div class="card mb-4 border rounded-3">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         GENERAL DETAILS
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
  <div class="row">
    <div class="col-8">

      <!-- Info -->
      <div class="card mb-4 px-3 mw-100">
        <div class="card-body">

        
		<?php  //if($address['country']) { 
		foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 $address = json_decode($value->selectedValue,true);?>
          <div class="row mb-2">
            <div class="col-md-3 text-muted">Country:</div>
            <div class="col-md-9">
              <span class="text-body"><?php echo  $address['country'];?></span>
            </div>
          </div>
          <?php } }
		   //} ?>

          
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
            
          </div>

          <div class="row mb-2">
            <div class="col-md-3 text-muted">Status:</div>
            <div class="col-md-9">
            Current
            </div>
          </div>

        </div>
       
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

          <div class="mb-1"> <p class="mb-4"><span class="text-primary font-italic mr-1"><img src="https://psba.engagifii.com/assets/images/org-list-grey.png" alt="avatar" class="rounded-circle img-fluid mr-2" style="width: 30px;">Cumberland Valley S D</span> 
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
  <div class="card mb-4 border rounded-3">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          EVENTS AND TRAINING
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        <?php echo do_shortcode('[event-list]'); ?>
      </div>
    </div>
  </div>
  <div class="card mb-4 border rounded-3">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapseTwo">
          AWARDS
        </button>
      </h2>
    </div>
    <div id="collapse3" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
                <strong>Certification Statistics</strong>
                <ul class="nav nav-pills justify-content-center session-tab nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
                        <a class="border nav-link text-left py-3 active " data-toggle="pill" data-target="#tab-1" href="#" role="tab" aria-controls="home" aria-selected="true"><span class="d-block">10</span><small>Registered <span class="ms-1"  data-toggle="tooltip" data-placement="top" data-title="Tooltip on top"><i class="far fa-info-circle"></i></span> </small></a>
                    </li>
                    <li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
                        <a class="border nav-link text-left py-3 " data-toggle="pill" data-target="#tab-2" href="#" role="tab" aria-controls="home" aria-selected="true"><span class="d-block">4</span><small>In Progress <span class="ms-1"  data-toggle="tooltip" data-placement="top" data-title="Tooltip on top"><i class="far fa-info-circle"></i></span></small></a>
                    </li>
                    <li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
                        <a class="border nav-link text-left py-3 " data-toggle="pill" data-target="#tab-3" href="#" role="tab" aria-controls="home" aria-selected="true"><span class="d-block">5</span><small>Not Started <span class="ms-1"  data-toggle="tooltip" data-placement="top" data-title="Tooltip on top"><i class="far fa-info-circle"></i></span></small></a>
                    </li>
                    <li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
                        <a class="border nav-link text-left py-3 " data-toggle="pill" data-target="#tab-4" href="#" role="tab" aria-controls="home" aria-selected="true"><span class="d-block">8</span><small>Earned <span class="ms-1"  data-toggle="tooltip" data-placement="top" data-title="Tooltip on top"><i class="far fa-info-circle"></i></span></small></a>
                    </li>
                    <li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
                        <a class="border nav-link text-left py-3 " data-toggle="pill" data-target="#tab-5" href="#" role="tab" aria-controls="home" aria-selected="true"><span class="d-block">9</span><small>Awarded <span class="ms-1"  data-toggle="tooltip" data-placement="top" data-title="Tooltip on top"><i class="far fa-info-circle"></i></span></small></a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
  	<div class="accordion" id="accordionExample1">
    
   <?php 
   	$length = 5;

for($i = 0; $i < $length; $i++){ ?>
	  <div class="card mb-4 border rounded-lg">
    <div class="card-header">
      <button class="btn btn-link d-flex w-100 text-left  <?php if($i!=0){ echo 'collapsed'; }?>" type="button" data-toggle="collapse" data-target="#collapseOne<?php echo $i;?>" aria-expanded="true" aria-controls="collapseOne">
        Accordion Item #<?php echo $i+1; ?><span class="ml-auto"><small class="text-warning">In progress (9%)</small> <span class="btn btn-primary btn-sm py-0 px-2 ms-1">Report</span></span>
      </button>
    </div>
    <div id="collapseOne<?php echo $i;?>" class="accordion-collapse collapse <?php if($i==0){ echo 'show'; }?>" data-parent="#accordionExample1">
      <div class="card-body">
        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>

<?php }

   ?>
</div>
  </div>
  <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
  	  	<div class="accordion" id="accordionExample2">
    
   <?php 
   	$length = 5;

for($i = 0; $i < $length; $i++){ ?>
	  <div class="card mb-4 border rounded-lg">
    <div class="card-header">
      <button class="btn btn-link d-flex w-100 text-left  <?php if($i!=0){ echo 'collapsed'; }?>" type="button" data-toggle="collapse" data-target="#collapseOnex<?php echo $i;?>" aria-expanded="true" aria-controls="collapseOne">
        Accordion Item #<?php echo $i+1; ?><span class="ml-auto"><small class="text-warning">In progress (9%)</small> <span class="btn btn-primary btn-sm py-0 px-2 ms-1">Report</span></span>
      </button>
    </div>
    <div id="collapseOnex<?php echo $i;?>" class="accordion-collapse collapse <?php if($i==0){ echo 'show'; }?>" data-parent="#accordionExample2">
      <div class="card-body">
        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>

<?php }

   ?>
</div>

  </div>
  <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="" tabindex="0">...</div>
</div>
      </div>
    </div>
  </div>
</div>
</div>

<?php } ?>