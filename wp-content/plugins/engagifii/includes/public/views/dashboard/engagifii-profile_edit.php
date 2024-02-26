<?php 
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
$tenant_code = 'psba';
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
$userEmail = $user->user_email;
    $obj      =  new Engagifii_API();
    $engagifiiProfile = $obj->engagifiiProfile('psba');
	$peopleDATA = json_decode($engagifiiProfile['api_response']);
if($peopleDATA->isError==true) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
} 
include 'sidebar_nav.php';  
	$tags= $peopleDATA->tags;
	$infoseq='';
	$infotabId = '';
	$groupseq = '';
	$groupId = '';
	foreach ($peopleDATA->tabs as $key => $value) {
     if($value->sequence==1){
		 $infoseq = $key;
		 $infotabId = $value->id;
     }
 }
 	foreach ($peopleDATA->tabs[$infoseq]->groupFields as $key => $value) {
     if($value->sequence==1){
		 $groupseq = $key;
		 $groupId = $value->id;
     }
 }
 ?>
    <style>
	.profile-tabs .nav-link {
	top:0 !important;	
	border-bottom:0 !important	
	}
	.profile-tabs .nav-link.active, .profile-tabs .nav-link:hover {
	border-bottom:0 !important	
	}
	</style>
<div class="container-fluid">
<ul class="nav nav-tabs profile-tabs mb-4" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#nav-header" type="button" role="tab" aria-controls="home" aria-selected="true">Name</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#nav-body" type="button" role="tab" aria-controls="profile" aria-selected="false">Contact Information</button>
  </li>
 
</ul>
	<form action="" class="edit-profile">
    
    <div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade border bg-light rounded-2 p-4 show active" id="nav-header" role="tabpanel" aria-labelledby="nav-home-tab">
  	<div class="row">
        	<div class="col-md-3 text-center">
            <div class="overflow-hidden rounded-circle d-block m-auto" style="width:130px;height:130px">
            <span id="upload_profile" class="position-relative  d-block h-100">
    <img src="<?php echo $peopleDATA->people->imageThumbUrl; ?>" alt="..." class="img-fluid h-100" id="blah"  >
    <span class="position-absolute w-100 h-100 top-0 start-0 text-white d-flex align-items-center flex-column justify-content-center" style="background:rgba(0,0,0,0.6); opacity:0; top:0; left:0"><i class="fa fa-image"></i><br>Upload</span>
    <input type="file" class="position-absolute top-0 start-0 w-100 h-100 z-1" style="opacity:0; top:0; left:0" accept="image/*" id="imgInp" onchange="encodeImageFileAsURL(this)"> 
    <style>
	#upload_profile:hover span {
	opacity:1 !important;	
	}
	</style>
    </span>
    </div>	
    
            </div>
            <div class="col-md-9">
              <div data-section="header" class="row mb-4">
                  <div class="form-group col-md-4">
                  <label for="">First Name</label>
                    <input  type="text" value="<?php echo $peopleDATA->people->firstName; ?>" class="form-control firstName">
                  </div>
                  <div class="form-group col-md-4">
                  <label for="">Middle Name</label>
                    <input  type="text" value="<?php echo $peopleDATA->people->middleName; ?>" class="form-control middleName">
                  </div>
                  <div class="form-group col-md-4">
                  <label for="">Last Name</label>
                    <input type="text" value="<?php echo $peopleDATA->people->lastName; ?>" class="form-control lastName">
                  </div>
                  <!-- <div class="form-group col-12">
                  	<div class="flex">
                    	<span class="mr-3">Tag(s):</span>
                        <span class="tags_all"> -->
     				<?php /*if($tags){
						foreach ($tags as $key => $value) {
							echo '<span class="badge rounded-pill text-bg-light border border-dark-subtle mr-2 mb-2">'.$value->tagName.'<span class="tag_del px-1" style="cursor:pointer">X</span></span>';
					 }		
					} else {
						echo '<em>No Tags found!</em>';
					}*/ ?> 
                    <!-- </span>
                  	</div>
                    <input type="text" value="" class="form-control tag_add" placeholder="Add Tags">
                  </div> -->
               <div class="form-group col-12">
                	        <button type="submit" class="btn btn-primary">Update Profile</button>

                            <a class="btn btn-default border border-dark" href="<?php echo $site_url ?>/engagifii-profile">Cancel</a>

                </div>
                <div class="curl-message col-12" >
                	
                	<span class="curl-progress" style="display:none"><em>Hold on, Profile updating...</em></span>
                	<span class="curl-success" style="display:none"><em>Profile updated successfully.</em></span>
                </div>
              </div>
            </div>
    </div>
  </div>
  <div class="tab-pane fade border bg-light rounded-2" id="nav-body" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="pb-3">
              <div class="overflow-hidden">
              <h5 class="bg-body-secondary py-2 pl-3 border-bottom">Contact Information</h5>
              <div class="px-3">
              <div class="form-group">
              	<label for="Email Address">Email Address</label>
                    <input disabled type="text" value="<?php echo $peopleDATA->people->primaryEmail->value; ?>" class="form-control primaryEmail">
              </div>
              
              <div class="row">
              <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==11){ 
      $formattedPhoneNumber='';
          if($value->selectedValue){
          $formattedPhoneNumber = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $value->selectedValue);
          }?>
              <div class="form-group col-md-6">
              	<label for=""><?php echo $value->name;?></label>
                    <input type="text" value="<?php echo $formattedPhoneNumber;?>" class="form-control phonenumber-<?php echo $key;?>">
              </div>
   <?php  } 
 }
?>			</div>
			</div>
              <h5 class="bg-body-secondary py-2 pl-3 border-bottom">Address</h5>
              <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 $address = json_decode($value->selectedValue,true);
	 ?>
              	<div class="px-3 addressGroup<?php echo $key;?> ">
              	<div class="row">
                <div class="form-group col-12">
                	<div class="input-group">
                    	 <label class="sr-only" for=""><b><?php echo $value->name;?></b></label>
                        <input type="text" name="" class="form-control text-start" id="locationName" data-value ="<?php  echo  $address['locationName'];?>" value="<?php  echo  $address['locationName'];?>"/>
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2"><i class="fal fa-search"></i></span>
                      </div>
                    </div>

                 
                 </div>
                <div class="form-group col-12">
                  <label for="">Address Line 1</label>
                	<input type="text" name="" class="form-control text-start" id="address" data-value ="<?php  echo  $address['address'];?>" value="<?php  echo  $address['address'];?>"/>
                    </div>
                <div class="form-group col-12">
                  <label for="">Address Line 2</label>
                	<input type="text" name="" class="form-control text-start" id="addressLine2" data-value ="<?php  echo  $address['addressLine2'];?>" value="<?php  echo  $address['addressLine2'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">City</label>
                	<input type="text" name="" class="form-control text-start" id="city" data-value ="<?php  echo  $address['city'];?>" value="<?php  echo  $address['city'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">State</label>
                	<input type="text" name="" class="form-control text-start" id="state" data-value ="<?php  echo  $address['state'];?>" value="<?php  echo  $address['state'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">Zip</label>
                	<input type="text" name="" class="form-control text-start" id="zipCode" data-value ="<?php  echo  $address['zipCode'];?>" value="<?php  echo  $address['zipCode'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">Country</label>
                	<input type="text" name="" class="form-control text-start" id="country" data-value ="<?php  echo  $address['country'];?>" value="<?php  echo  $address['country'];?>"/>
                    </div>
                     <div class="">
                	<input type="hidden" name="" class="form-control text-start" id="lat" data-value ="<?php  echo  $address['lat'];?>" value="<?php  echo  $address['lat'];?>"/>
                	<input type="hidden" name="" class="form-control text-start" id="lng" data-value ="<?php  echo  $address['lng'];?>" value="<?php  echo  $address['lng'];?>"/>
                    </div>
                    	
                </div>
                </div>
                <hr class="border-secondary">
   <?php  } 
 }
?>
              
              </div>
               <div class="form-group col-12 px-3">
                	        <button type="submit" class="btn btn-primary">Update Profile</button>

                            <a class="btn btn-default border border-dark" href="<?php echo $site_url ?>/engagifii-profile">Cancel</a>

                </div>
                <div class="curl-message col-12  px-3" >
                	
                	<span class="curl-progress" style="display:none"><em>Hold on, Profile updating...</em></span>
                	<span class="curl-success" style="display:none"><em>Profile updated successfully.</em></span>
                </div>
            </div>
  </div>
</div>
    
    	
    </form>
    <script>
	jQuery('body').on('click','.tag_del',function(){
		jQuery(this).parent().remove();	
		if($('.tags_all>span').length==0){
		$('.tags_all').html('<em>No Tags Found!</em>');	
		}
	});
	 jQuery(".tag_add").keypress(function (event) {
            if (event.keyCode === 13 && jQuery(this).val()!='') {
				$('.tags_all>em').remove();
				/*jQuery('.tags_all > span').each(function(){
					var oldtag=jQuery(this).clone();  
					oldtag.find('span').remove();
					tags.push(oldtag.html());	
				});*/
                var val = '<span class="badge rounded-pill text-bg-light border border-dark-subtle mr-2 mb-2">'+jQuery(this).val()+'<span class="tag_del px-1" style="cursor:pointer">X</span></span>';
				var tag=jQuery(this).val();
				$('.tags_all').append(val);
				jQuery(this).val('');
				/*tags.push(tag);
				allTags = tags.join();*/
            }
			//event.stopPropagation();
			//event.preventDefault();
        });
	var payload = [];

	 function encodeImageFileAsURL(element) {
		 var  DPpayload=[];
		 var baseimg, profiledpdata, imageThumbUrlpath,imageThumbUrl='';
        let file = element.files[0];
		if(file.size/1024>100){
			alert('Image size should be less than 100KB');
		return;	
		}
        let reader = new FileReader();
        reader.onloadend = function() {
		  let xx = reader.result;
		  baseimg =xx.replace(/^data:image\/[a-z]+;base64,/, "");
		  const [files] = element.files
		  if (files) {
			blah.src = URL.createObjectURL(files);
				 profiledpdata = {
			"ImageString": baseimg,
			"Module": 'crm',
		 };
		  if(profiledpdata){
			DPpayload.push( profiledpdata ); 
			 DPpayload = JSON.stringify(DPpayload[0] ); 
				   const options = {
				method: 'POST',
				headers: {
				  'Content-Type': 'application/json'
				},
				body: DPpayload
			  };
			  
			  const apiUrl = 'https://engagifiiresource.azurewebsites.net/api/upload';
			  fetch(apiUrl,options)
				.then(response => {
				  if (!response.ok) {
					throw new Error('Network response was not ok');
				  }
				  return response.json();
				})
				.then(data => {
				  //console.log('API response data:', data);
			   imageThumbpayload = {
				  "imageThumbUrl": data,
			   }
			   ;
				  jQuery('#blah').attr('src',data);
				  jQuery('.curl-success').show().siblings().hide();
					xxx();		
						  
						})
				.catch(error => {
				  console.error('There has been a problem with your fetch operation:', error);
				});
				
				
				function xxx(){
			  const dpUrl = 'https://engagifii-preview4-crm.azurewebsites.net/api/v1/People/UpdatePersonHeader/<?php echo $peopleDATA->people->id; ?>';
					  const dpoptions = {
						method: 'PUT',
						headers: {
						  'Content-Type': 'application/json',
						  'Authorization':'Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE2OTUzODM3MDcsImV4cCI6MTY5NTM5MDkwNywiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNjk1MzgzNzA3LCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjkvMjIvMjAyMyAxMTo0MjozNyBBTSIsImN1cnJlbnQtbG9naW4iOiI5LzIyLzIwMjMgMTE6NTU6MDcgQU0iLCJzY29wZSI6WyJvcGVuaWQiLCJwcm9maWxlIiwiZW1haWwiLCJVc2Vyc0FQSSIsIkFjY3JlZGl0YXRpb25BUEkiLCJCaWxsdHJhY2tpbmdBcGkiLCJDb21tZW50QXBpIiwiTm90ZXNBcGkiXSwiYW1yIjpbInB3ZCJdfQ.pzsOBFlrqmUbN-Xl79II2gPmxPhlAvzPmEEKdLDamZ-U-mpFKSTRnN3Db12N1lVf7bmDA-k7yWld7y7kQ1pW29R7iXxTlc7WTSrEUlcwCDp2cBFlcTx9MbXBERLl8v3UYZ4fhAy0KwuDEQYxNa_KoyO0sY5T9vBeBS6axIJXHVMir6IKf5nr9C1OIn7WdVENZsgaYdhcElNi0JmbHdoNLspJbKC4C7LbZkqxGusR70qSg5eIPXGWO_eah7IIwhbCJqvx2jk0LTGenXgnxXWH3pXPRfGPp_qGMJeAEEoSe7a-L0lT2cWtKkaqqJeX2g42J9w-SvR3v39Di5-F9JABnQ',
						  'tenant-code':'<?php echo $tenant_code; ?>'
						},
						body: JSON.stringify(imageThumbpayload)
						};
					  fetch(dpUrl,dpoptions)
						.then(response => {
						  if (!response.ok) {
							throw new Error('Network response was not ok');
						  }
						  return response.json();
						})
						.then(data => {
						  console.log('profile image updated successfully');
						})
						.catch(error => {
						  //console.error('There has been a problem with your fetch operation:', error);
						});
				}
				

		  }
		}
        }
        reader.readAsDataURL(file);
      }

$('.edit-profile').on('submit', function(event) {
	jQuery('.curl-progress').show().siblings().hide();
	payload = [];
  event.preventDefault();
  
var tags=[];
var allTags='';
jQuery('.tags_all > span').each(function(){
	var oldtag=jQuery(this).clone();  
	oldtag.find('span').remove();
	tags.push(oldtag.html());	
	allTags = tags.join();
});
if(allTags){
var tagsdata = {
	  "tabId":null,
  "tabGroupId":null,
  "tabGroupFieldId":null,
  "loggedInUserId":"<?php echo $peopleDATA->people->id; ?>",
  "profileUserId":"<?php echo $peopleDATA->people->id; ?>",
  "isHeader":true,
  "headerFieldName":"tags",
  "smartDropDownRequest":"",
  "fieldChangeValues":[
  			{
			"oldValue":"",
			"newValue":allTags,
			"primary":false
			}
		],
	"isValueChanged":false
};
payload.push( tagsdata );  
}
  if(jQuery('.firstName').val()!='<?php echo $peopleDATA->people->firstName; ?>'){
	  var newfirstName = jQuery('.firstName').val();
	  	var firstNamedata = {
    "tabId": null,
    "tabGroupId": null,
    "tabGroupFieldId": null,
    "loggedInUserId": "<?php echo $peopleDATA->people->id; ?>",
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": true,
    "headerFieldName": "firstName",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $peopleDATA->people->firstName; ?>",
        "newValue": newfirstName,
        "primary": false
      }
    ],
    "isValueChanged": false
};

payload.push( firstNamedata );  
  }
  if(jQuery('.middleName').val()!='<?php echo $peopleDATA->people->middleName; ?>'){
	  var newmiddleName = jQuery('.middleName').val();
	  	var middleNamedata = {
    "tabId": null,
    "tabGroupId": null,
    "tabGroupFieldId": null,
    "loggedInUserId": "<?php echo $peopleDATA->people->id; ?>",
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": true,
    "headerFieldName": "middleName",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $peopleDATA->people->middleName; ?>",
        "newValue": newmiddleName,
        "primary": false
      }
    ],
    "isValueChanged": false
};

payload.push( middleNamedata );  
  }
  if(jQuery('.lastName').val()!='<?php echo $peopleDATA->people->lastName; ?>'){
	  var newlastName = jQuery('.lastName').val();
	  	var lastNamedata = {
    "tabId": null,
    "tabGroupId": null,
    "tabGroupFieldId": null,
    "loggedInUserId": "<?php echo $peopleDATA->people->id; ?>",
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": true,
    "headerFieldName": "lastName",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $peopleDATA->people->lastName; ?>",
        "newValue": newlastName,
        "primary": false
      }
    ],
    "isValueChanged": false
};

payload.push( lastNamedata );  
  }
  
   <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==11){ ?>
  if(jQuery('.phonenumber-<?php echo $key;?>').val()!='<?php echo $value->selectedValue; ?>'){
	  var newPhoneNumber<?php echo $key;?> = jQuery('.phonenumber-<?php echo $key;?>').val();
	  	var PhoneNumberdata<?php echo $key;?> = {
    "tabId": "<?php echo $infotabId; ?>",
    "tabGroupId": "<?php echo $groupId; ?>",
    "tabGroupFieldId": "<?php echo $value->id; ?>",
    "loggedInUserId": "<?php echo $peopleDATA->people->id; ?>",
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": false,
    "headerFieldName": "",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": "<?php echo $value->selectedValue; ?>",
        "newValue": newPhoneNumber<?php echo $key;?>,
        "primary": <?php if($value->isPrimary==1){ echo 'true';}else{echo 'false';}?>
      }
    ],
    "isValueChanged": false
};

payload.push( PhoneNumberdata<?php echo $key;?> );  
  }
   <?php  } 
 } 
?>
     <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 //$address = json_decode($value->selectedValue,true);
	 ?>
	var keys= [];
var olds=[];
var values=[];
var  oldobj = {};
var  obj = {};
jQuery('.addressGroup<?php echo $key; ?> input').each(function(){
keys.push(jQuery(this).attr('id'));
olds.push(jQuery(this).attr('data-value'));
values.push(jQuery(this).val());
});
for(i = 0 ; i < keys.length && i < olds.length ; i++){
    oldobj[keys[i]] = olds[i];
}
for(i = 0 ; i < keys.length && i < values.length ; i++){
    obj[keys[i]] = values[i];
}
//console.log(obj);
//console.log(oldobj);
if(JSON.stringify(obj)!=JSON.stringify(oldobj)){
	  	var Addressdata<?php echo $key;?> = {
    "tabId": "<?php echo $infotabId; ?>",
    "tabGroupId": "<?php echo $groupId; ?>",
    "tabGroupFieldId": "<?php echo $value->id; ?>",
    "loggedInUserId": "<?php echo $peopleDATA->people->id; ?>",
    "profileUserId": "<?php echo $peopleDATA->people->id; ?>",
    "isHeader": false,
    "headerFieldName": "",
    "smartDropDownRequest": "",
    "fieldChangeValues": [
      {
        "oldValue": JSON.stringify(oldobj),
        "newValue": JSON.stringify(obj),
        "primary": <?php if($value->isPrimary==1){ echo 'true';}else{echo 'false';}?>
      }
    ],
    "isValueChanged": false
};

payload.push( Addressdata<?php echo $key;?> );  
}
   <?php  } 
 }
?>
 


  
/*$.ajax({
    url: window.location.href,
    type: "POST",
    contentType: "application/json",
    data: DPpayload,
    success: function(response) {
        console.log("Profile Picture successfully. Response: ", DPpayload);
		jQuery('.curl-success').show().siblings().hide();
		
    },
 });*/
 $.ajax({
    url: window.location.href,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(payload),
    success: function(response) {
       // console.log("cURL request executed successfully. Response: ", payload);
		jQuery('.curl-success').show().siblings().hide();
		
    },
 });
 

  
  
  //console.log(DPpayload);
  //console.log(payload);
  
 



});
//google places search
   function initializeAutocomplete() {
      var input = document.getElementById('locationName');
      var options = {
         types: ['geocode'],
      };
      var autocomplete = new google.maps.places.Autocomplete(input, options);
      autocomplete.addListener('place_changed', function() {
         var place = autocomplete.getPlace();
         console.log(place);
         document.getElementById('address').value = extractAddressComponent(place, 'street_number') + ' ' + extractAddressComponent(place, 'route');
         document.getElementById('addressLine2').value =extractAddressComponent(place, 'administrative_area_level_2');
         document.getElementById('city').value = extractAddressComponent(place, 'locality');
         document.getElementById('state').value = extractAddressComponent(place, 'administrative_area_level_1');
         document.getElementById('zipCode').value = extractAddressComponent(place, 'postal_code');
         document.getElementById('country').value = extractAddressComponent(place, 'country');
         // Add more fields for other address components as needed
      });
   }
   function extractAddressComponent(place, componentType) {
      for (var i = 0; i < place.address_components.length; i++) {
         var component = place.address_components[i];
         for (var j = 0; j < component.types.length; j++) {
            if (component.types[j] === componentType) {
               return component.long_name;
            }
         }
      }
      return '';
   }
   google.maps.event.addDomListener(window, 'load', initializeAutocomplete);


	</script>
    <?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
$payload = json_decode(file_get_contents("php://input"), true);	
//print_r($payload);
//$DPpayload = json_decode(file_get_contents("php://input"), true);	
//print_r($DPpayload);

}
?>
<?php 
 	 //$payloadurl = 'https://engagifiwebstg.wpengine.com/psba/wp-content/plugins/wp-front-end-profile/views/payload.txt';
	//$payload = file_get_contents($payloadurl);
$curl = curl_init();
	$tokenurl= 'https://engagifii-preview4-crm.azurewebsites.net/api/v1/Settings/GetAccessToken';
	
	curl_setopt_array($curl, array(  
  CURLOPT_URL => $tokenurl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",   
    "tenant-code:".$tenant_code,
  ),
));
$tokenresponse = curl_exec($curl);
$tokenresponse = json_decode($tokenresponse);
curl_close($curl);




$authentication1 = 'authorization: Bearer '.$tokenresponse->result;
$curl = curl_init();
$url1 ='https://engagifii-preview4-dynamicobjectapproval.azurewebsites.net/api/v1/PeopleApproval/CreateRequest';
  curl_setopt_array($curl, array(
  CURLOPT_URL => $url1,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,  // Set request type to POST
  CURLOPT_POSTFIELDS => json_encode($payload),  // Set the payload data
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "tenant-code:".$tenant_code,
    $authentication1
  ),
));
$response1 = curl_exec($curl);
$updateDATA = json_decode($response1);
//print_r($updateDATA);
// Close the cURL session
curl_close($curl);

?>
</div>

