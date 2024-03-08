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
	    	return $collection;
		}
		else
			return array();
		
	}

	public function getLegislationColumnData(){
		$postedData=array();
			 $dataResponse = $this->submitApiRequest("legislative/public-bills/column-list",$postedData,"GET",'legislation');
			 if(isset($dataResponse['api_response'])){
					 $collection = json_decode($dataResponse['api_response']);
			 	return $collection; 	
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
    		return $collection;
		}
		else
			return array();
	}

	public function getClassColumnData(){

		$dataResponse = $this->submitApiRequest("Public/ClassColumnList",array(),"GET",'classes');
		if(isset($dataResponse['api_response'])){
			$collection   = json_decode($dataResponse['api_response']);
			unset($collection[0]);
			unset($collection[1]);
			unset($collection[7]);
			unset($collection[8]);
			return $collection;
		}
		else
			return array();    	
	}


	public function getEventsColumnData(){

		$dataResponse = $this->submitApiRequest("Public/EventColumnList",array(),"GET",'event');
		if(isset($dataResponse['api_response'])){
			$collection   = json_decode($dataResponse['api_response']);
		return $collection;
		}
		else
			return array();
		
    	
	}
	
	public function getSessionsData(){
		$postData = array();
 	   $apiUrl = 'legislative/public-bills/sessions/';	
		$dataResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
		return $dataResponse['api_response'];
	}
	public function getDashboardFieldData(){
		$postData = array();
 	   $apiUrl = 'FieldConfiguration/GetFieldConfigurationByTab/people/2bc0f0f5-e5e0-4fd2-a29d-d52297300bba';	
		$dataResponse = $this->submitApiRequest($apiUrl,$postData, "GET",'dashboardFields');
		return $dataResponse['api_response'];
	}
}
return new adminDataColumn();