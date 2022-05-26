	<?php
	function view_mode( $atts ) {	
	 $values = shortcode_atts(array(
        'search' => '',
		'placeholder' =>'Search...'
    ),$atts); 
	

	$html = '<div class="container-fluid pb-4"><div class="row">';
	$more='';
	if ($values["search"]=='on') {
		$more = 'col-md-6';
    $html .='<div class="col-md-6 col-12">
    <form action="calendar-search.php" class="calendarsearch-form" method="POST">

<div class="apply-search form-inline">
	<div class="form-group mr-2 position-relative">
    <input type="text" name="search" id="calendar-search" placeholder="'.$values["placeholder"].'" class="bg-light pr-5 form-control" >
    <button type="button" class="clear-search btn position-absolute p-2 shadow-none" style="right:0; top:4px; display:none"><i class="far fa-times"></i></button>
</div>
    <input type="submit" value="Search" class="btn btn-primary" id="apply-filter-search-cal" />
</div>
</form>
	
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

    return $html;

}
add_shortcode( 'view_mode', 'view_mode' );


