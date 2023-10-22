<?php
    $obj      =  new Engagifii_API();
    $publicOfficial = $obj->publicOfficial();
	if(!$publicOfficial){
		echo'<h5 class="text-center pt-5">Data not available</h5>';
		return;	
	}
	$residence = $obj->poResidence();
	$district = $obj->poDistrict();
	$party = $obj->poParty();
	$role = $obj->poRole();
	$committee = $obj->poComittee();
	$counties = $obj->poCounty();
	$filterParam = ['City of Residence', 'Committies','Counties','District','Political Party', 'Role', ];
	$filterAPI = [$residence, $committee,$counties,$district,$party, $role ];
	//$publicOfficial = json_decode(file_get_contents(ENGAGIFII_ASSETS_URL.'/po.txt'));
	//print_r($publicOfficial);
	$siteURL= site_url();
    $title_key = -1;
    
$seqColumns=['Name','Counties','City of Residence','District','Committees','Party','Role'];
$forDatatable   =   array();
$tableHeader='';
$i = 0;
foreach ($seqColumns as $key => $value) {
	if($value == 'Name'){
	  $title_key = $i;
	}
	$forDatatable[]['data'] = str_replace(' ', '', strtolower($value));
	$tableHeader.='<th class="'.str_replace(' ', '', strtolower($value)).'Col">'.$value.'</th>';
  $i++;
}


?>
<style type="text/css">
  .session-tab button.active, .filter-top-bg{
	background-color:#002474  !important;
 }
 .filter-toggle {
	width: 40px;
	height: 40px; 
	color:#002474  !important;
 }
 .po-filter.show > .filter-toggle, .po-filter.ft-selected > .filter-toggle{
	background-color:#002474  !important;
	color:#fff  !important;
 }
 .po-filter .dropdown-menu {
	width: 300px; 
 }
 .po-filter label {
	font-size: 15px; 
 }
</style>
 <ul class="nav nav-pills justify-content-center session-tab" id="pills-tab" role="tablist">
      <?php $i=1; 
	  	foreach ($publicOfficial as $key => $value) {
			if($key=='stateSenateMemberList'){
				$name='State Senate';
			}
			elseif($key=='stateHouseMemberList'){
				$name='State House';
			}
			elseif($key=='stateSenateCommittees'){
				$name='State Senate Committees';
			}
			elseif($key=='stateHouseCommittees'){
				$name='State House Committees';
			}
			elseif($key=='countyDeligationList'){
				$name='County Delegations';
			}
			elseif($key=='congressionalDelegationMemberList'){
				$name='Congressional Delegations';
			}else{
				$name=$Key;
			}
			$class=''; 
			if($i==1){
				$class =' active';
			}
    echo '<li class="nav-item mr-3 mb-3" role="presentation">
    <button class="border-dark nav-link bg-transparent'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$name.' <b>('.count($value).')</b></button></li>';
 $i++; }
	  ?>   		
      </ul>
       <div class="tab-content" id="nav-tabContent">
      <?php $k=1; 
	  	foreach ($publicOfficial as $key => $value) {
			$class=''; 
			if($k==1 ){
				$class =' show active';
			}
			$tabDataArray= $publicOfficial[$key];
			?>
<div class="tab-pane fade <?php echo $class; ?>" id="tab-<?php echo $k; ?>" role="tabpanel" data-tab="<?php echo $key; ?>">
</div><!--tab pane close-->
      <?php $k++; 
	  } ?>
       </div>
       <script>
  var titleColumn = '<?php echo $title_key; ?>';
  var table,tab,prvtab,tabCount='';
  var residence=[],committee=[],party=[],role=[],county=[],states=[];
	  tab = $('.tab-pane.active').attr('data-tab');
		ajaxDT();  
  $('button[data-toggle="pill"]').on('shown.bs.tab', function(e){
	   tab = $('.tab-pane:eq('+$(e.target).parent('li').index()+')').attr('data-tab');
	   tabCount='';
	  $('.tab-pane:eq('+$(e.relatedTarget).parent('li').index()+')').html('');
	  ajaxDT();
   });
   $('body').on('shown.bs.collapse','.card >div+div', function (e) {
	   tabCount = $('.tab-pane.active .card .show').attr('data-count');
	  $('.tab-pane .card .show').parents('.card').siblings().find('.card-body').html(''); 
		if($('.card-body table',this).length==0){
		  ajaxDT();
		}
	}) ;
   function ajaxDT(){
	   if((tab=='stateSenateCommittees'||tab=='stateHouseCommittees'||tab=='countyDeligationList')&& tabCount!=''){
			$('.active .show .card-body').html('<div class="loaders text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');  	 
			}else{
			$('.tab-pane.active').html('<div class="loaders text-center py-5"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');   
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
			  states:states
          },
          success: function(response) { 
		  	var data =   response; 
			if((tab=='stateSenateCommittees'||tab=='stateHouseCommittees'||tab=='countyDeligationList') && tabCount!=''){
			  $('.tab-pane.active .card .show .card-body').html(data);
			}else{
			  $('.tab-pane.active').html(data);
			}
			$('.loaders').remove();
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
					   dt_dropdown();
						 $('[data-toggle="tooltip"]').tooltip() ;
				   },
				   
					"initComplete": function(settings, json) {
					   tableEvents();
			  },
			  });
   }
<?php
  if($title_key > -1){
?>
  function tableEvents(){
  $('.dataTables_wrapper table').find('thead tr th:eq('+titleColumn+')').each( function (i) { 
         var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" id="searchclass" placeholder="Search Public official.." class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:3;display:none"><i class="fal fa-times"></i></button></div>' );

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
           if ( table.columns(titleColumn).search() !== titlesearch ) {
				table.columns(titleColumn).search(titlesearch).draw();
            }
}, 500));
 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$(this).parents('th').find('.clear-search').show();
			} else {
				$(this).parents('th').find('.clear-search').hide();
			} 
 });
$('th .clear-search').click(function(e){
	 $(this).parents('th').find('#searchclass').val('');
	$(this).parents('th').find('.clear-search').hide();
	e.stopPropagation();
	table.column(titleColumn).search('').draw();
 });

    } );
	 $('#searchclass, .search-dt span').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchclass').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});
  }
	
  <?php
}
  ?>
  $(document).on('click', '.po-filter .dropdown-menu', function (e) {
  e.stopPropagation();
});
function ajaxFilter(){
	$('.filter_submit').text('Loading...').append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>').attr('disabled',''); 
	if($(".po-filter input:checkbox:checked").length > 0){
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
				  states:states
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
$('.filter_submit').on('click', function(){
tabCount='';
  residence=[];committee=[];party=[];role=[];county=[];states=[];
	$('.po-filter ul').each(function(){
	  $('input',this).each(function(){
		  if ($(this).is(':checked')) {
			  if($(this).parents('.border-bottom').attr('data-filter')=='cityofresidence'){
				residence.push($(this).val());	
			  }
			  if($(this).parents('.border-bottom').attr('data-filter')=='committies'){
				committee.push($(this).val());	
			  }
			  if($(this).parents('.border-bottom').attr('data-filter')=='counties'){
				county.push($(this).val());	
			  }
			  if($(this).parents('.border-bottom').attr('data-filter')=='district'){
				states.push($(this).val());	
			  }
			  if($(this).parents('.border-bottom').attr('data-filter')=='politicalparty'){
				party.push($(this).val());	
			  }
			  if($(this).parents('.border-bottom').attr('data-filter')=='role'){
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
		states = states.filter(function(elem, index, self) {
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
$('.po-filter ul').each(function(){
	$('input',this).prop('checked', false);
   var ftSelected=0;
	$('input',this).change(function(){
		ftSelected = $(this).parents('ul').find('input:checkbox:checked').length;
	  if(ftSelected>0){
		$(this).parents('.border-bottom').addClass('ft-active').find('.ft-counter').text('('+ftSelected+')');  
	  }else{
		$(this).parents('.border-bottom').removeClass('ft-active').find('.ft-counter').text('');  
	  }
});
});
$('#clear-all').on('click', function(){
tabCount='';
  residence=[];committee=[];party=[];role=[];county=[];states=[];
	$('.po-filter input').each(function(){
	  $(this).prop('checked', false);
	});
	$('.ft-active').removeClass('ft-active');
  ajaxFilter();
	$('.ft-counter').text('');
});
	  </script>