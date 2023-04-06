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
    $dataResponse = $this->submitApiRequest("Public/EndorsementColumnList",array(),"GET",'endorsement');
    $collection   = json_decode($dataResponse['api_response']);
    if(!$collection){
      echo '<h5 class="text-center text-danger"><strong><em>Settings for this page are not complete.  Please contact your administrator.</em></strong><h5>';
      return;
    }
    unset($collection[0]);
    unset($collection[6]);
    unset($collection[8]);
    unset($collection[9]);
    array_values($collection);
    
    $options = get_option( 'ebt_api_settings' );
    $ebt_visib_datacol_list = $options['ebt_visib_datacol_list'];
if($ebt_visib_datacol_list==null){
 $ebt_visib_datacol_list =[];
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
    $dataResponse = $this->submitApiRequest("Public/Award/GetAllTags/".$date, $postedData, "GET", 'endorsement');
    $tags = json_decode($dataResponse['api_response']);
    
    $dateRange  = $obj->awardDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));

?>
 <?php $placeholder_text = 'Search by award name';
 $module ="endorsement";
echo do_shortcode('[view_mode search="on" placeholder="'.$placeholder_text.'" module="'.$module.'"]'); ?>
<?php


  if($calendar_view){
?>

<div class="container-fluid">
  <?php echo do_shortcode('[endorsement-calendar]'); ?>
</div>
<?php
  } 
?>


<?php
function removeWhitespace($buffer)
{
    return preg_replace('/\s+/', ' ', $buffer);
}

ob_start();
?>
<div class="filter-content">
	<div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative bg-light border"><i class="far fa-filter click-filter"></i><span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span></div> 
  <div class="filter-border">
  <div class="filter-area d-none">
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
    <div class="col-sm-12 ">
      <input type="hidden" id="isApplyACtive" value="0">
      <?php if(in_array('createdOn', $ebt_visib_datacol_list)){ ?>
      <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Created Between <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative pb-2">
          <input type="text" name="createdbetween"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>
      <?php } ?>
      <?php
      
        if(array_search('tags', $ebt_visib_datacol_list)){
      ?>
       <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Tags <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php
            foreach ($tags as $key => $value) {
              echo '<li class="d-flex align-items-start"><input id="tag_'.$key.'" class="mr-2 mt-1" type="checkbox" name="emdorsementTag[]" value="'.$value->id.'"> <label class="" for="tag_'.$key.'"><small> '.addslashes($value->name).'</small></label></li>';
            }
          ?>  
        </ul></div>
      </div>
      <?php
        }
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
<div class="containerEngagii ff" id="list_div">
  <div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
  <table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here endorsement-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
    <thead> 
      <tr>                
        <?php 
        $forDatatable = array();
        $i=0;
        

        foreach($seqColumns as $key => $value){
              $forDatatable[$i]['data'] =$value->colName; 
              $forDatatable[$i]['name'] =$value->colName;
              if($value->displayName == 'Endorsement Type')
              {
                 $value->displayName = "Type";
              }
              if($value->colName == 'name'){
                $title_key = $i;
              }
          ?>    
            <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
          <?php $i++; 
        } ?>
      </tr> 
    </thead> 
  </table>
    <div id="eng-overlay"><span class="spinner"></span></div>
</div>
</div>


<script type="text/javascript">

  var tags       = '';
  var createdDate = '';
  var endDate     = '';
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
       "dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       "bInfo":false,
       "processing": true,
       "searching": true,
       "ordering":true,
		<?php if(in_array('name', $ebt_visib_datacol_list)){ ?>
		"order": [[<?php echo array_search('name',$ebt_visib_datacol_list);?>, 'asc']],
		 <?php } ?>
       "columnDefs": [ 
          { "targets": ['price','objectType','validity','courseCount','register','tags','createdOn'],
            "orderable": false
          },
		  { className: "title-col", "targets": "name" },
		  { className: "text-center", "targets": ["price","objectType","validity","courseCount","register","tags","createdOn"] },
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search endorsements..."
         },
         "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": ajax_url_ebt,
            "type": "POST",
            "data": function(d) {           
              d.tags    = tags; 
              d.createdDate = createdDate;   
			d.titleColumn = titleColumn;
              
            }, 
        },
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
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
            dt_scroll();
			   <?php } ?>
			   $('[data-toggle="tooltip"]').tooltip() ;
         },
		
    });


<?php
  if($title_key > -1){
?>

  $('#ebtmaintable thead tr th:eq('+titleColumn+')').each( function (i) {
    $('.list-search-btn').click(function(e){
	var ttitle= $('.list-search').val();
	if(ttitle!=''){
		$('#list').trigger('click');	
		table.column(titleColumn).search(ttitle).draw();
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
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" id="searchclass" placeholder="Search endorsement" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:3;display:none"><i class="fal fa-times"></i></button></div>' );
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
            if ( table.column(titleColumn).search() !== titlesearch ) {
				table.column(titleColumn).search(titlesearch).draw();
            }
}, 500));
 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$('.clear-search').show();
			} else {
				$('.clear-search').hide();
			} 
 });
$('.clear-search').click(function(e){
	 $('#searchclass').val('');
	$('.clear-search').hide();
	e.stopPropagation();
	table.column(titleColumn).search('').draw();
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
});
  <?php
}
  ?>
	$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});
   // $('div.top-filter').html('<?php echo $filter_content; ?>');
	$('div.flt-btn').html('<?php echo $filter_content; ?>');

 
    $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
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
	 if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
    createdDate = '';
    countFilterData();
});
    $('.filter-icon').click(function(e){
        e.stopPropagation();
        $('.filter-border').show();
        $('.filter-area').toggleClass('d-none');
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
	});

   $('input[name="createdbetween"]').daterangepicker({
   minDate:'<?php echo $min_date; ?>',
    maxDate: '<?php echo $max_date; ?>',
    autoApply: true
  }, function(start, end) {
      createdDate = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
      countFilterData();
 if($('#apply-filter-data .spinner-border').length==0){
			  $('#apply-filter-data').attr('disabled','').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
		  }
    });

    //filter
    $('#apply-filter-data').click(function(){
      <?php
       if(in_array('tags', $ebt_visib_datacol_list))
       {
      ?>
          tags = $.map($('input[name="emdorsementTag[]"]:checked'), function(c){return c.value; });
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

         <?php
           if(in_array('tags', $ebt_visib_datacol_list))
           {
          ?>
              tags = $.map($('input[name="emdorsementTag[]"]:checked'), function(c){return c.value; });
          <?php
            }
          ?>

          
          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
            action:'filtercountdata',
            tags    : tags,
            createdDate : createdDate,
          },
          success: function(response) {       
            var element  = document.getElementById("countFilterResult");
	  $('#apply-filter-data .spinner-border').remove();
	  $('#apply-filter-data').removeAttr('disabled');
           // console.log(response);
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
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
</div>