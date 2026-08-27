<?php  
/*
* Class: endorsement column List
* Fetch All column List
* Since V1.0.0
*/

defined( 'ABSPATH' ) || exit;

class adminDataColumn extends Engagifii_API{
	protected $dbObj;

	public function __construct(){		
		global $wpdb;
		$this->dbObj=$wpdb;
		add_action('wp_ajax_nopriv_admcolumnconfig',array($this,'getColumnData'));
		add_action('wp_ajax_admcolumnconfig',array($this,'getColumnData'));		
		add_action('wp_ajax_nopriv_endorsementList',array($this,'getColumnData'));
		add_action('wp_ajax_endorsementList',array($this,'getColumnData'));	
		add_action('wp_ajax_nopriv_coursesList',array($this,'getCourseColumnData'));
		add_action('wp_ajax_coursesList',array($this,'getCourseColumnData'));	
		add_action('wp_ajax_nopriv_classesList',array($this,'getClassColumnData'));
		add_action('wp_ajax_classesList',array($this,'getClassColumnData'));	
		add_action('wp_ajax_nopriv_classesType',array($this,'getClassesType'));
		add_action('wp_ajax_classesType',array($this,'getClassesType'));	
		add_action('wp_ajax_nopriv_eventsList',array($this,'getEventsColumnData'));
		add_action('wp_ajax_eventsList',array($this,'getEventsColumnData'));	
		add_action('wp_ajax_nopriv_eventsType',array($this,'getEventsType'));
		add_action('wp_ajax_eventsType',array($this,'getEventsType'));	
		add_action('wp_ajax_nopriv_eventsClassList',array($this,'getAllCommonColumnList'));
		add_action('wp_ajax_eventsClassList',array($this,'getAllCommonColumnList'));	
		add_action('wp_ajax_nopriv_legislationSessions',array($this,'getSessionsData'));
		add_action('wp_ajax_legislationSessions',array($this,'getSessionsData'));	
		add_action('wp_ajax_nopriv_legislationList',array($this,'getLegislationColumnData'));
		add_action('wp_ajax_legislationList',array($this,'getLegislationColumnData'));	
		add_action('wp_ajax_nopriv_legislationTags',array($this,'legislationTagsFilter'));
		add_action('wp_ajax_legislationTags',array($this,'legislationTagsFilter'));	
		add_action('wp_ajax_nopriv_legislationMembers',array($this,'legislationAssignToFilter'));
		add_action('wp_ajax_legislationMembers',array($this,'legislationAssignToFilter'));	
		add_action('wp_ajax_nopriv_legislationGroups',array($this,'legislationGroupsFilter'));
		add_action('wp_ajax_legislationGroups',array($this,'legislationGroupsFilter'));	
		add_action('wp_ajax_nopriv_legislationMemberTags',array($this,'legislationAssignToTagFilter'));
		add_action('wp_ajax_legislationMemberTags',array($this,'legislationAssignToTagFilter'));	
		add_action('wp_ajax_nopriv_legislationTabs',array($this,'legislationTabs'));
		add_action('wp_ajax_legislationTabs',array($this,'legislationTabs'));	
		add_action('wp_ajax_nopriv_groupColumns',array($this,'groupColumns'));
		add_action('wp_ajax_groupColumns',array($this,'groupColumns'));	
		add_action('wp_ajax_nopriv_orgColumns',array($this,'orgColumns'));
		add_action('wp_ajax_orgColumns',array($this,'orgColumns'));
		add_action('wp_ajax_nopriv_orgDetailColumns',array($this,'orgDetailColumns'));
		add_action('wp_ajax_orgDetailColumns',array($this,'orgDetailColumns'));
		
	}

	public function getColumnData(){
		$dataResponse = $this->submitApiRequest("Public/EndorsementColumnList",array(),"GET",'endorsement');
		if(isset($dataResponse['api_response']))
		{
			$collection   = json_decode($dataResponse['api_response']);
			unset($collection[0]);
			unset($collection[6]);
			unset($collection[8]);
			unset($collection[9]);
	    	//return $collection;
			wp_send_json($collection);
		}
		else
			return array();
		
	}
	public function getLegislationColumnData(){
		$postedData=array();
			 $dataResponse = $this->submitApiRequest("legislative/public-bills/column-list",$postedData,"GET",'legislation');
			 if(isset($dataResponse['api_response'])){
					 $collection = json_decode($dataResponse['api_response']);
			 	//return $collection;
				wp_send_json($collection->columnList); 	
			 }else
			  return array();
			
	}

	public function getCourseColumnData(){

		$dataResponse = $this->submitApiRequest("Public/CourseColumnList",array(),"GET",'courses');
		
		if(isset($dataResponse['api_response']))
		{
			$collection   = json_decode($dataResponse['api_response']);
			unset($collection[0]);
			unset($collection[1]);
			unset($collection[5]);
			unset($collection[8]);
    		//return $collection;
			wp_send_json($collection);
		}
		else
			return array();
	}

	public function getClassColumnData(){
		$options = get_option( 'ebt_api_settings' );
		$tenantCode = $options['dashboard_tenant_code'];
		$dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
		if(isset($dataResponse['api_response'])){
			$collection   = json_decode($dataResponse['api_response']);
			unset($collection[0]);
			unset($collection[1]);
			unset($collection[7]);
			unset($collection[8]);
			if($tenantCode == 'psba'){ 
			   $nextKey = max(array_keys($collection)) + 1;
			  $collection[$nextKey] = [
				  'colName' => 'talentLms',
				  'displayName' => 'Access Class',
			  ];
			}
			//return $collection;
			wp_send_json($collection);
		}
		else
			return array();    	
	}
public function getClassesType($date){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'public/GetObjectTypesForFilter/'.date('Y-m-d');
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response'], true);
		//return $responseArray;
			wp_send_json($responseArray);
	}

	public function getEventsColumnData(){

		$dataResponse = $this->submitApiRequest("Public/EventColumnList",array(),"GET",'event');
		if(isset($dataResponse['api_response'])){
			$allowedColNames = ['name', 'city', 'tags', 'eventClasses', 'register', 'eventStatus', 'eventType', 'startDateTime'];
			$collection   = json_decode($dataResponse['api_response'], true);
			$collection  = array_filter($collection, function ($item) use ($allowedColNames) {
            	return in_array($item['colName'] ?? '', $allowedColNames, true);
			});
			foreach ($collection as &$item) {
            if (isset($item['colName']) && $item['colName'] === 'startDateTime') {
                $item['displayName'] = 'Event Schedule';
            }
        }
		//return $collection;
			wp_send_json($collection );
		}
		else
			return array();
	}
	public function getEventsType($date){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'public/event-type';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'event');
		$responseArray = json_decode($response['api_response'], true);
		//return $responseArray;
			wp_send_json($responseArray);
	}
	public function getAllCommonColumnList(){

		$dataResponse = $this->submitApiRequest("Public/AllCommonColumnList",array(),"GET",'classes');
		if(isset($dataResponse['api_response'])){
			$allowedColNames = ['name', 'entity', 'city', 'tags', 'register', 'status', 'Type', 'startDateTime'];
			$collection   = json_decode($dataResponse['api_response'],true);
			$collection  = array_filter($collection, function ($item) use ($allowedColNames) {
            	return in_array($item['colName'] ?? '', $allowedColNames, true);
			});
			foreach ($collection as &$item) {
            if (isset($item['colName']) && $item['colName'] === 'startDateTime') {
                $item['displayName'] = 'Schedule';
            }
			if (isset($item['colName']) && $item['colName'] === 'city') {
                $item['displayName'] = 'Location';
            }
		  }
		//return $collection;
			wp_send_json($collection );
		}
		else
			return array();
		
    	
	}
	
	public function getSessionsData(){
		$postData = array();
 	   $apiUrl = 'legislative/public-bills/sessions/';	
		$dataResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
		if(isset($dataResponse['api_response'])){
		  $collection   = json_decode($dataResponse['api_response'],true);
		  usort($collection, function($a, $b) {
			  return $b['sessionId'] <=> $a['sessionId'];
		  });
		  //return $dataResponse['api_response'];
		  wp_send_json($collection);
		} else {
			return array();
		}	
	}
	public function getDashboardFieldData($tenantCode){
		$postData = array();
 	   $apiUrl = 'GetPersonProfileFields/'.$tenantCode;	
		$dataResponse = $this->submitApiRequest($apiUrl,$postData, "GET",'dashboard');
		return $dataResponse['api_response'];
	}
	public function getOrganizationsColumnData(){
		$postData = array("Organizations");
 	   $apiUrl = 'exportPeople/get/personFields/';	
		$dataResponse = $this->submitApiRequest($apiUrl,$postData, "POST",'dashboard');
		return $dataResponse['api_response'];
	}
	public function groupColumns(){
		$options = get_option( 'ebt_api_settings' );
		$tenantCode = $options['dashboard_tenant_code'];
		$dataResponse = $this->submitApiRequest("PeopleColumnList/",array(),"GET",'dashboard');
		if(isset($dataResponse['api_response'])){
			$response   = json_decode($dataResponse['api_response'], true);
			$withoutFieldId = [];
		  $withFieldId = [];
		  foreach ($response as $item) {
			  if (empty($item['fieldId'])) {
				  $withoutFieldId[] = $item;
			  } else {
				  $withFieldId[] = $item;
				//   if(!isset($item['controlTypeId'])) {
				// 	  $item['controlTypeId'] = 3;
				//   }
			  }
		  }
		  $collection = array_merge($withoutFieldId, $withFieldId);
			wp_send_json($collection );
		}
		else
			return array();
	}
	public function orgColumns(){
		$options = get_option( 'ebt_api_settings' );
		$tenantCode = $options['dashboard_tenant_code'];
		$dataResponse = $this->submitApiRequest("OrganizationColumnListWithCF/".$tenantCode,array(),"GET",'dashboard');
		if(isset($dataResponse['api_response'])){
			$excludedCols = ['Id', 'IsFavorite', 'IsTenantDefault', 'TimeZone', 'LocationInfo', 'ActiveMembers', 'ChildCount', 'isCurrent', 'childCount', 'ImageThumbUrl', 'SecondaryEmails'];
			// Custom field colNames to hide: Logo (always shown with org name) and Website (duplicate of system Website field)
			$excludedCustomFieldColNames = ['Logo', 'Website'];
			$collection   = json_decode($dataResponse['api_response'],true);
			$collection  = array_filter($collection, function ($item) use ($excludedCols, $excludedCustomFieldColNames) {
				$colName = $item['colName'] ?? '';
				$fieldId = $item['fieldId'] ?? $colName;
				if (in_array($colName, $excludedCols, true)) return false;
				// Hide custom fields whose colName is in the exclusion list (identified by fieldId !== colName)
				if (in_array($colName, $excludedCustomFieldColNames, true) && strcasecmp($fieldId, $colName) !== 0) return false;
				return true;
			});
			foreach ($collection as &$item) {
            if (isset($item['colName']) && $item['colName'] === 'TotalMembers') {
                $item['displayName'] = 'Total/Active Members';
            }
		  }
		  $collection = array_values($collection);
		  // Ensure Name is always first in the list
		  $nameIndex = null;
		  foreach ($collection as $i => $item) {
			  if (($item['colName'] ?? '') === 'Name') { $nameIndex = $i; break; }
		  }
		  if ($nameIndex !== null && $nameIndex !== 0) {
			  $nameItem = array_splice($collection, $nameIndex, 1);
			  array_unshift($collection, $nameItem[0]);
		  }
		  // Deduplicate by colName, keeping the first (highest-priority) occurrence
		  $seenColNames = [];
		  $collection = array_values(array_filter($collection, function($item) use (&$seenColNames) {
			  $colName = $item['colName'] ?? '';
			  if (in_array($colName, $seenColNames, true)) return false;
			  $seenColNames[] = $colName;
			  return true;
		  }));
		  // Inject OrganizationTags — not returned by API but supported in display
		  if (!in_array('OrganizationTags', $seenColNames, true)) {
			  $collection[] = ['colName' => 'OrganizationTags', 'displayName' => 'Organization Tags', 'fieldId' => 'OrganizationTags'];
		  }
			wp_send_json($collection);
			
		}
		else
			return array();
	}
	public function orgDetailColumns(){
		$options = get_option( 'ebt_api_settings' );
		$tenantCode = $options['dashboard_tenant_code'];
		$dataResponse = $this->submitApiRequest("OrganizationColumnListWithCF/".$tenantCode,array(),"GET",'dashboard');
		if(!isset($dataResponse['api_response'])){
			return array();
		}

		$excludedCols = ['Id', 'IsFavorite', 'IsTenantDefault', 'TimeZone', 'LocationInfo', 'ActiveMembers', 'ChildCount', 'isCurrent', 'childCount', 'ImageThumbUrl', 'SecondaryEmails', 'Name'];
		$excludedCustomFieldColNames = ['Logo', 'Website'];
		$collection = json_decode($dataResponse['api_response'], true);
		$collection = array_filter($collection, function ($item) use ($excludedCols, $excludedCustomFieldColNames) {
			$colName = $item['colName'] ?? '';
			$fieldId = $item['fieldId'] ?? $colName;
			if (in_array($colName, $excludedCols, true)) {
				return false;
			}
			if (in_array($colName, $excludedCustomFieldColNames, true) && strcasecmp($fieldId, $colName) !== 0) {
				return false;
			}
			return true;
		});

		foreach ($collection as &$item) {
			if (isset($item['colName']) && $item['colName'] === 'TotalMembers') {
				$item['displayName'] = 'Total/Active Members';
			}
		}
		unset($item);

		$collection = array_values($collection);
		$seenColNames = [];
		$collection = array_values(array_filter($collection, function($item) use (&$seenColNames) {
			$colName = $item['colName'] ?? '';
			if ($colName === '' || in_array($colName, $seenColNames, true)) {
				return false;
			}
			$seenColNames[] = $colName;
			return true;
		}));

		$detailOnlyFields = [
			['colName' => 'Overview', 'displayName' => 'Overview', 'fieldId' => 'Overview'],
			['colName' => 'SocialPages', 'displayName' => 'Social Pages', 'fieldId' => 'SocialPages'],
			['colName' => 'WebsiteContacts', 'displayName' => 'Contacts', 'fieldId' => 'WebsiteContacts'],
		];
		foreach ($detailOnlyFields as $detailField) {
			if (!in_array($detailField['colName'], $seenColNames, true)) {
				$collection[] = $detailField;
				$seenColNames[] = $detailField['colName'];
			}
		}

		wp_send_json($collection);
	}
	public function legislationTabs(){
		$options = get_option( 'ebt_api_settings' );
		$tenant_code = $options['lbt_tenant_code']['tenant_code'] ?? '';
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
	  if ($tenant_code == 'mabe') {
		  $legislation_tabs['staffanalysis'] = 'MABE Notes';
	  }
	  // Show MACo Analysis tab only for tenant_code 'maco'
	  if ($tenant_code == 'baltimorecountymd' ||
		  $tenant_code == 'princegeorgescountymd' ||
		  $tenant_code == 'howardcountymd' ||
		  $tenant_code == 'mcmd') {
		  $legislation_tabs['macoanalysis'] = 'MACo Analysis';
	  }
	   $results = [];
		foreach ($legislation_tabs as $key => $label) {
			$results[] = (object)[
				'colName'   => $key,
				'displayName' => $label
			];
		}
		wp_send_json($results);
	}
}
return new adminDataColumn();