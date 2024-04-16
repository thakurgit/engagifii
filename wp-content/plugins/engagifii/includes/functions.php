<?php
function engagifii_scripts() { ?> 
<script>
console.log('test');
		function moOAuthLoginNew(app_name) {
			window.location.href = 'https://engagifiwebstg.wpengine.com/psba' + '/?option=oauthredirect&app_name=' + app_name;
		}
    const accessToken = '<?php echo isset($_SESSION['accesstoken']) ? $_SESSION['accesstoken'] : ''; ?>';
    
    if (accessToken) {
    document.cookie = 'peopleToken=' + encodeURIComponent(accessToken) + '; expires=' + new Date(new Date().getTime() + 24 * 60 * 60 * 1000).toUTCString() + '; path=/';
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
