<?php
	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
  $course_visible_column_list = $options['course_visible_column_list'];
  
  $dataResponse = $this->submitApiRequest("Public/CourseColumnList",array(),"GET",'courses');
  $collection   = json_decode($dataResponse['api_response']);
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
?>

<div class="containerEngagii">
	<div class="container-fluid engagifii-box engagifii-main-cotainer">
  	<table  id="ebtmaintable" class="table table-bordered light-background main-list-here nowrap" style="width: 100% !important;">
    	<thead> 
		    <tr>        
		    	<?php
		    		if(is_array($collection) && count($collection)>0){
		    			foreach ($collection as $key => $value) {
		    				if(in_array($value->colName, $course_visible_column_list)){
		    					$forDatatable[]['data'] = $value->colName;
                  if($value->displayName == 'Course Type')
                  {
                     $value->displayName = "Type";
                  }
		    				?>
		    					<th class="<?php echo $value->colName; ?>"><?php echo $value->displayName; ?></th>
		    				<?php
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
	<div class="filter-icon"><i class="fas fa-filter click-filter"></i></div>
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
				<div class="heading-title">Class Name <i class="fa fa-angle-down pull-right"></i></div>
				<div class="content-area d-none">
					<ul class="list-group height-100">
					<?php foreach ($classes as $key => $value) { echo '<li class=""><input type="checkbox" name="courseClass[]" value="'.$value['name'].'"> '.$value['name'].'</li>';} ?>
					</ul>
				</div>
			</div>
			<div class="filter-list">
				<div class="heading-title"> Instructor <i class="fa fa-angle-down pull-right"></i></div>
				<div class="content-area d-none"><ul class="list-group height-100">
					<?php

						foreach ($instructor as $key => $value) {
							echo '<li class=""><input type="checkbox" name="courseInstrutor[]" value="'.$value['id'].'"> '.$value['name'].'</li>';
						}
					?>	
				</ul></div>
			</div>
			<div class="filter-list">
				<div class="heading-title"> Created Between <i class="fa fa-angle-down pull-right"></i></div>
				<div class="content-area d-none">
					<input type="text" name="createdbetween" readonly="readonly" class="form-control input-xs small-css" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
				</div>
			</div>
			<div class="filter-list">
				<div class="heading-title"> Tags <i class="fa fa-angle-down pull-right"></i></div>
				<div class="content-area d-none"><ul class="list-group height-100">
					<?php
						foreach ($tags as $key => $value) {
							echo '<li class=""><input type="checkbox" name="courseTag[]" value="'.$value['name'].'"> '.$value['name'].'</li>';
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

	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
       	"dom": '<"row"<"col-sm-11"f"><"top-filter col-sm-1 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-2"l><"col-sm-7 "p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
      	"columnDefs": [ 
          { "targets": ['objectType','creditHours', 'courseTags'],
            "orderable": false
          }
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
             $(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>
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


$('.clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            tags = '';
            createdDate = '';
            instructor = '';
            classes = '';
            table.draw();

      })

     
      $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0){
        container.hide();
        $('.filter-area').addClass('d-none');
      }
      });



</script>