<?php
$filterParam = ['City of Residence', 'Committies','Counties','District','Political Party', 'Role', ];
$seqColumns=['Name','Counties','City of Residence','District','Committees','Party','Role'];
?>
<style type="text/css">
.session-tab button.active {
	background-color: #002474 !important;
}
.filter-toggle {
	width: 40px;
	height: 40px;
	color: #002474 !important;
}
.po-filter.show > .filter-toggle, .po-filter.ft-selected > .filter-toggle {
	background-color: #002474 !important;
	color: #fff !important;
}
.po-filter .dropdown-menu {
	width: 300px;
}
.po-filter label {
	font-size: 15px;
}
</style>
<ul class="nav nav-pills justify-content-center session-tab" id="pills-tab" role="tablist">
  <div class="loaders text-center py-5">
    <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
  </div>
</ul>
<div class="dropdown dropleft po-filter d-flex justify-content-end mb-3">
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
<div class="tab-content" id="nav-tabContent"> </div>
<script>
var titleColumn = '<?php echo array_search('Name',$seqColumns);?>';
var table,tab,prvtab,tabCount='';
var residence=[],committee=[],party=[],role=[],county=[],office=[];

window.addEventListener("load", function () {
// document.querySelector('body').classList.add('loaded');
$.ajax({
  type : "post",
  url: engagifiiUrl_ajaxurl,
  data:{
 	 action:'publicOfficialTabs',
  },
  success: function(response) { 
	$('#pills-tab').html((JSON.parse(response))['tabName']);
	$('#nav-tabContent').html((JSON.parse(response))['tabContent']);
	tab = $('#nav-tabContent').find('.tab-pane.active').attr('data-tab');
	if(tab){
		ajaxDT();
	}
	$('button[data-toggle="pill"]').on('shown.bs.tab', function(e){
		tab = $('#nav-tabContent').find('.tab-pane:eq('+$(e.target).parent('li').index()+')').attr('data-tab');
		tabCount='';
		$('#nav-tabContent').find('.tab-pane:eq('+$(e.relatedTarget).parent('li').index()+')').html('');
		ajaxDT();
	});
	$.ajax({
	  type : "post",
	  url: engagifiiUrl_ajaxurl,
	  data:{
	 	 action:'poFilter',
	 	 filterParams:<?php echo json_encode($filterParam);?>,
	  },
	  success: function(response) { 
	 	 for (var i = 0; i < (JSON.parse(response)).length; i++) {
	 		 $('#filter-'+i+' .td-dropdown').html((JSON.parse(response))[i]);
	 	 }
	 	 dt_dropdown();
		  filterEvents();  
		  }
	});
  }
});
});
$('body').on('shown.bs.collapse','.card >div+div', function (e) {
	tabCount = $('#nav-tabContent').find('.tab-pane.active .card .show').attr('data-count');
	$('.tab-pane .card .show').parents('.card').siblings().find('.card-body').html(''); 
	if($('.card-body table',this).length==0){
		ajaxDT();
	}
}) ;
function ajaxDT(){
	if((tab=='stateSenateCommittees'||tab=='stateHouseCommittees'||tab=='countyDeligationList')&& tabCount!=''){
		$('.active .show .card-body').html('<div class="loaders text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');  	 
	}else{
		$('#nav-tabContent').find('.tab-pane.active').html('<div class="loaders text-center py-5"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');   
	}
$.ajax({
  type : "post",
  url: engagifiiUrl_ajaxurl,
  data:{
	action:'publicOfficial',
	tab:tab,
	tabCount:tabCount,
	cityofResidence:residence,
	committee:committee,
	politicalParty:party,
	role:role,
	county:county,
	office:office
  },
  success: function(response) { 
	var data =   response; 
	if((tab=='stateSenateCommittees'||tab=='stateHouseCommittees'||tab=='countyDeligationList') && tabCount!=''){
		$('.tab-pane.active .card .show .card-body').html(data);
	}else{
		$('#nav-tabContent').find('.tab-pane.active').html(data);
	}
	initDT();
  }
});
}
function initDT(){
  table = $('table.tabData').DataTable({
	//"pageLength": '10',
	// "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
	"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
	"bInfo":false,
	"processing": true,
	"searching": true,
	"responsive": true,
	//"fixedHeader": true,
	"ordering":true,
	"search": {regex: true},
	<?php if(in_array('Name', $seqColumns)){ ?>
	"order": [[<?php echo array_search('Name',$seqColumns);?>, 'asc']],
	<?php } ?>
	"columnDefs": [ 
	{ "targets": ['countiesCol','committeesCol'],
	"orderable": false
	},
	{ width: 200, targets: <?php echo array_search('Name',$seqColumns);?> },
	{ className: "text-center", "targets": ["startdate","instructors"] },
	{ responsivePriority: 1, targets: 'sectionname' },
	],
	"language": {
	processing: '<span>&nbsp;</span>',
	"emptyTable": '-'
	},
	"oLanguage": {
	"sLengthMenu": "Show _MENU_ records per page"
	},
	createdRow: function (row, data, index) { 
	//$(row).addClass( 'bg-white' );
	},  
	//"columns":<?php //echo (json_encode($forDatatable)); ?>,
	"drawCallback": function( settings ) {
		$('[data-toggle="tooltip"]').tooltip() ;
	},
	
	"initComplete": function(settings, json) {
		dt_titleSearch('Search Public official..');
	},
  });
}
$(document).on('click', '.po-filter .dropdown-menu', function (e) {
	e.stopPropagation();
});
function ajaxFilter(){
  $('.filter_submit').text('Loading...').append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>').attr('disabled',''); 
  if($(".po-filter ul input:checkbox:checked").length > 0){
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
  $.ajax({
	type : "post",
	url: engagifiiUrl_ajaxurl,
	data:{
	  action:'publicOfficialCount',
	  cityofResidence:residence,
	  committee:committee,
	  politicalParty:party,
	  role:role,
	  county:county,
	  office:office
  },
  success: function(response) { 
  	$('#pills-tab li').each(function(){
 	 var idx = $(this).index();	
 	 $('button  b',this).text('('+response[idx]+')');
 	 });
  $('.filter_submit').text('Apply').removeAttr("disabled").find('span').remove();
  }
  });	
  ajaxDT();
}
//events on filter submit button
$('.filter_submit').on('click', function() {
	tabCount = '';
	residence = [];
	committee = [];
	party = [];
	role = [];
	county = [];
	office = [];
	$('.po-filter ul').each(function() {
		$('input', this).each(function() {
			if($(this).is(':checked')) {
				if($(this).parents('.border-bottom').attr('data-filter') == 'cityofresidence') {
					residence.push($(this).val());
				}
				if($(this).parents('.border-bottom').attr('data-filter') == 'committies') {
					committee.push($(this).val());
				}
				if($(this).parents('.border-bottom').attr('data-filter') == 'counties') {
					county.push($(this).val());
				}
				if($(this).parents('.border-bottom').attr('data-filter') == 'district') {
					office.push($(this).val());
				}
				if($(this).parents('.border-bottom').attr('data-filter') == 'politicalparty') {
					party.push($(this).val());
				}
				if($(this).parents('.border-bottom').attr('data-filter') == 'role') {
					role.push($(this).val());
				}
			}
		});
	});
	residence = residence.filter(function(elem, index, self) {
		return index === self.indexOf(elem);
	});
	committee = committee.filter(function(elem, index, self) {
		return index === self.indexOf(elem);
	});
	county = county.filter(function(elem, index, self) {
		return index === self.indexOf(elem);
	});
	office = office.filter(function(elem, index, self) {
		return index === self.indexOf(elem);
	});
	party = party.filter(function(elem, index, self) {
		return index === self.indexOf(elem);
	});
	role = role.filter(function(elem, index, self) {
		return index === self.indexOf(elem);
	});
	ajaxFilter();
});
//clear all button in filter
$('#clear-all').on('click', function() {
	tabCount = '';
	residence = [];
	committee = [];
	party = [];
	role = [];
	county = [];
	office = [];
	$('.po-filter input').each(function() {
		$(this).prop('checked', false);
	});
	$('.ft-list').each(function() {
	  if($(this).val()!=''){
		  $(this).val('').keyup();
	  }
	});
	$('.ft-active').removeClass('ft-active');
	ajaxFilter();
	$('.ft-counter').text('');
});
function filterEvents(){
	$('.po-filter ul').each(function() {
		  	$('input', this).prop('checked', false);
		  	var ftSelected = 0;
		  	$('input', this).change(function() {
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
	  if($(this).is(':checked')) {
		  $(this).parent().siblings('ul').find('li input').prop('checked', true).change();
	  } else {
		  $(this).parent().siblings('ul').find('li input').prop('checked', false).change();
	  }
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
}

</script> 
