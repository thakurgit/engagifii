<?php 
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
if (! is_user_logged_in()) {
	$login = 'moOAuthLoginNew("Engagifii")';
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center mb-0'>This page is restricted. Please";
	echo "<a href='javascript:void' class='btn btn-warning mx-2 px-2 py-0' onclick='".$login."' > Login </a>";
    //printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo 'to view this page.</h5></div>';
	?>
    <script>
	document.addEventListener('DOMContentLoaded', function() {
		clearAllCookies('false');
	});
    </script>
    <?php
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
	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
    $colNames = $options['people_fields']['fields']; 
	$filterParams=array_intersect($colNames,['Organization','Position','Department','Status','Total Time']);
    //print_r($filterParams);
    $columnSearch_key = [];
    $fiscalYear  = $obj->getFiscalYear();
$fiscalYearResponse = json_decode($fiscalYear['api_response'])->collection;
//print_r($fiscalYear); 

$largestStartDate = null;
$largestEndDate = null;

foreach ($fiscalYearResponse as $fiscalYear) {
    $startDate = strtotime($fiscalYear->startDate);
    $endDate = strtotime($fiscalYear->endDate);

    if ($largestStartDate === null || $startDate > $largestStartDate) {
        $largestStartDate = $startDate;
         $largestFiscalYearName = $fiscalYear->name;
   }

    if ($largestEndDate === null || $endDate > $largestEndDate) {
        $largestEndDate = $endDate;
    }
}
//$fiscalStartDate = date('Y-m-d', $largestStartDate );
//$fiscalEndDate = date('Y-m-d', $largestEndDate );
$fiscalStartDate = $largestStartDate ? date('Y-m-d', $largestStartDate) : '2024-01-01';
$fiscalEndDate = $largestEndDate ? date('Y-m-d', $largestEndDate) : date('Y-m-d');
// print_r($fiscalStartDate);
// print_r($fiscalEndDate);
?>
<style>
	th.peoplename, th.email {
    min-width: 150px;
}	
</style>
<div class="container-fluid mb-3">
    	<div class="d-flex align-items-center">
            	<h4 class="mb-0 mr-2">
                	<button type="button" title="Refresh Members" class="refresh btn shadow-none p-2 mr-1"> <i class="fas fa-sync"></i></button><?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/Member-Icon.png" class="img-fluid" alt="member-icon" style="max-width:40px" >'; ?></h4>
                <h5 class="mb-0">Members</h5>                
                <button  type="button" class="btn btn-primary btn-sm  ml-auto ga"  title="Select Member" disabled><i class="far fa-file-pdf mr-2"></i>Generate Awards Report</button>
				<button  type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm  ml-2 gt"  title="Select Member" disabled><i class="far fa-file-pdf mr-2"></i>Generate Credits Earned Report</button>
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
                        <label>Select Date Range</label>
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
					<div>Note: The report will only show data of the members who have earned credits in the selected date range</div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary gtm">Submit<span style="display:none" role="status" aria-hidden="true" class="spinner-border spinner-border-sm ml-2 mb-1"></span></button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="dropdown dropleft po-filter d-flex justify-content-end ml-3">
                  <button class="btn border rounded-circle filter-toggle bg-light d-flex align-items-center justify-content-center position-relative" type="button" data-toggle="dropdown" aria-expanded="false"> <i class="far fa-filter"></i> </button>
                  <div class="dropdown-menu py-0">
                    <div class="filter-top-bg py-2 px-3 bg-dark text-white d-flex align-items-center"> <span class="filter-title"> <i class="far fa-filter mr-2"></i> Filter </span> <span class="clear-all ml-auto" id="clear-all" title="Reset Filter">Clear All</span> </div>
                    <div class="accordion" id="accordionFilter">
                      <?php $ft=0; foreach ($filterParams as $key => $values) { 
					?>
                      <div class="filter-list border-bottom" data-filter="<?php echo str_replace(array( ' ' ), '', strtolower($values)); ?>">
                        <h5 class="mb-0">
							<?php 
								if($values =='Total Time'){ 
									$filterTitle = "Total Time Worked (In Years)";
								} else{ 
										$filterTitle = $values;
									}
								?>
									<button class="btn btn-block text-left d-flex align-items-center shadow-none px-3 py-1 <?php if($ft % 2 == 1){ echo 'bg-light'; } ?>" type="button" data-toggle="collapse" data-target="#filter-<?php echo $ft; ?>" ><?php echo $filterTitle; ?><span class="ml-2 font-weight-bold ft-counter text-black"></span><i class="fal fa-chevron-down ml-auto"></i> </button>
						</h5>
                        <div  id="filter-<?php echo $ft; ?>" class="collapse px-3" data-parent="#accordionFilter">
                        	<?php if($values =='Total Time'){ ?>
                            <div class="d-flex align-items-center py-2 timework">
                            <input class="form-control form-control-sm min" placeholder="1" type="number" min="0" step="1"><span class="px-2">to</span><input class="form-control form-control-sm max" type="number" min="0" step="1" placeholder="2">
                            </div>
                            
                            <?php } else { ?>
                        	  <ul class="list-group td-dropdown mb-3" style="overflow:auto; max-height:200px">
                            <div class="loaders text-center py-3">
                              <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                            </div>
                          </ul>
                            <?php } ?>
                        </div>
                      </div>
                      <?php $ft++; } ?>
                    </div>
                    <div class="text-center py-2">
                    <button class="btn btn-primary py-1" type="button" name="callmasterApi" id="apply-filter-data">Apply<span class="mx-1" id="countFilterResult"></span><div class="spinner-border spinner-border-sm d-none mb-1" role="status"><span class="sr-only">Loading...</span></div>      
        </button></div>
                  </div>
                </div>
        </div>
</div>
<div class="container-fluid mb-3 bg-light py-2 text-center d-none memberSelect">
	<span class="currentSelected"></span>/<span class="totalMembers"></span> members selected. <button class="btn btn-link p-0 selectAll shadow-none">Select all <span class="totalMembers"></span> members</button><button class="btn btn-link p-0 deSelectAll d-none shadow-none">Clear selection</button>
</div>
	<div class="engagifii-box engagifii-main-cotainer position-relative px-xl-5">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page nowrap " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	<?php
				$i=0;
		    			foreach ($colNames as $key) {
		    			$forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
                  if($key == 'People Name'){
					$searchObject=[];
					$searchObject['key'] = $i;
					$searchObject['placeholder'] = 'Search Member';
					$searchObject['column'] = $key;
					$columnSearch_key[]=$searchObject;
                  }
                  if($key == 'Email'){
					$searchObject=[];
					$searchObject['key'] = $i;
                    $searchObject['placeholder'] = 'Search Office Email';
					$searchObject['column'] = $key;
					$columnSearch_key[]=$searchObject;
                  }
                  if($key == 'Organization'){
                   $key = 'Current Organization';
                  }
                  if($key == 'Total Time'){
                    $key = 'Total Time Worked';
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
<div class="modal fade" id="pdfcreated" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0 border-0">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close p-2" data-dismiss="modal" aria-label="Close" style="z-index:9">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<p class="text-center">Your file is being prepared. When the file is ready, it will be available under your <a target="_blank" href="<?php echo site_url(); ?>/my-profile/my-transcript/downloads">My Downloads</a>. </p>
      </div>
      
    </div>
  </div>
</div>
<div class="modal fade" id="nocredit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0 border-0">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close p-2" data-dismiss="modal" aria-label="Close" style="z-index:9">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<p class="text-center">None of the selected members have earned credits in the selected date range.</p>
      </div>
      
    </div>
  </div>
</div>
<script type="text/javascript">
var positions = [], departments = [], orgs=[], Status=[], totalTime=[], selectedRow=[];
var titleColumn, emailColumn, val, totalRecords;
var startDate = '1970-01-01T00:00:00';
var endDate = '<?php echo date('Y-m-d').'T23:59:59';?>';
 var columnSearch = '<?php echo json_encode( $columnSearch_key); ?>';
 columnSearch = JSON.parse(columnSearch);
 for (let i = 0; i < columnSearch.length; i++) {
 	if(columnSearch[i].column=='People Name'){
		titleColumn=columnSearch[i].key;
	}if(columnSearch[i].column=='Email'){
		emailColumn=columnSearch[i].key;
	}
} 
  var profileId = localStorage.getItem("logged_in_user");
  var filterSubmitted = false;
var selectAll = false;
	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
		"order": [[<?php echo array_search('People Name',$colNames);?>, 'asc']],
      	"columnDefs": [ 
          { "targets": ['people-select','email','position', 'status', 'officephone', 'lastlogin', 'lastupdated', 'persontype', 'currentorganization','department','totaltimeworked','primaryorganization'],
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
				d.positions=positions; 
				d.departments=departments; 
				d.orgs=orgs; 
      			  d.status=Status;
				d.totalTime= totalTime;
				d.titleColumn = titleColumn; 
				d.emailColumn = emailColumn; 
            }, 
        },
        createdRow: function (row, data, index) { 
            // $(row).addClass( 'bg-white1' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 
     "drawCallback": function( settings ) {
            dt_dropdown();
			   $('[data-toggle="tooltip"]').tooltip() ; 
		   var thSelect= $(".people-select :checkbox");
			   if(selectedRow.length > 0 || selectedRow =='all'){
				$('.gt').removeAttr('disabled');
				$('.ga').removeAttr('disabled');
				  }else{
				$('.gt').attr('disabled',''); 
				$('.ga').attr('disabled',''); 
         		  }
			$('.select-row').each(function(){
				if(selectedRow.includes($(this).val()) || selectedRow =='all'){
					$(this).prop('checked',true).change().parents('tr').addClass('selected');  
					if(selectedRow =='all'){
						$(this).attr('disabled','');	
					}
				}
				$(this).change(function(){
					var orgId = $(this).parents('tr').find('td div[data-orgId]').attr('data-orgId');
					localStorage.setItem('orgId', orgId);
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
						if($('tr.selected').length==0){
							 thSelect.prop("indeterminate", false).prop("checked", false);
						} else{
							thSelect.prop("indeterminate", true);
							if($('tr.selected').length==settings.aoData.length){
								thSelect.prop("checked", true).prop("indeterminate", false);	
							}
						}
						$('.currentSelected').text(selectedRow.length);
						if(selectedRow.length !== 0){
							$('.gt').removeAttr('disabled');
							$('.ga').removeAttr('disabled');
							$('.memberSelect').removeClass('d-none');
						}else{
							$('.gt').attr('disabled',''); 
							$('.ga').attr('disabled','');
							$('.memberSelect').addClass('d-none');
						 }
						if(selectedRow.length==totalRecords){
							$('.deSelectAll').removeClass('d-none');
							$('.selectAll').addClass('d-none');		
						} else {
							$('.deSelectAll').addClass('d-none');
							$('.selectAll').removeClass('d-none');		
						}
				});	
			});
			thSelect.change(function(){
				if ($(this).is(':checked')) {
					$('.select-row').prop('checked',true).change(); 
					$('.memberSelect').removeClass('d-none');
					$('.currentSelected').text(selectedRow.length);
				}else{
					$('.select-row').prop('checked',false).change(); 
				}
			});
		   if($('tr.selected').length==settings.aoData.length){
				thSelect.prop("checked", true).prop("indeterminate", false);   
		   }
		   if($('tr.selected').length<settings.aoData.length && $('tr.selected').length>0){
			  $(".people-select :checkbox").prop("indeterminate", true); 
		   } 
		   if($('tr.selected').length==0){
			  thSelect.prop("checked", false).prop("indeterminate", false);
		   }
		   $('.selectAll').click(function(){
			  $(this).addClass('d-none');
			  $('.deSelectAll').removeClass('d-none');
			  selectedRow = 'all';
			  thSelect.prop('checked',true).prop("indeterminate", false).attr('disabled','');
			  $('.select-row').each(function(){
				$(this).prop('checked',true).attr('disabled','').parents('tr').addClass('selected');
			  });
			  $('.currentSelected').text(settings._iRecordsTotal);
			   $('.gt').removeAttr('disabled');
			   $('.ga').removeAttr('disabled');
			});
			$('.deSelectAll').click(function(e){
			  $(this).addClass('d-none');  
			  $('.selectAll').removeClass('d-none'); 
			  selectedRow = [];
			  thSelect.removeAttr('disabled').prop("checked", false).prop("indeterminate", false);
			  $('.select-row').each(function(){
				$(this).removeAttr('disabled').prop('checked',false).parents('tr').removeClass('selected');
			  });
			  $('.currentSelected').text('0');
			   $('.gt').attr('disabled','');
			   $('.ga').attr('disabled','');
			   e.stopPropagation();
			});

			 $('#apply-filter-data .spinner-border').addClass('d-none');
			 $('#apply-filter-data').removeAttr('disabled')
			if(filterSubmitted){
				 var element  = document.getElementById("countFilterResult");
				if(element){
					 element.innerHTML = " ("+settings._iRecordsTotal+")";
				 } 
				 filterSubmitted = false;
				// $('.deSelectAll').trigger('click'); 
			}
         },
		  "initComplete": function(settings, json) {
			  	totalRecords=settings._iRecordsTotal;
				$('.totalMembers').text(totalRecords);
			//dt_filterActivate();
			           dt_scroll();
			  $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
    },
    });
$(document).on('click', '.po-filter .dropdown-menu', function (e) {
	e.stopPropagation();
});	
	 $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();	
$('.gtm').click(function(){
	$(this).attr('disabled','').find('span.spinner-border').show();
	var molOrgId = '';
	molOrgId = localStorage.getItem('orgId');
  //var selectedIds = selectedRow.join();
  var selectedDateRange = $('.dateFilter input').val();
  var dates = selectedDateRange.split('-'); // Split the selectedDateRange by '-' delimiter
  var startDate = dates[0]; // Start date
  var endDate = dates[1]; // End date
  //alert(selectedDateRange); isCreditEarnedByParticipant
	var logged_in_user = localStorage.getItem("logged_in_user");
	$.ajax({
		type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'isCreditEarnedByParticipant',
			  participantsIds: selectedRow,
			  startDate: startDate, 
			  endDate: endDate, 
          },
    success: function(response) { 
		var jsonResponse = JSON.parse(response);
      if (jsonResponse.api_status && jsonResponse.api_response === "true") { 
		$.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'generateDownloadsByMemberIds',
			  memberIds: selectedRow,
			  selectedStartDate: startDate, 
			  selectedEndDate: endDate, 
			  molOrgId:molOrgId
          },
          success: function(response) { 
			$('.gtm').removeAttr('disabled').find('span.spinner-border').hide();
            $('#exampleModal').modal('hide') ;
		  	$('#pdfcreated').modal('show');
			
		  }
        });
	  }
	  else{
		$('#exampleModal').modal('hide') ;
		$('#nocredit').modal('show');
		$('.gtm').removeAttr('disabled').find('span.spinner-border').hide();
	  }
	}
});
});
$('.ga').click(function(){
	$(this).attr('disabled','').find('span.spinner-border').show();
	var selectedIds = selectedRow.join();
	var logged_in_user = localStorage.getItem("logged_in_user");
		  $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'generateAwardsDownloadsByMemberIds',
			  memberIds: selectedRow,
			  },
          success: function(response) { 
			$('.ga').removeAttr('disabled').find('span.spinner-border').hide();
            $('#pdfcreated').modal('show');
			
		  }
        });
	  });	  
	

 $( document ).ready(function() {
   // $('input[name="createdbetween"]').val('');
   $('.dateFilter input').val('');
   <?php  if ($fiscalStartDate){ ?>
      var defaultStartDate = '<?php echo date("m/d/Y", strtotime($fiscalStartDate)); ?>';
     var defaultEndDate = '<?php echo date("m/d/Y", strtotime($fiscalEndDate)); ?>';
    $('.dateFilter input').val(defaultStartDate + ' - ' + defaultEndDate);
   <?php }   ?>
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
		 //table.draw();
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
var colNames = <?php echo json_encode($filterParams); ?>;
//console.log(colNames);
window.addEventListener("load", function () {
  //console.log('hellooo');
  $.ajax({
		type : "post",
		url: engagifiiUrl_ajaxurl,
		data:{
		   action:'peopleFilters',
		   filterParams: colNames,
		},
		success: function(response) {     
		for (var key of Object.keys(JSON.parse(response))) {
			var index = Object.keys(JSON.parse(response)).indexOf(key);
			$('#filter-'+index+' ul').html(JSON.parse(response)[key]);
		}
		filterEvents();
			}
	  });
});
$('.refresh').click(function(){
		table.draw();
	});
function filterEvents(){
	$('.po-filter ul').each(function() {
		  	$('input', this).prop('checked', false);
		  	var ftSelected = 0;
		  	$('input', this).change(function() {
					positions = $.map($('input[name="peoplePosition[]"]:checked'), function(c){return c.value; });
					departments = $.map($('input[name="peopleDepartement[]"]:checked'), function(c){return c.value; });
					orgs = $.map($('input[name="peopleOrganization[]"]:checked'), function(c){return c.value; });
				  Status = $.map($('input[name="peopleStatus[]"]:checked'), function(c){return c.value; });
				  if(!selectAll){
					  countFilterData();
				  }
		  		ftSelected = $(this).parents('ul').find('input:checkbox:checked').length;
		  		if(ftSelected > 0) {
		  			$(this).parents('.border-bottom').addClass('ft-active').find('.ft-counter').text('(' + ftSelected + ')');
		  		} else {
		  			$(this).parents('.border-bottom').removeClass('ft-active').find('.ft-counter').text('');
		  		}
		  	});
			if($('li', this).length>0){
		 	 	$('<input class="form-control my-2 form-control-sm bg-light ft-list" placeholder="Search..."/><div class="form-check"><input class="form-check-input select-all" type="checkbox" value="" id="all-' + $(this).parents('.border-bottom').attr('data-filter') + '"><label class="form-check-label" for="all-' + $(this).parents('.border-bottom').attr('data-filter') + '"><small class="font-weight-bold">Select / Deselect All</small></label></div>').insertBefore(this);
		  		$('<span class="d-none small pb-2 text-center font-italic">No data found with this keyword</span>').insertAfter(this);
			}
		  });	
  //select/Deselect all checkbox in filter
  $('.select-all').change(function() {
	  selectAll = true;
	  if($(this).is(':checked')) {
		  $(this).parent().siblings('ul').find('li input').prop('checked', true).change();
	  } else {
		  $(this).parent().siblings('ul').find('li input').prop('checked', false).change();
	  }
		countFilterData();
	  selectAll = false;
  });
  //search list in filter
  $('.ft-list').each(function() {
	  $(this).on('keyup', function() {
		  var value = $(this).val().toLowerCase();
		  $(this).siblings('ul').find('li').filter(function() {
			  $(this).toggle($.trim($(this).text()).toLowerCase().indexOf(value) > -1);
		  });
		  if($(this).siblings('ul').find('li:visible').length < 1) {
			  $(this).siblings('span').removeClass('d-none').addClass('d-flex');
			  $(this).siblings('div').addClass('d-none');
		  } else {
			  $(this).siblings('span').addClass('d-none').removeClass('d-flex');
			  $(this).siblings('div').removeClass('d-none');
		  }
	  });
  });
  $('.po-filter .td-dropdown').each(function() {   
$(this).mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:'minimal-dark',
		 			scrollbarPosition:'outside'
});
});
}
/*$('.timework .max').on('blur', function(){
	if(parseInt($(this).val(),10) < parseInt($('.timework .min').val())){
	  $(this).val($('.timework .min').val());	
	}
	countFilterData();
});*/
$( '.timework .max' ).on('input',delay(function (e) {
	if(parseInt($(this).val(),10) < parseInt($('.timework .min').val())){
	  $(this).val($('.timework .min').val());	
	}
	countFilterData();
  }, 500));
//filter submit
$('#apply-filter-data').click(function(){
	totalTime=[];
	filterSubmitted = true;
	positions = $.map($('input[name="peoplePosition[]"]:checked'), function(c){return c.value; });
	departments = $.map($('input[name="peopleDepartement[]"]:checked'), function(c){return c.value; });
	orgs = $.map($('input[name="peopleOrganization[]"]:checked'), function(c){return c.value; });
  Status = $.map($('input[name="peopleStatus[]"]:checked'), function(c){return c.value; });
	$(this).attr('disabled','');
	$('#apply-filter-data .spinner-border').removeClass('d-none');
  if($('.timework .min').val()==parseInt($('.timework .min').val(), 10) && $('.timework .max').val()==parseInt($('.timework .max').val(), 10)){
	 totalTime.push(parseInt($('.timework .min').val()), parseInt($('.timework .max').val())); 
  }
  if(totalTime.length!=0){
	$('.timework').parents('.filter-list').addClass('ft-active');
  }else{
	$('.timework').parents('.filter-list').removeClass('ft-active');
  }
	if($(".po-filter ul input:checkbox:checked").length > 0 || totalTime.length!=0){
	  $('.po-filter').addClass('ft-selected');
	   if($('.filter-toggle span').length==0){
		   $('.filter-toggle').append('<span class="badge badge-danger position-absolute" style="right:-6px; top:-6px">'+$('.ft-active').length+'</span>');
	   }else{
		  $('.filter-toggle span').text($('.ft-active').length);
	  }
  }else{
  	$('.po-filter').removeClass('ft-selected');	
  	$('.filter-toggle span').remove();
  }
      table.draw();
    });
$('#clear-all').click(function(){
	positions = [], departments = [], orgs=[], Status=[],totalTime=[];
	$('#countFilterResult').text('');
	$('.filter-toggle span').remove();
	$('.po-filter').removeClass('ft-selected');	
	$('.po-filter input').each(function() {
		$(this).prop('checked', false);
	});
	$('.ft-list').each(function() {
	  if($(this).val()!=''){
		  $(this).val('').keyup();
	  }
	});
	$('.timework input').val('');
	$('.ft-active').removeClass('ft-active');
	$('.ft-counter').text('');
	table.draw();
});
function countFilterData(){
		totalTime=[];
  if($('.timework .min').val()==parseInt($('.timework .min').val(), 10) && $('.timework .max').val()==parseInt($('.timework .max').val(), 10)){
	 totalTime.push(parseInt($('.timework .min').val()), parseInt($('.timework .max').val())); 
  }
	  $('#apply-filter-data .spinner-border').removeClass('d-none');
	  $('#apply-filter-data').attr('disabled','')
  $.ajax({
    type : "post",
    url: engagifiiUrl_ajaxurl,
    data:{
		action:'peopleList',
		length:10,
		start:1,
		countResult :true,
		positions:positions,
				departments:departments,
				orgs:orgs,
      			status:Status,
				totalTime: totalTime,    
		},
    success: function(response) {     
      var element  = document.getElementById("countFilterResult");
	  $('#apply-filter-data .spinner-border').addClass('d-none');
	  $('#apply-filter-data').removeAttr('disabled');
      if(element)
      {
          element.innerHTML = " ("+response+")";
      }    
    }
});
}

$('.input-group-append').click(function() {
  // Trigger click event of the date input element
  $(this).siblings('input').click();
});

// Adjust the position of the calendar icon in the date input group
$('.input-group-append').css('cursor', 'pointer');

</script>