<?php

	/*$obj 			=  new Engagifii_API();*/
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
    
    $colNames = $options['people_fields']['fields']; 
    //print_r($colNames);

  /* $classes = $obj->courseAllClasses($date);
    $tags    = $obj->courseAllTags($date);
    $instructor = $obj->courseAllInstructors($date);
    $dateRange  = $obj->courseDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));*/
    $title_key = -1;
?>
<style>
table tbody tr.selected {
	background-color: #bed6f2 !important;
}
</style>
<div class="container-fluid mb-3">
    	<div class="d-flex align-items-center">
            	<h4 class="mb-0 mr-3"><button type="button" title="Refresh Downloads" class="refresh btn shadow-none p-2 mr-2"> <i class="fas fa-sync"></i></button><?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/download_blue.png" class="img-icon-lg img-fluid" alt="download-icon" >'; ?></h4>
                <div class="mr-5">
                <h5 class="mb-0">Members</h5>                
                </div>        	
                <button  type="button" class="btn btn-primary btn-sm ml-3 gt ml-auto" data-toggle="tooltip" data-placement="top" title="Select Course" disabled><i class="far fa-file-pdf mr-2"></i>Generate Credits Earned Report</button>
        </div>
</div>
<div class="containerEngagii d-none">
<div class="container-fluid pb-4">
    	<div class="text-center text-lg-right d-flex align-items-center justify-content-end flt-btn-course"></div>
    </div>
</div>
	<div class="engagifii-box engagifii-main-cotainer position-relative">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	<?php
				$i=0;
				//$colNames =['people-select','People Name', 'Email', 'Current Position', 'Organization','Person Type']; //,'Current Department','Person Type','Organization', 'Total Time Served in Committees','Roles', 'Total Time Worked'
		    			foreach ($colNames as $key) {
		    			$forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
                  if($key == 'People Name'){
                    $title_key = $i;
                  }
				  $i++;
				  if($key == 'people-select'){
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

<script type="text/javascript">
var startDate = '1970-01-01T00:00:00';
var endDate = '<?php echo date('Y-m-d').'T23:59:59';?>';
 var titleColumn = '<?php echo $title_key; ?>';
  var profileId = localStorage.getItem("logged_in_user");
  var selectedRow=[];
  var val;
	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
				  "dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
		"order": [[<?php echo array_search('People Name',$colNames);?>, 'asc']],
      	"columnDefs": [ 
          { "targets": ['people-select','peoplename','email','currentposition', 'organization', 'persontype'],
            "orderable": false
          },
		  { width: 350, targets: <?php echo array_search('People Name',$colNames);?> },
		  { width: 150, targets: <?php echo array_search('Email',$colNames);?> },
		  { className: "", "targets": ['people-select', 'peoplename','email','currentposition', 'organization', 'persontype'] },
		   
        ],
		
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search People"
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
            	d.action='peopleList'; 
            	//d.profileId = profileId;
            	//d.startDate = startDate;
            	//d.endDate = endDate;
				d.titleColumn = titleColumn; 
                 
            }, 
        },
        createdRow: function (row, data, index) { 
            // $(row).addClass( 'bg-white1' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 
     "drawCallback": function( settings ) {
		 	
            dt_dropdown();
          // dt_scroll();
			   $('[data-toggle="tooltip"]').tooltip() ; 
			   if(selectedRow.length !== 0){
				 $('.gt').removeAttr('disabled'); 
				 $('.gt').attr('data-original-title', 'Download PDF'); 
			   }else{
				 $('.gt').attr('disabled','');  
				 $('.gt').attr('data-original-title', 'Select Course');
				 $('.gt').tooltip('hide');
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
				 $('.gt').attr('data-original-title', 'Download PDF');  
			   }else{
				 $('.gt').attr('disabled',''); 
				 $('.gt').attr('data-original-title', 'Download PDF'); 
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
			var frow = $('#ebtmaintable tbody tr:first-child');
			  $(frow).addClass('bg-secondary');
         },
		  "initComplete": function(settings, json) {
			//dt_filterActivate();
			
			  $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
			  
		

    },
    });
	
	 $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
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

 dt_titleSearch('Search Member');

  <?php
}
  ?>


	

$(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});


</script> 