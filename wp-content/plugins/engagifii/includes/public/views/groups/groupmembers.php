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
<div class="container-fluid mb-3">
    	<div class="d-flex align-items-center">
            	<h4 class="mb-0 mr-2">
                	<button type="button" title="Refresh Members" class="refresh btn shadow-none p-2 mr-1"> <i class="fas fa-sync"></i></button><?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/Member-Icon.png" class="img-fluid" alt="member-icon" style="max-width:40px" >'; ?></h4>
                <h5 class="mb-0">Members</h5>                
               
                  </div>
                </div>
        </div>
</div>
<!-- Group Tabs -->
<ul class="nav nav-pills nav-fill flex-nowrap group-tabs mb-5 overflow-auto overflow-x-auto w-100" id="groupTabs" role="tablist">
    <?php foreach($groups as $idx => $group): ?>
        <li class="nav-item mr-3">
            <a class="text-nowrap border border-primary nav-link<?php if($idx === 0) echo ' active'; ?>" id="<?php echo $group->id; ?>" data-toggle="tab" href="#group-<?php echo $idx; ?>" role="tab" aria-controls="group-<?php echo $idx; ?>" aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>">
                <?php echo htmlspecialchars($group->title); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

	<div class="engagifii-box engagifii-main-cotainer position-relative px-xl-5 w-100">
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


</div>

<script type="text/javascript">
var groupId = $('#groupTabs li:first-child a').attr('id');
$('#groupTabs a').click(function(){
	groupId = $(this).attr('id');
	$('#eng-overlay').show();
	table.draw();
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
          { "targets": ['email','position', 'organization'],
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
</script>



