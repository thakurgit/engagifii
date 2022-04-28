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
@import url(https://fonts.googleapis.com/css?family=Arvo);
@import url(https://fonts.googleapis.com/css?family=Heebo);
@import url(https://fonts.googleapis.com/css?family=Cabin);
@import url(https://fonts.googleapis.com/css?family=Courier+Prime);
@import url('https://fonts.googleapis.com/css?family=Concert+One');
@import url(https://fonts.googleapis.com/css?family=Lato);
@import url(https://fonts.googleapis.com/css?family=Lobster);
@import url('https://fonts.googleapis.com/css?family=Lora');
@import url(https://fonts.googleapis.com/css?family=Montserrat);
@import url('https://fonts.googleapis.com/css?family=Noto+Sans');
@import url('https://fonts.googleapis.com/css?family=Nunito+Sans');
@import url(https://fonts.googleapis.com/css?family=Open+Sans);
@import url(https://fonts.googleapis.com/css?family=Oswald);
@import url(https://fonts.googleapis.com/css?family=Poppins);
@import url(https://fonts.googleapis.com/css?family=Playfair+Display);
@import url('https://fonts.googleapis.com/css?family=PT+Sans');
@import url('https://fonts.googleapis.com/css?family=Prompt');
@import url(https://fonts.googleapis.com/css?family=Roboto);
@import url(https://fonts.googleapis.com/css?family=Rubik);
@import url(https://fonts.googleapis.com/css?family=Raleway);
@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro);
@import url('https://fonts.googleapis.com/css?family=Slabo+27px');
@import url(https://fonts.googleapis.com/css?family=Ubuntu);

::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  font-family: <?php echo $font_family;?> !important;
  font-size: <?php echo $ebt_detail_text_font?>px !important;
}
::-moz-placeholder { /* Firefox 19+ */
  font-family: <?php echo $font_family;?> !important;
  font-size: <?php echo $ebt_detail_text_font?>px !important;
}
:-ms-input-placeholder { /* IE 10+ */
  font-family: <?php echo $font_family;?> !important;
  font-size: <?php echo $ebt_detail_text_font?>px !important;
}
:-moz-placeholder { /* Firefox 18- */
  font-family: <?php echo $font_family;?> !important;
  font-size: <?php echo $ebt_detail_text_font?>px !important;
}

#ebtmaintable_wrapper .dataTable thead tr>th,  .light-background thead>tr>th,  .engagifii-box .prv, .engagifii-box .nxt, .filter-top-bg{ 
  background: <?php echo $table_heading_bg;?> !important;
  color:<?php echo $table_heading_color?>;
}
.containerEngagii .click-filter{color:<?php echo $table_heading_bg?>;}
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

.go-back{
	color: <?php echo $ebt_table_link_color?>!important;
	font-family: <?php echo $font_family;?> !important;
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

#ebtmaintable_length label{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

#ebtmaintable_length .custom-select{
	font-family: <?php echo $font_family;?> !important;
    font-size: <?php echo $ebt_detail_text_font?>px !important;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.filter-title{
	font-family: <?php echo $font_family;?> !important;
	color: <?php echo $ebt_detail_text_color?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
}

.clear-all{
	font-family: <?php echo $font_family;?> !important;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

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
	font-family: <?php echo $font_family;?> !important;
	background: <?php echo $table_heading_bg;?> !important;
	border: <?php echo $table_heading_bg;?> !important;
	box-shadow: none;
	font-size: <?php echo $ebt_detail_text_font?>px;
	color: <?php echo $ebt_detail_text_color?> !important;
}

.engagifii-box table.table thead th{border-bottom: none;}
#ebtmaintable tbody tr:hover {
    background-color: <?php echo $ebt_table_hover_color;?> !important;
}

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

.alpha-teal{
	background-color: <?php echo $table_heading_bg?>;
}



.alpha-teal th{
	color:<?php echo $table_heading_color?>;
	font-size: <?php echo $table_heading_fsize?>px;
	font-family: <?php echo $font_family;?> !important;
}

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



.download-detail{
	color: <?php echo $ebt_table_link_color?>!important;
}

#ebtmaintable a:hover , .go-back:hover{
	color: <?php echo $ebt_table_link_hover_color?>!important;
}

.download-detail:hover{
	color: <?php echo $ebt_table_link_hover_color?>!important;
}

.no-table-gapping-detail td a{
	color: <?php echo $ebt_table_link_color?>!important;
}

.no-table-gapping-detail td a:hover{
	color: <?php echo $ebt_table_link_hover_color?>!important;
}

#ebtmaintable span.badge{
	background: <?php echo $ebt_sponsors_color;?> !important;
	font-family: <?php echo $font_family;?> !important;
}



#ebtmaintable_paginate .page-item.active a{
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
}

.no-border{
	font-size: <?php echo $ebt_detail_heading_font?>px;
    color: <?php echo $ebt_detail_heading_color?>;
}

.vertical-middle , .summary-content-para-engagiigii, .ebt-link, .ebt-link.active, .summary-content-para{
	font-size: <?php echo $ebt_detail_text_font?>px;
    color: <?php echo $ebt_detail_text_color?> !important;
}

.calendar-background{background-color: <?php echo $ebt_detail_calendar_background_color; ?>}
.calendar-text {color: <?php echo $ebt_detail_calendar_text_color; ?>}
.class-background{background-color: <?php echo $ebt_detail_class_list_background_color; ?>}
.class-text span, .class-text div{color: <?php echo $ebt_detail_class_calendar_text_color; ?>}
.calendar__day:not(.today):hover, .calendar__day.active{background-color: <?php echo $ebt_detail_calendar_hover_color; ?> !important;}
.classNames:hover {background-color: <?php echo $ebt_detail_calendar_strip_hover_color; ?>; }
/* CSS for Class Names on Calendar view */

    span#CalendarClassName {
    
    padding-left: 5px;
	padding-right:5px;
}
.classNames {
    background-color: <?php echo $ebt_detail_calendar_strip_color; ?>;
    border-radius: 3px;
    padding-left: 2px;
    margin-bottom: 2px;
}

a.calendar-class {
    color: white !important;
}

/* Ends Here */

<?php }?>
</style>