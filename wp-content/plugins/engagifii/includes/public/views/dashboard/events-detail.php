<?php session_start();
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
    $obj      =  new Engagifii_API();
//$pid = $_SESSION['pid'];
if (isset($_COOKIE['pid'])) {
    $pid = $_COOKIE['pid'];
  }
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
/*$userEmail = $user->user_email;
    $obj      =  new Engagifii_API();
    $engagifiiProfile = $obj->engagifiiProfile('psba',$userEmail);
	$peopleDATA = json_decode($engagifiiProfile['api_response']);*/
if(!$pid) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
} 
 include 'sidebar_nav.php'; 
 echo do_shortcode("[events-details Id='event-id']"); 

