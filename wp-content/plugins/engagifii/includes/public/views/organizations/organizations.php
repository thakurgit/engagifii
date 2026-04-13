<?php 
$enabled_modules = get_option('engagifii_enabled_modules', array()); 
  $setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('organization_directory', $enabled_modules)) {   
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}

// Handle tags parameter from shortcode (optional - filters organizations by tags if provided)
if (!isset($orgTags)) {
    $orgTags = '';
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
      $dataResponse = $this->submitApiRequest("OrganizationColumnListWithCF/".$tenantCode,array(),"GET",'dashboard');
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
        <div class="col-12 d-flex justify-content-between align-items-center">
            <!-- Search box for Grid view and combined (both) view -->
            <?php if ($allowedViewMode === 'grid' || $allowedViewMode === 'both'){ ?>
            <div class="form-group mb-0" id="grid-search-wrapper" style="flex: 1; max-width: 400px;<?php if ($allowedViewMode === 'both') { ?> display:none;<?php } ?>">
                <input type="text" id="grid-search-box" class="form-control" placeholder="Search Organizations..." />
            </div>
            <?php } ?>
            
            <div class="d-flex align-items-center ml-auto">
                <div id="filter-content-wrapper" style="display: block; margin-right: 10px;">
                    <div id="filter-loader" class="text-center" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="filter-content" id="filterdp1" style="display:block;">
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
                                    
                                    <!-- Dynamic filters will be loaded here -->
                                    <div id="dynamic-filters-container">
                                        <div class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading filters...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading filters...</p>
                                        </div>
                                    </div>
                                    
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
                <?php } ?>
            </div>
        </div>
        <div class="col-12 mb-4"></div>
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
.grid-view .card a,
.grid-view .card .btn-link,
.grid-view .card [data-toggle="popover"],
.grid-view .card [data-toggle="dropdown"],
.grid-view .card .org-location-popover {
  cursor: pointer;
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
jQuery(document).ready(function($) {
  
  // Ensure filter area starts hidden
  $('.filter-area').addClass('d-none');
  
});

  //var groupId = $('#groupTabs li:first-child a').attr('id');
  var viewMode='<?php echo $allowedViewMode; ?>';
  var start = 0;
  var length = 8;
  var titleColumn = '<?php echo $title_key; ?>';
  var isUserLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
  var wpLoginUrl = '<?php echo esc_js( wp_login_url( get_permalink() ) ); ?>';
  // Fields to hide/blur for non-logged-in users (admin-configurable)
  var orgGuestHiddenFields = <?php
    $ghf = defined('ORGANIZATION_GUEST_HIDDEN_FIELDS') ? ORGANIZATION_GUEST_HIDDEN_FIELDS : ['phoneNumbers', 'primaryEmail'];
    $normalized = array_map(function($f) { return preg_replace('/\s+/', '', strtolower($f)); }, $ghf);
    echo json_encode(array_values($normalized));
  ?>;
  var organizationTypes = [];
  var statuses = [];
  var locations = [];
  var initialOrganizationTags = <?php echo isset($orgTags) && !empty($orgTags) ? json_encode(array_map('trim', explode(',', $orgTags))) : '[]'; ?>;
  var organizationTags = initialOrganizationTags.slice(); // Copy initial tags
  var customFields = {};
  var organizationId = '<?php echo isset($_GET['organizationId']) ? $_GET['organizationId'] : ''; ?>';
  
  // Global object to store dynamic filter data (declare early to avoid reference errors)
  var dynamicFiltersConfig = [];
  var dynamicFilterSelections = {};
  
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
		  $('#grid-search-wrapper').show();
		  OrgList(start);
	  } else {
		  $('.list-view').show();
		  $('.grid-view').hide();
		  $('#grid-search-wrapper').hide();
		  $('#grid-search-box').val('');
		  gridSearchQuery = '';
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
				
				// Send filter parameters
				d.organizationTypes = organizationTypes;
				d.statuses = statuses;
				d.locations = locations;
				d.organizationTags = organizationTags;
				
				// Merge custom fields with dynamic filter selections
				var allCustomFields = Object.assign({}, customFields || {});
				if (dynamicFilterSelections && typeof dynamicFilterSelections === 'object') {
					Object.keys(dynamicFilterSelections).forEach(function(key) {
						if (dynamicFilterSelections[key] && dynamicFilterSelections[key].length > 0) {
							allCustomFields[key] = dynamicFilterSelections[key];
						}
					});
				}
				d.customFields = allCustomFields;
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
	var gridSearchQuery = '';
	function OrgList(start){ 
		 $('.grid-view #eng-overlay').show();
		  $('.grid-view .row').css('opacity','.3');
		  
		// Merge custom fields with dynamic filter selections
		var allCustomFields = Object.assign({}, customFields || {});
		if (dynamicFilterSelections && typeof dynamicFilterSelections === 'object') {
			Object.keys(dynamicFilterSelections).forEach(function(key) {
				if (dynamicFilterSelections[key] && dynamicFilterSelections[key].length > 0) {
					allCustomFields[key] = dynamicFilterSelections[key];
				}
			});
		}
		
		// Build columns array structure like DataTable does, including search text
		var columns = [];
		columns[titleColumn] = {
			search: {
				value: gridSearchQuery
			}
		};
		  
	   $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'getOrganizations',			 
			  viewMode:'Grid',
			  length:length,
			  start:start,
			  titleColumn: titleColumn,
			  columns: columns,
			  order: [{dir: 'asc'}],
			  organizationTypes: organizationTypes,
			  statuses: statuses,
			  locations: locations,
			  organizationTags: organizationTags,
			  customFields: allCustomFields
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
	
	// Grid search functionality — server-side search via API
	var gridSearchTimeout;
	$('#grid-search-box').on('keyup', function() {
		clearTimeout(gridSearchTimeout);
		gridSearchQuery = $(this).val();
		gridSearchTimeout = setTimeout(function() {
			start = 0;
			OrgList(start);
		}, 400);
	});
	
	//grid layout
 //var organizationGridCols = <?php echo json_encode(ORGANIZATION_COLS_GRID); ?>;
  var organizationGridCols = <?php 
  $gridCols = [];
    foreach (ORGANIZATION_COLS_GRID as $key) {
      $json = json_decode(stripslashes($key), true);
      if (!$json || !isset($json['colName'], $json['displayName'])) continue;
      $colClass = preg_replace('/\s+/', '', strtolower($json['colName']));
      // Custom field: has a fieldId that differs from its colName (system fields have fieldId === colName)
      $isCustomField = !empty($json['fieldId']) && strcasecmp($json['fieldId'], $json['colName']) !== 0;
      $entry = [
          'colClass' => $colClass,
          'displayName' => $json['displayName'],
          'colName' => $json['colName'],
          'fieldId' => $json['fieldId'] ?? null,
          'controlTypeId' => isset($json['controlTypeId']) ? (int)$json['controlTypeId'] : null,
          'isCustomField' => $isCustomField
      ];
      $gridCols[] = $entry;
  }
    echo json_encode($gridCols);
?>;
  
  // Get selected card layout template from settings
  var cardLayoutTemplate = '<?php 
    $options = get_option('ebt_api_settings');
    echo isset($options['organization_settings']['grid']['card_layout']) ? $options['organization_settings']['grid']['card_layout'] : 'classic'; 
  ?>';

  // Cards per row for classic layout (2, 3, or 4)
  var orgClassicCardsPerRow = <?php
    $options = get_option('ebt_api_settings');
    $cpr = isset($options['organization_settings']['grid']['classic_cards_per_row']) ? intval($options['organization_settings']['grid']['classic_cards_per_row']) : 4;
    echo in_array($cpr, [2, 3, 4]) ? $cpr : 4;
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

</script>

<?php
// Include card layout templates dynamically based on settings
$options = get_option('ebt_api_settings');
$selectedLayout = isset($options['organization_settings']['grid']['card_layout']) ? $options['organization_settings']['grid']['card_layout'] : 'classic';

// Include the selected template file
$templatePath = plugin_dir_path(__FILE__) . '../templates/card-layouts/' . $selectedLayout . '.php';
if (file_exists($templatePath)) {
    include $templatePath;
} else {
    // Fallback to classic if template not found
    include plugin_dir_path(__FILE__) . '../templates/card-layouts/classic.php';
}
?>

<script>
function renderOrgGrid(data) {
  //console.log('Rendering Organization Grid with data:', data);
    var container = $('.grid-view .row');
    container.empty();
    if (data.length === 0) {
        container.append('<h3 class="text-secondary text-center col-12">No organizations found!</h3>');
        return;
    }
    
    // Render based on selected template
    switch(cardLayoutTemplate) {
        case 'modern':
            renderModernLayout(data, container);
            break;
        case 'minimal':
            renderMinimalLayout(data, container);
            break;
        case 'detailed':
            renderDetailedLayout(data, container);
            break;
        case 'classic':
        default:
            renderClassicLayout(data, container);
            break;
    }
    
    setTimeout(function() {
        // General popovers use hover
        $('[data-toggle="popover"]:not(.org-location-popover)').popover({ trigger: 'hover', html: true });
        // Location popovers use click so the user can interact (copy addresses)
        $('.org-location-popover').popover({ trigger: 'click', html: true, placement: 'top', sanitize: false });
        // Close location popover when clicking outside
        $(document).off('click.orgLocPop').on('click.orgLocPop', function(e) {
            if (!$(e.target).closest('.org-location-popover, .popover').length) {
                $('.org-location-popover').popover('hide');
            }
        });
        // Delegated copy handler — works on dynamically injected popover content
        $(document).off('click.orgCopy').on('click.orgCopy', '.org-copy-address-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var address = $(this).data('address');
            var $icon = $(this).find('i');
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(address).then(function() {
                    $icon.removeClass('fa-copy').addClass('fa-check text-success');
                    setTimeout(function() { $icon.removeClass('fa-check text-success').addClass('fa-copy'); }, 1500);
                });
            } else {
                var ta = document.createElement('textarea');
                ta.value = address;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.focus();
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                $icon.removeClass('fa-copy').addClass('fa-check text-success');
                setTimeout(function() { $icon.removeClass('fa-check text-success').addClass('fa-copy'); }, 1500);
            }
        });
    }, 100);
}

function copyOrgAddress(btn, text) {
    // Legacy fallback — main handler is now delegated via $(document).on('click.orgCopy')
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text);
    }
}

// Returns a blurred "Login to view" placeholder for guest-restricted fields
function applyGuestMask(value, colClass) {
    if (isUserLoggedIn || orgGuestHiddenFields.indexOf(colClass) === -1) return value;
    var maskStyle = 'filter:blur(3.5px);user-select:none;letter-spacing:1px;';
    var lockIcon  = '<i class="fas fa-lock" style="font-size:0.8em;opacity:0.6;"></i> ';
    // Choose a type-appropriate placeholder pattern
    var placeholder = (colClass === 'phonenumbers') ? '(•••)\u00a0•••-••••'
                    : (colClass === 'primaryemail')  ? '••••@•••••.•••'
                    : '• • • • • • •';
    return '<a href="#" data-toggle="modal" data-target="#loginModal" title="Login to view" style="text-decoration:none;color:inherit;">'
         + lockIcon + '<span style="' + maskStyle + '">' + placeholder + '</span></a>';
}

// Helper function to build field values
function buildFieldValues(org) {
    var fieldValues = {
        name: org.name || '--',
        primaryemail: applyGuestMask(
            org.primaryEmail
                ? '<a href="mailto:' + org.primaryEmail + '">' + org.primaryEmail + '</a>'
                : (org.secondaryEmails && org.secondaryEmails.length > 0
                    ? '<a href="mailto:' + org.secondaryEmails[0].value + '">' + org.secondaryEmails[0].value + '</a>'
                    : '--'),
            'primaryemail'
        ),
        phonenumbers: applyGuestMask(
            (org.phoneNumbers && org.phoneNumbers.length > 0 && org.phoneNumbers[0].value)
                ? formatPhoneUS(org.phoneNumbers[0].value)
                : '--',
            'phonenumbers'
        ),
        website: applyGuestMask(
            org.website
                ? '<a href="' + (org.website.indexOf('http') === 0 ? org.website : 'https://' + org.website) + '" target="_blank" rel="noopener noreferrer">' + org.website + '</a>'
                : '--',
            'website'
        ),
        organizationtype: applyGuestMask(org.organizationType || '--', 'organizationtype'),
        status: applyGuestMask(
            (org.status === 'Active')
                ? '<span style="color: #28a745; font-weight: 600;">' + org.status + '</span>'
                : (org.status || '--'),
            'status'
        ),
        locations: applyGuestMask(
            (org.locations && org.locations.length > 0)
                ? '<a tabindex="0" class="btn-link p-0 org-location-popover" data-toggle="popover" data-html="true" data-content="' +
                    buildLocationPopoverHtml(org.locations).replace(/"/g, '&quot;') +
                    '">View Locations</a>'
                : '--',
            'locations'
        ),
        totalmembers: applyGuestMask(
            (org.totalMembers !== undefined && org.activeMembers !== undefined)
                ? org.activeMembers + '/' + org.totalMembers
                : '--',
            'totalmembers'
        ),
        organizationtags: applyGuestMask(
            (org.organizationTags && org.organizationTags.length > 0)
                ? buildPopoverHtml('tags', org.organizationTags)
                : '--',
            'organizationtags'
        ),
        createdon: applyGuestMask(
            org.createdOn ? new Date(org.createdOn).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
            'createdon'
        ),
        modifiedon: applyGuestMask(
            org.modifiedOn ? new Date(org.modifiedOn).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '--',
            'modifiedon'
        ),
    };

    // Dynamic custom fields — resolved from admin-saved column map (no hardcoded fieldIds)
    var cfList = (org.customFields && Array.isArray(org.customFields)) ? org.customFields : [];
    cfList.forEach(function(cf) {
        if (!cf.fieldId) return;
        var meta = orgCfFieldIdMap[cf.fieldId.toLowerCase()];
        if (!meta) return;
        var colClass = meta.colClass;
        var val = cf.fieldValue || '';
        if (!val) {
            fieldValues[colClass] = '--';
            return;
        }
        var rendered;
        if (meta.controlTypeId === 11) {
            rendered = formatPhoneUS(val);
        } else if (meta.controlTypeId === 1) {
            var d = new Date(val);
            rendered = isNaN(d.getTime()) ? val : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        } else if (isValidUrl(val)) {
            if (/\.(png|jpg|jpeg|gif|webp|svg)(\?.*)?$/i.test(val)) {
                rendered = '<img src="' + val + '" alt="' + meta.colName + '" style="max-width:70px;max-height:45px;object-fit:contain;" />';
            } else {
                rendered = '<a href="' + (val.indexOf('http') === 0 ? val : 'https://' + val) + '" target="_blank" rel="noopener noreferrer">' + val + '</a>';
            }
        } else if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            rendered = '<a href="mailto:' + val + '">' + val + '</a>';
        } else if (/^\d{10}$/.test(val.replace(/\D/g, '')) && val.replace(/\D/g, '').length === 10) {
            rendered = formatPhoneUS(val);
        } else {
            rendered = val;
        }
        fieldValues[colClass] = applyGuestMask(rendered, colClass);
    });

    return fieldValues;
}

// Helper function to get field label
function getFieldLabel(displayName) {
    var labelMap = {
        'Locations': 'Location',
        'Created On': 'Added',
        'Modified On': 'Last Updated',
        'Primary Email': 'Email',
        'Phone Numbers': 'Phone',
        'Total Members': 'Total/Active Members'
    };
    return labelMap[displayName] || displayName;
}

function formatPhoneUS(phone) {
    phone = phone.replace(/\D/g, '');
    if (phone.length === 10) {
        return '(' + phone.substr(0,3) + ') ' + phone.substr(3,3) + '-' + phone.substr(6,4);
    }
    return phone;
}

// Build a lookup map from custom fieldId (lowercase) to column metadata from organizationGridCols
var orgCfFieldIdMap = {};
organizationGridCols.forEach(function(col) {
    if (col.isCustomField && col.fieldId) {
        orgCfFieldIdMap[col.fieldId.toLowerCase()] = col;
    }
});

function isValidUrl(url) {
    if (!url) return false;
    try { 
        new URL(url); 
        return true; 
    } catch (_) { 
        return false; 
    }
}

function buildLocationPopoverHtml(locations) {
    if (!Array.isArray(locations) || locations.length === 0) return '--';
    var html = '<div style="min-width:240px"><h6 class="text-center mb-2">Locations</h6><ul class="list-unstyled mb-0">';
    locations.forEach(function(loc, idx) {
        var addressParts = [
            loc.address || '',
            loc.addressLine2 || '',
            loc.city || '',
            loc.state || '',
            loc.zipCode || '',
            loc.country || ''
        ].filter(function(p) { return p.trim() !== ''; });
        var addressText = addressParts.join(', ');
        // Encode address for safe storage in data attribute (no quotes needed)
        var escapedAttr = addressText.replace(/&/g, '&amp;').replace(/"/g, '&quot;');
        html += '<li class="mb-2 px-2 py-1' + (idx % 2 === 0 ? ' bg-light' : '') + '">';
        html += '<div class="d-flex justify-content-between align-items-start">';
        html += '<b>' + (loc.fieldName || 'Address') + '</b>';
        html += '<button type="button" class="org-copy-address-btn btn btn-sm p-0 ml-2 text-secondary" ';
        html += 'data-address="' + escapedAttr + '" title="Copy address" style="line-height:1;font-size:13px;background:none;border:none;cursor:pointer;">';
        html += '<i class="far fa-copy"></i></button>';
        html += '</div>';
        html += '<div class="small mt-1">' + (addressText || '&mdash;') + '</div>';
        html += '</li>';
    });
    html += '</ul></div>';
    return html;
}

function buildPopoverHtml(type, items) {
    if (!Array.isArray(items) || items.length === 0) return '--';
    
    var title = type === 'tags' ? 'Tags' : 'Items';
    var html = '<div style="min-width:200px"><h6 class="text-center mb-2">' + title + '</h6><ul class="list-unstyled mb-0">';
    
    items.forEach(function(item, idx) {
        var displayValue = '';
        if (typeof item === 'string') {
            displayValue = item;
        } else if (item.name) {
            displayValue = item.name;
        } else if (item.tagName) {
            displayValue = item.tagName;
        } else if (item.value) {
            displayValue = item.value;
        }
        
        if (displayValue) {
            html += '<li class="' + (idx % 2 === 0 ? 'bg-light' : '') + ' px-2 py-1">' + displayValue + '</li>';
        }
    });
    
    html += '</ul></div>';
    
    return '<a tabindex="0" class="btn-link p-0" data-toggle="popover" data-html="true" data-content="' + 
           html.replace(/"/g, '&quot;') + '">View ' + title + '</a>';
}

<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Organization');
  <?php
}

  ?>
  
// Load dynamic filters after page loads
window.addEventListener("load", function () {
    // Initially show filter content and hide the main loader
    $('#filter-loader').hide();
    $('.filter-content').show();
    
    loadDynamicFilters();
});

function loadDynamicFilters() {
     // Show loading inside the dynamic filters container
    $('#dynamic-filters-container').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading filters...</span></div><p class="mt-2 text-muted">Loading filters...</p></div>');
    
    $.ajax({
        type: "post",
        url: engagifiiUrl_ajaxurl,
        data: {
            action: 'getOrganizationFilterConfiguration'
        },
        success: function(response) {
                     
            if (response.success && response.data) {
                // Check if the API returned an error
                if (response.data.isError === true) {
                    console.error('API Error:', response.data.message);
                    console.error('Error Details:', response.data.detail);
                    $('#dynamic-filters-container').html('<p class="text-center text-danger py-3">API Error: ' + (response.data.message || 'Failed to load filters') + '</p>');
                    return;
                }
                
                // Check if data is an array or if it's an object with nested data
                var filtersData = response.data;
                
                // If data is an object, try to find the array of filters
                if (!Array.isArray(filtersData)) {
                    
                    // Try common property names for filter arrays
                    if (filtersData.filters && Array.isArray(filtersData.filters)) {
                        filtersData = filtersData.filters;
                    } else if (filtersData.data && Array.isArray(filtersData.data)) {
                        filtersData = filtersData.data;
                    } else if (filtersData.result && Array.isArray(filtersData.result)) {
                        filtersData = filtersData.result;
                    } else if (filtersData.items && Array.isArray(filtersData.items)) {
                        filtersData = filtersData.items;
                    } else {
                        // If it's still not an array, convert the object values to array
                        filtersData = Object.values(filtersData);
                    }
                }
                
                
                if (!filtersData || filtersData.length === 0) {
                    $('#dynamic-filters-container').html('<p class="text-center text-muted py-3">No filters configured</p>');
                    return;
                }
                
                dynamicFiltersConfig = filtersData;
                renderDynamicFilters(dynamicFiltersConfig);
            } else {
                console.error('API returned error or no data:', response);
                $('#dynamic-filters-container').html('<p class="text-center text-danger py-3">Failed to load filters</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading filter configuration:', error);
            console.error('XHR:', xhr);
            console.error('Status:', status);
            
            $('#dynamic-filters-container').html('<p class="text-center text-danger py-3">Error loading filters: ' + error + '</p>');
        }
    });
}

function renderDynamicFilters(filters) {
   
    var container = $('#dynamic-filters-container');
    container.empty();
    
    if (!filters || filters.length === 0) {
        container.html('<p class="text-center text-muted py-3">No filters available</p>');
        return;
    }
    
    filters.forEach(function(filter) {
        var filterId = filter.fieldName || 'filter_' + filter.order;
        var filterHtml = '';
        
        // Create filter container
        filterHtml += '<div class="filter-list border-bottom" data-filter-id="' + filterId + '" data-filter-type="' + filter.filterType + '">';
        filterHtml += '<div class="heading-title py-2 d-flex align-items-center justify-content-between dynamic-filter-title">';
        filterHtml += filter.displayName + ' <i class="far fa-angle-down"></i>';
        filterHtml += '</div>';
        filterHtml += '<div class="content-area dynamic-filter-content d-none" data-field-name="' + filterId + '">';
        
        // Check filter type
        if (filter.filterType === 1) {
            // Numeric filter - need to fetch data from serviceUrl if provided
            if (filter.serviceUrl && filter.serviceUrl !== '') {
                filterHtml += '<div class="loaders text-center py-3"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>';
            } else {
                filterHtml += '<p class="text-muted text-center py-2">Numeric filter (serviceUrl not provided)</p>';
            }
        } else if (filter.filterType === 4) {
            // Dropdown/Checkbox filter
            if (filter.properties && Array.isArray(filter.properties) && filter.properties.length > 0) {
                // Add search box
                filterHtml += '<div class="p-2 border-bottom">';
                filterHtml += '<input type="text" class="form-control form-control-sm filter-search-box" placeholder="Type to search..." />';
                filterHtml += '</div>';
                // Render checkboxes from properties
                filterHtml += '<ul class="list-group m-0 filter-items-list">';
                filter.properties.forEach(function(item) {
                    var value = item.id || item.name || '';
                    var display = item.name || item.id || '';
                    if (value && display) {
                        filterHtml += '<li class="list-group-item border-0 py-1 px-2" data-filter-value="' + display.toString().toLowerCase() + '">';
                        filterHtml += '<label class="m-0">';
                        filterHtml += '<input class="mr-2" type="checkbox" value="' + value + '">';
                        filterHtml += display;
                        filterHtml += '</label></li>';
                    }
                });
                filterHtml += '</ul>';
            } else if (filter.serviceUrl && filter.serviceUrl !== '') {
                // Need to fetch data from serviceUrl
                filterHtml += '<div class="loaders text-center py-3"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>';
            } else {
                filterHtml += '<p class="text-muted text-center py-2">No options available</p>';
            }
        } else {
            filterHtml += '<p class="text-muted text-center py-2">Unknown filter type</p>';
        }
        
        filterHtml += '</div></div>';
        container.append(filterHtml);
    });
    
    // Initialize dynamic filter click handlers
    initializeDynamicFilterHandlers();
    
    // Initialize search boxes for pre-rendered filters
    initializeFilterSearchBoxes();
}

function initializeFilterSearchBoxes() {
    // Add search functionality to all filter search boxes
    $(document).on('keyup', '.filter-search-box', function() {
        var searchValue = $(this).val().toLowerCase();
        var $listItems = $(this).closest('.dynamic-filter-content, .content-area').find('.filter-items-list li');
        
        $listItems.each(function() {
            var itemValue = $(this).data('filter-value') || '';
            if (itemValue.indexOf(searchValue) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
}

function initializeDynamicFilterHandlers() {
    // Handle filter title clicks
    $(document).on('click', '.dynamic-filter-title', function() {
        var $filterList = $(this).closest('.filter-list');
        var $contentArea = $filterList.find('.dynamic-filter-content');
        var filterType = $filterList.data('filter-type');
        var filterId = $filterList.data('filter-id');
        
        // Toggle content area
        $contentArea.toggleClass('d-none');
        
        // If content area is being shown and has a loader, fetch data
        if (!$contentArea.hasClass('d-none') && $contentArea.find('.loaders').length > 0) {
            loadFilterData($filterList);
        }
    });
    
    // Handle checkbox changes
    $(document).on('change', '.dynamic-filter-content input[type="checkbox"]', function() {
        var $filterList = $(this).closest('.filter-list');
        var filterId = $filterList.data('filter-id');
        
        // Update selections
        if (!dynamicFilterSelections[filterId]) {
            dynamicFilterSelections[filterId] = [];
        }
        
        var value = $(this).val();
        if ($(this).is(':checked')) {
            if (!dynamicFilterSelections[filterId].includes(value)) {
                dynamicFilterSelections[filterId].push(value);
            }
        } else {
            dynamicFilterSelections[filterId] = dynamicFilterSelections[filterId].filter(function(v) {
                return v !== value;
            });
        }
        
        // Trigger filter count update
        if ($('#apply-filter-data .spinner-border').length == 0) {
            $('#apply-filter-data').attr('disabled', '').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
        }
        countFilterData();
    });
}

function loadFilterData($filterList) {
    var filterId = $filterList.data('filter-id');
    var filterType = $filterList.data('filter-type');
    var $contentArea = $filterList.find('.dynamic-filter-content');
    
    // Find the filter config
    var filterConfig = dynamicFiltersConfig.find(function(f) {
        return (f.fieldName || 'filter_' + f.order) === filterId;
    });
    
    if (!filterConfig || !filterConfig.serviceUrl) {
        $contentArea.find('.loaders').remove();
        $contentArea.append('<p class="text-muted text-center py-2">No service URL available</p>');
        return;
    }
    
    $.ajax({
        type: 'POST',
        url: engagifiiUrl_ajaxurl,
        data: {
            action: 'getFilterItemsFromServiceUrl',
            serviceUrl: filterConfig.serviceUrl
        },
        success: function(response) {
            $contentArea.find('.loaders').remove();
            
            if (response.success && response.data) {
                var data = response.data;
                
                // Check if data is empty (e.g., [{id: "", name: "", ...}])
                var hasValidData = false;
                if (Array.isArray(data) && data.length > 0) {
                    hasValidData = data.some(function(item) {
                        var hasId = item.id && item.id !== "";
                        var hasName = item.name && item.name !== "";
                        var hasValue = item.value && item.value !== "";
                        var hasDisplayName = item.displayName && item.displayName !== "";
                        return hasId || hasName || hasValue || hasDisplayName;
                    });
                }
                
                if (!hasValidData) {
                    $contentArea.append('<p class="text-muted text-center py-2">No data found</p>');
                    return;
                }
                
                var htmlContent = '';
                
                // Add search box at the top
                htmlContent += '<div class="p-2 border-bottom">';
                htmlContent += '<input type="text" class="form-control form-control-sm filter-search-box" placeholder="Type to search..." />';
                htmlContent += '</div>';
                
                if (filterType === 1) {
                    // Numeric filter - render as range inputs or list
                    if (Array.isArray(data) && data.length > 0) {
                        htmlContent += '<ul class="list-group m-0 filter-items-list">';
                        data.forEach(function(item) {
                            var value = item.id || item.value || item.name || item.tagName || item || '';
                            var display = item.name || item.tagName || item.displayName || value || '';
                            if (value && display && value !== '' && display !== '') {
                                htmlContent += '<li class="list-group-item border-0 py-1 px-2" data-filter-value="' + display.toString().toLowerCase() + '">';
                                htmlContent += '<label class="m-0">';
                                htmlContent += '<input class="mr-2" type="checkbox" value="' + value + '">';
                                htmlContent += display;
                                htmlContent += '</label></li>';
                            }
                        });
                        htmlContent += '</ul>';
                    } else {
                        htmlContent += '<p class="text-muted text-center py-2">No numeric values available</p>';
                    }
                } else if (filterType === 4) {
                    // Checkbox filter
                    if (Array.isArray(data) && data.length > 0) {
                        htmlContent += '<ul class="list-group m-0 filter-items-list">';
                        data.forEach(function(item) {
                            var value = item.id || item.value || item.name || '';
                            var display = item.name || item.displayName || item.value || value || '';
                            if (value && display && value !== '' && display !== '') {
                                htmlContent += '<li class="list-group-item border-0 py-1 px-2" data-filter-value="' + display.toString().toLowerCase() + '">';
                                htmlContent += '<label class="m-0">';
                                htmlContent += '<input class="mr-2" type="checkbox" value="' + value + '">';
                                htmlContent += display;
                                htmlContent += '</label></li>';
                            }
                        });
                        htmlContent += '</ul>';
                    } else {
                        htmlContent += '<p class="text-muted text-center py-2">No options available</p>';
                    }
                }
                
                $contentArea.append(htmlContent);
                // Note: Search functionality is handled globally by initializeFilterSearchBoxes()
            } else {
                $contentArea.append('<p class="text-muted text-center py-2">No data available</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading filter items:', error);
            $contentArea.find('.loaders').remove();
            $contentArea.append('<p class="text-danger text-center py-2">Error loading data</p>');
        }
    });
}

// The old filter loading code has been removed and replaced with dynamic filters

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
    // Collect values from old filters (backward compatibility)
    organizationTypes = getCheckedValues('.organizationType-filter');
    statuses = getCheckedValues('.status-filter');
    locations = getCheckedValues('.locations-filter');
    
    // Collect tags from UI and merge with shortcode tags (always keep shortcode tags)
    var uiTags = getCheckedValues('.organizationTags-filter');
    organizationTags = initialOrganizationTags.slice(); // Start with shortcode tags
    uiTags.forEach(function(tag) {
        if (!organizationTags.includes(tag)) {
            organizationTags.push(tag); // Add UI-selected tags if not already present
        }
    });
    
    updateCustomFieldsFromDOM();
    
    // Also collect dynamic filter selections
    
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
    
    // Update filter count badge
    updateFilterCount();
});

// Clear all filters functionality
$('#clear-all').click(function() {
    // Clear old filters
    organizationTypes = [];
    statuses = [];
    locations = [];
    organizationTags = initialOrganizationTags.slice(); // Reset to initial tags from shortcode
    customFieldSelections = {};
    resetCustomFields();
    
    // Clear dynamic filters
    dynamicFilterSelections = {};
    
    // Clear all checkboxes
    clearAllCheckboxes('.filter-list');
    clearAllCheckboxes('.custom-field-filter');
    clearAllCheckboxes('.dynamic-filter-content');
    
    // Clear date inputs
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
    $('#countFilterResult').text('');
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

// Debounce function to prevent excessive API calls
var countFilterDataTimeout = null;
var currentCountRequest = null;

function countFilterData() {
    // Cancel any pending timeout
    if (countFilterDataTimeout) {
        clearTimeout(countFilterDataTimeout);
    }
    
    // Cancel any pending request
    if (currentCountRequest && currentCountRequest.readyState !== 4) {
        currentCountRequest.abort();
    }
    
    // Set new timeout to delay the API call
    countFilterDataTimeout = setTimeout(function() {
        var organizationTypes = getCheckedValues('.organizationType-filter');
        var statuses = getCheckedValues('.status-filter');
        var locations = getCheckedValues('.locations-filter');
        var organizationTags = getCheckedValues('.organizationTags-filter');
        updateCustomFieldsFromDOM();
        
        // Merge dynamic filter selections into customFields
        var allCustomFields = Object.assign({}, customFields);
        Object.keys(dynamicFilterSelections).forEach(function(key) {
            if (dynamicFilterSelections[key] && dynamicFilterSelections[key].length > 0) {
                allCustomFields[key] = dynamicFilterSelections[key];
            }
        });

        currentCountRequest = $.ajax({
            type: "post",
            url: engagifiiUrl_ajaxurl,
            data: {
                action: 'organizationCountFilterData',
                organizationTypes: organizationTypes,
                statuses: statuses,
                locations: locations,
                organizationTags: organizationTags,
                customFields: allCustomFields
            },
            success: function(response) {
                var element = document.getElementById("countFilterResult");
                $('#apply-filter-data .spinner-border').remove();
                $('#apply-filter-data').removeAttr('disabled');
                if (element) {
                    element.innerHTML = " (" + response.api_response + ")";
                }
            },
            error: function(xhr, status, error) {
                if (status !== 'abort') {
                    console.error('Error counting filtered data:', error);
                }
                $('#apply-filter-data .spinner-border').remove();
                $('#apply-filter-data').removeAttr('disabled');
            }
        });
    }, 500); // Wait 500ms after last change before making API call
}

// Function to count all selected filters
function updateFilterCount() {
    var filterCount = 0;
    
    // Count old filter categories with selections (exclude dynamic filters to avoid double counting)
    $('.filter-list').each(function() {
        // Skip if this is a dynamic filter (has data-filter-id)
        if (!$(this).data('filter-id')) {
            if ($(this).find('input[type=checkbox]:checked').length > 0) {
                filterCount++;
            }
        }
    });
    
    // Count dynamic filter categories with selections
    if (dynamicFilterSelections && typeof dynamicFilterSelections === 'object') {
        Object.keys(dynamicFilterSelections).forEach(function(key) {
            if (dynamicFilterSelections[key] && dynamicFilterSelections[key].length > 0) {
                filterCount++;
            }
        });
    }
    
    // Update the filter icon badge
    if (filterCount > 0) {
        $('.filter-icon').addClass('active');
        $('.filter-icon span').text(filterCount).show();
    } else {
        $('.filter-icon').removeClass('active');
        $('.filter-icon span').hide();
    }
    
    return filterCount;
}

// Trigger countFilterData on filter changes
$(document).on('change', '.filter-list input[type=checkbox]', function() {   
    if ($('#apply-filter-data .spinner-border').length == 0) {
        $('#apply-filter-data').attr('disabled', '').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
    }
    countFilterData();
});

// Trigger on dynamic filter changes
$(document).on('change', '.dynamic-filter-content input[type=checkbox]', function() {
    if ($('#apply-filter-data .spinner-border').length == 0) {
        $('#apply-filter-data').attr('disabled', '').prepend('<span role="status" aria-hidden="true" class="spinner-border spinner-border-sm mr-1"></span>');
    }
    countFilterData();
});

// Use delegated event handler for filter icon click
$(document).on('click', '.click-filter', function(e) {
    e.preventDefault();
    e.stopPropagation();
     
    var $filterArea = $('.filter-area');
    var $filterBorder = $('.filter-border');
        
    // Toggle using Bootstrap class and show parent container
    if ($filterArea.hasClass('d-none')) {
        $filterArea.removeClass('d-none');
        $filterBorder.show(); // Show the parent container
       
    } else {
        $filterArea.addClass('d-none');
        $filterBorder.hide(); // Hide the parent container
       
    }   
   
});

// Close filter when clicking outside
$(document).on('click', function(event) {
    var $filterArea = $('.filter-area');
    var $filterBorder = $('.filter-border');
    var $filterIcon = $('.filter-icon');
    if (!$filterArea.is(event.target) && !$filterArea.has(event.target).length &&
        !$filterIcon.is(event.target) && !$filterIcon.has(event.target).length) {
        if (!$filterArea.hasClass('d-none')) {
            $filterArea.addClass('d-none');
            $filterBorder.hide();
        }
    }
});

</script>



