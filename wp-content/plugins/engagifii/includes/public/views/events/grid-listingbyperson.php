<style>
 #filter-content-wrapper {
    display: none;
}

#filter-loader {
    text-align: center;
    padding: 20px;
}

.filter-content {
    display: none;
}
</style>
<?php
$options = get_option('ebt_api_settings');
	$columns='';
	$columnNames=[];
	if (!empty(EVENTS_COLS) && isArrayOfJsonStrings(EVENTS_COLS)) {
		  $columns = convertToObjectArray(EVENTS_COLS);
		  $columnNames = extractColNames(EVENTS_COLS);
	}else{
	  $dataResponse = $this->submitApiRequest("Public/EventColumnList",array(),"GET",'event');
		if(!$dataResponse['api_response']){
			echo '<h5 class="text-center text-danger"><strong><em>No data found! Please contact website admin.</em></strong><h5>';
			return;
		}
		$columns   = json_decode($dataResponse['api_response']);
	}
$default_length = '10';
$calendar_view = false;
if(isset($attr['records'])){
  $default_length = $attr['records'];
}
if(isset($attr['calendar'])){
  $calendar_view = $attr['calendar'];
}

    $obj =  new Engagifii_API();

    /* added static column list by vpsc */
    /*$dataResponse = $this->submitApiRequest("Public/EventColumnList",array(),"GET",'event');
	//print_r($dataResponse);
	//die;
if(!$dataResponse['api_response']){
	echo '<h5 class="text-center text-danger"><strong><em>No data found! Please contact website admin.</em></strong><h5>';
	return;
}
    $collection   = json_decode($dataResponse['api_response']);
    $options = get_option('ebt_api_settings');
    $columnNames = $options['events_visible_column_list'];
	$columnNames   =  array();
	if($options['events_visible_column_list']){
  	  $columnNames = $options['events_visible_column_list'];
	}*/

  $enabled_modules = get_option('engagifii_enabled_modules', array()); 
   if (!in_array('events', $enabled_modules)) {   
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}
    
?>

<div class="containerEngagii">
<?php
  if($calendar_view){
	  $placeholder_text = 'Search by event name';
echo do_shortcode('[view_mode search="on" placeholder="'.$placeholder_text.'"]');
?>
<div class="container-fluid">
  <?php echo do_shortcode('[events-calendar]'); ?>
</div>
<?php
  } else {
	echo '<div class="container-fluid pb-2"><div class="row"><div class="col-6"><div class="d-flex align-items-center"><h4 class="mb-0 mr-3"><button type="button" title="Refresh Downloads" class="refresh btn shadow-none p-2 mr-2"> <i class="fas fa-sync"></i></button><img src="'.ENGAGIFII_ASSETS_URL.'/images/Events.png" class="img-fluid img-icon-lg" alt="award-icon"></h4><h5 class="mb-0">Events</h5></div></div><div class="col-6 text-center text-lg-right view-mode d-flex align-items-center justify-content-end"><div class="flt-btn mr-3 " style="display:block"></div></div></div>';  
  }
?>


</div>
<?php


ob_start();
?>
<div id="filter-content-wrapper" style="display: none;">
<div id="filter-loader" class="text-center py-3">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
<div class="filter-content" id="filterdp1">
	<div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative bg-light border"><i class="far fa-filter click-filter"></i><span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span></div>
  <div class="filter-border">
  <div class="filter-area d-none" id="filterdp">
    <div class="Engagiirow filter-top-bg col-sm-12 py-2 bg-dark text-white">
      <div class="row">
      <div class="col-6 text-left">
        <span class="filter-title">
          <i class="far fa-filter mr-2"></i> Filter
          <span id="blockedchecked"></span> 
        </span>
      </div>
      <div class="col-6 text-right">
        <span class="clear-all" id="clear-all"> Clear All</span>
      </div>
      </div>
    </div>
    <div class="col-sm-12" id="test">
      <input type="hidden" id="isApplyACtive" value="0">
      <?php if(in_array('startDateTime', $columnNames)) { ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Event Date <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative pb-2">
          <input type="text" name="createdbetween"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>
      <?php }
      if(in_array('eventType', $columnNames) && array_search('eventType', $columnNames)){
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Event Types <i class="far fa-angle-down"></i></div>
        <div class="content-area eventType-filter d-none"><ul class="list-group m-0">
            <div class="loaders text-center py-3">
              <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
            </div>
          <?php
		 
          ?>  
        </ul></div>
      </div>
      
      <?php
        }
      
      
      if(in_array('city', $columnNames)) {
         //if(array_search('location', $columnNames)){
          ?>
          <div class="filter-list border-bottom">
            <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Location <i class="far fa-angle-down"></i></div>
            <div class="content-area city-filter d-none"><ul class="list-group m-0">
            <div class="loaders text-center py-3">
              <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
            </div>
              <?php
		
              ?>  
            </ul></div>
          </div>
          
          <?php
          // }
              }
    if (in_array('tags', $columnNames) && array_search('tags', $columnNames)) {
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Tags <i class="far fa-angle-down"></i></div>
        <div class="content-area tags-filter d-none"><ul class="list-group m-0">
            <div class="loaders text-center py-3">
              <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
            </div>
          <?php
		
          ?>  
        </ul></div>
      </div>
      
    <?php } 
    
    ?>
      
    </div>
          <div class="apply-filter">
        <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data">Apply 
          <span id="countFilterResult"></span>
        </button>
      </div>

  </div>
</div>
</div>
    </div>
<?php
$filter_content =ob_get_contents();
ob_end_clean();
$filter_content = removeWhitespace($filter_content);
$title_key = -1;
?>

<?php 
$dt_class=' ';
$dt_respnsive = '';
$dt_darktheme = '';
if (array_key_exists("dt_responsive",$options)){
	$dt_respnsive = $options['dt_responsive'];
	if($dt_respnsive==1){
		$dt_class = 'dt-responsive nowrap ';	
	}
}
if (array_key_exists("dt_darktheme",$options)){
  $dt_darktheme = $options['dt_darktheme'];
  if($dt_darktheme==1){
  	$dt_class .= 'table-dark ';	
  }
}


/*if($columnNames && count($columnNames)>0){
  $filteredColumns=[]; //object array filtered from columnList
  $columnGroup=[]; //array of keys from filtered objects 
  $tempColumn=[];  //temporary object from filtered objects
  $seqColumns=array_fill(0, count($columnNames), ''); //sequenced object array
  //compare columns with checked columns
  foreach($collection as $key => $value) {
	  if (in_array($value->colName, $columnNames)){
		  array_push($filteredColumns, $value);
		  array_push($columnGroup, $value->colName);	
	  }
  }
  //sequence columns with checked columns
  foreach($filteredColumns as $key => $value) {
		  array_push($tempColumn, $filteredColumns[array_search($value->colName, $columnGroup)]);
		  array_splice($seqColumns,array_search($value->colName, $columnNames),1,$tempColumn);
		  $tempColumn=[];
  }
} else {
	$seqColumns=$collection;
}*/
?>
<div class="containerEngagii" id="list_div">
  <div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
    <table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here events-page <?php echo  $dt_class; ?>" style="width: 100% !important; ">
    <thead> 
      <tr>                
        <?php 
        $forDatatable = array();
        $i=0;
        foreach($columns as $key => $value){
         // print_r($value);
          //if(in_array($value->colName, $columnNames)){
              $forDatatable[$i]['data'] =$value->colName; 
              //$forDatatable[$i]['name'] =$value->colName;
              if($value->colName == 'eventType')
              {
                 $value->displayName = "Type";
              }
              if($value->colName == 'city'){
                $value->displayName = "Location";
              }
              /*if($value->colName == 'startDateTime'){
                $value->displayName = "Event Schedule";
              }*/
              if($value->colName == 'name'){
				 // $value->displayName = "event_name";
                $title_key = $i;
              }
			  //$forDatatable[]['data'] = $value->colName;

          ?>    
            <th class="<?php echo strtolower($value->colName); ?>">
            <?php  echo $value->displayName; ?>
            </th>
          <?php $i++; 
          //}
        } ?>
      </tr> 
    </thead> 
  </table>
  <div id="eng-overlay"><span class="spinner"></span></div>
</div>
</div>
<style>
 .ebtmaintable-tbl-container{
   position: relative;
 }
.custom-scroll #ebtmaintable, .custom-scroll {
	 overflow: visible; 
}
 
</style>

<script type="text/javascript">

  var tags       = [];
  var types       = '';
  var city       = '';
 var createdDate = '';
  var startdate = '';
  var enddate     = '';
  var titleColumn = '<?php echo $title_key; ?>';
  var fv = 0;

        
var table = $('#ebtmaintable').DataTable( {
    
        
       "pageLength": 10,
       "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50]],
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       "bInfo":false,
       "processing": true,
       "searching": true,
       "ordering":true,
	   //"search": {regex: true},
		<?php if(in_array('startDateTime', $columnNames)){ ?>
		"order": [[<?php echo array_search('startDateTime',$columnNames);?>, 'asc']],
		 <?php } ?>
       "columnDefs": [ 
          { "targets": ['tags','register','eventtype','city','eventstatus'],
            "orderable": false
          },
		  { className: "title-col", "targets": "name" },
		  { className: "text-left", "targets": ["tags","register","eventtype","eventdates","city"] },
		  <?php if(in_array('startDateTime', $columnNames)){ ?>
		  {'targets': <?php echo array_search('startDateTime',$columnNames);?>, 'createdCell':  function (td, cellData, rowData, row, col) {
			  var html = $(cellData);
			  var editor = $("<p>").append(html);
			  var cell = editor.find("span:first-child").html();
           $(td).attr('data-order', cell ); 
       		 }
    	 }
		 <?php } ?>
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search events..."
         },
         "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
          "url": ajax_url_evt,
            "type": "POST",
            "data": function(d) {           
            d.action = 'eventsbyperson';   
              d.tags    = tags; 
              d.types    = types; 
              d.locations    = city; 
             // d.eventEndDate = enddate;   
			 // d.eventStartDate = startdate;  
			  d.createdDate = createdDate;
			 // d.text = text;
			  d.titleColumn = titleColumn; 
              
            }, 
        },
        createdRow: function (row, data, index) { 
            // $(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
     "drawCallback": function( settings ) {
      if ($('.dataTables_empty').length) {
        $('.dataTables_empty').html('<div class="dt-empty-message"><h2 class="text-muted">No event found at the moment. Please check back later or adjust your filters.</h2></div>');
        $('.dataTables_paginate').hide(); // Hide pagination
        $('.dataTables_length').hide();   // Hide "Show X records per page"
      }else{
        $('.dataTables_paginate').show(); // Show pagination if records exist
        $('.dataTables_length').show();   // Show "Show X records per page"
      }
			 dt_dropdown();
			 eventRegPopUp();
			 <?php if($dt_respnsive==''){ ?>
           dt_scroll();
			   <?php } ?>
			   $('[data-toggle="tooltip"]').tooltip() ;
         },
		  "initComplete": function(settings, json) {
			  $('#eng-overlay').css( 'display', 'none' );
        $('#filter-content-wrapper').fadeIn();
			 // dt_filterActivate();
    },
    });

	$('.refresh').click(function(){
		table.draw();
	});

<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Events');
  <?php
}
  ?>
 
  function eventRegPopUp() {
    $('.open-pop').click(function(e) {
        var tpath = $(this).attr('data-url');
        $('#iframeContainer').remove();
        var iframe = $('<iframe>', {
            src: tpath,
            id: 'iframeContainer',
            width: '100%',
            height: '100%',
            frameborder: 0,
            scrolling: 'auto'
        });
        
        // Optionally create a modal or div to append the iframe
        var modalContainer = $('<div>', {
            id: 'modalContainer',
            css: {
                'width': '90vw',
                'height': '90vh',
                'position': 'fixed',
                'top': '50%',
                'left': '50%',
                'transform': 'translate(-50%, -50%)',
                'background': '#fff',
                'z-index': 9999,
                'padding': '20px',
                'box-shadow': '0 0 10px rgba(0,0,0,0.5)',
                'overflow': 'hidden'
            }
        });
 var closeButton = $('<button>', {
            text: 'X',
            css: {
                'position': 'absolute',
                'top': '10px',
                'right': '10px',
                'padding': '5px 10px',
                'background-color': '#f44336',
                'color': '#fff',
                'border': 'none',
                'cursor': 'pointer',
                'font-size': '16px',
                'border-radius': '5px'
            },
            click: function() {
                // Remove modal when the close button is clicked
                $('#modalContainer').remove();
                table.draw();
            }
        });

        // Append iframe to modalContainer
        modalContainer.append(closeButton);
        modalContainer.append(iframe);
        
        // Append modalContainer to the body
        $('body').append(modalContainer);
        
        // Close modal logic when clicking outside the iframe (optional)
        modalContainer.click(function(e) {
            if (!$(e.target).is('iframe')) {
                $('#modalContainer').remove();
                table.draw();
            }
        });

        e.preventDefault();
    });
}
	$('div.flt-btn').html('<?php echo $filter_content; ?>');

 
    $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        //console.log(processing);
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();

    setTimeout(function() {      
        $('.dataTables_empty').html('');
    },500);
  

$( '.cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
			startdate='';
			enddate='';
    countFilterData();
});
window.addEventListener("load", function () {
  $('#filter-loader').show(); // Show loader
  $('.filter-content').hide(); // Hide filter content
  $.ajax({
		type : "post",
		url: engagifiiUrl_ajaxurl,
		data:{
		   action:'eventFilters',
		   filterParams:<?php echo json_encode($columnNames);?>,
		},
		success: function(response) { 
		for (var key of Object.keys(JSON.parse(response))) {
			$('.'+key+'-filter ul').html(JSON.parse(response)[key]);
		}
		dt_filterActivate();
		var dates = JSON.parse(response)['startDateTime'];
		filterEvents(dates['minStartDate'],dates['maxEndDate']); 
 $('#filter-loader').hide(); // Hide loader
      $('.filter-content').fadeIn(); // Show filter content
			}
     
	  });
});
function filterEvents(minDate,maxDate){
   $('input[name="createdbetween"]').daterangepicker({
   minDate:minDate,
    maxDate: maxDate,
    autoApply: true
  }, function(start, end) {
      createdDate = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
	  startdate=start.format('MM/DD/YYYY');
	  enddate=end.format('MM/DD/YYYY');
		  if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
      countFilterData();

    });
  startdate='';
  enddate='';
  $('input[name="createdbetween"]').val('');
  $('.filter-list input[type=checkbox]').change(function(){
	  if($('#apply-filter-data .spinner-border').length==0){
		  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
	  }
	  
	  countFilterData();
  })
}
    //filter
    $('#apply-filter-data').click(function(){
  $('.filter-list').each(function() {
	 if ($(this).find('input[type=checkbox]').is(':checked')) {
		$(this).addClass('checked');
	 } else {
		$(this).removeClass('checked');
	 }
  });

  $('input[name="createdbetween"]').each(function() {
	 if ($(this).val()!='') {
		$(this).parents('.filter-list').addClass('checked');
	 } else {
		$(this).parents('.filter-list').removeClass('checked');
	 }
  });
  fv = $('.filter-list.checked').length;
  if(fv>0){
	$('.filter-icon').addClass('active'); 
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active');  
  }


tags = $.map($('input[name="eventsTags[]"]:checked'), function(c){return c.value; });
if(tags.length==0){
tags= [];	
}

types = $.map($('input[name="eventsType[]"]:checked'), function(c){return c.value; });
city = $.map($('input[name="eventsLocation[]"]:checked'), function(c){return c.value; });
      
      $(".filter-area").toggleClass('d-none');
      table.draw();

    });


      $('.clear-all').click(function(){
         <?php
          //if(in_array('tags', $columnNames))
         // {
        ?>
            $('.filter-content input[type=checkbox]').prop('checked',false);
        <?php
         // }
        ?>
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active'); 
            tags = [];
			types='';
			city='';
            createdDate = '';
			startdate='';
			enddate='';
            table.draw();

      })


	  $(document).on('click', function (e) {
 $('.filter-area').addClass('d-none');
});
$(document).on('click', '.filter-area, .td-dropdown', function (e) {
  e.stopPropagation();
});
$(document).on('click', 'th.prev', function (e) {
  e.stopPropagation();
});
$(document).on('click', 'th.next', function (e) {
  e.stopPropagation();
});
$(document).on('click', '.daterangepicker ', function (e) {
  e.stopPropagation();
});


      function countFilterData(){

var tags = $.map($('input[name="eventsTags[]"]:checked'), function(c){return c.value; });
if(tags.length==0){
tags= [];	
}
var types = $.map($('input[name="eventsType[]"]:checked'), function(c){return c.value; });
var city = $.map($('input[name="eventsLocation[]"]:checked'), function(c){return c.value; });
  $.ajax({
    type : "post",
    url: engagifiiUrl_ajaxurl,
    data:{
        action:'eventfiltercountdata',
        tags : tags,  
        types : types,  
        locations : city,  
         eventEndDate : enddate,   
		eventStartDate : startdate ,
		createdDate: createdDate, 
    },
    success: function(response) {     
	//console.log(response); 
      var element  = document.getElementById("countFilterResult");
	  $('#apply-filter-data .spinner-border').remove();
	  $('#apply-filter-data').removeAttr('disabled')
      if(element)
      {
          element.innerHTML = " ("+response.api_response +")";
          //console.log(response.api_response);
      }    
    }
});
}

 
 $(document).ready(function(){
  <?php
  if($calendar_view){
  ?>
   $('#list_div').hide();
   <?php
    }
   ?>
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
	if(localStorage.getItem("view_mode")=='list'){
		$('#list').trigger("click");
	}
});


</script>      
</div>