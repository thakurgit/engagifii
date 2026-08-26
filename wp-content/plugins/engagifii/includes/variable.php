<?php
//use this file for defining common variable being used across plugin
 $options = get_option('ebt_api_settings');
	$front_pages = (is_array($options) && isset($options['front_pages'])) ? $options['front_pages'] : array();
//Bills page link
	$bills_page = (is_array($front_pages) && isset($front_pages['bills_page'])) ? $front_pages['bills_page'] : '';
	if($bills_page){
		$bills_page_link=get_permalink( $bills_page );	
	}else{
		$bills_page_link= site_url() .'/bill-tracking/';	
	}
	define('BILLS_PAGE_LINK', $bills_page_link);
//Bills detail page link
	$bills_detail_page = (isset($front_pages['bills_detail_page']) && is_array($front_pages)) ? $front_pages['bills_detail_page'] : '';
	if($bills_detail_page){
		$bills_detail_page_link=get_permalink( $bills_detail_page );	
	}else{
		$bills_detail_page_link= site_url() .'/engagifii-detail/';	
	}
	define('BILLS_DETAIL_PAGE_LINK', $bills_detail_page_link);
// Public official page link
$public_official_page = $front_pages['public_official_page'] ?? '';
if ( ! defined( 'PUBLIC_OFFICIAL_PAGE_LINK' ) ) {
    define('PUBLIC_OFFICIAL_PAGE_LINK', $public_official_page ? get_permalink( $public_official_page ) : site_url( '/public-official/' ));
}

// Public official detail page link
$public_official_detail_page = $front_pages['public_official_detail_page'] ?? '';
if ( ! defined( 'PUBLIC_OFFICIAL_DETAIL_PAGE_LINK' ) ) {
    define('PUBLIC_OFFICIAL_DETAIL_PAGE_LINK', $public_official_detail_page ? get_permalink( $public_official_detail_page ) : site_url('/public-official-detail/' ) );
}
// classes page link
$classes_page = $front_pages['classes_page'] ?? '';
if ( ! defined( 'CLASSES_PAGE_LINK' ) ) {
    define('CLASSES_PAGE_LINK', $classes_page ? get_permalink( $classes_page ) : site_url('/classes/' ) );
}
//class detail page link
	$classes_detail_page = $front_pages['classes_detail_page'] ?? '';
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
	define('CLASS_DETAIL_LINK', $classes_detail_page_link);
// courses page link
$courses_page = $front_pages['courses_page'] ?? '';
if ( ! defined( 'COURSES_PAGE_LINK' ) ) {
    define('COURSES_PAGE_LINK', $courses_page ? get_permalink( $courses_page ) : site_url('/courses/' ) );
}
// courses detail page link
$courses_detail_page = $front_pages['courses_detail_page'] ?? '';
if ( ! defined( 'COURSE_DETAIL_LINK' ) ) {
    define('COURSE_DETAIL_LINK', $courses_detail_page ? get_permalink( $courses_detail_page ) : site_url('/course-details/' ) );
}
// events page link
$events_page = $front_pages['events_page'] ?? '';
if ( ! defined( 'EVENTS_PAGE_LINK' ) ) {
    define('EVENTS_PAGE_LINK', $events_page ? get_permalink( $events_page ) : site_url('/events/' ) );
}
//event detail link
	$events_detail_page = $front_pages['events_detail_page'] ?? '';
	if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	}else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	}
		define('EVENT_DETAIL_LINK', $events_detail_page_link);
//Bills columns
	$lbt_visib_datacol_list = isset($options['lbt_visib_datacol_list']) ? $options['lbt_visib_datacol_list'] : [];  
	define('LEGISLATION_COLS', $lbt_visib_datacol_list);
//class and Events columns
	$events_class_columns = isset($options['training_calendar_visible_column_list']) ? $options['training_calendar_visible_column_list'] : [];  
	define('EVENTS_CLASS_COLS', $events_class_columns);
//event columns
	$events_visible_column_list = isset($options['events_visible_column_list']) ? $options['events_visible_column_list'] : [];  	
	define('EVENTS_COLS', $events_visible_column_list);
//event types columns
	 $events_type_visible_column_list = isset($options['events_type_visible_column_list']) ? $options['events_type_visible_column_list'] : [];  
	define('EVENTS_TYPES_COLS', $events_type_visible_column_list);
//class columns
	 $class_visible_column_list = isset($options['class_visible_column_list']) ? $options['class_visible_column_list'] : [];  
	define('CLASS_COLS', $class_visible_column_list);
//class types columns
	 $class_type_visible_column_list = isset($options['class_type_visible_column_list']) ? $options['class_type_visible_column_list'] : [];  
	define('CLASS_TYPES_COLS', $class_type_visible_column_list);
//Group members columns
	 $group_members_visible_column_list = isset($options['group_members_settings']['list']['visible_column_list']) ? $options['group_members_settings']['list']['visible_column_list'] : [];  
	define('GROUP_MEMBERS_COLS', $group_members_visible_column_list);
	 $group_members_visible_column_grid = isset($options['group_members_settings']['grid']['visible_column_list']) ? $options['group_members_settings']['grid']['visible_column_list'] : [];  
	define('GROUP_MEMBERS_COLS_GRID', $group_members_visible_column_grid);
// Organization list page link
$organization_page = $front_pages['organization_page'] ?? '';
if ( ! defined( 'ORGANIZATION_PAGE_LINK' ) ) {
    define('ORGANIZATION_PAGE_LINK', $organization_page ? get_permalink( $organization_page ) : site_url('/organizations/'));
}

// Organization detail page link
$organization_detail_page = $front_pages['organization_detail_page'] ?? '';
if ( ! defined( 'ORGANIZATION_DETAIL_LINK' ) ) {
    define('ORGANIZATION_DETAIL_LINK', $organization_detail_page ? get_permalink( $organization_detail_page ) : site_url('/organization-details/'));
}

//Organizations columns
	 $organization_visible_column_list = isset($options['organization_settings']['list']['visible_column_list']) ? $options['organization_settings']['list']['visible_column_list'] : [];
 	define('ORGANIZATION_COLS', $organization_visible_column_list); 
	 $organization_visible_column_grid = isset($options['organization_settings']['grid']['visible_column_list']) ? $options['organization_settings']['grid']['visible_column_list'] : [];
 	define('ORGANIZATION_COLS_GRID', $organization_visible_column_grid);
	$organization_detail_visible_fields = isset($options['organization_settings']['detail']['visible_field_list']) ? $options['organization_settings']['detail']['visible_field_list'] : [];
	if ( ! defined('ORGANIZATION_DETAIL_VISIBLE_FIELDS') ) {
		define('ORGANIZATION_DETAIL_VISIBLE_FIELDS', $organization_detail_visible_fields);
	}
//Organization guest hidden fields (fields to blur/hide for non-logged-in users)
	if ( ! defined('ORGANIZATION_GUEST_HIDDEN_FIELDS') ) {
		$organization_guest_hidden_fields = array_key_exists('guest_hidden_fields', $options['organization_settings'] ?? [])
			? ($options['organization_settings']['guest_hidden_fields'] ?? [])
			: ['phoneNumbers', 'primaryEmail']; // default: hide phone + email for guests
		define('ORGANIZATION_GUEST_HIDDEN_FIELDS', $organization_guest_hidden_fields);
	}
//Group Members guest hidden fields (fields to blur/hide for non-logged-in users)
	if ( ! defined('GROUP_MEMBERS_GUEST_HIDDEN_FIELDS') ) {
		$gm_guest_hidden_fields = array_key_exists('guest_hidden_fields', $options['group_members_settings'] ?? [])
			? ($options['group_members_settings']['guest_hidden_fields'] ?? [])
			: ['email', 'phone']; // default: hide email + phone for guests
		define('GROUP_MEMBERS_GUEST_HIDDEN_FIELDS', $gm_guest_hidden_fields);
	}