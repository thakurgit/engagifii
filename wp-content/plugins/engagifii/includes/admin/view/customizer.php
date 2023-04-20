<div class="wrap eng-customizer <?php if($tab == 'customizer'){ echo 'show';}else {echo 'hide'; }?>" >
<div class="engagifii-setting m-tlr-20">
        <div>
        <?php
		 $options = get_option( 'ebt_api_settings' );
		 if(isset($options['dt_responsive'])){
    $dt_responsive = $options['dt_responsive'];
   }else{
   	$dt_responsive = 0;
   }

    $chkd = '';
    if($dt_responsive==1)
    {
    	 $chkd  = ' checked';
    }
	
	 ?>
          <span>Enable table responsive</span>
          <span><input type="checkbox" name="ebt_api_settings[dt_responsive]" id="dt-responsive" value="1" <?php echo $chkd; ?>></span>
        </div>
         <div>
        <?php
		
		 if(isset($options['dt_darktheme'])){
    $dt_darktheme = $options['dt_darktheme'];
   }else{
   	$dt_darktheme = 0;
   }

    $dark = '';
    if($dt_darktheme==1)
    {
    	 $dark  = ' checked';
    }
	 ?>
          <span>Enable Dark theme datatable</span>
          <span><input type="checkbox" name="ebt_api_settings[dt_darktheme]" id="dt_darktheme" value="1" <?php echo $dark; ?>></span>
        </div>

</div>
</div>