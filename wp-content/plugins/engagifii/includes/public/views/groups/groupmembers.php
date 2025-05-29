<?php 

	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
$groupJsonStrings = $options['group_members_settings']['groupFields'];
$groups = [];
foreach ($groupJsonStrings as $json) {
    $decoded = json_decode($json);
    if ($decoded) {
        $groups[] = $decoded;
    }
}?>
<div class="container-fluid ">
	<div class="row">
    	
    	<div class="col-12 justify-content-end d-flex">
          <div class="btn-group view-mode" role="group" aria-label="">
            <button type="button" class="btn btn-outline-primary " view-mode="grid"><i class="fas fa-grid"></i></button>
            <button type="button" class="btn btn-outline-primary  active" view-mode="list"><i class="fas fa-list"></i></button> 
          </div>
        </div>
        <div class="col-12 mb-4"></div>
<!-- Group Tabs -->
<style>
.prv, .nxt {
  top: 9px;
}
#groupTabs {
  scrollbar-width: none;          /* Firefox */
  -ms-overflow-style: none;       /* IE 10+ */
}

#groupTabs::-webkit-scrollbar {
  display: none;                  /* Chrome, Safari, Opera */
}
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
<div class="col-12">
	<span class="prv position-absolute bg-primary text-white rounded-circle align-items-center justify-content-center d-none " id="scrollLeftBtn"><i class="far fa-angle-left"></i></span>
  <div class="tab-scroll-container overflow-hidden" id="tabScrollContainer" >
  <ul class="nav nav-pills nav-fill flex-nowrap group-tabs mb-5 overflow-auto" id="groupTabs" role="tablist">
      <?php foreach($groups as $idx => $group): ?>
          <li class="nav-item mr-3">
              <a class="text-nowrap border border-primary nav-link<?php if($idx === 0) echo ' active'; ?>" id="<?php echo $group->id; ?>" data-toggle="tab" href="#group-<?php echo $idx; ?>" role="tab" aria-controls="group-<?php echo $idx; ?>" aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>">
                  <?php echo htmlspecialchars($group->title); ?>
              </a>
          </li>
      <?php endforeach; ?>
  </ul>
  </div>
  <span class="nxt position-absolute bg-primary text-white rounded-circle  align-items-center justify-content-center d-none" id="scrollRightBtn"><i class="far fa-angle-right"></i></span>
</div>
<!-- Group Title -->
<div class="col-12">
    <h2 id="currentGroupTitle" class="mb-5 text-center"><?php echo isset($groups[0]) ? htmlspecialchars($groups[0]->title) : ''; ?></h3>
</div>
	<div class="engagifii-box  engagifii-main-cotainer position-relative col-12 list-view">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page nowrap " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	 <?php  $i = 0;
				  foreach (GROUP_MEMBERS_COLS as $key){
					  if($key == 'name'){
                    	$title_key = $i;
                 	 }
				 $forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
				  ?>
            <th class="text-capitalize <?php echo preg_replace('/\s+/', '', strtolower($key)); ?>"><?php echo $key; ?></th>
        <?php } ?>
 		    

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
  var titleColumn = '<?php echo $title_key; ?>';
  $('#groupTabs a').click(function(){
	  groupId = $(this).attr('id');
     var groupTitle = $(this).text();
    $('#currentGroupTitle').text(groupTitle);

	  if(viewMode=='grid'){
		  groupMembers(start);	
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
		  groupMembers(start);
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
            	d.action='peopleloadGridDataByGroups'; 
				d.groupId=groupId; 
				d.titleColumn = titleColumn; 
				/*d.departments=departments; 
				d.orgs=orgs; 
      			  d.status=Status;
				d.totalTime= totalTime;
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
			         //  dt_scroll();
			  $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
    },
    });
	 $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('.engagifii-box #eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();
	
	//fetch group members
	function groupMembers(start){ 
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
	
	//grid layout
	function renderGroupGrid(data) {
	  var container = $('.grid-view .row');
	  container.empty(); // Clear previous content
		if (data.length === 0) {
			container.append('<h3 class="text-secondary text-center col-12">No members found!</h3>'); 
		  return;
		}
	  data.forEach(function(item) {
		var person = item.people;
		if(isValidUrl(person.imageThumbUrl)){
			var personPhoto = ' <img src="' + person.imageThumbUrl + '" class="card-img-top mb-3" alt="' + person.fullName + '">';
		}else {
			var personPhoto = '<i class="fa fa-user-circle text-secondary mb-3 mx-auto img-default"></i>';
		}
		var personPosition = person.peoplePosition && person.peoplePosition.length > 0  ? person.peoplePosition[0].positionName  : 'N/A';
		var personOrg = person.organization.name  ? person.organization.name  : 'N/A';
		var personPhone = person.primaryPhoneNumber && person.primaryPhoneNumber.value  ? '<a href="tel:' + person.primaryPhoneNumber.value + '">' + person.primaryPhoneNumber.value + '</a>'  : 'N/A';
var card = '<div class="col-md-3 mb-4">\
  <div class="card h-100 shadow p-3">\
    '+personPhoto+'<hr>\
    <div class="card-body p-0 pt-3 group-card">\
      <h5 class="card-title">' + person.fullName + '</h5>\
      <?php if(in_array('email', GROUP_MEMBERS_COLS)){ ?>
      <p class="card-text mb-1"><i class="fas fa-envelope mr-"></i><a href="mailto:'+ person.email+'"> ' + person.email + '</a></p>\
      <?php } if(in_array('organization', GROUP_MEMBERS_COLS)){ ?>
      <p class="card-text mb-1"><i class="fas fa-landmark mr-2"></i> ' + personOrg + '</p>\
      <?php } if(in_array('position', GROUP_MEMBERS_COLS)){ ?>
      <p class="card-text mb-1"><i class="fas fa-user-tie mr-2"></i> ' + personPosition + '</p>\
      <?php } if(in_array('phone', GROUP_MEMBERS_COLS)){ ?>
      <p class="card-text"><i class="fas fa-phone mr-2"></i> ' + personPhone + '</p>\
      <?php } ?>
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
      groupMembers(start); // re-fetch new data
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
dt_titleSearch('Search Members');
  <?php
}

  ?>
  //group list scroller
$(document).ready(function () {
  const $container = $('#groupTabs');
  const $scrollLeftBtn = $('#scrollLeftBtn');
  const $scrollRightBtn = $('#scrollRightBtn');
  const $wrapper = $container.parent();

  function updateScrollButtons() {
    const scrollLeft = $container.scrollLeft();
    const scrollWidth = $container[0].scrollWidth;
    const clientWidth = $container.outerWidth();
    const overflow = scrollWidth > clientWidth;
    const atStart = scrollLeft <= 0;
    const atEnd = scrollLeft + clientWidth >= scrollWidth - 1;

    // Only show buttons if overflow exists
    if (overflow) {
      $scrollLeftBtn.removeClass('d-none').addClass('d-inline-flex')
                    .toggleClass('disabled', atStart);
      $scrollRightBtn.removeClass('d-none').addClass('d-inline-flex')
                     .toggleClass('disabled', atEnd);
      $wrapper.addClass('px-4 mx-2');
    } else {
      $scrollLeftBtn.addClass('d-none').removeClass('d-inline-flex disabled');
      $scrollRightBtn.addClass('d-none').removeClass('d-inline-flex disabled');
      $wrapper.removeClass('px-4 mx-2');
    }
  }

	function scrollTabs(direction) {
	  const distance = $container.outerWidth() * 0.75; // 75% of visible width
	  const scrollAmount = direction === 'left' ? -distance : distance;
	   $container[0].scrollBy({ left: scrollAmount, behavior: 'smooth' });  
	}
  $scrollLeftBtn.on('click', () => {
    if (!$scrollLeftBtn.hasClass('disabled')) scrollTabs('left');
  });

  $scrollRightBtn.on('click', () => {
    if (!$scrollRightBtn.hasClass('disabled')) scrollTabs('right');
  });

  $container.on('scroll', updateScrollButtons);
  $(window).on('resize', updateScrollButtons);

  updateScrollButtons(); // Initial check
});

</script>