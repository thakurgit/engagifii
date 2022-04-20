<?php
  $obj      =  new Engagifii_API();
  $collection   = array();
    $forDatatable   =   array();
    $date           =   date('Y-m-d');
    $options  = get_option( 'ebt_api_settings' );
    $class_visible_column_list = $options['class_visible_column_list'];
    
    $dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
    $collection   = json_decode($dataResponse['api_response']);
    unset($collection[0]);
    unset($collection[1]);
    unset($collection[7]);
    unset($collection[8]);
    

    $classes = $obj->getAllClassCourses($date);
    $creditFilter    = $obj->getCreditHoursFilter($date);
    $instructor = $obj->classAllInstructors($date);
    $dateRange  = $obj->classdateFilters($date);
    //print_r($dateRange);
    $min_date   = date('m/d/Y',strtotime($dateRange['minStartDate']));
    $max_date = date('m/d/Y',strtotime($dateRange['maxEndDate']));
?>

<div class="containerEngagii">
  <div class="container-fluid engagifii-box engagifii-main-cotainer">
    <table  id="ebtmaintable" class="table table-bordered light-background main-list-here nowrap" style="width: 100% !important;">
      <thead> 
        <tr>        
          <?php
            if(is_array($collection) && count($collection)>0){
              foreach ($collection as $key => $value) {
                 if(in_array($value->colName, $class_visible_column_list)){
                
                  if($value->displayName == 'Class Type')
                  {
                     $value->displayName = "Type";
                  }
                  if($value->colName == 'sessions')
                  {
                      $value->colName = 'startdate';
                  }
                    $forDatatable[]['data'] = $value->colName;
                ?>
                  <th class="<?php echo strtolower($value->displayName); ?> <?php echo $value->colName; ?>">
            <?php  echo $value->displayName; ?>
            </th>
                <?php
                }
              }
            }
          ?>    

        </tr> 
      </thead> 
    </table>
    <div id="eng-overlay"><span class="spinner"></span></div>
</div>

</div>
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
        <div class="heading-title">Course Name <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none">
          <ul class="list-group height-100">
          <?php foreach ($classes as $key => $value) { echo '<li class=""><input type="checkbox" name="courseClass[]" value="'.$value['name'].'"> '.$value['name'].'</li>';} ?>
          </ul>
        </div>
      </div>
      <div class="filter-list">
        <div class="heading-title"> Instructor <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none"><ul class="list-group height-100">
          <?php

            foreach ($instructor as $key => $value) {
              echo '<li class=""><input type="checkbox" name="courseInstrutor[]" value="'.$value['id'].'"> '.$value['name'].'</li>';
            }
          ?>  
        </ul></div>
      </div>
      <div class="filter-list">
        <div class="heading-title"> Created Between <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none">
          <input type="text" name="createdbetween" readonly="readonly" class="form-control input-xs small-css" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" >
        </div>
      </div>
      <!-- <div class="filter-list">
        <div class="heading-title"> Credit Hours <i class="fa fa-angle-down pull-right"></i></div>
        <div class="content-area d-none">
          <span id="ex18-label-2a" class="sr-only">low value</span>
          <span id="ex18-label-2b" class="sr-only">high value</span>
          <span class="p-2"><?php echo $creditFilter['minRange']; ?> </span><input id="ex18b" type="text"/> <span class="p-2"><?php echo $creditFilter['maxRange']; ?></span>
        </div>
      </div> -->
      
    </div>
    <div class="apply-filter">
        <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data">Apply 
          <span id="countFilterResult"></span>
        </button>
      </div>
  </div>
</div>
</div>
<?php
$filter_content =ob_get_contents();
ob_end_clean();
$filter_content = removeWhitespace($filter_content);
?>
<script type="text/javascript">
  var courses = '';
  var instructor = '';
 
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
          { "targets": ['objectType','classDuration', 'classTag', 'classInstructorsCount', 'register'],
            "orderable": false
          },
          { width: 200, targets: 3 }
        ],
        "language": {
          processing: '<span>&nbsp;</span>',
          "emptyTable": '-',
          search:'',
          searchPlaceholder: "Search classes..."
        },
        "oLanguage": {
            "sLengthMenu": "Show _MENU_ records per page"
        },
        "serverSide": true,
        "ajax": {
            "url": engagifiiUrl_ajaxurl,
            "type": "POST",
            "data": function(d) {  
              d.action='classes'; 
              d.courses = courses;
              d.instructors = instructor;  
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
    autoApply: true,
    locale: {
          cancelLabel: 'Clear'
      }
  });

 $( document ).ready(function() {
    $('input[name="createdbetween"]').val('');
});


$('.clear-all').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#isApplyACtive').val(0);
            $('input[name="createdbetween"]').val('');
            tags = '';
            courses = '';
            instructor = '';
            createdDate = '';
            table.draw();

      })

//filter
    $('#apply-filter-data').click(function(){
      courses = $.map($('input[name="courseClass[]"]:checked'), function(c){return c.value; });
      instructor = $.map($('input[name="courseInstrutor[]"]:checked'), function(c){return c.value; });
      createdDate = $('input[name="createdbetween"]').val();
      
      $(".filter-area").toggleClass('d-none');
      table.draw();

    });


    $(document).on('click', function (e) {
      var container = $(".filter-border");
      // If the target of the click isn't the container
      if(!container.is(e.target) && container.has(e.target).length === 0){
        container.hide();
        $('.filter-area').addClass('d-none');
      }
      });

    // $("#ex18b").slider({
    //     min: <?php echo (int)$creditFilter['minRange']; ?>,
    //     max: <?php echo (int)$creditFilter['maxRange']; ?>,
    //     value: [<?php echo (int)$creditFilter['minRange']; ?>, <?php echo (int)$creditFilter['maxRange']; ?>],
    //     labelledby: ['ex18-label-2a', 'ex18-label-2b']
    //   });

</script>