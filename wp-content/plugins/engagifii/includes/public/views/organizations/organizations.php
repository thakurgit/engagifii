<?php 
$enabled_modules = get_option('engagifii_enabled_modules', array()); 
  $setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('organization_directory', $enabled_modules)) {   
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );   
  $title_key = 0;
$allowedViewMode = isset($viewMode) && trim($viewMode) !== ''
    ? strtolower($viewMode)
    : 'both';

    $columns='';
    $columnNames=[];
    if (!empty(ORGANIZATION_COLS) && isArrayOfJsonStrings(ORGANIZATION_COLS)) {
          $columns = convertToObjectArray(ORGANIZATION_COLS);
          $columnNames = extractColNames(ORGANIZATION_COLS);
    }else{
        $options = get_option( 'ebt_api_settings' );
        $tenantCode = $options['dashboard_tenant_code'];
      $dataResponse = $this->submitApiRequest("OrganizationColumnList/".$tenantCode,array(),"GET",'dashboard');
        if(!$dataResponse['api_response']){
            echo '<h5 class="text-center text-danger"><strong><em>No data found! Please contact website admin.</em></strong><h5>';
            return;
        }
        $columns   = json_decode($dataResponse['api_response']);

    
    }
       $columns = array_filter($columns, function($column) {
    if (!isset($column->controlTypeId)) {
        error_log("Filtering: controlTypeId is not set");
        return true;
    }

    $id = (int) $column->controlTypeId;
    error_log("Filtering: controlTypeId = $id");

    return in_array($id, [3, 6, 15]);
});

?>
<div class="container-fluid ">
	<div class="row">
        <div class="col-12 justify-content-end d-flex">
            <div id="filter-content-wrapper" style="display: block; margin-right: 10px;">
    <div id="filter-loader" class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="filter-content" id="filterdp1">
        <div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative bg-light border">
            <i class="far fa-filter click-filter"></i>
            <span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span>
        </div>
        <div class="filter-border">
            <div class="filter-area d-none" id="filterdp">
                <div class="Engagiirow filter-top-bg col-sm-12 py-2 bg-dark text-white">
                    <div class="row">
                        <div class="col-6 text-left">
                            <span class="filter-title">
                                <i class="far fa-filter mr-2"></i> Filter
                                <span id="blockedchecked"></span> 
                            </span>
                        </div>
                        <div class="col-6 text-right">
                            <span class="clear-all" id="clear-all"> <i class="fal fa-sync"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" id="test" style="max-height: 400px; overflow-y: auto;">
                    <input type="hidden" id="isApplyACtive" value="0">
                    
                    <?php
                    $columnNames = [];
                    if (!empty(ORGANIZATION_COLS) && isArrayOfJsonStrings(ORGANIZATION_COLS)) {
                        $columnNames = extractColNames(ORGANIZATION_COLS);
                    }
                    ?>
                    
                    <?php if(in_array('OrganizationType', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Organization Type <i class="far fa-angle-down"></i></div>
                        <div class="content-area organizationType-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('Status', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Status <i class="far fa-angle-down"></i></div>
                        <div class="content-area status-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('Locations', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Locations <i class="far fa-angle-down"></i></div>
                        <div class="content-area locations-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('OrganizationTags', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Organization Tags <i class="far fa-angle-down"></i></div>
                        <div class="content-area organizationTags-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('CreatedOn', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Created Date <i class="far fa-angle-down"></i></div>
                        <div class="content-area d-none position-relative pb-2">
                            <input type="text" name="createdbetween" class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY">
                            <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('ModifiedOn', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Modified Date <i class="far fa-angle-down"></i></div>
                        <div class="content-area d-none position-relative pb-2">
                            <input type="text" name="modifiedbetween" class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY">
                            <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <!-- Dynamically generate filters for custom fields -->
                    <?php foreach ($columns as $column) {
    if (isset($column->fieldId) && !empty($column->fieldId)) { ?>
        <div class="filter-list border-bottom">
            <div class="heading-title py-2 d-flex align-items-center justify-content-between custom-field-filter-tittle">
                <?php echo esc_html($column->displayName ?? $column->fieldName); ?> <i class="far fa-angle-down"></i>
            </div>
            <div class="content-area custom-field-filter d-none" data-field-id="<?php echo esc_attr($column->fieldId); ?>">
                <ul class="list-group m-0">
                    <div class="loaders text-center py-3">
                        <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                </ul>
            </div>
        </div>
    <?php }
} ?>
                    
                </div>
                
                <div class="apply-filter">
                    <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data">Apply 
                        <span id="countFilterResult"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
          <?php if ($allowedViewMode === 'both'){ ?>
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
		    	 <?php  
           $labelOverrides = [
    'Created On' => 'Added',
    'Locations' => 'Location',
    'Organization Tags' => 'Tags',
    'Primary Email' => 'Email',
    'Phone Numbers' => 'Phone',
    // Add more as needed
];
$i = 0;    
           //print_r(ORGANIZATION_COLS);     
				  foreach (ORGANIZATION_COLS as $key){
 $json = json_decode(stripslashes($key), true);
 //print_r($json);
					  if (!$json || !isset($json['colName'], $json['displayName'])) {
						  continue;
					  }
					  if($json['colName'] == 'Name'){
                    	$title_key = $i;
                 	 }
					 $colClass = preg_replace('/\s+/', '', strtolower($json['colName']));
					$forDatatable[]['data'] = $colClass;
// Use override if exists, else default displayName
    $label = isset($labelOverrides[$json['displayName']]) ? $labelOverrides[$json['displayName']] : $json['displayName'];
?>
    <th class="text-capitalize <?php echo esc_attr($colClass); ?>">
        <?php echo esc_html($label); ?>
    </th>
<?php $i++; } ?>

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
  var organizationTypes = [];
  var statuses = [];
  var locations = [];
  var organizationTags = [];
  var customFields = {};
  var organizationId = '<?php echo isset($_GET['organizationId']) ? $_GET['organizationId'] : ''; ?>';
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
		"order": [[titleColumn, 'asc']],
      	"columnDefs": [ 
            { "targets": "_all", "orderable": false },
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
      if ($('.dataTables_empty').length) {
        $('.dataTables_empty').html('<div class="dt-empty-message"><h2 class="text-muted">No organization found at the moment. Please check back later or adjust your filters.</h2></div>');
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
			 renderPagination(parsedResponse.count, start, length, modulename='organizations');
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
 //var organizationGridCols = <?php echo json_encode(ORGANIZATION_COLS_GRID); ?>;
  var organizationGridCols = <?php 
  $gridCols = [];
    foreach (ORGANIZATION_COLS_GRID as $key) {
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
//console.log('Organization Grid Columns:', organizationGridCols); 
var orgFieldIcons = {
    email: '<i class="fas fa-envelope mr-2"></i>',
    phone: '<i class="fas fa-phone mr-2"></i>',
    status: '<i class="fas fa-user-check mr-2"></i>',
    organizationType: '<i class="fas fa-landmark mr-2"></i>',
    tags: '<i class="fas fa-tags mr-2"></i>',
    createdOn: '<i class="fas fa-calendar-plus mr-2"></i>',
    lastUpdated: '<i class="fas fa-sync-alt mr-2"></i>',
    name: '', // handled as card-title
};
function renderOrgGrid(data) {
  //console.log('Rendering Organization Grid with data:', data);
    var container = $('.grid-view .row');
    container.empty();
    if (data.length === 0) {
        container.append('<h3 class="text-secondary text-center col-12">No organizations found!</h3>');
        return;
    }
    data.forEach(function(org) {
        var orgPhoto = isValidUrl(org.imageThumbUrl)
            ? '<img src="' + org.imageThumbUrl + '" class="card-img-top mb-3" alt="' + org.name + '">'
            : '<i class="fa fa-user-circle text-secondary mb-3 mx-auto img-default"></i>';

       var fieldValues = {
    name: org.name || '--',
    primaryemail: org.primaryEmail ? '<a href="mailto:' + org.primaryEmail + '">' + org.primaryEmail + '</a>' :
        (org.secondaryEmails && org.secondaryEmails.length > 0 ? '<a href="mailto:' + org.secondaryEmails[0].value + '">' + org.secondaryEmails[0].value + '</a>' : '--'),
  phonenumbers: (org.phoneNumbers && org.phoneNumbers.length > 0 && org.phoneNumbers[0].value)
    ? formatPhoneUS(org.phoneNumbers[0].value)
    : '--',
    organizationtype: org.organizationType || '--',
    status: org.status || '--',
    locations: (org.locations && org.locations.length > 0)
    ? '<a tabindex="0" class="btn-link p-0" data-toggle="popover" data-html="true" data-content="' +
        buildLocationPopoverHtml(org.locations).replace(/"/g, '&quot;') +
        '">View Locations</a>'
    : '--',
    totalmembers: (org.totalMembers !== undefined && org.activeMembers !== undefined)
        ? org.activeMembers + '/' + org.totalMembers
        : '--',
    organizationtags: (org.organizationTags && org.organizationTags.length > 0)
        ? buildPopoverHtml('tags', org.organizationTags)
        : '--',
    createdon: org.createdOn ? new Date(org.createdOn).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
    modifiedon: org.modifiedOn ? new Date(org.modifiedOn).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
};

       var cardBody = '<h5 class="card-title">' + fieldValues.name + '</h5>';
        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            var label = colObj.displayName;
             if (label === 'Locations') label = 'Location';
            if (label === 'Created On') label = 'Added';
            if (label === 'Modified On') label = 'Last Updated';
            if (label === 'Primary Email') label = 'Email';
            if (label === 'Phone Numbers') label = 'Phone';
            if (label === 'Total Members') label = 'Total/Active Members';

            if (col === 'name') return;
            if (fieldValues[col] !== undefined) {
                cardBody += '<p class="card-text mb-1"><span class="font-weight-bold">' + label + ':</span> ' +
                    fieldValues[col] +
                    '</p>';
            }
        });

        var card = '<div class="col-md-3 mb-4">' +
            '<div class="card h-100 shadow p-3">' +
            orgPhoto + '<hr>' +
            '<div class="card-body p-0 pt-3 group-card">' +
            cardBody +
            '</div></div></div>';
        container.append(card);
    });
     setTimeout(function() {
        $('[data-toggle="popover"]').popover({ trigger: 'hover', html: true });
    }, 100);
}
function getFieldLabel(field) {
    return field.replace(/([a-z])([A-Z])/g, '$1 $2').replace(/^./, function(str){ return str.toUpperCase(); });
}
function formatPhoneUS(phone) {
    phone = phone.replace(/\D/g, '');
    if (phone.length === 10) {
        return '(' + phone.substr(0,3) + ') ' + phone.substr(3,3) + '-' + phone.substr(6,4);
    }
    return phone;
}

// function isValidUrl(url) {
//     try { new URL(url); return true; } catch (_) { return false; }
// }
function buildLocationPopoverHtml(locations) {
    if (!Array.isArray(locations) || locations.length === 0) return '--';
    var html = '<div style=\'min-width:220px\'><h6 class="text-center mb-2">Locations</h6><ul class="list-unstyled mb-0">';
    locations.forEach(function(loc, idx) {
        html += '<li class="mb-2' + (idx % 2 === 0 ? ' bg-light' : '') + '">';
        html += '<div><b>' + (loc.fieldName || 'Address') + ':</b></div>';
        html += '<div>' +
            (loc.address || '') +
            (loc.addressLine2 ? ', ' + loc.addressLine2 : '') +
            (loc.city ? ', ' + loc.city : '') +
            (loc.state ? ', ' + loc.state : '') +
            (loc.zipCode ? ', ' + loc.zipCode : '') +
            (loc.country ? ', ' + loc.country : '') +
            '</div>';
        //     if (loc.lat && loc.lng) {
        //     html += '<div class="embed-responsive embed-responsive-16by9 mt-2" style="height:120px;"><iframe class="embed-responsive-item" style="width:100%;height:100%;" src="https://maps.google.com/maps?q=' +
        //         encodeURIComponent(loc.lat) + ',' + encodeURIComponent(loc.lng) +
        //         '&hl=en&z=14&amp;output=embed" allowfullscreen></iframe></div>';
        // }
        html += '</li>';
    });
    html += '</ul></div>';
    return html;
}

<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Organization');
  <?php
}

  ?>
  
// Load filters after page loads
window.addEventListener("load", function () {
    $('#filter-loader').show(); // Show loader
    $('.filter-content').hide(); // Hide filter content
    
    $.ajax({
        type: "post",
        url: engagifiiUrl_ajaxurl,
        data: {
            action: 'organizationFilters',
             filterParams: <?php echo json_encode($columnNames); ?>,
             organizationId: organizationId,
        },
        success: function(response) { 
            console.log('Raw response:', response);
            try {
                var parsedResponse = JSON.parse(response);
                console.log('Parsed response:', parsedResponse);
                for (var key of Object.keys(parsedResponse)) {
                if (key == 'OrganizationType') {
                    organizationTypes = parsedResponse[key];
                    htmlcontent = '<ul class="list-group m-0">';
                    for (var i = 0; i < organizationTypes.length; i++) {
                        htmlcontent += '<li class="list-group-item border-0 py-1 px-2"><label class="m-0"><input class="mr-2" data-filter-key="OrganizationType" type="checkbox" value="' + organizationTypes[i]['organizationType'] + '">' + organizationTypes[i]['organizationType'] + '</label></li>';
                    }
                    htmlcontent += '</ul>';
                    $('.organizationType-filter').html(htmlcontent);
                } else if (key == 'Status') {
                    statuses = parsedResponse[key];
                    htmlcontent = '<ul class="list-group m-0">';
                    for (var i = 0; i < statuses.length; i++) {
                        htmlcontent += '<li class="list-group-item border-0 py-1 px-2"><label class="m-0"><input class="mr-2" data-filter-key="Status" type="checkbox" value="' + statuses[i]['status'] + '">' + statuses[i]['status'] + '</label></li>';
                    }
                    htmlcontent += '</ul>';
                    $('.status-filter').html(htmlcontent);
                } else if (key == 'Locations') {
                    locations = parsedResponse[key];
                    htmlcontent = '<ul class="list-group m-0">';
                    for (var i = 0; i < locations.length; i++) {
                        htmlcontent += '<li class="list-group-item border-0 py-1 px-2"><label class="m-0"><input class="mr-2" data-filter-key="Locations" type="checkbox" value="' + locations[i]['location'] + '">' + locations[i]['location'] + '</label></li>';
                    }
                    htmlcontent += '</ul>';
                    $('.locations-filter').html(htmlcontent);
                } else if (key == 'OrganizationTags') {
                    organizationTags = parsedResponse[key];
                    htmlcontent = '<ul class="list-group m-0">';
                    for (var i = 0; i < organizationTags.length; i++) {
                        htmlcontent += '<li class="list-group-item border-0 py-1 px-2"><label class="m-0"><input class="mr-2" data-filter-key="OrganizationTags" type="checkbox" value="' + organizationTags[i]['tagName'] + '">' + organizationTags[i]['tagName'] + '</label></li>';
                    }
                    htmlcontent += '</ul>';
                    $('.organizationTags-filter').html(htmlcontent);
                }
            }
            dt_filterActivate();
            $('#filter-loader').hide(); // Hide loader
            $('.filter-content').fadeIn(); // Show filter content
        } catch (e) {
            console.error('Error parsing filter response:', e);
            console.error('Response was:', response);
            $('#filter-loader').hide();
            $('.filter-content').fadeIn();
        }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            $('#filter-loader').hide();
            $('.filter-content').fadeIn();
        }
    });
});

function filterEvents(minDate, maxDate) {
    startdate = '';
    enddate = '';
    $('input[name="createdbetween"]').val('');
    
    $('.filter-list input[type=checkbox]').change(function() {
        if ($('#apply-filter-data .spinner-border').length == 0) {
            $('#apply-filter-data').attr('disabled', '').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
        }
        countFilterData();
    });
}

// Common function to collect checked values from a filter area
function getCheckedValues(selector) {
    var values = [];
    $(selector + ' input[type="checkbox"]:checked').each(function() {
        values.push($(this).val());
    });
    return values;
}

// Common function to clear all checkboxes in a filter area
function clearAllCheckboxes(selector) {
    $(selector + ' input[type="checkbox"]').prop('checked', false);
}

// Common function to reset customFields object
function resetCustomFields() {
    customFields = {};
}

// Common function to update customFields object from DOM
function updateCustomFieldsFromDOM() {
    customFields = {};
    $('.custom-field-filter').each(function() {
        var fieldId = $(this).data('field-id');
        var checked = [];
        $(this).find('input[type="checkbox"]:checked').each(function() {
            checked.push($(this).val());
        });
        if (checked.length > 0) {
            customFields[fieldId] = checked;
        }
    });
}

// Apply filter button click handler
$('#apply-filter-data').click(function() {
    organizationTypes = getCheckedValues('.organizationType-filter');
    statuses = getCheckedValues('.status-filter');
    locations = getCheckedValues('.locations-filter');
    organizationTags = getCheckedValues('.organizationTags-filter');
    updateCustomFieldsFromDOM();
    console.log('Selected Filters:', {
        organizationTypes: organizationTypes,
        statuses: statuses,
        locations: locations,
        organizationTags: organizationTags,
        customFields: customFields
    });
    if ($.fn.DataTable.isDataTable('#ebtmaintable')) {
        table.draw();
    }
    if (viewMode === 'grid') {
        start = 0;
        OrgList(start);
    }
    $('.filter-area').addClass('d-none');
    $('#apply-filter-data .spinner-border').remove();
    $('#apply-filter-data').removeAttr('disabled');
});

// Clear all filters functionality
$('#clear-all').click(function() {
    organizationTypes = [];
    statuses = [];
    locations = [];
    organizationTags = [];
    customFieldSelections = {};
    clearAllCheckboxes('.filter-list');
    clearAllCheckboxes('.custom-field-filter');
    resetCustomFields();
    $('input[name="createdbetween"]').val('');
    $('input[name="modifiedbetween"]').val('');
    if ($.fn.DataTable.isDataTable('#ebtmaintable')) {
        table.draw();
    }
    if (viewMode === 'grid') {
        start = 0;
        OrgList(start);
    }
    $('.filter-area').addClass('d-none');   
     $('.filter-icon').removeClass('active');
        $('.filter-icon span').hide();
          // Reset the filter count display
    $('#countFilterResult').text('')
});

// Collect selected custom field values dynamically
// Object to store selected values for each custom field
var customFieldSelections = {};

$(document).on('click', '.custom-field-filter-tittle', function() {
    // Dynamically fetch the fieldId of the clicked element
    var fieldId = $(this).closest('.filter-list').find('.custom-field-filter').data('field-id');
    var selectedDate = new Date().toISOString().split('T')[0];

    if (!fieldId) {
        console.error('Field ID not found for the clicked element');
        return;
    }

    // Show loader only for the specific custom field
    $('.custom-field-filter[data-field-id="' + fieldId + '"] .loaders').show();

    $.ajax({
        type: 'POST',
        url: engagifiiUrl_ajaxurl,
        data: {
            action: 'getCustomFieldFilterData',
            fieldConfigurationId: fieldId,
            selectedDate: selectedDate
        },
        success: function(response) {
            console.log('Custom Field Filter Data:', response);
            if (response.success) {
                if (typeof response.data === 'object' && response.data !== null) {
                    var data = Array.isArray(response.data) ? response.data : [response.data];
                    var htmlContent = '';
                    data.forEach(function(item) {
                        var value = item.value || item.name || item.id || '';
                        var display = item.displayName || item.name || value || '';
                        if (value && display) {
                            htmlContent += '<li class="list-group-item border-0 py-1 px-2">' +
                                '<label class="m-0">' +
                                '<input class="mr-2" type="checkbox" value="' + value + '">' +
                                display + '</label></li>';
                        }
                    });
                    if (htmlContent) {
                        $('.custom-field-filter[data-field-id="' + fieldId + '"] ul').html(htmlContent);
                    } else {
                        $('.custom-field-filter[data-field-id="' + fieldId + '"] ul').html('<li class="list-group-item border-0 py-1 px-2 text-muted">No options available</li>');
                    }
                } else {
                    $('.custom-field-filter[data-field-id="' + fieldId + '"] ul').html('<li class="list-group-item border-0 py-1 px-2 text-muted">No data available</li>');
                }
            } else {
                console.error('Failed to fetch custom field filter data:', response.data.message || 'No data returned');
            }
        },
        error: function() {
            console.error('AJAX request failed');
        },
        complete: function() {
            $('.custom-field-filter[data-field-id="' + fieldId + '"] .loaders').hide();
        }
    });
});

// Save selected values when checkboxes are changed
$(document).on('change', '.custom-field-filter input[type=checkbox]', function() {
    var fieldId = $(this).closest('.custom-field-filter').data('field-id');
    if (!customFieldSelections[fieldId]) {
        customFieldSelections[fieldId] = [];
    }

    var value = $(this).val();
    if ($(this).is(':checked')) {
        if (!customFieldSelections[fieldId].includes(value)) {
            customFieldSelections[fieldId].push(value);
        }
    } else {
        customFieldSelections[fieldId] = customFieldSelections[fieldId].filter(function(id) {
            return id !== value;
        });
    }
});

function countFilterData() {
    var organizationTypes = getCheckedValues('.organizationType-filter');
    var statuses = getCheckedValues('.status-filter');
    var locations = getCheckedValues('.locations-filter');
    var organizationTags = getCheckedValues('.organizationTags-filter');
    updateCustomFieldsFromDOM();

    $.ajax({
        type: "post",
        url: engagifiiUrl_ajaxurl,
        data: {
            action: 'organizationCountFilterData',
            organizationTypes: organizationTypes,
            statuses: statuses,
            locations: locations,
            organizationTags: organizationTags,
            customFields: customFields
        },
        success: function(response) {
            var element = document.getElementById("countFilterResult");
            $('#apply-filter-data .spinner-border').remove();
            $('#apply-filter-data').removeAttr('disabled');
            if (element) {
                element.innerHTML = " (" + response.api_response + ")";
            }
        }
    });
}

// Trigger countFilterData on filter changes
$(document).on('change', '.filter-list input[type=checkbox]', function() {   
    countFilterData();
    var appliedCategories = $('.filter-list').filter(function() {
        return $(this).find('input[type=checkbox]:checked').length > 0;
    }).length;
    // Store the count but do not show it yet
    $('.filter-icon').data('appliedCategories', appliedCategories);
});

$('#apply-filter-data').click(function() {
    // Show the count on the filter icon after Apply is clicked
    var appliedCategories = $('.filter-icon').data('appliedCategories') || 0;
    if (appliedCategories > 0) {
        $('.filter-icon').addClass('active');
        $('.filter-icon span').text(appliedCategories).show();
    } else {
        $('.filter-icon').removeClass('active');
        $('.filter-icon span').hide();
    }
});

$('.click-filter').click(function(e) {
    e.preventDefault();
    $('.filter-area').toggleClass('d-none');
});

$(document).click(function(event) {
    var $filterArea = $('.filter-area');
    var $filterIcon = $('.filter-icon');
    if (!$filterArea.is(event.target) && !$filterArea.has(event.target).length &&
        !$filterIcon.is(event.target) && !$filterIcon.has(event.target).length) {
        $filterArea.addClass('d-none');
    }
});

</script>



