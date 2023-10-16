<?php
    $obj      =  new Engagifii_API();
    $publicOfficial = $obj->publicOfficial();

/*$options = get_option('ebt_api_settings');
$tenant_code          = $options['lbt_tenant_code']['tenant_code'];

$url = 'https://engagifii-preview6-billtracking.azurewebsites.net/api/1.0/legislative/public-bills/elected/officials-all-tabs-list';
$curl = curl_init();
// Append any necessary query parameters to the URL
$queryParameters = array(
    // Add your query parameters here
);
$queryString = http_build_query($queryParameters);
if (!empty($queryString)) {
    $url .= '?' . $queryString;
}

function fetchData($url, $tenant_code, $payload)
{
	$peopleDATA = '';
	$curl = curl_init();
	
	curl_setopt_array($curl, array(  
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_POST => true,  // Set request type to POST
	  CURLOPT_POSTFIELDS => $payload,  // Set the payload data
	  CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json",   
		"tenant-code:".$tenant_code, 
	   
	  ),
	));
	$response = curl_exec($curl);
	$peopleDATA = json_decode($response);
	// Close the cURL session
	curl_close($curl);
	return $peopleDATA;
}
$payload='{}';
$peopleDATA = fetchData($url, $tenant_code, $payload);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    // Handle the search request and update $peopleDATA
    $searchText = isset($_POST['searchText']) ? $_POST['searchText'] : '';
    $payload = json_encode(['searchText' => $searchText]);
	
	$peopleDATA = fetchData($url, $tenant_code,$payload);
    //print_r($peopleDATA);
}*/
?>
<style type="text/css">
  
 .session-tab button {
	border-bottom:3px solid transparent !important;
	color:#333 !important;
 }
  .session-tab button.active{
	border-bottom-color:#002474  !important;
 }
 mark, .mark {
	background-color: #F9D276;
}
</style>
<div class="container-fluid mb-4">
<div class="row">
	<div class="col-md-4"><form method="post" action="">
    	<div class="position-relative input-group">
		
                    <input type="text" placeholder="Search Public Official..." class="form-control search-official border-dark" name="searchText">
                    <div class="input-group-append">
                    <button type="button" class="search-close position-absolute btn" style="right:30px; top:0; display:none; z-index:99"><i class="fal fa-times"></i></button>
                        <button type="submit" class="input-group-text bg-transparent border-dark"><i class="fal fa-search"></i></button>
                    </div>
               

      </div> </form>
    </div>
</div>
</div>
     <ul class="nav nav-pills mb-3 justify-content-center session-tab border-bottom" id="pills-tab" role="tablist">
      <?php $i=1; 
	  	foreach ($publicOfficial as $key => $value) {
			if($key=='relatedOfficials' || $key=='myOfficials' || $key=='stateBoardOfEducationMemberList' || $key=='stateWideElectedMemberList'){
				continue;	
			}
			if($key=='stateSenateMemberList'){
				$name='State Senate';
			}
			elseif($key=='stateHouseMemberList'){
				$name='State House';
			}
			elseif($key=='stateSenateCommittees'){
				$name='State Senate Committees';
			}
			elseif($key=='stateHouseCommittees'){
				$name='State House Committees';
			}
			elseif($key=='countyDeligationList'){
				$name='County Delegations';
			}
			elseif($key=='congressionalDelegationMemberList'){
				$name='Congressional Delegations';
			}else{
				$name=$Key;
			}
			$class=''; 
			if($i==1){
				$class =' active';
			}
    echo '<li class="nav-item mr-4" role="presentation">
    <button class="nav-link bg-transparent border-0 rounded-0 px-0'.$class.'" id="" data-toggle="pill" data-target="#tab-'.$i.'" type="button" role="tab" aria-controls="home" aria-selected="true">'.$name.' <b>('.count($value).')</b></button></li>';
 $i++; }
	  ?>   		
      </ul>
      <div class="tab-content" id="nav-tabContent">
      <?php $k=1; 
	  	foreach ($publicOfficial as $key => $value) {
			if($key=='relatedOfficials' || $key=='myOfficials' || $key=='stateBoardOfEducationMemberList' || $key=='stateWideElectedMemberList'){
				continue;	
			}
			$class=''; 
			if($k==1){
				$class =' show active';
			}
			$list = $value;
			?>
      <div class="tab-pane fade <?php echo $class; ?>" id="tab-<?php echo $k; ?>" role="tabpanel" aria-labelledby="nav-home-tab">
      	<div class="row">
        	<?php if($key=='stateSenateCommittees' || $key=='stateHouseCommittees' || $key=='countyDeligationList'){ 
				echo '<div class="accordion col-12" id="accordionExample">';
				foreach ($list as $key => $value) { ?>
					  <div class="card">
                        <div class="card-header px-0" id="heading<?php echo $value['id']; ?>">
                          <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left py-0" type="button" data-toggle="collapse" data-target="#collapse<?php echo $value['id']; ?>" aria-expanded="true" aria-controls="collapseOne">
                              <i class="fal fa-plus mr-3"></i><?php echo $value['name']; ?>
                            </button>
                          </h2>
                        </div>
                        <div id="collapse<?php echo $value['id']; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                          <div class="card-body">
                            <div class="row">
                            	<?php //if($key=='countyDeligationList'){
									//print_r($value->countyOfficals);
									 foreach ($value['countyOfficals'] as $key => $values) { ?>
                                 	 <div class="col-md-6 col-lg-4 mb-3 ">
                                  <div class="border bg-light rounded-2 p-3">
                                      <div class="d-flex">
                                          <div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px">
                                                  <img src="<?php echo $values['profilePic']; ?>" alt="" class="img-fluid">
                                          </div>
                                          <div>
                                      <a href="<?php echo site_url();?>/public-official-detail/?id=<?php echo $values['id']; ?>"><?php echo $values['legalName']; ?></a><br>
                                      (<?php echo $values['officialNameLabel'] ; ?>)<br>
                                      <?php echo $values['legislativeRole']; ?><br>
                                      <?php echo $values['districtCode']; ?><br>
                                      <?php echo $values['residence']; ?><br>
                                      <?php echo $values['party']; ?>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                                	<?php }
								//}
								//if($key=='stateSenateCommittees' || $key=='stateHouseCommittees') {
									foreach ($value['committeeOfficals'] as $key => $values) { ?>
                                       <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border bg-light rounded-2 p-3">
                                        <div class="d-flex">
                                            <div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px">
                                                    <img src="<?php echo $values['profilePic']; ?>" alt="" class="img-fluid">
                                            </div>
                                            <div>
                                         <a href="<?php echo site_url();?>/public-official-detail/?id=<?php echo $values['id']; ?>"><?php echo $values['legalName']; ?></a><br>
                                        (<?php echo $values['officialNameLabel'] ; ?>)<br>
                                      <?php echo $values['legislativeRole']; ?><br>
                                      <?php echo $values['districtCode']; ?><br>
                                      <?php echo $values['residence']; ?><br>
                                      <?php echo $values['party']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
									<?php }
								//}?>	
                            </div>
                          </div>
                        </div>
                      </div>
				<?php }
				echo '</div>';
			 } else {
        		foreach ($list as $key => $value) {?>
        		<div class="col-md-6 col-lg-4 mb-3">
            	<div class="border bg-light rounded-2 p-3">
                	<div class="d-flex">
                    	<div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px">
                        		<img src="<?php echo $value['profilePic']; ?>" alt="" class="img-fluid">
                        </div>
                        <div>
                	 <a href="<?php echo site_url();?>/public-official-detail/?id=<?php echo $value['id']; ?>"><?php echo $value['legalName']; ?></a><br>
                    (<?php echo $value['officialNameLabel'] ; ?>)<br>
                    <?php echo $value['legislativeRole']; ?><br>
                    <?php echo $value['districtCode']; ?><br>
                    <?php echo $value['residence']; ?><br>
                    <?php echo $value['party']; ?>
                        </div>
                    </div>
                </div>
            </div>
           	 <?php }
			 } ?>
        </div>
      </div>
      <?php $k++; } ?>
      </div>
       <script>
	   jQuery(document).ready(function(){
		 jQuery('.search-official').val('');  
	   });
	   var searchOfficial='';
	   jQuery('.search-official').keyup(function(){
			if(jQuery('.search-official').val()!=''){
				jQuery('.search-close').show();	
			}else{
			  jQuery('.search-close').hide();		
			}
	   });
	   jQuery('form button').click(function(e){
		   if(jQuery('.search-official').val()!=''){
			   searchOfficial = jQuery('.search-official').val();
			 publicOfficial();
		   }else{
			alert('Search field should not be blank');   
		   }
		 e.preventDefault();
	   });
	    jQuery('.search-close').click(function(){
			 jQuery('.search-official').val(''); 
			jQuery(this).hide();
			searchOfficial = '';
			 publicOfficial();	
		});
	    function publicOfficial()  {

          $.ajax({
          type : "post",
          url: engagifiiUrl_ajaxurl,
          data:{
              action:'publicofficialdata',
			  searchText:searchOfficial
        
          },
          success: function(response) { 
		  	var data =   response; 
			console.log(data); 
			var count1=[];
			  const keys = Object.keys(data);
			  for (const key of keys) {
				const count = data[key].length;
				count1.push(`${count}`);
			  }
			jQuery('#pills-tab li').each(function(){
				jQuery(this).find('button b').text('('+count1[jQuery(this).index()]+')');	
			});
			var keyss=[];
			for (let key in data) { 
				var html='';
			  let value;
			  value = data[key];
			   //console.log(key);
			  //console.log(value); 
			  keyss.push(key);
			   var index = keyss.indexOf(key);
			   if(key=='stateSenateCommittees' || key=='stateHouseCommittees'){
				  for (let key in value) {
					htmlData=value[key]; 
					var htmlinner='';
					var innerValue = htmlData['committeeOfficals'];
				
					for (let key in innerValue) {
						htmlinnerdata=innerValue[key];
						htmlinner+='<div class="col-md-6 col-lg-4 mb-3"><div class="border bg-light rounded-2 p-3"><div class="d-flex"><div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px"><img src="'+htmlinnerdata['profilePic']+'" alt="" class="img-fluid"></div><div><a href="http://localhost/engagifiwebstg/public-official-detail/?id='+htmlinnerdata['id']+'">'+htmlinnerdata['legalName']+'</a><br>('+htmlinnerdata['officialNameLabel']+')<br>'+htmlinnerdata['legislativeRole']+'<br>'+htmlinnerdata['districtCode']+'<br>'+htmlinnerdata['residence']+'<br>'+htmlinnerdata['party']+'</div></div></div> </div>';	
					}
					html+='<div class="card"> <div class="card-header px-0" id="heading'+htmlData['id']+'"> <h2 class="mb-0"> <button class="btn btn-link btn-block text-left py-0" type="button" data-toggle="collapse" data-target="#collapse'+htmlData['id']+'" aria-expanded="true" aria-controls="collapseOne"> <i class="fal fa-plus mr-3"></i>'+htmlData['name']+' </button> </h2> </div><div id="collapse'+htmlData['id']+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style=""> <div class="card-body"> <div class="row">'+htmlinner+' </div></div></div></div>';
				  }
				  //console.log(html);
					jQuery('.tab-pane').eq(index).find('.accordion').html(html);	
			   } else if(key=='countyDeligationList'){
				 	for (let key in value) {
					htmlData=value[key]; 
					var htmlinner='';
					var innerValue = htmlData['countyOfficals'];
					for (let key in innerValue) {
						htmlinnerdata=innerValue[key];
						htmlinner+='<div class="col-md-6 col-lg-4 mb-3"><div class="border bg-light rounded-2 p-3"><div class="d-flex"><div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px"><img src="'+htmlinnerdata['profilePic']+'" alt="" class="img-fluid"></div><div><a href="http://localhost/engagifiwebstg/public-official-detail/?id='+htmlinnerdata['id']+'">'+htmlinnerdata['legalName']+'</a><br>('+htmlinnerdata['officialNameLabel']+')<br>'+htmlinnerdata['legislativeRole']+'<br>'+htmlinnerdata['districtCode']+'<br>'+htmlinnerdata['residence']+'<br>'+htmlinnerdata['party']+'</div></div></div> </div>';	
					}
					html+='<div class="card"> <div class="card-header px-0" id="heading'+htmlData['id']+'"> <h2 class="mb-0"> <button class="btn btn-link btn-block text-left py-0" type="button" data-toggle="collapse" data-target="#collapse'+htmlData['id']+'" aria-expanded="true" aria-controls="collapseOne"> <i class="fal fa-plus mr-3"></i>'+htmlData['name']+' </button> </h2> </div><div id="collapse'+htmlData['id']+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style=""> <div class="card-body"> <div class="row">'+htmlinner+' </div></div></div></div>';
				  }
				  //console.log(html);
					jQuery('.tab-pane').eq(index).find('.accordion').html(html);  
			   } else {
				  for (let key in value) {
					htmlData=value[key]; 
					html+='<div class="col-md-6 col-lg-4 mb-3"><div class="border bg-light rounded-2 p-3"><div class="d-flex"><div class="overflow-hidden rounded-circle mr-3" style="height:50px; width:50px"><img src="'+htmlData['profilePic']+'" alt="" class="img-fluid"></div><div><a href="http://localhost/engagifiwebstg/public-official-detail/?id='+htmlData['id']+'">'+htmlData['legalName']+'</a><br>('+htmlData['officialNameLabel']+')<br>'+htmlData['legislativeRole']+'<br>'+htmlData['districtCode']+'<br>'+htmlData['residence']+'<br>'+htmlData['party']+'</div></div></div> </div>';
				  }
				  //console.log(html);
					jQuery('.tab-pane').eq(index).find('.row').html(html);	
			   }
	   
		  } 
		  }
        });
      }

	  
	/*function replaceText() {


    var searchword = jQuery(".search-official").val();

    var custfilter = new RegExp(searchword, "ig");
    var repstr = "<span class='mark px-0'>" + searchword + "</span>";

    if (searchword != "") {
        jQuery('.tab-pane .col-md-6 div').each(function() {
            jQuery(this).html(jQuery(this).html().replace(custfilter, repstr));
        })
    }
}
jQuery(".search-official1").on("keypress", function() {
	if (event.keyCode === 13 && jQuery(this).val()!='') {
		
		
		 var  officialPayload=[];
		 var searchOfficial='';
				 searchOfficial = {
			"searchText": jQuery(this).val();
		 };
			officialPayload.push( searchOfficial ); 
			 officialPayload = JSON.stringify(officialPayload[0]); 
			 console.log();
		const options = {
				method: 'POST',
				headers: {
				  'Content-Type': 'application/json',
				  'tenant-code':''
				},
				body: officialPayload
			  };
			  
			  const apiUrl ='https://engagifii-preview6-billtracking.azurewebsites.net/api/1.0/legislative/public-bills/elected/officials-all-tabs-list';
			  fetch(apiUrl,options)
				.then(response => {
				  if (!response.ok) {
					throw new Error('Network response was not ok');
				  }
				  return response.json();
				})
				.then(data => {
				  console.log('API response data:', data);
				  
			   		
						  
						})
				.catch(error => {
				  console.error('There has been a problem with your fetch operation:', error);
				});
		
		
		
		
   /* var value = jQuery(this).val().toLowerCase();
         var val = value.trim();
         val = val.replace(/\s+/g, '');
		 
	if(val.length > 3) { //for checking 3 characters
   	 jQuery(".tab-pane .col-md-6 div").filter(function() {
		if(jQuery(this).text().toLowerCase().indexOf(value) > -1) {
			jQuery(this).parents('.col-md-6').removeClass('d-none');	
		} else {
			jQuery(this).parents('.col-md-6').addClass('d-none');	
		}
    });
replaceText();
} else{
	 jQuery(".tab-pane .col-md-6").removeClass('d-none');
	 jQuery(".mark").each(function() {
   		 jQuery(this).replaceWith(this.childNodes);
 	 });	
}
	}
  });*/
  
	  </script>