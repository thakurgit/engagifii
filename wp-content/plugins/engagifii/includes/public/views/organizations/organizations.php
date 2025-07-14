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
		    	 <?php  
           $labelOverrides = [
    'Created On' => 'Added',
    'Locations' => 'Location',
    'Organization Tags' => 'Tags',
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
// console.log('Organization Grid Columns:', organizationGridCols); 
var orgFieldIcons = {
    email: '<i class="fas fa-envelope mr-2"></i>',
    phone: '<i class="fas fa-phone mr-2"></i>',
    //status: '<i class="fas fa-user-check mr-2"></i>',
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
    'activemembers': (org.totalMembers !== undefined && org.activeMembers !== undefined)
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
            if (col === 'name') return;
            if (fieldValues[col] !== undefined) {
                cardBody += '<p class="card-text mb-1">' +
                    (orgFieldIcons[col] || '') +
                    '<span class="font-weight-bold">' + label + ':</span> ' +
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
</script>



