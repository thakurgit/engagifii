<?php

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
    $dataResponse = $this->submitApiRequest("Public/EventColumnList",array(),"GET",'event');
	//print_r($dataResponse);
	//die;
if(!$dataResponse['api_response']){
	echo '<h5 class="text-center text-danger"><strong><em>No data found! Please contact website admin.</em></strong><h5>';
	return;
}
    $collection   = json_decode($dataResponse['api_response']);
    $options = get_option('ebt_api_settings');
    $events_visible_column_list = $options['events_visible_column_list'];
	$ebt_visib_datacol_list   =  array();
	if($options['events_visible_column_list']){
  	  $ebt_visib_datacol_list = $options['events_visible_column_list'];
	}

    /* Get Tags list */
    $payloadData = array();
    $getCurrentdate = date("Y-m-d");
    $payloadData['selectedDate'] = $getCurrentdate;
    $payloadData['itemCount'] = 10;
    $payloadData['sortBy'] = 'createdon';
    $payloadData['sortDirection'] = 'desc';
    $payloadData['pageNumber'] = 1;
    $payloadData['filterBody'] = array('searchText' => '', 'selectedDate' => $getCurrentdate);

    $postedData = $payloadData;
    $date = date('Y-m-d');
    $dataResponse = $this->submitApiRequest("/public/tags".$date, $postedData, "GET", 'event');
    $tags = $obj->eventsAllTags();
    $eventTypes = $obj->eventTypes($date);
    $eventLocations = $obj->eventLocation();
    //print_r($dataResponse);
    $dateRange  = $obj->eventDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
    
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
	echo '<div class="container-fluid pb-2"><div class="row"><div class="col-12 text-center text-lg-right view-mode d-flex align-items-center justify-content-end">
    <div class="flt-btn mr-3 " style="display:block">
        </div>
    </div></div>';  
  }
?>


</div>
<?php
function removeWhitespace($buffer)
{
    return preg_replace('/\s+/', ' ', $buffer);
}

ob_start();
?>
<div class="filter-content" id="filterdp1">
	<div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative bg-light border"><i class="far fa-filter click-filter"></i><span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span></div>
  <div class="filter-border">
  <div class="filter-area" id="filterdp">
    <div class="Engagiirow filter-top-bg col-sm-12 py-2 bg-dark text-white">
      <div class="row">
      <div class="col-6 text-left">
        <span class="filter-title">
          <i class="far fa-filter mr-2"></i> Filter
          <span id="blockedchecked"></span> 
        </span>
      </div>
      <div class="col-6 text-right">
        <span class="clear-all" id="clear-all"> <i class="fal fa-sync"></i></span>
      </div>
      </div>
    </div>
    <div class="col-sm-12" id="test">
      <input type="hidden" id="isApplyACtive" value="0">
      <?php if(in_array('startDateTime', $events_visible_column_list)) { ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Event Date <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative pb-2">
          <input type="text" name="createdbetween"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>
      <?php }
      if(in_array('eventType', $events_visible_column_list) && array_search('eventType', $ebt_visib_datacol_list)){
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Event Types <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php
		  if($eventTypes){
            foreach ($eventTypes as $key => $value) {
              echo '<li class="d-flex align-items-start"><input type="checkbox" name="eventsType[]" id="event_'.$key.'" value="'.$value['value'].'" class="mr-2 mt-1"> <label for="event_'.$key.'"><small> '.addslashes($value['text']).'</small></label></li>';
            }
            }
          ?>  
        </ul></div>
      </div>
      
      <?php
        }
      
      
      if(in_array('city', $events_visible_column_list)) {
         //if(array_search('location', $ebt_visib_datacol_list)){
          ?>
          <div class="filter-list border-bottom">
            <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Location <i class="far fa-angle-down"></i></div>
            <div class="content-area d-none"><ul class="list-group m-0">
              <?php
			  if($eventLocations){
                foreach ($eventLocations as $key => $value) {
           echo '<li class="d-flex align-items-start"><input  type="checkbox" name="eventsLocation[]" id="location_'.$key.'" value="'.$value['id'].'" class="mr-2 mt-1"> <label for="location_'.$key.'"><small>'.addslashes($value['city']).'</small></label></li>';
                }
                }
              ?>  
            </ul></div>
          </div>
          
          <?php
          // }
              }
    if (in_array('tags', $events_visible_column_list) && array_search('tags', $ebt_visib_datacol_list)) {
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Tags <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php
		  if($tags){
            foreach ($tags as $key => $value) {
              echo '<li class="d-flex align-items-start"><input id="tag_'.$key.'" class="mr-2 mt-1" type="checkbox" name="eventsTags[]" value="'.$value['id'].'"> <label class="" for="tag_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';
            }
            }
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


if($ebt_visib_datacol_list && count($ebt_visib_datacol_list)>0){
  $filteredColumns=[]; //object array filtered from columnList
  $columnGroup=[]; //array of keys from filtered objects 
  $tempColumn=[];  //temporary object from filtered objects
  $seqColumns=array_fill(0, count($ebt_visib_datacol_list), ''); //sequenced object array
  //compare columns with checked columns
  foreach($collection as $key => $value) {
	  if (in_array($value->colName, $ebt_visib_datacol_list)){
		  array_push($filteredColumns, $value);
		  array_push($columnGroup, $value->colName);	
	  }
  }
  //sequence columns with checked columns
  foreach($filteredColumns as $key => $value) {
		  array_push($tempColumn, $filteredColumns[array_search($value->colName, $columnGroup)]);
		  array_splice($seqColumns,array_search($value->colName, $ebt_visib_datacol_list),1,$tempColumn);
		  $tempColumn=[];
  }
} else {
	$seqColumns=$collection;
}
?>
<div class="containerEngagii" id="list_div">
  <div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
    <table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here events-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
    <thead> 
      <tr>                
        <?php 
        $forDatatable = array();
        $i=0;
        foreach($seqColumns as $key => $value){
         // print_r($value);
          //if(in_array($value->colName, $ebt_visib_datacol_list)){
              $forDatatable[$i]['data'] =$value->colName; 
              $forDatatable[$i]['name'] =$value->colName;
              if($value->colName == 'eventType')
              {
                 $value->displayName = "Type";
              }
              if($value->colName == 'city'){
                $value->displayName = "Location";
              }
              if($value->colName == 'startDateTime'){
                $value->displayName = "Event Schedule";
              }
              if($value->colName == 'name'){
				  $value->displayName = "event_name";
                $title_key = $i;
              }
			  //$forDatatable[]['data'] = $value->colName;

          ?>    
            <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
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


 
</style>

<script type="text/javascript">

  var tags       = ['portal'];
  var types       = '';
  var city       = '';
 var createdDate = '';
  var startdate = '';
  var enddate     = '';
  var text     = '';
  var titleColumn = '<?php echo $title_key; ?>';
  var fv = 0;

  $('#list').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#calendar').removeClass('btn-primary').addClass('btn-light');
            $('#list_div').show();
			$('.flt-btn').fadeIn(300);
      $('#calendar_div, #calendar_filter, #calendarsearch_div, .calendarsearch-form, .new-search').hide();
            $('#calendar_filter').hide();
 localStorage.setItem("view_mode",$('.view-m .btn-primary').attr('id'));
        })
        $('#calendar').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#list').removeClass('btn-primary').addClass('btn-light');
      $('#calendar_div, #calendar_filter, .calendarsearch-form, .new-search').show();
      $('#list_div, #calendarsearch_div, .filter-border').hide();
			$('.flt-btn').fadeOut(100);
 localStorage.setItem("view_mode",$('.view-m .btn-primary').attr('id'));
        })
        
var table = $('#ebtmaintable').DataTable( {
    
        
       "pageLength": 10,
       "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50]],
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       "bInfo":false,
       "processing": true,
       "searching": true,
       "ordering":true,
	   //"search": {regex: true},
		<?php if(in_array('startDateTime', $ebt_visib_datacol_list)){ ?>
		"order": [[<?php echo array_search('startDateTime',$ebt_visib_datacol_list);?>, 'desc']],
		 <?php } ?>
       "columnDefs": [ 
          { "targets": ['tags','register','eventType','city','eventStatus'],
            "orderable": false
          },
		  { className: "title-col", "targets": "name" },
		  { className: "text-center", "targets": ["tags","register","eventType","eventDates","city"] },
		  <?php if(in_array('startDateTime', $ebt_visib_datacol_list)){ ?>
		  {'targets': <?php echo array_search('startDateTime',$ebt_visib_datacol_list);?>, 'createdCell':  function (td, cellData, rowData, row, col) {
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
			  d.text = text; 
              
            }, 
        },
        createdRow: function (row, data, index) { 
            // $(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
     "drawCallback": function( settings ) {
			 dt_dropdown();
			 <?php if($dt_respnsive==''){ ?>
           dt_scroll();
			   <?php } ?>
			   $('[data-toggle="tooltip"]').tooltip() ;
         },
		  "initComplete": function(settings, json) {
			  $('#eng-overlay').css( 'display', 'none' );
			  dt_filterActivate();
    },
    });


<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Events');
 /* $('#ebtmaintable thead tr th:eq('+titleColumn+')').each( function (i) {
    
$('.list-search-btn').click(function(e){
	var ttitle= $('.list-search').val();
	if(ttitle!=''){
    text = ttitle;
		$('#list').trigger('click');	
		table.column(titleColumn).search(text).draw();
		 $( '#searchclass' ).val($('.list-search').val());
		$('.clear-search').show();
     
	} else {
		alert("search field can't be empty");	
	}
	e.stopPropagation();
 });
$('.list-search').on("keydown", function(event) {
  if(event.which == 13){
	$('.list-search-btn').trigger('click');  
  }  
});
        var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" id="searchclass" placeholder="Search events" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:5;display:none"><i class="fal fa-times"></i></button></div>' );
		function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

$( 'input', this ).keyup(delay(function (e) {
	  text = this.value;
            if ( text !== '' ) {
				table.draw();
            }
}, 500));


 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$('.clear-search').show();
			} else {
				$('.clear-search').hide();
			} 
 });
$('th .clear-search').click(function(e){
	 $('#searchclass').val('');
	$('.clear-search').hide();
	e.stopPropagation();
	//table.column(i).search('').draw();
	text = '';
	table.draw();
 });

        
    } );
	$(document).ready(function (){    
    $('#searchclass').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchclass').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});
});*/
  <?php
}
  ?>
	$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});
    //$('div.top-filter').html('<?php //echo $filter_content; ?>');
	$('div.flt-btn').html('<?php echo $filter_content; ?>');

 
    $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        //console.log(processing);
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();

    setTimeout(function() {      
        $('.dataTables_empty').html('');
    },500);
  
    $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});

$( '.cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
    countFilterData();
});
   /* $('.filter-icon').click(function(e){
        e.stopPropagation();
        $(this).siblings('.filter-border').show();
       $(this).siblings('.filter-border').find('.filter-area').toggleClass('d-none');
        $('#isApplyACtive').val(1);
		jQuery(".filter-area .list-group").mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:"minimal-dark",
		 			scrollbarPosition:"outside"
		 			});
    })

   $('.heading-title').click(function(){
		$(this).next('.content-area').toggleClass('d-none');
		$(this).parent().siblings('.filter-list').find('.content-area').addClass('d-none');
	});*/

   $('input[name="createdbetween"]').daterangepicker({
   minDate:'<?php echo $min_date; ?>',
    maxDate: '<?php echo $max_date; ?>',
    autoApply: true
  }, function(start, end) {
      createdDate = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
      countFilterData();

    });

    //filter
    $('#apply-filter-data').click(function(){
     <?php /*?> <?php
       if(in_array('tags', $ebt_visib_datacol_list))
       {
      ?>
          tags       = $.map($('input[name="emdorsementTag[]"]:checked'), function(c){return c.value; });
      <?php
        }
      ?><?php */?>
tags = $.map($('input[name="eventsTags[]"]:checked'), function(c){return c.value; });
types = $.map($('input[name="eventsType[]"]:checked'), function(c){return c.value; });
city = $.map($('input[name="eventsLocation[]"]:checked'), function(c){return c.value; });
     // createdDate = $('input[name="createdbetween"]').val();
      
      $(".filter-area").toggleClass('d-none');
      table.draw();

    });


      $('.clear-all').click(function(){
         <?php
          if(in_array('tags', $ebt_visib_datacol_list))
          {
        ?>
            $('input[type=checkbox]').prop('checked',false);
        <?php
          }
        ?>
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active'); 
            tags = '';
			types='';
			city='';
            createdDate = '';
            table.draw();

      })


     
      $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
       // container.hide();
       // $('.filter-area').addClass('d-none');
      }
      });
	  $(document).on('click', function (e) {
 $('.filter-area').addClass('d-none');
});
$(document).on('click', '.filter-area', function (e) {
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

      $('.filter-list input[type=checkbox]').change(function(){
		  if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
		  
          countFilterData();
      })

      function countFilterData()
      {

//var courses = $.map($('input[name="courseClassCal[]"]:checked'), function(c){return c.value; });
var tags = $.map($('input[name="eventsTags[]"]:checked'), function(c){return c.value; });
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
         //eventEndDate : enddate,   
		//eventStartDate : startdate ,
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

	  $("#apply-filter-data").click(function () {

  $('.filter-list').each(function() {
	 if ($(this).find('input[type=checkbox]').is(':checked')) {
		$(this).addClass('checked');
	 } else {
		$(this).removeClass('checked');
	 }
  });
  fv = $('.filter-list.checked').length;
  if(fv>0){
	$('.filter-icon').addClass('active'); 
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active');  
  }
 });  
 
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
<script>
  
    //  //Hide Filters on clicking outside filter area
    //  const filterdp1= document.getElementById('filterdp1');
    //  const filterdp= document.getElementById('filterdp');
    //  const test= document.getElementById('list');
    //  const id = $('.filter-area').val();

    //  document.onclick= function(e){
    //  if(e.target.id !== 'filterdp1' && e.target.id !== 'filterdp' && e.target.id !== 'test'){
    //   var elmId = $("#filter-area").attr("id");
    //     alert(elmId);
    //     //$('.filter-area').addClass('d-none');
    //     //$('.filter-content').addClass('d-none');
        
    //   }
    // };

    // filterdp.onclick = function(){
    //   alert(e.target.id);
    // };
    
 
  </script>
</div>