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
.badge[data-toggle="popover"] + .popover,
.badge[data-toggle="popover"].popover {
    min-width: 400px !important;
    max-width: 600px;
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
					  $json = json_decode(stripslashes($key), true);
					  if (!$json || !isset($json['colName'], $json['displayName'])) {
						  continue;
					  }
					  if($json['colName'] == 'name'){
                    	$title_key = $i;
                 	 }
					 $colClass = preg_replace('/\s+/', '', strtolower($json['colName']));
					$forDatatable[]['data'] = $colClass;
				  ?>
            <th class="text-capitalize <?php echo esc_attr($colClass); ?>">
    <?php echo esc_html($json['displayName']); ?>
</th>
        <?php  $i++; } ?>
 		    

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>
<?php } if ($allowedViewMode === 'grid' ||$allowedViewMode === 'both' ){  ?>
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
		"order": [[titleColumn, 'asc']],
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
      if ($('.dataTables_empty').length) {
        $('.dataTables_empty').html('<div class="dt-empty-message"><h2 class="text-muted">No member found at the moment. Please check back later or adjust your filters.</h2></div>');
        $('.dataTables_paginate').hide(); // Hide pagination
        $('.dataTables_length').hide();   // Hide "Show X records per page"
      }else{
        $('.dataTables_paginate').show(); // Show pagination if records exist
        $('.dataTables_length').show();   // Show "Show X records per page"
      }
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
			 renderPagination(parsedResponse.count, start, length, modulename = 'groupMembers');
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
 // var groupMemberCols = <?php echo json_encode(GROUP_MEMBERS_COLS_GRID); ?>;
 //print_r(GROUP_MEMBERS_COLS_GRID);
  // Convert GROUP_MEMBERS_COLS_GRID to a more usable format
var groupMemberCols = <?php
    $gridCols = [];
    foreach (GROUP_MEMBERS_COLS_GRID as $key) {
        $json = json_decode(stripslashes($key), true);
       if (!$json || !isset($json['colName'], $json['displayName'])) continue;
      $colClass = preg_replace('/\s+/', '', strtolower($json['colName']));
      $gridCols[] = [
          'colClass' => $colClass,
          'displayName' => $json['displayName'],
          'colName' => $json['colName']
      ];
  }
    echo json_encode($gridCols);
?>;
 
  //console.log(groupMemberCols);
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
  // console.log(data);
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
    primaryorganization: person.organization && person.organization.name ? person.organization.name : '--',
    currentposition: (person.peoplePosition && person.peoplePosition.length > 0)
        ? buildPopoverHtml('position', person.peoplePosition)
        : '--',
    currentdepartment: (person.peopleDepartment && person.peopleDepartment.length > 0)
        ? buildPopoverHtml('department', person.peopleDepartment)
        : '--',
    roles: (person.roles && person.roles.length > 0)
        ? buildPopoverHtml('roles', person.roles)
        : '--',
    persontype: (person.personTypes && person.personTypes.length > 0)
        ? buildPopoverHtml('persontype', person.personTypes)
        : '--',
    term: (person.terms && person.terms.length > 0)
        ? buildPopoverHtml('terms', person.terms)
        : '--',
  region: (person.terms && person.terms.length > 0)
    ? buildPopoverHtml('region', extractRegionsFromTerms(person.terms))
    : '--',
    phone: (person.primaryPhoneNumber && person.primaryPhoneNumber.value && person.primaryPhoneNumber.value.length === 10)
        ? '<a href="tel:' + person.primaryPhoneNumber.value + '">' + person.primaryPhoneNumber.value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3') + '</a>'
        : '--',
    status: person.status || '--',
    userstatus: person.userStatus || '--',
    modifieddate: person.modifiedDate ? new Date(person.modifiedDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
    lastlogin: person.lastLogin || '--',
    age: person.age || '--',
    tags: (person.tags && person.tags.length > 0)
    ? buildPopoverHtml('tags', person.tags)
    : '--',
    createddate: person.createdDate ? new Date(person.createdDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
    totaltimeworked: person.totalTimeWorked ? formatMonthsToYearsAndMonths(person.totalTimeWorked) : '--',
    // createdon: org.createdOn ? new Date(org.createdOn).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
    // modifiedon: org.modifiedOn ? new Date(org.modifiedOn).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
    name: person.fullName || '--'
};
   // Add custom fields dynamically
        var customFields = (person.customFields && Array.isArray(person.customFields)) ? person.customFields : [];
        customFields.forEach(function(field) {
    var key = (field.fieldName || field.title || field.name || '').toLowerCase().replace(/\s+/g, '');
    var value = field.selectedValue || field.value || '';

    // Handle address fields (controlTypeId == 9)
    if (field.controlTypeId == 9 && value) {
        var address = (typeof value === 'string') ? JSON.parse(value) : value;
        if (address && typeof address === 'object') {
            var parts = [];
            if (address.address) parts.push(address.address);
            if (address.addressLine2) parts.push(address.addressLine2);
            if (address.city) parts.push(address.city);
            if (address.state) parts.push(address.state);
            if (address.zipCode) parts.push(address.zipCode);
            var formatted = parts.filter(Boolean).join(', ');
            fieldValues[key] = formatted || '--';
        } else {
            fieldValues[key] = '--';
        }
    }else if (field.controlTypeId == 11 && value) {
    // Extract digits and extension (e.g., 6675553000ext10)
    var match = value.match(/^(\D*\d{3}\D*\d{3}\D*\d{4})(?:\D*(?:ext|x|extension)\D*(\d+))?/i);
    var digits = value.replace(/\D/g, '').substring(0, 10);
    var extMatch = value.match(/(?:ext|x|extension)\s*\.?\s*(\d+)/i);
    var ext = extMatch ? extMatch[1] : '';

    if (digits.length === 10) {
        var formatted = digits.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        if (ext) {
            formatted += ' ext ' + ext;
        }
        fieldValues[key] = '<a href="tel:' + digits + (ext ? ',,' + ext : '') + '">' + formatted + '</a>';
    } else {
        fieldValues[key] = value;
    }
}
else if (field.controlTypeId == 1 && value) {
    // Format date as "MMM DD, YYYY"
    var dateObj = new Date(value);
    if (!isNaN(dateObj.getTime())) {
        fieldValues[key] = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    } else {
        fieldValues[key] = value;
    }
}
else if (key && value !== '') {
        fieldValues[key] = value;
    }
});

groupMemberCols.forEach(function(colObj) {
    var col = colObj.colClass;
    if (typeof fieldValues[col] === 'undefined') {
        fieldValues[col] = '--';
    }
});

        var cardBody = '<h5 class="card-title">' + fieldValues.name + '</h5>';
        groupMemberCols.forEach(function(colObj) {
           var col = colObj.colClass;
            var label = colObj.displayName;
            if (col === 'name') return; // already shown as title
            if (fieldValues[col] !== undefined) {
                cardBody += '<p class="card-text mb-1">' +
                    (fieldIcons[col] || '') +
                    '<span class="font-weight-bold">' + label + ':</span> ' +
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


//grid pagination


//search members
<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Members');
  <?php
}

  ?>
 
</script>