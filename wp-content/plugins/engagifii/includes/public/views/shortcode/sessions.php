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
     <ul class="nav nav-pills mb-3 justify-content-center session-tab" id="pills-tab" role="tablist">


      
   
          <?php //print_r($sessionResponses); 
		  $i=0;
		  rsort($sessionResponses);
             foreach ($sessionResponses  as $key => $value) {
				$session_id = $value->sessionId;
				$session_name = $value->sessionName;
				
         		?>
                 <li class="nav-item" role="presentation">
    <button class="nav-link <?php if($i==0){ echo ''; } ?> " id="<?php echo $session_id;?>" data-toggle="pill" data-target="#session-<?php echo $session_id;?>" type="button" role="tab" aria-controls="home" aria-selected="true"><?php echo $session_name;  ?></button>
  </li>

                <?php
				$i++;
              }

          ?>
         
     
      </ul>
    

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