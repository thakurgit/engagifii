jQuery(document).ready(function(){
   jQuery('.engagifii-color-picker').wpColorPicker();

   jQuery("#engagii_custom_css").on("click", function(){
      var isChecked= jQuery(this).is(":checked");
      if(isChecked==true){
         jQuery(".engagifi_style_group").show();
      }
      else
      {

         jQuery(".engagifi_style_group").hide();
         jQuery('#font-family-tz').val('');
         jQuery('#font-size-tz').val('');
         jQuery('#table-size-tz').val('');
         jQuery('#detail-size-tz').val('');
         jQuery('#ebt-size-tz').val('');
         jQuery('.wp-picker-clear').click();
      }

   });
   
});














