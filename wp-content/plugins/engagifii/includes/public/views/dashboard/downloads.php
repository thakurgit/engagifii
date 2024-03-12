<?php 
ini_set('session.gc_maxlifetime', 3600);
session_start();
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
    $obj      =  new Engagifii_API();
$pid = $_SESSION['pid'];
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
/*$userEmail = $user->user_email;
    $obj      =  new Engagifii_API();
    $engagifiiProfile = $obj->engagifiiProfile('psba',$userEmail);
	$peopleDATA = json_decode($engagifiiProfile['api_response']);*/
if(!$pid) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
} 
 include 'sidebar_nav.php'; 
$title_key = -1;
?>
<div class="container-fluid mb-3">
    	<div class="d-flex align-items-center">
            	<h4 class="mb-0 mr-3"><button type="button" title="Refresh Downloads" class="refresh btn shadow-none p-2 mr-2"> <i class="fas fa-sync"></i></button><?php echo '<img src="'.ENGAGIFII_ASSETS_URL.'/images/download_blue.png" class="img-icon-lg img-fluid" alt="download-icon" >'; ?></h4>
                <div class="mr-5">
                <h5 class="mb-0">My Downloads</h5>
                <small>Files will be automatically deleted in 7 days</small>
                </div>
        	
        	<button type="button" id="clearDownloads" class="btn btn-danger btn-sm"><i class="fas fa-trash mr-2"></i></i>Clear Downloads <span style="display:none" role="status" aria-hidden="true" class="spinner-border spinner-border-sm ml-2"></span></button>
        </div>
</div>
	<div class="container-fluid engagifii-box engagifii-main-cotainer position-relative">
  	<table  id="ebtmaintable" class="table table-bordered border-0 table-striped main-list-here course-page " style="width: 100% !important;">
    	<thead> 
		    <tr>    
		    	<?php
				$i=0;
				$colNames =['download-select','File Name','Requested','Status'];
		    			foreach ($colNames as $key) {
		    			$forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
                  if($key == 'File Name'){
                    $title_key = $i;
                  }
				  $i++;
				  if($key == 'download-select'){
					echo '<th class="'.$key.'"></th>'; 
					continue; 
				  }
				  ?>
					  <th class="<?php echo preg_replace('/\s+/', '', strtolower($key)); ?>"><?php echo $key ?></th>
				  <?php
                  
				}
		    	?>		

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>
<script type="text/javascript">
  var titleColumn = '<?php echo $title_key; ?>';
  var profileId = '5e7f3fed-c3f8-4b38-a25f-4f6a32511337';
  var val;
	var table = $('#ebtmaintable').DataTable( {
       	"pageLength": 10,
		"dom": '<"row no-gutters"<"col-12 custom-scroll border-left border-right border-bottom"t">><"row pagin"<"col-sm-5 pt-3"l><"col-sm-7 pt-3"p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
		"order": [[<?php echo array_search('Requested',$colNames);?>, 'desc']],
      	"columnDefs": [ 
          { "targets": ['download-select','requested','status'],
            "orderable": false
          },
		  //{ className: "title-col", "targets": "name" },
		  { width: 80, targets: 0 },
		  { className: "text-center", "targets": ["download-select","requested","status"] },
		   
        ],
		
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search courses"
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
            	d.action='downloadsByPerson';
				d.titleColumn = titleColumn;  
            }, 
        },
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 
     "drawCallback": function( settings ) {
            dt_dropdown();
          // dt_scroll();
			   $('[data-toggle="tooltip"]').tooltip() ; 
			   
         },
		  "initComplete": function(settings, json) {
			//dt_filterActivate();
			
			  $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', 'none' );
			  
		

    },
    });
	 $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        $('#ebtmaintable_wrapper').siblings('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();
	
	$('body').on('click', '#clearDownloads, .deleteReport', function(e) {
	var reportid='';
	if($(this).attr('report-id')) {
		reportid = $(this).attr('report-id');	
	}else{
		$('#clearDownloads').attr('disabled','').find('span').show();
	}
	   $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'clearDownloads',
			  reportid:reportid,
          },
          success: function(response) { 
		  	$('#clearDownloads').removeAttr('disabled').find('span').hide();
		  	if(response){
				table.draw();	
			}
		  }
        });
		e.stopPropagation();
		e.preventDefault();
});
	$('.refresh').click(function(){
		table.draw();
	});


<?php
  if($title_key > -1){
?>
  dt_titleSearch('Search downloads');
  <?php
}
  ?>


	//$('div.flt-btn-course').html('<?php //echo $filter_course; ?>');

    
   /* $('.flt-btn-course .filter-icon').click(function(e){
        e.stopPropagation();
        $(this).siblings('.filter-border').show();
       $(this).siblings('.filter-border').find('.filter-area').toggleClass('d-none');
        $('#isApplyACtive').val(1);
		jQuery(".filter-area .list-group").mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:"minimal-dark",
		 			scrollbarPosition:"outside"
		 			});
    });

   $('.flt-btn-course .heading-title').click(function(){
		$(this).next('.content-area').toggleClass('d-none');
		$(this).parent().siblings('.filter-list').find('.content-area').addClass('d-none');
	});*/



  	//filter
  	$('#apply-filter-data1').click(function(){
  		classes = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
  		instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
  		tags       = $.map($('input[name="courseTags[]"]:checked'), function(c){return c.value; });
  		createdDate = $('input[name="createdbetween"]').val();
      
      $(".flt-btn-course .filter-area").toggleClass('d-none');
  		table.draw();

  	});

 $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});
$( '.flt-btn-course .cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
});

$('.flt-btn-course .clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#coursecountFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light'); 
            tags = '';
            createdDate = '';
            instructor = '';
            classes = '';
            table.draw();

      })

     
      $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
        container.hide();
        $('.filter-area').addClass('d-none');
      }
      });
	  
	  
	  
	  $("#apply-filter-data1").click(function () {
 
  
  $('.flt-btn-course .filter-list').each(function() {
	 if ($(this).find('input[type=checkbox]').is(':checked')) {
		$(this).addClass('checked');
	 } else {
		$(this).removeClass('checked');
	 }
  });
  fv = $('.flt-btn-course .filter-list.checked').length;
  if(fv>0){
	$('.filter-icon').addClass('active bg-primary text-white').removeClass('bg-light');
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light'); 
  }
 });  

    



$(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});


</script> 