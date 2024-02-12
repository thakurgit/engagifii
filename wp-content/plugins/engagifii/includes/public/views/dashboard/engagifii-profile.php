<?php 
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
    $engagifiiProfile = $obj->engagifiiProfile('psba',$userEmail);
	$peopleDATA = json_decode($engagifiiProfile['api_response']);
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
              <span class="mr-4 bg-white rounded py-1 px-2"><strong>Status:</strong> <span style="color:<?php echo $statusColor; ?>;"><?php echo $status; ?></span></span>
              <?php } 
			  $dp = json_decode($peopleDATA->tabs[2]->groupFields[2]->fields[0]->selectedValue, true);
			  $department =$dp[0]['positionHistory'][0]['departmentName'];
			  $position =$dp[0]['positionHistory'][0]['positionName'];
			  ?>
              <span class="mr-4 bg-white rounded py-1 px-2"><strong>Department: </strong><?php echo $department; ?></span>
              <span class="mr-4 bg-white rounded py-1 px-2"><strong>Position: </strong><?php echo $position; ?></span>
  </div>
        </div>
        </div>
  </div>
  </div>
  <!-- Header -->
  <div class="container-fluid">
  <div class="accordion" id="accordionExample">
  <div class="card mb-4 border rounded-sm">
    <div class="card-header p-0" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left p-3 position-relative" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         Contact Information
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        	<div class="row">
            	<div class="col-12 ">
            <?php  
		 foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 $address = json_decode($value->selectedValue,true);
	 ?>
 <Strong><?php echo $value->name;?>: </strong> <br><?php  echo $address['locationName'].'<br>'.  $address['address'].', '.$address['addressLine2'].'<br>'.$address['city'].', '.$address['state'].' '.$address['zipCode'].'<br>'.$address['country'];?>         
     <?php  } 
 }
?><hr class="my-4">
	
                </div>
                <div class="col-md-4">
                <?php if($peopleDATA->people->primaryEmail->value){ ?>
          <p class="mb-0"> <strong><?php echo $peopleDATA->people->primaryEmail->type; ?>: </strong><br>
           
            <a href="mailto:<?php echo $peopleDATA->people->primaryEmail->value; ?>" ><?php echo $peopleDATA->people->primaryEmail->value; ?></a>
          </p>
            <?php } ?>	
                </div>
                
                	<?php if($peopleDATA->people->primaryPhoneNumber->value){ ?>
            <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==11){ ?>
             <div class="col-md-4"> 
            <strong><?php echo $value->name;?>:</strong><br>
            <a href="tel:<?php echo $value->selectedValue;?>"><?php echo $value->selectedValue;?></a> </div>
   <?php  } 
 } }
?>
               
                
                
            </div>

<!-- / Content -->
      </div>
    </div>
  </div>
  <div class="card mb-4 border rounded-sm">
    <div class="card-header p-0" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left p-3 position-relative collapsed" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="">
         Organization
        </button>
      </h2>
    </div>

    <div id="collapse2" class="collapse" aria-labelledby="" data-parent="#accordionExample">
      <div class="card-body">
        	<div class="row">
            	<div class="col-12">
<p class="mb-4">
<span class="text-primary font-italic mr-1"><img src="<?php echo $peopleDATA->people->primaryOrganization->imageThumbUrl; ?>" alt="avatar" class="rounded-circle img-fluid mr-2" style="width: 30px;"><?php echo $peopleDATA->people->primaryOrganization->name; ?></span> 
                </p>
         </div>    
            </div>

<!-- / Content -->
      </div>
    </div>
  </div>
</div>
</div>
<script>
 localStorage.setItem("logged_in_user", "<?php echo $peopleDATA->people->id;?>");
</script>
