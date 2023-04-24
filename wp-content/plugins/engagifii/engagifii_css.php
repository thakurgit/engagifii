<?php
/*
* Engagifii Customized CSS
* Since v1.0.0
* Custom table, font and header color settings
*
* @package engagifii-api
* @category wordpress
* @author Engagifii
*/

$custom_Css = get_option('ebt_api_settings');
if(isset($options['engagifii_apply_css_ebt'])){
    $activate_css= $custom_Css['engagifii_apply_css_ebt'];
   }else{
   	$activate_css = 0;
}

// css settings
$settings = get_option('ebt_api_settings');

$font_family = ( isset( $settings['engagifii_font_family'] ) && $settings['engagifii_font_family'] != '' ) ? $settings['engagifii_font_family'] : '';

$table_heading_bg   = ( isset( $settings['ebt_table_bg_color'] ) && $settings['ebt_table_bg_color'] != '' ) ? $settings['ebt_table_bg_color'] : '';

$table_heading_color   = ( isset( $settings['ebt_table_thead_color'] ) && $settings['ebt_table_thead_color'] != '' ) ? $settings['ebt_table_thead_color'] : '';

$table_heading_fsize   = ( isset( $settings['ebt_table_thead_fontsize'] ) && $settings['ebt_table_thead_fontsize'] != '' ) ? $settings['ebt_table_thead_fontsize'] : '';

$ebt_table_tbody_color   = ( isset( $settings['ebt_table_tbody_color'] ) && $settings['ebt_table_tbody_color'] != '' ) ? $settings['ebt_table_tbody_color'] : '';

$ebt_table_tbody_fontsize   = ( isset( $settings['ebt_table_tbody_fontsize'] ) && $settings['ebt_table_tbody_fontsize'] != '' ) ? $settings['ebt_table_tbody_fontsize'] : '';

$ebt_table_link_color   = ( isset( $settings['ebt_table_link_color'] ) && $settings['ebt_table_link_color'] != '' ) ? $settings['ebt_table_link_color'] : '';

$ebt_table_link_hover_color   = ( isset( $settings['ebt_table_link_hover_color'] ) && $settings['ebt_table_link_hover_color'] != '' ) ? $settings['ebt_table_link_hover_color'] : '';

$ebt_sponsors_color   = ( isset( $settings['ebt_sponsors_color'] ) && $settings['ebt_sponsors_color'] != '' ) ? $settings['ebt_sponsors_color'] : '';

$ebt_table_hover_color   = ( isset( $settings['ebt_table_hover_color'] ) && $settings['ebt_table_hover_color'] != '' ) ? $settings['ebt_table_hover_color'] : '';

$ebt_pagination_color   = ( isset( $settings['ebt_pagination_color'] ) && $settings['ebt_pagination_color'] != '' ) ? $settings['ebt_pagination_color'] : '';

$ebt_pagination_default_color   = ( isset( $settings['ebt_pagination_default_color'] ) && $settings['ebt_pagination_default_color'] != '' ) ? $settings['ebt_pagination_default_color'] : '';

$ebt_pagination_hover_color   = ( isset( $settings['ebt_pagination_hover_color'] ) && $settings['ebt_pagination_hover_color'] != '' ) ? $settings['ebt_pagination_hover_color'] : '';

$ebt_detail_heading_color   = ( isset( $settings['ebt_detail_heading_color'] ) && $settings['ebt_detail_heading_color'] != '' ) ? $settings['ebt_detail_heading_color'] : '';

$ebt_detail_heading_font   = ( isset( $settings['ebt_detail_heading_font'] ) && $settings['ebt_detail_heading_font'] != '' ) ? $settings['ebt_detail_heading_font'] : '';


$ebt_detail_text_color   = ( isset( $settings['ebt_detail_text_color'] ) && $settings['ebt_detail_text_color'] != '' ) ? $settings['ebt_detail_text_color'] : '';

$ebt_detail_text_font   = ( isset( $settings['ebt_detail_text_font'] ) && $settings['ebt_detail_text_font'] != '' ) ? $settings['ebt_detail_text_font'] : '';

$ebt_detail_calendar_background_color = $settings['ebt_detail_calendar_background_color']?? '';
$ebt_detail_class_list_background_color  = $settings['ebt_detail_class_list_background_color'] ?? '';
$ebt_detail_calendar_text_color          = $settings['ebt_detail_calendar_text_color'] ?? '';
$ebt_detail_class_calendar_text_color    = $settings['ebt_detail_class_calendar_text_color']?? '';
$ebt_detail_button_color                 = $settings['ebt_detail_button_color'] ?? '';
$ebt_detail_calendar_hover_color         = $settings['ebt_detail_calendar_hover_color'] ?? '';
$ebt_detail_calendar_strip_color         = $settings['ebt_detail_calendar_strip_color'] ?? '';
$ebt_detail_calendar_strip_hover_color         = $settings['ebt_detail_calendar_strip_hover_color'] ?? '';
?>

<style>
<?php if(isset($activate_css)){?>


#ebtmaintable_wrapper .dataTable thead tr>th,  .light-background thead>tr>th,  .engagifii-box .prv, .engagifii-box .nxt, .filter-top-bg{ 
  background: <?php echo $table_heading_bg;?> !important;
  color:<?php echo $table_heading_color?> !important;
}
.ui-widget-content {
	border-color: <?php echo $table_heading_bg;?> !important;
}
.ui-widget-header {
	background: <?php echo $table_heading_bg;?> !important;
}
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-state-hover,
.ui-widget-content .ui-state-hover,
.ui-widget-header .ui-state-hover,
.ui-state-focus,
.ui-widget-content .ui-state-focus,
.ui-widget-header .ui-state-focus  {
	background:<?php echo $table_heading_bg;?> !important;
}
.containerEngagii .click-filter{color:<?php echo $table_heading_bg?>;}
.containerEngagii.active .click-filter{
	color:<?php echo $table_heading_color?> !important;
}
.containerEngagii.active{
	background:<?php echo $table_heading_bg?> !important;
}
.engTrackingLevels{
	background: <?php echo $table_heading_bg;?> !important;
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

.searchbox-title {
    font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.select2-search__field{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.page .select2-results__message{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.sponsors-middle .flex-1{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.newsearchcustom {
    font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.tzsearchinput{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.engwarpper{
	font-size: <?php echo $ebt_detail_text_font?>px;
}



.engagifii-left-pane .engagifii-btn-tz{
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

.download-detail{
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

.page .select2-results__option span{
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

<?php /*?>#ebtmaintable_length label{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}<?php */?>



.heading-title{
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

.list-box li{
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

.filter-btn-tz{
	background: <?php echo $table_heading_bg;?> !important;
	border: <?php echo $table_heading_bg;?> !important;
	box-shadow: none;
	color: <?php echo $table_heading_color?> !important;
}

.engagifii-box table.table thead th{border-bottom: none;}
/*#ebtmaintable tbody tr:hover {
    background-color: <?php echo $ebt_table_hover_color;?> !important;
}*/

.no-table-gapping-detail tr:hover{
	background-color: <?php echo $ebt_table_hover_color;?> !important;
}

#ebtmaintable thead>tr>th,  .light-background thead>tr>th{
	color:<?php echo $table_heading_color?>;
	font-size: <?php echo $table_heading_fsize?>px;
	font-family: <?php echo $font_family;?> !important;
}



.bill-detail-summary-tab .bill-detail-sponsors .summary-content-para{
	font-family: <?php echo $font_family;?> !important;
}

.no-table-gapping-detail tbody tr td{
	font-family: <?php echo $font_family;?> !important;
}

.no-table-gapping-detail tbody tr td a{
	font-family: <?php echo $font_family;?> !important;
}

.sponsor-name-detail{
	font-family: <?php echo $font_family;?> !important;
}

#ebtmaintable_paginate .page-item.previous a{
	font-family: <?php echo $font_family;?> !important;
}

.tracking-level-top .tracking-name{
	font-family: <?php echo $font_family;?> !important;
}

#ebtmaintable a{
	font-family: <?php echo $font_family;?> !important;
}

#ebtmaintable_paginate .page-item.next a{
	font-family: <?php echo $font_family;?> !important;
}

#ebtmaintable_paginate .page-item.active a{
	font-family: <?php echo $font_family;?> !important;
}

#ebtmaintable_paginate .page-item a{
	font-family: <?php echo $font_family;?> !important;
}

.tracking-level-top .tracking{
	font-family: <?php echo $font_family;?> !important;
}

.same-in-parallel{
	font-family: <?php echo $font_family;?> !important;
}

.no-border{
	font-family: <?php echo $font_family;?> !important;
}
.vertical-middle{
	font-family: <?php echo $font_family;?> !important;
}

.ebt-link.active, .ebt-link{
	font-family: <?php echo $font_family;?> !important;
}

.bill-detail-summary-tab .bill-detail-summary-content .summary-content-para{
	font-family: <?php echo $font_family;?> !important;
}

.summary-content-para-engagiigii{
	font-family: <?php echo $font_family;?> !important;
}

/*.alpha-teal{
	background-color: <?php echo $table_heading_bg?>;
}



.alpha-teal th{
	color:<?php echo $table_heading_color?>;
	font-size: <?php echo $table_heading_fsize?>px;
	font-family: <?php echo $font_family;?> !important;
}*/

.no-table-gapping-detail td{
	font-size: <?php echo $ebt_table_tbody_fontsize?>px;
    color: <?php echo $ebt_table_tbody_color?>;
}

#ebtmaintable.table-bordered.dataTable tbody td{
	font-size: <?php echo $ebt_table_tbody_fontsize?>px;
    color: <?php echo $ebt_table_tbody_color?>;
    font-family: <?php echo $font_family;?> !important;
}

#ebtmaintable a{
	color: <?php echo $ebt_table_link_color?>!important;
}





#ebtmaintable a:hover {
	color: <?php echo $ebt_table_link_hover_color?>!important;
}



.no-table-gapping-detail td a{
	color: <?php echo $ebt_table_link_color?>!important;
}

.no-table-gapping-detail td a:hover{
	color: <?php echo $ebt_table_link_hover_color?>!important;
}





/*#ebtmaintable_paginate .page-item.active a{
	background-color: <?php echo $ebt_pagination_color?>;
    border-color: <?php echo $ebt_pagination_color?>;
}

#ebtmaintable_paginate .page-item a{
	background-color: <?php echo $ebt_pagination_default_color?>;
}

#ebtmaintable_paginate .page-item a:hover{
	background-color: <?php echo $ebt_pagination_hover_color?> !important;
}

#ebtmaintable_paginate .page-item.active a:hover{
	background-color: <?php echo $ebt_pagination_color?> !important;
}*/

.no-border{
	font-size: <?php echo $ebt_detail_heading_font?>px;
    color: <?php echo $ebt_detail_heading_color?>;
}

<?php /*?>.vertical-middle , .summary-content-para-engagiigii, .ebt-link, .ebt-link.active, .summary-content-para{
	font-size: <?php echo $ebt_detail_text_font?>px;
    color: <?php echo $ebt_detail_text_color?> !important;
}<?php */?>

/*.calendar-background{background-color: <?php echo $ebt_detail_calendar_background_color; ?>}
.calendar-text {color: <?php echo $ebt_detail_calendar_text_color; ?>}
.class-background{background-color: <?php echo $ebt_detail_class_list_background_color; ?>}
.class-text span, .class-text div{color: <?php echo $ebt_detail_class_calendar_text_color; ?>}*/
.calendar__day.active{background-color: <?php echo $ebt_detail_calendar_hover_color; ?> !important;}
.classNames > a {
    background-color: <?php echo $ebt_detail_calendar_strip_color; ?> !important;
}
.classNames > a:hover, .classNames > a:focus {background-color: <?php echo $ebt_detail_calendar_strip_hover_color; ?>!important;; }
/* CSS for Class Names on Calendar view */




/* Ends Here */

<?php }?>
</style>