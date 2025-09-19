<?php
/**
 * Engagifii Admin Header Template
 * Provides a consistent header for all Engagifii admin pages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the Engagifii admin header
 */
function engagifii_render_admin_header($title = 'Engagifii Settings', $description = 'Configure your engagement settings') {
    // Get module statistics for display
    global $wpdb;
    
    // Get total available modules (from the MODULES constant in settings class)
    if (class_exists('Engagifii_Settings')) {
        $all_modules_reflection = new ReflectionClass('Engagifii_Settings');
        $modules_constant = $all_modules_reflection->getConstant('MODULES');
        $total_modules = count($modules_constant);
    } else {
        // Fallback: Use the correct module list that matches the settings page
        $all_modules = array(
            'legislation', 'events', 'classes', 'awards', 'courses', 
            'group_directory', 'organization_directory'
        );
        $total_modules = count($all_modules);
    }
    
    // Get activated/enabled modules
    $enabled_modules = get_option('engagifii_enabled_modules', array());
    if (!is_array($enabled_modules)) {
        $enabled_modules = array();
    }
    $activated_modules = count($enabled_modules);
    
    ?>
    <style>
    /* ENGAGIFII ADMIN HEADER - INLINE FORCE OVERRIDE */
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
        color: #1f2937 !important;
        font-size: 24px !important;
        font-weight: 600 !important;
        margin: 0 0 10px 0 !important;
        text-align: center !important;
    }
    
    .engagifii-welcome-panel p {
        color: #6b7280 !important;
        font-size: 16px !important;
        margin: 0 0 30px 0 !important;
        text-align: center !important;
        line-height: 1.6 !important;
    }
    
    .engagifii-stats-grid {
        display: flex !important;
        gap: 20px !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
    }
    
    .stat-card {
        flex: 1 !important;
        min-width: 200px !important;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
        padding: 20px !important;
        border-radius: 8px !important;
        text-align: center !important;
        border: 1px solid #e5e7eb !important;
        transition: all 0.3s ease !important;
    }
    
    .stat-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15) !important;
        border-color: #6366f1 !important;
    }
    
    .stat-number {
        font-size: 32px !important;
        font-weight: 700 !important;
        color: #6366f1 !important;
        margin-bottom: 5px !important;
    }
    
    .stat-label {
        font-size: 14px !important;
        color: #6b7280 !important;
        font-weight: 500 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    /* BOXED LAYOUT MATCHING MODULE SETTINGS PAGE */
    .wrap {
        margin: 20px 0 0 0 !important;
    }
    
    .engagifii-settings-container {
        background: #f1f1f1 !important;
        margin: 0 !important;
        padding: 20px !important;
        min-height: calc(100vh - 160px) !important;
    }
    
    .engagifii-settings-wrap {
        max-width: 1200px !important;
        margin: 0 auto !important;
        background: #ffffff !important;
        border: 1px solid #c3c4c7 !important;
        border-radius: 8px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
    }
    
    .engagifii-settings-page {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Page header within the box */
    .engagifii-page-header {
        margin: 0 !important;
        border-radius: 8px 8px 0 0 !important;
    }
    
    /* Welcome panel within the boxed layout */
    .engagifii-welcome-panel {
        margin: 0 30px 30px 30px !important;
        border-radius: 0 !important;
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 30px 0 !important;
    }
    
    /* Content area styling */
    .engagifii-content-area {
        padding: 0 30px 30px 30px !important;
        background: #ffffff !important;
    }
    
    /* Tab navigation styling to match module settings */
    .nav-tab-wrapper {
        margin: 0 30px !important;
        border-bottom: 1px solid #c3c4c7 !important;
        background: #ffffff !important;
    }
    
    .nav-tab {
        border: 1px solid #c3c4c7 !important;
        border-bottom: none !important;
        background: #f6f7f7 !important;
        color: #555 !important;
    }
    
    .nav-tab.nav-tab-active {
        background: #ffffff !important;
        color: #000 !important;
        border-color: #c3c4c7 !important;
        border-bottom: 1px solid #ffffff !important;
        margin-bottom: -1px !important;
    }
    
    /* Form styling within the box */
    .form-table {
        background: #ffffff !important;
        margin: 20px 0 !important;
    }
    
    .form-table th {
        background: #f9f9f9 !important;
        border-right: 1px solid #e1e1e1 !important;
    }
    
    .form-table td {
        background: #ffffff !important;
    }
    
    /* Settings sections styling */
    .engagifii-setting {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 20px 30px !important;
        border-bottom: 1px solid #e1e1e1 !important;
    }
    
    .engagifii-setting:last-child {
        border-bottom: none !important;
    }
    
    /* OVERRIDE FOR 2-COLUMN STATS LAYOUT */
    .engagifii-stats-grid {
        display: flex !important;
        gap: 40px !important;
        justify-content: center !important;
        flex-wrap: nowrap !important;
        max-width: 800px !important;
        margin: 0 auto !important;
    }
    
    .engagifii-stats-grid .stat-card {
        flex: 1 !important;
        min-width: 180px !important;
        max-width: 250px !important;
        padding: 25px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* OVERRIDE TO MATCH MODULE SETTINGS PAGE EXACTLY */
    .engagifii-welcome-panel {
        background: #fff !important;
        border: none !important;
        box-shadow: none !important;
        padding: 40px 30px !important;
        border-radius: 0 !important;
        margin: 0 !important;
        text-align: center !important;
        border-left: none !important;
    }
    
    .engagifii-welcome-panel h2 {
        color: #2c3e50 !important;
        font-size: 32px !important;
        font-weight: 600 !important;
        margin: 0 0 20px 0 !important;
    }
    
    .engagifii-welcome-panel p {
        color: #7c8db5 !important;
        font-size: 16px !important;
        margin: 0 auto 40px auto !important;
        max-width: 800px !important;
        line-height: 1.6 !important;
    }
    
    .welcome-logo-container {
        margin-bottom: 30px !important;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border: 1px solid #e1e5e9 !important;
        border-radius: 12px !important;
        padding: 30px 20px !important;
        text-align: center !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }
    
    .stat-number {
        font-size: 42px !important;
        font-weight: 700 !important;
        color: #667eea !important;
        margin-bottom: 8px !important;
    }
    
    .stat-label {
        font-size: 14px !important;
        color: #8492a6 !important;
        font-weight: 500 !important;
        text-transform: none !important;
        letter-spacing: 0 !important;
    }
    
    /* Force horizontal layout on all screen sizes */
    @media (max-width: 768px) {
        .engagifii-stats-grid {
            flex-wrap: nowrap !important;
            gap: 20px !important;
        }
        
        .engagifii-stats-grid .stat-card {
            min-width: 140px !important;
            padding: 20px 15px !important;
        }
        
        .stat-number {
            font-size: 36px !important;
        }
    }
    </style>
    <div class="wrap">
        <div class="engagifii-settings-container">
            <div class="engagifii-settings-wrap">
                <div class="engagifii-settings-page">
        <div class="engagifii-page-header">
            <h1 class="engagifii-page-title"><?php echo esc_html($title); ?></h1>
        </div>
        
        <div class="engagifii-welcome-panel">
            <div class="welcome-logo-container">
                <img src="<?php echo plugins_url('assets/images/engagifii-logo.png', dirname(dirname(dirname(__FILE__)))); ?>" 
                     alt="Engagifii Logo" class="engagifii-welcome-logo">
            </div>
            
            <h2>Welcome to Engagifii!</h2>
            <p>Transform your website with powerful engagement modules. Select the features that best serve your community.</p>
            
            <div class="engagifii-stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($total_modules); ?></div>
                    <div class="stat-label">Modules Available</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($activated_modules); ?></div>
                    <div class="stat-label">Currently Active</div>
                </div>
            </div>
        </div>
        
        <!-- Content area for page-specific content -->
        <div class="engagifii-content-area">
    </div>
    <?php
}

/**
 * Close the Engagifii admin header
 */
function engagifii_close_admin_header() {
    ?>
        </div> <!-- Close .engagifii-content-area -->
            </div> <!-- Close .engagifii-settings-page -->
        </div> <!-- Close .engagifii-settings-wrap -->
    </div> <!-- Close .engagifii-settings-container -->
</div> <!-- Close .wrap -->
    <?php
}

/**
 * Enqueue admin header styles
 */
function engagifii_enqueue_admin_header_styles($hook) {
    // ULTIMATE NUCLEAR NOTICE REMOVAL - Bulletproof system
    if (isset($_GET['page']) && strpos($_GET['page'], 'engagifii') !== false) {
        
        // Phase 1: Remove at the earliest possible moment
        add_action('admin_init', function() {
            // Nuclear option - Clear ALL admin notice hooks
            global $wp_filter;
            
            // Completely wipe admin_notices
            if (isset($wp_filter['admin_notices'])) {
                $wp_filter['admin_notices'] = new WP_Hook();
            }
            
            // Completely wipe all_admin_notices  
            if (isset($wp_filter['all_admin_notices'])) {
                $wp_filter['all_admin_notices'] = new WP_Hook();
            }
            
            // Completely wipe user_admin_notices
            if (isset($wp_filter['user_admin_notices'])) {
                $wp_filter['user_admin_notices'] = new WP_Hook();
            }
            
            // Completely wipe network_admin_notices
            if (isset($wp_filter['network_admin_notices'])) {
                $wp_filter['network_admin_notices'] = new WP_Hook();
            }
        }, 1);
        
        // Phase 2: Remove specific WordPress core functions
        remove_action('admin_notices', 'update_nag', 3);
        remove_action('network_admin_notices', 'update_nag', 3);
        remove_action('admin_notices', 'maintenance_nag');
        remove_action('admin_notices', 'wp_admin_notices');
        remove_action('admin_notices', 'wp_plugin_update_rows');
        remove_action('load-plugins.php', 'wp_plugin_update_rows');
        remove_action('admin_notices', 'wp_theme_update_rows');
        remove_action('admin_notices', 'wp_dashboard_php_nag');
        remove_action('admin_notices', 'wp_site_health_notice');
        
        // Phase 3: Disable core update checks temporarily on our pages
        add_filter('pre_site_transient_update_core', '__return_null');
        add_filter('pre_site_transient_update_plugins', '__return_null');
        add_filter('pre_site_transient_update_themes', '__return_null');
        
        // Phase 4: Ultra-aggressive CSS injection at multiple points
        add_action('admin_head', function() {
            echo '<style id="engagifii-notice-killer">
                /* ULTIMATE NOTICE DESTROYER */
                #update-nag, .update-nag, .core-update-nag, 
                .notice, .error, .updated, .notice-warning, .notice-error, .notice-info, .notice-success,
                .plugin-update-tr, .theme-update-tr, .update-message,
                .wp-health-notice, .php-update-nag, .dashboard_php_nag,
                .maintenance-nag, .jetpack-message, .akismet-warning, .hello-dolly,
                div[class*="notice"], div[class*="error"], div[class*="updated"], div[class*="update"],
                div[id*="update"], div[id*="notice"], div[id*="error"],
                .wrap > div.notice, .wrap > div.error, .wrap > div.updated, .wrap > div.update-nag,
                #wpbody-content > div.notice, #wpbody-content > div.error, #wpbody-content > div.updated, #wpbody-content > div.update-nag,
                #wpbody-content > div[class*="notice"], #wpbody-content > div[class*="error"], #wpbody-content > div[class*="update"],
                body[class*="engagifii"] div.notice, body[class*="engagifii"] div.error, body[class*="engagifii"] div.updated, body[class*="engagifii"] div.update-nag,
                body.toplevel_page_engagifii-admin-settings div.notice,
                body.engagifii_page_engagifii-admin-shortcodes div.notice,
                body.engagifii_page_engagifii-dashboard-settings div.notice,
                .wp-core-ui .notice, .wp-core-ui .error, .wp-core-ui .updated,
                div[class^="notice"], div[class^="error"], div[class^="update"],
                div[class$="notice"], div[class$="error"], div[class$="update"],
                /* SPECIFIC TARGETING FOR THE EXACT NOTICE YOU SHOWED */
                #setting-error-egovt,
                div[id^="setting-error"],
                .settings-error,
                .tgmpa,
                div[class*="tgmpa"],
                div[id*="tgmpa"],
                .is-dismissible,
                /* Target by content - TGMPA and plugin activation notices */
                div:contains("required plugins"),
                div:contains("Begin updating plugins"),
                div:contains("Begin activating plugins"),
                div:contains("Dismiss this notice"),
                div:contains("Contact Form 7"),
                div:contains("GiveWP"),
                div:contains("Mailchimp"),
                div:contains("WooCommerce"),
                div:contains("One click demo import"),
                div:contains("updates available"),
                div:contains("currently inactive") { 
                    display: none !important; 
                    visibility: hidden !important;
                    opacity: 0 !important;
                    height: 0 !important;
                    width: 0 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow: hidden !important;
                    position: absolute !important;
                    left: -10000px !important;
                    top: -10000px !important;
                    z-index: -9999 !important;
                }
                
                /* Hide the yellow WordPress update bar specifically */
                #wpbody-content::before { content: none !important; }
                #wpbody-content > div:first-child:not(.engagifii-settings-page) { display: none !important; }
            </style>';
        }, 1);
        
        // Phase 5: Immediate JavaScript execution
        add_action('admin_head', function() {
            echo '<script id="engagifii-notice-destroyer">
                (function() {
                    // Execute immediately without waiting for jQuery
                    function destroyNotices() {
                        // Get all possible notice elements
                        var selectors = [
                            ".notice", ".error", ".updated", ".update-nag", "#update-nag", ".core-update-nag",
                            ".notice-warning", ".notice-error", ".notice-info", ".notice-success",
                            ".plugin-update-tr", ".theme-update-tr", ".update-message",
                            ".wp-health-notice", ".php-update-nag", ".dashboard_php_nag",
                            ".maintenance-nag", ".jetpack-message", ".akismet-warning", ".hello-dolly",
                            "div[class*=notice]", "div[class*=error]", "div[class*=updated]", "div[class*=update]",
                            "div[id*=update]", "div[id*=notice]", "div[id*=error]",
                            "#wpbody-content > div.notice", "#wpbody-content > div.error", "#wpbody-content > div.updated",
                            ".wrap > div.notice", ".wrap > div.error", ".wrap > div.updated",
                            // SPECIFIC TARGETING FOR THE EXACT NOTICE
                            "#setting-error-egovt",
                            "div[id^=setting-error]",
                            ".settings-error",
                            ".tgmpa",
                            "div[class*=tgmpa]",
                            "div[id*=tgmpa]",
                            ".is-dismissible"
                        ];
                        
                        selectors.forEach(function(selector) {
                            var elements = document.querySelectorAll(selector);
                            elements.forEach(function(el) {
                                if (el && !el.classList.contains("engagifii-settings-page")) {
                                    el.remove();
                                }
                            });
                        });
                        
                        // Also remove by content - anything mentioning WordPress updates or TGMPA stuff
                        var allDivs = document.querySelectorAll("div");
                        allDivs.forEach(function(div) {
                            var text = div.textContent || div.innerText || "";
                            var lowerText = text.toLowerCase();
                            if ((lowerText.indexOf("wordpress") !== -1 && 
                                (lowerText.indexOf("update") !== -1 || 
                                 lowerText.indexOf("available") !== -1 || 
                                 lowerText.indexOf("plugin") !== -1)) ||
                                lowerText.indexOf("required plugins") !== -1 ||
                                lowerText.indexOf("begin updating plugins") !== -1 ||
                                lowerText.indexOf("begin activating plugins") !== -1 ||
                                lowerText.indexOf("currently inactive") !== -1 ||
                                lowerText.indexOf("contact form 7") !== -1 ||
                                lowerText.indexOf("givewp") !== -1 ||
                                lowerText.indexOf("mailchimp") !== -1 ||
                                lowerText.indexOf("woocommerce") !== -1 ||
                                lowerText.indexOf("one click demo import") !== -1 ||
                                lowerText.indexOf("dismiss this notice") !== -1) {
                                div.remove();
                            }
                        });
                    }
                    
                    // Run immediately
                    destroyNotices();
                    
                    // Run every 50ms for the first 15 seconds (ultra aggressive)
                    var counter = 0;
                    var ultraInterval = setInterval(function() {
                        destroyNotices();
                        counter++;
                        if (counter >= 300) { // 300 * 50ms = 15 seconds
                            clearInterval(ultraInterval);
                            // Then run every 1 second forever
                            setInterval(destroyNotices, 1000);
                        }
                    }, 50);
                    
                    // DOM Observer - catch everything
                    if (window.MutationObserver) {
                        var observer = new MutationObserver(function(mutations) {
                            mutations.forEach(function(mutation) {
                                mutation.addedNodes.forEach(function(node) {
                                    if (node.nodeType === 1) { // Element node
                                        // Check if the added node is a notice
                                        if (node.classList && (node.classList.contains("notice") || 
                                            node.classList.contains("error") || 
                                            node.classList.contains("updated") || 
                                            node.classList.contains("update-nag"))) {
                                            node.remove();
                                        }
                                        
                                        // Check child elements too
                                        var noticeChildren = node.querySelectorAll ? 
                                            node.querySelectorAll(".notice, .error, .updated, .update-nag") : [];
                                        noticeChildren.forEach(function(child) {
                                            child.remove();
                                        });
                                    }
                                });
                            });
                            destroyNotices(); // Run full cleanup after any DOM change
                        });
                        
                        observer.observe(document.body, {
                            childList: true,
                            subtree: true,
                            attributes: true,
                            attributeFilter: ["class", "id"]
                        });
                    }
                    
                    // Also run on window load and DOMContentLoaded
                    if (document.readyState === "loading") {
                        document.addEventListener("DOMContentLoaded", destroyNotices);
                    }
                    window.addEventListener("load", destroyNotices);
                })();
                
                // jQuery version as backup
                jQuery(document).ready(function($) {
                    function jQueryDestroy() {
                        // Standard notice removal
                        $(".notice, .error, .updated, .update-nag, #update-nag, .core-update-nag").remove();
                        $(".notice-warning, .notice-error, .notice-info, .notice-success").remove();
                        $("div[class*=notice], div[class*=error], div[class*=updated], div[class*=update]").remove();
                        
                        // SPECIFIC TARGETING FOR THE EXACT NOTICE YOU SHOWED
                        $("#setting-error-egovt").remove();
                        $("div[id^=\'setting-error\']").remove();
                        $(".settings-error").remove();
                        $(".tgmpa").remove();
                        $("div[class*=\'tgmpa\']").remove();
                        $("div[id*=\'tgmpa\']").remove();
                        $(".is-dismissible").remove();
                        
                        // Remove by content - TGMPA and plugin notices
                        $("div:contains(\'required plugins\')").remove();
                        $("div:contains(\'Begin updating plugins\')").remove();
                        $("div:contains(\'Begin activating plugins\')").remove();
                        $("div:contains(\'currently inactive\')").remove();
                        $("div:contains(\'Contact Form 7\')").remove();
                        $("div:contains(\'GiveWP\')").remove();
                        $("div:contains(\'Mailchimp\')").remove();
                        $("div:contains(\'WooCommerce\')").remove();
                        $("div:contains(\'One click demo import\')").remove();
                        $("div:contains(\'Dismiss this notice\')").remove();
                        $("div:contains(\'updates available\')").remove();
                        
                        // Remove first div in wpbody-content if it\'s not our page
                        $("#wpbody-content > div:not(.engagifii-settings-page):first").remove();
                    }
                    jQueryDestroy();
                    setInterval(jQueryDestroy, 500);
                });
            </script>';
        }, 1);
    }
    
    // Only load on our admin pages
    if (strpos($hook, 'engagifii') === false) {
        return;
    }
    
    // Register and enqueue CSS
    wp_register_style(
        'engagifii-admin-header', 
        false,
        array(), 
        '1.0.0'
    );
    wp_enqueue_style('engagifii-admin-header');
    
    // Add inline CSS
    $css = "
    /* ENGAGIFII ADMIN HEADER STYLES */
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
        color: #1f2937 !important;
        font-size: 24px !important;
        font-weight: 600 !important;
        margin: 0 0 10px 0 !important;
        text-align: center !important;
    }
    
    .engagifii-welcome-panel p {
        color: #6b7280 !important;
        font-size: 16px !important;
        margin: 0 0 30px 0 !important;
        text-align: center !important;
        line-height: 1.6 !important;
    }
    
    .engagifii-stats-grid {
        display: flex !important;
        gap: 20px !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
    }
    
    .stat-card {
        flex: 1 !important;
        min-width: 200px !important;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
        padding: 20px !important;
        border-radius: 8px !important;
        text-align: center !important;
        border: 1px solid #e5e7eb !important;
        transition: all 0.3s ease !important;
    }
    
    .stat-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15) !important;
        border-color: #6366f1 !important;
    }
    
    .stat-number {
        font-size: 32px !important;
        font-weight: 700 !important;
        color: #6366f1 !important;
        margin-bottom: 5px !important;
    }
    
    .stat-label {
        font-size: 14px !important;
        color: #6b7280 !important;
        font-weight: 500 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    /* Hide WordPress default header elements */
    .center, .w-25 {
        display: none !important;
    }
    
    /* HIDE ALL WORDPRESS ADMIN NOTIFICATIONS ON ENGAGIFII PAGES */
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
    body[class*=\"engagifii\"] .notice,
    body[class*=\"engagifii\"] .error,
    body[class*=\"engagifii\"] .updated,
    body[class*=\"engagifii\"] .update-nag,
    body[class*=\"engagifii\"] #update-nag,
    body[class*=\"engagifii\"] .core-update-nag,
    body[class*=\"engagifii\"] #wpbody-content > .notice,
    body[class*=\"engagifii\"] #wpbody-content > .error,
    body[class*=\"engagifii\"] #wpbody-content > .updated,
    body[class*=\"engagifii\"] #wpbody-content > .update-nag,
    body[class*=\"engagifii\"] #wpbody-content > .notice-warning,
    body[class*=\"engagifii\"] #wpbody-content > .notice-info,
    body[class*=\"engagifii\"] #wpbody-content > .notice-error,
    body[class*=\"engagifii\"] #wpbody-content > .notice-success,
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
    ";
    
    wp_add_inline_style('engagifii-admin-header', $css);
    
    // Add JavaScript to hide any dynamically loaded notices
    $js = "
    jQuery(document).ready(function($) {
        function hideAllAdminNotices() {
            if (window.location.href.indexOf('engagifii') !== -1 || $('.engagifii-settings-page').length > 0) {
                $('.notice, .error, .updated, .update-nag, #update-nag, .core-update-nag').hide().remove();
                $('#wpbody-content > .notice, #wpbody-content > .error, #wpbody-content > .updated, #wpbody-content > .update-nag').hide().remove();
            }
        }
        
        hideAllAdminNotices();
        setInterval(hideAllAdminNotices, 500);
        
        var observer = new MutationObserver(function(mutations) {
            hideAllAdminNotices();
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    ";
    
    wp_add_inline_script('jquery', $js);
}

// Hook into WordPress admin
add_action('admin_enqueue_scripts', 'engagifii_enqueue_admin_header_styles');
?>