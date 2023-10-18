<?php
    $obj      =  new Engagifii_API();
    $publicOfficial = $obj->publicOfficial();
	$siteURL= site_url();
    $title_key = -1;
    
$seqColumns=['Name','Counties','City of Residence','District','Committees','Party','Role'];

function tabDataArray($publicOfficial, $tabName){
$tabDataArray= $publicOfficial[$tabName];
$tabData=array();
foreach ($tabDataArray as $key => $value) {
	$nestedData = array();
	$nestedData['name']='<div class="d-flex"><div class="overflow-hidden rounded-circle mr-2" style="height:40px; width:40px"><img src="'.$value['profilePic'].'" alt="" class="img-fluid"></div><div><a href="'.$siteURL.'/public-official-detail/?id='.$value['id'].'">'.$value['legalName'].'<br>('.$value['officialNameLabel'].')</a></div></div>';
	//counties
	$countiesList = $value['counties'];
$allCounties = array();
if($countiesList){
  if(count($countiesList)>1){
	$countyPopover = dd_header('Associated Counties','Search counties..');
	$subItems = "";
	$li=1;
	foreach ($countiesList as $index => $county) {
	  $class='';
	  if($li%2==1){
		$class='bg-light';	
	  }
	  $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">' . $county .  '</li>';
	  $li++;
	}
	$countyPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
	$allCounties[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$countiesList{0}.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$index.'" data-placement="left" data-containerid="' . $index . '" id="' . $index . '"> +' .(count($countiesList)-1) .'</span>'.$countyPopover.'</div>';
  }else if(count($countiesList)==1){
	$allCounties[] = $county;
  }
  $nestedData['counties']= implode(" ", $allCounties);
}else {
  $nestedData['counties']='<em class="opacity-50">N/A</em>';
}
	$nestedData['cityofresidence']=$value['residence'];
	$nestedData['district']=$value['districtCode'];
	//committies
	$committeesList=$value['committees'];
$allCommittees = array();
if($committeesList){
  if(count($committeesList)>1){
	$committeesPopover = dd_header('Associated Committies','Search committies..');
	$subItems = "";
	$li=1;
	foreach ($committeesList as $index => $committee) {
	  $class='';
	  if($li%2==1){
		$class='bg-light';	
	  }
	  $subItems .= ' <li class="px-2 py-1 border-bottom  small '.$class.'">' . $committee .  '</li>';
	  $li++;
	}
	$committeesPopover .= $subItems.'<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
	$allCommittees[] = '<div class="dropdown pr-4 text-left"><span class="d-inline-block pr-2">'.$committeesList{0}.'</span><span data-toggle="dropdown" style="right:0; top:0; bottom:0" class="position-absolute m-auto badge badge-sm bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle tag_'.$index.'" data-placement="left" data-containerid="' . $index . '" id="' . $index . '"> +' .(count($committeesList)-1) .'</span>'.$committeesPopover.'</div>';
  }else if(count($committeesList)==1){
	$allCommittees[] = $committee;
  }
  $nestedData['committees']= implode(" ", $allCommittees);
}else {
  $nestedData['committees']='<em class="opacity-50">N/A</em>';
}
	$nestedData['party']=$value['party'];
	$nestedData['role']=$value['legislativeRole'];
 $tabData[] = $nestedData;
}
return $tabData;
//print_r( $tabData);
}


//$tabDataArray= $publicOfficial['stateSenateMemberList'];
?>
<style type="text/css">
  
 .session-tab button {
	border-bottom:3px solid transparent !important;
	color:#333 !important;
 }
  .session-tab button.active{
	border-bottom-color:#002474  !important;
 }
 mark, .mark {
	background-color: #F9D276;
}
</style>
 <ul class="nav nav-pills mb-3 justify-content-center session-tab border-bottom" id="pills-tab" role="tablist">
      <?php $i=1; 
	  	foreach ($publicOfficial as $key => $value) {
			if($key=='stateSenateMemberList'){
				$name='State Senate';
			}
			elseif($key=='stateHouseMemberList'){
				$name='State House';
			}
			elseif($key=='stateSenateCommittees'){
				$name='State Senate Committees';
			}
			elseif($key=='stateHouseCommittees'){
				$name='State House Committees';
			}
			elseif($key=='countyDeligationList'){
				$name='County Delegations';
			}
			elseif($key=='congressionalDelegationMemberList'){
				$name='Congressional Delegations';
			}else{
				$name=$Key;
			}
			$class=''; 
			if($i==1){
				$class =' active';
			}
    echo '<li class="nav-item mr-4" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0 px-0'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$name.' <b>('.count($value).')</b></button></li>';
 $i++; }
	  ?>   		
      </ul>
       <div class="tab-content" id="nav-tabContent">
      <?php $k=1; 
	  	foreach ($publicOfficial as $key => $value) {
			if($key=='relatedOfficials' || $key=='myOfficials' || $key=='stateBoardOfEducationMemberList' || $key=='stateWideElectedMemberList'){
				continue;	
			}
			$class=''; 
			if($k==1){
				$class =' show active';
			}
			$list = $value;
			?>
<div class="tab-pane fade <?php echo $class; ?>" id="tab-<?php echo $k; ?>" role="tabpanel">
	<table id="ebtmaintable"  data-tab="<?php echo $key;?>"  class="tabData table table-bordered border-0 table-striped" style="width: 100% !important;">
      <thead> 
        <tr> 
        	<?php
              $i = 0;
			  $forDatatable   =   array();
              foreach ($seqColumns as $key => $value) {
                  if($value == 'Name'){
                    $title_key = $i;
                  }
                  $forDatatable[]['data'] = str_replace(' ', '', strtolower($value));
                ?>
                  <th class="<?php echo str_replace(' ', '', strtolower($value)); ?>Col"><?php  echo $value; ?></th>
                <?php
                $i++;
              }
          ?>        
        </tr> 
      </thead>
      
    </table>
</div>
      <?php $k++; } ?>
       </div>
<div class="container-fluid mb-4">
 
 
</div>
	
       <script>
  var titleColumn = '<?php echo $title_key; ?>';
  
  $('table').each(function(){
  var tabname='';
	 	tabname = $(this).attr('data-tab'); 
  });
 var table = new DataTable('table.tabData', {
			  "pageLength": '10',
			  "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
			  "dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
			  "bInfo":false,
			  "processing": true,
			  "searching": true,
			  "ordering":true,
			  "search": {regex: true},
			  <?php if(in_array('Name', $seqColumns)){ ?>
			  "order": [[<?php echo array_search('Name',$seqColumns);?>, 'asc']],
			   <?php } ?>
			  "columnDefs": [ 
				{ "targets": ['countiesCol','committeesCol'],
				  "orderable": false
				},
				{ width: 200, targets: <?php echo array_search('Name',$seqColumns);?> },
				{ className: "text-center", "targets": ["startdate","instructors"] },
				{ responsivePriority: 1, targets: 'sectionname' },
				<?php //if(in_array('sessions', $class_visible_column_list)){ ?>
				/*{'targets': <?php //echo array_search('sessions',$class_visible_column_list);?>, 'createdCell':  function (td, cellData, rowData, row, col) {
					var html = $(cellData);
					var editor = $("<p>").append(html);
					var cell = editor.find("span:first-child").html();
				 $(td).attr('data-order', cell ); 
				   }
			   },*/
			   <?php //} ?>
			  ],
			  "language": {
				processing: '<span>&nbsp;</span>',
				"emptyTable": '-'
			  },
			  "oLanguage": {
				  "sLengthMenu": "Show _MENU_ records per page"
			  },
			  "data": <?php echo json_encode(tabDataArray($publicOfficial,'stateSenateMemberList'));  ?>,
			  createdRow: function (row, data, index) { 
				   //$(row).addClass( 'bg-white' );
			  },  
			  "columns":<?php echo (json_encode($forDatatable)); ?>,
			   "drawCallback": function( settings ) {
				   
				   dt_dropdown();
				   <?php //if($dt_respnsive==''){ ?>
				 dt_scroll();
					 <?php //} ?>
					 $('[data-toggle="tooltip"]').tooltip() ;
			   },
			   
				"initComplete": function(settings, json) {
					$('#eng-overlay').css( 'display', 'none' );
		  },
		  });
<?php
  if($title_key > -1){
?>
  $('#ebtmaintable thead tr th:eq('+titleColumn+')').each( function (i) { 
         var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" id="searchclass" placeholder="Search Public official.." class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:3;display:none"><i class="fal fa-times"></i></button></div>' );

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
  $( 'input', this ).keyup(delay(function (e) {
	  var titlesearch = this.value;
            if ( table.column(titleColumn).search() !== titlesearch ) {
				table.column(titleColumn).search(titlesearch).draw();
            }
}, 500));


 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$('.clear-search').show();
			} else {
				$('.clear-search').hide();
			} 
 });
$('th .clear-search').click(function(e){
	 $('#searchclass').val('');
	$('.clear-search').hide();
	e.stopPropagation();
	table.column(titleColumn).search('').draw();
 });

    } );
	
	$(document).ready(function (){    
    $('#searchclass, .search-dt span').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchclass').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});
});
  <?php
}
  ?>


	  

  
	  </script>