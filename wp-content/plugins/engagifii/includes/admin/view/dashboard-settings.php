<div class="wrap dashoboard-settings <?php if($tab == 'dashboard-settings'){ echo 'show';}else {echo 'hide'; }?>" >
<div class="engagifii-setting m-tlr-20">
<?php 	$options = get_option( 'ebt_api_settings' );
	$tenant_url= $options['ebt_tenant_code']['engagifii_url'];
 if($tenant_url!='psba'){
echo '<h4>Profile settings is not allowed</h4>';
} else { 
?>
<h2>Profile settings is in progress..</h2>
<?php } ?>
</div>
</div>