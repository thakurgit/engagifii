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
			if( $tab === null || $tab === 'customizer')
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
                <?php
                	if ( isset( $options['engagifii_font_family'])){
                        $current = $options['engagifii_font_family'];
                	}
                    else{
                        $current = '';
                    }
                ?>
                <th> Table Heading Background</th>
                <td><?php
                       $ebt_table_bg = @$options['ebt_table_bg_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_bg_color]" value="'.$ebt_table_bg.'" class="engagifii-color-picker hide-options-here-tz">';
                         echo $_inputHtml;
                          ?> </td>
                <th>Font Family</th>
                <td>
                    <select name="ebt_api_settings[engagifii_font_family]" id="font-family-tz">
                        <option> </option>
                        <?php foreach( $fonts as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <?php
                    if ( isset( $options['ebt_table_thead_fontsize'])){
                            $current = $options['ebt_table_thead_fontsize'];
                    	}
                        else{
                            $current = '';
                        }
                    ?>
                <th>Table Hover Color </th>
                <td><?php 
                       $ebt_table_hover_color = $options['ebt_table_hover_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_hover_color]" value="'.$ebt_table_hover_color.'" class="engagifii-color-picker hide-options-here-tz">';
                         echo $_inputHtml;
                    ?></td>
                <th>Table Heading Font Size </th>
                <td>
                    <select name="ebt_api_settings[ebt_table_thead_fontsize]" id="font-size-tz">
                        <option> </option>
                        <?php foreach( $fontssie as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Sponsors Background </th>
                <td><?php
                       $ebt_sponsors_color = $options['ebt_sponsors_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_sponsors_color]" value="'.$ebt_sponsors_color.'" class="engagifii-color-picker">';
                       echo $_inputHtml;
                    ?></td>
                <th>Heading 1 Font Size </th>
                <td>
                    <?php
                    if ( isset( $options['ebt_detail_heading_font'])){
                            $current = $options['ebt_detail_heading_font'];
                        }
                        else{
                            $current = '';
                        }
                    ?>
                    <select name="ebt_api_settings[ebt_detail_heading_font]" id="detail-size-tz">
                        <option> </option>
                        <?php foreach( $fontssie as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Table Normal Text </th>
                <td><?php
                        $ebt_table_tbody_color = $options['ebt_table_tbody_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_tbody_color]" value="'.$ebt_table_tbody_color.'" class="engagifii-color-picker">';
                          echo $_inputHtml;
                    	 ?></td>
                <?php
                    if ( isset( $options['ebt_table_tbody_fontsize'])){
                            $current = $options['ebt_table_tbody_fontsize'];
                    	}
                        else{
                            $current = '';
                        }
                    ?>
                <th> Table Normal Font </th>
                <td>
                    <select name="ebt_api_settings[ebt_table_tbody_fontsize]" id="table-size-tz">
                        <option> </option>
                        <?php foreach( $fontssie as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Hyperlink Color </th>
                <td><?php
                        $ebt_table_link_color = $options['ebt_table_link_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_link_color]" value="'.$ebt_table_link_color.'" class="engagifii-color-picker">';
                          echo $_inputHtml;
                    	 ?></td>
                <th> Text Font Size</th>
                <td>
                    <?php
                    if ( isset( $options['ebt_detail_text_font'])){
                            $current = $options['ebt_detail_text_font'];
                        }
                        else{
                            $current = '';
                        }
                    ?>
                    <select name="ebt_api_settings[ebt_detail_text_font]" id="ebt-size-tz">
                        <option> </option>
                        <?php foreach( $fontssie as $key => $font ):?>
                        <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Table Heading Color </th>
                <td><?php
                        $ebt_table_thead_color = $options['ebt_table_thead_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_thead_color]" value="'.$ebt_table_thead_color.'" class="engagifii-color-picker hide-options-here-tz">';
                          echo $_inputHtml;
                    	 ?></td>
            </tr>
            <tr>
                <th> Hyperlink Hover Color</th>
                <td><?php
                       $ebt_table_link_hover_color = $options['ebt_table_link_hover_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_link_hover_color]" value="'.$ebt_table_link_hover_color.'" class="engagifii-color-picker">';
                       echo $_inputHtml;?> </td>
            </tr>
            <tr>
                <th>Pagination Default Color </th>
                <td><?php
                       $ebt_pagination_default_color = $options['ebt_pagination_default_color'];
                       $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_pagination_default_color]" value="'.$ebt_pagination_default_color.'" class="engagifii-color-picker">';
                       echo $_inputHtml;
                       ?></td>
            </tr>
            <tr>
                <th> Pagination Hover Color</th>
                <td><?php
                        $ebt_pagination_hover_color = $options['ebt_pagination_hover_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_pagination_hover_color]" value="'.$ebt_pagination_hover_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    	 ?></td>
            </tr>
            <tr>
              	
                <th> Text Color</th>
                <td><?php 
                        $ebt_detail_text_color = $options['ebt_detail_text_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_text_color]" value="'.$ebt_detail_text_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    	?></td>
            </tr>
            <tr>
                <th>Pagination Selected Color </th>
                <td><?php 
                        $ebt_pagination_color = $options['ebt_pagination_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_pagination_color]" value="'.$ebt_pagination_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    	?></td>
            </tr>
            <tr>
            	
                <th> Heading 1</th>
                <td><?php
                        $ebt_detail_heading_color = $options['ebt_detail_heading_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_heading_color]" value="'.$ebt_detail_heading_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    	 ?></td>
            </tr>
            <tr>
                <th>Calendar Background Color</th>
                <td>
                    <?php
                        $ebt_detail_calendar_background_color = $options['ebt_detail_calendar_background_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_background_color]" value="'.$ebt_detail_calendar_background_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Calendar class list Background Color</th>
                <td>
                    <?php
                        $ebt_detail_class_list_background_color = $options['ebt_detail_class_list_background_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_class_list_background_color]" value="'.$ebt_detail_class_list_background_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Calendar Text Color</th>
                <td>
                    <?php
                        $ebt_detail_calendar_text_color = $options['ebt_detail_calendar_text_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_text_color]" value="'.$ebt_detail_calendar_text_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Class Text Color</th>
                <td>
                    <?php
                        $ebt_detail_class_calendar_text_color = $options['ebt_detail_class_calendar_text_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_class_calendar_text_color]" value="'.$ebt_detail_class_calendar_text_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Calendar Button Color</th>
                <td>
                    <?php
                        $ebt_detail_button_color = $options['ebt_detail_button_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_button_color]" value="'.$ebt_detail_button_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Calendar Hover Color</th>
                <td>
                    <?php
                        $ebt_detail_calendar_hover_color = $options['ebt_detail_calendar_hover_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_hover_color]" value="'.$ebt_detail_calendar_hover_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Calendar Slot Background Color </th>
                <td>
                    <?php
                        $ebt_detail_calendar_strip_color = $options['ebt_detail_calendar_strip_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_strip_color]" value="'.$ebt_detail_calendar_strip_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Calendar Slot Background Hover Color</th>
                <td>
                    <?php
                        $ebt_detail_calendar_strip_hover_color = $options['ebt_detail_calendar_strip_hover_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_strip_hover_color]" value="'.$ebt_detail_calendar_strip_hover_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
                </td>
            </tr>

        </table>
        <hr>
        <div>
        <?php
		
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
	//print_r($dt_responsive);
	//print_r($chkd);
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
        
        
<?php
       do_action('engagifiiGetColumnList');
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
</script>