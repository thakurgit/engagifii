<link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.11.2/css/pro.min.css" >
    <style>

	div.dataTables_wrapper div.dataTables_filter {

	text-align: center;

}

	div.dataTables_wrapper div.dataTables_paginate ul.pagination {

	justify-content: center;

}

table.dataTable > thead .sorting, table.dataTable > thead .sorting_asc, table.dataTable > thead .sorting_desc, table.dataTable > thead .sorting_asc_disabled, table.dataTable > thead .sorting_desc_disabled {

	padding-left: 29px;

}



.location-marker {

	width: 45px;

	height: 45px;

	font-size: 24px;

	text-decoration: none !important;

}

#example_length {

	transform: translateY(42px);

}
.event-detail .nav-pills .nav-link {
	border-bottom: 3px solid transparent;
}
.event-detail .nav-pills .nav-link.active, .event-detail  .nav-pills .show > .nav-link {
	border-color:  var(--blue);
    color: var(--blue) !important;
	background: none !important;
}
.event-detail .btn-link {
	text-decoration: none !important;
}
.event-detail button:not(.collapsed) i {
	transform:rotate(180deg)
}
tbody td, thead th {
vertical-align:middle !important;	
}
.class-title {
line-height:1;	
}

	</style>
<?php
  //   $id         = $_REQUEST['id'] ?? null;
  //   $obj            =  new Engagifii_API();
  //   $response       =  $obj->getEventDetailsByID($id);
  //  //print_r($response);

?>
   <div class="container py-5">

		<div class="border row ">
        	<div class="col-12 py-3">
            	<div class="row">
                  <div class="col-10 d-flex align-items-center">
                      <img src="<?php echo $response->imageUrl; ?>" class="img-fluid" alt="" style="max-width:56px">	
                  <div class="px-2">
                      <h5 class="font-weight-bold mb-3"><?php echo $response->name; ?></h5>
                      <p class="mb-2"><strong>Event Type</strong>: <?php echo $response->eventType; ?>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Credit Hours</strong>: <?php echo ''; ?></p>
                      <?php if(is_array($response->tags) && count($response->tags)>0) {
                        ?>
                        <div class="pt-2">
                            <span>Tag(s): </span>
                            <span class="pl-1 pr-1"><i class="fa fa-tags"></i> <?php echo count($response->tags);  ?></span>
                            <?php
                                foreach ($response->tags as $key => $value) {
                                    ?>
                                        <span class="border round-tag py-2 px-4  text-capitalize"><?php echo $value; ?></span>
                                    <?php
                                }
                             ?>
                        </div> 
                        <?php
                                }
                        ?>
                  </div>
                  </div>
                  <?php
                    if($response->eventRegistrationState == 'RegistrationOn'){
                  ?>
                  <div class="col-2 text-right">
                      <p><button class="btn pr-0"><i class="fal fa-times"></i></button></p>
                      <a href="" class="btn btn-primary">Register</a>
                  </div>
                  <?php
                    }
                  ?>    
                </div>
            </div>
            <div class="bg-light py-3 col-12 border-top event-detail">
            	<div class="bg-white border">
                	<ul class="nav nav-pills mb-0 border-bottom" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link rounded-0 px-0 mx-3 text-dark active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">General</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link rounded-0 px-0 mx-3 text-dark" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Event Sessions</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link rounded-0 px-0 mx-3 text-dark" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Speakers</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link rounded-0 px-0 mx-3 text-dark" id="pills-classes-tab" data-toggle="pill" href="#pills-classes" role="tab" aria-controls="pills-contact" aria-selected="false">Classes</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link rounded-0 px-0 mx-3 text-dark" id="pills-materials-tab" data-toggle="pill" href="#pills-materials" role="tab" aria-controls="pills-materials" aria-selected="false">Event Materials</a>
  </li>
</ul>
					<div class="p-3">
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
  	<div class="accordion" id="accordionExample">
  <div class="card  mb-3 border-bottom">
    <div class="card-header p-0" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left text-dark d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Details
          <i class="fal fa-chevron-down"></i>
        </button>
      </h2>
    </div>
	
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <p><strong>Event Location</strong></p>
        <?php echo $response->location; ?>
        <p><strong>Price</strong></p>
        <p><?php echo $response->defaultPrice; ?></p>

        <p><strong>Description</strong></p>
      <?php echo $response->description;  ?>
        <p><strong>Organizer</strong></p>
        <div class="row">
        	<div class="col-md-6 col-xl-4 mb-3">
            	<div class="border p-2 rounded  d-flex align-items-start">
                	<img src="ico.png" alt="" class="img-fluid" style="max-width:30px">
                    <div class="text-right">
                    	<strong>Jane Asgenes</strong><br>
						<strong>Position</strong>: Instructor<br>
						<strong>Department</strong>: Community Education
                    </div>	
                </div>
            </div>
        	<div class="col-md-6 col-xl-4 mb-3">
            	<div class="border p-2 rounded  d-flex align-items-start">
                	<img src="ico.png" alt="" class="img-fluid" style="max-width:30px">
                    <div class="text-right">
                    	<strong>Eric Aamlid</strong><br>
						<strong>Position</strong>: Special Ed Behavior Para<br>
						<strong>Department</strong>: Community Education
                    </div>	
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card  mb-3 border-bottom">
    <div class="card-header p-0" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left text-dark d-flex justify-content-between align-items-center collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Default Registration Settings
          <i class="fal fa-chevron-down"></i>
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">

        <div class="alert alert-success" role="alert">
          This event requires registration
        </div>
        
        <p class="font-weight-bold">Registration Limit</p>
        <p><?php echo $response->registrantsCapacity; ?></p>
        

        <p class="font-weight-bold">Registration Dates</p>
        <p><?php echo date('D M d, h:i A', strtotime($response->registrationStartFrom)); ?></p>


         <p class="font-weight-bold">Pre-reqisites</p>
        <p>Events</p>
        
        <ul class="list-group col-md-6">
            <li class="list-group-item">
              <i class="fas fa-calendar"></i> Event title
            </li>
            <li class="list-group-item">
              <i class="fas fa-calendar "></i> Event title
            </li>
          </ul>
      </div>
    </div>
  </div>
  <div class="card  mb-3 border-bottom">
    <div class="card-header p-0" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left text-dark d-flex justify-content-between align-items-center collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Schedule
          <i class="fal fa-chevron-down"></i>
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
</div>
  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
  
  	<table id="session" class="table table-hover table-bordered light-background nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Session Type</th>
                <th>Price</th>
                <th>Credit Hours</th>
                <th>Date/Time</th>
                <th class="text-center">Room</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr>
            <tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr>
            <tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr>
            <tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr>
            <tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr><tr>
                <td>MSBA Session - 1</td>
                <td>Exhibitor</td>
                <td>$200.00</td>
                <td>50</td>
                <td>Nov 06, 2020<br>at 08:00 AM - 09:00 AM</td>
                
                <td>100</td>
            </tr>
            
            
        </tbody>
    </table>
  
  </div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
  
    	<table id="people" class="table table-hover table-bordered light-background nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Speakers</th>
                <th>Organizations</th>
                <th>Sessions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
            </tr>
            <tr>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
            </tr>

            <tr>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
            </tr>

            <tr>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
            </tr>

            <tr>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
            </tr>

            <tr>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
                <td><img src="profile.png" style="max-width:30px" alt="" class="img-fluid mr-2">Jana Aagenes</td>
            </tr>
            
            
        </tbody>
    </table>


  </div>
  <div class="tab-pane fade" id="pills-classes" role="tabpanel" aria-labelledby="pills-classes-tab">
  
    	<table id="classes" class="table table-hover table-bordered light-background nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Classes</th>
                <th>Duration</th>
                <th>Class Dates</th>
                <th>Instructors</th>
                <th>Endorsements</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:40px; flex: 0 0 40px" alt="" class="img-fluid mr-2">
                <div>ESOL Course<br><small class="text-muted">Syntex & Phonetics<br>Jan 24, 2020 - Feb 24, 2020<br>Every Thu at 00:01AM - 02:00 AM</small></div></td>
                <td>1 Month</td>
                <td>4 Class Dates</td>
                <td>2 Instructors</td>
                <td>4 Endorsements</td>
            </tr>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:40px; flex: 0 0 40px" alt="" class="img-fluid mr-2">
                <div>ESOL Course<br><small class="text-muted">Syntex & Phonetics<br>Jan 24, 2020 - Feb 24, 2020<br>Every Thu at 00:01AM - 02:00 AM</small></div></td>
                <td>1 Month</td>
                <td>4 Class Dates</td>
                <td>2 Instructors</td>
                <td>4 Endorsements</td>
            </tr>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:40px; flex: 0 0 40px" alt="" class="img-fluid mr-2">
                <div>ESOL Course<br><small class="text-muted">Syntex & Phonetics<br>Jan 24, 2020 - Feb 24, 2020<br>Every Thu at 00:01AM - 02:00 AM</small></div></td>
                <td>1 Month</td>
                <td>4 Class Dates</td>
                <td>2 Instructors</td>
                <td>4 Endorsements</td>
            </tr>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:40px; flex: 0 0 40px" alt="" class="img-fluid mr-2">
                <div>ESOL Course<br><small class="text-muted">Syntex & Phonetics<br>Jan 24, 2020 - Feb 24, 2020<br>Every Thu at 00:01AM - 02:00 AM</small></div></td>
                <td>1 Month</td>
                <td>4 Class Dates</td>
                <td>2 Instructors</td>
                <td>4 Endorsements</td>
            </tr>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:40px; flex: 0 0 40px" alt="" class="img-fluid mr-2">
                <div>ESOL Course<br><small class="text-muted">Syntex & Phonetics<br>Jan 24, 2020 - Feb 24, 2020<br>Every Thu at 00:01AM - 02:00 AM</small></div></td>
                <td>1 Month</td>
                <td>4 Class Dates</td>
                <td>2 Instructors</td>
                <td>4 Endorsements</td>
            </tr>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:40px; flex: 0 0 40px" alt="" class="img-fluid mr-2">
                <div>ESOL Course<br><small class="text-muted">Syntex & Phonetics<br>Jan 24, 2020 - Feb 24, 2020<br>Every Thu at 00:01AM - 02:00 AM</small></div></td>
                <td>1 Month</td>
                <td>4 Class Dates</td>
                <td>2 Instructors</td>
                <td>4 Endorsements</td>
            </tr>
            
            
        </tbody>
    </table>


  </div>
  <div class="tab-pane fade" id="pills-materials" role="tabpanel" aria-labelledby="pills-materials-tab">
  
    	<table id="materials" class="table table-hover table-bordered light-background nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Documents</th>
                <th>File Size</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="d-flex align-items-center class-title"><img src="profile.png" style="max-width:30px; flex: 0 0 30px" alt="" class="img-fluid mr-2">Registration Form.docx
                </td>
                
                <td>1 MB</td>
            </tr>
            
            
        </tbody>
    </table>


  </div>
</div>
                    </div>
                </div>	
            </div>
        </div>
   </div>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->

      <script>

	  $(document).ready(function() {

    $('#example').DataTable({

		"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',

		 "bInfo" : false,

		 'aoColumnDefs': [{

        'bSortable': false,

        'aTargets': [-1] /* 1st one, start by the right */

    }]

		});
		
		 $('#session').DataTable({
		"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',
		 'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1] /* 1st one, start by the right */
    }]
		});

    $('#people').DataTable({

		"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',

		 "bInfo" : false,

		

		});
		
		$('#classes').DataTable({

		"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',
		 "bInfo" : false,

		

		});
		
		$('#materials').DataTable({

		"dom": '<"row"<"col-sm-12"f">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 p-4"l><"col-sm-7 "p">>',

		 "bInfo" : false,

		

		});

} );

	  </script>

