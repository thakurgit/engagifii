<?php
    
    $obj =  new Engagifii_API();
    $lastResponse = $obj->lastAction();
    $lastResponses = json_decode($lastResponse['api_response']);
?>
<style type="text/css">
  .fixed-list{ overflow: auto;}
  .eq-height {
	max-height: 216px !important;  
  }
  @media (min-width:1200px) {
	  .eq-height {
	height: 216px !important;  
  }
}
</style>

 
  <div class="mb-4 mb-md-0">
  	<div class="row">
    
      <div class="col-sm-12">
      <select class="form-control eq-height" size="5">

   <?php foreach($lastResponses as $last){?>
        <option class="text-break pb-1" data-title="<?php echo $last->text;?>" data-id="<?php echo $last->value;?>" onclick="filterLastAction('<?php echo $last->value; ?>')" >
        
        <?php echo $last->text;?>
        </option>
        <?php }?>
</select>
    </div>
    

  </div>
  </div>

<script type="text/javascript">
    function filterLastAction(id) {
      $("body").removeClass('loaded');
      var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?actionType='+id;
      window.location.href = redirect_url;

    }

    $('option').mouseover(function(){
     $(this).addClass('bg-secondary');
});
$('option').mouseout(function(){
     $(this).removeClass('bg-secondary');
});
</script>