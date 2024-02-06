<?php
	function view_mode( $atts ) {	
	 $values = shortcode_atts(array(
        'search' => '',
		'placeholder' =>'',
        'module' =>''        
    ),$atts); 
	

	$html = '<div class="container-fluid pb-2"><div class="row">';
	$more='';
    if($values['module']!="endorsement"){
	if ($values["search"]=='on') {
        $more = 'col-md-6';
    $html .='<div class="col-md-6 col-12">
	<div class="new-search form-inline ">
	<input type="text" name="search"  placeholder="'.$values['placeholder'].'" class="bg-light form-control list-search mr-2" >
	<button type="submit" class=" btn btn-primary list-search-btn">Search</button>
	</div>
    <!--/*<form action="calendar-search.php" class="calendarsearch-form" method="POST">

<div class="apply-search form-inline d-none">
	<div class="form-group mr-2 position-relative">
    <input type="text" name="search" id="calendar-search" placeholder="'.$values["placeholder"].'" class="bg-light pr-5 form-control" >
    <button type="button" class="clear-search btn position-absolute p-2 shadow-none" style="right:0; top:4px; display:none"><i class="far fa-times"></i></button>
</div>
    <input type="submit" value="Search" class="btn btn-primary" id="apply-filter-search-cal" />
</div>
</form>*/-->
	
    </div>';
    }
    $html .='<div class="col-12 '.$more.' text-center text-lg-right view-mode d-flex align-items-center justify-content-end">
    <div class="flt-btn mr-3 mr-xl-5 " style="display:none">
        
        </div>
    	<div class="btn-group view-m" role="group">
        			 <button type="button" id="calendar" class="btn border  btn-primary shadow-none" aria-pressed="false"><i class="fal fa-calendar-alt mr-2"></i></i>Calendar</button>
                  <button type="button" id="list" class="btn btn-light border shadow-none" aria-pressed="false"><i class="fal fa-list mr-2"></i> List</button> 
                 
        </div>
        
       
    </div></div></div>';
}else{
    $html .='<div class="col-12 '.$more.' text-center text-lg-right view-mode d-flex align-items-center justify-content-end">
    <div class="flt-btn mr-3 mr-xl-5 " style="display:block">
        
        </div>
    	<div class="btn-group view-m" role="group">
        		 <button type="button" id="list" class="btn border  btn-primary shadow-none" aria-pressed="false"><i class="fal fa-list mr-2"></i> List</button> 
                 
        </div>
        
       
    </div></div></div>';
}

    return $html;

}
add_shortcode( 'view_mode', 'view_mode' );

function dd_header ($title, $search='') {
      $dd_header = '<div class="dropdown-menu shadow-lg dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center border-bottom mb-0 pb-3">'.$title.'</h6>';
	  if($search){
		  $dd_header ='<div class="dropdown-menu shadow-lg dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-3">'.$title.'</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown" placeholder="'.$search.'"/></div>';
	  }
      return $dd_header;
}


function dashboard_nav() {	
$site_url = site_url();
global $post;
    $post_slug = $post->post_name;
		$active = 'active';
		 $html='<style>
.dashboard-nav a {
	border-bottom: 4px solid transparent;	
	transition:0.3s all ease-in-out;
	color:#333;
}
.dashboard-nav a:hover, .dashboard-nav a.active {
	border-color: #2568EF;	
	color:#2568EF;
}
  </style>';
    $html .= '<div class="container-fluid mb-4">
	<div class="d-flex justify-content-center border-top border-bottom dashboard-nav h5">
    	<a href="'.$site_url.'/engagifii-profile" class="py-3 mx-4 ' . ($post_slug == 'engagifii-profile' ? $active : '') . '">My Profile</a>
        <a href="'.$site_url.'/engagifii-profile/my-transcript" class="py-3 mx-4 ' . ($post_slug == 'my-transcript' ? $active : '') . '">My Transcript</a>
        <a href="'.$site_url.'/engagifii-profile/events" class="py-3 mx-4 ' . ($post_slug == 'events' ? $active : '') . '">Events</a>
        
    </div>
</div>'; //<a href="'.$site_url.'/engagifii-profile/my-transactions" class="py-3 mx-4 ' . ($post_slug == 'my-transactions' ? $active : '') . '">Accounting Details</a>

    return $html;

}
add_shortcode( 'dashboard_nav', 'dashboard_nav' );


