<?php 

	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
    $colNames = ['Full Name', 'Email', 'Position', 'Organization'];
	//$groupList = $obj->getGroupsList();
    //print_r($groupList);
  //  $columnSearch_key = [];
  // $groupTabs = array_slice($groupList, 0, 5);
$groupJsonStrings = $options['group_members_settings']['groupFields'];
$groups = [];
foreach ($groupJsonStrings as $json) {
    $decoded = json_decode($json);
    if ($decoded) {
        $groups[] = $decoded;
    }
}?>
<style>
/*	th.peoplename, th.email {
    min-width: 150px;	
}	
 Modern pill-style tabs */
/*.group-tabs {
    display: flex;
    flex-direction: row;
    gap: 24px;
    border-bottom: none;
    justify-content: flex-start;
    margin-bottom: 32px;
    background: transparent;
}
.group-tabs .nav-link {
    border-radius: 8px;
    border: 2px solid #2196f3;
    color: #2196f3;
    background: #fff;
    padding: 12px 36px;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    transition: background 0.2s, color 0.2s;
    min-width: 180px;
    text-align: center;
}
.group-tabs .nav-link.active,
.group-tabs .nav-link:focus,
.group-tabs .nav-link:hover {
    background: #2196f3;
    color: #fff;
    border-color: #2196f3;
    outline: none;
}
.group-tabs .nav-item {
    margin: 0 !important;
}*/
</style>
<div class="container-fluid ">
	<div class="row">
    	<div class="col-6"> 
          <div class="d-flex align-items-center">
                  <h4 class="mb-0 mr-2">
                      <button type="button" title="Refresh Members" class="refresh btn shadow-none p-2 mr-1"> <i class="fas fa-sync"></i></button><?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/Member-Icon.png" class="img-fluid" alt="member-icon" style="max-width:40px" >'; ?></h4>
                  <h5 class="mb-0">Members</h5>                
                 
                    </div>
        </div>
    	<div class="col-6 justify-content-end d-flex">
          <div class="btn-group view-mode" role="group" aria-label="">
            <button type="button" class="btn btn-outline-primary " view-mode="grid"><i class="fas fa-grid"></i></button>
            <button type="button" class="btn btn-outline-primary  active" view-mode="list"><i class="fas fa-list"></i></button> 
          </div>
        </div>
        <div class="col-12 mb-4"></div>
<!-- Group Tabs -->
<div class="col-12">
  <ul class="nav nav-pills nav-fill flex-nowrap group-tabs mb-5 overflow-auto overflow-x-auto" id="groupTabs" role="tablist">
      <?php foreach($groups as $idx => $group): ?>
          <li class="nav-item mr-3">
              <a class="text-nowrap border border-primary nav-link<?php if($idx === 0) echo ' active'; ?>" id="<?php echo $group->id; ?>" data-toggle="tab" href="#group-<?php echo $idx; ?>" role="tab" aria-controls="group-<?php echo $idx; ?>" aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>">
                  <?php echo htmlspecialchars($group->title); ?>
              </a>
          </li>
      <?php endforeach; ?>
  </ul>
</div>

	<div class="engagifii-box  engagifii-main-cotainer position-relative px-xl-5 col-12 list-view">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page nowrap " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	 <?php foreach (GROUP_MEMBERS_COLS as $key):
				 $forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
				  ?>
            <th class="text-capitalize <?php echo preg_replace('/\s+/', '', strtolower($key)); ?>"><?php echo $key; ?></th>
        <?php endforeach; ?>
 		    

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>
<div class="col-12 grid-view" style="display:none">
	<div class="row mb-4">
    	
    </div>
    <nav aria-label="Page navigation example">
  <ul class="pagination pagination-sm justify-content-center grid-pagination">
    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
    <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav>
    <div id="eng-overlay" style="display: none;"><span class="spinner"></span></div>
</div>

</div>
</div>

<script type="text/javascript">
var groupId = $('#groupTabs li:first-child a').attr('id');
var viewMode='list';
var start = 0;
var length = 8;
$('#groupTabs a').click(function(){
	groupId = $(this).attr('id');
	if(viewMode=='grid'){
		groupMembers();	
	} else {
	  $('#eng-overlay').show();
	  table.draw();
	}
});
$('.view-mode button').click(function(){
	var selectedMode = $(this).attr('view-mode');

	if (selectedMode === viewMode) return;

	viewMode = selectedMode;
	$(this).addClass('active').siblings().removeClass('active');

	if(viewMode === 'grid'){
		$('.list-view').hide();
		$('.grid-view').show();
		groupMembers();
	} else {
		$('.list-view').show();
		$('.grid-view').hide();
		table.draw();
	}
});
	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
		"order": [[<?php echo array_search('name',GROUP_MEMBERS_COLS);?>, 'asc']],
      	"columnDefs": [ 
          { "targets": ['email','position', 'organization','phone'],
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
            	d.action='peopleloadGridDataByGroups'; 
				d.groupId=groupId; 
				/*d.departments=departments; 
				d.orgs=orgs; 
      			  d.status=Status;
				d.totalTime= totalTime;
				d.titleColumn = titleColumn; 
				d.emailColumn = emailColumn;*/ 
            }, 
        },
        createdRow: function (row, data, index) { 
            // $(row).addClass( 'bg-white1' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 
     "drawCallback": function( settings ) {
            dt_dropdown();
			   $('[data-toggle="tooltip"]').tooltip() ; 
			    $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
		   
         },
		  "initComplete": function(settings, json) {
			           dt_scroll();
			  $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
    },
    });
	function groupMembers(){
		console.log(start);
		 $('.grid-view #eng-overlay').show();
		  $('.grid-view .row').css('opacity','.3');
	   $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'peopleloadGridDataByGroups',
			  groupId:groupId,
			  viewMode:'Grid',
			  length:length,
			  start:start
          },
         success: function(response) {
	  		 $('.grid-view #eng-overlay').hide();
			  $('.grid-view .row').css('opacity','1');
			try {
			   var parsedResponse = JSON.parse(response);
			  var data = parsedResponse.data || [];
			  renderGroupGrid(data);
			  renderPagination(parsedResponse.count, start, length);
			} catch (e) {
			  console.error('Error parsing response:', e);
			}
		  },
		  error: function() {
			$('.grid-view #eng-overlay').hide();
			 $('.grid-view .row').css('opacity','1');
			console.error('AJAX request failed');
		  }
        });
	}
	function renderGroupGrid(data) {
	  var container = $('.grid-view .row');
	  container.empty(); // Clear previous content
	
	  data.forEach(function(item) {
		var person = item.people;
var card = '<div class="col-md-3 mb-4">\
  <div class="card h-100 shadow p-3">\
    <img src="' + person.imageThumbUrl + '" class="card-img-top" alt="' + person.fullName + '"><hr>\
    <div class="card-body p-2">\
      <h5 class="card-title">' + person.fullName + '</h5>\
      <p class="card-text mb-1"><i class="fas fa-envelope mr-1"></i><a href="mailto:'+ person.email+'"> ' + person.email + '</a></p>\
      <p class="card-text mb-1"><i class="fas fa-landmark mr-2"></i> ' + person.organization + '</p>\
      <p class="card-text"><strong>Position:</strong> ' + person.position + '</p>\
    </div>\
  </div>\
</div>';	
		container.append(card); 
	  });
} 
/*$(document).on('click', '.grid-pagination .page-link', function (e) {
  e.preventDefault();
  const page = $(this).text();
  if (page) {
    start = (page - 1) * length;
    groupMembers(); 
  }
});*/
function renderPagination(totalCount, start, length) {
  var currentPage = Math.floor(start / length) + 1;
  var totalPages = Math.ceil(totalCount / length);
  var $pagination = $('.grid-pagination');
  
  $pagination.find('li.page-number').remove();

  for (let i = 1; i <= totalPages; i++) {
    const activeClass = (i === currentPage) ? 'active' : '';
    const $pageItem = $('<li class="page-item page-number '+activeClass+'"><a class="page-link" href="#">'+i+'</a></li>');

    $pagination.find('li').last().before($pageItem);
  }

  $pagination.find('li:first-child').toggleClass('disabled', currentPage === 1);
  $pagination.find('li:last-child').toggleClass('disabled', currentPage === totalPages);

  $pagination.find('li.page-item a').off('click').on('click', function (e) {
    e.preventDefault();
    var selectedPage = parseInt($(this).text());
    if (selectedPage !== currentPage) {
      start = (selectedPage - 1) * length;
	console.log(start);
      groupMembers(); 
    }
  });

  $pagination.find('li:first-child a').off('click').on('click', function (e) {
    e.preventDefault();
    if (currentPage > 1) {
      start -= length;
      groupMembers();
    }
  });
  $pagination.find('li:last-child a').off('click').on('click', function (e) {
    e.preventDefault();
    if (currentPage < totalPages) {
      start += length;
      groupMembers();
    }
  });
}

</script>



