<?php 
if (!isset($groupId) || empty($groupId)) {
    echo '<h3 class="text-center text-muted">Group ID not found</h3>';
    return;
}
$allowedViewMode = isset($viewMode) && trim($viewMode) !== ''
    ? strtolower($viewMode)
    : 'both';
	$collection 	=	array();
  $forDatatable 	= 	array();
print_r(GROUP_MEMBERS_COLS);
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
<!-- Group Tabs -->
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
 .card-text i {
    display: none !important;
}
</style>

<?php if ($allowedViewMode === 'list' ||$allowedViewMode === 'both' ){ ?>
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
            <th class="text-capitalize <?php echo preg_replace('/\s+/', '', strtolower($key)); ?>">
    <?php echo trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $key)); ?>
</th>
        <?php } ?>
 		    

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>
<?php } if ($allowedViewMode === 'grid' ||$allowedViewMode === 'both' ){?>
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
  var groupId = '<?php echo $groupId;?>';
  var viewMode='<?php echo $allowedViewMode; ?>';
  var start = 0;
  var length = 8;
  var titleColumn = '<?php echo $title_key; ?>';
  <?php  if ($allowedViewMode === 'grid' ){?>
  groupMembers(start);
  <?php } ?>
  
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
           { "targets": "_all", "orderable": false }
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
			          dt_scroll();
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
  var groupMemberCols = <?php echo json_encode(GROUP_MEMBERS_COLS_GRID); ?>;
var fieldIcons = {
    email:    '<i class="fas fa-envelope mr-2"></i>',
    organization: '<i class="fas fa-landmark mr-2"></i>',
    position: '<i class="fas fa-user-tie mr-2"></i>',
    phone:    '<i class="fas fa-phone mr-2"></i>',
    status:   '<i class="fas fa-user-check mr-2"></i>',
    userstatus: '<i class="fas fa-user-shield mr-2"></i>',
    lastupdated: '<i class="fas fa-sync-alt mr-2"></i>',
    lastlogin: '<i class="fas fa-sign-in-alt mr-2"></i>',
    age:      '<i class="fas fa-birthday-cake mr-2"></i>',
    tags:     '<i class="fas fa-tags mr-2"></i>',
    terms:    '<i class="fas fa-calendar-alt mr-2"></i>',
    added:    '<i class="fas fa-plus mr-2"></i>',
    totaltimeworked: '<i class="fas fa-clock mr-2"></i>',
    persontype: '<i class="fas fa-id-badge mr-2"></i>',
    roles:    '<i class="fas fa-user-tag mr-2"></i>',
    department: '<i class="fas fa-building mr-2"></i>',
    region:   '<i class="fas fa-globe-americas mr-2"></i>',
    name:     '', // handled as card-title
};


	function getFieldLabel(field) {
    // Insert space before each uppercase letter (except first), then capitalize first letter
    return field
        .replace(/([a-z])([A-Z])/g, '$1 $2') // add space before capital
        .replace(/^./, function(str){ return str.toUpperCase(); }); // capitalize first letter
}

function renderGroupGrid(data) {
    var container = $('.grid-view .row');
    container.empty(); // Clear previous content
    if (data.length === 0) {
        container.append('<h3 class="text-secondary text-center col-12">No members found!</h3>');
        return;
    }
    data.forEach(function(item) {
        var person = item.people;
        var personPhoto = isValidUrl(person.imageThumbUrl)
            ? ' <img src="' + person.imageThumbUrl + '" class="card-img-top mb-3" alt="' + person.fullName + '">'
            : '<i class="fa fa-user-circle text-secondary mb-3 mx-auto img-default"></i>';

        // Prepare field values INSIDE the loop, after person is defined
       // ...existing code...
var fieldValues = {
    email:    person.email ? '<a href="mailto:' + person.email + '">' + person.email + '</a>' : '--',
    organization: person.organization && person.organization.name ? person.organization.name : '--',
    position: (person.peoplePosition && person.peoplePosition.length > 0)
        ? buildPopoverHtml('position', person.peoplePosition)
        : '--',
    department: (person.peopleDepartment && person.peopleDepartment.length > 0)
        ? buildPopoverHtml('department', person.peopleDepartment)
        : '--',
    roles: (person.roles && person.roles.length > 0)
        ? buildPopoverHtml('roles', person.roles)
        : '--',
    persontype: (person.persontype && person.persontype.length > 0)
        ? buildPopoverHtml('persontype', person.persontype)
        : '--',
    terms: (person.terms && person.terms.length > 0)
        ? buildPopoverHtml('terms', person.terms)
        : '--',
  region: (person.terms && person.terms.length > 0)
    ? buildPopoverHtml('region', extractRegionsFromTerms(person.terms))
    : '--',
    phone: (person.primaryPhoneNumber && person.primaryPhoneNumber.value && person.primaryPhoneNumber.value.length === 10)
        ? '<a href="tel:' + person.primaryPhoneNumber.value + '">' + person.primaryPhoneNumber.value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3') + '</a>'
        : '--',
    status: person.status || '--',
    userstatus: person.userstatus || '--',
    lastupdated: person.lastupdated || '--',
    lastlogin: person.lastlogin || '--',
    age: person.age || '--',
    tags: Array.isArray(person.tags) ? person.tags.join(', ') : (person.tags || '--'),
    added: person.added || '--',
    totaltimeworked: person.totaltimeworked || '--',
    name: person.fullName || '--'
};

        var cardBody = '<h5 class="card-title">' + fieldValues.name + '</h5>';
        groupMemberCols.forEach(function(col) {
            if (col === 'name') return; // already shown as title
            if (fieldValues[col] !== undefined) {
                cardBody += '<p class="card-text mb-1">' +
                    (fieldIcons[col] || '') +
                    '<span class="font-weight-bold">' + getFieldLabel(col) + ':</span> ' +
                    fieldValues[col] +
                    '</p>';
            }
        });

        var card = '<div class="col-md-3 mb-4">' +
            '<div class="card h-100 shadow p-3">' +
            personPhoto + '<hr>' +
            '<div class="card-body p-0 pt-3 group-card">' +
            cardBody +
            '</div></div></div>';
        container.append(card);
    });

    // Initialize popovers after rendering
    setTimeout(function() {
        $('[data-toggle="popover"]').popover();
    }, 100);
}

function extractRegionsFromTerms(terms) {
    if (!Array.isArray(terms)) return [];
    const regions = [];
    terms.forEach(term => {
        if (term.regionName) {
            regions.push({
                regionName: term.regionName,
                organizationName: term.position && term.position.organizationName ? term.position.organizationName : '',
                electionTermName: term.electionTermName || ''
            });
        }
    });
    return regions;
}

function buildPopoverHtml(field, items) {
    if (!Array.isArray(items) || items.length === 0) return '--';

    // Helper to get label for the field type
   function getPluralLabel(field, count) {
        if (field === 'position') return count + ' Positions';
        if (field === 'department') return count + ' Departments';
        if (field === 'roles') return count + ' Roles';
        if (field === 'persontype') return count + ' Person Types';
        if (field === 'terms') return count + ' Terms';
        if (field === 'tags') return count + ' Tags';
        if (field === 'region') return count + ' Regions';
        return count + ' Items';
    }
     if (field === 'region') {
        var pluralLabel = getPluralLabel(field, items.length);
        var linkText = (items.length === 1) ? items[0].regionName : pluralLabel;
        var htmlList = '<div class="dropdown-menu show p-0" style="min-width:600px !important;">';
        items.forEach(function(it) {
            htmlList += '<div class="px-3 py-2 border-bottom small" style="white-space:normal;">' +
                '<strong>' + (it.regionName || '') + '</strong>' +
                '<br><span class="d-block" style="color:#2176d2;">' + (it.organizationName || '') + '</span>' +
                '<span class="d-block">Term: ' + (it.electionTermName || '') + '</span>' +
                '</div>';
        });
        htmlList += '</div>';
        return '<span class="d-inline-block pr-2">' +
            '<span tabindex="0" class="badge badge-primary" data-toggle="popover" data-html="true" data-trigger="focus" data-content="' +
            htmlList.replace(/"/g, '&quot;') +
            '">' + linkText + '</span></span>';
    }

    // Single item: show only the main label (no org/department)
    if (items.length === 1) {
        if (items[0].positionName) {
            return items[0].positionName;
        } else if (items[0].departmentName) {
            return items[0].departmentName;
        } else if (items[0].electionTermName) {
            return items[0].electionTermName;
        } else if (items[0].name) {
            return items[0].name;
        } else if (items[0].tagName) {
            return items[0].tagName;
        } else {
            return items[0].toString();
        }
    }

    // Multiple items: show "N Positions" (or similar) as clickable badge
    var pluralLabel = getPluralLabel(field, items.length);

    // Build HTML list for popover content (like list view)
    var htmlList = '<div class="dropdown-menu show p-0" style="min-width:600px !important;">';
    items.forEach(function(it, idx) {
        var label = '';
        if (it.positionName) {
            label = '<strong>' + it.positionName + '</strong>';
            if (it.organizationName) label += '<div class="text-muted small">' + it.organizationName + '</div>';
        } else if (it.departmentName) {
            label = '<strong>' + it.departmentName + '</strong>';
            if (it.organizationName) label += '<div class="text-muted small">' + it.organizationName + '</div>';
        } else if (it.electionTermName) {
            label = '<strong>' + it.electionTermName + '</strong>';
            if (it.position && it.position.organizationName) label += '<div class="text-muted small">' + it.position.organizationName + '</div>';
        } else if (it.name) {
            label = it.name;
        } else if (it.tagName) {
            label = it.tagName;
        } else {
            label = it.toString();
        }
        htmlList += '<div class="px-3 py-2 border-bottom small" style="white-space:normal;">' + label + '</div>';
    });
    htmlList += '</div>';

    // Popover trigger: show "N Positions" (or similar)
    var html = '<span class="d-inline-block pr-2">' +
        '<span tabindex="0" class="badge badge-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-content="' +
        htmlList.replace(/"/g, '&quot;') +
        '">' + pluralLabel + '</span></span>';
    return html;
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
 $('<style>.popover-wide{min-width:350px !important;max-width:600px !important;width:100% !important;}</style>').appendTo('head');
</script>