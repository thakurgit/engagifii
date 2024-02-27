<?php session_start();
$name = $_SESSION['name'];
$dp = $_SESSION['dp'];
 $site_url = site_url();?>
<style>
 body{
padding-left: 300px;
transition:0.3s all ease-in-out;	
}
body.menu-closed {
padding-left: 0;	
}
.sidebar-nav-fixed{
background: #21086b;	
width: 300px;
left: -300px;	
top: 0;
z-index: 1111;
transition:0.3s all ease-in-out;	
}
.sidebar-nav-fixed.open {
left: 0;
}

.sidebar-nav li a {
color:white;
border-bottom: 1px solid rgba(255,255,255,0.2);	
padding: 15px 0px;
display: block;
transition:0.3s all ease-in-out;
}
.sidebar-nav li a:hover, .sidebar-nav li a.active {
border-bottom-color:white ;	
}
#menu-toggle {
	width: 45px;
	cursor: pointer;
	height: 45px;
	right: -45px;
	top: 4px;
	background: white;
}
#menu-toggle * {
	-webkit-transition: .25s ease-in-out;
	transition: .25s ease-in-out;
}
#menu-toggle #hamburger {
	width: 22px;
	height: 29px;
}
#menu-toggle span {
	display: block;
	background: #333;
}
#menu-toggle.open span {
	background: #333;
}
#menu-toggle #hamburger span {
	height: 2px;
	margin: 6px 0;
	width: 22px;
}
#menu-toggle.open #hamburger span {
	width: 0%;
}
#menu-toggle #hamburger span:nth-child(1) {
	-webkit-transition-delay: .5s;
	transition-delay: .5s;
}
#menu-toggle.open #hamburger span:nth-child(1) {
	-webkit-transition-delay: 0s;
	transition-delay: 0s;
}
#menu-toggle #hamburger span:nth-child(2) {
	-webkit-transition-delay: .625s;
	transition-delay: .625s;
}
#menu-toggle.open #hamburger span:nth-child(2) {
	-webkit-transition-delay: .125s;
	transition-delay: .125s;
}
#menu-toggle #hamburger span:nth-child(3) {
	-webkit-transition-delay: .75s;
	transition-delay: .75s;
}
#menu-toggle.open #hamburger span:nth-child(3) {
	-webkit-transition-delay: .25s;
	transition-delay: .25s;
}
#menu-toggle #cross {
	position: absolute;
	height: 25px;
	width: 25px;
	-webkit-transform: rotate(45deg);
	transform: rotate(45deg);
}
#menu-toggle #cross span:nth-child(1) {
	height: 0%;
	width: 2px;
	position: absolute;
	top: 0;
	left: 50%;
	-webkit-transition-delay: 0s;
	transition-delay: 0s;
	-webkit-transform: translateX(-50%);
	transform: translateX(-50%);
}
#menu-toggle.open #cross span:nth-child(1) {
	height: 100%;
	-webkit-transition-delay: .625s;
	transition-delay: .625s;
}
#menu-toggle #cross span:nth-child(2) {
	width: 0%;
	height: 2px;
	position: absolute;
	left: 0;
	top: 50%;
	-webkit-transition-delay: .25s;
	transition-delay: .25s;
	-webkit-transform: translateY(-50%);
	transform: translateY(-50%);
}
#menu-toggle.open #cross span:nth-child(2) {
	width: 100%;
	-webkit-transition-delay: .375s;
	transition-delay: .375s;
}
</style> 
  <div class="d-flex justify-content-end px-3 mb-3">
    <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle px-2 border-0 align-items-center d-inline-flex" data-toggle="dropdown" aria-expanded="false" style="background:#21086B;">
    <?php echo $name; ?>
    <span class="overflow-hidden rounded-circle ml-2 " style="width:30px;height:30px">
    <?php if (str_contains($dp, 'http')) {?>
    <img src="<?php echo $dp; ?>" alt="..." class="img-fluid rounded-circle">
    <?php } else { ?>
    <i class="fa fa-user-circle" style="font-size:30px"></i>
    <?php } ?>
    </span>
  </button>
  <div class="dropdown-menu dropdown-menu-right">
    <a class="dropdown-item" href="<?php echo $site_url ?>/engagifii-profile/">My Profile</a>
    <!-- <a class="dropdown-item" href="<?php echo $site_url ?>/engagifii-profile/edit">Edit Profile</a> -->
     <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="<?php echo esc_url(wp_logout_url('')); ?>" onclick="clearAllCookies()" target="_blank">Logout</a>
  </div>
</div>
    
</div>

<div class="sidebar-nav-fixed position-fixed h-100 py-4 open">
<div id="menu-toggle" class="position-absolute open navbtn   align-items-center justify-content-center d-none">
              <div id="hamburger" class="position-absolute text-right">
                <span></span>
                <span></span>
                <span></span>
              </div>
              <div id="cross">
                <span></span>
                <span></span>
              </div>
            </div>
 <?php //$logo = get_theme_mod('custom_logo');
//if($logo) {
//$logo = wp_get_attachment_url($logo); ?>
<div class="text-center px-3">
        <!--<a href="<?php //echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand text-hide "><img src="<?php //echo $logo; ?>" alt="" class="img-fluid"></a>-->
        <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/mypsba-logo.png" alt="" class="img-fluid" style="max-height:65px">
        </div>
        <?php //} 
		
global $post;
    $post_slug = $post->post_name;
		$active = 'active';
		
    echo '<ul class="list-unstyled sidebar-nav px-3 mt-4">
    	<li><a href=""><i class="fas fa-home mr-3"></i>Home</a></li>
    	<li><a href="'.$site_url.'/engagifii-profile" class="py-3  ' . ($post_slug == 'engagifii-profile' ? $active : '') . '"><i class="fas fa-user mr-3"></i>My Profile</a></li>
        <li><a href="'.$site_url.'/engagifii-profile/my-transcript/downloads" class="py-3  "><i class="fas fa-download mr-3"></i></i>My Downloads</a></li>
        <li><a href="'.$site_url.'/engagifii-profile/events" class="py-3  ' . ($post_slug == 'events' ? $active : '') . '"><i class="far fa-calendar-alt mr-3"></i>Event Registration</a></li>
        <li><a href="'.$site_url.'/engagifii-profile/my-transcript" class="py-3 ' . ($post_slug == 'my-transcript' ? $active : '') . '"><i class="fas fa-file mr-3"></i>My Transcripts</a></li>
        <li><a href="" class="py-3 "><i class="fas fa-child mr-3"></i>Members</a></li>
        <li><a href="" class="py-3 "><i class="fas fa-book mr-3"></i>Resources</a></li>
        <li><a href="" class="py-3  "><i class="far fa-calendar-alt mr-3"></i>Signature Events</a></li>
    </ul>';
	?>
</div>
<script>
$(document).ready(function() {
	  $('#menu-toggle').click(function(){
  $(this).toggleClass('open');
  
  $('.sidebar-nav-fixed').toggleClass('open');
  $('body').toggleClass('menu-closed');
  
});
});

function clearAllCookies() {
	  localStorage.clear();  
     var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
	function myWindow(){
		 window.open('https://engagifii-preview4-identity.azurewebsites.net/Account/SignOut?ReturnUrl=%2Fconnect%2Fauthorize%2Fcallback%3Fclient_id%3Dng.EngagifiiUI%26redirect_uri%3Dhttps%253A%252F%252Fpsba.engagifii-preview4.com%252Fauth-callback%26response_type%3Did_token%2520token%26scope%3Dopenid%2520profile%2520email%2520UsersAPI%2520AccreditationAPI%2520BilltrackingApi%2520CommentApi%2520NotesApi%26state%3D2f9558adbd6147b0acdd08d1aa46c79c%26nonce%3D43ea3bf67eef475ca04ea79b328fd000','_self');
	}
  setTimeout(function() {
	  myWindow();
	  }, 300);
	
}

</script>