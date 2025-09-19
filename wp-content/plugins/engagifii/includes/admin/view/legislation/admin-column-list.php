<?php 
// Only show legislation settings if the legislation module is enabled
if (!engagifii_should_show_module_settings('legislation')) {
    return;
}
?>
<div data-tab="settings" class="wrap legislation-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>">
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Legislation Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
<?php
    $options = get_option( 'ebt_api_settings' );
    $title_display_setting = $options['lbt_title_display_setting'] ?? 'title';
    	echo '<div class="engagifii-setting accordion-content" style="display:none;">'; 
    if($options['lbt_api_url']=='' || $options['lbt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';
		} else{ ?>
        	<!--sessions list-->
			<div class="cols-wrapper">
            <h3>Manage Multiple Sessions Visibility</h3><hr> 
            <?php
			if(isset($options['sessionsetting'])){
				$sessionsetting = $options['sessionsetting'];
			}else{
				$sessionsetting = null;
			}
			$session_setting = '';
			if($sessionsetting==1) {
			 $session_setting  = 'checked';
			}
			if($session_setting=='checked'){
				$sessionoption = 'display:block;';
			} else {
				$sessionoption = 'display:none;';
			}?>
            <div style="padding-bottom:10px"> <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="ebt_api_settings[sessionsetting]" id="sessionsetting" value="1" <?php echo $session_setting; ?> /> <label for="sessionsetting" class="form-check-label"><strong>Enable Multiple Sessions</strong></label></div><i>Note: By default, multiple session will be off.</i></div>
            <div id="sessionList" style=" <?php echo $sessionoption; ?> ">
           	 <?php renderColumnsUI('lbt_visib_session_list','legislationSessions'); ?>
            </div>
                </div>
                <!--columns list-->
			<div class="cols-wrapper" style="position:relative">
            <h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page.</i><hr> 
            <?php renderColumnsUI('lbt_visib_datacol_list','legislationList'); ?>
                </div>
                <!--Bill title-->
			<div class="cols-wrapper" style="position:relative">
            <h3>Bill Title</h3><hr> 
            <div style="width:49%; display: inline-block;">
	<input type="radio" name="ebt_api_settings[lbt_title_display_setting]" class="regular-text" value="title" <?php if($title_display_setting == 'title') { echo "checked"; } ?>> <span>Show original title only</span>
	<p><img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/title.png"></p>
</div>

<div style="width:49%; display: inline-block;">
	<input type="radio" name="ebt_api_settings[lbt_title_display_setting]" value="alternate" <?php if($title_display_setting == 'alternate') { echo "checked"; } ?>> Show alternate title only
	<p><img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/alternate.png"></p>
</div>

<div style="width:49%; display: inline-block;">
	<input type="radio" name="ebt_api_settings[lbt_title_display_setting]" value="title-top" <?php if($title_display_setting == 'title-top') { echo "checked"; } ?>> Show original title on top
	<p><img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/title-on-top.png"></p>
</div>

<div style="width:49%; display: inline-block;">
	<input type="radio" name="ebt_api_settings[lbt_title_display_setting]" value="alternate-top" <?php if($title_display_setting == 'alternate-top') { echo "checked"; } ?>> Show alternate title on top
	<p><img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/alternate-on-top.png"></p>
</div>
                </div>
                <!--Tags-->
			<div class="cols-wrapper" style="position:relative">
            <h3>Manage Tags Visibility</h3><i>Check the columns that should be visible on the page.</i><hr> 
            <?php renderColumnsUI('lbt_visib_legislative_list','legislationTags'); ?>
                </div>
                <!--Staff Members-->
                <div class="cols-wrapper" style="position:relative">
            <h3>Manage Staff Members Visibility</h3><i>Check the columns that should be visible on the page.</i><hr> 
            <?php renderColumnsUI('lbt_visib_members_list','legislationMembers'); ?>
                </div>
                <!--Groups-->
                <div class="cols-wrapper" style="position:relative">
            <h3>Manage Groups Visibility</h3><i>Check the columns that should be visible on the page.</i><hr> 
            <?php renderColumnsUI('lbt_visib_groups_list','legislationGroups'); ?>
                </div>
               <!-- Member Tags-->
                <div class="cols-wrapper" style="position:relative">
            <h3>Manage Member Tags Visibility</h3><i>Check the columns that should be visible on the page.</i><hr> 
            <?php renderColumnsUI('lbt_visib_members_tags_list','legislationMemberTags'); ?>
                </div>
                <!-- Member tabs-->
                <div class="cols-wrapper" style="position:relative">
            <h3>Manage Member Tabs Visibility (Bill Details page) <button id="infoTabsVisibility" style="background:none; border:none !important; box-shadow:none !important" class="button" type="button"><span class="dashicons dashicons-info"></span></button></h3><i>Check the columns that should be visible on the page.</i><hr>
            <!-- Popup Modal -->
<div id="tabsVisibilityPopup" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5);">
    <div style="position:relative; width:90%; max-width:600px; margin:5% auto; background:#fff; border-radius:8px; padding:20px; box-shadow:0 2px 20px #0003;">
        <span id="closeTabsVisibilityPopup" style="position:absolute; right:15px; top:10px; font-size:22px; cursor:pointer;">&times;</span>
        <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/tabs-visibility-info.png" alt="Tabs Visibility Help" style="width:100%;max-width:550px;display:block;margin:0 auto;">
    </div>
</div> 
            <?php renderColumnsUI('legislation_tab_visibility','legislationTabs'); ?>
                </div>
    	<?php
	}
		echo '</div>';				
?>

</div>
<script type="text/javascript">
jQuery('#sessionsetting').change(function(){
	if(jQuery(this).is(':checked')){
		jQuery('#sessionList').show();
	} else {
		jQuery('#sessionList').hide();
	}
});
document.getElementById('infoTabsVisibility').onclick = function() {
    document.getElementById('tabsVisibilityPopup').style.display = 'block';
};
document.getElementById('closeTabsVisibilityPopup').onclick = function() {
    document.getElementById('tabsVisibilityPopup').style.display = 'none';
};
// Optional: close popup when clicking outside the modal content
document.getElementById('tabsVisibilityPopup').onclick = function(e) {
    if(e.target === this) this.style.display = 'none';
};

</script>
