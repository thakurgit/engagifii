<?php   $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		?>
        <form class="ebt-setting-frm" action='options.php' method='post'>
    <?php

        $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
        settings_fields( 'engagifiiPlugin' );
        do_settings_sections( 'engagifiiPlugin' );
        
        //@do_settings_sections( 'engagifiiPluginTwo');
		?>
    <?php
        $options = get_option( 'ebt_api_settings' );
            
        if(isset($options['engagifii_apply_css_ebt'])){
            $engagifii_apply_css_ebt = $options['engagifii_apply_css_ebt'];
            }else{
           		$engagifii_apply_css_ebt = 0;
           	}
           $checkedHtml  = ' style="display:none"';
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
        do_action('profileSettings');
		?>
    <div class="ebt-submit-btn">
        <?php 
            if($tab!= 'shortcode')
                submit_button("Save Settings");
        ?>
    </div>
</form> 
<script type="text/javascript">
  function getTenantCode(tenantCode, current) {    		
	  var tCode = tenantCode;
    tCode =tCode.replace('https://', '');
	  tCode =tCode.split('.')[0];
	 tCode =tCode.replace(/([-,.€~!@#$%^&*()+=`{}\[\]\|\\:;'<>])+/g, '');
	  jQuery(current).siblings('span').html(tCode);
	   jQuery(current).siblings('input').val(tCode);
    }
	
  jQuery('.accordion-btn').click(function(){
		jQuery(this).toggleClass('active').next('.accordion-content').slideToggle();
		
		
	
});
jQuery( '.shortcode-list code' ).click( function( event ) {
			var range = document.createRange();
			range.selectNodeContents( this );
			window.getSelection().addRange( range );
		} );
		
//change sequence as per dragged sequence on load	
	jQuery('.sortable-list').each(function(){
		var colsList=[];	
		if(jQuery(this).siblings('.cls').val()!==''){
		  colsList= (jQuery(this).siblings('.cls').val()).split(',');
		}
		
	if(colsList.length>0){
	  var i;
	  var outerHtml=[];
	  for (i = 0; i < colsList.length; ++i) {
		 outerHtml.push(jQuery(this).children().eq(colsList[i]-1).prop('outerHTML') );
	  }
		jQuery(this).html(outerHtml);
	  }
	});
	
//toggle all
		var tid =0;
		jQuery('.ebt-grid-column-list').each(function() {
    if(jQuery(this).find('li:not(.toggleAll) input[type="checkbox"]').length > 0) {
        jQuery(this).prepend('<li class="toggleAll"><input type="checkbox" id="toggleAll_'+tid+'"/><label for="toggleAll_'+tid+'"><b><u>Select/Deselect all</u></b></label></li>');
		jQuery('#toggleAll_'+tid).each(function(){
		  if(jQuery(this).parent().siblings('li').find('input[type="checkbox"]').length==jQuery(this).parent().siblings('li').find('input:checked').length) {
			 jQuery(this).prop('checked',true);
			  
		  } else {
			 jQuery(this).prop('checked',false);
		  }
		});
		jQuery('#toggleAll_'+tid).change(function(){
			if(jQuery(this).is(':checked')){
				jQuery(this).parent().siblings('li').find('input[type="checkbox"]').not('input[readonly]').prop('checked',true);
			} else {
				jQuery(this).parent().siblings('li').find('input[type="checkbox"]').not('input[readonly]').prop('checked',false);
			}
		});
      tid++;  
		//jQuery(this).find('li:not(.toggleAll)').each(function(){
		 jQuery(this).find('li:not(.toggleAll) input[type="checkbox"]').change(function(){
			if(jQuery(this).parents('.ebt-grid-column-list').find('li:not(.toggleAll) input[type="checkbox"]').length==jQuery(this).parents('.ebt-grid-column-list').find('li:not(.toggleAll) input:checked').length) {
			   jQuery(this).parent().siblings('.toggleAll').find('input[type="checkbox"]').prop('checked',true);
			} else {

			   jQuery(this).parent().siblings('.toggleAll').find('input[type="checkbox"]').prop('checked',false);
			}
			  
		  });
		//add column number to each list
	  if(jQuery(this).is("#legislationList, #classList, #eventList, #coursesList, #endorsementList")) {
        jQuery(this).children('li:not(.toggleAll)').each(function() {
		var label = jQuery(this).find('label').text(); 
		jQuery(this).find('label').html('<b>'+jQuery(this).index()+'.</b> '+label); 
	  });
	}
    }
});
//alert on readonly checkbox
		jQuery('body').on('click', 'input[readonly], input[readonly]+label', function() {
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
//sortable	
  jQuery( function() {
	  jQuery('.sortable-list').each(function(){
		jQuery(this).sortable({
			items : 'li:not(.toggleAll)',
			 placeholder: "ui-state-highlight",
			 update: function( event, ui ) {
				   var col_order=[];
				  jQuery(this).children("li:not(.toggleAll)" ).each(function(){
					  col_order.push(jQuery(this).attr('data-order'));
					  jQuery(this).find('label b').text(jQuery(this).index()+'.');
				  });
				  jQuery(this).siblings('.cls').val(col_order);
				 }
			});
            jQuery('<button class="resetposition" title="Click to reset the columns sequence">Reset columns position</button>').insertAfter(jQuery(this));
	  });
	  //sortable reset
	function sorts(a, b) {
	  return parseInt(a.dataset.order) - parseInt(b.dataset.order);
	}
	jQuery('.resetposition').click(function(e){
  jQuery(this).siblings('ul').find('li.ui-sortable-handle').sort(sorts).each(function() {
  var elem = jQuery(this);
  jQuery(elem).appendTo(jQuery(this).parent('ul'));
});
jQuery(this).siblings('.cls').val('');
		e.preventDefault();
  jQuery(this).prev('ul').find('li:not(.toggleAll)').each(function() {
	  var dataOrder = jQuery(this).attr('data-order');
  	jQuery(this).find('label b').text(dataOrder+'. ');
});
	});
          
       
  } );
</script>