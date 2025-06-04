<?php 

	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );   
  $title_key = 0;
$allowedViewMode = isset($viewMode) && trim($viewMode) !== ''
    ? strtolower($viewMode)
    : 'both';
?>
<div class="container-fluid ">
	<div class="row">
    	  <?php if ($allowedViewMode === 'both'){ ?>
    	<div class="col-12 justify-content-end d-flex">
          <div class="btn-group view-mode" role="group" aria-label="">
            <button type="button" class="btn btn-outline-primary " view-mode="grid"><i class="fas fa-grid mr-1"></i>Grid View</button>
            <button type="button" class="btn btn-outline-primary  active" view-mode="list"><i class="fas fa-list mr-1"></i>List view</button> 
          </div>
        </div>
        <div class="col-12 mb-4"></div>
  <?php } ?>
  <style>
/*.prv, .nxt {
  top: 9px;
}
#groupTabs {
  scrollbar-width: none;          
  -ms-overflow-style: none;       
}

#groupTabs::-webkit-scrollbar {
  display: none;                  
}*/
.group-card .card-text {
font-size: 14px;	
}
.grid-view .card .img-default { 
font-size: 260px;
}
@media screen and (max-width: 1080px) {
  .grid-view .card .img-default {
    font-size: 150px;
  }
}
</style>
<!-- Group Title -->
<?php if ($allowedViewMode === 'list' ||$allowedViewMode === 'both' ){ ?>
	<div class="engagifii-box  engagifii-main-cotainer position-relative px-xl-5 col-12 list-view">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page nowrap " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	 <?php  $i = 0;         
				  foreach (ORGANIZATION_COLS as $key){
					  if($key == 'OrganizationName'){
                    	$title_key = $i;
                 	 }
				 $forDatatable[]['data'] = $key
				  ?>
            <th class="text-capitalize <?php echo preg_replace('/\s+/', '', strtolower($key)); ?>">
    <?php echo trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $key)); ?>
</th>
        <?php } ?>
 		    

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>
<?php } if ($allowedViewMode === 'grid' || $allowedViewMode === 'both' ){?>
<div class="col-12 grid-view" <?php if($allowedViewMode === 'both') { ?>style="display:none" <?php } ?>>
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
<?php } ?>
</div>
</div>

<script type="text/javascript">
  //var groupId = $('#groupTabs li:first-child a').attr('id');
  var viewMode='<?php echo $allowedViewMode; ?>';
  var viewMode='list';
  var start = 0;
  var length = 8;
  var titleColumn = '<?php echo $title_key; ?>';
 <?php  if ($allowedViewMode === 'grid' ){?>
   OrgList(start);
  <?php } ?>
  $('.view-mode button').click(function(){
	  var selectedMode = $(this).attr('view-mode');  
	  if (selectedMode === viewMode) return;  
	  viewMode = selectedMode;
	  $(this).addClass('active').siblings().removeClass('active');  
	  if(viewMode === 'grid'){
		  $('.list-view').hide();
		  $('.grid-view').show();
		  OrgList(start);
	  } else {
		  $('.list-view').show();
		  $('.grid-view').hide();
		  table.draw();
	  }
  });
	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
          "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
		"order": [[<?php echo array_search('OrganizationName',ORGANIZATION_COLS);?>, 'asc']],
      	"columnDefs": [ 
          { "targets": ['active/totalmember', 'location', 'status', 'phonenumbers', 'email', 'organizationtype', 'tags'],
            "orderable": false
          },
		  <?php //if(in_array('People Name', $colNames)){ ?>
		  //	{ width: 350, targets: <?php //echo array_search('People Name',$colNames);?> },
		  <?php //} if(in_array('Email', $colNames)){ ?>
		//  { width: 150, targets: <?php //echo array_search('Email',$colNames);?> },
		  <?php // } ?>
		 // { className: "text-center", "targets": ['people-select'] },
		   
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
            	d.action='getOrganizations'; 			
				      d.titleColumn = titleColumn; 			
            }, 
        },
        createdRow: function (row, data, index) { 
           
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
	 $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('.engagifii-box #eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();
	
	//fetch group members
	function OrgList(start){ 
		 $('.grid-view #eng-overlay').show();
		  $('.grid-view .row').css('opacity','.3');
	   $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'getOrganizations',			 
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
			  renderOrgGrid(data);
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
	
	//grid layout
	function renderOrgGrid(data) {
	  var container = $('.grid-view .row');
	  container.empty(); // Clear previous content
		if (data.length === 0) {
			container.append('<h3 class="text-secondary text-center col-12">No members found!</h3>'); 
		  return;
		}
	  data.forEach(function(item) {
		var org = item;
		if(isValidUrl(org.imageThumbUrl)){
			var orgPhoto = ' <img src="' + org.imageThumbUrl + '" class="card-img-top" alt="' + org.name + '">';
		}else {
			var orgPhoto = '<i class="fa fa-user-circle text-secondary" style="font-size:260px"></i>';
		}
		var orgTotalMember = org.totalMembers ;
    var orgActiveMember = org.activeMembers;
		var orgStatus = org.status;
    var orgType = org.organizationType ? org.organizationType : 'N/A';
		var orgTag = org.Tags ? org.Tags : 'N/A';
var card = '<div class="col-md-3 mb-4">\
  <div class="card h-100 shadow p-3">\
    '+orgPhoto+'<hr>\
    <div class="card-body p-2">\
      <h5 class="card-title">' + org.name + '</h5>\
       <p class="card-text mb-1"><strong>Total Members:</strong> ' + orgTotalMember + '</p>\
          <p class="card-text mb-1"><strong>Active Members:</strong> ' + orgActiveMember + '</p>\
          <p class="card-text mb-1"><strong>Status:</strong> ' + orgStatus + '</p>\
          <p class="card-text mb-1"><strong>Type:</strong> ' + orgType + '</p>\
          <p class="card-text"><strong>Tags:</strong> ' + orgTag + '</p>\
    </div>\
  </div>\
</div>';	
		container.append(card); 
	  });
} 

//grid pagination
function renderPagination(totalCount, start, length) {
  const $pagination = $('.grid-pagination');
  const currentPage = Math.floor(start / length) + 1;
  const totalPages = Math.ceil(totalCount / length);

  // Clear existing page numbers (except First & Last <li>)
  $pagination.find('li.page-number').remove();

  const visiblePages = [];
  
  // Always show first page
  visiblePages.push(1);

  // Pages before current
  for (let i = currentPage - 2; i <= currentPage + 2; i++) {
    if (i > 1 && i < totalPages) {
      visiblePages.push(i);
    }
  }

  // Always show last page if not already in list
  if (totalPages > 1) {
    visiblePages.push(totalPages);
  }

  // Remove duplicates and sort
  const uniquePages = [...new Set(visiblePages)].sort((a, b) => a - b);

  // Render pages with ellipsis
  for (let i = 0; i < uniquePages.length; i++) {
    if (i > 0 && uniquePages[i] !== uniquePages[i - 1] + 1) {
      $pagination.find('li.page-item').last().before('<li class="page-item disabled page-number"><span class="page-link">...</span></li>');
    }

    const pageNum = uniquePages[i];
    const activeClass = pageNum === currentPage ? 'active' : '';
    const $pageItem = $('<li class="page-item page-number ' + activeClass + '"><a class="page-link" href="#">' + pageNum + '</a></li>');
    $pagination.find('li.page-item').last().before($pageItem);
  }

  // Enable/Disable Previous and Next
  $pagination.find('li:first-child').toggleClass('disabled', currentPage === 1);
  $pagination.find('li:last-child').toggleClass('disabled', currentPage === totalPages);

  // Click handlers
  $pagination.find('li.page-item a').off('click').on('click', function (e) {
    e.preventDefault();
    const text = $(this).text();
    let newPage = currentPage;

    if (text === 'Previous' && currentPage > 1) newPage = currentPage - 1;
    else if (text === 'Next' && currentPage < totalPages) newPage = currentPage + 1;
    else if (!isNaN(parseInt(text))) newPage = parseInt(text);

    if (newPage !== currentPage) {
      start = (newPage - 1) * length;
      OrgList(start); // re-fetch new data
    }
  });
}

//check if url is valid
function isValidUrl(url) {
  try {
    new URL(url);
    return true;
  } catch (_) {
    return false;
  }
}
//search members
<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Organization');
  <?php
}

  ?>
</script>



