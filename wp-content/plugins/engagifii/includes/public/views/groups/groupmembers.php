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
  $columns='';
    $columnNames=[];
    if (!empty(GROUP_MEMBERS_COLS) && isArrayOfJsonStrings(GROUP_MEMBERS_COLS)) {
          $columns = convertToObjectArray(GROUP_MEMBERS_COLS);
          $columnNames = extractColNames(GROUP_MEMBERS_COLS);
    }else{
        $options = get_option( 'ebt_api_settings' );
		$tenantCode = $options['dashboard_tenant_code'];
      $dataResponse = $this->submitApiRequest("PeopleColumnList/".$tenantCode,array(),"GET",'dashboard');
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
                    
                    <?php if(in_array('startDateTime', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Event Date <i class="far fa-angle-down"></i></div>
                        <div class="content-area d-none position-relative pb-2">
                            <input type="text" name="createdbetween" class="form-control form-control-sm input-xs small-css bg-light" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY">
                            <span style="right:0; top:0; cursor:pointer" class="position-absolute cleardate mt-1 mr-2"><i class="fal fa-times"></i></span>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('eventType', $columnNames) && array_search('eventType', $columnNames) && !empty(CLASS_TYPES_COLS) && isArrayOfJsonStrings(EVENTS_TYPES_COLS)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Event Types <i class="far fa-angle-down"></i></div>
                        <div class="content-area eventType-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(in_array('city', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between"> Location <i class="far fa-angle-down"></i></div>
                        <div class="content-area city-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if (in_array('currentDepartment', $columnNames) && array_search('currentDepartment', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Department <i class="far fa-angle-down"></i></div>
                        <div class="content-area currentDepartment-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if (in_array('currentPosition', $columnNames) && array_search('currentPosition', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Position <i class="far fa-angle-down"></i></div>
                        <div class="content-area currentPosition-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if (in_array('personType', $columnNames) && array_search('personType', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Person Type <i class="far fa-angle-down"></i></div>
                        <div class="content-area personType-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if (in_array('organization', $columnNames) && array_search('organization', $columnNames)) { ?>
                    <div class="filter-list border-bottom">
                        <div class="heading-title py-2 d-flex align-items-center justify-content-between regular-field-filter-tittle"> Organization <i class="far fa-angle-down"></i></div>
                        <div class="content-area organization-filter d-none">
                            <ul class="list-group m-0">
                                <div class="loaders text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                            </ul>
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
             <?php } ?>
        </div>
         
        <div class="col-12 mb-4"></div>
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
label{
    margin-bottom: 0px !important;
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
  var departments = [];
  var positions = [];
  var personTypes = [];
  var roles = [];
  var organizations = [];
  var customFields = {};
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
                d.action = 'peopleloadGridDataByGroups'; 
                d.groupId = groupId; 
                d.titleColumn = titleColumn; 
                d.departments = departments; 
                d.positions = positions;
                d.personTypes = personTypes;
                d.roles = roles;
                d.organizations = organizations;
                // Add custom fields filter
                d.customFields = customFields;
                console.log('DataTable AJAX customFields:', customFields);               
            }, 
        },
        createdRow: function (row, data, index) {          
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
         
     "drawCallback": function( settings ) {
      if ($('.dataTables_empty').length) {
        $('.dataTables_empty').html('<div class="dt-empty-message"><h2 class="text-muted">No member found at the moment. Please check back later or adjust your filters.</h2></div>');
        $('.dataTables_paginate').hide(); 
        $('.dataTables_length').hide();   
      }else{
        $('.dataTables_paginate').show(); 
        $('.dataTables_length').show();   
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
              start:start,
              departments:departments,
              positions:positions,
              personTypes:personTypes,
              roles:roles,
              organizations:organizations,
              customFields: customFields

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
    name:     '', 
};


    function getFieldLabel(field) {    
    return field
        .replace(/([a-z])([A-Z])/g, '$1 $2')
        .replace(/^./, function(str){ return str.toUpperCase(); });
}

function renderGroupGrid(data) {
    var container = $('.grid-view .row');
    container.empty(); 
    if (data.length === 0) {
        container.append('<h3 class="text-secondary text-center col-12">No members found!</h3>');
        return;
    }    
    data.forEach(function(item) {
        var person = item.people;
        var personPhoto = isValidUrl(person.imageThumbUrl)
            ? ' <img src="' + person.imageThumbUrl + '" class="card-img-top mb-3" alt="' + person.fullName + '">'
            : '<i class="fa fa-user-circle text-secondary mb-3 mx-auto img-default"></i>';

       
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


<?php
  if($title_key > -1){
?>
dt_titleSearch('Search Members');
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
            action: 'groupMemberFilters',
            filterParams: <?php echo json_encode($columnNames); ?>,
             groupId:groupId, 
        },
        success: function(response) { 
           // console.log(response);
            for (var key of Object.keys(JSON.parse(response))) {
                $('.' + key + '-filter ul').html(JSON.parse(response)[key]);
            }
            dt_filterActivate();
            var dates = JSON.parse(response)['startDateTime'];
            //filterEvents(dates['minStartDate'], dates['maxEndDate']); 
            $('#filter-loader').hide(); // Hide loader
            $('.filter-content').fadeIn(); // Show filter content
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
    departments = getCheckedValues('.currentDepartment-filter');
    positions = getCheckedValues('.currentPosition-filter');
    personTypes = getCheckedValues('.personType-filter');
    roles = getCheckedValues('.roles-filter');
    organizations = getCheckedValues('.organization-filter');
    updateCustomFieldsFromDOM();
    console.log('Selected Filters:', {
        departments: departments,
        positions: positions,
        personTypes: personTypes,
        roles: roles,
        organizations: organizations,
        customFields: customFields
    });
    if ($.fn.DataTable.isDataTable('#ebtmaintable')) {
        table.draw();
    }
    if (viewMode === 'grid') {
        start = 0;
        groupMembers(start);
    }
    $('.filter-area').addClass('d-none');
    $('#apply-filter-data .spinner-border').remove();
    $('#apply-filter-data').removeAttr('disabled');
});

// Clear all filters functionality
$('#clear-all').click(function() {
    departments = [];
    positions = [];
    personTypes = [];
    roles = [];
    organizations = [];
    customFieldSelections = {};
    clearAllCheckboxes('.filter-list');
    clearAllCheckboxes('.custom-field-filter');
    resetCustomFields();
    $('input[name="createdbetween"]').val('');
    if ($.fn.DataTable.isDataTable('#ebtmaintable')) {
        table.draw();
    }
    if (viewMode === 'grid') {
        start = 0;
        groupMembers(start);
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
    var groupId = '<?php echo $groupId; ?>';

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
            selectedDate: selectedDate,
            groupId: groupId
        },
        success: function(response) {
            console.log('Custom Field Filter Data:', response);
            if (response.success) {
                if (typeof response.data === 'object' && response.data !== null) {
                    var filterContent = '';
                    response.data.forEach(function(item) {
                        var isChecked = customFieldSelections[fieldId] && customFieldSelections[fieldId].includes(item.id);
                        filterContent += '<li class="d-flex align-items-start">'
                            + '<input type="checkbox" class="mr-2 mt-1" id="customfield_' + item.id + '" value="' + item.id + '"'
                            + (isChecked ? ' checked' : '') + '>'
                            + '<label for="customfield_' + item.id + '"><small>' + item.name + '</small></label>'
                            + '</li>';
                    });

                    var $filterArea = $('.custom-field-filter[data-field-id="' + fieldId + '"]');
                    $filterArea.removeClass('d-none'); // Make sure it's visible
                    $filterArea.find('.list-group').html(filterContent);
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
    var departments = getCheckedValues('.currentDepartment-filter');
    var positions = getCheckedValues('.currentPosition-filter');
    var personTypes = getCheckedValues('.personType-filter');
    var roles = getCheckedValues('.roles-filter');
    var organizations = getCheckedValues('.organization-filter');
    var groupId = '<?php echo $groupId; ?>';
    updateCustomFieldsFromDOM();

    $.ajax({
        type: "post",
        url: engagifiiUrl_ajaxurl,
        data: {
            action: 'groupCountFilterData',
            departments: departments,
            positions: positions,
            personTypes: personTypes,
            roles: roles,
            organizations: organizations,
            customFields: customFields,
            groupId: groupId
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
$(document).on('click', function(event) {
    var $filterArea = $('.filter-area');
    var $filterIcon = $('.filter-icon');
    if (!$filterArea.is(event.target) && !$filterArea.has(event.target).length &&
        !$filterIcon.is(event.target) && !$filterIcon.has(event.target).length) {
        $filterArea.addClass('d-none');
    }
});

</script>
