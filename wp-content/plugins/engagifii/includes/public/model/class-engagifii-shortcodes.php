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
			//'courses-list-ByPerson' => 'courses_grid_view_byPerson',
			'courses-detail' => 'courses_detail',
			'course-details' => 'course_details_id',
			'classes-list' => 'classes_grid_view',
			'classes-list-js' => 'classes_grid_view2', // For new Class List view
			'classes-list-calendar' => 'classes_list_calender_grid_view',
			'classes-list-calendar-class-name' => 'classes_list_calender_grid_view_classname',
			'classes-detail' => 'classes_detail',
			'class-details' => 'class_details_id',
			'legislation-lastaction'=> 'legislation_last_action_filter',
			'legislation-tags'      => 'legislation_tag_filter',
			'legislation-staffmember' => 'legislation_member_filter',
			'legislation-tracking'    => 'legislation_tracking_filter',
			'legislation-search-billnumber' => 'legislation_bill_number',
			'legislative-issue' => 'legislation_issue',
			'bill-count'        => 'bill_count',
			'sessions'			=> 'sessions',
			'course-count'      => 'course_count',
			'class-count'       => 'class_count',
			'endorsement-count' => 'endorsement_count',
			'class-calendar'    => 'class_calendar',
			'class-calendar-class-name'    => 'class_calendar_class_name',
			'endorsement-calendar'    => 'endorsement_calendar',
			'class-list-with-pagination' => 'class_list_pagination',
			'event-list' => 'event_grid_view',
			//'event-listByPerson' => 'event_grid_view_person',
			'events-details' => 'events_details',
			'events-calendar'    => 'events_calendar',
			'events-list-calendar' => 'events_list_calender_grid_view', 
			'classes-calendar-search' => 'classes_calendar_search',
			'events-calendar-search' => 'events_calendar_search',
			'endorsement-calendar-search' => 'endorsement_calendar_search',
			'public-officials' => 'public_officials',
			'public-officials-new' => 'public_officials_new',
			'public-officials-detail' => 'public_officials_detail',
			'public-officials-old' => 'public_officials_old',
			'legislative-reports' => 'legislative_reports',
			'report-list' => 'report_list',
			'engagifii-profile' => 'engagifii_profile',
			'engagifii-profile-edit' => 'engagifii_profile_edit',
			'engagifii-myTranscript' => 'engagifii_myTranscript',
			'engagifii-myTranscript-detail' => 'engagifii_myTranscript_detail',
			'engagifii-myTranscript-class-detail' => 'engagifii_myTranscript_class_detail',
			'engagifii-members' => 'engagifii_members',
			'engagifii-myEvents' => 'engagifii_myEvents',
			'engagifii-myEvents-detail' => 'engagifii_myEvents_detail',
			'engagifii-myClasses' => 'engagifii_myclasses',
			'engagifii-myClasses-detail' => 'engagifii_myClasses_detail',
			'engagifii-myDownloads' => 'engagifii_myDownloads',
			'engagifii-myHome' => 'engagifii_myHome',
			'people-list' => 'people_list',
			'training-calendar' => 'training_calendar'
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode,array($this,$function));
		}

	}

	public function people_list(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/peoplelist.php';
		return ob_get_clean();
	}	

	public function engagifii_profile(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/engagifii-profile.php';
		return ob_get_clean();
	}	
	public function engagifii_profile_edit(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/engagifii-profile_edit.php';
		return ob_get_clean();
	}		
	public function engagifii_myTranscript(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/my-transcript.php';
		return ob_get_clean();
	}	
	public function engagifii_myTranscript_detail(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/course-detail.php';
		return ob_get_clean();
	}
	public function engagifii_myTranscript_class_detail(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/class-detail.php';
		return ob_get_clean();
	}
	public function engagifii_myEvents(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/events.php';
		return ob_get_clean();
	}	
	public function engagifii_myEvents_detail(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/events-detail.php';
		return ob_get_clean();
	}	
	public function engagifii_myClasses(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/classes.php';
		return ob_get_clean();
	}	
	public function engagifii_myClasses_detail(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/class-detail.php';
		return ob_get_clean();
	}	
	public function engagifii_myDownloads(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/downloads.php';
		return ob_get_clean();
	}
	public function engagifii_myHome(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/welcome-to-dashboard.php';
		return ob_get_clean();
	}
	public function engagifii_members(){
		ob_start();
		include $this->basePath.'includes/public/views/dashboard/members.php';
		return ob_get_clean();
	}		
	public function public_officials(){
		ob_start();
		include $this->basePath.'includes/public/views/public_official/public_officials.php';
		return ob_get_clean();
	}	
	public function public_officials_new(){
		ob_start();
		include $this->basePath.'includes/public/views/public_official/public_officials_new.php';
		return ob_get_clean();
	}
	public function public_officials_old(){
		ob_start();
		include $this->basePath.'includes/public/views/public_official/public_officials_old.php';
		return ob_get_clean();
	}	
	public function public_officials_detail(){
		ob_start();
		include $this->basePath.'includes/public/views/public_official/public_officials_detail.php';
		return ob_get_clean();
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
	/*public function courses_grid_view_byPerson()
	{
		ob_start();
		include $this->basePath.'includes/public/views/courses/grid-listingByPerson.php';
		return ob_get_clean();
	}*/

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

	// For new class list view
	public function classes_grid_view2()
	{
		ob_start();
		include $this->basePath.'includes/public/views/classes/grid-listing-JS.php';
		return ob_get_clean();
	}

	//Class Calendar Search
	public function classes_calendar_search()
	{
		ob_start();
		include $this->basePath.'includes/public/views/classes/calendar-search.php';
		return ob_get_clean();
	}

	//calendar search ends here

	//Events Calendar Search
	public function events_calendar_search()
	{
		ob_start();
		include $this->basePath.'includes/public/views/events/calendar-search.php';
		return ob_get_clean();
	}

	//calendar search ends here

	//Endorsement Calendar Search
	public function endorsement_calendar_search()
	{
		ob_start();
		include $this->basePath.'includes/public/views/endorsement/calendar-search.php';
		return ob_get_clean();
	}

	//calendar search ends here

	public function classes_list_calender_grid_view($attr)
	{
		ob_start();
		extract(shortcode_atts(array(
		     'calendar' => true
		    ), $attr));
		include $this->basePath.'includes/public/views/classes/grid-listing.php';
		return ob_get_clean();
	}

	public function classes_list_calender_grid_view_classname($attr)
	{
		ob_start();
		extract(shortcode_atts(array(
		     'calendarclassname' => true
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

//Added for sessions
	public function sessions(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/sessions.php';
		return ob_get_clean();
	}
	//ends here

	//Added for legislative reports
	public function legislative_reports(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/legislative-reports.php';
		return ob_get_clean();
	}
	//ends here

	//Added for legislative reports List
	public function report_list(){
		ob_start();
		include $this->basePath.'includes/public/views/shortcode/reports-list.php';
		return ob_get_clean();
	}
	//ends here


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

	public function class_calendar_class_name(){
		ob_start();
		include $this->basePath.'includes/public/views/classes/calendar-with-class-name.php';
		return ob_get_clean();
	}

	//added by guru
	public function endorsement_calendar(){
		ob_start();
		include $this->basePath.'includes/public/views/endorsement/calendar.php';
		return ob_get_clean();
	}

	public function event_grid_view()
	{
		ob_start();
		include $this->basePath.'includes/public/views/events/grid-listing.php';
		return ob_get_clean();
	}
	
	public function training_calendar()
	{
		ob_start();
		include $this->basePath.'includes/public/views/trainingcalendar/grid-listing.php';
		return ob_get_clean();
	}
	/*public function event_grid_view_person()
	{
		ob_start();
		include $this->basePath.'includes/public/views/events/grid-listingbyperson.php';
		return ob_get_clean();
	}*/
	public function events_list_calender_grid_view($attr)
	{
		ob_start();
		extract(shortcode_atts(array(
		     'calendar' => true
		    ), $attr));
		include $this->basePath.'includes/public/views/events/grid-listing.php';
		return ob_get_clean();
	}

	public function events_grid_details(){
		ob_start();
		include $this->basePath.'includes/public/views/events/grid-listing-details.php';
		return ob_get_clean();
	} 

	public function events_details($attr){
		ob_start();
		extract(shortcode_atts(array(
		     'endId' => $attr['id']
		    ), $attr));

		include $this->basePath.'includes/public/views/events/grid-listing-details.php';
		return ob_get_clean();
	}

	public function events_calendar(){
		ob_start();
		include $this->basePath.'includes/public/views/events/calendar.php';
		return ob_get_clean();
	}

	//end here
}
$this->engagifiiShortcode  = new Engagifii_Shortcodes($this->pluginBasePath);