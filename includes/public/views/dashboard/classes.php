<?php 
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
if (! is_user_logged_in()) {
	$login = 'moOAuthLoginNew("Engagifii")';
   echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center mb-0'>This page is restricted. Please";
	echo "<a href='javascript:void' class='btn btn-warning mx-2 px-2 py-0' onclick='".$login."' > Login </a>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo 'to view this page.</h5></div>';
	?>
    <script>
	document.addEventListener('DOMContentLoaded', function() {
		clearAllCookies('false');
	});
    </script>
    <?php
    return;
}
if (isset($_COOKIE['pid'])) {
    $pid = $_COOKIE['pid'];
  }
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
if(!$pid) { 
echo "<br><br><div class='alert alert-danger' role='alert'>
<h5 class='text-center'>Profile with username <strong>".$user->user_login."</strong> doesn't exist.</h5></div>";
return;
} 
 include 'sidebar_nav.php'; 
include $this->basePath.'includes/public/views/classes/grid-listingbyperson.php';

