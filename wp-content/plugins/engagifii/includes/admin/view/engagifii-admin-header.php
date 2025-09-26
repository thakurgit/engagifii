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
    div#setting-error-egovt {
    display: none;
}
p#footer-upgrade

 {
    display: none;
}
    .engagifii-page-header {
        background: #2271b1 !important;
        color: white !important;
        padding: 30px 40px !important;
        margin: -20px -20px 30px -20px !important;
        border-radius: 0 !important;
        box-shadow: 0 4px 20px rgba(34, 113, 177, 0.3) !important;
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
        border-left: 4px solid #2271b1 !important;
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
        box-shadow: 0 8px 25px rgba(34, 113, 177, 0.15) !important;
        border-color: #2271b1 !important;
    }
    
    .stat-number {
        font-size: 32px !important;
        font-weight: 700 !important;
        color: #2271b1 !important;
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
        padding: 0px 30px !important;
        border-bottom: 1px solid #e1e1e1 !important;
    }
    .notice{
        display: none !important;
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
        color: #2271b1 !important;
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

?>
