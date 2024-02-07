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
<div class="sidebar-nav-fixed position-fixed h-100 py-4 open">
<div id="menu-toggle" class="position-absolute open navbtn  d-flex align-items-center justify-content-center">
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
 <?php $logo = get_theme_mod('custom_logo');
if($logo) {
$logo = wp_get_attachment_url($logo); ?>
<div class="text-center px-3">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand text-hide "><img src="<?php echo $logo; ?>" alt="" class="img-fluid"></a>
        </div>
        <?php } 
		$site_url = site_url();
global $post;
    $post_slug = $post->post_name;
		$active = 'active';
		
    echo '<ul class="list-unstyled sidebar-nav px-3 mt-4">
    	<li><a href="'.$site_url.'/engagifii-profile" class="py-3  ' . ($post_slug == 'engagifii-profile' ? $active : '') . '"><i class="fas fa-home mr-3"></i>Home</a></li>
        <li><a href="'.$site_url.'/engagifii-profile/events" class="py-3  ' . ($post_slug == 'events' ? $active : '') . '"><i class="far fa-calendar-alt mr-3"></i>Event Registration</a></li>
        <li><a href="'.$site_url.'/engagifii-profile/my-transcript" class="py-3 ' . ($post_slug == 'my-transcript' ? $active : '') . '"><i class="fas fa-file mr-3"></i>My Transcript</a></li>
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
</script>