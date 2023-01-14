<?php
$obj =  new Engagifii_API();
$sessionResponse = $obj->sessions();
$sessionResponses = json_decode($sessionResponse['api_response']);
?>
<style type="text/css">
  button#billSearch{ padding: 0 15px; }
  button#billSearch:focus{ outline: none;  }
  .form-control:focus{ box-shadow: unset; border-color: unset; border: 1px solid #EAEDF2 !important }
</style>
<div class="m-2">
      <div class="row">
      
   <!--    <div class="col-lg-5">
        <select class="form-control sm-select text-break bill-search-height" id="bill_type">
          <?php
              foreach ($sessionResponses as $key => $value) {
                $bill_string = explode(" ", $value->value);
                $bill_id = substr($value->value, 0, 1).substr($bill_string[1], 0,1);
          ?>
                <option value="<?php echo $bill_id; ?>"><?php echo $value->value; ?></option>
          <?php
              }

          ?>
          </select>
      </div> -->
      <div class="col-lg-12">
        <div class="input-group bill-search-height">
          
        <input type="text" class="form-control bill-search-height" value="" id="bill_number" placeholder="Eg: HB 0002 or SB 0980">
        <div class="input-group-append">
          <button type="button" id="billSearch" class="input-group-text"><label  for="inputGroupSelect02">Go</label></button>
        </div>
        </div>

      </div>
      </div>
    
  </div>

  <script type="text/javascript">
    $("#bill_number").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#billSearch").click();
    }
    });
    $('#billSearch').click(function(){
      var bill = $('#bill_number').val();
      $("body").removeClass('loaded');
      var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?bill='+bill;
      window.location.href = redirect_url;
    })
  </script>