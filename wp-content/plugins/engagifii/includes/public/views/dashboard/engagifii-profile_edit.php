<?php 
ini_set('session.gc_maxlifetime', 3600);
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
	//$peopleDATA = json_decode('{"people":{"id":"dd8e61f5-9cd0-4b9a-809a-f0573f2fa74f","pid":"GJWK4764","email":"ghj2@yopmail.com","isFavorite":false,"imageThumbUrl":"https:\/\/ssresource.azureedge.net\/resource\/crm\/2bff7818-8c2c-4c73-a626-5ef42bec9c17.png","salutation":"","firstName":"GHJ2","middleName":"Min","lastName":"Junior G","fullName":"GHJ2 Junior G","suffix":"","timeZone":null,"isActive":true,"isUnsubscribed":false,"personaTypeId":4,"personTypes":[{"id":"ac261b8b-5062-4acb-8dc5-97f10c6aa6bb","name":"Non-Member"}],"isInstructor":false,"identityId":"b5cf6ece-353c-4ab1-a129-4780a4b5e92d","userStatus":4,"invitationExpirationDate":"2024-03-14T03:15:21.7959338","isStarredMember":false,"starredTypes":[],"createdDate":"2024-02-13T02:15:14.0490797","modifiedDate":"0001-01-01T00:00:00","primaryEmail":{"value":"ghj2@yopmail.com","type":"Office Email","isUnsubscribe":false,"unsubscribedBy":null,"unsubscribedOn":"0001-01-01T00:00:00","unsubscribedById":"00000000-0000-0000-0000-000000000000"},"primaryPhoneNumber":{"value":"5559998888","type":"Office Phone","isUnsubscribe":false,"unsubscribedBy":null,"unsubscribedOn":"0001-01-01T00:00:00","unsubscribedById":"00000000-0000-0000-0000-000000000000"},"secondaryEmails":[],"secondaryPhoneNumbers":[{"value":"5557778888","type":"Home Phone","isUnsubscribe":false,"unsubscribedBy":null,"unsubscribedOn":"0001-01-01T00:00:00","unsubscribedById":"00000000-0000-0000-0000-000000000000"}],"mappingId":null,"isBillingContact":false,"requestStatus":0,"primaryOrganizationId":"7e6729e7-b21b-432f-85ce-48eacde00aad","primaryOrganization":{"id":"7e6729e7-b21b-432f-85ce-48eacde00aad","name":"Abington Heights School District","imageThumbUrl":"https:\/\/ssresource.azureedge.net\/resource\/organization\/847b4b19-9e08-4f6f-9fba-6111da6aa6d2.png","isActive":true},"organizationsList":null,"systemDefinedRole":null,"totalTimeWorked":9,"roles":[{"id":1587,"name":"View Only","personTypes":[{"id":4,"name":"Non-Member","icon":null}]},{"id":1585,"name":"Board Secretary","personTypes":[]}]},"tags":[],"tabs":[{"id":"c46c3f0f-361d-423d-3a1f-08da42ccd2eb","displayName":"Committees","isSystemGenerated":true,"sequence":2,"isFirstTab":false,"groupFields":null},{"id":"72af1686-a350-47ee-98a4-9bb170e10e6c","displayName":"Associated Items","isSystemGenerated":true,"sequence":2,"isFirstTab":false,"groupFields":null},{"id":"2bc0f0f5-e5e0-4fd2-a29d-d52297300bba","displayName":"Basic Information","isSystemGenerated":true,"sequence":1,"isFirstTab":true,"groupFields":[{"id":"b9547e35-5329-41f1-7d08-08d9f214c244","name":"Contact Information","sequence":1,"isFirstTab":true,"fields":[{"id":"5d5a02eb-df6c-46cf-b442-0e6e61b6a9f3","name":"Office Address","controlTypeId":9,"fieldDataTypeId":1,"sequence":12,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":"{\"address\":\"International sale\",\"addressLine2\":\" AMAC\",\"city\":\"Abuja\",\"state\":\"Federal Capital Territory\",\"country\":\"Nigeria\",\"lat\":9.006258899999999,\"lng\":7.2695056,\"locationName\":\"Nnamdi Azikiwe International Airport\"}","isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"3510aa84-4b6d-4428-9085-6bc1bf365dda","name":"Home Phone","controlTypeId":11,"fieldDataTypeId":1,"sequence":7,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":"5557778888","isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"023aa2ae-b78f-4643-82ff-6cd69bfe3acb","name":"Nickname","controlTypeId":4,"fieldDataTypeId":1,"sequence":11,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":"jack","isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"e38fc768-3cc2-4619-b022-72465bc76057","name":"Office Email","controlTypeId":10,"fieldDataTypeId":1,"sequence":3,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":"ghj2@yopmail.com","isPrimary":true,"isMandatory":true,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"6da1a4b9-492c-4c12-bc5f-ce47f053e41e","name":"Office Phone","controlTypeId":11,"fieldDataTypeId":1,"sequence":5,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":"5559998888","isPrimary":true,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"33466b5a-6962-49eb-9abd-f682f8bbde5c","name":"Address","controlTypeId":9,"fieldDataTypeId":1,"sequence":9,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":"{\"address\":\"400 Bizzell Street\",\"addressLine2\":\"ADMN Brazos County\",\"city\":\"College Station\",\"state\":\"TX\",\"country\":\"United States\",\"zipCode\":\"77843\",\"lat\":30.6186806,\"lng\":-96.33646549999999,\"locationName\":\"Texas A&M University\"}","isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"20f0e87e-235c-4c37-b067-08082796a3a7","name":"Extension","controlTypeId":8,"fieldDataTypeId":1,"sequence":6,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"0468f5c7-9195-4a0d-a311-4690454dcb61","name":"Cell Phone","controlTypeId":11,"fieldDataTypeId":1,"sequence":8,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"c78c88ec-5666-4000-9f38-49f057b614d7","name":"Price List","controlTypeId":6,"fieldDataTypeId":1,"sequence":10,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"f35e06c7-d2ef-48c8-b723-6f6089e19b38","name":"Status","controlTypeId":6,"fieldDataTypeId":1,"sequence":1,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false}]},{"id":"88b93960-cad0-4de3-b829-57775144582c","name":"Board Service","sequence":3,"isFirstTab":true,"fields":[{"id":"bd17ecb3-039d-45b8-b836-51cd32fdfd62","name":"Board Service Term 3","controlTypeId":2,"fieldDataTypeId":1,"sequence":3,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"12e1a378-4d47-4036-ac81-7ec18cfe81aa","name":"Board Service Term 2","controlTypeId":2,"fieldDataTypeId":1,"sequence":2,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"47f90699-17cf-4be2-a247-cb9f28cbc3f6","name":"Board Service Term 1","controlTypeId":2,"fieldDataTypeId":1,"sequence":1,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"19d0474f-2fd8-40b6-a893-f4b914065287","name":"Board Service Term 4","controlTypeId":2,"fieldDataTypeId":1,"sequence":4,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false}]},{"id":"007ed9fc-0089-41d9-a5ef-78013a03386c","name":"Positions","sequence":2,"isFirstTab":true,"fields":[{"id":"008eea74-6111-4f61-953d-8481e97de444","name":"Organization","controlTypeId":12,"fieldDataTypeId":1,"sequence":1,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[{"id":"6e483501-b10a-421c-bd66-019d92fc84f3","name":"Total Time Worked","controlTypeId":17,"fieldDataTypeId":1,"sequence":1,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":true,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"c237819c-3700-43a2-b694-09d343482f4a","name":"Department","controlTypeId":14,"fieldDataTypeId":1,"sequence":2,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":true,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Organization\/GetAllDepartmentOptionsByTenant","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"3e7f8c08-9f8b-47a8-a460-e55ea8837b17","name":"Position","controlTypeId":13,"fieldDataTypeId":1,"sequence":1,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":true,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Organization\/GetAllOrganizationPositionsByTenant","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"abef9ef6-b1f7-4c57-8011-fcbe8250b4b6","name":"Position Dates","controlTypeId":16,"fieldDataTypeId":1,"sequence":1,"validationExpression":null,"defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":true,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false}],"hasPrimaryButton":true,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Organization\/GetAllOrganizations","selectedValue":"[{\"id\":\"7E6729E7-B21B-432F-85CE-48EACDE00AAD\",\"name\":\"Abington Heights School District\",\"imageThumbUrl\":\"https:\/\/ssresource.azureedge.net\/resource\/organization\/847b4b19-9e08-4f6f-9fba-6111da6aa6d2.png\",\"childOrg\":null,\"parentOrg\":null,\"childOrgs\":null,\"parentOrgs\":null,\"isChecked\":true,\"isPrimary\":true,\"isIconBtn\":false,\"positionHistory\":[{\"organizationId\":\"7E6729E7-B21B-432F-85CE-48EACDE00AAD\",\"positionId\":\"b215ca59-be31-4abc-a2a5-1a3df9ffc2a4\",\"positionName\":\"Administrative Assistant - Superintendent\",\"departmentId\":\"a0ead330-8bb3-4a36-a6a3-425e9ac2e4e9\",\"departmentName\":\"PSBA Finance and Operations\",\"isCurrent\":true,\"startDate\":\"Wed May 10 2023\",\"endDate\":\"2024-02-19T07:07:59.618Z\",\"years\":0,\"months\":9,\"isShow\":true},{\"organizationId\":\"7E6729E7-B21B-432F-85CE-48EACDE00AAD\",\"positionId\":\"8b6e0c04-4111-0868-10cc-800cab9ea365\",\"positionName\":\"Board Member\",\"departmentId\":\"157b2e29-1713-4371-b566-533774557b09\",\"departmentName\":\"PSBA Leadership\",\"isCurrent\":false,\"startDate\":\"Mon Jun 12 2023\",\"endDate\":\"Tue Feb 06 2024\",\"years\":0,\"months\":7,\"isShow\":true}],\"totalTimeWorked\":\"9 mos\",\"isFieldRequired\":false,\"notShowBtn\":false,\"show\":\"Show All Past Positions\",\"showBtn\":true},{\"id\":\"49A841BB-0856-4776-80EF-2807C721790E\",\"name\":\"Cumberland Valley S D\",\"imageThumbUrl\":\"\/assets\/images\/org-list-grey.png\",\"childOrg\":null,\"parentOrg\":null,\"childOrgs\":null,\"parentOrgs\":null,\"isChecked\":true,\"isIconBtn\":true,\"positionHistory\":[{\"organizationId\":\"49A841BB-0856-4776-80EF-2807C721790E\",\"positionId\":\"a4186acf-7891-01e4-1f10-4e115d1eff8e\",\"positionName\":\"Legislative Analyst\",\"departmentId\":\"c28436ad-a60a-41dc-9960-201ffb0bd1ab\",\"departmentName\":\"PSBA Legal\",\"isCurrent\":true,\"startDate\":\"Sat Jul 01 2023\",\"endDate\":\"\",\"years\":0,\"months\":7,\"isShow\":true}],\"totalTimeWorked\":\"7 mos\",\"isFieldRequired\":false,\"notShowBtn\":false,\"show\":\"Show All Past Positions\",\"showBtn\":true}]","isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"4f996120-48ad-4ebb-9c8f-533eeaa97de4","name":"PSBA Liaison","controlTypeId":6,"fieldDataTypeId":1,"sequence":2,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false}]},{"id":"c443a2c9-c937-4fbd-adc0-7bcc8363d0b7","name":"Lagacy Information","sequence":4,"isFirstTab":true,"fields":[{"id":"1424fafb-83d3-4a1b-b40a-156f101bea98","name":"Solicitor For","controlTypeId":4,"fieldDataTypeId":1,"sequence":7,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"1de37438-e19e-47da-b3ae-2676b11ff198","name":"Left Position Reason","controlTypeId":4,"fieldDataTypeId":1,"sequence":6,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"68ee4612-a65b-481d-8de6-2e26223236e2","name":"LMS Eligible","controlTypeId":3,"fieldDataTypeId":1,"sequence":4,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"dbab78d8-deae-4637-a816-441bde5998f8","name":"Can Register Others","controlTypeId":3,"fieldDataTypeId":1,"sequence":5,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"15d9d467-d213-442e-babc-498ba7b7c13f","name":"Original Entry Date","controlTypeId":1,"fieldDataTypeId":1,"sequence":9,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"d3304060-62b6-4800-88d0-70be4e02fc34","name":"Legacy Contact ID - GUID","controlTypeId":4,"fieldDataTypeId":1,"sequence":1,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"14f2545c-f5c2-4d9d-a417-84df1e4817e5","name":"PSBA Web Access","controlTypeId":3,"fieldDataTypeId":1,"sequence":3,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"598961f7-23b4-4ebd-a457-b124ae30df25","name":"PSBA Region","controlTypeId":3,"fieldDataTypeId":1,"sequence":8,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":"\/api\/v1.0\/Settings\/GetFieldPossibleValues\/{fieldId}\/{personaTypeId}","selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false},{"id":"17ebb526-1e1c-4736-b229-d87d2d31a12d","name":"Altai Member ID","controlTypeId":8,"fieldDataTypeId":1,"sequence":2,"validationExpression":"","defaultValue":null,"possibleValues":null,"relatedFields":[],"hasPrimaryButton":false,"placeholder":null,"serviceUrl":null,"selectedValue":null,"isPrimary":false,"isMandatory":false,"isUnsubscribed":false,"unsubscribedBy":null,"unsubscribedById":"00000000-0000-0000-0000-000000000000","unsubscribedOn":"0001-01-01T00:00:00","isVisible":false}]}]},{"id":"22d11238-3945-4731-9532-d9e7d2feb596","displayName":"Library","isSystemGenerated":true,"sequence":3,"isFirstTab":false,"groupFields":null}],"personDetailType":0}');
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
              <?php  foreach ($peopleDATA->tabs[$infoseq]->groupFields[$groupseq]->fields as $key => $value) {
     if($value->controlTypeId==9){ 
	 $address = json_decode($value->selectedValue,true);
	 ?>
              	<div class="px-3 address-wrap addressGroup<?php echo $key;?> ">
              <h5 class="bg-body-secondary py-2 border-bottom"><?php echo $value->name;?></h5>
              	<div class="row">
                <div class="form-group col-12">
                	<div class="input-group">
                    	 <label class="sr-only" for=""><b><?php echo $value->name;?></b></label>
                        <input type="text" name="" class="form-control text-start locationName" id="locationName" data-value ="<?php  echo  $address['locationName'];?>" value="<?php  echo  $address['locationName'];?>"/>
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2"><i class="fal fa-search"></i></span>
                      </div>
                    </div>

                 
                 </div>
                <div class="form-group col-12">
                  <label for="">Address Line 1</label>
                	<input type="text" name="" class="form-control text-start address" id="address" data-value ="<?php  echo  $address['address'];?>" value="<?php  echo  $address['address'];?>"/>
                    </div>
                <div class="form-group col-12">
                  <label for="">Address Line 2</label>
                	<input type="text" name="" class="form-control text-start addressLine2" id="addressLine2" data-value ="<?php  echo  $address['addressLine2'];?>" value="<?php  echo  $address['addressLine2'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">City</label>
                	<input type="text" name="" class="form-control text-start city" id="city" data-value ="<?php  echo  $address['city'];?>" value="<?php  echo  $address['city'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">State</label>
                	<input type="text" name="" class="form-control text-start state" id="state" data-value ="<?php  echo  $address['state'];?>" value="<?php  echo  $address['state'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">Zip</label>
                	<input type="text" name="" class="form-control text-start zipCode" id="zipCode" data-value ="<?php  echo  $address['zipCode'];?>" value="<?php  echo  $address['zipCode'];?>"/>
                    </div>
                <div class="form-group col-md-4">
                  <label for="">Country</label>
                	<input type="text" name="" class="form-control text-start country" id="country" data-value ="<?php  echo  $address['country'];?>" value="<?php  echo  $address['country'];?>"/>
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
function initializeAutocomplete() {
        var input = document.querySelectorAll('.locationName');
        var options = {
            types: ['establishment','geocode']
        };
        input.forEach(function (element) {
            var autocomplete = new google.maps.places.Autocomplete(element, options);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
				console.log(place);
                var parentDiv = element.closest('.address-wrap');
                parentDiv.querySelector('.address').value = extractAddressComponent(place, 'street_number') + ' ' + extractAddressComponent(place, 'route');
                parentDiv.querySelector('.addressLine2').value = extractAddressComponent(place, 'premise') + ' ' + extractAddressComponent(place, 'administrative_area_level_2');
                parentDiv.querySelector('.city').value = extractAddressComponent(place, 'locality');
                parentDiv.querySelector('.state').value = extractAddressComponent(place, 'administrative_area_level_1');
                parentDiv.querySelector('.zipCode').value = extractAddressComponent(place, 'postal_code');
                parentDiv.querySelector('.country').value = extractAddressComponent(place, 'country');
                element.value = place.formatted_address;
            });
        });
    }
    google.maps.event.addDomListener(window, 'load', initializeAutocomplete);
	</script>
    <?php
/*if ($_SERVER["REQUEST_METHOD"] === "POST") {
$payload = json_decode(file_get_contents("php://input"), true);	
//print_r($payload);
//$DPpayload = json_decode(file_get_contents("php://input"), true);	
//print_r($DPpayload);

}

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
curl_close($curl);*/



$peopleToken = $_SESSION['accesstoken'];
$authentication1 = 'authorization: Bearer '.$peopleToken;
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

