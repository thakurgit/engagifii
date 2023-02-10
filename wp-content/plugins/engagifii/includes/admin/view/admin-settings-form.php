<form class="ebt-setting-frm" action='options.php' method='post'>
    <?php

        $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
        settings_fields( 'engagifiiPlugin' );
        do_settings_sections( 'engagifiiPlugin' );
        
        //@do_settings_sections( 'engagifiiPluginTwo');
		?>
    <?php
        $options = get_option( 'ebt_api_settings' );
       // print_r($options);
        
        if(isset($options['engagifii_apply_css_ebt'])){
            $engagifii_apply_css_ebt = $options['engagifii_apply_css_ebt'];
            }else{
           		$engagifii_apply_css_ebt = 0;
           	}
        
            $checkedHtml  = ' style="display:none"';
           // if($engagifii_apply_css_ebt==1 && $tab === null)
			if( $tab === null)
            {
            	$checkedHtml  = ' style="display:block"';
            }
            include( plugin_dir_path( __FILE__ ) . '/admin-fonts-sie.php');
            include( plugin_dir_path( __FILE__ ) . '/admin-fonts.php');
            $options = get_option( 'ebt_api_settings' );


        ?>
    <div class="wrap tab-content ff">
    <div class="engagifi_style_group engagifii-setting m-tlr-20" <?php echo $checkedHtml ?>>
        <table class="engtcustomtbl" cellspacing="0" cellpadding="15" width="100%">
        <tr>
        	<td colspan="4"><h3>Table Header</h3><hr></td>
        </tr>
            <tr>
                
                <td><h4>Table Heading Background</h4>
                <?php
                       $ebt_table_bg = @$options['ebt_table_bg_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_bg_color]" value="'.$ebt_table_bg.'" class="engagifii-color-picker hide-options-here-tz">';
                         echo $_inputHtml;
                          ?>
                </td>
                <td>
                 <?php
                    if ( isset( $options['ebt_table_thead_fontsize'])){
                            $current = $options['ebt_table_thead_fontsize'];
                    	}
                        else{
                            $current = '';
                        }
                    ?>
                	<h4>Table Header Font Size</h4>	
                     <select name="ebt_api_settings[ebt_table_thead_fontsize]" id="font-size-tz">
                        <option> </option>
                        <?php foreach( $fontssie as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td colspan="2">
                	<h4>Table Heading Color</h4>
                    <?php
                        $ebt_table_thead_color = $options['ebt_table_thead_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_thead_color]" value="'.$ebt_table_thead_color.'" class="engagifii-color-picker hide-options-here-tz">';
                          echo $_inputHtml;
                    	 ?>
                </td>
                
                <?php /*?><th>Font Family</th>
                <td>
                    <select name="ebt_api_settings[engagifii_font_family]" id="font-family-tz">
                        <option> </option>
                        <?php foreach( $fonts as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td><?php */?>
            </tr>
              <tr>
        	<td colspan="4"><h3>Table Body</h3><hr></td>
        </tr>
            <tr>
            	<td>
                	<h4>Table Body color</h4>
                    <?php
                        $ebt_table_tbody_color = $options['ebt_table_tbody_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_tbody_color]" value="'.$ebt_table_tbody_color.'" class="engagifii-color-picker">';
                          echo $_inputHtml;
                    	 ?>
                </td>
                <td>
                 <?php
                    if ( isset( $options['ebt_table_tbody_fontsize'])){
                            $current = $options['ebt_table_tbody_fontsize'];
                    	}
                        else{
                            $current = '';
                        }
                    ?>
                	<h4>Table Body Font size</h4>
                     <select name="ebt_api_settings[ebt_table_tbody_fontsize]" id="table-size-tz">
                        <option> </option>
                        <?php foreach( $fontssie as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                	<h4>Hyperlink Color</h4>
                    <?php
                        $ebt_table_link_color = $options['ebt_table_link_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_link_color]" value="'.$ebt_table_link_color.'" class="engagifii-color-picker">';
                          echo $_inputHtml;
                    	 ?>
                </td>
                <td>
                	<h4>Hyperlink Hover Color</h4>
                    <?php
                       $ebt_table_link_hover_color = $options['ebt_table_link_hover_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_link_hover_color]" value="'.$ebt_table_link_hover_color.'" class="engagifii-color-picker">';
                       echo $_inputHtml;?>
                </td>
            </tr>
             <tr>
        	<td colspan="4"><h3>Calendar</h3><hr></td>
        </tr>
        <tr>
        	<td><h4>Calendar Active Date Background</h4>
             <?php
                        $ebt_detail_calendar_hover_color = $options['ebt_detail_calendar_hover_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_hover_color]" value="'.$ebt_detail_calendar_hover_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
              </td>
              <td>
              	<h4>Calendar Class Name Background</h4>
                 <?php
                        $ebt_detail_calendar_strip_color = $options['ebt_detail_calendar_strip_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_strip_color]" value="'.$ebt_detail_calendar_strip_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
              </td>
              <td colspan="2">
              	<h4>Calendar Class Name Background - Hover</h4>
                 <?php
                        $ebt_detail_calendar_strip_hover_color = $options['ebt_detail_calendar_strip_hover_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_strip_hover_color]" value="'.$ebt_detail_calendar_strip_hover_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
              </td>
        </tr>
        

        </table>
        
       
    </div>
    </div>
        
        
<?php
       do_action('engagifiiGetColumnList');
	   do_action('engagifiiCustomizer');
        ?>
    <div class="ebt-submit-btn">
        <?php 
            if($tab!= 'shortcode')
                submit_button("Save Settings");
        ?>
    </div>
</form> 
<script type="text/javascript">
    function getTenantCode(engagifiiUrl)
    {    		
    
    var url = new URL(engagifiiUrl);
    var index = url.hostname.indexOf('.');
    subdomain= url.hostname.substring(0, index);
    if (typeof subdomain == 'undefined' )
    {
    return false;
    }
    jQuery("#ebt_tenantcode_preview").html(subdomain);
    jQuery("#ebt_tenant_code_text").val(subdomain);

   
    }
    function getTenantCode_lbt(engagifiiUrl)
    {    		
    
    var url = new URL(engagifiiUrl);
    var index = url.hostname.indexOf('.');
    subdomain= url.hostname.substring(0, index);
    if (typeof subdomain == 'undefined' )
    {
    return false;
    }
   

    jQuery("#lbt_tenantcode_preview").html(subdomain);
    jQuery("#lbt_tenant_code_text").val(subdomain);




    }
    function getTenantCode_evt(engagifiiUrl)
    {    		
    
    var url = new URL(engagifiiUrl);
    var index = url.hostname.indexOf('.');
    subdomain= url.hostname.substring(0, index);
    if (typeof subdomain == 'undefined' )
    {
    return false;
    }
   
    jQuery("#evt_tenantcode_preview").html(subdomain);
    jQuery("#evt_tenant_code_text").val(subdomain);


    }
    
	
	jQuery('.accordion-btn').click(function(){
		jQuery(this).toggleClass('active').next('.accordion-content').slideToggle();
		
		
	
});
jQuery( '.shortcode-list code' ).click( function( event ) {
			var range = document.createRange();
			range.selectNodeContents( this );
			window.getSelection().addRange( range );
		} );
		
		var tid =0;
		jQuery('.ebt-grid-column-list').each(function() {
            jQuery(this).prepend('<li class="toggleAll"><input type="checkbox" id="toggleAll_'+tid+'"/><label for="toggleAll_'+tid+'"><b><u>Select/Deselect all</u></b></label></li>');
			jQuery('#toggleAll_'+tid).change(function(){
				if(jQuery(this).is(':checked')){
					jQuery(this).parent().siblings('li').find('input[type="checkbox"]').prop('checked',true);
				} else {
					jQuery(this).parent().siblings('li').find('input[type="checkbox"]').prop('checked',false);
				}
			});
          tid++;  
        }); 
	jQuery('input[readonly], input[readonly]+label').click(function(){
		showAlert();
return false;
	});
	function showAlert(){
		var alertHtml ='<div class="showalert">This item can not be modified.</div>';	
		jQuery(alertHtml).appendTo('body');
		setTimeout(function() {
   			 jQuery('.showalert').fadeOut('fast').remove();
			 
		}, 1500); 
	}
	
	var lbt_col_order=[];
  jQuery( function() {
    jQuery( "#legislationList" ).sortable({
		items : ':not(.toggleAll)',
		 update: function( event, ui ) {
			 dropped();
			 }
		});
	function dropped(){
		lbt_col_order=[];
		jQuery( "#legislationList li:not(.toggleAll)" ).each(function(){
			jQuery(this).attr('data-current-order',jQuery(this).index());
			lbt_col_order.push(jQuery(this).attr('data-order'));
			
		});
		jQuery('.cls').val(lbt_col_order);;
	}
  } );
</script>