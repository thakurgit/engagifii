<?php
$obj =  new Engagifii_API();
$sessionResponse = $obj->sessions();
$sessionResponses = json_decode($sessionResponse['api_response']);

?>
<style type="text/css">
  
 .session-tab button {
	border-bottom:3px solid transparent !important;
	color:#333 !important;
 }
  .session-tab button.active{
	border-bottom-color:#002474  !important;
 }
</style>
<?php if(count($sessionResponses )>1) { ?>
     <ul class="nav nav-pills mb-3 justify-content-center session-tab border-bottom" id="pills-tab" role="tablist">


      
   
          <?php //print_r($sessionResponses); 
		  $i=0;
		  rsort($sessionResponses);
             foreach ($sessionResponses  as $key => $value) {
				$session_id = $value->sessionId;
				$session_name = $value->sessionName;
				
         		?>
                 <li class="nav-item mx-2" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0  <?php if($i==0){ echo ''; } ?> " sessionname = "<?php echo $session_name;?>" id="<?php echo $session_id;?>" data-toggle="pill" data-target="#session-<?php echo $session_id;?>" type="button" role="tab" aria-controls="home" aria-selected="true"><?php echo $session_name;  ?></button>
  </li>

                <?php
				$i++;
              }

          ?>
         
     
      </ul>
   <?php } ?> 

 