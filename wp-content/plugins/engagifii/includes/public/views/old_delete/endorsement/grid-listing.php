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
  <div class="filter-icon"><i class="fas fa-filter click-filter"></i></div>
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
        <div class="content-area d-none">
          <input type="text" name="createdbetween" readonly="readonly" class="form-control input-xs small-css" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
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
              echo '<li class=""><input type="checkbox" name="emdorsementTag[]" value="'.$value->id.'"> '.$value->name.'</li>';
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
?>



<div class="container-fluid engagifii-box engagifii-main-cotainer">
  <table  id="ebtmaintable" class="table table-bordered light-background main-list-here nowrap" style="width: 100% !important;">
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


var table = $('#ebtmaintable').DataTable( {
    
        
       "pageLength": 10,
       "dom": '<"row"<"col-sm-11"f"><"top-filter col-sm-1 text-right">><"row"<"col-sm-12 custom-scroll"t">><"row"<"col-sm-5 pt-2"l><"col-sm-7 "p">>',
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
          "emptyTable": 'No records found',
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
        "columns":<?php echo (json_encode($forDatatable)); ?>
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

  });

    //filter
    $('#apply-filter-data').click(function(){
      <?php
       if(array_key_exists('tags', $ebt_visib_datacol_list))
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
          if(array_key_exists('tags', $ebt_visib_datacol_list))
          {
        ?>
            $('input[type=checkbox]').prop('checked',false);
        <?php
          }
        ?>
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            tags = '';
            createdDate = '';
            table.draw();

      })


     
      $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0){
        container.hide();
        $('.filter-area').addClass('d-none');
      }
      });

 
</script>      
</div>