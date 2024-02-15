<?php
	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
  

   $classes = $obj->courseAllClasses($date);
    $tags    = $obj->courseAllTags($date);
    $instructor = $obj->courseAllInstructors($date);
    $dateRange  = $obj->courseDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
    $title_key = -1;
?>
<style>
table tbody tr.selected {
	background-color: #bed6f2 !important;
}
</style>
<div class="containerEngagii d-none">
<div class="container-fluid pb-4">
    	<div class="text-center text-lg-right d-flex align-items-center justify-content-end flt-btn-course"></div>
    </div>
</div>
	<div class="engagifii-box engagifii-main-cotainer position-relative">
  	<table  id="courseByPerson" class="table table-bordered border-0 table-striped main-list-here course-page " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	<?php
				$i=0;
				$colNames =['course-select','Course Name','Course Type','Total Credit Earned','Classes','Completion Date'];
		    			foreach ($colNames as $key) {
		    			$forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
                  if($key == 'Course Name'){
                    $title_key = $i;
                  }
				  $i++;
				  if($key == 'course-select'){
					echo '<th class="'.$key.'"><input type="checkbox"></th>'; 
					continue; 
				  }
		    				?>
		    					<th class="<?php echo preg_replace('/\s+/', '', strtolower($key)); ?>"><?php echo $key ?></th>
		    				<?php
                  
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
        <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data1">Apply 
          <span id="coursecountFilterResult"></span>
        </button>
      </div>
	</div>
</div>
</div>
<?php
$filter_course =ob_get_contents();
ob_end_clean();
if(function_exists('removeWhitespace')){
$filter_course = removeWhitespace($filter_course);
}
?>
<script type="text/javascript">
var startDate = '1970-01-01T00:00:00';
var endDate = '<?php echo date('Y-m-d').'T00:00:00';?>';
 var titleColumn = '<?php echo $title_key; ?>';
  var profileId = localStorage.getItem("logged_in_user");
  var selectedRow=[];
  var val;
	var table = $('#courseByPerson').DataTable( {
       	"pageLength": 10,
				  "dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
		"order": [[<?php echo array_search('Course Name',$colNames);?>, 'asc']],
      	"columnDefs": [ 
          { "targets": ['course-select','tags','coursetype','completiondate','credithours','classes'],
            "orderable": false
          },
		  //{ className: "title-col", "targets": "name" },
		  { className: "text-center", "targets": ["completiondate","credithours","tags","coursetype","course-select","classes"] },
		   
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
            	d.action='coursesByPerson'; 
            	d.profileId = profileId;
            	d.startDate = startDate;
            	d.endDate = endDate;
				 //d.titleColumn = titleColumn; 
                 
            }, 
        },
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 
     "drawCallback": function( settings ) {
            dt_dropdown();
          // dt_scroll();
			   $('[data-toggle="tooltip"]').tooltip() ; 
			   if(selectedRow.length !== 0){
				 $('.gt').removeAttr('disabled');  
			   }else{
				 $('.gt').attr('disabled','');  
			   }
			$('.select-row').each(function(){
				if(selectedRow.includes($(this).val())){
					$(this).prop('checked',true).change();  	
				}
			$(this).change(function(){
				if ($(this).is(':checked')) {
					$(this).parents('tr').addClass('selected');	
					val =$(this).val();
					if(selectedRow.indexOf(val)===-1){
						selectedRow.push(val);
					}
				}else{
					$(this).parents('tr').removeClass('selected');	
					const index = selectedRow.indexOf($(this).val());
					if (index > -1) {
					  selectedRow.splice(index, 1); 
					}
				}
					
					if($('.select-row:checked').length==0){
						 $(".course-select :checkbox").prop("indeterminate", false);	
						 $(".course-select :checkbox").prop("checked", false);	
					} else{
						$(".course-select :checkbox").prop("indeterminate", true);
						if($('.select-row:checked').length==$('.select-row').length){
						 	$(".course-select :checkbox").prop("indeterminate", false);	
							 $(".course-select :checkbox").prop("checked", true);	
						}
					}
			   if(selectedRow.length !== 0){
				 $('.gt').removeAttr('disabled');  
			   }else{
				 $('.gt').attr('disabled','');  
			   }
			});	
			});
			$(".course-select :checkbox").change(function(){
				if ($(this).is(':checked')) {
					$('.select-row').prop('checked',true).change(); 	
				}else{
					$('.select-row').prop('checked',false).change(); 
				}
			});
         },
		  "initComplete": function(settings, json) {
			//dt_filterActivate();
			
			  $('#courseByPerson_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
			  
		

    },
    });
	 $('#courseByPerson').on( 'processing.dt', function ( e, settings, processing ) {
        $('#courseByPerson_wrapper').siblings('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();
	

$('.gt').click(function(){
	var logged_in_user = localStorage.getItem("logged_in_user");
	   $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'generateDownloads',
			  CourseId:selectedRow,
			  groupById: logged_in_user,
          },
          success: function(response) { 
		  	$('#pdfcreated').modal('show')
			
		  }
        });
});

 $( document ).ready(function() {
   // $('input[name="createdbetween"]').val('');
    $('.dateFilter input').val('');
});
//date filter
$('.dateFilter input').daterangepicker({
  // minDate:'<?php //echo $class_start_date; ?>',
   // maxDate: '<?php //echo $class_end_date; ?>',
    autoApply: true
  }, function(start, end) {
      var classDates = start.format('YYYY-MM-DD')+'to'+end.format('YYYY-MM-DD');
		var classDate = classDates.split("to");
	 	startDate = $.trim(classDate[0])+'T00:00:00';
		endDate = $.trim(classDate[1])+'T00:00:00';
    });
	$('.dateFilter input').change(function(){
		if($(this).val()!==''){
			$( '.clearDateFilter' ).show();
		}
	});
$( '.clearDateFilter' ).click(function() {
		 $('.dateFilter input').val('');
		 $(this).hide();
 startDate = '1970-01-01T00:00:00';
 endDate = '<?php echo date('Y-m-d').'T00:00:00';?>';
		 table.draw();
});
$( '.dateFilter button' ).click(function() {
		 table.draw();
});

<?php
  if($title_key > -1){
?>

  $('#courseByPerson1 thead tr th:eq('+titleColumn+')').each( function (i) {
 
         var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" id="searchcourses" placeholder="Search courses" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:5;display:none"><i class="fal fa-times"></i></button></div>' );

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
$('th .clear-search').click(function(e){
	 $('#searchcourses').val('');
	$('.clear-search').hide();
	e.stopPropagation();
	table.column(titleColumn).search('').draw();
 });

    } );
	
	$(document).ready(function (){    
    $('#searchcourses, .search-dt span').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchcourses').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});
});
  <?php
}
  ?>


	$('div.flt-btn-course').html('<?php echo $filter_course; ?>');

    
   /* $('.flt-btn-course .filter-icon').click(function(e){
        e.stopPropagation();
        $(this).siblings('.filter-border').show();
       $(this).siblings('.filter-border').find('.filter-area').toggleClass('d-none');
        $('#isApplyACtive').val(1);
		jQuery(".filter-area .list-group").mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:"minimal-dark",
		 			scrollbarPosition:"outside"
		 			});
    });

   $('.flt-btn-course .heading-title').click(function(){
		$(this).next('.content-area').toggleClass('d-none');
		$(this).parent().siblings('.filter-list').find('.content-area').addClass('d-none');
	});*/

   /*$('input[name="createdbetween"]').daterangepicker({
   minDate:'<?php //echo $min_date; ?>',
    maxDate: '<?php //echo $max_date; ?>',
    autoApply: true
  }, function(start, end) {
      createdDate = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
      coursecountFilterData();

    });*/


  	//filter
  	/*$('#apply-filter-data1').click(function(){
  		classes = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
  		instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
  		tags       = $.map($('input[name="courseTags[]"]:checked'), function(c){return c.value; });
  		createdDate = $('input[name="createdbetween"]').val();
      
      $(".flt-btn-course .filter-area").toggleClass('d-none');
  		table.draw();

  	});*/

/*$( '.flt-btn-course .cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
    coursecountFilterData();
});*/

/*$('.flt-btn-course .clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#coursecountFilterResult').html(' ');
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
      });*/
	  
	  
	  
	/*  $("#apply-filter-data1").click(function () {
 
  
  $('.flt-btn-course .filter-list').each(function() {
	 if ($(this).find('input[type=checkbox]').is(':checked')) {
		$(this).addClass('checked');
	 } else {
		$(this).removeClass('checked');
	 }
  });
  fv = $('.flt-btn-course .filter-list.checked').length;
  if(fv>0){
	$('.filter-icon').addClass('active bg-primary text-white').removeClass('bg-light');
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light'); 
  }
 });  

    $('.flt-btn-course .filter-list input[type=checkbox]').change(function(){
          coursecountFilterData();
      })


    function coursecountFilterData()
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
            var element  = document.getElementById("coursecountFilterResult");
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
            }    
          }
        });
      }*/

$(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});


</script> 