<?php
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
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

$authenticationAward = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDU5MjEwNDAsImV4cCI6MTcwNTkyODI0MCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA1OTIxMDQwLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjAxLzIyLzIwMjQgMTA6NTQ6NDciLCJjdXJyZW50LWxvZ2luIjoiMDEvMjIvMjAyNCAxMDo1NzoyMCIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.g-LxFKPj00MXMUkupYlN1trw3i_mL-0u3kGlQ31qli5VzQI-NFlnX5skewt-9OJKPOfItfMbM8Rdt5_qf4noFk3WEExwGjSHbY1C8RJRqCFbf9WybM2kqWk9A3YFy9ZbZD5XUd4lojb3cZQdSpMdcBvlMhTTcf33xhgsrf4Uy2BT1W8SP4ukZb6AqvkSeCtCrLEaUoWwn6kGcFjiOxycbdn1x9Gzy5C0K6eUoWi1Q2oeobDhgKXiRVSXuFn7c62N0U37t-1AnD2xXDPaJjm-mpWmx1OX1ZZqhb73Tolt_S5dwIzigI7xhSzviecp90mrE3Z6IkHQmt11nkjSqnLHEQ';

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
//$profileId= $peopleDATA->people->id;
$profileId = '5e7f3fed-c3f8-4b38-a25f-4f6a32511337';
// Close the cURL session
//curl_close($curl);
$current_user_posts = get_posts($args);
$total              = count($current_user_posts);
$awardURL= 'https://engagifii-preview4-tna.azurewebsites.net/api/v1/Awards/AwardsCertificationsByPeople/'.$profileId;
$awardpayload = '{"itemCount":10,"pageNumber":1,"sortBy":"name","sortDirection":"asc","filterBody":{}}';
curl_setopt_array($curl, array(  
  CURLOPT_URL => $awardURL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,  // Set request type to POST
  CURLOPT_POSTFIELDS => $awardpayload,  // Set the payload data
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",   
    "tenant-code:accg", 
    $authenticationAward
  ),
));
$responseAward = curl_exec($curl);
$awardData = json_decode($responseAward);
// Close the cURL session
curl_close($curl);
//print_r($awardData);



if($peopleDATA->isError==true) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
} else {
?>
 <!--<link href="https://site-assets.fontawesome.com/releases/v6.2.1/css/all.css" rel="stylesheet" >-->
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
.transcaript-tabs button.nav-link {
	border-bottom: 4px solid transparent !important;
	padding-left: 0 !important;
	padding-right: 0 !important;
}
.transcaript-tabs button.nav-link.active, .transcaript-tabs button.nav-link:hover {
	border-bottom:  4px solid #2568ef !important;
}
  </style>
  <div class="d-flex justify-content-end px-3 mb-3">
    
    <a class="btn btn-outline-dark" href="<?php echo esc_url(wp_logout_url('')); ?>"><?php esc_html_e('Logout', 'wpfep'); ?></a>
    
</div>
<?php echo do_shortcode('[dashboard_nav]'); ?>
  <div class="container-fluid">
  <div class="border rounded">
  	<ul class="nav nav-tabs transcaript-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link px-0 mx-3 border-0 bg-transparent active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Awards</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link px-0 mx-3 border-0 bg-transparent" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Credits Earned</button>
  </li>
 
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane p-3 fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  	<h6>Certification Statistics</h6>
    <ul class="nav nav-pills justify-content-center session-tab nav-fill" id="pills-tab" role="tablist">
    <?php 
$tabs = ['Registered','In Progress','Not Started','Earned','Awarded'];
$tooltip=['This count indicates the total number of certifications a person is registered in.','This count indicates that out of all certifications a person is registered in, how many certifications they have started to earn credits for, by registering in the course(s) associated with the certifications.','This count indicates that out of all certifications a person is registered in, how many certifications they have not yet started because they are not registered in course(s) associated with the certifications.','This count indicates the number of certifications for which a person has met the certification criteria for but the certification is not awarded yet. An admin needs to manually award the certifications in such cases.','This count indicates the number of certifications that have been awarded to a person.'];
$tabCount=array_fill(0, count($tabs), 0);
$tabCount[array_search('Registered', $tabs)] = count($awardData->result);
foreach ($awardData->result as $award){
switch ($award->status) {
case 5:
$tabCount[array_search('Awarded', $tabs)]++;
break;
case 1:
$tabCount[array_search('Not Started', $tabs)]++;
break;
case 2:
$tabCount[array_search('In Progress', $tabs)]++;
break;
case 4:
$tabCount[array_search('Earned', $tabs)]++;
break;
}
}


//for($i = 0; $i < $length; $i++){ 
$tabNo=0;
foreach($tabs as $tab){?>
<li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
            <a data-tab="<?php echo preg_replace('/\s+/', '', strtolower($tab));?>" class="border nav-link text-left py-3 <?php if($tabNo==0){ echo 'active';} ?>" data-toggle="pill" data-target="#tab-1<?php //echo $tabNo; ?>" href="" role="tab" aria-controls="home" aria-selected="true"><span class="d-block h2 mb-0"><?php echo $tabCount[$tabNo]; ?></span><small><?php echo $tab; ?> <span class="ms-1"  data-toggle="tooltip" data-placement="top" data-title="<?php echo $tooltip[$tabNo]; ?>"><i class="far fa-info-circle"></i></span> </small></a>
        </li>	 
<?php $tabNo++; } ?>
       
    </ul>

    <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="tab-1<?php //echo $tabNo; ?>" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
  	<div class="accordion award-list" id="awrad-accordion">
    <?php $i=0; $status=''; $class=''; foreach ($awardData->result as $award){
		if($award->status==1){
			$status = '<small class="text-danger"><i class="fa fa-times-circle mr-1"></i>Not started</small>';
			$class=preg_replace('/\s+/', '', strtolower($tabs[2]));
		} else if($award->status==5){
			$status = '<small class="text-success"><i class="fa fa-check-circle mr-1"></i>Awarded on '.$award->grantedDateString.'</small>';
			$class=preg_replace('/\s+/', '', strtolower($tabs[4]));
		} else if($award->status==2){
			$status = '<small class="text-warning"><i class="fa fa-clock mr-1"></i>In Progress ('.$award->certificationProgress.'%)</small>';
			$class=preg_replace('/\s+/', '', strtolower($tabs[1]));
		} else if($award->status==4){
			$status = '<small class="text-success"><i class="fa fa-check-circle mr-1"></i>Earned</small>';
			$class=preg_replace('/\s+/', '', strtolower($tabs[3]));
		} 
		 ?>
		<div class="card mb-4 border rounded-sm " data-content="<?php echo $class; ?>">
        	<div class="card-header position-relative p-0" id="headingTwo">
        	<h2 class="mb-0">
            	<button class="btn btn-link d-flex w-100 pr-5 text-left  <?php if($i!=0){ echo 'collapsed1'; }?>" type="button" data-toggle="collapse" data-target="#collapseOne<?php echo $i;?>" aria-expanded="true" aria-controls="collapseOne">
        <?php echo $award->name; ?><span class="ml-auto"><?php echo $status; ?> <span class="btn btn-primary btn-sm py-0 px-2 ms-1">Report</span></span>
      </button>
            </h2>
            </div>
            <div id="collapseOne<?php echo $i;?>" class="accordion-collapse collapse <?php if($i==0){ echo 'show1'; }?>" data-parent="#awrad-accordion">
      <div class="card-body">
       
      </div>
    </div>
        </div>
	<?php $i++; } ?>
   
</div>
  </div>
  
</div>
  </div>
  <div class="tab-pane p-3 fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  <?php echo do_shortcode('[courses-list-ByPerson]'); ?>
  </div>
</div>

  </div>
</div>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
 var activeTab = $(e.target).attr('data-tab');
 $('#awrad-accordion > .card') .each(function(){
	if($(this).attr('data-content')==activeTab){
		$(this).removeClass('d-none');
	}else{
		$(this).addClass('d-none');
	}
	
 });
 if($(e.target).attr('data-tab')=='registered'){
	$('#awrad-accordion > .card').removeClass('d-none'); 
 }
});

</script>
<?php } ?>