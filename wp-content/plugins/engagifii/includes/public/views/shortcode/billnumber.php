<?php
$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('legislation', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center">Legislation module is deactivated. Please contact the admin.</div>';
    return;
}
/*$obj =  new Engagifii_API();
$billResponse = $obj->billType();
$billResponses = json_decode($billResponse['api_response']);*/
?>
<style type="text/css">
  button#billSearch{ padding: 0 15px; }
  button#billSearch:focus{ outline: none;  }
  .form-control:focus{ box-shadow: unset; border-color: unset; border: 1px solid #EAEDF2 !important }
</style>
<div class="m-2">
      <div class="row">
      
     <?php /*?> <div class="col-lg-5">
        <select class="form-control sm-select text-break bill-search-height" id="bill_type">
          <?php
              foreach ($billResponses as $key => $value) {
                $bill_string = explode(" ", $value->value);
                $bill_id = substr($value->value, 0, 1).substr($bill_string[1], 0,1);
          ?>
                <option value="<?php echo $bill_id; ?>"><?php echo $value->value; ?></option>
          <?php
              }

          ?>
          </select>
      </div> <?php */?>
      <div class="col-lg-12">
        <div class="input-group bill-search-height">
          
        <input type="text" class="form-control bill-search-height" value="" id="bill_number" placeholder="Eg: HB 0002 or SB 0980">
        <div class="input-group-append">
          <button type="button" id="billSearch" class="input-group-text">Go</button>
        </div>
        </div>

      </div>
      </div>
    
  </div>
 
  <script type="text/javascript">
  /*var sessionId='';
  	$('.session-tab li button').click(function(){
		sessionId = $(this).attr('id');
		//$('#bill-count').siblings('a').attr('href',viewAll+'?sessionId='+sessionId);
	});*/

    $("#bill_number").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#billSearch").click();
    }
    });
	window.addEventListener("load", function () {
    $('#billSearch').click(function(){
      var bill = $('#bill_number').val();
      $("body").removeClass('loaded');
	  if(sessionId!=0){
     	 var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?bill='+bill+'&sessionId='+sessionId;
	  }else {
     	 var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?bill='+bill;
	  }
      window.location.href = redirect_url;
    });
    });
  </script>