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
$table_heading_bg   = ( isset( $settings['ebt_table_bg_color'] ) && $settings['ebt_table_bg_color'] != '' ) ? $settings['ebt_table_bg_color'] : '';
$table_heading_color   = ( isset( $settings['ebt_table_thead_color'] ) && $settings['ebt_table_thead_color'] != '' ) ? $settings['ebt_table_thead_color'] : '';
$table_heading_fsize   = ( isset( $settings['ebt_table_thead_fontsize'] ) && $settings['ebt_table_thead_fontsize'] != '' ) ? $settings['ebt_table_thead_fontsize'] : '';
$ebt_table_tbody_color   = ( isset( $settings['ebt_table_tbody_color'] ) && $settings['ebt_table_tbody_color'] != '' ) ? $settings['ebt_table_tbody_color'] : '';
$ebt_table_tbody_fontsize   = ( isset( $settings['ebt_table_tbody_fontsize'] ) && $settings['ebt_table_tbody_fontsize'] != '' ) ? $settings['ebt_table_tbody_fontsize'] : '';
$ebt_table_link_color   = ( isset( $settings['ebt_table_link_color'] ) && $settings['ebt_table_link_color'] != '' ) ? $settings['ebt_table_link_color'] : '';
$ebt_table_link_hover_color   = ( isset( $settings['ebt_table_link_hover_color'] ) && $settings['ebt_table_link_hover_color'] != '' ) ? $settings['ebt_table_link_hover_color'] : '';
$ebt_detail_calendar_hover_color         = $settings['ebt_detail_calendar_hover_color'] ?? '';
$ebt_detail_calendar_strip_color         = $settings['ebt_detail_calendar_strip_color'] ?? '';
$ebt_detail_calendar_strip_hover_color         = $settings['ebt_detail_calendar_strip_hover_color'] ?? '';
$engagifii_theme_color   = ( isset( $settings['engagifii_theme_color'] ) && $settings['engagifii_theme_color'] != '' ) ? $settings['engagifii_theme_color'] : '';
function darken_color($rgb, $darker=2) {

	$hash = (strpos($rgb, '#') !== false) ? '#' : '';
	$rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
	if(strlen($rgb) != 6) return $hash.'000000';
	$darker = ($darker > 1) ? $darker : 1;

	list($R16,$G16,$B16) = str_split($rgb,2);

	$R = sprintf("%02X", floor(hexdec($R16)/$darker));
	$G = sprintf("%02X", floor(hexdec($G16)/$darker));
	$B = sprintf("%02X", floor(hexdec($B16)/$darker));

	return $hash.$R.$G.$B;
}
?>
<style>
<?php if(isset($activate_css)){
	if($engagifii_theme_color){
?>
:root{
--engagifii-color:<?php echo $engagifii_theme_color;?>;
--engagifii-color-hover:<?php echo darken_color($engagifii_theme_color, $darker=1.5);?>;
}
<?php } ?>
#ebtmaintable_wrapper .dataTable thead tr>th,  .light-background thead>tr>th,  .engagifii-box .prv, .engagifii-box .nxt{ 
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
}
<?php /*?>.filter-btn-tz{
	background: <?php echo $table_heading_bg;?> !important;
	border: <?php echo $table_heading_bg;?> !important;
	box-shadow: none;
	color: <?php echo $table_heading_color?> !important;
}<?php */?>
.engagifii-box table.table thead th{border-bottom: none;}
#ebtmaintable thead>tr>th,  .light-background thead>tr>th{
	color:<?php echo $table_heading_color?>;
	font-size: <?php echo $table_heading_fsize?>px;
}
#ebtmaintable.table-bordered.dataTable tbody td{
	font-size: <?php echo $ebt_table_tbody_fontsize?>px;
    color: <?php echo $ebt_table_tbody_color?>;
}
#ebtmaintable a{
	color: <?php echo $ebt_table_link_color?>!important;
}
#ebtmaintable a:hover {
	color: <?php echo $ebt_table_link_hover_color?>!important;
}
.calendar__day.active{background-color: <?php echo $ebt_detail_calendar_hover_color; ?> !important;}
.classNames > a {
    background-color: <?php echo $ebt_detail_calendar_strip_color; ?> !important;
}
.classNames > a:hover, .classNames > a:focus {background-color: <?php echo $ebt_detail_calendar_strip_hover_color; ?>!important;; }
.page-link, a, .btn-link {
	color: var(--engagifii-color);
}
.page-item.active .page-link , .btn-primary, .btn-primary.disabled, .btn-primary:disabled {
	background-color: var(--engagifii-color);
	border-color: var(--engagifii-color);
} 
.btn-primary:hover, .btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active, .show > .btn-primary.dropdown-toggle {
	background-color: var(--engagifii-color-hover);
	border-color: var(--engagifii-color-hover);
}
.text-primary {
	color:var(--engagifii-color) !important;
}
a:hover, .btn-link:hover, .dropdown-item:hover, .dropdown-item:focus {
	color: var(--engagifii-color-hover);
}
.bg-primary{
	background-color: var(--engagifii-color) !important;
}
<?php }?>
</style>