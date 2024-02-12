<?php
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
include 'dashboard-api.php';
if($peopleDATA->isError==true) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
}
include 'sidebar_nav.php'; 
?>
   <style>
/*.accordion .card-header button::after {
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
}*/
.transcaript-tabs button.nav-link {
	border-bottom: 4px solid transparent !important;
	padding-left: 0 !important;
	padding-right: 0 !important;
}
.transcaript-tabs button.nav-link.active, .transcaript-tabs button.nav-link:hover {
	border-bottom:  4px solid #2568ef !important;
}
.tooltip > div {
	max-width: 350px;
}
  </style>
  <div class="container-fluid">
  <div class="border rounded">
  	<ul class="nav nav-tabs transcaript-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link px-0 mx-3 border-0 bg-transparent active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Badges</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link px-0 mx-3 border-0 bg-transparent" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Credits Earned</button>
  </li>
 
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane p-3 fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  	<div class="d-flex justify-content-between align-items-end mb-4">
  	<div><h6>Badge Statistics</h6>
    <p class="mb-0">Total Credit Earned as of <?php echo date("d M Y");?>: <strong>200</strong><br>Total Credit Earned in <?php echo date("Y");?>: <strong>80</strong></p>
    </div>
    	<a href="" class="btn btn-primary btn-sm"><span class="mr-1"><svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.4921 0.484375H0.3302V12.8754H1.24805V1.40223H10.4921V0.484375Z" fill="white"/>
<path d="M11.1884 12.2853H9.70539V13.7683L11.1884 12.2853Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.18895H2.10034V14.5144H8.78753V11.3675H12V2.18895ZM8.65641 3.76241H5.37838V4.74582H8.65641V3.76241ZM10.2954 5.40143H3.6738V6.38484H10.2954V5.40143ZM3.6738 6.97489H10.2954V7.9583H3.6738V6.97489ZM10.2954 8.61391H3.6738V9.59732H10.2954V8.61391Z" fill="white"/>
</svg></span> All Reports</a>
    </div>
    <ul class="nav nav-pills justify-content-center session-tab nav-fill" id="pills-tab" role="tablist">
    <?php 
$tabs = ['Registered','In Progress','Not Started','Earned','Awarded'];
$tooltip=['This count indicates the total number of certifications a person is registered in.','This count indicates that out of all certifications a person is registered in, how many certifications they have started to earn credits for, by registering in the course(s) associated with the certifications.','This count indicates that out of all certifications a person is registered in, how many certifications they have not yet started because they are not registered in course(s) associated with the certifications.','This count indicates the number of certifications for which a person has met the certification criteria for but the certification is not awarded yet. An admin needs to manually award the certifications in such cases.','This count indicates the number of certifications that have been awarded to a person.'];
$tabColor = ['#2568EF','#FFA92B','#DC3545','#05C86A','#138600'];
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
<style>
.session-tab li:nth-of-type(<?php echo $tabNo+1; ?>) a:hover::before, .session-tab li:nth-of-type(<?php echo $tabNo+1; ?>) a.active::before{
position: absolute;
content: '';
width: 100%;
height: 100%;
background: <?php echo $tabColor[$tabNo]; ?>;	
opacity:0.2;
left:0;
top:0;
z-index:-1;
}
</style>
<li class="nav-item mb-3 mr-3 rounded-1" role="presentation">
            <a style="border-color: <?php echo $tabColor[$tabNo]; ?>!important;" data-tab="<?php echo preg_replace('/\s+/', '', strtolower($tab));?>" class="bg-transparent position-relative border nav-link text-left py-3 <?php if($tabNo==0){ echo 'active';} ?>" data-toggle="pill" data-target="#tab-1<?php //echo $tabNo; ?>" href="" role="tab" aria-controls="home" aria-selected="true"><span class="d-block h2 mb-0" style="color:<?php echo $tabColor[$tabNo]; ?> !important;"><?php echo $tabCount[$tabNo]; ?></span><small class="text-dark"><?php echo $tab; ?> <span class="ms-1"  data-toggle="tooltip" data-placement="top"  data-title="<?php echo $tooltip[$tabNo]; ?>"><i class="far fa-info-circle"></i></span> </small></a>
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
            	<button class="btn btn-link d-flex w-100  text-left  <?php if($i!=0){ echo 'collapsed1'; }?>" type="button" data-toggle="collapse" data-target="#collapseOne<?php echo $i;?>" aria-expanded="true" aria-controls="collapseOne">
        <?php echo $award->name; ?><span class="ml-auto"><?php echo $status; ?> <span class="btn btn-primary btn-sm py-0 px-2 ms-1"><span class="mr-1"><svg width="10" height="12" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.4921 0.484375H0.3302V12.8754H1.24805V1.40223H10.4921V0.484375Z" fill="white"/>
<path d="M11.1884 12.2853H9.70539V13.7683L11.1884 12.2853Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.18895H2.10034V14.5144H8.78753V11.3675H12V2.18895ZM8.65641 3.76241H5.37838V4.74582H8.65641V3.76241ZM10.2954 5.40143H3.6738V6.38484H10.2954V5.40143ZM3.6738 6.97489H10.2954V7.9583H3.6738V6.97489ZM10.2954 8.61391H3.6738V9.59732H10.2954V8.61391Z" fill="white"/>
</svg></span>Report</span></span>
      </button>
            </h2>
            </div>
           <!-- <div id="collapseOne<?php echo $i;?>" class="accordion-collapse collapse <?php if($i==0){ echo 'show1'; }?>" data-parent="#awrad-accordion">
      <div class="card-body">
       
      </div>
    </div>-->
        </div>
	<?php $i++; } ?>
   
</div>
  </div>
  
</div>
  </div>
  <div class="tab-pane p-3 fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    	<div><h6>Credit Earned Statistics</h6>
    <p class="mb-0">Total Credit Earned as of <?php echo date("d M Y");?>: <strong>200</strong><br>Total Credit Earned in <?php echo date("Y");?>: <strong>80</strong></p>
    </div>

  <?php //echo do_shortcode('[courses-list-ByPerson]');
  include $this->basePath.'includes/public/views/courses/grid-listingByPerson.php'; ?>
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
