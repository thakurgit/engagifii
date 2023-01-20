<div class="wrap legislation-column <?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>">
<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">Legislation Columns Visibility<i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>

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
	$sessionResponse = $obj->getSessionsData();
	$sessionResponses = json_decode($sessionResponse['api_response']);
	//rsort($sessionResponses)
		echo '<div class="engagifii-setting  accordion-content" style="display:none;">'; ?>
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
echo '<div style="padding-left:7px"> <input type="checkbox" name="ebt_api_settings[sessionsetting]" id="sessionsetting" value="1" '.$session_setting.'/> <label for="sessionsetting"><strong>Enable Multiple Sessions</strong></label><br><i>Note:- By default, multiple session will be off.</i></div>';
		if($session_setting=='checked'){
				$sessionoption = 'display:block;';
			} else {
				$sessionoption = 'display:none;';
			}
		echo '<ul class="ebt-grid-column-list" id="sessionList" style="width:100%; '.$sessionoption.'">';
			foreach ($sessionResponses as $session) {
                    $checked = "";
                if(in_array($session->sessionId, $lbt_visib_session_list))
                {
                    $checked = " checked";
                }
 		echo '<li style="width:31%; display:inline-block;"> <input id="'.$session->sessionId.'" class="session-'.$session->sessionId.'" type="checkbox" name="ebt_api_settings[lbt_visib_session_list][]" '.$checked.' value='.$session->sessionId.'><label for="'.$session->sessionId.'">'.$session->sessionName.'</label></li>'		;
			}
		echo '</ul>';
		

	if(is_array ($response->columnList)){
		echo '<h3>Columns visibility</h3>
<hr><p><input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for columns.." class="large-text"></p>';
		echo '<ul class="ebt-grid-column-list" id="legislationList" style="width:100%; display:block;">';
		$counter=0;
 
 		foreach ($response->columnList as $key => $row) {

 		$checked = "";
 		if(in_array($row->key, $lbt_visib_datacol_list))
 		{
 			$checked .= " checked";
 		}
 		if($row->key=='title' || $row->key == 'billNumber')
 		{
 			$checked .= " checked readonly";
 		}
 		
	 
 		echo '<li style="width:31%; display:inline-block;"> <input id="'.$row->key.'" class="'.$row->key.'" type="checkbox" name="ebt_api_settings[lbt_visib_datacol_list][]" '.$checked.' value='.$row->key.'><label for="'.$row->key.'">'.$row->name.'</label></li>'		;
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

<h3>Tags for filters <span> <input type="text" id="searchTag" onkeyup="searchTags()" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$tags = $obj->legislationTagsFilter();
		echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationTags" style="width:100%; display:block; height:200px; overflow:auto;">';
         $site_url = site_url();
         if($site_url == 'http://engagifiiweb.com')
         {
            foreach ($tags as  $tag) {
                if($tag->count > 0){
                    $checked = "";
                if(in_array($tag->tagId, $lbt_visib_tags_list))
                {
                    $checked = " checked";
                }
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$tag->tagId.'" class="'.$tag->tagId.'" type="checkbox" name="ebt_api_settings[lbt_visib_tags_list][]" '.$checked.' value='.$tag->tagId.'><label for="'.$tag->tagId.'">'.$tag->text.'('.$tag->count.') </label><input type="hidden" name="ebt_api_settings[tags_list][]" value="'.$tag->tagId.'"></li>'; 
                }             
            }
         }
         else
         {
            foreach ($tags as  $tag) {
                if($tag->count > 0){
                    $checked = "";
                if(in_array($tag->tagId, $lbt_visib_tags_list))
                {
                    $checked = " checked";
                }
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$tag->tagId.'" class="'.$tag->tagId.'" type="checkbox" name="ebt_api_settings[lbt_visib_tags_list][]" '.$checked.' value='.$tag->tagId.'><label for="'.$tag->tagId.'">'.$tag->text.'('.$tag->count.') </label></li>'; 
                }             
            }
         }
		

	echo '</ul>';		
?>

<h3>Staff members for filters <span> <input type="text" id="searchMember" onkeyup="searchMembers()" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$members = $obj->legislationAssignToFilter();

$groups = $obj->legislationGroupsFilter();
$assignTags = $obj->legislationAssignToTagFilter();
		echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationMembers" style="width:100%; display:block; height:200px; overflow:auto;">';
		foreach ($members as  $member) {
	 		
 		$checked = "";
 		if(in_array($member->personId, $lbt_visib_members_list))
 		{
 			$checked .= " checked";
 		}
        if($site_url== 'http://engagifiiweb.com'){
            if($member->count){
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$member->personId.'" class="'.$member->personId.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_list][]" '.$checked.' value='.$member->personId.'><label for="'.$member->personId.'">'.$member->fullName.'('.$member->count.')</label></li><input type="hidden" name="ebt_api_settings[members_list][]" value="'.$member->personId.'"></li>'     ;   
            }

        }else
        {
            echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$member->personId.'" class="'.$member->personId.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_list][]" '.$checked.' value='.$member->personId.'><label for="'.$member->personId.'">'.$member->fullName.'</label></li>'     ;   
        }
 					  
	}
    echo "<h3>Groups</h3>";

    foreach ($groups as  $group) {
            
        $checked = "";
        if(in_array($group->value, $lbt_visib_groups_list))
        {
            $checked .= " checked";
        }
        if($site_url== 'http://engagifiiweb.com'){
            if($group->count){
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$group->value.'" class="'.$group->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_groups_list][]" '.$checked.' value='.$group->value.'><label for="'.$group->value.'">'.$group->text.'('.$group->count.')</label></li><input type="hidden" name="ebt_api_settings[groups_list][]" value="'.$group->value.'"></li>'       ;
            }
        }
        else
        {
            echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$group->value.'" class="'.$group->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_groups_list][]" '.$checked.' value='.$group->value.'><label for="'.$group->value.'">'.$group->text.'</label></li>'       ;
        }
                         
    }

    echo "<h3>Tags</h3>";
    foreach ($assignTags as  $assign) {
            
        $checked = "";
        if(in_array($assign->value, $lbt_visib_members_tags_list))
        {
            $checked .= " checked";
        }
        if($site_url== 'http://engagifiiweb.com'){
            if($assign->count){
                echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$assign->value.'" class="'.$assign->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_tags_list][]" '.$checked.' value="'.$assign->value.'"><label for="'.$assign->value.'">'.$assign->text.'('.$assign->count.')</label></li><input type="hidden" name="ebt_api_settings[member_tags_list][]" value="'.$assign->value.'"></li>'       ;
            }

        }
        else
        {
            echo '<li style="width:31%; display:inline-block;word-break:break-word;"> <input id="'.$assign->value.'" class="'.$assign->value.'" type="checkbox" name="ebt_api_settings[lbt_visib_members_tags_list][]" '.$checked.' value="'.$assign->value.'"><label for="'.$assign->value.'">'.$assign->text.'</label></li>'       ;
        }
                         
    }
	echo '</ul>';		
?>

<h3>Legislative issue filters <span> <input type="text" id="searchIssue" onkeyup="searchIssues()" placeholder="Search..." class="regular-text"></span></h3>
<hr>
<?php
$tags = $obj->legislationTagsFilter();
        echo '<ul class="ebt-grid-column-list tz-dropdown-filter" id="legislationIssue" style="width:100%; display:block; height:200px; overflow:auto;">';
        foreach ($tags as  $tag) {
        
            if($tag->count > 0){
                $checked = "";
                if(in_array($tag->tagId, $lbt_visib_legislative_list))
                {
                    $checked .= " checked";
                }
                echo '<li style="width:31%; display:inline-block; word-break:break-word;"> <input id="'.$tag->tagId.'" class="'.$tag->tagId.'" type="checkbox" name="ebt_api_settings[lbt_visib_legislative_list][]" '.$checked.' value='.$tag->tagId.'><label for="'.$tag->tagId.'">'.$tag->text.' ('.$tag->count.') </label></li><input type="hidden" name="ebt_api_settings[legislative_tags_list][]" value="'.$tag->tagId.'"></li>'     ;                 
            }
       
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
</script>
