<?php 
if (! is_user_logged_in()) {
    echo "<br><br><div class='alert alert-warning' role='alert'><h5 class='text-center'>";
    printf(esc_attr('This page is restricted. Please %s to view this page.', 'wpfep'), wp_loginout('', false));
    echo '</h5></div>';
    return;
}
include 'sidebar_nav.php';
?>
<div class="text-center px-3">
        <!--<a href="<?php //echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand text-hide "><img src="<?php //echo $logo; ?>" alt="" class="img-fluid"></a>-->
        <img src="<?php echo ENGAGIFII_ASSETS_URL; ?>/images/mypsba-logo.png" alt="" class="img-fluid" style="max-height:65px">
        </div>
<h2>Welcome to MyPSBA </h2>