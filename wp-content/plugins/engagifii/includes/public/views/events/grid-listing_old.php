<?php
  $obj            = new Engagifii_API();
  $collection     = array();
  $date           = date('Y-m-d');
  $options        = get_option( 'ebt_api_settings' );

  $dataResponse = $this->submitApiRequest("public/EventColumnList",array(),"GET",'events');
  $collection   = json_decode($dataResponse['api_response']);
  $remove = array(0,1,3,4,8,9,11,13,15,16,17,18,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,50,51,52,53,54);
  $eventColumns = array_except($collection, $remove);
  $forDatatable[]['data']  = 'name';
  $forDatatable[]['data'] = 'eventType';
  $forDatatable[]['data'] = 'eventSchedule';
  $forDatatable[]['data'] = 'location';
  $forDatatable[]['data'] = 'classes';
	$forDatatable[]['data'] = 'price';
	$forDatatable[]['data'] = 'tags';
	$forDatatable[]['data']  = 'register';

  function array_except($array, $keys){
  	foreach($keys as $key){
        unset($array[$key]);
    }
    return $array;
  }
  //print_r($eventColumns);
?>
<link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.11.2/css/pro.min.css" >
<style type="text/css">
    .location-marker {
    width: 45px;
    height: 45px;
    font-size: 24px;
    text-decoration: none !important;
}

div.dataTables_wrapper div.dataTables_filter {
    text-align: center;
}
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
    justify-content: center;
}

table.dataTable > thead .sorting, table.dataTable > thead .sorting_asc, table.dataTable > thead .sorting_desc, table.dataTable > thead .sorting_asc_disabled, table.dataTable > thead .sorting_desc_disabled {
    padding-left: 29px;
}
/* table.dataTable > thead .sorting::before, table.dataTable > thead .sorting::after, table.dataTable > thead .sorting_asc::before, table.dataTable > thead .sorting_asc::after, table.dataTable > thead .sorting_desc::before, table.dataTable > thead .sorting_desc::after, table.dataTable > thead .sorting_asc_disabled::before, table.dataTable > thead .sorting_asc_disabled::after, table.dataTable > thead .sorting_desc_disabled::before, table.dataTable > thead .sorting_desc_disabled::after {
    opacity: 1;
    font-family:"Font Awesome 5 Pro"
}
table.dataTable > thead .sorting::before, table.dataTable > thead .sorting_asc::before, table.dataTable > thead .sorting_desc::before, table.dataTable > thead .sorting_asc_disabled::before, table.dataTable > thead .sorting_desc_disabled::before {
    content: "\f0d8";
    bottom: 1em;
    right: auto;
    left: 12px;
}
table.dataTable > thead .sorting::after, table.dataTable > thead .sorting_asc::after, table.dataTable > thead .sorting_desc::after, table.dataTable > thead .sorting_asc_disabled::after, table.dataTable > thead .sorting_desc_disabled::after {
    content: "\f0d7";
    bottom: 0.5em;
    right: auto;
    left: 12px;
} */
.dropdown-menu {
	min-width: 15rem;
}
.dropdown-menu label, .dropdown-menu .form-control {
font-size: 13px;	
}
.custom-control-label::after , .custom-control-label::before{
	width: 1rem;
	height: 1rem;
}
.daterangepicker .calendar-table th, .daterangepicker .calendar-table td {
padding: 0.5rem;	
}
</style>
   <div class="container-fluid px-xl-5 engagifii-box py-5">
   <div class="row">
   	<div class="col-md-1 ml-auto text-right" id="filter" >
    	<button type="button" class="btn btn-sm bg-light" id="filter-toggle" ><i class="fas fa-filter"></i></button>
   		 <div class="dropdown-menu dropdown-menu-right pt-0" aria-labelledby="dropdownMenuButton" id="abc">
           <div class="bg-dark py-1 px-3 text-white rounded-top d-flex align-items-center">
           	<i class="fas fa-filter mr-2 small"></i>Filter <button class="btn ml-auto p-0 text-white shadow-none "><small>Clear All</small></button>
           </div>
    <button data-toggle="collapse" href="#collapseExample" type="button" class="d-flex align-items-center justify-content-between btn px-2 btn-block py-0 shadow-none mt-2">Status <i class="fas fa-angle-down"></i></button> 
    <div class="dropdown-divider"></div>
    <div class="collapse px-2" id="collapseExample"> 
    	 <div class="custom-control custom-checkbox">
   	 <input type="checkbox" class="custom-control-input" id="customCheck1">
    	<label class="custom-control-label" for="customCheck1">New</label>
  </div>
    <div class="custom-control custom-checkbox ">
   	 <input type="checkbox" class="custom-control-input" id="customCheck2">
    	<label class="custom-control-label" for="customCheck2">Pending</label>
  </div>
    <div class="custom-control custom-checkbox ">
   	 <input type="checkbox" class="custom-control-input" id="customCheck3">
    	<label class="custom-control-label" for="customCheck3">Upcoming</label>
  </div>
    <div class="custom-control custom-checkbox ">
   	 <input type="checkbox" class="custom-control-input" id="customCheck4">
    	<label class="custom-control-label" for="customCheck4">In-process</label>
  </div>
    </div>
   
       <button data-toggle="collapse" href="#collapseExample1" type="button" class="d-flex align-items-center justify-content-between btn px-2 btn-block py-0 shadow-none mt-2">Created between <i class="fas fa-angle-down"></i></button>  
    <div class="dropdown-divider"></div> 
    <div class="collapse px-2" id="collapseExample1"> 
    <div class="input-group mb-2">
    
    <input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Date" name="dates">
    <div class="input-group-append">
      <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
    </div>
  </div>
    </div>
  <div class="text-center pt-3"><button class="btn btn-sm btn-primary">Apply</button></div>
  </div>
  </div>
   </div>
   	<table id="ebtmaintable" class="table table-bordered nowrap" style="width:100%">
        <thead class="text-white bg-primary">
            <tr>
                <th class="name">Title</th>
                <th class="eventType">Type</th>
                <th class="eventSchedule">Date/Time</th>
                <th class="location text-center">Location</th>
                <th class="classes">Classes</th>
                <th class="price">Price</th>
                <th class="tags">Tags</th>
                <th class="register text-center">Register</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
   </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
      <script>
	  $(document).ready(function() {
    var table = $('#ebtmaintable').DataTable({
		"dom": '<"row"<"col-md-11 col-10"><"top-filter col-md-1 col-2 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
		"bInfo":false,
        "processing": true,
        "searching": true,
        "ordering":false,
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-'
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
              d.action='events';   
            }, 
        },
        createdRow: function (row, data, index) { 
             $(row).addClass( 'bg-white' );
        },
        "columns":<?php echo (json_encode($forDatatable)); ?>,
	 "drawCallback": function( settings ) {
            $('.dataTables_wrapper ').append('<span class="nxt"><i class="fa fa-angle-right"></i></span>');
            $('.dataTables_wrapper ').prepend('<span class="prv"><i class="fa fa-angle-left"></i></span>');
            $('.prv').addClass('disabled');
              var divWidth = parseInt($('.custom-scroll').width());
              var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
              var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
              if(scrollwidth - divWidth - leftwidth == '24')
              {
                  $('.nxt').addClass('disabled');
              }
            $('.nxt').click(function () {
               $('.custom-scroll').animate({
                  scrollLeft: "+=200px"
               }, "slow"); 
               $('.prv').removeClass('disabled'); 
                var divWidth = parseInt($('.custom-scroll').width());
               var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
               var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
               if(scrollwidth - divWidth - leftwidth == '24')
               {
                  $('.nxt').addClass('disabled');
               }
               else{
                $('.nxt').removeClass('disabled');
               }
               if($('.custom-scroll').scrollLeft()==0){
                  $('.prv').addClass('disabled');  
               }
            });  
            $('.prv').click(function () {
               $('.custom-scroll').animate({
                  scrollLeft: "-=200px"
               }, "slow");
                 var divWidth = parseInt($('.custom-scroll').width());
               var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
               var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
               if(scrollwidth - divWidth - leftwidth == '24')
               {
                  $('.nxt').addClass('disabled');
               }
               else{
                $('.nxt').removeClass('disabled');
               }
               if($('.custom-scroll').scrollLeft()==0){
                  $('.prv').addClass('disabled');  
               }
            });  
         },
		});


 $('#ebtmaintable thead tr th:eq(0)').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search" class="form-control form-control-sm search-endorsement" value=""/>' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

});

jQuery('#filter-toggle').on('click', function (e) {
 jQuery(this).next('.dropdown-menu').toggleClass('show');
});
$('input[name="dates"]').daterangepicker();
	  </script>

