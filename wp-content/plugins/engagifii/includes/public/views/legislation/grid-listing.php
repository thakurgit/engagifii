<?php

$actionType = '';
$billnumber = '';
$tagsRequest = '';
$staffMember = '';
$get_tracking = '';
if (isset($_REQUEST['tracking']))
{
   $get_tracking = $_REQUEST['tracking'];
   $key_array = array_keys($_GET);
   echo '<h2 class="text-center">'.str_replace("_", " ", base64_decode($key_array[1])).' bills </h2>';
}

if (isset($_REQUEST['actionType']))
{
    $actionType = $_REQUEST['actionType'];
	 echo '<h2 class="text-center">Search related to action type '.$actionType.' </h2>';
}

if (isset($_REQUEST['bill']))
{
    $billnumber = $_REQUEST['bill'];
    echo '<h2 class="text-center">Search related to bill number '.$billnumber.' </h2>';
}

if (isset($_REQUEST['tag']))
{
    $tagsRequest = $_REQUEST['tag'];
    $key_array = array_keys($_GET);
    echo '<h2 class="text-center">Bills associated with '.str_replace("_", " ", base64_decode($key_array[1])).' </h2>';
}

if (isset($_REQUEST['member']))
{
    $staffMember = $_REQUEST['member'];
    $key_array = array_keys($_GET);
    echo '<h2 class="text-center">Bills assigned to '.str_replace("_", " ", base64_decode($key_array[1])).' </h2>';
}

if (isset($_REQUEST['groups']))
{
    $staffMemberGroup = $_REQUEST['groups'];
    $key_array = array_keys($_GET);
    echo '<h2 class="text-center">Bills assigned to '.str_replace("_", " ", base64_decode($key_array[1])).'</h2>';
}
if (isset($_REQUEST['membertags']))
{
    $staffMemberTags = $_REQUEST['membertags'];
    $key_array = array_keys($_GET);
    echo '<h2 class="text-center">Bills assigned to '.str_replace("_", " ", base64_decode($key_array[1])).'</h2>';
}
if (isset($_REQUEST['sessionId']))
{
  $key_array = array_keys($_GET);
  echo '<h2 class="text-center"><span class="h5 sessionname d-inline-block"></span></h2>';
}


$obj = new Engagifii_API();
$dataResponse = $obj->submitApiRequest("legislative/public-bills/column-list", array() , "GET", 'legislation');
$collection = json_decode($dataResponse['api_response']);
if(!$collection){
	echo '<h5 class="text-center text-danger"><strong><em>No data found! Please contact website admin.</em></strong><h5>';
	return;
}
$options = get_option('ebt_api_settings');
$lbt_visib_datacol_list = $options['lbt_visib_datacol_list'];
$lbt_visib_tags_list = $options['lbt_visib_tags_list']??array();
$lbt_visib_members_list = $options['lbt_visib_members_list']??array();
$lbt_visib_groups_list   = $options['lbt_visib_groups_list'] ?? array();
$lbt_visib_members_tags_list = $options['lbt_visib_members_tags_list'] ?? array();

$lbt_visible_column_list = $options['lbt_visib_datacol_list'];

$trackingResponse = $obj->getTrackingLevels();
$trackingResponses = json_decode($trackingResponse['api_response']);
//$countResponse = count($trackingResponses);
$i = 0;

/* House Committee */
$houseResponse = $obj->houseCommiittee();
$houseResponses = json_decode($houseResponse['api_response']);

/* Senate Committee */
$senateResponse = $obj->senateCommiittee();
$senateResponses = json_decode($senateResponse['api_response']);
/* Sponsors */
$sponsorResponse = $obj->sponsorList();
$sponsorResponses = json_decode($sponsorResponse['api_response']);

/* Bill Type */
$billResponse = $obj->billType();
$billResponses = json_decode($billResponse['api_response']);

/* Last Action */
$lastResponse = $obj->lastAction();
$lastResponses = json_decode($lastResponse['api_response']);

/* Status */
$statusResponse = $obj->statusFilter();
$statusResponses = json_decode($statusResponse['api_response']);

/* tags list for filters */
$tags = $obj->legislationTagsFilter();

/*assign to fietrs */
$assignto = $obj->legislationAssignToFilter();
$assigntoGroups = $obj->legislationGroupsFilter();
$assignToTags = $obj->legislationAssignToTagFilter();

?>


<div class="container-fluid pb-4">
<span class="total-bill-text"></span>
<p class="lead text-center"><span class="bill-count"></span></p>
<div class="row rowEngagii tz-Engagii-flex">
<div class="col-12 text-center text-lg-right d-flex align-items-center justify-content-end">
   <div class="one-coloumnsEngagii"> 
      <input type="hidden" name="enga_custom_multi_filter" id="enga_custom_multi_filter" value="">
      <div class="containerEngagii filter-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative bg-light border">
         <i class="far fa-filter click-filter"></i>
         <span class="d-flex align-items-center justify-content-center rounded-circle text-white bg-danger position-absolute"></span>
      </div>

      <div class="filter-border">
      <div class="filter-area d-none">
         <div class="Engagiirow filter-top-bg col-sm-12 py-2 bg-dark text-white">
         <div class="row">
            <div class="col-6">
               <span class="filter-title"> <i class="far fa-filter mr-2"></i>Filter <span id="blockedchecked" class="font-weight-bold ml-1"></span> </span>
            </div>
           
            <div class="col-6 text-right">
               <span class="clear-all" id="clear-all"><i class="fal fa-sync"></i> </span>
            </div>
            </div>
         </div>

         <div class="Engagiirow">
            <div class="col-sm-12 tz-areafix">
               <div class="filter-section">
               <!-- Tags -->
               <?php  if(in_array('assignedto', $lbt_visible_column_list)) { ?>
                  <div class="filter-list border-bottom">
                     <div class="heading-title py-2 d-flex align-items-center">Assign To <span id="countviewbyassign" class="font-weight-bold ml-1"></span><i class="far fa-angle-down ml-auto"> </i></div>
                     <div class="multiple-select">
                        <div class="input-box">
                           <label class="d-none" for="searchbyassignto">Search</label>
                           <input type="text" class="form-control mb-2" name="searchbyassignto" id="searchbyassignto" data-search-container="searchbyassignto" value="" placeholder="Type to search..." />
                        </div>
                        <div class="list-box">
                           <ul class="searchbyassignto tz-dropdown-filter list-unstyled" >
                           <?php if(count($lbt_visib_members_list) > 0) {
                              foreach ($assignto as $assign){
                                 if (in_array($assign->personId, $lbt_visib_members_list)){
                           ?>
                                    <li data-title="<?php echo $assign->fullName; ?>" data-id="<?php echo $assign->personId; ?>">
                                       <label class="d-none" for="item_id_<?php echo $assign->personId; ?>">Assign to</label>
                                       <input type="checkbox" name="enggafifilterdata[]" data-type="members" value="<?php echo $assign->personId; ?>" id="item_id_<?php echo $assign->personId; ?>" >
                                       <?php echo $assign->fullName; ?>
                                    </li>
                           <?php    
                              } } ?>

                           <?php foreach ($assigntoGroups as $assign){
                                 if (in_array($assign->value, $lbt_visib_groups_list)){
                           ?>
                                 <li data-title="<?php echo $assign->text; ?>" data-id="<?php echo $assign->value; ?>">
                                    <label class="d-none" for="item_id_<?php echo $assign->value; ?>">Assign to</label>
                                    <input type="checkbox" name="enggafifilterdata[]" data-type="groups" value="<?php echo $assign->value; ?>" id="item_id_<?php echo $assign->value; ?>" >
                                    <?php echo $assign->text; ?>
                                 </li>
                           <?php }} ?>

                           <?php 
                              foreach ($assignToTags as $assign){
                                 if (in_array($assign->value, $lbt_visib_members_tags_list)){
                           ?>
                                    <li data-title="<?php echo $assign->text; ?>" data-id="<?php echo $assign->value; ?>">
                                       <label class="d-none" for="item_id_<?php echo $assign->value; ?>">Assign to</label>
                                       <input type="checkbox" name="enggafifilterdata[]" data-type="tags" value="<?php echo $assign->value; ?>" id="item_id_<?php echo $assign->value; ?>" >
                                       <?php echo $assign->text; ?>
                                    </li>
                                 <?php } } 
								 } else {
									echo '<em>No data found!!</em>'; 
								 }?>
                           </ul>
                        </div>
                     </div>
                  </div>
<?php } ?>
          <!-- Bill Types -->
  <?php  if(in_array('billType', $lbt_visible_column_list)) { ?>
      <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Bill Types <span id="countviewbybilltypes" class="font-weight-bold ml-1"></span><i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbybilltypes">Search</label>
      <input type="text" class="form-control" name="searchbybilltypes" id="searchbybilltypes" data-search-container="searchbybilltypes" value=""  placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbybilltypes tz-dropdown-filter list-unstyled" >
      <?php foreach ($billResponses as $bill)
{ ?>
        <li data-title="<?php echo $bill->text; ?>" data-id="<?php echo $bill->value; ?>">
          <label class="d-none" for="item_id_<?php echo $bill->value; ?>">bill type</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $bill->value; ?>" id="item_id_<?php echo $bill->value; ?>" >
        <?php echo $bill->text; ?>
        </li>
        <?php
} ?>
      </ul>
      </div>
    </div>
    </div>
<?php } ?>
<?php  if(in_array('houseCommittees', $lbt_visible_column_list)) { ?>
    <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">House Committee <span id="countviewhousecommittee" class="font-weight-bold ml-1"></span> <i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbyhousecommittee">Search</label>
      <input type="text" class="form-control" name="searchbyhousecommittee" id="searchbyhousecommittee" data-search-container="searchbyhousecommittee" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbyhousecommittee tz-dropdown-filter list-unstyled" >
        <?php foreach ($houseResponses as $house)
{ ?>
        <li data-title="<?php echo $house->text; ?>" data-id="<?php echo $house->value; ?>">
          <label class="d-none" for="item_id_<?php echo $house->value; ?>">house committee</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $house->value; ?>" id="item_id_<?php echo $house->value; ?>" >
        <?php echo $house->text.' '.'('.$house->count.')'; ?>
        </li>
        <?php
} ?>
      </ul>
      </div>

    </div>
    </div>
<?php } ?>


     <!-- Last Action Date  -->
     <?php  if(in_array('lastActionOn', $lbt_visible_column_list)) { ?> 
     <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Last Action Date  <i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select mb-2">
      <div class="row">
                <div class="col-6">
                  <label class="date-label" for ="datepicker-start"> Start Date: </label> 
                  <div class="date-design position-relative">
                  <input class="form-control form-control-sm input-xs small-css" placeholder="MM/DD/YYYY" type="text" id="datepicker-start">
                  <i class="fal fa-calendar-alt custom-calendar"> </i>
                  </div>
                </div>

                <div class="col-6">
                  <label class="date-label" for="datepicker-end"> End Date: </label>
                  <div class="date-design position-relative">
                  <input class="form-control form-control-sm input-xs small-css" placeholder="MM/DD/YYYY" type="text" id="datepicker-end">
                  <i class="fal fa-calendar-alt custom-calendar"> </i>
                    </div>
                </div>
              </div>
    </div>
    </div>
<?php } ?>
    <!-- Last Action Types -->
     
    <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Last Action Types <span id="countviewbylastactiontypes" class="font-weight-bold ml-1"></span> <i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbylastactiontypes">Search</label>
      <input type="text" class="form-control" name="searchbylastactiontypes" id="searchbylastactiontypes" data-search-container="searchbylastactiontypes" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbylastactiontypes tz-dropdown-filter list-unstyled" >
      <?php foreach ($lastResponses as $last)
{ ?>
        <li data-title="<?php echo $last->text; ?>" data-id="<?php echo $last->value; ?>"  <?php if ($actionType == $last->value)
    { ?> class="liactive" <?php
    } ?>>
          <label class="d-none" for="item_id_<?php echo $last->value; ?>">Action type</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $last->value; ?>" id="item_id_<?php echo $last->value; ?>" <?php if ($actionType == $last->value)
    {
        echo "checked=true disabled";
    } ?>>
        <?php echo $last->text; ?>
        </li>
        <?php
} ?>
      </ul>
      </div>

    </div>
    </div>

    <!-- Senate Committee -->
    <?php  if(in_array('senateCommittees', $lbt_visible_column_list)) { ?> 
    <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Senate Committee <span id="countviewsenatecommittee" class="font-weight-bold ml-1"></span><i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbysenatecommittee">Search</label>
      <input type="text" class="form-control" name="searchbysenatecommittee" id="searchbysenatecommittee" data-search-container="searchbysenatecommittee" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbysenatecommittee tz-dropdown-filter list-unstyled" >
      <?php foreach ($senateResponses as $senate)
{ ?>
        <li data-title="<?php echo $senate->text; ?>" data-id="<?php echo $senate->value; ?>">
          <label class="d-none" for="item_id_<?php echo $senate->value; ?>">Senate Committee</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $senate->value; ?>" id="item_id_<?php echo $senate->value; ?>" >
        <?php echo $senate->text.' '.'('.$senate->count.')'; ?>
        </li>
        <?php
} ?>
      </ul>
      </div>

    </div>
    </div>
<?php } ?>

    <!-- Sponsors -->
    <?php  if(in_array('sponsors', $lbt_visible_column_list)) { ?> 
      <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Sponsors <span id="countviewbysponsors" class="font-weight-bold ml-1"></span><i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbysponsors">Search</label>
      <input type="text" class="form-control" name="searchbysponsors" id="searchbysponsors" data-search-container="searchbysponsors" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbysponsors tz-dropdown-filter list-unstyled" >
      <?php foreach ($sponsorResponses as $sponsors){ ?>
        <li data-title="<?php echo $sponsors->text; ?>" data-id="<?php echo $sponsors->value; ?>">
          <label class="d-none" for="sponsors_item_id_<?php echo $sponsors->value; ?>">Sponsors</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $sponsors->value; ?>" id="sponsors_item_id_<?php echo $sponsors->value; ?>" >
        <?php echo $sponsors->text; ?>
        </li>
        <?php } ?>
      </ul>
      </div>

    </div>
    </div>
<?php } ?>

      <!-- Tags -->
      <?php  if(in_array('tags', $lbt_visible_column_list)) { ?>   
      <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Tags <span id="countviewbytags" class="font-weight-bold ml-1"></span><i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbytags">Search</label>
      <input type="text" class="form-control" name="searchbytags" id="searchbytags" data-search-container="searchbytags" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbytags tz-dropdown-filter list-unstyled" >
      <?php foreach ($tags as $tag){
            if (in_array($tag->tagId, $lbt_visib_tags_list)){ ?>
              <li data-title="<?php echo $tag->text; ?>" data-id="<?php echo $tag->tagId; ?>">
              <label class="d-none" for="item_id_<?php echo $tag->tagId; ?>">Tags</label>
              <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $tag->tagId; ?>" id="item_id_<?php echo $tag->tagId; ?>" <?php if ($tagsRequest == $tag->tagId)
              {
                  echo "checked  disabled";
              } ?> >
              <?php echo $tag->text; ?>
              </li>
              <?php
              }
            } ?>
      </ul>
      </div>

    </div>
    </div>
<?php } ?>

    <!-- Tracking Lavels -->
    <!-- <?php  if(in_array('trackingLevel', $lbt_visible_column_list)) { ?>    -->
    <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Tracking Levels <span id="countviewbytrackinglevels" class="font-weight-bold ml-1"></span> <i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select">
      <div class="input-box">
        <label class="d-none" for="searchbytrackinglevels">Search</label>
      <input type="text" class="form-control" name="searchbytrackinglevels" id="searchbytrackinglevels" data-search-container="searchbytrackinglevels" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbytrackinglevels tz-dropdown-filter list-unstyled" >
      <?php 
      $is_tracking = 0;
      foreach ($trackingResponses as $tracking){
            if($tracking->count > 0) { 
              $is_tracking = 1;
        ?>
              <li data-title="<?php echo $tracking->title; ?>" data-id="<?php echo $tracking->value; ?>">
          <label class="d-none" for="tracking_item_id_<?php echo $tracking->trackingLevelId; ?>">Tracking levels</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $tracking->trackingLevelId; ?>" id="tracking_item_id_<?php echo $tracking->trackingLevelId; ?>"  <?php if($tracking->trackingLevelId == $get_tracking) echo "checked disabled"; ?>>
        <span style="background-color:<?php echo $tracking->colorCode; ?>; width: 13px;height: 13px;border-radius: 50%;display: inline-block;margin-left: 8px;"></span>
        <?php echo $tracking->title; ?>
        <?php
            }
        }
      ?>
      </ul>
      </div>

    </div>
    </div>
<!-- <?php } ?> -->

    <!-- Status types -->
    <?php  if(in_array('status', $lbt_visible_column_list)) { ?>  
    <div class="filter-list border-bottom">
      <div class="heading-title py-2 d-flex align-items-center">Status <span id="countviewbystatustypes" class="font-weight-bold ml-1"></span> <i class="far fa-angle-down ml-auto"> </i></div>
    <div class="multiple-select"> 
      <div class="input-box">
        <label class="d-none" for="searchbystatustypes">Search</label>
      <input type="text" class="form-control" name="searchbystatustypes" id="searchbystatustypes" data-search-container="searchbystatustypes" value="" placeholder="Type to search..." />
      </div>
      <div class="list-box">
      <ul class="searchbystatustypes tz-dropdown-filter list-unstyled" >
      <?php foreach ($statusResponses as $status)
{ ?>
        <li data-title="<?php echo $status->text; ?>" data-id="<?php echo $status->value; ?>">
          <label class="d-none" for="status_item_id_<?php echo $status->value; ?>">Status</label>
        <input type="checkbox" name="enggafifilterdata[]" value="<?php echo $status->value; ?>" id="status_item_id_<?php echo $status->value; ?>" >
        <?php echo $status->text; ?>
        </li>
        <?php
} ?>
      </ul>
      </div>

    </div>
    </div>
    </div>
    <?php } ?>
    </div>
    <div class="col-sm-12 tz-areafix">
    <div class="apply-filter">
      <input type="hidden" name="isapplyactive" id="isapplyactive" value="0" >
      <button class="btn btn-primary btn-sm text-white filter-btn-tz" type="button" name="callmasterApi" id="apply-filter-data">Apply <span id="countFilterResult"></span></button>

    </div>
    </div>
   </div>
  </div>
   </div>
</div>
</div>
</div>
</div>

<script>


var trackingLevels=[];
var sponsors=[];
var houseCommittees=[];
var senateCommittees=[];
var lastActionTypes=[];
var billTypes=[];
var statusTypes=[];
var tags = [];
var assignedto  = [];
var assignGroups = [];
var assignTags = [];
var startDate=null;
var endDate=null;


function counFilterBlock()
{
   var updateCount = document.getElementById("blockedchecked");
   var elements = window.document.getElementsByClassName("tzsearchinput");
   var totalCount= 0;
   for (var n = 0; n < elements.length; ++n) {
      var element = elements[n];  
      var data = element.dataset;    
      var selectedBlockCount=element.dataset.selectedblock;
      if(selectedBlockCount)
      {
         totalCount = parseInt(totalCount)+parseInt(selectedBlockCount);
      }
   }  
   if(updateCount)
   {    
      updateCount.innerHTML= "(" +totalCount + ")";
   }
}

function getCheckedHouseCommitteValues()
{
  <?php
    if(count($houseResponses) > 0){
  ?>
  var elements = window.document.getElementsByClassName("searchbyhousecommittee");  
  houseCommittees=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
    if (checkbox.checked == true){      
      var chkval = checkbox.value;
      houseCommittees.push(chkval);
    }  
  }
  <?php
    }
  ?>  

}

function getCheckedSenateCommitteValues()
{

  <?php
     if(count($senateResponses)){
  ?>
  var elements = window.document.getElementsByClassName("searchbysenatecommittee");  
  senateCommittees=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
    if (checkbox.checked == true){      
      var chkval = checkbox.value;
      senateCommittees.push(chkval);
    }  
  }
  <?php
    }
  ?>

}


function getCheckedSponsersValues()
{
  var elements = window.document.getElementsByClassName("searchbysponsors");  
  sponsors=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
    if (checkbox.checked == true){      
      var chkval = checkbox.value;
      sponsors.push(chkval);
    }  
  }

}

function getCheckedtagsValues()
{
  <?php
    if(count($lbt_visib_tags_list) > 0){
  ?>
  var elements = window.document.getElementsByClassName("searchbytags");  
  tags=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
    if (checkbox.checked == true){      
      var chkval = checkbox.value;
      tags.push(chkval);
    }  
  }
  <?php
    }
  ?>

}


function getcheckedassignValues()
{
  var elements = window.document.getElementsByClassName("searchbyassignto");  
  assignedto=[];
  assignGroups = [];
  assignTags = [];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
	if(checkbox){
    if (checkbox.checked == true){      
      var chkval = checkbox.value;
      if(checkbox.dataset.type == "members")
        assignedto.push(chkval);

      if(checkbox.dataset.type == "groups")
        assignGroups.push(chkval);

      if(checkbox.dataset.type == "tags")
        assignTags.push(chkval);
    }  
	}
  }

}
function getCheckedBillTypesValues()
{
  var elements = window.document.getElementsByClassName("searchbybilltypes");  
  billTypes=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
	if(checkbox){
    if ( checkbox.checked == true){      
      var chkval = checkbox.value;
      billTypes.push(chkval);
    }  
	}
  }
}



function getCheckedLastActionTypesValues()
{

  var elements = window.document.getElementsByClassName("searchbylastactiontypes"); 

  lastActionTypes=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
if(checkbox){
    if (checkbox.checked == true){      
      var chkval = checkbox.value;
      lastActionTypes.push(chkval);
    }  
}
  }

}


function getCheckedTrackingLevelsValues()
{
  <?php
    if($is_tracking){
  ?>
  var elements = window.document.getElementsByClassName("searchbytrackinglevels");  
  trackingLevels=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
	if(checkbox){
    if ( checkbox.checked == true){      
      var chkval = checkbox.value;
      trackingLevels.push(chkval);
    }  
	}
  }
<?php
  }
?>
}

function getCheckedStatusTypesValues()
{
  var elements = window.document.getElementsByClassName("searchbystatustypes");  
  statusTypes=[];
  for (var n = 0; n < elements.length; ++n) {
    var element = elements[n]; 
    var checkbox = element.querySelector("input[type='checkbox']");
	if(checkbox){
    if ( checkbox.checked == true){      
      var chkval = checkbox.value;
      statusTypes.push(chkval);
    }  
	}
  }

}





function getUpdatedValues(isBlockChecked)
{


  setTimeout(function () {
    counFilterBlock();
    getCheckedHouseCommitteValues();
    getCheckedSenateCommitteValues();
    getCheckedSponsersValues();
    getCheckedtagsValues();
    getcheckedassignValues();
    getCheckedBillTypesValues();
    getCheckedLastActionTypesValues();
    getCheckedTrackingLevelsValues();
    getCheckedStatusTypesValues();
  },200);
  setTimeout(function () {
    getCountSelected();
  },300);
}
function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}

<?php
if (isset($_REQUEST['actionType']))
{
?>
    lastActionTypes.push('<?php echo $_REQUEST['actionType']; ?>');

<?php
}

if (isset($_REQUEST['tag']))
{
?>
    tags.push('<?php echo $_REQUEST['tag']; ?>');
  
<?php
}
if (isset($_REQUEST['tracking']))
{
?>
    trackingLevels.push('<?php echo $_REQUEST['tracking']; ?>');
    
<?php
}
if (isset($_REQUEST['member']))
{
?>
    assignedto.push('<?php echo $_REQUEST['member']; ?>');
<?php
}
if(isset($_REQUEST['groups']))
{
?>
    assignGroups.push('<?php echo $_REQUEST['groups'] ?>');
<?php
}
if(isset($_REQUEST['membertags'])){
?>
  assignTags.push('<?php echo $_REQUEST['membertags'] ?>');
    
<?php
}
?>

function getCountSelected()
{
  
  
  var eletitle = document.getElementsByName("search_by_title");
  var searchbytitle = "";
  
for (var i = 0; i < eletitle; i++) {
        searchbytitle +=eletitle.value;
    }
  var eltzdatasearch = document.getElementsByName("tzdatasearch");
  var tzdatasearch = "";
  var appl_sessionId = '';
if (window.location.href.indexOf("sessionId") > -1){
	appl_sessionId = window.location.href.split('sessionId=')[1];
}
 
  
    for (var i = 0; i < eltzdatasearch; i++) {
        tzdatasearch +=eltzdatasearch.value;
    }
  var trackingLevels_Unq = trackingLevels.filter( onlyUnique );
  $.ajax({
      type : "post",
      url: engagifiiUrl_ajaxurl,
      data:{
        action:'legislationfiltercountdata',
        trackingLevels: trackingLevels_Unq.toString(),
        sponsors: sponsors.toString(),
        tags: tags.toString(),
        assignTo: assignedto.toString(),
        assignTag : assignTags.toString(),
        assignGroups : assignGroups.toString(),
        houseCommittees: houseCommittees.toString(),
        senateCommittees: senateCommittees.toString(),
        lastActionTypes: lastActionTypes.toString(),
        billTypes: billTypes.toString(),
        statusTypes: statusTypes.toString(),
        searchbytitle: searchbytitle,
        tzdatasearch: tzdatasearch,
        startDate: startDate,
        endDate: endDate,
		sessionId: appl_sessionId,
      },
      success: function(response) {       
        var element  = document.getElementById("countFilterResult");
        if(element)
        {
          element.innerHTML = " ("+response.api_response +")";
        }

        $('.lead .bill-count').html('(Total '+response.api_response+' bills)');
        $('.total-bill-text').html(sessionId);
            
         }
    });
}


function clearAll()
{          
    const element = window.document.querySelectorAll('.deftzselected');
    if (element) {
        element.forEach(function(el){
        el.click();   
    });
        setTimeout(function(){
          startDate=null;
          endDate=null;
  $("#isapplyactive").val(0);
  $("#datepicker-start").val("");
  $("#datepicker-end").val("");
 $("#apply-filter-data").trigger("click");
 $(".filter-border").hide();
},200);
   
}
         

}
document.getElementById("clear-all").addEventListener("click",function(){
  clearAll();
});





var engSelectedItesm = {};
     $(function() {
      getCountSelected();


var searchbyhousecommittee =  new pluginFilterData();
searchbyhousecommittee.applySearch({searchelement:"searchbyhousecommittee",itemselectedclass:"liactive", countView: "countviewhousecommittee", clickCallback:getUpdatedValues});

var searchbysenatecommittee =  new pluginFilterData();
searchbysenatecommittee.applySearch({searchelement:"searchbysenatecommittee",itemselectedclass:"liactive", countView:"countviewsenatecommittee", clickCallback:getUpdatedValues});

var searchbysponsors =  new pluginFilterData();
searchbysponsors.applySearch({searchelement:"searchbysponsors",itemselectedclass:"liactive" , countView:"countviewbysponsors", clickCallback:getUpdatedValues});

var searchbytags=  new pluginFilterData();
searchbytags.applySearch({searchelement:"searchbytags",itemselectedclass:"liactive" , countView:"countviewbytags", clickCallback:getUpdatedValues});

var searchbyassignto = new pluginFilterData();
searchbyassignto.applySearch({searchelement:"searchbyassignto",itemselectedclass:"liactive" , countView:"countviewbyassign", clickCallback:getUpdatedValues})

var searchbybilltypes =  new pluginFilterData();
searchbybilltypes.applySearch({searchelement:"searchbybilltypes",itemselectedclass:"liactive" , countView:"countviewbybilltypes", clickCallback:getUpdatedValues});

var searchbylastactiontypes =  new pluginFilterData();
searchbylastactiontypes.applySearch({searchelement:"searchbylastactiontypes",itemselectedclass:"liactive" , countView:"countviewbylastactiontypes", clickCallback:getUpdatedValues});

var searchbytrackinglevels =  new pluginFilterData();
searchbytrackinglevels.applySearch({searchelement:"searchbytrackinglevels",itemselectedclass:"liactive" , countView:"countviewbytrackinglevels", clickCallback:getUpdatedValues});

var searchbystatustypes =  new pluginFilterData();
searchbystatustypes.applySearch({searchelement:"searchbystatustypes",itemselectedclass:"liactive" , countView:"countviewbystatustypes", clickCallback:getUpdatedValues});  

      });
     // Extra Div for Tracking


function addDivTracking(){
    var span_Ext1 = jQuery(document).find(".select2-search").find("span.engTrackingLevels").length;
    if(span_Ext1 <1)
    {
      jQuery(document).find(".select2-search").find(".select2-search__field").attr("placeholder", "Search");
      jQuery(document).find(".select2-search").find(".select2-search__field").after('<span class="icon engseachicon"><i class="fa fa-search"></i></span>'); 
      jQuery(document).find(".select2-search").prepend("<span class=\"engTrackingLevels\"> Tracking Levels </span>");  
    }
}
  </script>

<?php 
$dt_class=' ';
$dt_respnsive = '';
$dt_respnsive = get_option( 'ebt_api_settings' )['dt_responsive'];
if($dt_respnsive==1){
$dt_class = 'dt-responsive nowrap ';	
}
$dt_darktheme = '';
$dt_darktheme = get_option( 'ebt_api_settings' )['dt_darktheme'];
if($dt_darktheme==1){
$dt_class .= 'table-dark ';	
}
if($lbt_visib_datacol_list && count($lbt_visib_datacol_list)>0){
  $filteredColumns=[]; //object array filtered from columnList
  $columnGroup=[]; //array of keys from filtered objects 
  $tempColumn=[];  //temporary object from filtered objects
  $seqColumns=array_fill(0, count($lbt_visib_datacol_list), ''); //sequenced object array
  //compare columns with checked columns
  foreach($collection->columnList as $key => $value) {
	  if (in_array($value->key, $lbt_visib_datacol_list)){
		  array_push($filteredColumns, $value);
		  array_push($columnGroup, $value->key);	
	  }
  }
  //sequence columns with checked columns
  foreach($filteredColumns as $key => $value) {
		  array_push($tempColumn, $filteredColumns[array_search($value->key, $columnGroup)]);
		  array_splice($seqColumns,array_search($value->key, $lbt_visib_datacol_list),1,$tempColumn);
		  $tempColumn=[];
  }
  $bill_number_column_key = array_search("billNumber", $lbt_visib_datacol_list);
  $bill_title_key = array_search("title", $lbt_visib_datacol_list);
} else {
	$seqColumns=$collection->columnList;
	$searchTable=[]; 
	foreach($seqColumns as $key => $value) {
	  array_push($searchTable, $value->key);	
	}
	$bill_number_column_key = array_search("billNumber", $searchTable);
	$bill_title_key = array_search("title", $searchTable);
}

/*$temp_array = array();
foreach ($seqColumns as $key => $value) {
 
  if($value->key == 'title'){
    $title_key = $key;
  }

  if($value->key == 'assignedto')
  {
    $temp_array[] = $seqColumns[$key];
    unset($seqColumns[$key]);
    array_values(array_filter($seqColumns));         
  }
}
array_splice( $seqColumns, $title_key+1, 0, $temp_array );*/
?>
<div class="container-fluid engagifii-box engagifii-main-container position-relative <?php if($dt_respnsive==''){ echo 'px-xl-5'; } ?> ">
    <table  id="ebtmaintable" class="table table-bordered border-0 table-striped   main-list-here legislation <?php echo  $dt_class; ?> " style="width: 100% !important;">
      <thead> 
             <tr>
                <?php
$forDatatable = array();
$i = 0;
$sort_key = 1;
foreach ($seqColumns as $key => $row){
   // if (in_array($row->key, $lbt_visib_datacol_list)){

        if ($row->key == 'introducedDate')
        {
            $row->key = 'IntroducedDate';
        }
        if ($row->key == 'billType')
        {
            $row->key = 'BillType';

        }

        if ($row->key == 'billNumber')
        {
            $sort_key = $i;
        }

        $forDatatable[$i]['data'] = $row->key;
		//unset($forDatatable[9]);
		
		$class=strtolower($row->name);
		
?>                       
                  <th class="<?php echo $class; ?> <?php echo $row->key; ?>" scope="col">
                    <?php if ($row->key == 'trackingLevel')
        {
            echo "Tracking\nLevel";
		} else {
            echo $row->name;
        } ?>
                  </th>
                <?php
        $i++;
    //}
}
?>
            </tr> 
               
        </thead> 
     
    </table>

    <div id="eng-overlay">
      <span class="spinner"></span>
  </div>
</div>

<script type="text/javascript">
var lbtDynamicTableClass = new Array();




function formatColor(item, state) {
  if (!item.id) {
    return item.text;
  }
  //console.log(item.element.dataset);
  var bgTrackignColor = item.element.dataset.engcolor;
  var bgColor = $('<span style="background-color:'+bgTrackignColor+'; width: 15px;height: 15px;border-radius: 50%;display: inline-block;margin-left: 8px; vertical-align:middle;">', {
    class: "trac-code-here",
    width: 26
  });
  var span = $("<span>", {
    text: " " + item.text
  });
  span.prepend(bgColor);
  return span;
}



function dateChanged(ev) {
    $(this).daterangepicker('hide');
    if ($('#datepicker-start').val() != '' && $('#datepicker-end').val() != '') 
      {
        getUpdatedValues();
      }
    
}
  $(document).ready(function() {

    var startDAta= $( "#datepicker-start" ).daterangepicker({opens: 'left',singleDatePicker: true,autoApply: true}, function(start, end) {
      
      console.log(end.format('MM/DD/YYYY'));
      startDate = start.format('MM/DD/YYYY');
      console.log(startDate);
      getUpdatedValues();

    }); 
  
   var endData= $( "#datepicker-end" ).daterangepicker({opens: 'left',singleDatePicker: true, autoApply: true} , function(start, end) {
      endDate = start.format('MM/DD/YYYY');
      console.log(endDate);
      getUpdatedValues();

    });

   $('#datepicker-start').val('');
   $('#datepicker-end').val('');
    
    
   /*$( document).on('click', '.click-filter', function (e) {
      if($(".filter-border").is(":visible")){
        //alert('bh');
        $(".filter-border").hide();  
      } else {
        $(".filter-border").show();
      }
     e.stopPropagation();
    });*/
	 $('.filter-icon').click(function(e){
        e.stopPropagation();
        $('.filter-border').show();
        $('.filter-area').toggleClass('d-none');
		jQuery(".filter-area .list-group, .list-box").mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:"minimal-dark",
		 			scrollbarPosition:"outside"
		 			});
		
    });
 

    /*$(document).on('click', function (e) {
      var container = $(".filter-border");

      
        var container_class= $(e.target).attr('class');
        if(container_class == 'prev available'|| container_class == 'next available')
        {
           return false;
        }
    // If the target of the click isn't the container
    if(!container.is(e.target) && container.has(e.target).length === 0   && (e.target.className == 'prev available' || e.target.className == 'next available' )){
      container.hide();
    }
  });*/
	  $(document).on('click', function (e) {
 $('.filter-area').addClass('d-none');
});
$(document).on('click', '.filter-area', function (e) {
  e.stopPropagation();
});
$(document).on('click', 'th.prev', function (e) {
  e.stopPropagation();
});
$(document).on('click', 'th.next', function (e) {
  e.stopPropagation();
});
$(document).on('click', '.daterangepicker ', function (e) {
  e.stopPropagation();
});
   
    $(document).find('th').on("click", function (event) {
        if($(event.target).is("input"))
            event.stopImmediatePropagation();
    });


 
var sponserVal = $(document).find("#enga_custom_multi_filter").val();



var appl_trackingLevels=[];
var appl_sponsors=[];
var appl_houseCommittees=[];
var appl_senateCommittees=[];
var appl_lastActionTypes=[];
var appl_billTypes=[];
var appl_statusTypes=[];
var appl_tags =[];
var appl_assignto  = [];
var appl_assignGroups = [];
var appl_assignTags   = [];
var appl_sessionId1 = '';
if (window.location.href.indexOf("sessionId") > -1){
	appl_sessionId1 = window.location.href.split('sessionId=')[1];
}
<?php
if (isset($_REQUEST['actionType']))
{

?>
 var lastActionTypes_Unq = lastActionTypes.filter( onlyUnique );
  appl_lastActionTypes=lastActionTypes_Unq;

<?php
}

if (isset($_REQUEST['tag']))
{
?>
var tags_Unq        = tags.filter(onlyUnique);
appl_tags = tags_Unq;

<?php
}

if (isset($_REQUEST['tracking']))
{
?>

    var trackingLevels_Unq = trackingLevels.filter( onlyUnique );
    appl_trackingLevels=trackingLevels_Unq;

<?php
}

if (!empty($staffMember))
{
?>
    var assignedto_unq  = assignedto.filter(onlyUnique);
    appl_assignto = assignedto_unq;
<?php
}
if(isset($_REQUEST['membertags'])){
?>
  var assignTags_unq = assignTags.filter(onlyUnique);
    appl_assignTags = assignTags_unq;

<?php
}
if(isset($_REQUEST['groups'])){
  ?>
    var assignGroups_unq = assignGroups.filter(onlyUnique);
    appl_assignGroups = assignGroups_unq;

  <?php
}
?>

<?php
if (isset($_REQUEST['actionType']) || isset($_REQUEST['tag']) || isset($_REQUEST['member']) || isset($_REQUEST['tracking']) || isset($_REQUEST['groups']) || isset($_REQUEST['membertags']))
{
?>
    $("#isapplyactive").val("1");
    $('#blockedchecked').val(1);

     var fv = 1;
  if(fv>0){
  $('.filter-icon').addClass('active bg-primary text-white').removeClass('bg-light'); 
  $('.filter-icon span').text(fv); 
  } else {
  $('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light');  
  }
<?php
}

?>
function copyDataforApply()
{
   appl_trackingLevels=[];
   appl_sponsors=[];
   appl_houseCommittees=[];
   appl_senateCommittees=[];
   appl_lastActionTypes=[];
   appl_billTypes=[];
   appl_statusTypes=[];
   appl_tags=[];
   appl_assignto= [];
   appl_assignTags = [];
   appl_assignGroups = [];
   var trackingLevels_Unq = trackingLevels.filter( onlyUnique );
   var houseCommittees_Unq = houseCommittees.filter( onlyUnique );
   var sponsors_Unq = sponsors.filter( onlyUnique );
   var senateCommittees_Unq = senateCommittees.filter( onlyUnique );
   var lastActionTypes_Unq = lastActionTypes.filter( onlyUnique );
   var billTypes_Unq = billTypes.filter( onlyUnique );
   var statusTypes_Unq = statusTypes.filter( onlyUnique );
   var tags_Unq        = tags.filter(onlyUnique);
   var assignedto_unq  = assignedto.filter(onlyUnique);
   var assignTags_unq  = assignTags.filter(onlyUnique);
   var assignGroups_unq = assignGroups.filter(onlyUnique);

   appl_trackingLevels=trackingLevels_Unq;
   appl_sponsors=sponsors_Unq;
   appl_houseCommittees=houseCommittees_Unq;
   appl_senateCommittees=senateCommittees_Unq;
   appl_lastActionTypes=lastActionTypes_Unq;
   appl_billTypes=billTypes_Unq;
   appl_statusTypes=statusTypes_Unq;
   appl_tags  = tags_Unq;
   startDate=startDate;
   endDate=endDate;
   appl_assignto = assignedto_unq;
   appl_assignTags = assignTags_unq;
   appl_assignGroups = assignGroups_unq;
}

 
var sort_key = '<?php echo $sort_key ?>';
var blog_title = "<?php echo _WORKSPACE_; ?>";
var table = $('#ebtmaintable').DataTable( {
    
      "pageLength": 10,
      "dom": '<"row no-gutters"<"col-sm-12 custom-scroll border-left border-right border-bottom"t>><"row"<"col-sm-5 pt-3"l><"col-sm-7 pt-3 "p>>',
      "bInfo":false,
      "processing": true,
      "searching": true,
	  "scrollX": false,
      
      "order": [[$('th.title').index(), 'desc']],
      "language": {
         processing: '<span>&nbsp;</span>',
         "emptyTable": "No bill found!",
         search:'',
         searchPlaceholder: "Search by bill title..."
      },
      "oLanguage": {
         "sLengthMenu": "Show _MENU_ records per page"
      },
		

        "serverSide": true,
        "ajax": {
            "url": ajax_url_lbt,
            "type": "POST",
            "data": function(d) {           

            d.sponsorsList= $('#enga_custom_multi_filter').val();
            d.datepickerstart= $('#datepicker-start').val();
            d.datepickerend= $('#datepicker-end').val();
            d.tzdatasearch = $('#tzdatasearch').val();
            d.search_by_title_row = $('#search_by_title_row').val();
            
            d.trackingLevels = appl_trackingLevels;
            d.sponsors =  appl_sponsors;
            d.houseCommittees= appl_houseCommittees;
            d.senateCommittees= appl_senateCommittees;
            d.lastActionTypes= appl_lastActionTypes;
            d.billTypes= appl_billTypes;
            d.statusTypes= appl_statusTypes;
            d.startDate = startDate;
            d.tags      = appl_tags;
            d.endDate = endDate;
            d.assignTo  = appl_assignto;
            d.assignTag = appl_assignTags;
            d.assignGroups = appl_assignGroups;
            d.isapplyactive = $("#isapplyactive").val();
			d.sessionIds = appl_sessionId1;
         },
            
         },
         createdRow: function (row, data, index) { 
            //$(row).addClass( 'bg-white' );           
            var lbtClassColor = data.trackingLevelColorCode;
            var lbtTrackColor = lbtClassColor.replace("#", "_");
            var lbtclass = "lbtclass_"+lbtTrackColor;
            if (lbtDynamicTableClass.indexOf(lbtclass) === -1) {
               lbtDynamicTableClass.push(lbtclass);
               createLBTcssClass(lbtclass,lbtClassColor);
            }
         },        
         "columns":<?php echo (json_encode($forDatatable)); ?>, 
		 
		 "columnDefs": [ 
	  				{ "targets": [ 'BillType','state','fileId','trackingLevel','IntroducedDate','lastActionOn','sponsors','houseCommittees','senateCommittees','status', 'tags', 'assignedto'], "orderable": false},
            { responsivePriority: 1, targets: 'billNumber' },
			{ responsivePriority: 2, targets: 'title' },
			{ responsivePriority: 10001, targets: 'lastActionOn' },
			{ responsivePriority: 10002, targets: 'IntroducedDate' },
			{ responsivePriority: 10003, targets: 'houseCommittees' },
			{ responsivePriority: 10004, targets: 'senateCommittees' },
			{ className: "text-center", "targets": ["BillType","status","fileId","state"] },
			{ className: "title-col", "targets": "title" }
			//{ 'width': '75', 'targets': 'billNumber' },
			//{ 'width': '199', 'targets': 'title' },
			//{ 'width': '45', 'targets': 'BillType' }
      ],
         
		 "drawCallback": function( settings ) {
			 dt_dropdown();
			 <?php if($dt_respnsive==''){ ?>
           dt_scroll();
			   <?php } ?>
         }
		 
      });
   
 
$('#ebtmaintable')
    .on( 'processing.dt', function ( e, settings, processing ) {
        $('#eng-overlay').css( 'display', processing ? 'block' : 'none' );
    } )
    .dataTable();



 $("#apply-filter-data").click(function (e) {
  
  // show filter icon with table column when active
  $(".filter_showcase").remove();
      var spanfilter = "<span class='fa fa-filter  filter_showcase'></span>";

      $("#isapplyactive").val("1");

      if(houseCommittees.toString()){
        $('.houseCommittees').append(spanfilter);
      }
     
      if(senateCommittees.toString()){
        $('.senateCommittees').append(spanfilter);
      }
      if(sponsors.toString()){
        $('.sponsors').append(spanfilter);
      }
       if(tags.toString()){
        $('.tags').append(spanfilter);
      }
       if(assignedto.toString() || assignGroups.toString() || assignTags.toString()){
        $('.assignedto').append(spanfilter);
      }
      if(billTypes.toString()){
        $('.BillType').append(spanfilter);
      }
      if(lastActionTypes.toString()){
        $('.lastActionOn').append(spanfilter);
      }
      if(trackingLevels.toString()){
        $('.trackingLevel').append(spanfilter);
      }
      if(statusTypes.toString()){
        $('.status').append(spanfilter);
      }

      if(startDate && endDate)
      {
          $('.lastActionOn').append(spanfilter);
      }

      $(".filter-border").hide();
      copyDataforApply();
     
      setTimeout(function() {      
         table.draw();
      },500);
      return false;
});  

// checking table column for serach feature
var billNumber = '<?php echo $billnumber; ?>';
var table_key = '<?php echo $bill_number_column_key; ?>';
var title_key = '<?php echo $bill_title_key; ?>';
function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
if(table_key){
  
  $('#ebtmaintable thead tr th:eq('+table_key+')').each( function (i) {
      var title = $(this).text();
      $(this).html( '<div class="position-relative"><input type="text" placeholder="Eg: HB 0002 or SR 0980" class="form-control form-control-sm search-endorsement pr-4" id="bill_number" value="'+billNumber+'"/><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:0; top:0px; display:none"><i class="far fa-times"></i></button></div>' );
  $( 'input', this ).keyup(delay(function (e) {
		  var strs = this.value;
		  if (/\d/.test(strs)) {
			 var number = strs.match(/\d+/)[0];
		   if (number.length == 3) {
			  number = '0' + number;
			} else if (number.length == 2) {
			  number = '00' + number;
			}else if(number.length == 1) {
			  number = '000' + number;
			}
		   strs = strs.replace(/\d+/, ' '+number);
		   strs = strs.replace(/  +/g, ' '); 
		  }
		  //console.log(strs);
         if ( table.column(i).search() !== strs ) {
            table.column(i).search( strs ).draw();
         }
	}, 500));
 	$( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$(this).siblings('.clear-search').show();
			} else {
				$(this).siblings('.clear-search').hide();
			} 
 });
$('#bill_number + .clear-search').click(function(e){
	 $('#bill_number').val('');
	$(this).hide();
	e.stopPropagation();
	table.column(i).search('').draw();
 });
	  
   });
}  

if(title_key){
  $('#ebtmaintable thead tr th:eq('+title_key+')').each( function (i) {
        var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt"><input type="text" placeholder="Search title" class="form-control form-control-sm search-endorsement pr-4 shadow-nonw" value="" id="searchclass"/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:3;display:none"><i class="fal fa-times"></i></button></div>' );
 
       $( 'input', this ).keyup(delay(function (e) {
		    var titlesearch = this.value;
            if ( table.column(i).search() !== titlesearch ) {
                table.column('1').search( titlesearch ).draw();
            }
        }, 500));
		 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$(this).siblings('.clear-search').show();
			} else {
				$(this).siblings('.clear-search').hide();
			} 
 });
$('#searchclass + div+ .clear-search').click(function(e){
	 $('#searchclass').val('');
	$(this).hide();
	e.stopPropagation();
	table.column('1').search('').draw();
 });
		
    } );
}
$(document).ready(function (){    
    $('#searchclass, #bill_number, .search-dt span').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchclass, #bill_number').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});
	 });

<?php
if (isset($_REQUEST['bill']))
{
?>
      $('.search-endorsement').keyup();
<?php
}

?>


// Search By Bill Number, Tracking Level, Last Action Date
$('.multisearch').on( 'keyup change clear', function () {
        table.column(0).search( $(this).val(), true);
        if (table.page.info().recordsDisplay != 1) {
          table.column(0).search($(this).val(), true);
        }
    table.draw();
});


});

function createLBTcssClass(className,backgroundColor)
{
      
  var css = "#ebtmaintable ."+className+" { border-left: 7px solid "+backgroundColor+" !important;}";
  head = document.head || document.getElementsByTagName('head')[0];
  existsStyle = style = document.getElementById('lbtcss');
  if(existsStyle ==null || existsStyle == 'undefined' )
  {
    style = document.createElement('style');
    style.setAttribute("id", "lbtcss");
    head.appendChild(style);
    style.type = 'text/css';
  }
  else
  {
    style = existsStyle;
  }
  if(style.styleSheet){
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }

}  

// activate/deactvate filter icon
$("#apply-filter-data").click(function () {

  var fv = parseInt($('#blockedchecked').text().replace(/[^0-9]/gi, ''),10);
  if(fv>0){
    $('.filter-icon').addClass('active bg-primary text-white').removeClass('bg-light'); 
    $('.filter-icon span').text(fv); 
  } else {
    $('.filter-icon').removeClass('active bg-primary text-white').addClass('bg-light');
  }
});  
 


if (window.location.href.indexOf("sessionId") > -1){
	$('.sessionname').html(' ('+ localStorage.getItem("sessionname")+')');
}
</script>      
</div>
