<?php 
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
if (isset($_COOKIE['pid'])) {
    $pid = $_COOKIE['pid'];
  }
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
if(!$pid) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
} 
 include 'sidebar_nav.php'; 
	/*$obj 			=  new Engagifii_API();*/
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
    $colNames = $options['people_fields']['fields']; 
    //print_r($colNames);
    $columnSearch_key = [];
?>
<style>
table tbody tr.selected {
	background-color: #bed6f2 !important;
}
</style>
<div class="container-fluid mb-3">
    	<div class="d-flex align-items-center">
            	<h4 class="mb-0 mr-2">
                	<button type="button" title="Refresh Downloads" class="refresh btn shadow-none p-2 mr-1"> <i class="fas fa-sync"></i></button><?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/download_blue.png" class="img-fluid" alt="member-icon" style="max-width:40px" >'; ?></h4>
                <h5 class="mb-0">Members</h5>                
                <button  type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm  ml-auto gt"  title="Select Member" disabled><i class="far fa-file-pdf mr-2"></i>Generate Credits Earned Report</button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Generate Credits Earned Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group dateFilter">
                        <label>Select date range</label>
                        <div class="input-group mr-2" style="max-width:255px">
                        <input type="text" class="form-control form-control-sm shadow-none" placeholder="Select Date Range">
                      <div class="input-group-append">
                        <span class="input-group-text bg-transparent clearDateFilter" style="cursor:pointer; display:none;"><i class="far fa-times"></i></span>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text "><i class="far fa-calendar-alt"></i></span>
                      </div>
                    </div>
                    </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="dropdown dropleft po-filter d-flex justify-content-end ml-3">
                  <button class="btn border rounded-circle filter-toggle bg-light d-flex align-items-center justify-content-center position-relative" type="button" data-toggle="dropdown" aria-expanded="false"> <i class="far fa-filter"></i> </button>
                  <div class="dropdown-menu py-0">
                    <div class="filter-top-bg py-2 px-3 bg-dark text-white d-flex align-items-center"> <span class="filter-title"> <i class="far fa-filter mr-2"></i> Filter </span> <span class="clear-all ml-auto" id="clear-all" title="Reset Filter"> <i class="fal fa-sync"></i> </span> </div>
                    <div class="accordion" id="accordionFilter">
                      <?php $ft=0; foreach ($filterParam as $key => $values) { ?>
                      <div class="border-bottom" data-filter="<?php echo str_replace(array( ' ' ), '', strtolower($values)); ?>">
                        <h5 class="mb-0">
                          <button class="btn btn-block text-left d-flex align-items-center shadow-none px-3 py-1 <?php if($ft % 2 == 1){ echo 'bg-light'; } ?>" type="button" data-toggle="collapse" data-target="#filter-<?php echo $ft; ?>" ><?php echo $values; ?><span class="ml-2 font-weight-bold ft-counter text-black"></span><i class="fal fa-chevron-down ml-auto"></i> </button>
                        </h5>
                        <div  id="filter-<?php echo $ft; ?>" class="collapse px-3" data-parent="#accordionFilter">
                          <ul class="list-group td-dropdown mb-3" style="overflow:auto; max-height:200px">
                            <div class="loaders text-center py-3">
                              <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                            </div>
                          </ul>
                        </div>
                      </div>
                      <?php $ft++; } ?>
                    </div>
                    <div class="text-center py-2">
                      <button class="filter_submit btn btn-primary py-1" type="submit">Apply</button>
                    </div>
                  </div>
                </div>
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
                    $columnSearch_key[0]['key'] = $i;
                    $columnSearch_key[0]['placeholder'] = 'Search Member';
                  }
                  if($key == 'Email'){
                    $columnSearch_key[1]['key'] = $i;
                    $columnSearch_key[1]['placeholder'] = 'Search Office Email';
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
 var columnSearch = '<?php echo json_encode( $columnSearch_key); ?>';
 columnSearch = JSON.parse(columnSearch);
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
          { "targets": ['people-select','email','currentposition', 'organization', 'persontype'],
            "orderable": false
          },
		  <?php if(in_array('People Name', $colNames)){ ?>
		  	{ width: 350, targets: <?php echo array_search('People Name',$colNames);?> },
		  <?php } if(in_array('Email', $colNames)){ ?>
		  { width: 150, targets: <?php echo array_search('Email',$colNames);?> },
		  <?php } ?>
		  { className: "text-center", "targets": ['people-select'] },
		   
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
				d.titleColumn = columnSearch[0]['key']; 
				<?php if(in_array('Email', $colNames)){ ?>
				d.emailColumn = columnSearch[1]['key']; 
				<?php } ?>
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
						 $(".people-select :checkbox").prop("indeterminate", false);	
						 $(".people-select :checkbox").prop("checked", false);	
					} else{
						$(".people-select :checkbox").prop("indeterminate", true);
						if($('.select-row:checked').length==$('.select-row').length){
						 	$(".people-select :checkbox").prop("indeterminate", false);	
							 $(".people-select :checkbox").prop("checked", true);	
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
			$(".people-select :checkbox").change(function(){
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
  if(count($columnSearch_key)>0){
?>
for (var i = 0; i < columnSearch.length; i++) {
 dt_columnSearch(columnSearch[i].key,columnSearch[i].placeholder);
}
  <?php
}
  ?>


	

$(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});


</script> 