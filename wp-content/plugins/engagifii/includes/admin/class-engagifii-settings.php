<?php
/**
 * Engagifii Module Settings
 * 
 * Handles the plugin settings and module management
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Engagifii_Settings {

    /**
     * Available modules configuration
     */
    const MODULES = array(
        'legislation' => array(
            'title' => 'Legislation Tracking',
            'description' => 'Track bills, Bill details, Public Officials and Legislative reports',
            'pages' => array('bill-tracking', 'engagifii-detail', 'legislative-tracking-database', 'engagifii-grid-view'),
            'shortcodes' => array('legislation-list', 'legislation-details')
        ),
        'events' => array(
            'title' => 'Events Management',
            'description' => 'Manage and display events',
            'pages' => array(),
            'shortcodes' => array('events-list', 'event-details')
        ),
        'classes' => array(
            'title' => 'Classes',
            'description' => 'Educational classes and training sessions',
            'pages' => array('classes', 'class-details'),
            'shortcodes' => array('classes-list-calendar-class-name', 'class-details')
        ),
        'awards' => array(
            'title' => 'Awards/Endorsements',
            'description' => 'Awards recognition and endorsement tracking system',
            'pages' => array('endorsement-grid-view', 'endorsement-detail'),
            'shortcodes' => array('awards-list', 'award-details', 'endorsement-grid-list', 'endorsement_grid_detail_information')
        ),
        'courses' => array(
            'title' => 'Courses',
            'description' => 'Educational courses and curriculum',
            'pages' => array('courses', 'course-details'),
            'shortcodes' => array('courses-list', 'course-details')
        ),
        'group_directory' => array(
            'title' => 'Group Member Directory',
            'description' => 'Group Member director',
            'pages' => array(),
            'shortcodes' => array('group-members')
        ),
        'organization_directory' => array(
            'title' => 'Organization Directory',
            'description' => 'Organization listings and details',
            'pages' => array(),
            'shortcodes' => array('organization-list', 'organization-details')
        )
    );

    /**
     * Initialize the settings class
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('wp_ajax_engagifii_save_modules', array($this, 'save_module_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Remove admin notices on our settings page
        add_action('admin_head', array($this, 'remove_admin_notices'));
    }

    /**
     * Remove admin notices on Engagifii settings page
     */
    public function remove_admin_notices() {
        $screen = get_current_screen();
        if ($screen && ($screen->id === 'engagifii_page_engagifii-settings' || $screen->id === 'settings_page_engagifii-settings')) {
            // Remove all admin notice hooks
            remove_all_actions('admin_notices');
            remove_all_actions('network_admin_notices');
            remove_all_actions('all_admin_notices');
            remove_all_actions('user_admin_notices');
            
            // Also add inline CSS as backup
            echo '<style type="text/css">
                .wrap .notice, 
                .wrap div.error, 
                .wrap div.updated,
                .notice,
                div.error,
                div.updated {
                    display: none !important;
                }
            </style>';
        }
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'engagifii-module-api',     // Parent menu slug
            'Engagifii Settings',       // Page title
            'Module Settings',          // Menu title
            'manage_options',           // Capability
            'engagifii-settings',       // Menu slug
            array($this, 'options_page') // Callback function
        );
    }

    /**
     * Initialize settings
     */
    public function settings_init() {
        register_setting('engagifii_settings', 'engagifii_enabled_modules');
        register_setting('engagifii_settings', 'engagifii_setup_completed');
        
        // Migrate endorsements to awards if needed
        $this->migrate_endorsements_to_awards();
    }

    /**
     * Migrate endorsements module to awards/endorsements
     */
    private function migrate_endorsements_to_awards() {
        $enabled_modules = get_option('engagifii_enabled_modules', array());
        if (!is_array($enabled_modules)) {
            $enabled_modules = array();
        }
        
        // Check if endorsements is enabled but awards is not
        if (in_array('endorsements', $enabled_modules) && !in_array('awards', $enabled_modules)) {
            // Remove endorsements and add awards
            $enabled_modules = array_diff($enabled_modules, array('endorsements'));
            $enabled_modules[] = 'awards';
            update_option('engagifii_enabled_modules', $enabled_modules);
        }
        // If both are enabled, just remove endorsements
        elseif (in_array('endorsements', $enabled_modules) && in_array('awards', $enabled_modules)) {
            $enabled_modules = array_diff($enabled_modules, array('endorsements'));
            update_option('engagifii_enabled_modules', $enabled_modules);
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Check for both the old settings page hook and new submenu hook
        if ('settings_page_engagifii-settings' !== $hook && 
            'engagifii_page_engagifii-settings' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'engagifii-admin-style',
            plugin_dir_url(__FILE__) . '../../assets/css/admin-settings.css',
            array(),
            ENGAGIFII_VERSION . '.' . time()  // Force cache refresh
        );
        
        /*
        wp_enqueue_script(
            'engagifii-admin-script',
            plugin_dir_url(__FILE__) . '../../assets/js/admin-settings.js',
            array('jquery'),
            ENGAGIFII_VERSION,
            true
        );
        */
        
        /*
        wp_localize_script('engagifii-admin-script', 'engagifii_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('engagifii_settings_nonce')
        ));
        */
        
        // Add inline script for tab functionality
        wp_add_inline_script('engagifii-admin-script', '
            console.log("Inline script loaded");
            
            // Vanilla JavaScript tab functionality
            document.addEventListener("DOMContentLoaded", function() {
                console.log("DOM ready - inline script");
                
                var tabButtons = document.querySelectorAll(".tab-button");
                console.log("Found tab buttons:", tabButtons.length);
                
                tabButtons.forEach(function(button) {
                    button.addEventListener("click", function(e) {
                        e.preventDefault();
                        var tabId = this.getAttribute("data-tab");
                        console.log("Tab clicked:", tabId);
                        
                        // Remove active from all
                        document.querySelectorAll(".tab-button").forEach(function(b) {
                            b.classList.remove("active");
                        });
                        document.querySelectorAll(".tab-panel").forEach(function(p) {
                            p.classList.remove("active");
                            p.style.display = "none";
                        });
                        
                        // Activate clicked tab
                        this.classList.add("active");
                        var panel = document.getElementById(tabId + "-tab");
                        if (panel) {
                            panel.classList.add("active");
                            panel.style.display = "block";
                            console.log("Activated panel:", tabId);
                        }
                    });
                });
            });
        ');
    }

    /**
     * Settings page HTML
     */
    public function options_page() {
        $enabled_modules = get_option('engagifii_enabled_modules', array());
        
        // Ensure $enabled_modules is always an array
        if (!is_array($enabled_modules)) {
            $enabled_modules = array();
        }
        
        $setup_completed = get_option('engagifii_setup_completed', false);
        $is_initial_setup = !$setup_completed && isset($_GET['setup']) && $_GET['setup'] === '1';
        $show_welcome = $is_initial_setup || !$setup_completed; // Show welcome for initial setup OR if setup never completed
        ?>
        
        <div class="wrap engagifii-settings-page">
            <div class="engagifii-settings-container">
                <div class="engagifii-settings-wrap">
                    <h1><?php echo $is_initial_setup ? 'Engagifii Initial Setup' : 'Engagifii Module Settings'; ?></h1>
            
            <?php if ($show_welcome): ?>
                <div class="engagifii-welcome-panel">
                    <div class="welcome-panel-content">
                        <div class="welcome-logo-container">
                            <img src="<?php echo plugin_dir_url(__FILE__) . '../../assets/images/engagifii-logo.png'; ?>" alt="Engagifii Logo" class="engagifii-welcome-logo" />
                        </div>
                        <h2>Welcome to Engagifii!</h2>
                        <p class="about-description">Transform your website with powerful engagement modules. Select the features that best serve your community.</p>
                        
                        <div class="welcome-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?php echo count(self::MODULES); ?></span>
                                <span class="stat-label">Modules Available</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo count($enabled_modules); ?></span>
                                <span class="stat-label">Currently Active</span>
                            </div>
                        </div>
                        
                        <!-- Tabbed Interface -->
                        <form id="engagifii-module-form" method="post" action="options.php">
                            <?php settings_fields('engagifii_settings'); ?>
                            
                            <div class="engagifii-setup-tabs">
                            <div class="tab-navigation">
                                <button type="button" class="tab-button active" data-tab="modules">
                                    <span class="dashicons dashicons-admin-plugins"></span>
                                    Module Selection
                                </button>
                                <button type="button" class="tab-button" data-tab="help">
                                    <span class="dashicons dashicons-info"></span>
                                    Setup Guide & Help
                                </button>
                            </div>
                            
                            <div class="tab-content">
                                <!-- Module Selection Tab -->
                                <div id="modules-tab" class="tab-panel active">
                                    <div class="tab-header">
                                        <h3>Choose Your Modules</h3>
                                        <p>Select the engagement features you want to enable for your website.</p>
                                    </div>
                                    
                                    <div class="engagifii-modules-grid">
                                        <?php foreach (self::MODULES as $module_key => $module_data): ?>
                                            <div class="engagifii-module-card <?php echo in_array($module_key, $enabled_modules) ? 'active' : ''; ?>">
                                                <div class="module-header">
                                                    <div class="module-icon">
                                                        <?php
                                                        $plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
                                                        $icons = [
                                                            'legislation' => $plugin_url . 'assets/images/all-state-bill-icon.png',
                                                            'events' => $plugin_url . 'assets/images/Events.png',
                                                            'classes' => $plugin_url . 'assets/images/class_updated.png',
                                                            'awards' => $plugin_url . 'assets/images/trophy.png',
                                                            'courses' => $plugin_url . 'assets/images/course-icon.png',
                                                            'group_directory' => $plugin_url . 'assets/images/groups.png',
                                                            'organization_directory' => $plugin_url . 'assets/images/organizations.png'
                                                        ];
                                                        $icon_url = $icons[$module_key] ?? ($plugin_url . 'assets/images/engagifii-logo.png');
                                                        ?>
                                                        <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($module_data['title']); ?>" class="module-icon-img" />
                                                    </div>
                                                    <div class="module-toggle-container">
                                                        <label class="modern-toggle module-toggle">
                                                            <input 
                                                                type="checkbox" 
                                                                name="engagifii_enabled_modules[]" 
                                                                value="<?php echo esc_attr($module_key); ?>"
                                                                <?php checked(in_array($module_key, $enabled_modules)); ?>
                                                                class="module-checkbox"
                                                            />
                                                            <span class="toggle-slider">
                                                                <span class="toggle-button"></span>
                                                                <span class="toggle-text-on">ON</span>
                                                                <span class="toggle-text-off">OFF</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="module-content">
                                                    <h3 class="module-title"><?php echo esc_html($module_data['title']); ?></h3>
                                                    <p class="module-description"><?php echo esc_html($module_data['description']); ?></p>
                                                    <?php if (isset($module_data['features']) && !empty($module_data['features'])): ?>
                                                        <ul class="module-features">
                                                            <?php foreach ($module_data['features'] as $feature): ?>
                                                                <li><span class="dashicons dashicons-yes-alt"></span> <?php echo esc_html($feature); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Tab Actions -->
                                    <div class="tab-actions">
                                        <?php if ($is_initial_setup): ?>
                                            <input type="hidden" name="engagifii_setup_completed" value="1">
                                            <button type="submit" class="button button-primary button-hero">
                                                <span class="dashicons dashicons-yes-alt"></span>
                                                Complete Setup
                                            </button>
                                            <p class="action-description">Save your module selection and complete the setup process.</p>
                                        <?php else: ?>
                                            <button type="submit" class="button button-primary button-large">
                                                <span class="dashicons dashicons-update"></span>
                                                Save Module Settings
                                            </button>
                                            <p class="action-description">Update your module configuration and apply changes.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Help & Setup Guide Tab -->
                                <div id="help-tab" class="tab-panel">
                                    <div class="tab-header">
                                        <h3>Setup Guide & Help</h3>
                                        <p>Everything you need to get started with Engagifii.</p>
                                    </div>
                                    
                                    <div class="setup-section">
                                        <h4>🚀 Getting Started:</h4>
                                        <div class="setup-steps">
                                            <div class="step-item">
                                                <span class="step-number">1</span>
                                                <span class="step-text">Select modules in the "Module Selection" tab</span>
                                            </div>
                                            <div class="step-item">
                                                <span class="step-number">2</span>
                                                <span class="step-text"><a href="<?php echo admin_url('admin.php?page=engagifii-module-api&tab=settings'); ?>" target="_blank">Configure API settings</a></span>
                                            </div>
                                            <div class="step-item">
                                                <span class="step-number">3</span>
                                                <span class="step-text"><a href="<?php echo admin_url('admin.php?page=engagifii-module-api&tab=shortcode'); ?>" target="_blank">Use shortcodes on your pages</a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="help-section">
                                        <h4>💡 Need Additional Help?</h4>
                                        <p>Configure powerful community engagement features and customize appearance after enabling modules.</p>
                                        
                                        <div class="help-grid">
                                            <div class="help-item">
                                                <h5>📖 Documentation</h5>
                                                <p>Read our comprehensive setup guides and feature documentation.</p>
                                                <a href="https://docs.google.com/document/d/1pNd0OG0W0sjqMvJhW1Iu0dFHOrLsSldBX07XoSLsT7s/edit?usp=sharing" class="button button-secondary" target="_blank">View Docs</a>
                                            </div>
                                            <div class="help-item">
                                                <h5>🎯 Quick Actions</h5>
                                                <div class="quick-actions">
                                                    <a href="<?php echo admin_url('admin.php?page=engagifii-module-api'); ?>" class="button button-primary" target="_blank">
                                                        <span class="dashicons dashicons-admin-settings"></span>
                                                        Configure Settings
                                                    </a>
                                                    <a href="<?php echo admin_url('admin.php?page=engagifii-module-api&tab=shortcode'); ?>" class="button button-secondary" target="_blank">
                                                        <span class="dashicons dashicons-editor-code"></span>
                                                        Browse Shortcodes
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            <?php endif; ?>

            <style>
            /* EMERGENCY BASIC STYLING - In case external CSS doesn't load */
            .engagifii-settings-wrap {
                max-width: 1200px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                margin: 20px 0;
            }
            
            .tab-navigation {
                display: flex;
                border-bottom: 2px solid #e2e8f0;
                margin-bottom: 20px;
                background: rgba(255, 255, 255, 0.9);
            }
            
            .tab-button {
                background: none !important;
                border: none !important;
                padding: 15px 25px !important;
                cursor: pointer !important;
                font-size: 16px !important;
                color: #64748b !important;
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                transition: all 0.3s ease !important;
            }
            
            .tab-button.active {
                color: #ffffff !important;
                background: #2271b1 !important;
                font-weight: 600 !important;
            }
            
            .tab-panel {
                display: none !important;
                padding: 30px;
            }
            
            .tab-panel.active {
                display: block !important;
            }
            
            .engagifii-modules-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 20px 0;
            }
            
            .module-header {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .module-toggle {
                position: relative;
                display: inline-block;
                width: 44px;
                height: 24px;
                margin-right: 15px;
            }
            
            .toggle-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                border-radius: 24px;
                transition: .4s;
            }
            
            .toggle-slider:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }
            
            input:checked + .toggle-slider {
                background-color: #00a32a;
            }
            
            input:checked + .toggle-slider:before {
                transform: translateX(20px);
            }
            
            /* WordPress Admin Notices Positioning */
            .wrap {
                margin: 0;
                padding: 0;
            }
            
            /* Ensure top-level notices are visible and positioned properly */
            body.wp-admin .wrap > .notice,
            body.wp-admin .wrap > .error, 
            body.wp-admin .wrap > .updated {
                display: block !important;
                margin: 20px 20px 20px 0 !important;
                position: relative;
                z-index: 1000;
            }
            
            /* Allow specific important notices to show even inside our container */
            .engagifii-settings-container .notice:not(.hidden),
            .engagifii-settings-wrap .notice:not(.hidden) {
                display: block !important;
                margin: 10px 0 !important;
            }
            
            /* Only hide generic duplicates, not important plugin notices */
            .engagifii-settings-wrap .notice.duplicate,
            .engagifii-settings-container .notice.duplicate {
                display: none !important;
            }
            
            .engagifii-settings-container {
                margin: 0;
                padding: 0;
                position: relative;
                z-index: 1;
            }
            
            /* Hide WordPress version notice on Engagifii settings page */
            #wp-version-message,
            #footer-upgrade {
                display: none !important;
            }
            
            /* Simple notice hiding - same approach as engagifii_modules.php */
            .wrap .notice, 
            .wrap div.error, 
            .wrap div.updated {
                display: none !important;
            }
            
            /* Prevent WordPress admin bar from interfering */
            .wp-admin .engagifii-settings-wrap {
                margin-top: 0 !important;
            }
            
            /* Ensure admin notices appear above our header */
            #wpbody-content > .wrap > .notice,
            #wpbody-content > .notice {
                position: relative;
                z-index: 1001;
                margin: 20px 20px 20px 0 !important;
            }
            
            /* Modern Professional Styling */
            .engagifii-settings-wrap {
                max-width: 1200px;
                margin: 20px auto;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .engagifii-settings-wrap h1 {
                background: linear-gradient(135deg, #2271b1 0%, #2271b1 100%);
                color: white;
                margin: 0;
                padding: 30px 40px;
                font-size: 28px;
                font-weight: 600;
                text-align: center;
            }

            /* Welcome Panel */
            .engagifii-welcome-panel {
                background: #fff;
                border: 1px solid #c3c4c7;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                padding: 23px 10px 0;
                position: relative;
                border-left: 4px solid #2271b1;
            }

            .welcome-logo-container {
                text-align: center;
                margin-bottom: 30px;
            }

            .engagifii-welcome-logo {
                max-width: 280px;
                height: auto;
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                transition: transform 0.3s ease;
            }

            .engagifii-welcome-logo:hover {
                transform: scale(1.02);
            }

            .welcome-panel-content h2 {
                font-size: 32px;
                color: #2c3e50;
                text-align: center;
                margin: 0 0 15px 0;
                font-weight: 700;
            }

            .about-description {
                font-size: 18px;
                color: #5a6c7d;
                text-align: center;
                margin-bottom: 30px;
                line-height: 1.6;
            }

            /* Welcome Stats */
            .welcome-stats {
                display: flex;
                justify-content: center;
                gap: 40px;
                margin: 30px 0;
            }

            .stat-item {
                text-align: center;
                padding: 20px;
                background: rgba(255, 255, 255, 0.8);
                border-radius: 12px;
                border: 2px solid #e1e8ff;
                min-width: 120px;
            }

            .stat-number {
                display: block;
                font-size: 32px;
                font-weight: 700;
                color: #2271b1;
                margin-bottom: 5px;
            }

            .stat-label {
                font-size: 14px;
                color: #5a6c7d;
                font-weight: 500;
            }

            /* Welcome Unified Panel */
            .welcome-panel-unified {
                background: rgba(255, 255, 255, 0.9);
                padding: 40px;
                border-radius: 12px;
                border: 2px solid #e1e8ff;
                margin-top: 40px;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
            }

            .unified-header {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 15px;
                margin-bottom: 30px;
                text-align: center;
            }

            .header-icon {
                font-size: 32px;
            }

            .unified-header h3 {
                margin: 0;
                color: #2c3e50;
                font-size: 24px;
                font-weight: 600;
            }

            .setup-section {
                margin-bottom: 30px;
            }

            .setup-section h4 {
                margin: 0 0 20px 0;
                color: #2271b1;
                font-size: 18px;
                font-weight: 600;
                text-align: left;
            }

            /* Setup Steps - Horizontal Layout */
            .setup-steps {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px 30px;
                margin-bottom: 25px;
            }

            .step-item {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 12px 15px;
                background: rgba(34, 113, 177, 0.05);
                border-radius: 8px;
                border: 1px solid rgba(34, 113, 177, 0.1);
            }

            .step-number {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                background: #2271b1;
                color: white;
                border-radius: 50%;
                font-weight: 600;
                font-size: 13px;
                flex-shrink: 0;
            }

            .step-text {
                flex: 1;
                color: #4a5568;
                line-height: 1.4;
                font-size: 14px;
            }

            .step-text a {
                color: #2271b1;
                text-decoration: none;
                font-weight: 500;
            }

            .step-text a:hover {
                text-decoration: underline;
            }

            /* Help Section */
            .help-section {
                border-top: 1px solid #e1e8ff;
                padding-top: 25px;
                text-align: center;
            }

            .help-section p {
                color: #4a5568;
                margin: 0 0 20px 0;
                font-size: 15px;
                line-height: 1.5;
            }

            .help-section strong {
                color: #2c3e50;
            }

            /* Unified Actions */
            .unified-actions {
                display: flex;
                gap: 15px;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            /* Tab System Styles */
            .engagifii-setup-tabs {
                margin-top: 30px;
            }
            
            /* All tab styles moved to external CSS file: assets/css/admin-settings.css */
            
            .tab-header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .tab-header h3 {
                color: #1e293b;
                font-size: 24px;
                margin: 0 0 10px 0;
                font-weight: 600;
            }
            
            .tab-header p {
                color: #64748b;
                font-size: 16px;
                margin: 0;
                line-height: 1.5;
            }
            
            /* Button Icon Alignment */
            .button .dashicons,
            .tab-actions .button .dashicons,
            .quick-actions .button .dashicons,
            .unified-actions .button .dashicons {
                vertical-align: middle !important;
                margin-right: 8px !important;
                margin-top: -2px !important;
                font-size: 16px !important;
                width: 16px !important;
                height: 16px !important;
                line-height: 1 !important;
            }
            
            .button-hero .dashicons {
                font-size: 18px !important;
                width: 18px !important;
                height: 18px !important;
                margin-right: 10px !important;
                margin-top: -1px !important;
            }
            
            .button-large .dashicons {
                font-size: 17px !important;
                width: 17px !important;
                height: 17px !important;
                margin-right: 9px !important;
                margin-top: -1px !important;
            }
            
            /* Tab Actions Specific */
            .tab-actions .button {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                line-height: 1.4 !important;
            }
            
            /* Quick Actions Specific */
            .quick-actions .button {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }
            
            .quick-actions .button .dashicons {
                margin-right: 0 !important;
                flex-shrink: 0 !important;
            }
            
            /* Tab Actions */
            .tab-actions {
                text-align: center;
                padding: 30px 0 0 0;
                border-top: 1px solid #e2e8f0;
                margin-top: 30px;
            }
            
            .tab-actions .button-hero {
                font-size: 18px;
                padding: 15px 40px;
                height: auto;
            }
            
            .tab-actions .button-large {
                font-size: 16px;
                padding: 12px 30px;
                height: auto;
            }
            
            .tab-actions .action-description {
                color: #64748b;
                font-size: 14px;
                margin: 15px 0 0 0;
                font-style: italic;
            }
            
            /* Help Grid */
            .help-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-top: 30px;
            }
            
            .help-item {
                background: #f8fafc;
                padding: 25px;
                border-radius: 8px;
                border-left: 4px solid #2271b1;
            }
            
            .help-item h5 {
                color: #1e293b;
                font-size: 18px;
                margin: 0 0 10px 0;
                font-weight: 600;
            }
            
            .help-item p {
                color: #64748b;
                margin: 0 0 20px 0;
                line-height: 1.5;
            }
            
            .quick-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            /* Button Icon Alignment */
            .quick-actions .button {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
                vertical-align: middle !important;
                line-height: 1.4 !important;
            }
            
            .quick-actions .button .dashicons {
                font-size: 16px !important;
                width: 16px !important;
                height: 16px !important;
                line-height: 1 !important;
                vertical-align: middle !important;
                margin: 0 !important;
                flex-shrink: 0 !important;
            }
            
            .quick-actions .button-primary {
                padding: 8px 16px !important;
                height: auto !important;
            }
            
            .quick-actions .button-secondary {
                padding: 8px 16px !important;
                height: auto !important;
            }
            
            @media (max-width: 768px) {
                .help-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                
                /* All tab mobile styles moved to external CSS file: assets/css/admin-settings.css */
            }

            .unified-actions .button {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 24px;
                border-radius: 8px;
                transition: all 0.2s ease;
                font-size: 14px;
                font-weight: 500;
                white-space: nowrap;
                min-height: 44px;
            }

            .unified-actions .button .dashicons {
                font-size: 16px;
            }

            /* Modules Section */
            .modules-section {
                padding: 40px;
            }

            .section-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .section-header h2 {
                font-size: 28px;
                color: #2c3e50;
                margin: 0 0 15px 0;
                font-weight: 600;
            }

            .section-description {
                font-size: 16px;
                color: #5a6c7d;
                margin: 0;
                line-height: 1.6;
            }

            /* Module Grid */
            .engagifii-modules-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 25px;
                margin: 0;
            }

            /* Module Cards */
            .engagifii-module-card {
                background: #fff;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                transition: all 0.3s ease;
                overflow: hidden;
                position: relative;
            }

            .engagifii-module-card:hover {
                border-color: #2271b1;
                box-shadow: 0 8px 24px rgba(34, 113, 177, 0.15);
                transform: translateY(-2px);
            }

            .engagifii-module-card.active {
                border-color: #48bb78;
                background: #f0fff4 !important;
            }

            /* Ensure inactive modules have white background */
            .engagifii-module-card:not(.active) {
                background: #fff !important;
            }

            /* Remove the old checkmark since toggle is now visible */
            .engagifii-module-card.active::before {
                display: none;
            }

            /* Module Header */
            .module-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                padding: 20px 20px 10px 20px;
                position: relative;
            }

            .module-icon {
                font-size: 32px;
                flex-shrink: 0;
                width: 50px;
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(34, 113, 177, 0.1);
                border-radius: 12px;
            }

            .module-icon-img {
                width: 32px;
                height: 32px;
                object-fit: contain;
                filter: opacity(0.8);
                transition: filter 0.3s ease;
            }

            .engagifii-module-card.active .module-icon-img {
                filter: opacity(1) saturate(1.2);
            }

            /* Module Toggle - positioned top-right */
            .module-toggle {
                position: absolute;
                top: 15px;
                right: 15px;
                z-index: 10;
            }

            /* Module Title Section */
            .module-title-section {
                flex: 1;
                padding: 0 70px 0 15px; /* Right padding to avoid toggle overlap */
            }

            .module-title {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
                color: #2c3e50;
                line-height: 1.3;
                word-wrap: break-word;
            }

            /* Modern Toggle Switch */
            .modern-toggle {
                display: flex;
                align-items: center;
                cursor: pointer;
                user-select: none;
                position: relative;
            }

            .modern-toggle *,
            .modern-toggle *::before,
            .modern-toggle *::after {
                box-sizing: border-box;
            }

            .modern-toggle input[type="checkbox"] {
                display: none;
                opacity: 0;
                position: absolute;
                left: -9999px;
            }

            .toggle-slider {
                position: relative;
                width: 64px;
                height: 32px;
                background: #cbd5e0;
                border-radius: 16px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                flex-shrink: 0;
                overflow: hidden;
                border: none;
                outline: none;
            }

            .toggle-slider::before,
            .toggle-slider::after {
                display: none;
                content: none;
            }

            .toggle-button {
                position: absolute;
                width: 26px;
                height: 26px;
                background: white;
                border-radius: 50%;
                left: 3px;
                top: 3px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
                z-index: 2;
                border: none;
                outline: none;
            }

            .toggle-button::before,
            .toggle-button::after {
                display: none;
                content: none;
            }

            .modern-toggle input:checked + .toggle-slider {
                background: #38a169;
            }

            .modern-toggle input:checked + .toggle-slider .toggle-button {
                left: 35px;
                background: white;
                border: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            }

            .modern-toggle input:not(:checked) + .toggle-slider .toggle-button {
                left: 3px;
                background: white;
                border: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            }

            /* Toggle Text on Slider */
            .toggle-text-on,
            .toggle-text-off {
                position: absolute;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                top: 50%;
                transform: translateY(-50%);
                transition: all 0.3s ease;
                z-index: 1;
            }

            .toggle-text-on {
                left: 8px;
                color: white;
                opacity: 0;
            }

            .toggle-text-off {
                right: 6px;
                color: #64748b;
                opacity: 1;
            }

            /* Show/Hide text based on toggle state */
            .modern-toggle input:checked + .toggle-slider .toggle-text-on {
                opacity: 1;
            }

            .modern-toggle input:checked + .toggle-slider .toggle-text-off {
                opacity: 0;
            }

            .modern-toggle input:not(:checked) + .toggle-slider .toggle-text-on {
                opacity: 0;
            }

            .modern-toggle input:not(:checked) + .toggle-slider .toggle-text-off {
                opacity: 1;
            }

            /* Module Content */
            .module-content {
                padding: 10px 20px 20px 20px;
            }

            .module-description {
                color: #5a6c7d;
                line-height: 1.6;
                margin: 0 0 15px 0;
                font-size: 14px;
            }

            .module-features {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                background: rgba(34, 113, 177, 0.05);
                border-radius: 8px;
                border: 1px solid rgba(34, 113, 177, 0.1);
            }

            .features-label {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: #2271b1;
                font-weight: 500;
            }

            .features-label .dashicons {
                font-size: 16px;
            }

            /* Actions Section */
            .engagifii-actions {
                background: #f8fafc;
                padding: 40px;
                border-top: 1px solid #e2e8f0;
            }

            .actions-container {
                max-width: 800px;
                margin: 0 auto;
                text-align: center;
            }

            .action-primary {
                margin-bottom: 30px;
            }

            .action-primary .button {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 15px 30px;
                font-size: 16px;
                border-radius: 8px;
                transition: all 0.2s ease;
            }

            .action-primary .button:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            /* Custom button styling to match theme */
            .engagifii-settings-wrap .button-primary,
            .engagifii-settings-wrap .button-primary:hover,
            .engagifii-settings-wrap .button-primary:focus,
            .engagifii-settings-wrap .button-primary:active {
                background: #2271b1 !important;
                border-color: #2271b1 !important;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(34, 113, 177, 0.2) !important;
            }

            .engagifii-settings-wrap .button-primary:hover {
                background: #5a67d8 !important;
                border-color: #5a67d8 !important;
                box-shadow: 0 4px 8px rgba(34, 113, 177, 0.3) !important;
            }

            .action-description {
                margin: 15px 0 0 0;
                color: #5a6c7d;
                font-style: italic;
            }

            /* Quick Actions */
            .quick-actions {
                display: flex;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .quick-action-link {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 20px;
                background: white;
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                color: #4a5568;
                text-decoration: none;
                transition: all 0.2s ease;
                font-weight: 500;
            }

            .quick-action-link:hover {
                border-color: #2271b1;
                color: #2271b1;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(34, 113, 177, 0.1);
            }

            .quick-action-link .dashicons {
                font-size: 18px;
            }

            /* Responsive Design */
            @media (max-width: 1024px) {
                .engagifii-modules-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                
                .welcome-panel-unified {
                    padding: 35px;
                    max-width: 90%;
                }
                
                .setup-steps {
                    grid-template-columns: 1fr;
                    gap: 12px;
                }
                
                .unified-actions {
                    flex-direction: column;
                    align-items: center;
                }
                
                .unified-actions .button {
                    width: 200px;
                }
                
                .welcome-stats {
                    flex-direction: column;
                    gap: 20px;
                    align-items: center;
                }
            }

            @media (max-width: 768px) {
                .engagifii-settings-wrap {
                    margin: 10px;
                    border-radius: 8px;
                }
                
                .engagifii-welcome-panel, .modules-section, .engagifii-actions {
                    padding: 20px;
                }
                
                .welcome-panel-unified {
                    padding: 25px 20px;
                    max-width: 95%;
                    margin-top: 30px;
                }
                
                /* Mobile adjustments for new toggle layout */
                .module-header {
                    padding: 15px 15px 8px 15px;
                }
                
                .module-toggle {
                    top: 12px;
                    right: 12px;
                }
                
                .module-title-section {
                    padding: 0 60px 0 12px; /* Reduced right padding for smaller screens */
                }
                
                .module-title {
                    font-size: 16px;
                }
                
                .module-icon {
                    width: 40px;
                    height: 40px;
                    font-size: 24px;
                }
                
                .module-icon-img {
                    width: 24px;
                    height: 24px;
                }
                
                .toggle-slider {
                    width: 56px;
                    height: 28px;
                    border-radius: 14px;
                }
                
                .toggle-button {
                    width: 22px;
                    height: 22px;
                    top: 3px;
                }
                
                .modern-toggle input:checked + .toggle-slider .toggle-button {
                    left: 31px;
                }
                
                .toggle-text-on,
                .toggle-text-off {
                    font-size: 9px;
                }
                
                .toggle-text-on {
                    left: 6px;
                }
                
                .toggle-text-off {
                    right: 5px;
                }
                
                .unified-header h3 {
                    font-size: 20px;
                }
                
                .setup-section h4 {
                    font-size: 16px;
                }
                
                .step-item {
                    padding: 10px 12px;
                }
                
                .step-number {
                    width: 24px;
                    height: 24px;
                    font-size: 12px;
                }
                
                .step-text {
                    font-size: 13px;
                }
                
                .help-section p {
                    font-size: 14px;
                }
                
                .unified-actions .button {
                    width: 100%;
                    max-width: 250px;
                }
                
                .engagifii-welcome-logo {
                    max-width: 220px;
                }
                
                .quick-actions {
                    flex-direction: column;
                    align-items: center;
                }
                
                .section-header h2 {
                    font-size: 24px;
                }
                
                .welcome-panel-content h2 {
                    font-size: 26px;
                }
            }
            </style>
        </div>

        <script>
        console.log('INLINE: Script section executing');
        
        // Make sure ajaxurl is available
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        
        // Hide admin notices on page load and dynamically
        (function() {
            function hideAdminNotices() {
                const notices = document.querySelectorAll('.notice, .error, .updated, div.notice, div.error, div.updated');
                notices.forEach(function(notice) {
                    if (notice.closest('.engagifii-settings-page')) {
                        notice.style.display = 'none';
                    }
                });
            }
            
            // Hide on load
            document.addEventListener('DOMContentLoaded', hideAdminNotices);
            
            // Hide any notices that appear later
            const observer = new MutationObserver(hideAdminNotices);
            observer.observe(document.body, { childList: true, subtree: true });
        })();
        
        // Tab functionality with multiple approaches
        (function() {
            function initTabs() {
                console.log('Initializing tabs...');
                
                // Vanilla JavaScript approach
                const tabButtons = document.querySelectorAll('.tab-button');
                const tabPanels = document.querySelectorAll('.tab-panel');
                
                tabButtons.forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const tabId = this.getAttribute('data-tab');
                        console.log('Vanilla JS tab clicked:', tabId);
                        
                        // Remove active class from all buttons and panels
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        tabPanels.forEach(panel => {
                            panel.classList.remove('active');
                            panel.style.display = 'none';
                        });
                        
                        // Add active class to clicked button and corresponding panel
                        this.classList.add('active');
                        const targetPanel = document.getElementById(tabId + '-tab');
                        if (targetPanel) {
                            targetPanel.classList.add('active');
                            targetPanel.style.display = 'block';
                        }
                    });
                });
                
                // Add event listeners for module checkboxes to update count in real-time (vanilla JS)
                const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
                moduleCheckboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        updateActiveModuleCountVanilla();
                        updateModuleCardBackground(this);
                    });
                });
                
                // Function to update module card background instantly when toggle is clicked
                function updateModuleCardBackground(checkbox) {
                    const moduleCard = checkbox.closest('.engagifii-module-card');
                    if (moduleCard) {
                        if (checkbox.checked) {
                            // Module is activated - add active class and green background
                            moduleCard.classList.add('active');
                        } else {
                            // Module is deactivated - remove active class and use white background
                            moduleCard.classList.remove('active');
                        }
                    }
                }
                
                // Function to update the active module count (vanilla JS)
                function updateActiveModuleCountVanilla() {
                    const activeCount = document.querySelectorAll('.module-checkbox:checked').length;
                    const statElements = document.querySelectorAll('.stat-label');
                    statElements.forEach(function(element) {
                        if (element.textContent.includes('Currently Active')) {
                            const numberElement = element.parentElement.querySelector('.stat-number');
                            if (numberElement) {
                                numberElement.textContent = activeCount;
                            }
                        }
                    });
                }
                
                // jQuery approach as fallback
                if (typeof jQuery !== 'undefined') {
                    jQuery(document).ready(function($) {
                        console.log('jQuery tabs initialization');
                        
                        $('.tab-button').off('click.tabs').on('click.tabs', function(e) {
                            e.preventDefault();
                            var tabId = $(this).data('tab');
                            console.log('jQuery tab clicked:', tabId);
                            
                            $('.tab-button').removeClass('active');
                            $('.tab-panel').removeClass('active').hide();
                            
                            $(this).addClass('active');
                            $('#' + tabId + '-tab').addClass('active').show();
                        });
                        
                        // Handle form submission for initial setup
                        <?php if ($is_initial_setup): ?>
                        $('#engagifii-module-form').on('submit', function(e) {
                            e.preventDefault();
                            
                            var selectedModules = [];
                            $('input[name="engagifii_enabled_modules[]"]:checked').each(function() {
                                selectedModules.push($(this).val());
                            });
                            
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'engagifii_initial_setup',
                                    modules: selectedModules,
                                    nonce: '<?php echo wp_create_nonce("engagifii_settings_nonce"); ?>'
                                },
                                beforeSend: function() {
                                    $('.button-hero').prop('disabled', true).text('Setting up...');
                                },
                                success: function(response) {
                                    if (response.success) {
                                        window.location.href = '<?php echo admin_url("admin.php?page=engagifii-settings&setup=completed"); ?>';
                                    } else {
                                        alert('Error: ' + response.data);
                                        $('.button-hero').prop('disabled', false).text('Complete Setup');
                                    }
                                }
                            });
                        });
                        <?php endif; ?>
                        
                        <?php if (!$is_initial_setup): ?>
                        // Handle regular module settings save
                        $('#engagifii-module-form').on('submit', function(e) {
                            e.preventDefault();
                            
                            var selectedModules = [];
                            $('input[name="engagifii_enabled_modules[]"]:checked').each(function() {
                                selectedModules.push($(this).val());
                            });
                            
                            console.log('Selected modules:', selectedModules);
                            console.log('Form data to send:', {
                                action: 'engagifii_save_modules',
                                modules: selectedModules,
                                nonce: '<?php echo wp_create_nonce("engagifii_settings_nonce"); ?>'
                            });
                            
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'engagifii_save_modules',
                                    modules: selectedModules,
                                    nonce: '<?php echo wp_create_nonce("engagifii_settings_nonce"); ?>'
                                },
                                beforeSend: function() {
                                    $('.button-primary').prop('disabled', true).html('<span class="dashicons dashicons-update"></span> Saving...');
                                },
                                success: function(response) {
                                    if (response.success) {
                                        $('.button-primary').prop('disabled', false).html('<span class="dashicons dashicons-yes"></span> Saved!');
                                        
                                        // Show success message
                                        var successMessage = $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                                        $('.engagifii-settings-wrap').prepend(successMessage);
                                        
                                        // Reset button after 2 seconds
                                        setTimeout(function() {
                                            $('.button-primary').html('<span class="dashicons dashicons-update"></span> Save Module Settings');
                                        }, 2000);
                                        
                                        // Remove success message after 5 seconds
                                        setTimeout(function() {
                                            successMessage.fadeOut(500, function() {
                                                $(this).remove();
                                            });
                                        }, 5000);
                                    } else {
                                        $('.button-primary').prop('disabled', false).html('<span class="dashicons dashicons-update"></span> Save Module Settings');
                                        alert('Error: ' + response.data);
                                    }
                                },
                                error: function() {
                                    $('.button-primary').prop('disabled', false).html('<span class="dashicons dashicons-update"></span> Save Module Settings');
                                    alert('An error occurred while saving. Please try again.');
                                }
                            });
                        });
                        <?php endif; ?>
                        
                        // Add event listener for module checkboxes to update count in real-time
                        $('.module-checkbox').on('change', function() {
                            updateActiveModuleCount();
                        });
                        
                        // Function to update the active module count
                        function updateActiveModuleCount() {
                            var activeCount = $('.module-checkbox:checked').length;
                            $('.stat-item .stat-label:contains("Currently Active")').siblings('.stat-number').text(activeCount);
                        }
                    });
                }
            }
            
            // Initialize tabs with multiple attempts
            document.addEventListener('DOMContentLoaded', initTabs);
            setTimeout(initTabs, 100);
            setTimeout(initTabs, 500);
            setTimeout(initTabs, 1000);
        })();
        </script>
            </div> <!-- .engagifii-settings-wrap -->
        </div> <!-- .engagifii-settings-container -->
        </div> <!-- .wrap -->
        <?php
    }

    /**
     * Handle initial setup AJAX
     */
    public static function handle_initial_setup() {
        check_ajax_referer('engagifii_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $modules = isset($_POST['modules']) ? $_POST['modules'] : array();
        
        // Save enabled modules
        update_option('engagifii_enabled_modules', $modules);
        update_option('engagifii_setup_completed', true);
        
        // Create pages for enabled modules
        self::create_module_pages($modules);
        
        wp_send_json_success('Setup completed successfully');
    }

    /**
     * Handle create pages AJAX
     */
    public static function handle_create_pages() {
        check_ajax_referer('engagifii_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $modules = isset($_POST['modules']) ? $_POST['modules'] : array();
        
        // Update enabled modules
        update_option('engagifii_enabled_modules', $modules);
        
        // Create pages for enabled modules
        $created_pages = self::create_module_pages($modules);
        
        wp_send_json_success('Created ' . count($created_pages) . ' pages');
    }

    /**
     * Create pages for enabled modules
     */
    public static function create_module_pages($enabled_modules) {
        $created_pages = array();
        
        foreach ($enabled_modules as $module_key) {
            if (!isset(self::MODULES[$module_key])) {
                continue;
            }
            
            $module = self::MODULES[$module_key];
            
            // Create pages based on module
            switch ($module_key) {
                case 'legislation':
                    $created_pages = array_merge($created_pages, self::create_legislation_pages());
                    break;
                case 'classes':
                    $created_pages = array_merge($created_pages, self::create_classes_pages());
                    break;
                case 'awards':
                    $created_pages = array_merge($created_pages, self::create_endorsement_pages());
                    break;
                case 'courses':
                    $created_pages = array_merge($created_pages, self::create_courses_pages());
                    break;
                case 'group_directory':
                    $created_pages = array_merge($created_pages, self::create_group_directory_pages());
                    break;
            }
        }
        
        return $created_pages;
    }

    /**
     * Create legislation module pages
     */
    private static function create_legislation_pages() {
        $pages = array();
        
        // Bill Tracking Page
        if (!get_page_by_path('bill-tracking', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Bill Tracking',
                'post_content' => '[legislation-list]',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'bill-tracking'
            ));
            if ($page_id) $pages[] = 'bill-tracking';
        }
        
        // Bill Detail Page
        if (!get_page_by_path('engagifii-detail', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Bill Detail',
                'post_content' => "[legislation-details Id='bill-id']",
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'engagifii-detail'
            ));
            if ($page_id) $pages[] = 'engagifii-detail';
        }
        
        // Legislative Database Page
        if (!get_page_by_path('legislative-tracking-database', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Legislative Tracking Database',
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'legislative-tracking-database'
            ));
            if ($page_id) $pages[] = 'legislative-tracking-database';
        }
        
        // Engagifii Grid View Page
        if (!get_page_by_path('engagifii-grid-view', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Engagifii Grid View',
                'post_content' => '<div class="capital-watch-main-contatiner">[legislation-list]</div>',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'engagifii-grid-view'
            ));
            if ($page_id) $pages[] = 'engagifii-grid-view';
        }
        
        return $pages;
    }

    /**
     * Create classes module pages
     */
    private static function create_classes_pages() {
        $pages = array();
        
        // Classes Page
        if (!get_page_by_path('classes', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Classes',
                'post_content' => '[classes-list-calendar-class-name calendarclassname=true]',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'classes'
            ));
            if ($page_id) $pages[] = 'classes';
        }
        
        // Class Details Page
        if (!get_page_by_path('class-details', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Class Details',
                'post_content' => "[class-details Id='class-id']",
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'class-details'
            ));
            if ($page_id) $pages[] = 'class-details';
        }
        
        return $pages;
    }

    /**
     * Create courses module pages
     */
    private static function create_courses_pages() {
        $pages = array();
        
        // Courses Page
        if (!get_page_by_path('courses', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Courses',
                'post_content' => '[courses-list]',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'courses'
            ));
            if ($page_id) $pages[] = 'courses';
        }
        
        // Course Details Page
        if (!get_page_by_path('course-details', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Course Details',
                'post_content' => "[course-details Id='course-id']",
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'course-details'
            ));
            if ($page_id) $pages[] = 'course-details';
        }
        
        return $pages;
    }

    /**
     * Create group directory pages
     */
    private static function create_group_directory_pages() {
        $pages = array();
        
        // My Profile Page with child pages
        if (!get_page_by_path('my-profile', OBJECT, 'page')) {
            $parent_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'My Profile',
                'post_content' => '[user-profile]',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'my-profile'
            ));
            
            if ($parent_id) {
                $pages[] = 'my-profile';
                
                // Create child pages
                $child_pages = array(
                    array('name' => 'my-endorsements', 'title' => 'My Endorsements', 'content' => '[user-endorsements]'),
                    array('name' => 'my-sponsorships', 'title' => 'My Sponsorships', 'content' => '[user-sponsorships]'),
                    array('name' => 'my-committees', 'title' => 'My Committees', 'content' => '[user-committees]'),
                    array('name' => 'my-classes', 'title' => 'My Classes', 'content' => '[user-classes]'),
                    array('name' => 'my-courses', 'title' => 'My Courses', 'content' => '[user-courses]')
                );
                
                foreach ($child_pages as $child) {
                    if (!get_page_by_path($child['name'], OBJECT, 'page')) {
                        $child_id = wp_insert_post(array(
                            'post_type' => 'page',
                            'post_title' => $child['title'],
                            'post_content' => $child['content'],
                            'post_status' => 'publish',
                            'post_author' => 1,
                            'post_name' => $child['name'],
                            'post_parent' => $parent_id
                        ));
                        if ($child_id) $pages[] = $child['name'];
                    }
                }
            }
        }
        
        return $pages;
    }

    /**
     * Create endorsement pages
     */
    private static function create_endorsement_pages() {
        $pages = array();
        
        // Endorsement Grid View Page
        if (!get_page_by_path('endorsement-grid-view', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Endorsement Grid View',
                'post_content' => '<div class="capital-watch-main-contatiner">[endorsement-grid-list]</div>',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'endorsement-grid-view'
            ));
            if ($page_id) $pages[] = 'endorsement-grid-view';
        }
        
        // Endorsement Detail Page
        if (!get_page_by_path('endorsement-detail', OBJECT, 'page')) {
            $page_id = wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => 'Endorsement Detail',
                'post_content' => '<div class="capital-watch-main-contatiner">[endorsement_grid_detail_information]</div>',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => 'endorsement-detail'
            ));
            if ($page_id) $pages[] = 'endorsement-detail';
        }
        
        return $pages;
    }

    /**
     * Check if a module is enabled
     */
    public static function is_module_enabled($module_key) {
        $enabled_modules = get_option('engagifii_enabled_modules', array());
        
        // Ensure $enabled_modules is always an array
        if (!is_array($enabled_modules)) {
            $enabled_modules = array();
        }
        
        return in_array($module_key, $enabled_modules);
    }

    /**
     * Get enabled modules
     */
    public static function get_enabled_modules() {
        $enabled_modules = get_option('engagifii_enabled_modules', array());
        
        // Ensure it's always an array
        if (!is_array($enabled_modules)) {
            $enabled_modules = array();
        }
        
        return $enabled_modules;
    }

    /**
     * Check if module should show in admin settings
     */
    public static function should_show_module_settings($module_key) {
        $enabledModules = self::get_enabled_modules();
        
        // Check if specific module is enabled
        return in_array($module_key, $enabledModules);
    }

    /**
     * Check if any of the specified modules are enabled
     */
    public static function should_show_for_modules($module_keys) {
        if (!is_array($module_keys)) {
            $module_keys = array($module_keys);
        }
        
        $enabledModules = self::get_enabled_modules();
        
        // Check if any of the specified modules are enabled
        foreach ($module_keys as $module_key) {
            if (in_array($module_key, $enabledModules)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle AJAX save module settings
     */
    public function save_module_settings() {
        // Check nonce for security
        check_ajax_referer('engagifii_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $modules = isset($_POST['modules']) ? $_POST['modules'] : array();
        
        // Sanitize the modules array
        $clean_modules = array();
        if (is_array($modules)) {
            foreach ($modules as $module) {
                $clean_modules[] = sanitize_text_field($module);
            }
        }
        
        // Save enabled modules - always return success if we reach here
        update_option('engagifii_enabled_modules', $clean_modules);
        
        wp_send_json_success(array(
            'message' => 'Module settings saved successfully!',
            'modules' => $clean_modules
        ));
    }

    /**
     * Static AJAX handler for save module settings
     */
    public static function handle_save_modules() {
        // Check nonce for security
        check_ajax_referer('engagifii_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $modules = isset($_POST['modules']) ? $_POST['modules'] : array();
        
        // Sanitize the modules array
        $clean_modules = array();
        if (is_array($modules)) {
            foreach ($modules as $module) {
                $clean_modules[] = sanitize_text_field($module);
            }
        }
        
        // Save enabled modules
        update_option('engagifii_enabled_modules', $clean_modules);
        
        wp_send_json_success(array(
            'message' => 'Module settings saved successfully!',
            'modules' => $clean_modules,
            'debug' => array(
                'received_modules' => $modules,
                'clean_modules' => $clean_modules,
                'current_user_can' => current_user_can('manage_options')
            )
        ));
    }
}

// Initialize AJAX handlers
add_action('wp_ajax_engagifii_initial_setup', array('Engagifii_Settings', 'handle_initial_setup'));
add_action('wp_ajax_engagifii_create_pages', array('Engagifii_Settings', 'handle_create_pages'));
add_action('wp_ajax_engagifii_save_modules', array('Engagifii_Settings', 'handle_save_modules'));

// Global helper functions for use in other files
function engagifii_should_show_module_settings($module_key) {
    return Engagifii_Settings::should_show_module_settings($module_key);
}

function engagifii_should_show_for_modules($module_keys) {
    return Engagifii_Settings::should_show_for_modules($module_keys);
}
