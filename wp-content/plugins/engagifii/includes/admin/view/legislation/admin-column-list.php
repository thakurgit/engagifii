<div data-tab="settings" class="wrap legislation-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>">
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Legislation Page Settings<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>

<?php
	$obj =  new adminDataColumn();
 	$response = $obj->getLegislationColumnData();
 	$options = get_option( 'ebt_api_settings' );
    $lbt_visib_datacol_list = isset($options['lbt_visib_datacol_list']) ? $options['lbt_visib_datacol_list']: array();  
    $lbt_visib_tags_list   = $options['lbt_visib_tags_list']  ?? array();
    $lbt_visib_members_list  = $options['lbt_visib_members_list'] ?? array();
    $lbt_visib_legislative_list  = $options['lbt_visib_legislative_list'] ?? array();
    $title_display_setting = $options['lbt_title_display_setting'] ?? 'title';
    $lbt_visib_groups_list  = $options['lbt_visib_groups_list'] ?? array();
    $lbt_visib_members_tags_list = $options['lbt_visib_members_tags_list'] ?? array();
    $lbt_visib_session_list   = $options['lbt_visib_session_list']  ?? array();
	$lbt_col_order   = isset($options['lbt_col_order']) ? $options['lbt_col_order']: array();
	$sessionResponse = $obj->getSessionsData();
	$sessionResponse =json_decode($sessionResponse) ?? array();
    $tenant_code = $options['lbt_tenant_code']['tenant_code'] ?? '';
	rsort($sessionResponse);
		echo '<div class="engagifii-setting  accordion-content" style="display:none;">';
		if($options['lbt_api_url']=='' || $options['lbt_tenant_code']['tenant_code']==''){
			echo '<b style="color:red"><i>Please provide both the API URL and the Tenant Code in the API URLs section above in order to manage these page settings</i></b>';	
		} else {?>
		    <h3>Multiple Sessions</h3><hr>
<?php
if(isset($options['sessionsetting'])){
    $sessionsetting = $options['sessionsetting'];
   }else{
       $sessionsetting = null;
   }

    $session_setting = '';
    if($sessionsetting==1)
    {
         $session_setting  = 'checked';
    }
	if(count($sessionResponse)>0){
	  echo '<div style="padding-left:7px"> <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="ebt_api_settings[sessionsetting]" id="sessionsetting" value="1" '.$session_setting.'/> <label for="sessionsetting" class="form-check-label"><strong>Enable Multiple Sessions</strong></label></div><i>Note: By default, multiple session will be off.</i></div>';
	} else {
	  echo '<div style="padding-left:7px"> <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="" id="sessionsetting1" value="1" disabled/> <label for="sessionsetting1" class="form-check-label"><strong>Enable Multiple Sessions <span style="color:red"><i>(Oops! No sessions available.)</i></span></strong></label></div><i>Note: By default, multiple session will be off.</i></div>';
	}
		if($session_setting=='checked' && count($sessionResponse)>0){
				$sessionoption = 'display:block;';
			} else {
				$sessionoption = 'display:none;';
			}
		echo '<ul class="ebt-grid-column-list" id="sessionList" style="width:100%; '.$sessionoption.'">';
			foreach ($sessionResponse as $session) {
                    $checked = "";
                if(in_array($session->sessionId, $lbt_visib_session_list))
                {
                    $checked = " checked";
                }
 		echo '<li style=""> <input id="'.$session->sessionId.'" class="session-'.$session->sessionId.'" type="checkbox" name="ebt_api_settings[lbt_visib_session_list][]" '.$checked.' value='.$session->sessionId.'><label for="'.$session->sessionId.'">'.$session->sessionName.'</label></li>'		;
			}
		echo '</ul>';
		

	if(is_array ($response->columnList)){
		echo '<h3>Manage Column Visibility</h3><i>Check the columns that should be visible on the page and drag the field names to the order in which they should be displayed. Ordering is available for list views only.</i>
<hr><p><input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for columns.." class="large-text"></p>';
		echo '<input type="hidden" class="cls" name="ebt_api_settings[lbt_col_order]" value="'.$options['lbt_col_order'].'" /><ul class="ebt-grid-column-list sortable-list" id="legislationList" style="width:100%; display:block;" >';
		$counter=1;
		$legislation_columns = array();
		foreach ($lbt_visib_datacol_list as $key=>$row) {
			if(array_key_exists("key",$row)){
				array_push($legislation_columns,$row['key']);	
			}
				
		}
 		foreach ($response->columnList as $key => $row) {
 		$checked = "";
 		if(in_array($row->key, $legislation_columns)){
 			$checked .= " checked";
 		}
 		if($row->key=='title' || $row->key == 'billNumber'){
 			$checked .= " checked readonly";
 		}
 		
	 
 		echo '<li  data-order="'.$counter.'"><input id="" class="" type="hidden" name="ebt_api_settings[lbt_visib_datacol_list]['.$key.'][label]" value="'.$row->name.'"> <input id="'.$row->key.'" class="'.$row->key.'" type="checkbox" name="ebt_api_settings[lbt_visib_datacol_list]['.$key.'][key]" '.$checked.' value='.$row->key.'><label for="'.$row->key.'">'.$row->name.'</label></li>'		;
		$counter++;					  
	}

	echo '</ul>';

}
?>
		

<h3>Alternate Bill Title</h3>
<hr>
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

<h3>Filter Settings (Tags) <span> <input type="text" id="searchTag" onkeyup="searchTags()" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php $tags = $obj->legislationTagsFilter();
if($tags){
		$dataCountable=0;
		echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationTags" style="width:100%; display:block; max-height:200px; overflow:auto;">';
         $site_url = site_url();
        /* if($site_url == 'http://engagifiiweb.com')
         {
            foreach ($tags as  $tag) {
                if($tag->count > 0){
                    $checked = "";
                if(in_array($tag->tagId, $lbt_visib_tags_list))
                {
                    $checked = " checked";
                }
                echo '<li style=""> <input id="'.$tag->tagId.'" class="'.$tag->tagId.'" type="checkbox" name="ebt_api_settings[lbt_visib_tags_list][]" '.$checked.' value='.$tag->tagId.'><label for="'.$tag->tagId.'">'.$tag->text.'('.$tag->count.') </label><input type="hidden" name="ebt_api_settings[tags_list][]" value="'.$tag->tagId.'"></li>'; 
                }             
            }
         }
         else{*/
            foreach ($tags as  $tag) {
                if($tag->count > 0){
                    $checked = "";
                if(in_array($tag->tagId, $lbt_visib_tags_list))
                {
                    $checked = " checked";
                }
                echo '<li style=""> <input id="'.$tag->tagId.'" class="'.$tag->tagId.'" type="checkbox" name="ebt_api_settings[lbt_visib_tags_list][]" '.$checked.' value='.$tag->tagId.'><label for="'.$tag->tagId.'">'.$tag->text.'('.$tag->count.') </label></li>'; 
				$dataCountable++;          
                }   
            }
         //}
	if($dataCountable==0){
	  echo '<b style="color:red"><i>No any assigned tags available!</i></b>';	
	}
	echo '</ul>';		
}else{
  echo '<b style="color:red"><i>No data found!</i></b>';	
}
?>

<h3>Filter Settings (Staff Members) <span> <input type="text" id="searchMember" onkeyup="searchMembers()" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$members = $obj->legislationAssignToFilter();
		echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationMembers" style="width:100%; display:block; max-height:200px; overflow:auto;">';
		if($members){
		foreach ($members as  $member) {
	 		
 		$checked = "";
 		if(in_array($member->personId, $lbt_visib_members_list))
 		{
 			$checked .= " checked";
 		}
       // if($site_url== 'http://engagifiiweb.com'){
            if($member->count){
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$member->personId.'" class="'.$member->personId.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_list][]" '.$checked.' value='.$member->personId.'><label for="'.$member->personId.'">'.$member->fullName.'('.$member->count.')</label></li>'     ;   
            }

      //  }
		/*else  {
            echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$member->personId.'" class="'.$member->personId.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_list][]" '.$checked.' value='.$member->personId.'><label for="'.$member->personId.'">'.$member->fullName.'</label></li>'     ;   
        }*/
 					  
	}
		}else {
			echo '<b style="color:red"><i>No data found!</i></b>';	
		}
    
	echo '</ul>';		
?>
<h3>Groups <span> <input type="text" id="" onkeyup="" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$groups = $obj->legislationGroupsFilter();
	$dataCountable=0;
		echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationMembers" style="width:100%; display:block; max-height:200px; overflow:auto;">';
		if($groups){
		  foreach ($groups as  $group) {
				  
			  $checked = "";
			  if(in_array($group->value, $lbt_visib_groups_list))
			  {
				  $checked .= " checked";
			  }
			//  if($site_url== 'http://engagifiiweb.com'){
				  if($group->count){
					  echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$group->value.'" class="'.$group->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_groups_list][]" '.$checked.' value='.$group->value.'><label for="'.$group->value.'">'.$group->text.'('.$group->count.')</label></li>' ; 
					  $dataCountable++;     ;
				  }
			 // }
			 /* else
			  {
				  echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$group->value.'" class="'.$group->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_groups_list][]" '.$checked.' value='.$group->value.'><label for="'.$group->value.'">'.$group->text.'</label></li>'       ;
			  }*/
							   
		  }
		  if($dataCountable==0){
	  echo '<b style="color:red"><i>No any assigned groups available!</i></b>';	
	}
		}else {
			echo '<b style="color:red"><i>No data found!</i></b>';	
		}
	echo '</ul>';		
?>
<h3>Tags <span> <input type="text" id="" onkeyup="" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$assignTags = $obj->legislationAssignToTagFilter();
		echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationMembers" style="width:100%; display:block; max-height:200px; overflow:auto;">';
		if($assignTags){
    foreach ($assignTags as  $assign) {
            
        $checked = "";
        if(in_array($assign->value, $lbt_visib_members_tags_list))
        {
            $checked .= " checked";
        }
       // if($site_url== 'http://engagifiiweb.com'){
            if($assign->count){
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$assign->value.'" class="'.$assign->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_tags_list][]" '.$checked.' value="'.$assign->value.'"><label for="'.$assign->value.'">'.$assign->text.'('.$assign->count.')</label></li><input type="hidden" name="ebt_api_settings[member_tags_list][]" value="'.$assign->value.'"></li>'       ;
            }

       /* }  else{
            echo '<li style=""> <input id="'.$assign->value.'" class="'.$assign->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_tags_list][]" '.$checked.' value="'.$assign->value.'"><label for="'.$assign->value.'">'.$assign->text.'</label></li>'       ;
        }*/
                         
    }
		}else {
			echo '<b style="color:red"><i>No data found!</i></b>';	
		}
	echo '</ul>';		
?>
<h3>Legislative Issues <span> <input type="text" id="searchIssue" onkeyup="searchIssues()" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$tags = $obj->legislationTagsFilter();
if($tags){
		$dataCountable=0;
        echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationIssue" style="width:100%; display:block; max-height:200px; overflow:auto;">';
        foreach ($tags as  $tag) {
        
            if($tag->count > 0){
                $checked = "";
                if(in_array($tag->tagId, $lbt_visib_legislative_list))
                {
                    $checked .= " checked";
                }
                echo '<li style=""> <input id="'.$tag->tagId.'" class="'.$tag->tagId.'" type="checkbox" name="ebt_api_settings[lbt_visib_legislative_list][]" '.$checked.' value='.$tag->tagId.'><label for="'.$tag->tagId.'">'.$tag->text.' ('.$tag->count.') </label></li>'     ;                 
				$dataCountable++;          
            }
       
    }

	if($dataCountable==0){
	  echo '<b style="color:red"><i>No any assigned issues available!</i></b>';	
	}
    echo '</ul>'; 
}else {
			echo '<b style="color:red"><i>No data found!</i></b>';	
}
}
	    
?>
 
<h3 style="display:inline; margin-right:8px;">
    Manage Tabs Visibility on the Bill Detail Page
    <span style="cursor:pointer; margin-left:8px; vertical-align:middle;" id="infoTabsVisibility">
        <span class="dashicons dashicons-info" style="font-size: 18px; color: #0d6efd; vertical-align:middle;"></span>
    </span>
</h3>
<!-- Popup Modal -->
<div id="tabsVisibilityPopup" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5);">
    <div style="position:relative; width:90%; max-width:600px; margin:5% auto; background:#fff; border-radius:8px; padding:20px; box-shadow:0 2px 20px #0003;">
        <span id="closeTabsVisibilityPopup" style="position:absolute; right:15px; top:10px; font-size:22px; cursor:pointer;">&times;</span>
        <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/tabs-visibility-info.png" alt="Tabs Visibility Help" style="width:100%;max-width:550px;display:block;margin:0 auto;">
    </div>
</div>
<hr>
<?php
$legislation_tabs = [
    'summary'        => 'State Summary',    
    'versions'       => 'Versions',
    'votes'          => 'Votes',
    'history'        => 'History',
    'quick'          => 'Quick Links',  
];
if ($tenant_code != 'aasb' && $tenant_code != 'mha') {
    $legislation_tabs['staffanalysis'] = 'Staff Analysis';
}
// Show MACo Analysis tab only for tenant_code 'maco'

if ($tenant_code == 'baltimorecountymd' ||
    $tenant_code == 'princegeorgescountymd' ||
    $tenant_code == 'howardcountymd' ||
    $tenant_code == 'mcmd') {
    $legislation_tabs['macoanalysis'] = 'MACo Analysis';
}
// Get saved visible tabs for legislation module
$visible_legislation_tabs = $options['legislation_tab_visibility'] ?? array_keys($legislation_tabs);

echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationTabVisibilitySettings" style="width:100%; display:block; max-height:200px; overflow:auto;">';
foreach ($legislation_tabs as $tab_key => $tab_label) {
    $checked = in_array($tab_key, $visible_legislation_tabs) ? 'checked' : '';
    echo '<li>
        <input id="legislation_tab_' . $tab_key . '" class="' . $tab_key . '" type="checkbox" name="ebt_api_settings[legislation_tab_visibility][]" value="' . $tab_key . '" ' . $checked . '>
        <label for="legislation_tab_' . $tab_key . '">' . $tab_label . '</label>
    </li>';
}
echo '</ul>';
?>


</div>
</div>
<script type="text/javascript">

function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("legislationList");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("label")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "inline-block";
        } else {
            li[i].style.display = "none";
        }
    }
}



function searchTags() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchTag");
    filter = input.value.toUpperCase();
    ul = document.getElementById("legislationTags");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("label")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "inline-block";
        } else {
            li[i].style.display = "none";
        }
    }
}

function searchIssues() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchIssue");
    filter = input.value.toUpperCase();
    ul = document.getElementById("legislationIssue");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("label")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "inline-block";
        } else {
            li[i].style.display = "none";
        }
    }
}

function searchMembers() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchMember");
    filter = input.value.toUpperCase();
    ul = document.getElementById("legislationMembers");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("label")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "inline-block";
        } else {
            li[i].style.display = "none";
        }
    }
}

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
