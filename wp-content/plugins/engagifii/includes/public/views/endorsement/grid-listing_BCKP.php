<?php
    $obj =  new Engagifii_API();

    /* added static column list by vpsc */
    $dataResponse = $this->submitApiRequest("Public/EndorsementColumnList",array(),"GET",'endorsement');
    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[6]);
    unset($collection[8]);
    unset($collection[9]);
    array_values($collection);
    

    $options = get_option( 'ebt_api_settings' );
    $ebt_visib_datacol_list = $options['ebt_visib_datacol_list'];
   
    /* Get Tags list */
    $payloadData = array();
    $getCurrentdate = date("Y-m-d");
    $payloadData['selectedDate'] = $getCurrentdate;
    $payloadData['itemCount'] = 10;
    $payloadData['sortBy'] = 'createdon';
    $payloadData['sortDirection'] = 'desc';
    $payloadData['pageNumber'] = 1;
    $payloadData['filterBody'] = array('searchText' => '', 'selectedDate' => $getCurrentdate);

    $postedData = $payloadData;
    $date = date('Y-m-d');
    $dataResponse = $this->submitApiRequest("Public/Award/GetAllTags/".$date, $postedData, "GET", 'endorsement');
    $tags = json_decode($dataResponse['api_response']);
    
    $dateRange  = $obj->awardDateFilter($date);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));

?>

<div class="containerEngagii">
<!-- start filter UI -->

<?php
function removeWhitespace($buffer)
{
    return preg_replace('/\s+/', ' ', $buffer);
}

ob_start();
?>
<div class="filter-content">
	<div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative"><i class="fas fa-filter click-filter"></i><span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span></div> 
  <div class="filter-border">
  <div class="filter-area d-none">
    <div class="Engagiirow filter-top-bg col-sm-12">
      <div class="col-sm-6 text-left">
        <span class="filter-title">
          <i class="fas fa-filter"></i> Filter 
          <span id="blockedchecked"></span> 
        </span>
      </div>
      <div class="col-sm-6 text-right">
        <span class="clear-all" id="clear-all"> Clear All </span>
      </div>
    </div>
    <div class="col-sm-12 height-4">
      <input type="hidden" id="isApplyACtive" value="0">
      
      <div class="filter-list">
        <div class="heading-title"> Created Between <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none position-relative">
          <input type="text" name="createdbetween"  class="form-control input-xs small-css" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
          <span class="position-absolute cleardate mt-1 mr-1 text-secondary" style="right:0; top:0; cursor:pointer"><i class="fa fa-times"></i></span>
        </div>
      </div>
      <?php
      
        if(array_search('tags', $ebt_visib_datacol_list)){
      ?>
      <div class="filter-list">
        <div class="heading-title"> Tags <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none"><ul class="list-group height-100">
          <?php
            foreach ($tags as $key => $value) {
              echo '<li class=""><input type="checkbox" name="emdorsementTag[]" value="'.$value->id.'"> '.addslashes($value->name).'</li>';
            }
          ?>  
        </ul></div>
      </div>
      <?php
        }
      ?>
      <div class="apply-filter">
        <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data">Apply 
          <span id="countFilterResult"></span>
        </button>
      </div>
    </div>
  </div>
</div>
</div>
<?php
$filter_content =ob_get_contents();
ob_end_clean();
$filter_content = removeWhitespace($filter_content);
$title_key = -1;
?>



<div class="container-fluid engagifii-box engagifii-main-cotainer">
  <table  id="ebtmaintable" class="table table-bordered light-background main-list-here nowrap endorsement-page" style="width: 100% !important;">
    <thead> 
      <tr>                
        <?php 
        $forDatatable = array();
        $i=0;
        // foreach ($collection as $key => $value) {
        //   if($value->colName == "register")
        //   {
        //       $temp_array = $collection;
        //       array_splice($collection, $key, 1);
        //       array_values(array_filter($collection));
        //       array_push($collection, $temp_array);
        //   }
        // }

        foreach($collection as $key => $value){
          if(in_array($value->colName, $ebt_visib_datacol_list)){
              $forDatatable[$i]['data'] =$value->colName; 
              $forDatatable[$i]['name'] =$value->colName;
              if($value->displayName == 'Endorsement Type')
              {
                 $value->displayName = "Type";
              }
              if($value->colName == 'name'){
                $title_key = $i;
              }
          ?>    
            <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
          <?php $i++; 
          }
        } ?>
      </tr> 
    </thead> 
  </table>
  <div id="eng-overlay"><span class="spinner"></span></div>
</div>

<style>
 .ebtmaintable-tbl-container{
   position: relative;
 }


 
</style>

<script type="text/javascript">

  var tags       = '';
  var createdDate = '';
  var endDate     = '';

  var fv = 0;
var table = $('#ebtmaintable').DataTable( {
    
        
       "pageLength": 10,
       "dom": '<"row"<"col-md-11 col-10"><"top-filter col-md-1 col-2 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-2"l><"col-sm-7 "p">>',
       "bInfo":false,
       "processing": true,
       "searching": true,
       "ordering":true,
       "columnDefs": [ 
          { "targets": ['price','objectType','validity','courseCount','register','tags'],
            "orderable": false
          }
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search endorsements..."
         },
         "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": ajax_url_ebt,
            "type": "POST",
            "data": function(d) {           
              d.tags    = tags; 
              d.createdDate = createdDate;   
              
            }, 
        },
        createdRow: function (row, data, index) { 
             $(row).addClass( 'bg-white' );
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
            $('.dataTables_wrapper ').append('<span class="nxt"><i class="fa fa-angle-right"></i></span>');
            $('.dataTables_wrapper ').prepend('<span class="prv"><i class="fa fa-angle-left"></i></span>');
            $('.prv').addClass('disabled');
             var divWidth = parseInt($('.custom-scroll').width());
               var scrollwidth =  parseInt($('.custom-scroll').get(0).scrollWidth);
               var leftwidth = parseInt($('.custom-scroll').scrollLeft());
               
               if(scrollwidth - divWidth - leftwidth == '24')
               {
                  $('.nxt').addClass('disabled');
               }
            $('.nxt').click(function () {
              console.log("nxt");
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
               if($('.custom-scroll').scrollLeft()==0){
                  $('.prv').addClass('disabled');  
               }
            });  
            $('.prv').click(function () {
               $('.custom-scroll').animate({
                  scrollLeft: "-=200px"
               }, "slow");
               if($('.custom-scroll').scrollLeft()==0){
                  $('.prv').addClass('disabled');  
               }
            });  
         }
		
    });


<?php
  if($title_key > -1){
?>

  $('#ebtmaintable thead tr th:eq(<?php echo $title_key; ?>)').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search endorsement" class="form-control form-control-sm search-endorsement" value=""/>' );
 
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
	$('body').on('click', '.blank', function(){
			$('.dataTables_filter input[type=search]').val('').keyup(); 
			$(this).parent('label').removeClass('has-data');
			table.draw();
		});
    $('div.top-filter').html('<?php echo $filter_content; ?>');

 
    $('#ebtmaintable').on( 'processing.dt', function ( e, settings, processing ) {
        console.log(processing);
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } ).dataTable();

    setTimeout(function() {      
        $('.dataTables_empty').html('');
    },500);
  
    $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});

$( '.cleardate' ).click(function() {
    $('input[name="createdbetween"]').val('');
    createdDate = '';
    countFilterData();
});
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
      <?php
       if(in_array('tags', $ebt_visib_datacol_list))
       {
      ?>
          tags       = $.map($('input[name="emdorsementTag[]"]:checked'), function(c){return c.value; });
      <?php
        }
      ?>
      createdDate = $('input[name="createdbetween"]').val();
      
      $(".filter-area").toggleClass('d-none');
      table.draw();

    });


      $('.clear-all').click(function(){
         <?php
          if(in_array('tags', $ebt_visib_datacol_list))
          {
        ?>
            $('input[type=checkbox]').prop('checked',false);
        <?php
          }
        ?>
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            $('#countFilterResult').html(' ');
            fv = 0;
          $('.filter-icon').removeClass('active'); 
            tags = '';
            createdDate = '';
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

      $('.filter-list input[type=checkbox]').change(function(){
          countFilterData();
      })

      function countFilterData()
      {

         <?php
           if(in_array('tags', $ebt_visib_datacol_list))
           {
          ?>
              tags       = $.map($('input[name="emdorsementTag[]"]:checked'), function(c){return c.value; });
          <?php
            }
          ?>

          
          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
            action:'filtercountdata',
            tags    : tags,
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
	$('.filter-icon').addClass('active'); 
	$('.filter-icon span').text(fv); 
  } else {
	$('.filter-icon').removeClass('active');  
  }
 });  
 
 $(document).ready(function(){
	if($('html').height()<$(window).height()){
		$('#site-footer').css('marginTop',$(window).height()-$('html').height()+$('#site-footer').outerHeight()+15);	
	}
});
</script>      
</div>