<?php
//use this file for defining common variable being uses across plugin
 $options = get_option('ebt_api_settings');
	$front_pages = $options['front_pages'];
//class detail page link
	$classes_detail_page = $front_pages['classes_detail_page'];
	if($classes_detail_page){
		$classes_detail_page_link=get_permalink( $classes_detail_page );	
	}else{
		$classes_detail_page_link= site_url() .'/class-details/';	
	}
	define('CLASS_DETAIL_LINK', $classes_detail_page_link);
//event detail link
	$events_detail_page = $front_pages['events_detail_page'];
	if($events_detail_page){
		$events_detail_page_link=get_permalink( $events_detail_page );	
	}else{
		$events_detail_page_link= site_url() .'/event-detail/';	 
	}
		define('EVENT_DETAIL_LINK', $events_detail_page_link);
//class and Events columns
	$events_class_columns = isset($options['training_calendar_visible_column_list']) ? $options['training_calendar_visible_column_list'] : [];  
	define('EVENTS_CLASS_COLS', $events_class_columns);
//event columns
	$events_visible_column_list = isset($options['events_visible_column_list']) ? $options['events_visible_column_list'] : [];  	
	define('EVENTS_COLS', $events_visible_column_list);
//class columns
	 $class_visible_column_list = isset($options['class_visible_column_list']) ? $options['class_visible_column_list'] : [];  
	define('CLASS_COLS', $class_visible_column_list);
//Group members columns
	 $group_members_visible_column_list = isset($options['group_members_settings']['visible_column_list']) ? $options['group_members_settings']['visible_column_list'] : [];  
	define('GROUP_MEMBERS_COLS', $group_members_visible_column_list);
//Organizations columns
	 $organization_visible_column_list = isset($options['organization_settings']['visible_column_list']) ? $options['organization_settings']['visible_column_list'] : [];
 	define('ORGANIZATION_COLS', $organization_visible_column_list);