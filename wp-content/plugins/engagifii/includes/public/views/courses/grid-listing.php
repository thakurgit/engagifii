<?php
	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
  $course_visible_column_list = $options['course_visible_column_list'];
  
  $dataResponse = $this->submitApiRequest("Public/CourseColumnList",array(),"GET",'courses');
  $collection   = json_decode($dataResponse['api_response']);
  if(!$collection){
    echo '<h5 class="text-center text-danger"><strong><em>Settings for this page are not complete.  Please contact your administrator.</em></strong><h5>';
    return;
  }
  unset($collection[0]);
  unset($collection[1]);
  unset($collection[5]);
  unset($collection[8]);

    $classes = $obj->courseAllClasses($date);
    $tags    = $obj->courseAllTags($date);
    $instructor = $obj->courseAllInstructors($date);
    $dateRange  = $obj->courseDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
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
<div class="containerEngagii">
<div class="container-fluid pb-4">
	<div class="row">
    	<div class="col-12 text-center text-lg-right d-flex align-items-center justify-content-end flt-btn"></div>
    </div>
</div>
	<div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
    	<thead> 
		    <tr>        
		    	<?php
		    		if(is_array($collection) && count($collection)>0){
              $i = 0;
		    			foreach ($collection as $key => $value) {
		    				if(in_array($value->colName, $course_visible_column_list)){
		    					$forDatatable[]['data'] = $value->colName;
                  if($value->displayName == 'Course Type')
                  {
                     $value->displayName = "Type";
                  }
                  if($value->colName == 'name'){
                    $title_key = $i;
                  }
		    				?>
		    					<th class="<?php echo $value->colName; ?>"><?php echo $value->displayName; ?></th>
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
				<span class="clear-all" id="clear-all"><i class="fal fa-sync"></i> </span>
			</div>
            </div>
		</div>
		<div class="col-sm-12 height-4">
      <input type="hidden" id="isApplyACtive" value="0">
			<div class="filter-list border-bottom">
				<div class="heading-title py-2 d-flex align-items-center justify-content-between">Class Name <i class="far fa-angle-down"></i></div>
				<div class="content-area d-none">
					<ul class="list-group m-0">
					<?php foreach ($classes as $key => $value) { echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" name="courseClass[]" value="'.addslashes($value['name']).'"><label><small> '.addslashes($value['name']).'</small></label></li>';} ?>
					</ul>
				</div>
			</div>
			<div class="filter-list border-bottom">
				<div class="heading-title py-2 d-flex align-items-center justify-content-between"> Instructor <i class="far fa-angle-down"></i></div>
				<div class="content-area d-none"><ul class="list-group m-0">
					<?php

						foreach ($instructor as $key => $value) {
							echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" name="courseInstrutor[]" value="'.$value['id'].'"><label><small> '.addslashes($value['name']).'</label></small></li>';
						}
					?>	
				</ul></div>
			</div>
			<div class="filter-list border-bottom">
				<div class="heading-title py-2 d-flex align-items-center justify-content-between"> Created Between <i class="far fa-angle-down"></i></div>
				<div class="content-area d-none position-relative">
					<input type="text" name="createdbetween"  class="form-control input-xs small-css" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
                      <span class="position-absolute cleardate mt-1 mr-1 text-secondary" style="right:0; top:0; cursor:pointer"><i class="fa fa-times"></i></span>
				</div>
			</div>
			<div class="filter-list border-bottom">
				<div class="heading-title py-2 d-flex align-items-center justify-content-between"> Tags <i class="far fa-angle-down"></i></div>
				<div class="content-area d-none"><ul class="list-group m-0">
					<?php
						foreach ($tags as $key => $value) {
							echo '<li class="d-flex align-items-start"><input class="mr-2 mt-1" type="checkbox" name="courseTag[]" value="'.addslashes($value['name']).'"><label><small> '.addslashes($value['name']).'</label></small></li>';
						}
					?>	
				</ul></div>
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
	var classes = '';
	var instructor = '';
	var tags       = ''; 
	var createdDate = '';
  var endDate     = '';
  var fv= 0;
	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
       	"dom": '<"row no-gutters"<"col-sm-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-2"l><"col-sm-7 "p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
      	"columnDefs": [ 
          { "targets": ['objectType','creditHours', 'courseTags','instructor','class'],
            "orderable": false
          },
		  { className: "title-col", "targets": "name" },
		  { className: "text-center", "targets": ["creditHours","instructor","class"] },
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search courses..."
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
            	d.action='courses'; 
            	d.classes = classes;
            	d.tags    = tags;
            	d.instructors = instructor;  
            	d.createdDate = createdDate;   
                 
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
            $('.dataTables_wrapper ').append('<span class="nxt position-absolute bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"><i class="far fa-angle-right"></i></span>');
            $('.dataTables_wrapper ').prepend('<span class="prv position-absolute bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"><i class="far fa-angle-left"></i></span>');
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
         }
    });
	$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});

  <?php
  if($title_key > -1){
?>

  $('#ebtmaintable thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search courses" class="form-control form-control-sm search-endorsement" value=""/>' );
 
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


  	//filter
  	$('#apply-filter-data').click(function(){
  		classes = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
  		instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
  		tags       = $.map($('input[name="courseTags[]"]:checked'), function(c){return c.value; });
  		createdDate = $('input[name="createdbetween"]').val();
      
      $(".filter-area").toggleClass('d-none');
  		table.draw();

  	});

 $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});
$( '.cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
    countFilterData();
});

$('.clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light'); 
            tags = '';
            createdDate = '';
            instructor = '';
            classes = '';
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

      classes = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      tags       = $.map($('input[name="courseTags[]"]:checked'), function(c){return c.value; });


          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
            action:'coursecountdata',
            classes : classes,
            tags    : tags,
            instructors : instructor, 
            createdDate : createdDate,
          },
          success: function(response) {       
            var element  = document.getElementById("countFilterResult");
            console.log(response);
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
            }    
          }
        });
      }

$(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});


</script>