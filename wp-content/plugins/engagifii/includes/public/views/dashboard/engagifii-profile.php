<?php  
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
$userEmail = $user->user_email;
    $obj      =  new Engagifii_API();
    $engagifiiProfile = $obj->engagifiiProfile('psba');
	$peopleDATA = json_decode($engagifiiProfile['api_response']);
	$_SESSION['pid']=$peopleDATA->people->id;
	$_SESSION['name']=$peopleDATA->people->firstName.' '.$peopleDATA->people->middleName.' '.$peopleDATA->people->lastName;
	$_SESSION['dp']=$peopleDATA->people->imageThumbUrl;
if($peopleDATA->isError==true) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
} 
include 'sidebar_nav.php';  
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
  ?>
  <!-- Header -->

   <style>
.accordion .card-header button::after {
	position: absolute;
	content: '';
	background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
	transition: 0.3s all ease;
	right: 15px;
	top: 50%;
	width: 20px;
	height: 20px;
	transform: translateY(-50%);
}
.accordion .card-header button:not(.collapsed)::after {
	transform: translateY(-50%) rotate(180deg);
}
  </style>
  <div class="container-fluid  mb-4 ">
    <?php if($peopleDATA->people->requestStatus==1){ ?>
   <div> Profile changes pending under review</div> 
   <?php } ?>
  <div class="py-3 px-4 rounded-sm" style="background:#e0eafc">
    	<div class="d-flex">
        	<div class="flex-shrink-0 position-relative text-center">
            	<?php if($peopleDATA->people->isStarredMember==true) { ?>
            	<span class="position-absolute <?php if($peopleDATA->people->isFavorite==true){ echo 'text-warning'; } ?>" style="left:-10px; top:-10px"><i class="fa fa-star"></i></span>	
                <?php } ?>
  	<div class="overflow-hidden rounded-circle mb-2 bg-white p-1 shadow-sm " style="width:120px;height:120px">
    <img src="<?php echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid rounded-circle">
    </div>
    <a href="<?php echo $site_url ?>/engagifii-profile/edit" class="btn btn-outline-dark btn-sm" style="z-index: 1;">
                Edit profile
              </a>
  </div>
  <div class="flex-grow-1 ml-3 pt-3">
  	<h4 class=""><?php echo $peopleDATA->people->firstName.' '.$peopleDATA->people->middleName.' '.$peopleDATA->people->lastName; ?></h4>
    <div class="d-flex">    	
    <?php 
	$status = 'Inactive';
			$statusColor = 'red';
			 if($peopleDATA->people->isActive==true) {
				$status = 'Active'; 
				$statusColor = 'green';
			  ?>
              <span class="mr-4 bg-white rounded py-1 px-2 d-none"><strong>Status:</strong> <span style="color:<?php echo $statusColor; ?>;"><?php echo $status; ?></span></span>
              <?php } 
			  $dp = json_decode($peopleDATA->tabs[2]->groupFields[2]->fields[0]->selectedValue, true);
			  if(count($dp[0]['positionHistory'])>0){
					  if(count($dp[0]['positionHistory'])==1){
						 $department =$dp[0]['positionHistory'][0]['departmentName'];
  						echo '<span class="mr-4 bg-white rounded py-1 px-2"><strong>Department: </strong>'.$department.'</span>';
						 $position =$dp[0]['positionHistory'][0]['positionName'];
  						echo '<span class="mr-4 bg-white rounded py-1 px-2"><strong>Position: </strong>'.$position.'</span>';
					  }else{ ?>
                      	<div class="dropdown">
                          <a class="mr-4 bg-white rounded py-1 px-2" href="" data-toggle="dropdown" aria-expanded="false">
                            <?php echo count($dp[0]['positionHistory']). ' Departments'; ?>
                          </a>
                          <div class="dropdown-menu py-1">
                            <h6 class="bg-light text-center py-1 mb-1">Departments (<?php echo count($dp[0]['positionHistory']); ?>)</h6>
						<?php  foreach($dp[0]['positionHistory'] as $key => $position){ 
                       		 echo '<span class="dropdown-item px-2 py-0 small text-dark">'.$position['departmentName'].'</span><span class="dropdown-item pr-2 pl-4 mb-2 py-0 small text-dark">'.$peopleDATA->people->primaryOrganization->name.'</span>';
                   		 }  ?>
                         </div>
               			 </div> 
                      	<div class="dropdown">
                          <a class="mr-4 bg-white rounded py-1 px-2" href="" data-toggle="dropdown" aria-expanded="false">
                            <?php echo count($dp[0]['positionHistory']). ' Positions'; ?>
                          </a>
                          <div class="dropdown-menu py-1">
                            <h6 class="bg-light text-center py-1 mb-1">Positions (<?php echo count($dp[0]['positionHistory']); ?>)</h6>
						<?php  foreach($dp[0]['positionHistory'] as $key => $position){ 
                       		 echo '<span class="dropdown-item px-2 py-0 small text-dark">'.$position['positionName'].'</span><span class="dropdown-item pr-2 pl-4 mb-2 py-0 small text-dark">'.$peopleDATA->people->primaryOrganization->name.'</span>';
                   		 }  ?>
                         </div>
               			 </div> 
					 <?php  }
				  
           } ?>
  </div>
        </div>
        </div>
  </div>
  </div>
  <!-- Header -->
  <div class="container-fluid">
  <div class="accordion" id="accordionExample">
  <div class="card mb-4 border rounded-sm">
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        	<div class="row">
            	
            <?php  
		 foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 $address = json_decode($value->selectedValue,true);
	 ?><div class="col-md-4 mb-4 ">
 <Strong><?php echo $value->name;?>: </strong> <br><?php  echo $address['address'].',<br> '.$address['addressLine2'].'<br>'.$address['city'].', '.$address['state'].' '.$address['zipCode'].'<br>'.$address['country'];         
     echo "</div>"; } 
 }
?><hr class="my-4 col-12">
	
                
                <div class="col-md-4 mb-4">
                <?php if($peopleDATA->people->primaryEmail->value){ ?>
          <p class="mb-0"> <strong><?php echo $peopleDATA->people->primaryEmail->type; ?>: </strong><br>
           
            <a href="mailto:<?php echo $peopleDATA->people->primaryEmail->value; ?>" ><?php echo $peopleDATA->people->primaryEmail->value; ?></a>
          </p>
            <?php } ?>	
                </div>
                
                	<?php if($peopleDATA->people->primaryPhoneNumber->value){ ?>
            <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==11){ 
          $formattedPhoneNumber='';
          if($value->selectedValue){
          $formattedPhoneNumber = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $value->selectedValue);
          }
?>
             <div class="col-md-4 mb-4"> 
            <strong><?php echo $value->name;?>:</strong><br>
            
            <a href="tel:<?php echo $formattedPhoneNumber;?>"><?php echo $formattedPhoneNumber;?></a> </div>
   <?php  } 
 } }
?>
               
                
                
            </div>

<!-- / Content -->
<hr>
<div class="row">
            	<div class="col-12">
              Organization:
<p class="mb-4">
<span class="text-primary font-italic mr-1"><img decoding="async" src="https://ssresource.azureedge.net/resource/organization/847b4b19-9e08-4f6f-9fba-6111da6aa6d2.png" alt="avatar" class="rounded-circle img-fluid mr-2" style="width: 30px;">Abington Heights School District</span> 
                </p>
         </div>    
            </div>

      </div>
    </div>
  </div>

</div>
</div>
<script>
 localStorage.setItem("logged_in_user", "<?php echo $peopleDATA->people->id;?>");
</script>
