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
			 	//echo "<pre>"; print_r($collection); echo "</pre>";
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
		//print_r("HEllo response<br/>");
		//print_r($dataResponse);
		//print_r("HEllo response<br/>");

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
	
	public function getSessionsData(){
		$postData = array();
 	   $apiUrl = 'legislative/public-bills/sessions/';	
		$dataResponse = $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
		//print_r("HEllo response<br/>");
		//print_r($dataResponse);
		//die;
		//print_r("HEllo response<br/>");

		
			return $dataResponse[api_response];
		
    	
	}


}
return new adminDataColumn();