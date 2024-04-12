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
$member_id = isset($_GET['member']) ? $_GET['member'] : null;
    $obj      =  new Engagifii_API();
    $options = get_option('ebt_api_settings'); 
    $profilePayloadFields = $options['dashboard_fields']['fields']; 
    if($member_id){
      $memberid = $member_id;
    }else{
        $memberid ="";
    }
    $profilePayload = array(
      "id" => $memberid,
      "fieldIds" => $profilePayloadFields
  );
	$tenantCode = $options['dashboard_apis']['tenant'];
  $engagifiiProfile = $obj->engagifiiProfile($profilePayload, $tenantCode);
	$peopleDATA = json_decode($engagifiiProfile['api_response']);

  $fiscalYear  = $obj->getFiscalYear();
  $fiscalYearResponse = json_decode($fiscalYear['api_response'])->collection;
  $largestStartDate = null;
  $largestEndDate = null;
  
  foreach ($fiscalYearResponse as $fiscalYear) {
      $startDate = strtotime($fiscalYear->startDate);
      $endDate = strtotime($fiscalYear->endDate);
  
      if ($largestStartDate === null || $startDate > $largestStartDate) {
          $largestStartDate = $startDate;
           $largestFiscalYearName = $fiscalYear->name;
     }
  
      if ($largestEndDate === null || $endDate > $largestEndDate) {
          $largestEndDate = $endDate;
      }
  }
  $fiscalStartDate = date('Y-m-d', $largestStartDate );
  $fiscalEndDate = date('Y-m-d', $largestEndDate );
  
if($peopleDATA->isError==true) { 
echo "<br><br><div class='alert alert-danger' role='alert'><h5 class='text-center'>Session Timeout. <a href='".esc_url(wp_logout_url(''))."' onclick='clearAllCookies()' target='_blank'> Login again</a></h5></div>";
?>
<script>
 function clearAllCookies() {
	  localStorage.clear();  
     var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
	function myWindow(){
		 window.open('https://engagifii-preview4-identity.azurewebsites.net/Account/SignOut?ReturnUrl=%2Fconnect%2Fauthorize%2Fcallback%3Fclient_id%3Dng.EngagifiiUI%26redirect_uri%3Dhttps%253A%252F%252Fpsba.engagifii-preview4.com%252Fauth-callback%26response_type%3Did_token%2520token%26scope%3Dopenid%2520profile%2520email%2520UsersAPI%2520AccreditationAPI%2520BilltrackingApi%2520CommentApi%2520NotesApi%26state%3D2f9558adbd6147b0acdd08d1aa46c79c%26nonce%3D43ea3bf67eef475ca04ea79b328fd000','_self');
	}
  setTimeout(function() {
	  myWindow();
	  }, 300);
	
}
</script> 
<?php return;
} 
	 
if(!$member_id){
  $loggedin_username = $peopleDATA->people->firstName.' '.$peopleDATA->people->middleName.' '.$peopleDATA->people->lastName;
  $loggedin_userdp = $peopleDATA->people->imageThumbUrl;
  setcookie('pid', $peopleDATA->people->id, time() + (24 * 3600), '/');
  setcookie('loggedin_username', $loggedin_username, time() + (24 * 3600), "/");
  setcookie('loggedin_userdp', $loggedin_userdp, time() + (24 * 3600), "/"); // 86400 = 1 day
   $_SESSION['pid']=$peopleDATA->people->id;
  $_SESSION['name']=$loggedin_username;
	$_SESSION['dp']=$loggedin_userdp;
}
$getPendingRequest = $obj->getPendingRequestByPeopleId($peopleDATA->people->id);
$isPendingRequest = json_decode($getPendingRequest['api_response']);
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
  <div class="profile-page">
  <div class="container-fluid  mb-4 ">
    <?php if($isPendingRequest==true){ ?>
      <div class="text-right"><span class="badge badge-warning">Profile Changes Pending for Approval</span> </div> 
   <?php } ?>
  <div class="py-3 px-4 rounded-sm">
    	<div class="d-flex">
        	<div class="flex-shrink-0 position-relative text-center">
            	<?php if($peopleDATA->people->isStarredMember==true) { ?>
            	<span class="position-absolute <?php if($peopleDATA->people->isFavorite==true){ echo 'text-warning'; } ?>" style="left:-10px; top:-10px"><i class="fa fa-star"></i></span>	
                <?php } ?>
  	<div class="overflow-hidden mb-2 bg-white p-1 shadow-sm " style="width:120px; height:120px;">
    <?php if (str_contains($peopleDATA->people->imageThumbUrl, 'http')) { ?>
    	<img src="<?php echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid">
    <?php } else { ?>
    	<i class="fa fa-user text-secondary" style="font-size:110px"></i>
    <?php } ?>
    </div>
    <a href="<?php echo $site_url ?>/engagifii-profile/edit" class="btn btn-outline-dark btn-sm edit-profile-btn" style="z-index: 1;">
                Edit profile
              </a>
  </div>
  <div class="flex-grow-1 ml-3 pt-3">
  	<p style="font-size:1.375rem; margin-top:15px; margin-bottom: 0px;"><?php echo $peopleDATA->people->firstName.' '.$peopleDATA->people->middleName.' '.$peopleDATA->people->lastName; ?></p>
    <div class="d-flex">    	
    <?php 
	  foreach ($peopleDATA->peopleFields as $key => $value) {
		if ($value->controlTypeId == 12 && in_array($value->id, $profilePayloadFields)) {
			$dp = json_decode($value->organizationValue, true);
			if(count($dp)==1){
			   if (count($dp[0]['positionHistory']) == 1 && $dp[0]['positionHistory'][0]['isCurrent']==true) {
				  $department = $dp[0]['positionHistory'][0]['departmentName'] ?: '--';
				  $position = $dp[0]['positionHistory'][0]['positionName'] ?: '--';
          if($department!="--"){
				  echo '<span class="py-1 pr-4" style="font-size:.875rem;"><strong>Department: </strong>' . $department . '</span>';
          }
          if($position!="--"){
				  echo '<span class="py-1 pr-4" style="font-size:.875rem;"><strong>Position: </strong>' . $position . '</span>';
          }
			   }else{ 
				  $positions = $dp[0]['positionHistory'];
				  $totalDepartments = 0;
				  $totalPositions = 0;
					foreach ($positions as $position) {
						if($position['isCurrent']==true){
						  $totalDepartments += count($position['departmentName']);
						  $totalPositions += count($position['positionName']);
						}
					}
					if($totalDepartments>0){
			   ?>
				  <div class="dropdown">
					  <a class="py-1 px-1" style="font-size:.875rem;" href="" data-toggle="dropdown" aria-expanded="false"><?php echo $totalDepartments . ' Departments';?> |
					  </a>
					  <div class="dropdown-menu py-1" style="width:300px;">
						  <h6 class="bg-light text-center py-1 mb-1">Departments (<?php echo $totalDepartments; ?>)</h6>
						  <?php foreach ($positions as $item) {
							  if($item['isCurrent']==true){
								  echo '<span class="dropdown-item px-2 py-0 small text-dark">' . $item['departmentName'] . '</span><span class="dropdown-item pr-2 pl-4 mb-2 py-0 small text-primary">' . $dp[0]['name'] . '</span>';
							  }
						  } ?>
					  </div>
				  </div>
				  <?php } if($totalPositions>0){ ?>
				  <div class="dropdown">
					  <a class="py-0 px-0" style="font-size:.875rem;" href="" data-toggle="dropdown" aria-expanded="false"><?php echo $totalPositions . ' Positions'; ?></a>
					  <div class="dropdown-menu py-1" style="width:300px;">
						  <h6 class="bg-light text-center py-1 mb-1">Positions (<?php echo $totalPositions; ?>)</h6>
						  <?php foreach ($positions as $item) {
							  if($item['isCurrent']==true){
								  echo '<span class="dropdown-item px-2 py-0 small text-dark">' . $item['positionName'] . '</span><span class="dropdown-item pr-2 pl-4 mb-2 py-0 small text-primary">' . $dp[0]['name'] . '</span>';
							  }
						  } ?>
					  </div>
				  </div>
			   <?php } 
			   }
			}else{
			  $totalDepartments = 0;
			  $totalPositions = 0;
			  $totalDepartmentsName = '';
			  $totalPositionsName = '';
			  foreach($dp as $key => $value){
				  $org = $value['name'];
				  $positions = $value['positionHistory'];
				  foreach ($positions as $position) {
					if($position['isCurrent']==true){
					  $totalDepartments += count($position['departmentName']);
					  $totalPositions += count($position['positionName']);
					  $totalDepartmentsName.='<span class="dropdown-item px-2 py-0 small text-dark">' . $position['departmentName'] . '</span><span class="dropdown-item pr-2 pl-4 mb-2 py-0 small text-primary">' . $org . '</span>';
					  $totalPositionsName.='<span class="dropdown-item px-2 py-0 small text-dark">' . $position['positionName'] . '</span><span class="dropdown-item pr-2 pl-4 mb-2 py-0 small text-primary">' . $org . '</span>';
					}
				  }
			  }
			  if($totalDepartments>0){ ?>
				  <div class="dropdown">
					  <a class="py-0 px-0" style="font-size:.875rem;" href="" data-toggle="dropdown" aria-expanded="false"><?php echo $totalDepartments . ' Departments';?> |
					  </a>
					  <div class="dropdown-menu py-1" style="width:300px;">
						  <h6 class="bg-light text-center py-1 mb-1">Departments (<?php echo $totalDepartments; ?>)</h6>
						  <?php echo $totalDepartmentsName; ?>
					  </div>
				  </div>
			  <?php } 
			  if($totalPositions>0){ ?>
				  <div class="dropdown">
					  <a class="py-0 px-0" style="font-size:.875rem;" href="" data-toggle="dropdown" aria-expanded="false"><?php echo $totalPositions . ' Positions';?> 
					  </a>
					  <div class="dropdown-menu py-1" style="width:300px;">
						  <h6 class="bg-light text-center py-1 mb-1">Positions (<?php echo $totalPositions; ?>)</h6>
						  <?php echo $totalPositionsName; ?>
					  </div>
				  </div>
			  <?php }
				} 
			  }
	  }
	?>
  </div>
  <div> <?php 
  $status = 'Inactive';
			$statusColor = 'red';
			 if($peopleDATA->people->isActive==true) {
				$status = 'Active'; 
				$statusColor = 'green';
			  if($member_id) { ?>
              <span class="mr-4 bg-white rounded py-1 d-none"><strong>Status:</strong> <span style="color:<?php echo $statusColor; ?>;"><?php echo $status; ?></span></span>
              <?php } } ?>
  </div>
        </div>
        </div>
  </div>
  <button  type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm  ml-auto gt"  title="Select Member" disabled><i class="far fa-file-pdf mr-2"></i>Generate Credits Earned Report</button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Generate Credits Earned Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group dateFilter">
                        <label>Select Date Range</label>
                        <div class="input-group mr-2" style="max-width:255px">
                        <input type="text" class="form-control form-control-sm shadow-none" placeholder="Select Date Range">
                      <div class="input-group-append">
                        <span class="input-group-text bg-transparent clearDateFilter" style="cursor:pointer; display:none;"><i class="far fa-times"></i></span>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text "><i class="far fa-calendar-alt"></i></span>
                      </div>
                    </div>
                    </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary gtm">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
  </div>
  <!-- Header -->
  <div class="container-fluid">
      <div class="card-body border rounded">
        	<div class="row">
            	
            <?php  
		// foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
		 foreach ($peopleDATA->peopleFields as $key => $value) {
     if($value->controlTypeId==9 && in_array($value->id, $profilePayloadFields)){
	 $address = json_decode($value->selectedValue,true);
	 ?>
     <div class="col-md-4 mb-4 ">
 <Strong><?php echo $value->name;?>: </strong> <br><?php  echo $address['address'].',<br>'.$address['city'].', '.$address['state'].' '.$address['zipCode'].'<br>'.$address['country'];         
     echo "</div>"; } 
 }
?>
<hr class="my-4 col-12">
  <div class="col-md-4 mb-4">
                <?php if($peopleDATA->people->primaryEmail->value){ ?>
          <p class="mb-0"> <strong><?php echo $peopleDATA->people->primaryEmail->type; ?>: </strong><br>
           
            <a class="font-weight-normal" href="mailto:<?php echo $peopleDATA->people->primaryEmail->value; ?>" ><?php echo $peopleDATA->people->primaryEmail->value; ?></a>
          </p>
            <?php } ?>	
                </div>
                
                	<?php //if($peopleDATA->people->primaryPhoneNumber->value){
           foreach ($peopleDATA->peopleFields as $key => $value) {
     if($value->controlTypeId==11 && in_array($value->id, $profilePayloadFields)){
          $formattedPhoneNumber='';
          if($value->selectedValue){
          $formattedPhoneNumber = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $value->selectedValue);
          }
?>
             <div class="col-md-4 mb-4"> 
            <strong><?php echo $value->name;?>:</strong><br>
            
            <?php echo $formattedPhoneNumber;?></div>
   <?php  } 
 } 
 //}
?>

<hr class="my-4 col-12">
<?php 
foreach ($peopleDATA->peopleFields as $key => $value) {
    		 if($value->controlTypeId==12 && in_array($value->id, $profilePayloadFields)){ ?>
<div class="col-12 d-none">
              <strong>Organization:</strong><br>
 
<p class="mb-4 mt-2">
<span class="text-primary mr-1">
<?php if($peopleDATA->people->primaryOrganization->imageThumbUrl && filter_var($peopleDATA->people->primaryOrganization->imageThumbUrl, FILTER_VALIDATE_URL)){ ?>
<img src="<?php echo $peopleDATA->people->primaryOrganization->imageThumbUrl; ?>" alt="" class="rounded-circle img-fluid mr-2" style="width: 30px;">
<?php } else { ?>
<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>
<?php } ?>
<?php echo $peopleDATA->people->primaryOrganization->name; ?></span> 
                </p>
                </div>
                <?php } } ?>
                
 <div class="col-12">
 <?php 
   foreach ($peopleDATA->peopleFields as $key => $value) {
    if ($value->controlTypeId == 12 && in_array($value->id, $profilePayloadFields)) {
		echo '<strong>Organization:</strong><br>';
        $dp = json_decode($value->organizationValue, true);
        foreach ($dp as $organization) {
            $orgIcon = $organization['imageThumbUrl'];
            $orgName = $organization['name'];
			echo '<div class="py-2 d-flex align-items-start border-bottom ">';
            if ($orgIcon && filter_var($orgIcon, FILTER_VALIDATE_URL)) {
              echo '<img src="' . $orgIcon . '" alt="Organization Thumbnail" class="rounded-circle img-fluid mr-2" style="max-width: 30px; flex:0 0 30px">';
          } else {
              echo '<span class="mr-2 text-white d-inline-flex align-items-center justify-content-center p-2 rounded-circle" style="font-size:24px; background:#979797"><i class="far fa-landmark"></i></span>';
          }
            echo '<div><h6 class="mb-2">' . $orgName . '</h6>';
            $positions = $organization['positionHistory'];
            foreach ($positions as $position) {
                if ($position['isCurrent'] == true) {
                    $department = $position['departmentName'] ?: '--';
                    $positionName = $position['positionName'] ?: '--';
                    $totalTimeWorked = "--";

                    echo '<strong>Department: </strong>' . $department . '<br>';
                    echo '<strong>Position: </strong>' . $positionName . '<br>';
                    echo '<strong>Total Time Worked: </strong>' . $totalTimeWorked.'<hr class="my-2">' ; 
                }
            }
			echo '</div></div>';
        }
    }
} ?>
 </div>
 </div>
      </div>
    </div>
   </div> 
    <!--profile edit-->
   <style>
   .profile-tabs .nav-link {
	top:0 !important;	
	border-bottom:0 !important	
	}
	.profile-tabs .nav-link.active, .profile-tabs .nav-link:hover {
	border-bottom:0 !important	
	}
  
   </style>
   	<div class="container-fluid position-relative profile-edit-modal d-none">
    <button type="button" style="top:0; right:0;" class="btn  position-absolute edit-profile-cancel mr-3 p-2 shadow-none" title="Cancel edit"><i class="fa fa-times"></i></button>
	<form action="" class="edit-profile">
    
    <div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade border rounded-2 p-4 show active" id="nav-header" role="tabpanel" aria-labelledby="nav-home-tab">
  	<div class="row">
        	<div class="col-md-1 mr-5 text-center">
            <div class="overflow-hidden d-block m-auto" style="width:130px;height:130px">
            <span id="upload_profile" class="position-relative  d-block h-100">
            <?php if (str_contains($peopleDATA->people->imageThumbUrl, 'http')) { ?>
    	<img src="<?php echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid h-100" id="blah"  >
    <?php } else { ?>
    	<i class="fa fa-user text-secondary" style="font-size:110px"></i>
    <?php } ?>
    <!-- <img src="<?php //echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid h-100" id="blah"  > -->
    <span class="position-absolute w-100 h-100 top-0 start-0 text-white d-flex align-items-center flex-column justify-content-center" style="background:rgba(0,0,0,0.6); opacity:0; top:0; left:0"><i class="fa fa-image"></i><br>Upload</span>
    <input type="file" class="position-absolute top-0 start-0 w-100 h-100 z-1" style="opacity:0; top:0; left:0" accept="image/*" id="imgInp" onchange=""> 
    <style>
	#upload_profile:hover span {
	opacity:1 !important;	
	}
	</style>
    </span>
    </div>	
    <div class="modal fade" id="modal_crop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Crop the image</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="image" src="">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="crop">Crop</button>
          </div>
        </div>
      </div>
    </div>
            </div>
            <div class="col-md-8 ml-3 mt-3">
              <div data-section="header" class="row mb-4">
                  <div class="form-group col-md-4">
                  <label for="">First Name</label>
                    <input  type="text" value="<?php echo $peopleDATA->people->firstName; ?>" class="form-control firstName">
                  </div>
                  <div class="form-group col-md-4">
                  <label for="">Middle Name</label>
                    <input  type="text" value="<?php echo $peopleDATA->people->middleName; ?>" class="form-control middleName">
                  </div>
                  <div class="form-group col-md-4">
                  <label for="">Last Name</label>
                    <input type="text" value="<?php echo $peopleDATA->people->lastName; ?>" class="form-control lastName">
                  </div>                                 
              </div>
              </div>
    </div>
    <hr class="my-4 col-12">
            <div class="pb-3">
              <div class="overflow-hidden">
              <div class="px-3">
              <div class="form-group">
              	<label for="Email Address">Email Address</label>
                    <input disabled type="text" value="<?php echo $peopleDATA->people->primaryEmail->value; ?>" class="form-control primaryEmail">
              </div>
              
              <div class="row">
              <?php   foreach ($peopleDATA->peopleFields as $key => $value) {
     if($value->controlTypeId==11 && in_array($value->id, $profilePayloadFields)){
      $formattedPhoneNumber='';
          if($value->selectedValue){
          $formattedPhoneNumber = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $value->selectedValue);
          }?>
              <div class="form-group col-md-6">
              	<label for=""><?php echo $value->name;?></label>
                    <input type="text" value="<?php echo $formattedPhoneNumber;?>" class="form-control phonenumber-<?php echo $key;?>">
                    <div class="invalid-feedback">Only numbers allowed.</div>
              </div>
   <?php  } 
 }
?>			</div>
			</div>
              <?php   foreach ($peopleDATA->peopleFields as $key => $value) {
     if($value->controlTypeId==9 && in_array($value->id, $profilePayloadFields)){ 
	 $address = json_decode($value->selectedValue,true);
	 ?>
              	<div class="px-3 address-wrap addressGroup<?php echo $key;?> ">
              <h5 class="bg-body-secondary py-2 border-bottom"><?php echo $value->name;?></h5>
              	<div class="row">
                <div class="form-group col-12">
                	<div class="input-group">
                    	 <label class="sr-only" for=""><b><?php echo $value->name;?></b></label>
                        <input type="text" name="" class="form-control text-start locationName" id="locationName" data-value ="<?php  echo  $address['locationName'];?>" value="<?php  echo  $address['locationName'];?>"/>
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2"><i class="fal fa-search"></i></span>
                      </div>
                    </div>

                 
                 </div>
                <div class="form-group col-12">
                  <label for="">Address Line 1</label>
                	<input type="text" name="" class="form-control text-start address" id="address" data-value ="<?php  echo  $address['address'];?>" value="<?php  echo  $address['address'];?>"/>
                    </div>
                <div class="form-group col-12">
                  <label for="">Address Line 2</label>
                	<input type="text" name="" class="form-control text-start addressLine2" id="addressLine2" data-value ="<?php  echo  $address['addressLine2'];?>" value="<?php  echo  $address['addressLine2'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">City</label>
                	<input type="text" name="" class="form-control text-start city" id="city" data-value ="<?php  echo  $address['city'];?>" value="<?php  echo  $address['city'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">State</label>
                	<input type="text" name="" class="form-control text-start state" id="state" data-value ="<?php  echo  $address['state'];?>" value="<?php  echo  $address['state'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">Zip</label>
                	<input type="text" name="" class="form-control text-start zipCode" id="zipCode" data-value ="<?php  echo  $address['zipCode'];?>" value="<?php  echo  $address['zipCode'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">Country</label>
                	<input type="text" name="" class="form-control text-start country" id="country" data-value ="<?php  echo  $address['country'];?>" value="<?php  echo  $address['country'];?>"/>
                    </div>
                     <div class="">
                	<!--<input type="hidden" name="" class="form-control text-start" id="lat" data-value ="<?php  echo  $address['lat'];?>" value="<?php  echo  $address['lat'];?>"/>
                	<input type="hidden" name="" class="form-control text-start" id="lng" data-value ="<?php  echo  $address['lng'];?>" value="<?php  echo  $address['lng'];?>"/>
                    </div> -->
                    	
                </div>
                </div>
                <hr class="border-secondary">
   <?php  } 
 }
?>
              
              </div>
               <div class="form-group col-12 px-3">
                	        <button type="submit" id="updateProfile" class="btn btn-primary">Update Profile <span style="display:none" role="status" aria-hidden="true" class="spinner-border spinner-border-sm ml-2"></span></button>

                            <a class="btn btn-default border border-dark edit-profile-cancel" href="<?php echo $site_url ?>/engagifii-profile">Cancel</a>

                </div>
               <!-- <div class="curl-message col-12  px-3" >
                	
                	<span class="curl-progress" style="display:none"><em>Hold on, Profile updating...</em></span>
                	<span class="curl-success" style="display:none"><em>Profile updated successfully.</em></span>
                </div>-->
            </div>
  </div>
</div>
<div class="position-fixed top-0 right-0 p-3" style="z-index: 5; right: 7px; top: 60px;">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
<div class="toast-body" style="background:#dff0d8">
      Profile updated successfully.<button type="button" data-dismiss="toast" aria-label="Close" class="ml-4  close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
      </div>
</div>
         
<div class="modal fade" id="requestSubmitted" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0 border-0">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close p-2" data-dismiss="modal" aria-label="Close" style="z-index:9">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
     <?php  if($member_id){ ?>
      <p class="text-center">Your request is submitted successfully. Please return to <a href="<?php echo site_url(); ?>/engagifii-profile/?member=<?php echo $member_id; ?>">User Profile</a></p>
      <?php } else { ?>
        <p class="text-center">Your request is submitted successfully. Please return to <a href="<?php echo site_url(); ?>/engagifii-profile">My Profile</a></p>
        <?php } ?> 
      </div>
      
    </div>
  </div>
</div>
  
    
    	
    </form>

    </div>
    <script>
   	jQuery(document).ready(function(e){
	jQuery('.edit-profile-btn').click(function(e){
		jQuery('.profile-page').hide();
		jQuery('.profile-edit-modal').removeClass('d-none');
		e.preventDefault();	
	});
	jQuery('.edit-profile-cancel').click(function(e){
		jQuery('.profile-page').show();
		jQuery('.profile-edit-modal').addClass('d-none');
		e.preventDefault();	
	});
	});
	jQuery('[class^="phonenumber-"], [class*=" phonenumber-"]').on('input', function() {
    this.value = this.value.replace(/\D/g, "");
    if (this.value.length < 10) {
        jQuery(this).siblings('.invalid-feedback').text('Please enter a valid phone number (10 digits minimum).').show();
    } else if (this.value.length > 25) {
        jQuery(this).siblings('.invalid-feedback').text('Please enter a valid phone number (25 characters maximum).').show();
    } else {
        jQuery(this).siblings('.invalid-feedback').hide();
        if (this.value.length >= 10) {
          var formattedValue = this.value.replace(/(\d{3})(\d{3})(\d{0,4})/, '($1) $2-$3');
            this.value = formattedValue.trim();
        }
    }
});
	jQuery('body').on('click','.tag_del',function(){
		jQuery(this).parent().remove();	
		if($('.tags_all>span').length==0){
		$('.tags_all').html('<em>No Tags Found!</em>');	
		}
	});
	 jQuery(".tag_add").keypress(function (event) {
            if (event.keyCode === 13 && jQuery(this).val()!='') {
				$('.tags_all>em').remove();
				/*jQuery('.tags_all > span').each(function(){
					var oldtag=jQuery(this).clone();  
					oldtag.find('span').remove();
					tags.push(oldtag.html());	
				});*/
                var val = '<span class="badge rounded-pill text-bg-light border border-dark-subtle mr-2 mb-2">'+jQuery(this).val()+'<span class="tag_del px-1" style="cursor:pointer">X</span></span>';
				var tag=jQuery(this).val();
				$('.tags_all').append(val);
				jQuery(this).val('');
				/*tags.push(tag);
				allTags = tags.join();*/
            }
			//event.stopPropagation();
			//event.preventDefault();
        });
	var payload = [];
    window.addEventListener('DOMContentLoaded', function () {
      var avatar = document.getElementById('blah');
      var image = document.getElementById('image');
      var input = document.getElementById('imgInp');
      var $modal = $('#modal_crop');
      var cropper;
	  var imageThumbpayload;


      input.addEventListener('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
          input.value = '';
          image.src = url;
          $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];

          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
      });

      $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
          aspectRatio: 1,
          viewMode: 0,
        });
      }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
      });

      document.getElementById('crop').addEventListener('click', function () {
        var initialAvatarURL;
        var canvas;

        $modal.modal('hide');

        if (cropper) {
          canvas = cropper.getCroppedCanvas({
            width: 130,
            height: 130,
          });
          initialAvatarURL = avatar.src;
          avatar.src = canvas.toDataURL();
          canvas.toBlob(function (blob) {
			var profiledpdata = {
			"ImageString": (avatar.src).replace(/^data:image\/[a-z]+;base64,/, ""),
			"Module": 'crm',
		 };
		 profiledpdata = JSON.stringify(profiledpdata ); 
            $.ajax('https://engagifiiresource.azurewebsites.net/api/upload', {
              method: 'POST',
      			data: profiledpdata,
              processData: false,
              headers: {
				'Content-Type': 'application/json',
			  },
              success: function (response) {
				  imageThumbpayload = {
					"imageThumbUrl": response,
				   };
				  jQuery('#blah').attr('src',response);
				  $.ajax('https://engagifii-preview4-crm.azurewebsites.net/api/v1/People/UpdatePersonHeader/<?php echo $peopleDATA->people->id; ?>', {
					method: 'PUT',
					  data: JSON.stringify(imageThumbpayload),
					processData: false,
					headers: {
					  'Content-Type': 'application/json',
					  'Authorization':'Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MTAxNjM4NjAsImV4cCI6MTc0MTY5OTg2MCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiM2ViYTRmNTItYjkwYi00YzgwLWFiYjMtNTE5YjBhNzcyMGVlIiwiYXV0aF90aW1lIjoxNzEwMTYzODYwLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IjA2MjQ2NGFhLTU5MWUtNDU4NC05MjI0LTcxZmZjNjEyZWMyOCIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IiIsImZhbWlseV9uYW1lIjoiIiwiZW1haWwiOiJqY3Jhd2xleUB5b3BtYWlsLmNvbSIsImxhc3QtbG9naW4iOiIwMy8xMS8yMDI0IDEzOjMwOjAwIiwiY3VycmVudC1sb2dpbiI6IjAzLzExLzIwMjQgMTM6MzE6MDAiLCJzY29wZSI6WyJvcGVuaWQiLCJwcm9maWxlIiwiZW1haWwiLCJVc2Vyc0FQSSIsIkFjY3JlZGl0YXRpb25BUEkiLCJCaWxsdHJhY2tpbmdBcGkiLCJDb21tZW50QXBpIiwiTm90ZXNBcGkiXSwiYW1yIjpbInB3ZCJdfQ.rRBS-695ziRsJNb1d4eGlostYFdsfOKF3b-Lj_9nUCGdxA95lHlsFxI1Qk-oLrBvNzRDBf_sbKisOQQ3fjw05V3d5flg7FXViUe48ekeDn-dIZWqa33btFT_-6Ukt-4rMjP-ZFSi7FscHiHW1vAbjx8vKAkDrEdhTR1yvLKy2Bnfkocgr225Om-1ATby8lXRy-3Xq1wofjrg25EUfgl7QzPv_s3LK_pT0eS1pdYuEw39UoZB8yWwtzQ4sqhaQihA6b63IBJqDKTO_Mda__dTqQndmheqfFcgZ-VvZF9EWl6_O8fu3g5CmrFXryDFO3vLeovvVm3L_HlDqEkyZUoh9g',
					  'tenant-code':'<?php echo $tenantCode; ?>'
					},
					success: function (response) {
						jQuery("#liveToast").toast("show");
					},
	  
					error: function () {
					},
	  
					complete: function () {
					},
				  });
              },

              error: function () {
                avatar.src = initialAvatarURL;
              },

              complete: function () {
              },
            });
          });
        }
      });
    });
	
	<?php /*?> function encodeImageFileAsURL(element) {
		 var  DPpayload=[];
		 var baseimg, profiledpdata, imageThumbUrlpath,imageThumbUrl='';
        let file = element.files[0];
		if(file.size/1024>100){
			alert('Image size should be less than 100KB');
		return;	
		}
        let reader = new FileReader();
        reader.onloadend = function() {
		  let xx = reader.result;
		  baseimg =xx.replace(/^data:image\/[a-z]+;base64,/, "");
		  const [files] = element.files
		  if (files) {
			blah.src = URL.createObjectURL(files);
				 profiledpdata = {
			"ImageString": baseimg,
			"Module": 'crm',
		 };
		  if(profiledpdata){
			DPpayload.push( profiledpdata ); 
			 DPpayload = JSON.stringify(DPpayload[0] ); 
				   const options = {
				method: 'POST',
				headers: {
				  'Content-Type': 'application/json'
				},
				body: DPpayload
			  };
			  
			  const apiUrl = 'https://engagifiiresource.azurewebsites.net/api/upload';
			  fetch(apiUrl,options)
				.then(response => {
				  if (!response.ok) {
					throw new Error('Network response was not ok');
				  }
				  return response.json();
				})
				.then(data => {
				  //console.log('API response data:', data);
			   imageThumbpayload = {
				  "imageThumbUrl": data,
			   }
			   ;
				  jQuery('#blah').attr('src',data);
				  jQuery("#liveToast").toast("show");
				 // jQuery('.curl-success').show().siblings().hide();
					xxx();		
						  
						})
				.catch(error => {
				  console.error('There has been a problem with your fetch operation:', error);
				});
				
				
				function xxx(){
			  const dpUrl = 'https://engagifii-preview4-crm.azurewebsites.net/api/v1/People/UpdatePersonHeader/<?php echo $peopleDATA->people->id; ?>';
					  const dpoptions = {
						method: 'PUT',
						headers: {
						  'Content-Type': 'application/json',
						  'Authorization':'Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MTAxNjM4NjAsImV4cCI6MTc0MTY5OTg2MCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiM2ViYTRmNTItYjkwYi00YzgwLWFiYjMtNTE5YjBhNzcyMGVlIiwiYXV0aF90aW1lIjoxNzEwMTYzODYwLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IjA2MjQ2NGFhLTU5MWUtNDU4NC05MjI0LTcxZmZjNjEyZWMyOCIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IiIsImZhbWlseV9uYW1lIjoiIiwiZW1haWwiOiJqY3Jhd2xleUB5b3BtYWlsLmNvbSIsImxhc3QtbG9naW4iOiIwMy8xMS8yMDI0IDEzOjMwOjAwIiwiY3VycmVudC1sb2dpbiI6IjAzLzExLzIwMjQgMTM6MzE6MDAiLCJzY29wZSI6WyJvcGVuaWQiLCJwcm9maWxlIiwiZW1haWwiLCJVc2Vyc0FQSSIsIkFjY3JlZGl0YXRpb25BUEkiLCJCaWxsdHJhY2tpbmdBcGkiLCJDb21tZW50QXBpIiwiTm90ZXNBcGkiXSwiYW1yIjpbInB3ZCJdfQ.rRBS-695ziRsJNb1d4eGlostYFdsfOKF3b-Lj_9nUCGdxA95lHlsFxI1Qk-oLrBvNzRDBf_sbKisOQQ3fjw05V3d5flg7FXViUe48ekeDn-dIZWqa33btFT_-6Ukt-4rMjP-ZFSi7FscHiHW1vAbjx8vKAkDrEdhTR1yvLKy2Bnfkocgr225Om-1ATby8lXRy-3Xq1wofjrg25EUfgl7QzPv_s3LK_pT0eS1pdYuEw39UoZB8yWwtzQ4sqhaQihA6b63IBJqDKTO_Mda__dTqQndmheqfFcgZ-VvZF9EWl6_O8fu3g5CmrFXryDFO3vLeovvVm3L_HlDqEkyZUoh9g',
						  'tenant-code':'<?php echo $tenantCode; ?>'
						},
						body: JSON.stringify(imageThumbpayload)
						};
					  fetch(dpUrl,dpoptions)
						.then(response => {
						  if (!response.ok) {
							throw new Error('Network response was not ok');
						  }
						  return response.json();
						})
						.then(data => {
						  console.log('profile image updated successfully');
              })
						.catch(error => {
						  //console.error('There has been a problem with your fetch operation:', error);
						});
				}
				

		  }
		}
        }
        reader.readAsDataURL(file);
      }<?php */?>

$('.edit-profile').on('submit', function(event) {
  $('#updateProfile').attr('disabled','').find('span').show();
	//jQuery('.curl-progress').show().siblings().hide();
	payload = [];
  event.preventDefault();
  
var tags=[];
var allTags='';
var loggedInUserId = localStorage.getItem("logged_in_user");
jQuery('.tags_all > span').each(function(){
	var oldtag=jQuery(this).clone();  
	oldtag.find('span').remove();
	tags.push(oldtag.html());	
	allTags = tags.join();
});
if(allTags){
var tagsdata = {
	  "tabId":null,
  "tabGroupId":null,
  "tabGroupFieldId":null,
  "loggedInUserId": loggedInUserId,
  "profileUserId":"<?php echo $peopleDATA->people->id; ?>",
  "isHeader":true,
  "headerFieldName":"tags",
  "smartDropDownRequest":"",
  "fieldChangeValues":[
  			{
			"oldValue":"",
			"newValue":allTags,
			"primary":false
			}
		],
	"isValueChanged":false
};
payload.push( tagsdata );  
}
  if(jQuery('.firstName').val()!='<?php echo $peopleDATA->people->firstName; ?>'){
	  var newfirstName = jQuery('.firstName').val();
	  	var firstNamedata = {
    "tabId": null,
    "tabGroupId": null,
    "tabGroupFieldId": null,
    "loggedInUserId": loggedInUserId,
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": true,
    "headerFieldName": "firstName",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $peopleDATA->people->firstName; ?>",
        "newValue": newfirstName,
        "primary": false
      }
    ],
    "isValueChanged": false
};

payload.push( firstNamedata );  
  }
  if(jQuery('.middleName').val()!='<?php echo $peopleDATA->people->middleName; ?>'){
	  var newmiddleName = jQuery('.middleName').val();
	  	var middleNamedata = {
    "tabId": null,
    "tabGroupId": null,
    "tabGroupFieldId": null,
    "loggedInUserId": loggedInUserId,
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": true,
    "headerFieldName": "middleName",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $peopleDATA->people->middleName; ?>",
        "newValue": newmiddleName,
        "primary": false
      }
    ],
    "isValueChanged": false
};

payload.push( middleNamedata );  
  }
  if(jQuery('.lastName').val()!='<?php echo $peopleDATA->people->lastName; ?>'){
	  var newlastName = jQuery('.lastName').val();
	  	var lastNamedata = {
    "tabId": null,
    "tabGroupId": null,
    "tabGroupFieldId": null,
    "loggedInUserId": loggedInUserId,
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": true,
    "headerFieldName": "lastName",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $peopleDATA->people->lastName; ?>",
        "newValue": newlastName,
        "primary": false
      }
    ],
    "isValueChanged": false
};

payload.push( lastNamedata );  
  }
  
   <?php  foreach ($peopleDATA->peopleFields as $key => $value) {
     if($value->controlTypeId==11 && in_array($value->id, $profilePayloadFields)){ 
	 $formattedPhoneNumber= preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $value->selectedValue)
	 ?>
  if(jQuery('.phonenumber-<?php echo $key;?>').val()!='<?php echo $formattedPhoneNumber; ?>'){
	  var newPhoneNumber<?php echo $key;?> = jQuery('.phonenumber-<?php echo $key;?>').val();
	  	var PhoneNumberdata<?php echo $key;?> = {
    "tabId": "<?php echo $value->tabId; ?>",
    "tabGroupId": "<?php echo $value->tabGroupId; ?>",
    "tabGroupFieldId": "<?php echo $value->id; ?>",
    "loggedInUserId": loggedInUserId,
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": false,
    "headerFieldName": "",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $formattedPhoneNumber; ?>",
        "newValue": newPhoneNumber<?php echo $key;?>,
        "primary": <?php if($value->isPrimary==1){ echo 'true';}else{echo 'false';}?>
      }
    ],
    "isValueChanged": false
};

payload.push( PhoneNumberdata<?php echo $key;?> );  
  }
   <?php  } 
 } 
?>
     <?php  foreach ($peopleDATA->peopleFields as $key => $value) {
     if($value->controlTypeId==9 && in_array($value->id, $profilePayloadFields)){
	 //$address = json_decode($value->selectedValue,true);
	 ?>
	var keys= [];
var olds=[];
var values=[];
var  oldobj = {};
var  obj = {};
jQuery('.addressGroup<?php echo $key; ?> input').each(function(){
keys.push(jQuery(this).attr('id'));
olds.push(jQuery(this).attr('data-value'));
values.push(jQuery(this).val());
});
for(i = 0 ; i < keys.length && i < olds.length ; i++){
    oldobj[keys[i]] = olds[i];
}
for(i = 0 ; i < keys.length && i < values.length ; i++){
    obj[keys[i]] = values[i];
}
//console.log(obj);
//console.log(oldobj);
if(JSON.stringify(obj)!=JSON.stringify(oldobj)){
	  	var Addressdata<?php echo $key;?> = {
    "tabId": "<?php echo $value->tabId; ?>",
    "tabGroupId": "<?php echo $value->tabGroupId; ?>",
    "tabGroupFieldId": "<?php echo $value->id; ?>",
    "loggedInUserId": loggedInUserId,
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": false,
    "headerFieldName": "",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": JSON.stringify(oldobj),
        "newValue": JSON.stringify(obj),
        "primary": <?php if($value->isPrimary==1){ echo 'true';}else{echo 'false';}?>
      }
    ],
    "isValueChanged": false
};

payload.push( Addressdata<?php echo $key;?> );  
}
   <?php  } 
 }
?>
 


  
/*$.ajax({
    url: window.location.href,
    type: "POST",
    contentType: "application/json",
    data: DPpayload,
    success: function(response) {
        console.log("Profile Picture successfully. Response: ", DPpayload);
		jQuery('.curl-success').show().siblings().hide();
		
    },
 });*/
 $.ajax({
    url: window.location.href,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(payload),
    success: function(response) {
       // console.log("cURL request executed successfully. Response: ", payload);
       $('#requestSubmitted').modal('show');
       $('#updateProfile').removeAttr('disabled').find('span').hide();
		//jQuery('.curl-success').show().siblings().hide();
		// setTimeout(function() {
    //            jQuery('.curl-success').hide();
    //              window.location.href = "<?php //echo $site_url; ?>/psba/engagifii-profile";
    //          }, 5000);
    },
 });
 

  
  
  //console.log(DPpayload);
  //console.log(payload);
  
 



});
//google places search
   function extractAddressComponent(place, componentType) {
      for (var i = 0; i < place.address_components.length; i++) {
         var component = place.address_components[i];
         for (var j = 0; j < component.types.length; j++) {
            if (component.types[j] === componentType) {
               return component.long_name;
            }
         }
      }
      return '';
   }
function initializeAutocomplete() {
        var input = document.querySelectorAll('.locationName');
        var options = {
            types: ['establishment','geocode']
        };
        input.forEach(function (element) {
            var autocomplete = new google.maps.places.Autocomplete(element, options);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
				console.log(place);
                var parentDiv = element.closest('.address-wrap');
                parentDiv.querySelector('.address').value = extractAddressComponent(place, 'street_number') + ' ' + extractAddressComponent(place, 'route');
                parentDiv.querySelector('.addressLine2').value = extractAddressComponent(place, 'premise') + ' ' + extractAddressComponent(place, 'administrative_area_level_2');
                parentDiv.querySelector('.city').value = extractAddressComponent(place, 'locality');
                parentDiv.querySelector('.state').value = extractAddressComponent(place, 'administrative_area_level_1');
                parentDiv.querySelector('.zipCode').value = extractAddressComponent(place, 'postal_code');
                parentDiv.querySelector('.country').value = extractAddressComponent(place, 'country');
                element.value = place.formatted_address;
            });
        });
    }
    google.maps.event.addDomListener(window, 'load', initializeAutocomplete);
	</script>
<script>
 if (!localStorage.getItem("logged_in_user")) {
 localStorage.setItem("logged_in_user", "<?php echo $peopleDATA->people->id;?>");
}

</script>
  <?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
$payload = json_decode(file_get_contents("php://input"), true);	

$peopleToken = $_SESSION['accesstoken'];
$authentication1 = 'authorization: Bearer '.$peopleToken;
$curl = curl_init();
$url1 ='https://engagifii-preview4-dynamicobjectapproval.azurewebsites.net/api/v1/PeopleApproval/CreateRequest';
  curl_setopt_array($curl, array(
  CURLOPT_URL => $url1,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,  // Set request type to POST
  CURLOPT_POSTFIELDS => json_encode($payload),  // Set the payload data
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "tenant-code:".$tenantCode,
    $authentication1
  ),
));
$response1 = curl_exec($curl);
$updateDATA = json_decode($response1);
//print_r($updateDATA);
// Close the cURL session
curl_close($curl);
}
?>