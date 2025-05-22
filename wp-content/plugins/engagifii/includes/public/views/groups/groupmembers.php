<?php 

	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
    $colNames = ['Full Name', 'Email', 'Position', 'Organization'];
	
    //print_r($filterParams);
    $columnSearch_key = [];
  
//print_r($fiscalYear); 



// print_r($fiscalEndDate);
?>
<style>
	th.peoplename, th.email {
    min-width: 150px;
}	
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

	<div class="engagifii-box engagifii-main-cotainer position-relative px-xl-5">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page nowrap " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	 <?php foreach ($colNames as $key): ?>
            <th class="<?php echo preg_replace('/\s+/', '', strtolower($key)); ?>"><?php echo $key; ?></th>
        <?php endforeach; ?>
 		    

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>


</div>

<script type="text/javascript">
var table = $('#ebtmaintable').DataTable({
    "pageLength": 10,
    "processing": true,
    "serverSide": true,
    "searching": true,
    "ordering": true,
    "ajax": {
        "url": engagifiiUrl_ajaxurl,
        "type": "POST",
        "data": function(d) {
            d.action = 'peopleloadGridDataByGroups';
        },
        "dataSrc": function(json) {
            return json.api_response.map(function(item) {
                const p = item.people;
                return {
                    fullname: p.fullName || '',
                    email: p.email || '',
                    position: p.position || '',
                    organization: p.organization || ''
                };
            });
        }
    },
    "columns": <?php echo json_encode($forDatatable); ?>,
    "language": {
        search: "",
        searchPlaceholder: "Search People"
    }
});
</script>