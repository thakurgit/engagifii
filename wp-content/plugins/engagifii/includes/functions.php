<?php
function engagifii_scripts() { ?> 
<script>
		function moOAuthLoginNew(app_name) {
			window.location.href = 'https://engagifiwebstg.wpengine.com/psba' + '/?option=oauthredirect&app_name=' + app_name;
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
</script>
    <?php
}
add_action('wp_footer', 'engagifii_scripts');

    $options  = get_option( 'ebt_api_settings' );
    $tenant_url          = $options['evt_tenant_code']['engagifii_url'];
	if($tenant_url=='psba'){
function custom_login_redirect( $redirect_to, $request, $user ) {
    // Get the current user's role
    $user_role = $user->roles[0];
 
    // Set the URL to redirect users to based on their role
    if ( $user_role == 'subscriber' ) {
        $redirect_to = site_url().'/my-profile/';
    } 
 
    return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (current_user_can('subscriber') && !is_admin()) {
show_admin_bar(false);
}

//print_r("Access Token:");
session_start();
//print_r($_SESSION['accesstoken']);
}

//add_action('mo_oauth_logged_in_user_token', 'GetToken' , 10, 2);   

function GetToken( $user, $token ){                
 
		?>
		ID Token
		<?php
		print_r($token['id_token']);

}




	}

