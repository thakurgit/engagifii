<?php
/**
 * Reusable header component for Engagifii admin pages
 * This provides consistent branding and styling across all admin pages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display the consistent Engagifii admin header
 * 
 * @param string $title The page title to display
 * @param bool $show_welcome Whether to show the welcome panel (default: false)
 * @param string $description Optional description text for the page
 */
function engagifii_render_admin_header($title = 'Engagifii Settings', $show_welcome = false, $description = '') {
    $enabled_modules = get_option('engagifii_enabled_modules', array());
    
    // Ensure $enabled_modules is always an array
    if (!is_array($enabled_modules)) {
        $enabled_modules = array();
    }
    
    // Count total modules available (matching Module Settings)
    $total_modules = 7; // Legislation, Events, Classes, Courses, Awards, Group Directory, Organization Directory
    ?>
    <div class="wrap engagifii-settings-page">
        <div class="engagifii-settings-container">
            <div class="engagifii-settings-wrap">
                <!-- Purple gradient header with INLINE STYLES for guaranteed application -->
                <div class="engagifii-page-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%); color: white; padding: 30px 40px; margin: -20px -20px 30px -20px; border-radius: 0; box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3); position: relative;">
                    <h1 style="color: white; font-size: 32px; font-weight: 600; margin: 0; text-align: center; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);"><?php echo esc_html($title); ?></h1>
                </div>
                
                <!-- Welcome panel with INLINE STYLES -->
                <div class="engagifii-welcome-panel" style="background: #fff; border: 1px solid #e5e7eb; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); padding: 30px; border-radius: 12px; margin: 0 0 30px 0; border-left: 4px solid #6366f1;">
                    <div class="welcome-panel-content">
                        <div class="welcome-logo-container" style="text-align: center; margin-bottom: 20px;">
                            <img src="<?php echo plugins_url('engagifii/assets/images/engagifii-logo.png'); ?>" alt="Engagifii Logo" class="engagifii-welcome-logo" style="height: 80px; width: auto; max-width: 300px; display: block; margin: 0 auto;" />
                        </div>
                        <h2 style="font-size: 28px; font-weight: 600; color: #1f2937; text-align: center; margin: 0 0 15px 0;">Welcome to Engagifii!</h2>
                        <p class="about-description" style="font-size: 16px; color: #6b7280; text-align: center; margin: 0 0 30px 0; line-height: 1.6;"><?php echo $description ?: 'Copy and paste these shortcodes into your pages and posts to display Engagifii content.'; ?></p>
                        
                        <div class="welcome-stats" style="display: flex; justify-content: center; gap: 40px; margin: 30px 0 0 0; flex-wrap: wrap;">
                            <div class="stat-item" style="text-align: center; background: #f8fafc; padding: 20px 30px; border-radius: 12px; border: 1px solid #e5e7eb; min-width: 120px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                                <span class="stat-number" style="display: block; font-size: 36px; font-weight: 700; color: #6366f1; line-height: 1;"><?php echo $total_modules; ?></span>
                                <span class="stat-label" style="display: block; font-size: 14px; color: #6b7280; margin-top: 5px; font-weight: 500;">Modules Available</span>
                            </div>
                            <div class="stat-item" style="text-align: center; background: #f8fafc; padding: 20px 30px; border-radius: 12px; border: 1px solid #e5e7eb; min-width: 120px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                                <span class="stat-number" style="display: block; font-size: 36px; font-weight: 700; color: #6366f1; line-height: 1;"><?php echo count($enabled_modules); ?></span>
                                <span class="stat-label" style="display: block; font-size: 14px; color: #6b7280; margin-top: 5px; font-weight: 500;">Currently Active</span>
                            </div>
                        </div>
                    </div>
                </div>
    
    <!-- DEBUG: Check if this HTML is being output -->
    <!-- Logo path: <?php echo plugin_dir_url(__FILE__) . '../../assets/images/engagifii-logo.png'; ?> -->
    
    <?php
}

/**
 * Close the header wrapper - call this after rendering page content
 */
function engagifii_close_admin_header() {
    ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Enqueue admin header styles - PROPER WordPress way
 */
function engagifii_enqueue_admin_header_styles() {
    // Remove all WordPress admin notices on Engagifii pages at PHP level
    global $pagenow;
    
    // Check if we're on an Engagifii admin page
    if (isset($_GET['page']) && strpos($_GET['page'], 'engagifii') !== false) {
        // Remove WordPress core update notifications
        remove_action('admin_notices', 'update_nag', 3);
        remove_action('network_admin_notices', 'update_nag', 3);
        remove_action('admin_notices', 'maintenance_nag');
        remove_action('admin_notices', 'wp_admin_notices');
        
        // Remove plugin update notices
        remove_action('admin_notices', 'wp_plugin_update_rows');
        remove_action('load-plugins.php', 'wp_plugin_update_rows');
        
        // Remove theme update notices
        remove_action('admin_notices', 'wp_theme_update_rows');
        
        // Remove PHP update notices
        remove_action('admin_notices', 'wp_dashboard_php_nag');
        
        // Remove all other notices that might appear
        global $wp_filter;
        if (isset($wp_filter['admin_notices'])) {
            $wp_filter['admin_notices']->callbacks = array();
        }
        if (isset($wp_filter['all_admin_notices'])) {
            $wp_filter['all_admin_notices']->callbacks = array();
        }
        
        // Hide update nag with CSS and JavaScript as backup
        add_action('admin_head', function() {
            echo '<style>
                #update-nag, .update-nag, .notice, .error, .updated { display: none !important; }
                body[class*="engagifii"] .notice,
                body[class*="engagifii"] .error,
                body[class*="engagifii"] .updated,
                body[class*="engagifii"] .update-nag { display: none !important; }
            </style>';
            echo '<script>
                jQuery(document).ready(function($) {
                    $(".notice, .error, .updated, .update-nag, #update-nag").remove();
                    setInterval(function() {
                        $(".notice, .error, .updated, .update-nag, #update-nag").remove();
                    }, 500);
                });
            </script>';
        });
    }
    
    // Only load on our admin pages
    if (strpos($hook, 'engagifii') === false) {
        return;
    }
    
    // Register and enqueue CSS with proper priority
    wp_register_style(
        'engagifii-admin-header', 
        false, // No file, inline styles
        array(), 
        '1.0.0'
    );
    wp_enqueue_style('engagifii-admin-header');
    
    // Add inline CSS with highest WordPress priority
    $css = "
    /* ENGAGIFII ADMIN HEADER - FORCE OVERRIDE */
    .engagifii-page-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%) !important;
        color: white !important;
        padding: 30px 40px !important;
        margin: -20px -20px 30px -20px !important;
        border-radius: 0 !important;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3) !important;
        position: relative !important;
    }
    
    .engagifii-page-header h1 {
        color: white !important;
        font-size: 32px !important;
        font-weight: 600 !important;
        margin: 0 !important;
        text-align: center !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    .engagifii-welcome-panel {
        background: #fff !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        padding: 30px !important;
        border-radius: 12px !important;
        margin: 0 0 30px 0 !important;
        border-left: 4px solid #6366f1 !important;
    }
    
    .welcome-logo-container {
        text-align: center !important;
        margin-bottom: 20px !important;
    }
    
    .engagifii-welcome-logo {
        height: 80px !important;
        width: auto !important;
        max-width: 300px !important;
        display: block !important;
        margin: 0 auto !important;
    }
    
    .engagifii-welcome-panel h2 {
        font-size: 28px !important;
        font-weight: 600 !important;
        color: #1f2937 !important;
        text-align: center !important;
        margin: 0 0 15px 0 !important;
    }
    
    .about-description {
        font-size: 16px !important;
        color: #6b7280 !important;
        text-align: center !important;
        margin: 0 0 30px 0 !important;
        line-height: 1.6 !important;
    }
    
    .welcome-stats {
        display: flex !important;
        justify-content: center !important;
        gap: 40px !important;
        margin: 30px 0 0 0 !important;
        flex-wrap: wrap !important;
    }
    
    .stat-item {
        text-align: center !important;
        background: #f8fafc !important;
        padding: 20px 30px !important;
        border-radius: 12px !important;
        border: 1px solid #e5e7eb !important;
        min-width: 120px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
    }
    
    .stat-number {
        display: block !important;
        font-size: 36px !important;
        font-weight: 700 !important;
        color: #6366f1 !important;
        line-height: 1 !important;
    }
    
    .stat-label {
        display: block !important;
        font-size: 14px !important;
        color: #6b7280 !important;
        margin-top: 5px !important;
        font-weight: 500 !important;
    }
    
    .engagifii-settings-page {
        background: #f9fafb !important;
    }
    
    .engagifii-settings-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
    }
    
    .engagifii-settings-wrap {
        background: transparent !important;
        padding: 0 !important;
    }
    
    /* Hide WordPress default header elements */
    .center, .w-25 {
        display: none !important;
    }
    
    /* HIDE ALL WORDPRESS ADMIN NOTIFICATIONS ON ENGAGIFII PAGES */
    
    /* Target all notices on Engagifii admin pages using body class */
    body.toplevel_page_engagifii-admin-settings .notice,
    body.toplevel_page_engagifii-admin-settings .error,
    body.toplevel_page_engagifii-admin-settings .updated,
    body.toplevel_page_engagifii-admin-settings .update-nag,
    body.engagifii_page_engagifii-admin-shortcodes .notice,
    body.engagifii_page_engagifii-admin-shortcodes .error,
    body.engagifii_page_engagifii-admin-shortcodes .updated,
    body.engagifii_page_engagifii-admin-shortcodes .update-nag,
    body.engagifii_page_engagifii-dashboard-settings .notice,
    body.engagifii_page_engagifii-dashboard-settings .error,
    body.engagifii_page_engagifii-dashboard-settings .updated,
    body.engagifii_page_engagifii-dashboard-settings .update-nag,
    body[class*="engagifii"] .notice,
    body[class*="engagifii"] .error,
    body[class*="engagifii"] .updated,
    body[class*="engagifii"] .update-nag,
    
    /* Target specific WordPress update notices globally */
    body.toplevel_page_engagifii-admin-settings #update-nag,
    body.toplevel_page_engagifii-admin-settings .core-update-nag,
    body.engagifii_page_engagifii-admin-shortcodes #update-nag,
    body.engagifii_page_engagifii-admin-shortcodes .core-update-nag,
    body[class*="engagifii"] #update-nag,
    body[class*="engagifii"] .core-update-nag,
    
    /* Target the yellow notice boxes at wpbody-content level */
    body[class*="engagifii"] #wpbody-content > .notice,
    body[class*="engagifii"] #wpbody-content > .error,
    body[class*="engagifii"] #wpbody-content > .updated,
    body[class*="engagifii"] #wpbody-content > .update-nag,
    body[class*="engagifii"] #wpbody-content > .notice-warning,
    body[class*="engagifii"] #wpbody-content > .notice-info,
    body[class*="engagifii"] #wpbody-content > .notice-error,
    body[class*="engagifii"] #wpbody-content > .notice-success,
    
    /* Original targeting for inside settings page */
    body.wp-admin .engagifii-settings-page .notice,
    body.wp-admin .engagifii-settings-page .error,
    body.wp-admin .engagifii-settings-page .updated,
    body.wp-admin .engagifii-settings-page .update-nag,
    body.wp-admin .engagifii-settings-page div.notice,
    body.wp-admin .engagifii-settings-page div.error,
    body.wp-admin .engagifii-settings-page div.updated,
    body.wp-admin .engagifii-settings-page div.update-nag,
    .engagifii-settings-page .notice,
    .engagifii-settings-page .error,
    .engagifii-settings-page .updated,
    .engagifii-settings-page .update-nag,
    .engagifii-settings-page div.notice,
    .engagifii-settings-page div.error,
    .engagifii-settings-page div.updated,
    .engagifii-settings-page div.update-nag {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        overflow: hidden !important;
        position: absolute !important;
        left: -9999px !important;
        top: -9999px !important;
    }
    
    /* Clean header area - hide notices in wpbody-content on Engagifii pages */
    .engagifii-settings-page #wpbody-content > .notice,
    .engagifii-settings-page #wpbody-content > .error,
    .engagifii-settings-page #wpbody-content > .updated,
    .engagifii-settings-page #wpbody-content > .update-nag,
    body.wp-admin .engagifii-settings-page #wpbody-content > .notice,
    body.wp-admin .engagifii-settings-page #wpbody-content > .error,
    body.wp-admin .engagifii-settings-page #wpbody-content > .updated,
    body.wp-admin .engagifii-settings-page #wpbody-content > .update-nag {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Hide plugin activation recommendations and warnings */
    .engagifii-settings-page .plugin-install-php,
    .engagifii-settings-page .install-help,
    .engagifii-settings-page .update-php,
    .engagifii-settings-page .php-update-nag,
    .engagifii-settings-page .dashboard_php_nag,
    .engagifii-settings-page .wp-health-notice {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Hide WordPress core update notices */
    .engagifii-settings-page #update-nag,
    .engagifii-settings-page .update-nag,
    .engagifii-settings-page .core-update-nag {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Hide plugin/theme update notifications */
    .engagifii-settings-page .plugin-update-tr,
    .engagifii-settings-page .theme-update-tr,
    .engagifii-settings-page .update-message {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Hide maintenance mode and other system notices */
    .engagifii-settings-page .maintenance-nag,
    .engagifii-settings-page .jetpack-message,
    .engagifii-settings-page .akismet-warning,
    .engagifii-settings-page .hello-dolly {
        display: none !important;
        visibility: hidden !important;
    }
    ";
    
    wp_add_inline_style('engagifii-admin-header', $css);
    
    // Add JavaScript to hide any dynamically loaded notices
    $js = "
    jQuery(document).ready(function($) {
        // Function to aggressively hide ALL admin notices on Engagifii pages
        function hideAllAdminNotices() {
            // Check if we're on an Engagifii page
            if (window.location.href.indexOf('engagifii') !== -1 || $('.engagifii-settings-page').length > 0) {
                
                // Hide WordPress update notices (most aggressive targeting)
                $('#wpbody-content .notice').hide().remove();
                $('#wpbody-content .error').hide().remove();
                $('#wpbody-content .updated').hide().remove();
                $('#wpbody-content .update-nag').hide().remove();
                $('.notice').hide();
                $('.error').hide(); 
                $('.updated').hide();
                $('.update-nag').hide();
                
                // Hide specific WordPress core update notices
                $('#update-nag').hide().remove();
                $('.update-nag').hide().remove();
                $('.core-update-nag').hide().remove();
                
                // Hide plugin update and activation notices
                $('.plugin-update-tr').hide().remove();
                $('.theme-update-tr').hide().remove();
                $('.update-message').hide().remove();
                $('.plugin-install-php').hide().remove();
                $('.install-help').hide().remove();
                
                // Hide PHP and system warnings
                $('.update-php').hide().remove();
                $('.php-update-nag').hide().remove();
                $('.dashboard_php_nag').hide().remove();
                $('.wp-health-notice').hide().remove();
                
                // Hide maintenance and third-party notices
                $('.maintenance-nag').hide().remove();
                $('.jetpack-message').hide().remove();
                $('.akismet-warning').hide().remove();
                $('.hello-dolly').hide().remove();
                
                // Hide admin bar update notices
                $('#wp-admin-bar-updates').hide();
                
                // Target the specific yellow notice box structure from screenshot
                $('#wpbody-content > .notice-warning').hide().remove();
                $('#wpbody-content > .notice-info').hide().remove();
                $('#wpbody-content > .notice-error').hide().remove();
                $('#wpbody-content > .notice-success').hide().remove();
                
                // Hide all div elements with notice classes at body level
                $('body > .notice, body > .error, body > .updated, body > .update-nag').hide().remove();
                $('.wrap > .notice, .wrap > .error, .wrap > .updated, .wrap > .update-nag').hide().remove();
                
                // Completely remove from DOM to prevent reappearing
                $('div[class*=\"notice\"], div[class*=\"error\"], div[class*=\"updated\"], div[class*=\"update\"]').each(function() {
                    if ($(this).text().toLowerCase().indexOf('wordpress') !== -1 || 
                        $(this).text().toLowerCase().indexOf('plugin') !== -1 ||
                        $(this).text().toLowerCase().indexOf('update') !== -1 ||
                        $(this).text().toLowerCase().indexOf('available') !== -1) {
                        $(this).hide().remove();
                    }
                });
            }
        }
        
        // Run immediately
        hideAllAdminNotices();
        
        // Run every 100ms for the first 5 seconds (aggressive cleanup)
        var counter = 0;
        var aggressiveInterval = setInterval(function() {
            hideAllAdminNotices();
            counter++;
            if (counter >= 50) { // 50 * 100ms = 5 seconds
                clearInterval(aggressiveInterval);
                // Then run every 2 seconds for ongoing cleanup
                setInterval(hideAllAdminNotices, 2000);
            }
        }, 100);
        
        // Run when DOM changes
        var observer = new MutationObserver(function(mutations) {
            hideAllAdminNotices();
        });
        
        // Observe the entire document for changes
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true
        });
    });
    ";
    
    wp_add_inline_script('jquery', $js);
}

// Hook with proper WordPress admin enqueue action
add_action('admin_enqueue_scripts', 'engagifii_enqueue_admin_header_styles');