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
    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[6]);
    unset($collection[8]);
    unset($collection[9]);
    array_values($collection);
    
//print_r($collection);
    $options = get_option( 'ebt_api_settings' );
    $ebt_visib_datacol_list = $options['events_visible_column_list'];
//print_r("-----------------------------------------------------------------<br/>");
   //print_r($ebt_visib_datacol_list);
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
    $tags = $obj->eventsAllTags($date);
    $eventTypes = $obj->eventTypes($date);
    $eventLocations = $obj->eventLocation();
    //print_r($eventLocations);
    $dateRange  = $obj->awardDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));

?>

<div class="containerEngagii">
<!-- start filter UI -->

<?php
  if($calendar_view){
?>
	<div class="container-fluid pb-4">
  <div class="row">
    <div class="col-12 text-center text-lg-right view-mode d-flex align-items-center justify-content-end">
    <div class="flt-btn mr-3 mr-xl-5" style="display:none">
        
        </div>
    	<div class="btn-group view-m" role="group">
        			 <button type="button" id="calendar" class="btn border  btn-primary shadow-none" aria-pressed="false"><i class="fal fa-calendar-alt mr-2"></i></i>Calendar</button>
                  <button type="button" id="list" class="btn btn-light border shadow-none" aria-pressed="false"><i class="fal fa-list mr-2"></i> List</button> 
                 
        </div>
    </div>
  </div>
  </div>

<div class="container-fluid">
  <?php echo do_shortcode('[events-calendar]'); ?>
</div>
<?php
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
    <div class="col-sm-12 height-4" id="test">
      <input type="hidden" id="isApplyACtive" value="0">
      
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Created Between <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative">
          <input type="text" name="createdbetween"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>
      <?php
      
        if(array_search('tags', $ebt_visib_datacol_list)){
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Tags <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php
            foreach ($tags as $key => $value) {
              echo '<li class="d-flex align-items-start"><input id="instruct_'.$key.'" class="mr-2 mt-1" type="checkbox" name="eventsTags[]" value="'.$value->id.'"> '.addslashes($value->name).'<label class="" for="instruct_'.$key.'"><small> '.addslashes($value->name).'</small></label></li>';
            }
          ?>  
        </ul></div>
      </div>
      
      <?php
        }
       
      
      if(array_search('eventType', $ebt_visib_datacol_list)){
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title"> Event Types <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none"><ul class="list-group height-100">
          <?php
            foreach ($eventTypes as $key => $value) {
              echo '<li class=""><label class="d-none" for="instruct_'.$key.'">Inst</label><input type="checkbox" name="eventsType[]" id="instruct_'.$key.'" value="'.$value['value'].'"> '.addslashes($value['text']).'</li>';
            }
          ?>  
        </ul></div>
      </div>
      
      <?php
        }

        //if(array_search('location', $ebt_visib_datacol_list)){
          ?>
          <div class="filter-list">
            <div class="heading-title"> Location <i class="fa fa-angle-down pull-right"></i></div>
            <div class="content-area d-none"><ul class="list-group height-100">
              <?php
                foreach ($eventLocations as $key => $value) {
                  //print_r($value);
                  echo '<li class=""><label class="d-none" for="location_'.$key.'">Inst</label><input type="checkbox" name="eventsLocation[]" id="location_'.$key.'" value="'.$value['id'].'"> '.addslashes($value['city']).'</li>';
                }
              ?>  
            </ul></div>
          </div>
          
          <?php
          // }
      ?>
      
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
$dt_respnsive = get_option( 'ebt_api_settings' )['dt_responsive'];
if($dt_respnsive==1){
$dt_class = 'dt-responsive nowrap ';	
}
$dt_darktheme = '';
$dt_darktheme = get_option( 'ebt_api_settings' )['dt_darktheme'];
if($dt_darktheme==1){
$dt_class .= 'table-dark ';	
}
?>
<div class="containerEngagii" id="list_div">
  <div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
    <table  id="ebtmaintable" class="table table-bordered table-striped main-list-here events-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
    <thead> 
      <tr>                
        <?php 
        $forDatatable = array();
        $i=0;
        // foreach ($collection as $key => $value) {
        //   if($value->colName == "register")
        //   {
        //       $temp_array = $collection;
        //       array_splice($collection, $key, 1);
        //       array_values(array_filter($collection));
        //       array_push($collection, $temp_array);
        //   }
        // }

        foreach($collection as $key => $value){
         // print_r($value);
          if(in_array($value->colName, $ebt_visib_datacol_list)){
              $forDatatable[$i]['data'] =$value->colName; 
              $forDatatable[$i]['name'] =$value->colName;
              if($value->displayName == 'eventType')
              {
                 $value->displayName = "Type";
              }
              if($value->colName == 'name'){
                $title_key = $i;
              }
              if($value->colName =='startDateTime'){
                $value->displayName = "Event Date";
              }
              if($value->colName =='city'){
                $value->displayName = "Location";
              }

          ?>    
            <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
          <?php $i++; 
          }
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

  var tags       = '';
  var createdDate = '';
  var endDate     = '';

  var fv = 0;

  $('#list').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#calendar').removeClass('btn-primary').addClass('btn-light');
            $('#list_div').show();
			$('.flt-btn').fadeIn(300);
            $('#calendar_div').hide();
            $('#calendar_filter').hide();
        })
        $('#calendar').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#list').removeClass('btn-primary').addClass('btn-light');
            $('#calendar_div').show();
            $('#calendar_filter').show();
            $('#list_div').hide();
			$('.flt-btn').fadeOut(100);
        })
        
var table = $('#ebtmaintable').DataTable( {
    
        
       "pageLength": 10,
       "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50]],
		"dom": '<"row no-gutters"<"col-12 custom-scroll"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       "bInfo":false,
       "processing": true,
       "searching": true,
       "ordering":true,
       "columnDefs": [ 
          { "targets": ['tags','register','eventType','eventDates','city'],
            "orderable": false
          },
		  { className: "title-col", "targets": "name" },
		  { className: "text-center", "targets": ["tags","register","eventType","eventDates","city"] },
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
             //d.action = 'events';   
              d.tags    = tags; 
              d.createdDate = createdDate;   
              
            }, 
        },
        createdRow: function (row, data, index) { 
            // $(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 "initComplete": function(settings, json) {
        
		 $('.dataTables_filter label').append('<button type="button" class="btn text-muted shadow-none bg-transparent position-absolute blank"><i class="fa fa-times"></button>');
		 $('.dataTables_filter input').keyup(function(){
			if($(this).val()==''){
				$(this).parent('label').removeClass('has-data');
			} else {
				$(this).parent('label').addClass('has-data');
			}
		 });
		 

    },
     "drawCallback": function( settings ) {
		 dt_dropdown();
			 <?php if($dt_respnsive==''){ ?>
            $('.dataTables_wrapper ').append('<span class="nxt position-absolute bg-primary text-white rounded-circle d-none d-xl-inline-flex align-items-center justify-content-center "><i class="far fa-angle-right"></i></span>');
            $('.dataTables_wrapper ').prepend('<span class="prv position-absolute bg-primary text-white rounded-circle d-none d-xl-inline-flex align-items-center justify-content-center disabled"><i class="far fa-angle-left"></i></span>');
              var divWidth = parseInt($('.custom-scroll').width());
			 var tablewidth = parseInt($('#ebtmaintable').width());
               if(tablewidth<=divWidth){
					$('.nxt,.prv').addClass('disabled');   
					return false;
			   } else {
				$('.nxt').click(function () {
					   tablewidth = parseInt($('#ebtmaintable').width());
				   $('.custom-scroll').animate({
					  scrollLeft: "+=250px"
				   }, "slow",function() {
					   var scrollLeft = parseInt($('.custom-scroll').scrollLeft());
					   //console.log(tablewidth+','+divWidth+scrollLeft)
    					$('.prv').removeClass('disabled'); 
				  		 if(tablewidth==divWidth+scrollLeft||tablewidth==divWidth+scrollLeft-1||tablewidth==divWidth+scrollLeft+1){
						  $('.nxt').addClass('disabled');  
				  		 }	
  					}); 
				   
				});  
				$('.prv').click(function () {
				   $('.custom-scroll').animate({
					  scrollLeft: "-=250px"
				   }, "slow",function(){
					 $('.nxt').removeClass('disabled');  
					 if($('.custom-scroll').scrollLeft()==0){
						$('.prv').addClass('disabled');  
					 }
				   });
				});  
			   }
			   <?php } ?>
         }
		
    });


<?php
  if($title_key > -1){
?>

  $('#ebtmaintable thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {
        var title = $(this).text();
        $(this).html( '<div class="position-relative"><label class="d-none" for="searchclass">search</label><input type="text" id="searchclass" placeholder="Search events" class="form-control form-control-sm search-events pr-4" value=""/> <button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:0; top:0px; display:none"><i class="far fa-times"></i></button></div>' );
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
	  var titlesearch = this.value;
            if ( table.column(i).search() !== titlesearch ) {
				table.column(i).search(titlesearch).draw();
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
	table.column(i).search('').draw();
 });

        /*$( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );*/
    } );
	$(document).ready(function (){    
    $('#searchclass').on('click', function(e){
       e.stopPropagation();    
    });
});
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
    $('.filter-icon').click(function(e){
        e.stopPropagation();
        $('.filter-border').show();
       $('.filter-area').toggleClass('d-none');
        $('#isApplyACtive').val(1);
    })

    $('.heading-title').click(function(){$(this).next('.content-area').toggleClass('d-none')});

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
      <?php
       if(in_array('tags', $ebt_visib_datacol_list))
       {
      ?>
          tags       = $.map($('input[name="emdorsementTag[]"]:checked'), function(c){return c.value; });
      <?php
        }
      ?>
      createdDate = $('input[name="createdbetween"]').val();
      
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
            createdDate = '';
            table.draw();

      })


     
      $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
        container.hide();
        $('.filter-area').addClass('d-none');
      }
      });

      $('.filter-list input[type=checkbox]').change(function(){
          countFilterData();
      })

      function countFilterData()
      {

//var courses = $.map($('input[name="courseClassCal[]"]:checked'), function(c){return c.value; });
var tags = $.map($('input[name="endorsementTags[]"]:checked'), function(c){alert (c.value); return c.value; });
  $.ajax({
    type : "post",
    url: engagifiiUrl_ajaxurl,
    data:{
        action:'eventfiltercountdata',
        //courses : courses,
        tags : tags,  
    },
    success: function(response) {      
     console.log(tags); 
      var element  = document.getElementById("countFilterResult");
      if(element)
      {
          element.innerHTML = " ("+response.api_response +")";
          console.log(response.api_response);
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