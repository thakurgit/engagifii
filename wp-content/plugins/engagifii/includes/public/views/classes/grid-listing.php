<?php
  $default_length = '10';
  $calendar_view = false;
  $calendar_view_classname = false;
  if(isset($attr['records'])){
    $default_length = $attr['records'];
  }
  if(isset($attr['calendar'])){
    $calendar_view = $attr['calendar'];
  }
  if(isset($attr['calendarclassname'])){
    $calendar_view_classname = $attr['calendarclassname'];
  }
  
  $obj      =  new Engagifii_API();
  $collection   = array();
    $forDatatable   =   array();
    $date           =   date('Y-m-d');
    $options  = get_option( 'ebt_api_settings' );
    $class_visible_column_list = $options['class_visible_column_list'];

   // print_r($class_visible_column_list);
    $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
    //print_r($dataResponse);

    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[1]);
    unset($collection[7]);
    unset($collection[8]);

//$dataResponse2 = $this->submitApiRequest("Public/ClassPagingList", $postData, "POST", 'classes');


    $classes = $obj->getAllClassCourses($date);
    $creditFilter    = $obj->getCreditHoursFilter($date);
    $instructor = $obj->classAllInstructors($date);
    $dateRange  = $obj->classdateFilters($date);

    //print_r($classes);

    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
    $title_key = -1;
    
?>

<?php

  if($calendar_view){
?>

  <?php echo do_shortcode('[class-calendar]'); ?>
<?php
  }
  else if($calendar_view_classname){
	  
    ?>
    
      <?php echo do_shortcode('[class-calendar-class-name]'); ?>
    <?php
      }
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
<div class="containerEngagii ff" id="list_div">
  <div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
    <table  id="ebtmaintable" class="table table-bordered table-striped main-list-here classes-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
      <thead> 
        <tr>        
          <?php
            if(is_array($collection) && count($collection)>0){
              $i = 0;
              foreach ($collection as $key => $value) {
                 if(in_array($value->colName, $class_visible_column_list)){
                
                  if($value->displayName == 'Class Type')
                  {
                     $value->displayName = "Type";
                  }
                  if($value->colName == 'sessions')
                  {
                      $value->colName = 'startdate';
                  }
				  if($value->displayName == 'Class dates')
                  {
                      $value->displayName = 'Class Dates';
                  }

                  if($value->colName == 'sectionname'){
                    $title_key = $i;
                  }
                  $forDatatable[]['data'] = $value->colName;
                ?>
                  <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
                <?php
                $i++;
                }
              }
            }
          ?>    

        </tr> 
      </thead> 
    </table>
    <div id="eng-overlay"><span class="spinner"></span></div>
</div>

</div>
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
        <span class="clear-all" id="clear-all"> <i class="fal fa-sync d-none"></i>Clear All </span>
      </div>
      </div>
    </div>
    <div class="col-sm-12 height-4">
      <input type="hidden" id="isApplyACtive" value="0">
      <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between">Course Name <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none">
          <ul class="list-group m-0">
          <?php foreach ($classes as $key => $value) { echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" id="class_'.$key.'" name="courseClass[]" value="'.addslashes($value['name']).'"><label class="" for="class_'.$key.'"><small> '.addslashes($value['name']).'</small></label></li>';} ?>
          </ul>
        </div>
      </div>
      <div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Instructor <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php

            foreach ($instructor as $key => $value) {
              echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" id="instructor_'.$key.'" name="courseInstrutor[]" value="'.$value['id'].'"> <label  for="instructor_'.$key.'"><small>'.addslashes($value['name']).'</small></label></li>';
            }
          ?>  
        </ul></div>
      </div>

<!-- credit Hour filters -->

<div class="filter-list border-bottom">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between" for="creditFilter"> Credit Hours <i class="far fa-angle-down "></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php

              echo '<input id="creditFilter" name="creditFilter" type="text" class="span2 form-control form-control-sm " readonly value="" data-slider-min="'.$creditFilter['minRange'].'" data-slider-max="'.$creditFilter['maxRange'].'" data-slider-step="5" data-slider-value="['.$creditFilter['minRange'].','.$creditFilter['maxRange'].']"/><div id="slider-range"></div>';
          ?>  
        </ul></div>
      </div>
      
      

      <div class="filter-list border-bototm">
        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> <label for="">Created Between</label> <i class="far fa-angle-down"></i></div>
        <div class="content-area d-none position-relative">
          <input type="text" name="createdbetween" id="createdbetween"  class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
        </div>
      </div>

      
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
?>
<script type="text/javascript">
  var courses = '';
  var instructor = '';
 
  var createdDate = '';
  var endDate     = '';
  var creditFilter ='';

  var fv = 0;
  
  
 
 
   $('#list').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#calendar').removeClass('btn-primary').addClass('btn-light');
            $('#list_div').show();
			$('.flt-btn').fadeIn(300);
            $('#calendar_div').hide();
            $('#calendar_filter').hide();
            $('#calendarsearch_div').hide();
            $('.calendarsearch-form').hide();
 localStorage.setItem("view_mode",$('.view-m .btn-primary').attr('id'));
        })
        $('#calendar').click(function(){
	   		$(this).addClass('btn-primary').removeClass('btn-light');
			$('#list').removeClass('btn-primary').addClass('btn-light');
            $('#calendar_div').show();
            $('#calendar_filter').show();
            $('#list_div').hide();
			$('.flt-btn').fadeOut(100);
            $('#calendarsearch_div').hide();
			$('.filter-border').hide();
        $('.filter-area').toggleClass('d-none');
        $('.calendarsearch-form').show();
 localStorage.setItem("view_mode",$('.view-m .btn-primary').attr('id'));
        })
  var table = $('#ebtmaintable').DataTable( {
        "pageLength": '<?php echo $default_length; ?>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        //"dom": '<"row"<"col-md-11 col-10"><"top-filter col-md-1 col-2 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
		"dom": '<"row no-gutters"<"col-12 custom-scroll"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
        "bInfo":false,
        "processing": true,
        "searching": true,
        "ordering":true,
        "columnDefs": [ 
          { "targets": ['objectType','classDuration', 'startdate', 'credithours', 'classTag', 'classInstructorsCount', 'register'],
            "orderable": false
          },
          //{ width: 200, targets: 3 },
		  { className: "title-col", "targets": "classes" },
		  { className: "text-center", "targets": ["startdate","instructors","credithours"] },
		  { responsivePriority: 1, targets: 'sectionname' },
        ],
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
              d.action='classes'; 
              d.courses = courses;
              d.instructors = instructor;  
              d.createdDate = createdDate;   
              d.creditHour = creditFilter;
			   d.minRange = 50; 
            d.maxRange = 60;
            }, 
        },
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 <?php if($dt_respnsive==''){ ?>
         "drawCallback": function( settings ) {
            $('.dataTables_wrapper ').append('<span class="nxt position-absolute bg-primary text-white rounded-circle d-none d-xl-inline-flex align-items-center justify-content-center "><i class="far fa-angle-right"></i></span>');
            $('.dataTables_wrapper ').prepend('<span class="prv position-absolute bg-primary text-white rounded-circle d-none d-xl-inline-flex align-items-center justify-content-center disabled"><i class="far fa-angle-left"></i></span>');
              var divWidth = parseInt($('.custom-scroll').width());
			 var tablewidth = parseInt($('#ebtmaintable').width());
               if(tablewidth==divWidth){
					$('.nxt,.prv').addClass('disabled');   
					return false;
			   } else {
				$('.nxt').click(function () {
					   tablewidth = parseInt($('#ebtmaintable').width());
				   $('.custom-scroll').animate({
					  scrollLeft: "+=250px"
				   }, "slow",function() {
					   var scrollLeft = parseInt($('.custom-scroll').scrollLeft());
					   console.log(tablewidth+','+divWidth+scrollLeft)
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
         },
		 <?php } ?>
		  "initComplete": function(settings, json) {

		 $('.dataTables_filter label').append('<button type="button" class="btn text-muted shadow-none bg-transparent position-absolute blank"><i class="fa fa-times"></button>');
		 $('.dataTables_filter input').keyup(function(){
			if($(this).val()==''){
				$(this).parent('label').removeClass('has-data');
			} else {
				$(this).parent('label').addClass('has-data');
			}
		 });
		 

    }
		
		
		
    });
<?php
  if($title_key > -1){
?>

  $('#ebtmaintable thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {
        var title = $(this).text();
        $(this).html( '<label class="d-none" for="searchclass">search</label><input type="text" id="searchclass" placeholder="Search classes" class="form-control form-control-sm search-endorsement" value=""/>' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
			
        } );
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
  /*$('div.top-filter').html('<?php //echo $filter_content; ?>');*/
  $('div.flt-btn').html('<?php echo $filter_content; ?>');


    $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();

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

 $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});
$( '.cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate= '';
    countFilterData();
});

$('.clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');

            $('input[name="creditFilter"]').val('');

            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light');  
            tags = '';
            courses = '';
            instructor = '';
            createdDate = '';
            creditFilter = '';
            table.draw();

      })

//filter
    $('#apply-filter-data').click(function(){
      courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      createdDate = $('input[name="createdbetween"]').val();

      //creditFilter = $.map($('input[name="creditFilter"]:checked'), function(c){return c.value; });
      //alert(creditFilter);

      $(".filter-area").toggleClass('d-none');
      table.draw();

    });


    $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
     // if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
      if(!container.is(e.target)){
      //  container.hide();
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
	$('.filter-icon').addClass('active bg-primary text-white').removeClass('bg-light'); 
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light');  
  }
 });  


     $('.filter-list input[type=checkbox]').change(function(){
          countFilterData();
      })


    function countFilterData()
    {

      var courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      var instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      //var creditFilter = $.map($('input[name="creditFilter[]"]'), function(c){return c.value; });
      var creditFilter = [4,50];
      //$.map($('input[name="creditFilter"]'), function(c){return c.value; });
     //alert(creditFilter);

      
          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'classcountdata',
              courses : courses,
              instructors : instructor,
              createdDate : createdDate,   
              creditHour : creditFilter,
        
          },
          success: function(response) {       
            var element  = document.getElementById("countFilterResult");
           
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
            }    
          }
        });
      }
$(document).ready(function(){
  <?php
  if($calendar_view || $calendar_view_classname){
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
$(document).ready(function(){

$("#slider-range").slider({
        range: true,
        min: <?php echo (int)$creditFilter['minRange']; ?>,
        max: <?php echo (int)$creditFilter['maxRange']; ?>,
        values: [<?php echo (int)$creditFilter['minRange']; ?>, <?php echo (int)$creditFilter['maxRange']; ?>],
		step: 5,
        
     slide: function(event, ui ) {
	    $( "#creditFilter" ).val(  ui.values[ 0 ] +'-'+  ui.values[ 1 ] );
      //countFilterData();  
       var courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      var instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      //var creditFilter = $.map($('input[name="creditFilter[]"]'), function(c){return c.value; });
       var     createdDate = $('input[name="createdbetween"]').val();



      
          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
		  
          data:{
              action:'classcountdata',
              courses : courses,
              instructors : instructor,
              createdDate : createdDate,   
              creditHour : [50,60],
			  minRange:50,
			  maxRange:60
        
          },
          success: function(response) {       
            var element  = document.getElementById("countFilterResult");
           
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
            }    
          }
        });

      }

});
    $( "#creditFilter" ).val(  $( "#slider-range" ).slider( "values", 0 ) +'-'+
       $( "#slider-range" ).slider( "values", 1 ) );
});






</script>