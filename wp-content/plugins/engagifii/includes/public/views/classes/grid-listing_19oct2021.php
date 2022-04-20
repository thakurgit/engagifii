<?php
  $default_length = '10';
  $calendar_view = false;
  if(isset($attr['records'])){
    $default_length = $attr['records'];
  }
  if(isset($attr['calendar'])){
    $calendar_view = $attr['calendar'];
  }
  $obj      =  new Engagifii_API();
  $collection   = array();
    $forDatatable   =   array();
    $date           =   date('Y-m-d');
    $options  = get_option( 'ebt_api_settings' );
    $class_visible_column_list = $options['class_visible_column_list'];
    
    $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[1]);
    unset($collection[7]);
    unset($collection[8]);
    

    $classes = $obj->getAllClassCourses($date);
    $creditFilter    = $obj->getCreditHoursFilter($date);
    $instructor = $obj->classAllInstructors($date);
    $dateRange  = $obj->classdateFilters($date);
    //print_r($dateRange);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
    $title_key = -1;
    
?>
<?php
  if($calendar_view){
?>
	<div class="container-fluid">
  <div class="row">
    <div class="col-11 text-center text-lg-right view-mode">
    	<div class="btn-group" role="group">
        			 <button type="button" id="calendar" class="btn bg-white border  bg-primary text-white" aria-pressed="false"><i class="fa fa-calendar-alt mr-2"></i>Calendar</button>
                  <button type="button" id="list" class="btn bg-white border" aria-pressed="false"><i class="fa fa-list mr-2"></i> List</button> 
                 
        </div>
    </div>
  </div>
  </div>

<div class="container-fluid">
  <?php echo do_shortcode('[class-calendar]'); ?>
</div>
<?php
  }
?>
<div class="containerEngagii" id="list_div">
  <div class="container-fluid engagifii-box engagifii-main-cotainer">
    <table  id="ebtmaintable" class="table table-bordered light-background main-list-here nowrap classes-page" style="width: 100% !important;">
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
	<div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative"><i class="fas fa-filter click-filter"></i><span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span></div> 
  <div class="filter-border">
  <div class="filter-area d-none">
    <div class="Engagiirow filter-top-bg col-sm-12">
      <div class="col-sm-6 text-left">
        <span class="filter-title">
          <i class="fas fa-filter"></i> Filter 
          <span id="blockedchecked"></span> 
        </span>
      </div>
      <div class="col-sm-6 text-right">
        <span class="clear-all" id="clear-all"> Clear All </span>
      </div>
    </div>
    <div class="col-sm-12 height-4">
      <input type="hidden" id="isApplyACtive" value="0">
      <div class="filter-list">
        <div class="heading-title">Course Name <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none">
          <ul class="list-group m-0">
          <?php foreach ($classes as $key => $value) { echo '<li class=""><label class="d-none" for="class_'.$key.'">Course name</label><input type="checkbox" id="class_'.$key.'" name="courseClass[]" value="'.addslashes($value['name']).'"> '.addslashes($value['name']).'</li>';} ?>
          </ul>
        </div>
      </div>
      <div class="filter-list">
        <div class="heading-title"> Instructor <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none"><ul class="list-group m-0">
          <?php

            foreach ($instructor as $key => $value) {
              echo '<li class=""><label class="d-none" for="instructor_'.$key.'">Instructor</label><input type="checkbox" id="instructor_'.$key.'" name="courseInstrutor[]" value="'.$value['id'].'"> '.addslashes($value['name']).'</li>';
            }
          ?>  
        </ul></div>
      </div>
      <div class="filter-list">
        <div class="heading-title"> <label for="createdbetween">Created Between</label> <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none position-relative">
          <input type="text" name="createdbetween" id="createdbetween"  class="form-control input-xs small-css" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span class="position-absolute cleardate mt-1 mr-1 text-secondary" style="right:0; top:0; cursor:pointer"><i class="fa fa-times"></i></span>
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
  var fv = 0;

   $('#list').click(function(){
	   		$(this).addClass('bg-primary text-white');
			$('#calendar').removeClass('bg-primary text-white');
            $('#list_div').show();
            $('#calendar_div').hide();
            $('#calendar_filter').hide();
        })
        $('#calendar').click(function(){
	   		$(this).addClass('bg-primary text-white');
			$('#list').removeClass('bg-primary text-white');
            $('#calendar_div').show();
            $('#calendar_filter').show();
            $('#list_div').hide();
        })

  var table = $('#ebtmaintable').DataTable( {
        "pageLength": '<?php echo $default_length; ?>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "dom": '<"row"<"col-md-11 col-10"><"top-filter col-md-1 col-2 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
        "bInfo":false,
        "processing": true,
        "searching": true,
        "ordering":true,
        "columnDefs": [ 
          { "targets": ['objectType','classDuration', 'classTag', 'classInstructorsCount', 'register'],
            "orderable": false
          },
          { width: 200, targets: 3 }
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
  <?php
}
  ?>

   	$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});
  $('div.top-filter').html('<?php echo $filter_content; ?>');


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
            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active');  
            tags = '';
            courses = '';
            instructor = '';
            createdDate = '';
            table.draw();

      })

//filter
    $('#apply-filter-data').click(function(){
      courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      createdDate = $('input[name="createdbetween"]').val();
      
      $(".filter-area").toggleClass('d-none');
      table.draw();

    });


    $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
        container.hide();
        $('.filter-area').addClass('d-none'); 
      }
      });

    // $("#ex18b").slider({
    //     min: <?php echo (int)$creditFilter['minRange']; ?>,
    //     max: <?php echo (int)$creditFilter['maxRange']; ?>,
    //     value: [<?php echo (int)$creditFilter['minRange']; ?>, <?php echo (int)$creditFilter['maxRange']; ?>],
    //     labelledby: ['ex18-label-2a', 'ex18-label-2b']
    //   });
	
	
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


     $('.filter-list input[type=checkbox]').change(function(){
          countFilterData();
      })


    function countFilterData()
    {

      var courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      var instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
     
      
          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'classcountdata',
              courses : courses,
              instructors : instructor,
              createdDate : createdDate,   
               
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