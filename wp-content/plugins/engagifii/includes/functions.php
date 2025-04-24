<?php
function engagifii_scripts() { ?> 
<style>
.login-btn {
	background: #ea8b2e;
	color: white !important;
	border-radius: 5px;
	padding: 5px 10px !important;
}	
</style>
<script>    
function clearAllCookies(newtab='') {
	  localStorage.clear();  
     var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
	if(newtab==''){
 	function myWindow(){
 		 //window.open('https://engagifii-preview4-identity.azurewebsites.net/Account/SignOut?ReturnUrl=%2Fconnect%2Fauthorize%2Fcallback%3Fclient_id%3Dng.EngagifiiUI%26redirect_uri%3Dhttps%253A%252F%252Fpsba.engagifii-preview4.com%252Fauth-callback%26response_type%3Did_token%2520token%26scope%3Dopenid%2520profile%2520email%2520UsersAPI%2520AccreditationAPI%2520BilltrackingApi%2520CommentApi%2520NotesApi%26state%3D2f9558adbd6147b0acdd08d1aa46c79c%26nonce%3D43ea3bf67eef475ca04ea79b328fd000','_self');
 			 window.open('https://engagifii-p5-identity.azurewebsites.net/Account/SignOut?ReturnUrl=https://engagifiwebstg.wpengine.com/psba/','_self');
 }
   setTimeout(function() {
 	  myWindow();
 	  }, 300);
 	}
	
}
function clearCookies() {
	  localStorage.clear();  
     var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
	}
    		function moOAuthLoginNew(app_name) {
			
			window.location.href = '<?php echo site_url(); ?>' + '/?option=oauthredirect&app_name=' + app_name;
		}
    const accessToken = '<?php echo isset($_SESSION['accesstoken']) ? $_SESSION['accesstoken'] : ''; ?>';
    
    if (accessToken) {
    document.cookie = 'peopleToken=' + encodeURIComponent(accessToken) + '; expires=' + new Date(new Date().getTime() + 720 * 60 * 60 * 1000).toUTCString() + '; path=/';
        // Save the access token to localStorage
        const jsonValue = {
  		"id_token": accessToken
		};
	const jsonValueString = JSON.stringify(jsonValue);
	localStorage.setItem('userLogin', jsonValueString);
    }
	//$(document).ready(function(){
		if(window.location.href=='<?php echo site_url();?>/' && localStorage.getItem("userLogin")!==null && localStorage.getItem("logged_in_user")===null){
window.location.href = "<?php echo site_url();?>/my-profile";	
		}
	//});
	document.addEventListener('DOMContentLoaded', function() {
        var loginEngagifii = document.getElementById('login_engagifii');

        if (loginEngagifii) {
            loginEngagifii.addEventListener('click', function() {
				if(localStorage.getItem("userLogin")!==null){
					window.location.href="<?php echo site_url();?>/my-profile";
				}else{
               		 moOAuthLoginNew('Engagifii');
				}
            });
        }
    });
   /* document.addEventListener('DOMContentLoaded', function() {
    var psbaLoginElements = document.getElementsByClassName('psba-login');

    if (psbaLoginElements.length > 0) {
        var psbaLogin = psbaLoginElements[0];

        psbaLogin.addEventListener('click', function() {
           // clearAllCookies();
        });
    }
});*/
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('a[id^="macosession-"]');
    links.forEach(link => {
        link.addEventListener('click', function (event) {
            const id = this.id;
            const year = id.split('-')[1];
            localStorage.setItem('sessionname', (year - 1) + ' Regular Session');
            });
    });
});
</script>
    <?php
}
add_action('wp_footer', 'engagifii_scripts');

    $options  = get_option( 'ebt_api_settings' );
	/*$dashboard_apis = $options['dashboard_apis']; 
    $tenant_url          = $dashboard_apis['tenant'];*/
	$tenant_url = $options['dashboard_tenant_code']; 
	if($tenant_url){
/*function custom_login_redirect( $redirect_to, $request, $user ) {
    // Get the current user's role
    $user_role = $user->roles[0];
 
    // Set the URL to redirect users to based on their role
    if ( $user_role == 'subscriber' ) {
        $redirect_to = site_url().'/my-profile/';
    } 
 
    return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );*/
add_action('wp_logout','engagifii_logout');

function engagifii_logout(){
  wp_safe_redirect( home_url() );
  exit;
}
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  if (current_user_can('subscriber') && !is_admin()) {
	show_admin_bar(false);
  }
}
session_start();
//add_action('mo_oauth_logged_in_user_token', 'GetToken' , 10, 2);   

function GetToken( $user, $token ){                
		?>
		ID Token
		<?php
		print_r($token['id_token']);
}
}


add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    $user = wp_get_current_user();
	$user_role = $user->roles[0];
	$options  = get_option( 'ebt_api_settings' );
    $login_btn = $options['dash_menus']['login_btn'];

	
	$login = "moOAuthLoginNew('Engagifii')";
    if (is_user_logged_in() && $args->theme_location == 'primary' &&   $user_role == 'subscriber' && $login_btn ) {
        $items .= '<li class="nav-item"><a title="Logout" class="nav-link login-btn" onclick="clearAllCookies()" href="'. wp_logout_url() .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'primary' && $login_btn ) {
        $items .= '<li class="nav-item"><a onClick="clearCookies(); '.$login.'" class="nav-link login-btn" title="Login with Engagifii" href="javascript:void">Log In</a></li>';
    }
    return $items;
}