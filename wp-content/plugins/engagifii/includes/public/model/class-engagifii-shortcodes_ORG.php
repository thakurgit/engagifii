<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Endorsement shortcode
 *
 * @class       Engagifii_Shortcodes
 * @version     1.0.0
 * @package     Engagifii/Classes
 * @category    Class
 * @author      Engagifii
 */
class Engagifii_Shortcodes extends abstractModelEngagifii{
		/**
	 * Init shortcode
	 */
	public $basePath;
	public function __construct($path){
		parent::__construct();
		$this->basePath=$path;		
	}
	public function init() {
		$shortcodes = array(
			'endorsement-grid-list' =>  'endorsement_grid_view',
			'endorsement_grid_detail_information' =>  'endorsement_grid_details',
			'endorsement-details' => 'endorsement_details',
			'endorsement-list-calendar' => 'endorsement_list_calender_grid_view',
			'legislation-list' 		       =>  'legislation_grid_view',			
			'legislation_grid_detail_information' =>  'legislation_grid_details',
			'legislation-details' => 'legislation_details',
			'courses-list' => 'courses_grid_view',
			'courses-detail' => 'courses_detail',
			'course-details' => 'course_details_id',
			'classes-list' => 'classes_grid_view',
			'classes-list-calendar' => 'classes_list_calender_grid_view',
			'classes-detail' => 'classes_detail',
			'class-details' => 'class_details_id',
			'legislation-lastaction'=> 'legislation_last_action_filter',
			'legislation-tags'      => 'legislation_tag_filter',
			'legislation-staffmember' => 'legislation_member_filter',
			'legislation-tracking'    => 'legislation_tracking_filter',
			'legislation-search-billnumber' => 'legislation_bill_number',
			'legislative-issue' => 'legislation_issue',
			'bill-count'        => 'bill_count',
			'course-count'      => 'course_count',
			'class-count'       => 'class_count',
			'endorsement-count' => 'endorsement_count',
			'class-calendar'    => 'class_calendar',
			'endorsement-calendar'    => 'endorsement_calendar',
			'class-list-with-pagination' => 'class_list_pagination',

		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode,array($this,$function));
		}

	}


	public function endorsement_grid_view(){
		ob_start();
		include $this->basePath.'includes/public/views/endorsement/grid-listing.php';
		return ob_get_clean();
	}	

	public function endorsement_grid_details(){
		ob_start();
		include $this->basePath.'includes/public/views/endorsement/grid-listing-details.php';
		return ob_get_clean();
	} 

	public function endorsement_details($attr){
		ob_start();
		extract(shortcode_atts(array(
		     'endId' => $attr['id']
		    ), $attr));

		include $this->basePath.'includes/public/views/endorsement/grid-listing-details.php';
		return ob_get_clean();
	}

	public function endorsement_list_calender_grid_view($attr)
	{
		ob_start();
		extract(shortcode_atts(array(
		     'calendar' => true
		    ), $attr));
		include $this->basePath.'includes/public/views/endorsement/grid-listing.php';
		return ob_get_clean();
	}

	
	

	public function legislation_grid_view(){
		ob_start();
		include $this->basePath.'includes/public/views/legislation/grid-listing.php';
		return ob_get_clean();
	}	

	public function legislation_grid_details(){
		ob_start();
		include $this->basePath.'includes/public/views/legislation/grid-listing-details.php';
		return ob_get_clean();
	} 

	public function legislation_details($attr){
		ob_start();
		extract(shortcode_atts(array(
		     'billId' => $attr['id']
		    ), $attr));

		include $this->basePath.'includes/public/views/legislation/grid-listing-details.php';
		return ob_get_clean();
	}

	public function courses_grid_view()
	{
		ob_start();
		include $this->basePath.'includes/public/views/courses/grid-listing.php';
		return ob_get_clean();
	}

	public function courses_detail($attr){
		ob_start();
		include $this->basePath.'includes/public/views/courses/grid-listing-details.php';
		return ob_get_clean();
	}

	public function course_details_id($attr){
		ob_start();
		extract(shortcode_atts(array(
		     'courseId' => $attr['id']
		    ), $attr));
		
		include $this->basePath.'includes/public/views/courses/grid-listing-details.php';
		return ob_get_clean();
	}


	public function classes_grid_view()
	{
		ob_start();
		include $this->basePath.'includes/public/views/classes/grid-listing.php';
		return ob_get_clean();
	}


	public function classes_list_calender_grid_view($attr)
	{
		ob_start();
		extract(shortcode_atts(array(
		     'calendar' => true
		    ), $attr));
		include $this->basePath.'includes/public/views/classes/grid-listing.php';
		return ob_get_clean();
	}

	public function class_list_pagination($attr){
		ob_start();
		extract(shortcode_atts(array(
		     'records' => $attr['records']
		    ), $attr));
		include $this->basePath.'includes/public/views/classes/grid-listing.php';
		return ob_get_clean();

	}

	public function classes_detail($attr){
		ob_start();
		include $this->basePath.'includes/public/views/classes/grid-listing-details.php';
		return ob_get_clean();
	}

	public function class_details_id($attr){
		ob_start();
		extract(shortcode_atts(array(
		     'classId' => $attr['id']
		    ), $attr));
		
		include $this->basePath.'includes/public/views/classes/grid-listing-details.php';
		return ob_get_clean();
	}

	public function legislation_last_action_filter()
	{

		ob_start();
		include $this->basePath.'includes/public/views/shortcode/lastaction.php';
		return ob_get_clean();
	}

	public function legislation_tag_filter()
	{

		ob_start();
		include $this->basePath.'includes/public/views/shortcode/tags.php';
		return ob_get_clean();
	}

	public function legislation_tracking_filter()
	{

		ob_start();
		include $this->basePath.'includes/public/views/shortcode/trackingLevel.php';
		return ob_get_clean();
	}

	public function legislation_member_filter()
	{

		ob_start();
		include $this->basePath.'includes/public/views/shortcode/staffmember.php';
		return ob_get_clean();
	}

	public function legislation_bill_number(){

		ob_start();
		include $this->basePath.'includes/public/views/shortcode/billnumber.php';
		return ob_get_clean();	
	}

	public function legislation_issue(){

		ob_start();
		include $this->basePath.'includes/public/views/shortcode/legislative-issue.php';
		return ob_get_clean();	
	}

	public function bill_count(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/bill-count.php';
		return ob_get_clean();
	}
	
	public function course_count(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/course-count.php';
		return ob_get_clean();
	}

	public function class_count(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/class-count.php';
		return ob_get_clean();
	}

	public function endorsement_count(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/endorsement-count.php';
		return ob_get_clean();
	}

	public function class_calendar(){
		ob_start();
		include $this->basePath.'includes/public/views/classes/calendar.php';
		return ob_get_clean();
	}
	//added by guru
	public function endorsement_calendar(){
		ob_start();
		include $this->basePath.'includes/public/views/endorsement/calendar.php';
		return ob_get_clean();
	}
	//end here
}
$this->engagifiiShortcode  = new Engagifii_Shortcodes($this->pluginBasePath);