<?php   $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		?>
        <form class="ebt-setting-frm" action='options.php' method='post'>
    <?php

        $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
        settings_fields( 'engagifiiPlugin' );
        do_settings_sections( 'engagifiiPlugin' );
        
		?>
    <?php
        $options = get_option( 'ebt_api_settings' );
            
       /* if(isset($options['engagifii_apply_css_ebt'])){
            $engagifii_apply_css_ebt = $options['engagifii_apply_css_ebt'];
            }else{
           		$engagifii_apply_css_ebt = 0;
           	}*/
           $checkedHtml  = ' style="display:none"';
     		 if( $tab === null)
            {
            	$checkedHtml  = ' style="display:block"';
            }
            //include( plugin_dir_path( __FILE__ ) . '/admin-fonts-sie.php');
            //include( plugin_dir_path( __FILE__ ) . '/admin-fonts.php');
           // $options = get_option( 'ebt_api_settings' );
        ?>
    <div class="wrap tab-content " >
    <div class="engagifi_style_group engagifii-setting m-tlr-20" data-tab="" <?php echo $checkedHtml ?>>
        <table class="engtcustomtbl" cellspacing="0" cellpadding="15" width="100%">
        <tr>
        	<td colspan="4"><h3>Theme Colors</h3><hr></td>
        </tr>
        <tr>
        	<td><h4>Primary Color</h4>
            <?php
                       $engagifii_theme_color = @$options['engagifii_theme_color'];
					   if(!$engagifii_theme_color){
							$engagifii_theme_color = $options['ebt_table_bg_color'];   
					   }
                       $_inputHtml = '<input type="text" name="ebt_api_settings[engagifii_theme_color]" value="'.$engagifii_theme_color.'" class="engagifii-color-picker hide-options-here-tz">';
                         echo $_inputHtml;
			?>
            </td>
        	<td></td>
        	<td></td>
        	<td></td>
        </tr>
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
                <td colspan="3">
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
                <td colspan="3">
                	<h4>Link Color</h4>
                    <?php
                        $ebt_table_link_color = $options['ebt_table_link_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_table_link_color]" value="'.$ebt_table_link_color.'" class="engagifii-color-picker">';
                          echo $_inputHtml;
                    	 ?>
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
              <td colspan="3">
              	<h4>Calendar Class Name Background</h4>
                 <?php
                        $ebt_detail_calendar_strip_color = $options['ebt_detail_calendar_strip_color'];
                        $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_detail_calendar_strip_color]" value="'.$ebt_detail_calendar_strip_color.'" class="engagifii-color-picker">';
                        echo $_inputHtml;
                    ?>
              </td>
        </tr>
        </table>  
        <hr>
        <div style="padding-left:7px"> 
        <?php if(isset($options['include_bootstrap'])){
    $include_bootstrap = $options['include_bootstrap']; 
   }else{
       $include_bootstrap = null;
   }
    $include_bootstrap_setting = '';
    if($include_bootstrap==1)
    {
         $include_bootstrap_setting  = 'checked';
    }?>
        <div class="form-check form-switch">
        	<input class="form-check-input" type="checkbox" name="ebt_api_settings[include_bootstrap]" id="include_bootstrap" value="1" <?php echo $include_bootstrap_setting; ?>> 
       		 <label for="include_bootstrap" class="form-check-label"><strong>Include Bootstrap Files</strong></label>
         	</div>
           <i>Note: This Plugin works with <a href="https://getbootstrap.com/docs/4.6/getting-started/introduction/" target="_blank" rel="nofollow">Bootstrap 4</a>. If your theme doesn't have Bootstrap files, then enable checkbox to work this plugin properly.</i>
        </div>
        
        <div style="padding-left:7px; margin-top:20px"> 
        <?php $include_fontawesome = isset($options['include_fontawesome']) ? $options['include_fontawesome'] : ['enabled' => 1, 'version' => '5.15.4'];
if (!is_array($include_fontawesome)) {
    $include_fontawesome = [
        'enabled' => $include_fontawesome,
        'version' => '5.15.4'
    ];
}
$enabled = isset($include_fontawesome['enabled']) ? $include_fontawesome['enabled'] : 1;
$version = isset($include_fontawesome['version']) ? $include_fontawesome['version'] : '5.15.4';
$include_fontawesome_setting = ($enabled == 1) ? 'checked' : '';
?>
<div class="form-check form-switch">
	<input type="hidden" name="ebt_api_settings[include_fontawesome][enabled]" value="0">
    <input class="form-check-input" type="checkbox" name="ebt_api_settings[include_fontawesome][enabled]" id="include_fontawesome" value="1" <?php echo $include_fontawesome_setting; ?>> 
    <label for="include_fontawesome" class="form-check-label"><strong>Include FontAwesome Icons</strong></label>
</div>

<div class="form-group" id="fa-version-wrapper" style=" <?php echo ($enabled == 1) ? '' : 'display: none;'; ?>">
    <label style="width: 150px;">Select FontAwesome Version</label>
    <select name="ebt_api_settings[include_fontawesome][version]" class="select-fa-ver">
        <option value="5.15.4" <?php echo ($version === '5.15.4') ? 'selected' : ''; ?>>5.15.4</option>
        <option value="6.7.2" <?php echo ($version === '6.7.2') ? 'selected' : ''; ?>>6.7.2</option>
    </select>
</div> 
           <i>Note: Uncheck this option if your theme already includes the FontAwesome icon library to prevent duplication.</i>
        </div>
    </div>

    </div>
        
      
<?php
	if($tab!= 'shortcode'){
      do_action('engagifiiGetColumnList');
	  do_action('engagifiiCustomizer');
      do_action('profileSettings');
	}
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
    tCode =tCode.replace('http://', '');
	  tCode =tCode.split('.')[0];
	 tCode =tCode.replace(/([-,.€~!@#$%^&*()+=`{}\[\]\|\\:;'<>])+/g, '');
	  jQuery(current).siblings('span').html(tCode);
	   jQuery(current).siblings('input').val(tCode);
    }
	
  jQuery('.accordion-btn').click(function(){
		jQuery(this).toggleClass('active').next('.accordion-content').slideToggle();
		jQuery(this).parent('.wrap').siblings().find('.accordion-content').slideUp();
		jQuery(this).parent('.wrap').siblings().find('.accordion-btn ').removeClass('active');
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
        jQuery(this).prepend('<li class="toggleAll" style="width: 98%;"><input type="checkbox" id="toggleAll_'+tid+'"/><label for="toggleAll_'+tid+'"><b><u>Select/Deselect all</u></b></label></li>');
		jQuery('#toggleAll_'+tid).each(function(){
		  if(jQuery(this).parent().siblings('li').find('input[type="checkbox"]').length==jQuery(this).parent().siblings('li').find('input:checked').length) {
			 jQuery(this).prop('checked',true);
			  
		  } else {
			 jQuery(this).prop('checked',false);
		  }
		});
		jQuery('#toggleAll_'+tid).change(function(){
			if(jQuery(this).is(':checked')){
				jQuery(this).parent().siblings('li').find('input[type="checkbox"]').not('input[readonly]').prop('checked',true).trigger('change');
			} else {
				jQuery(this).parent().siblings('li').find('input[type="checkbox"]').not('input[readonly]').prop('checked',false).trigger('change');
			}
		});
      tid++;  
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
		jQuery('body').on('click', 'input[readonly], input[disabled], input[readonly]+label, input[disabled]+label', function() {
		showAlert("This item can not be modified.");
return false;
	});
	function showAlert(message){
		var alertHtml ='<div class="showalert">'+message+'</div>';	
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
  	  	var envOnload=false;
  jQuery(document).ready(function() {
    var $ = jQuery;
    if ($('.set_logo').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $('.set_logo').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.siblings('input');
                var img = button.siblings('img'); 
                var remove = button.siblings('button');
                wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.id);
					img.attr('src',attachment.url);
					remove.removeClass('hidden');
                };
                wp.media.editor.open(button);
                return false;
            });
			$('.remove_logo').on('click', function(e) {
				 e.preventDefault();
				$(this).siblings('input').val('');
				$(this).siblings('img').attr('src','');
				$(this).addClass('hidden');
			});
        }
    }
    jQuery('.select-env').change( function() {
			$('.env-loading').show();
			$('.env-loading-msg').text('').removeClass('error success');;
		/* $.ajax({url: "https://engagifii.engagifii"+jQuery(this).val()+".com/assets/environment-config-1.0.json", 
		 success: function(result){
			 var apiUrls = {'crmUrl':result.crmBaseUrl,'reportUrl':result.courseReporturl,'authUrl':result.authPolicyDevUrl,'revenueUrl':result.revenueBaseUrl,'doUrl':result.dynamicObjectApprovalUrl,'tnaUrl':result.baseUrl,'eventUrl':result.eventBaseUrl,'legisUrl':result.legislationBaseUrl,'resourceUrl':result.resourceBaseUrl};
			 for (var key in apiUrls) {
				if (apiUrls.hasOwnProperty(key)) {
					if(apiUrls[key].indexOf('api') == -1){
						if(key=='resourceUrl'){
						  apiUrls[key] = apiUrls[key]+'/api/upload';	
						} else {
						  apiUrls[key] = apiUrls[key]+'/api/v1';	
						}
					}
					//jQuery('.select-env').siblings('.'+key).val(apiUrls[key]);
					jQuery('input.'+key).val(apiUrls[key]);
					
				}
			}
			$('.env-loading').hide();
			$('.env-loading-msg').text('API Urls updated!').addClass('success');;
			},
			 error: function(xhr, textStatus, errorThrown) {
			  //console.log(xhr, textStatus, errorThrown);
				$('.env-loading').hide();
				$('.env-loading-msg').text('Oops! API Urls update failed!').addClass('error');
			}
		});*/
	   $.ajax({
          type : "post",
          url: ajaxurl,
          data:{
              action:'apisJson',
			  env:jQuery(this).val(),
          },
		 success: function(result){
			 var apiUrls = {'crmUrl':result.crmBaseUrl,'reportUrl':result.courseReporturl,'authUrl':result.authPolicyDevUrl,'revenueUrl':result.revenueBaseUrl,'doUrl':result.dynamicObjectApprovalUrl,'tnaUrl':result.baseUrl,'eventUrl':result.eventBaseUrl,'legisUrl':result.legislationBaseUrl,'resourceUrl':result.resourceBaseUrl};
			 for (var key in apiUrls) {
				if (apiUrls.hasOwnProperty(key)) {
					if(apiUrls[key].indexOf('api') == -1){
						if(key=='resourceUrl'){
						  apiUrls[key] = apiUrls[key]+'/api/upload';	
						} else {
						  apiUrls[key] = apiUrls[key]+'/api/v1';	
						}
					}
					//jQuery('.select-env').siblings('.'+key).val(apiUrls[key]);
					jQuery('input.'+key).val(apiUrls[key]);
					
				}
			}
			$('.env-loading').hide();
			$('.env-loading-msg').text('API Urls updated!').addClass('success');;
			},
		 error: function(xhr, textStatus, errorThrown) {
			  //console.log(xhr, textStatus, errorThrown);
				$('.env-loading').hide();
				$('.env-loading-msg').text('Oops! API Urls update failed!').addClass('error');
			}
        });
	});
	//if(jQuery('.select-env').val()===''){
	if(jQuery('.select-env').hasClass('nullenv') || jQuery('input.crmUrl[type="hidden"]').val()==''){
		jQuery('.select-env').val('').change();	
		setTimeout(function() {
		  jQuery('.env-loading-msg').text('');
		}, 1000); 
	}
	$('#update_manually').change(function(){
		if (this.checked) {
			//$(this).parent().siblings().hide();	
			$('.apiUrls').show();	
		}else{
			//$(this).parent().siblings().show();	
			$('.apiUrls').hide();	
		}
	});
	$('.tenantInput').on('input',function(){
		$('.tenantCode').val($(this).siblings('input').val()).trigger('input');
	});
	
//tab switch
  $('.nav-tab-wrapper > a').click(function(e) {
	const url =$(this).attr('href');
	const urlParams = new URLSearchParams(url.split('?')[1]);
	const tabValue = urlParams.get('tab');
	if(tabValue=='shortcode' || jQuery('.nav-tab-active').index() ==$('.nav-tab-wrapper > a').length-1){
		return true;
	}
	$(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
	
	$("div[data-tab]").each(function() {
		if($(this).attr('data-tab')==tabValue){
		  $(this).show().removeClass('hide');	
		} else{
		  $(this).hide().addClass('hide');	
		  if(tabValue==null){
			$('div[data-tab=""]').show().removeClass('hide'); 
		  }
		}
	});
	e.preventDefault();
  });	
});
</script>