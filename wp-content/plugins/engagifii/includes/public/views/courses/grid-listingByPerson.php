<?php
	$obj 			=  new Engagifii_API();
	$collection 	=	array();
  $forDatatable 	= 	array();
  $date           =   date('Y-m-d');
	$options 	= get_option( 'ebt_api_settings' );
  

    $classes = $obj->courseAllClasses($date);
    $tags    = $obj->courseAllTags($date);
    $instructor = $obj->courseAllInstructors($date);
    $dateRange  = $obj->courseDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
    $title_key = -1;
?>
<?php 
$dt_class=' ';
$dt_respnsive = '';
$dt_respnsive = get_option( 'ebt_api_settings' )['dt_responsive'];
if($dt_respnsive==1){
$dt_class = 'dt-responsive nowrap ';	
}
$dt_darktheme = '';
$dt_darktheme = get_option( 'ebt_api_settings' )['dt_darktheme'];
if($dt_darktheme==1){
$dt_class .= 'table-dark ';	
}
?>
<div class="containerEngagii">
<div class="container-fluid pb-4">
	<div class="row">
    	<div class="col-12 text-center text-lg-right d-flex align-items-center justify-content-end flt-btn"></div>
    </div>
</div>
	<div class="container-fluid engagifii-box engagifii-main-cotainer position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?>">
  	<table  id="courseByPerson" class="table table-bordered border-0 table-striped main-list-here course-page <?php echo  $dt_class; ?>" style="width: 100% !important;">
    	<thead> 
		    <tr>        
		    	<?php
				$i=0;
				$colNames =['Course Name','Course Type','Completion Date','Credit Hours','Tags'];
		    			foreach ($colNames as $key) {
		    			$forDatatable[]['data'] = preg_replace('/\s+/', '', strtolower($key));
                  if($key == 'Course Name'){
                    $title_key = $i;
                  }
		    				?>
		    					<th class="<?php echo preg_replace('/\s+/', '', strtolower($key)); ?>"><?php echo $key ?></th>
		    				<?php
                  $i++;
				}
		    	//print_r($forDatatable);
				//die;			
		    	?>		

		    </tr> 
    	</thead> 
  	</table>
  	<div id="eng-overlay"><span class="spinner"></span></div>
</div>

</div>
<?php
/*function removeWhitespace($buffer)
{
    return preg_replace('/\s+/', ' ', $buffer);
}*/

ob_start();
?>
<?php
$filter_content =ob_get_contents();
ob_end_clean();
if(function_exists('removeWhitespace')){
$filter_content = removeWhitespace($filter_content);
}
?>
<script type="text/javascript">
	var classes = '';
	var instructor = '';
	var tags       = ''; 
	var createdDate = '';
  var endDate     = '';
  var fv= 0;
	var table = $('#courseByPerson').DataTable( {
       	"pageLength": 10,
       	"dom": '<"row no-gutters"<"col-sm-12 custom-scroll border-left border-right border-bottom"t">><"row"<"col-sm-5 pt-2"l><"col-sm-7 "p">>',
       	"bInfo":false,
       	"processing": true,
       	"searching": true,
       	"ordering":true,
      	"columnDefs": [ 
          { "targets": ['tags','coursetype','completiondate','credithours'],
            "orderable": false
          },
		  //{ className: "title-col", "targets": "name" },
		  //{ className: "text-center", "targets": ["creditHours","instructor","class"] },
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search courses..."
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
            	d.action='coursesByPerson'; 
            	//d.classes = classes;
            	//d.tags    = tags;
            	//d.instructors = instructor;  
            	//d.createdDate = createdDate;   
                 
            }, 
        },
        createdRow: function (row, data, index) { 
             //$(row).addClass( 'bg-white' );
        },        
        "columns":<?php echo (json_encode($forDatatable)); ?>,
		 "initComplete": function(settings, json) {
        
		 $('.dataTables_filter label').append('<button type="button" class="btn text-muted shadow-none bg-transparent position-absolute blank"><i class="fa fa-times"></button>');
		 $('.dataTables_filter input').keyup(function(){
			if($(this).val()==''){
				$(this).parent('label').removeClass('has-data');
			} else {
				$(this).parent('label').addClass('has-data');
			}
		 });
		 

    },
     "drawCallback": function( settings ) {
            $('.dataTables_wrapper ').append('<span class="nxt position-absolute bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"><i class="far fa-angle-right"></i></span>');
            $('.dataTables_wrapper ').prepend('<span class="prv position-absolute bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"><i class="far fa-angle-left"></i></span>');
            $('.prv').addClass('disabled');
              var divWidth = parseInt($('.custom-scroll').width());
               var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
               var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
               if(scrollwidth - divWidth - leftwidth == '24')
               {
                  $('.nxt').addClass('disabled');
               }
              
            $('.nxt').click(function () {
               $('.custom-scroll').animate({
                  scrollLeft: "+=200px"
               }, "slow"); 
               $('.prv').removeClass('disabled'); 
                 var divWidth = parseInt($('.custom-scroll').width());
               var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
               var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
               if(scrollwidth - divWidth - leftwidth == '24')
               {
                  $('.nxt').addClass('disabled');
               }
               else{
                $('.nxt').removeClass('disabled');
               }
               if($('.custom-scroll').scrollLeft()==0){
                  $('.prv').addClass('disabled');  
               }
            });  
            $('.prv').click(function () {
               $('.custom-scroll').animate({
                  scrollLeft: "-=200px"
               }, "slow");
                 var divWidth = parseInt($('.custom-scroll').width());
               var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
               var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
               if(scrollwidth - divWidth - leftwidth == '24')
               {
                  $('.nxt').addClass('disabled');
               }
               else{
                $('.nxt').removeClass('disabled');
               }
               if($('.custom-scroll').scrollLeft()==0){
                  $('.prv').addClass('disabled');  
               }
            });  
         }
    });
	$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});

  <?php
  if($title_key > -1){
?>

  $('#courseByPerson thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search courses" class="form-control form-control-sm search-endorsement" value=""/>' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );
  <?php
}
  ?>


	$('div.flt-btn').html('<?php echo $filter_content; ?>');
    $('#courseByPerson').on( 'processing.dt', function ( e, settings, processing ) {
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();

    
    $('.filter-icon').click(function(e){
        e.stopPropagation();
        $('.filter-border').show();
        $('.filter-area').toggleClass('d-none');
        $('#isApplyACtive').val(1);
    })

    $('.heading-title').click(function(){$(this).next('.content-area').toggleClass('d-none')});

   $('input[name="createdbetween"]').daterangepicker({
   minDate:'<?php echo $min_date; ?>',
    maxDate: '<?php echo $max_date; ?>',
    autoApply: true
  }, function(start, end) {
      createdDate = start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
      countFilterData();

    });


  	//filter
  	$('#apply-filter-data').click(function(){
  		classes = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
  		instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
  		tags       = $.map($('input[name="courseTags[]"]:checked'), function(c){return c.value; });
  		createdDate = $('input[name="createdbetween"]').val();
      
      $(".filter-area").toggleClass('d-none');
  		table.draw();

  	});

 $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});
$( '.cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
    countFilterData();
});

$('.clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#countFilterResult').html(' ');
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
	  
	  
	  
	  $("#apply-filter-data").click(function () {
 
  
  $('.filter-list').each(function() {
	 if ($(this).find('input[type=checkbox]').is(':checked')) {
		$(this).addClass('checked');
	 } else {
		$(this).removeClass('checked');
	 }
  });
  fv = $('.filter-list.checked').length;
  if(fv>0){
	$('.filter-icon').addClass('active bg-primary text-white').removeClass('bg-light');
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light'); 
  }
 });  

    $('.filter-list input[type=checkbox]').change(function(){
          countFilterData();
      })


    function countFilterData()
    {

      classes = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      tags       = $.map($('input[name="courseTags[]"]:checked'), function(c){return c.value; });


          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
            action:'coursecountdata',
            classes : classes,
            tags    : tags,
            instructors : instructor, 
            createdDate : createdDate,
          },
          success: function(response) {       
            var element  = document.getElementById("countFilterResult");
            console.log(response);
            if(element)
            {
              element.innerHTML = " ("+response.api_response +")";
            }    
          }
        });
      }

$(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});


</script>