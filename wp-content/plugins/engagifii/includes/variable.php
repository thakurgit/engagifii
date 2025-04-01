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
	$events_class_columns = $options['training_calendar_visible_column_list'];
	define('EVENTS_CLASS_COLS', $events_class_columns);
