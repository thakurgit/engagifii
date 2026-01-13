<?php
/**
* Admin Settings
* Since v1.0.0
* Author: Engagifii
*/

class ebtAdminConfigSettings {
	
	protected $dbObj;

	public function __construct(){	 
	 
		global $wpdb;
		$this->dbObj=$wpdb;			
		$this->init_hooks();	 	
	}

	private function init_hooks() {		
	add_action('admin_menu',array($this,'ebt_api_add_admin_menu'));			
	add_action('admin_init',array($this,'ebt_api_settings_init'));
		add_action('engagifiiGetColumnList', array($this,'show_datatable_column'));		
		add_action('engagifiiCustomizer', array($this,'engagifii_Customizer'));		
 	add_action('profileSettings', array($this,'profile_Settings'));	
	}

 	// function show_datatable_column()
 	// {
		
 	// 	$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
	// 	include_once( __DIR__.'/view/endorsement/admin-column-list.php' );
    // include_once( __DIR__.'/view/courses/admin-column-list.php' );
    // include_once( __DIR__.'/view/classes/admin-column-list.php' );

	// include_once( __DIR__.'/view/events/admin-column-list.php' );
	// 	include_once( __DIR__.'/view/legislation/admin-column-list.php' );
 	// }
     function show_datatable_column() {
        $tab = $_GET['tab'] ?? null;
        
        // Get enabled modules from our new module system
        $enabledModules = get_option('engagifii_enabled_modules', array());
		$setupCompleted = get_option('engagifii_setup_completed');

        // Ensure $enabledModules is always an array
        if (!is_array($enabledModules)) {
            $enabledModules = array();
        }
        
        // If specific modules are enabled, include their views
        $views = array();
        
        if (!$setupCompleted || in_array('awards', $enabledModules)) {
            $views[] = 'endorsement/admin-column-list.php';
        }
        
        if (!$setupCompleted || in_array('courses', $enabledModules)) {
            $views[] = 'courses/admin-column-list.php';
        }
        
        if (!$setupCompleted || in_array('classes', $enabledModules)) {
            $views[] = 'classes/admin-column-list.php';
        }
        
        if (!$setupCompleted || in_array('events', $enabledModules)) {
            $views[] = 'events/admin-column-list.php';
            $views[] = 'trainingcalendar/admin-column-list.php';
        }
        
        if (!$setupCompleted || in_array('legislation', $enabledModules)) {
            $views[] = 'legislation/admin-column-list.php';
        }
        
        if (!$setupCompleted || in_array('group_directory', $enabledModules)) {
            $views[] = 'groupmembers/admin-column-list.php';
        }
        
        if (!$setupCompleted || in_array('organization_directory', $enabledModules)) {
            $views[] = 'organizations/admin-column-list.php';
        }
        
        // Include the views
        if (!empty($views)) {
            foreach ($views as $view) {
                if (file_exists(__DIR__ . '/view/' . $view)) {
                    include_once(__DIR__ . '/view/' . $view);
                }
            }
        }
    }
      
	function engagifii_Customizer()
 	{
		
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		include_once( __DIR__.'/view/customizer.php' );
 	}
	function profile_Settings()
 	{
 		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		
		// Get API settings to check tenant code
		$options = get_option('ebt_api_settings');
		$dashboard_tenant_code = $options['dashboard_tenant_code'] ?? '';
		
		// Only show profile settings if tenant code is 'psba'
		if ($dashboard_tenant_code !== 'psba') {
		    return;
		}
		
		// Get enabled modules from our new module system
		$enabledModules = get_option('engagifii_enabled_modules', array());
		$setupCompleted = get_option('engagifii_setup_completed');
		
		// Ensure $enabledModules is always an array
		if (!is_array($enabledModules)) {
		    $enabledModules = array();
		}
		
		// Show dashboard settings if group_directory module is enabled
		if (!$setupCompleted || in_array('group_directory', $enabledModules)) {
		    include_once( __DIR__.'/view/dashboard-settings.php' );
		}
 	}
	function ebt_api_add_admin_menu() {
		// Get API settings to check tenant code
		$options = get_option('ebt_api_settings');
		$dashboard_tenant_code = $options['dashboard_tenant_code'] ?? '';
		
		// Get enabled modules from our new module system
		$enabledModules = get_option('engagifii_enabled_modules', array());
				$setupCompleted = get_option('engagifii_setup_completed');

		
		// Ensure $enabledModules is always an array
		if (!is_array($enabledModules)) {
		    $enabledModules = array();
		}
		
		// Main menu page - always show
		add_menu_page( 'Engagifii', 'Engagifii', 'manage_options', 'engagifii-module-api', array($this,'engagifii_settings_api_view'),plugins_url('engagifii/assets/images/logo-icon.png'), 4 );
		$parent = site_url().'/wp-admin/admin.php?page=engagifii-module-api';
		
		// API settings - always show
		add_submenu_page( 'engagifii-module-api', 'API settings', 'API settings', 'manage_options', $parent.'&tab=settings',  $callback = '');
		
		// Shortcodes - only show if relevant modules are enabled
		if (!$setupCompleted || in_array('legislation', $enabledModules) || 
		    in_array('classes', $enabledModules) || 
		    in_array('courses', $enabledModules) || 
		    in_array('events', $enabledModules) || 
		    in_array('awards', $enabledModules) ||
		    in_array('group_directory', $enabledModules) ||
		    in_array('organization_directory', $enabledModules)) {
			add_submenu_page( 'engagifii-module-api', 'Shortcodes', 'Shortcodes', 'manage_options', $parent.'&tab=shortcode',  $callback = '');
		}
		
		// Page Settings - only show if page-creating modules are enabled
		if (!$setupCompleted || in_array('legislation', $enabledModules) || 
		    in_array('classes', $enabledModules) || 
		    in_array('courses', $enabledModules) || 
		    in_array('awards', $enabledModules) ||
		    in_array('group_directory', $enabledModules)) {
			add_submenu_page( 'engagifii-module-api', 'Page Settings', 'Page Settings', 'manage_options', $parent.'&tab=page-settings',  $callback = '');
		}
		
		// Profile Settings - only show if group directory module is enabled AND tenant code is 'psba'
		if ($dashboard_tenant_code === 'psba') {
			add_submenu_page( 'engagifii-module-api', 'Profile Settings', 'Profile Settings', 'manage_options', $parent.'&tab=dashboard-settings',  $callback = '');
		}
	}
	
    function ebt_api_settings_init() {
        if (!current_user_can('manage_options')) {
          return;
        }
      
        register_setting('engagifiiPlugin', 'ebt_api_settings');
        register_setting('engagifiiModules', 'engagifii_modules');
      
        add_settings_section(
          'ebt_api_ebtPlugin_section',
          '', // Remove old header - now handled by engagifii-admin-header.php
          array($this, 'ebt_api_settings_section_callback'),
          'engagifiiPlugin'
        );
      }
      



/* List Page */

function ebt_api_shortocde_description() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Get enabled modules
    $enabledModules = get_option('engagifii_enabled_modules', array());
	$setupCompleted = get_option('engagifii_setup_completed');

    
    // Ensure $enabledModules is always an array
    if (!is_array($enabledModules)) {
        $enabledModules = array();
    }
    
    $shortcodes = array();
    
    // Classes shortcodes - only if classes module is enabled
    if (!$setupCompleted || in_array('classes', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Class Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Class List',
                    'shortcode'   => '[classes-list]'
                ),
                array(
                    'name'        => 'Class Details',
                    'shortcode'   => '[class-details Id=\'class-id\']'
                ),
                array(
                    'name'        => 'Class Calendar',
                    'shortcode'   => '[class-calendar]'
                ),
                array(
                    'name'        => 'Class List & Calendar',
                    'shortcode'   => '[classes-list-calendar calendar=true]'
                ),
                array(
                    'name'        => 'Class List & Calendar (With Class Name)',
                    'shortcode'   => '[classes-list-calendar-class-name calendarclassname=true]'
                ),
                array(
                    'name'        => 'Class Calendar (With Class Name)',
                    'shortcode'   => '[class-calendar-class-name]'
                )
            )
        );
    }
    
    // Legislation shortcodes - only if legislation module is enabled
    if (!$setupCompleted || in_array('legislation', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Legislation Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Legislation List',
                    'shortcode'   => '[legislation-list]'
                ),
                array(
                    'name'        => 'Legislation Details',
                    'shortcode'   => '[legislation-details Id=\'bill-id\']'
                ),
                array(
                    'name'        => 'Last Action Type',
                    'shortcode'   => '[legislation-lastaction]'
                ),
                array(
                    'name'        => 'Tracking Levels',
                    'shortcode'   => '[legislation-tracking]'
                ),
                array(
                    'name'        => 'Staff Members',
                    'shortcode'   => '[legislation-staffmember]'
                ),
                array(
                    'name'        => 'Bill Tags',
                    'shortcode'   => '[legislation-tags]'
                ),
                array(
                    'name'        => 'Search By Bill Number',
                    'shortcode'   => '[legislation-search-billnumber]'
                ),
                array(
                    'name'        => 'Total bills count',
                    'shortcode'   => '[bill-count]'
                ),
                array(
                    'name'        => 'Legislative Reports',
                    'shortcode'   => '[legislative-reports]'
                )
            )
        );
    }
    
    // Events shortcodes - only if events module is enabled
    if (!$setupCompleted || in_array('events', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Event Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Event List',
                    'shortcode'   => '[event-list]'
                ),
                array(
                    'name'        => 'Event Detail',
                    'shortcode'   => '[events-details Id=\'event-id\']'
                ),
                array(
                    'name'        => 'Event Calendar',
                    'shortcode'   => '[events-calendar]'
                ),
                array(
                    'name'        => 'Event List & Calendar',
                    'shortcode'   => '[events-list-calendar calendar=true]'
                )
            )
        );
    }
    
    // Courses shortcodes - only if courses module is enabled
    if (!$setupCompleted || in_array('courses', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Courses Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Course List',
                    'shortcode'   => '[courses-list]'
                ),
                array(
                    'name'        => 'Course Details',
                    'shortcode'   => '[course-details Id=\'course-id\']'
                )
            )
        );
    }
    
    // Awards shortcodes - only if awards module is enabled
    if (!$setupCompleted || in_array('awards', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Awards Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Awards List',
                    'shortcode'   => '[endorsement-grid-list]'
                ),
                array(
                    'name'        => 'Awards Detail',
                    'shortcode'   => '[endorsement-details Id=\'endorsement-id\']'
                )
            )
        );
    }
    
    // Group Directory shortcodes - only if group_directory module is enabled
    if (!$setupCompleted || in_array('group_directory', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Group Member Directory Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Group Members List View',
                    'shortcode'   => '[group-members-list id="your_group_id" viewMode="list"]'
                ),
                array(
                    'name'        => 'Group Members Grid View',
                    'shortcode'   => '[group-members-list id="your_group_id" viewMode="Grid"]'
                ),
                array(
                    'name'        => 'Group Members List & Grid View',
                    'shortcode'   => '[group-members-list id="your_group_id" viewMode="both"] OR [group_members_list id="your_group_id"]'
                )
            )
        );
    }
    
    // My Engagifii Dashboard - only if tenant code is 'psba'
    $options = get_option('ebt_api_settings');
    $dashboard_tenant_code = $options['dashboard_tenant_code'] ?? '';
    if ($dashboard_tenant_code === 'psba') {
        $shortcodes[] = array(
            'title' => 'My Engagifii Dashboard',
            'list'  => array(
                array(
                    'name'        => 'My Profile',
                    'shortcode'   => '[engagifii-profile]'
                ),
                array(
                    'name'        => 'My profile edit',
                    'shortcode'   => '[engagifii-profile-edit]'
                ),
                array(
                    'name'        => 'My Events',
                    'shortcode'   => '[engagifii-myEvents]'
                ),
                array(
                    'name'        => 'My Classes',
                    'shortcode'   => '[engagifii-myClasses]'
                ),
                array(
                    'name'        => 'My Transcript',
                    'shortcode'   => '[engagifii-myTranscript]'
                ),
                array(
                    'name'        => 'My Transactions',
                    'shortcode'   => '[engagifii-myTransactions]'
                )
            )
        );
    }
    
    // Organization Directory shortcodes - only if organization_directory module is enabled
    if (!$setupCompleted || in_array('organization_directory', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Organization Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Organization List View',
                    'shortcode'   => '[get-organization viewmode="list"]'
                ),
                array(
                    'name'        => 'Organization Grid View',
                    'shortcode'   => '[get-organization viewmode="grid"]'
                ),
                array(
                    'name'        => 'Organization List & Grid View',
                    'shortcode'   => '[get-organization viewmode="both"] OR [get-organization]'
                )
            )
        );
    }
    
    // Public Officials shortcodes - part of legislation module
    if (!$setupCompleted || in_array('legislation', $enabledModules)) {
        $shortcodes[] = array(
            'title' => 'Public Official Shortcodes',
            'list'  => array(
                array(
                    'name'        => 'Public Official List',
                    'shortcode'   => '[public-officials]'
                ),
                array(
                    'name'        => 'Public Official Details',
                    'shortcode'   => '[public-officials-detail]'
                )
            )
        );
    }

    // Add styling for shortcode cards to match module settings
    echo '<div class="engagifii-content-wrapper shortcode-cards">';
    
    echo '<style>
        .engagifii-settings-container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
        
        .engagifii-settings-wrap {
            background: #f8fafc;
            min-height: calc(100vh - 160px);
            padding: 0;
        }
        
        .engagifii-settings-page {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 20px;
            overflow: hidden;
        }
        
        .engagifii-content-area {
            padding: 40px;
        }
        
        .engagifii-modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin: 0 0 40px 0;
        }
        
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
        
        .module-header {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
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
            color: #2271b1;
        }
        
        .module-title-section {
            flex: 1;
            padding: 0 15px;
        }
        
        .module-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.3;
            word-wrap: break-word;
        }
        
        .module-content {
            padding: 10px 20px 20px 20px;
        }
        
        .shortcode-item {
            margin-bottom: 15px;
            padding: 12px;
            background: #f7fafc;
            border-radius: 8px;
            border-left: 4px solid #2271b1;
        }
        
        .shortcode-item:last-child {
            margin-bottom: 0;
        }
        
        .shortcode-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 6px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .shortcode-name .dashicons {
            color: #2271b1;
            font-size: 16px;
        }
        
        .shortcode-count {
            color: #718096;
            font-size: 12px;
            font-weight: 400;
            margin-top: 4px;
        }
        
        .shortcode-code {
            font-family: "Monaco", "Consolas", "Courier New", monospace;
            position: relative;
        }
        
        .shortcode-code code {
            background: #e2e8f0;
            color: #4a5568;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            display: block;
            font-weight: 500;
            word-break: break-all;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        
        .shortcode-code code:hover {
            background: #cbd5e0;
            border-color: #2271b1;
            transform: translateY(-1px);
        }
        
        .copy-hint {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #2d3748;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            white-space: nowrap;
        }
        
        .shortcode-code:hover .copy-hint {
            opacity: 1;
        }
        
        .copy-hint::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #2d3748;
        }
        
        .engagifii-actions {
            background: #f8fafc;
            padding: 40px;
            border-top: 1px solid #e2e8f0;
            margin: 0 -40px -40px -40px;
        }
        
        .actions-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .actions-container h3 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 25px;
        }
        
        .setup-steps {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        
        .step-number {
            background: linear-gradient(135deg, #2271b1 0%, #2271b1 100%);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .step-text {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .step-text a {
            color: #2271b1;
            text-decoration: none;
            font-weight: 500;
        }
        
        .step-text a:hover {
            text-decoration: underline;
        }
    </style>';
    
    // Add JavaScript for copy functionality
    echo '<script>
        function copyToClipboard(element) {
            const text = element.textContent;
            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                const originalHint = element.nextElementSibling;
                const originalText = originalHint.textContent;
                originalHint.textContent = "Copied!";
                originalHint.style.opacity = "1";
                
                // Reset after 2 seconds
                setTimeout(function() {
                    originalHint.textContent = originalText;
                    originalHint.style.opacity = "";
                }, 2000);
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand("copy");
                    const originalHint = element.nextElementSibling;
                    const originalText = originalHint.textContent;
                    originalHint.textContent = "Copied!";
                    originalHint.style.opacity = "1";
                    
                    setTimeout(function() {
                        originalHint.textContent = originalText;
                        originalHint.style.opacity = "";
                    }, 2000);
                } catch (err) {
                    console.error("Could not copy text: ", err);
                }
                document.body.removeChild(textArea);
            });
        }
    </script>';

    if (empty($shortcodes)) {
        echo '<div class="notice notice-warning">';
        echo '<p><strong>No shortcodes available.</strong></p>';
        echo '<p>Please go to <a href="' . admin_url('admin.php?page=engagifii-settings') . '">Engagifii Settings</a> to enable the modules you want to use, then return here to view available shortcodes.</p>';
        echo '</div>';
    } else {
        // Add the grid layout for shortcode cards
        echo '<div class="engagifii-modules-grid">';
        
        foreach ($shortcodes as $shortcode) {
            // Determine icon based on shortcode type
            $icon_class = 'dashicons-shortcode'; // Default
            if (strpos($shortcode['title'], 'Class') !== false) {
                $icon_class = 'dashicons-welcome-learn-more';
            } elseif (strpos($shortcode['title'], 'Legislation') !== false) {
                $icon_class = 'dashicons-book';
            } elseif (strpos($shortcode['title'], 'Event') !== false) {
                $icon_class = 'dashicons-calendar-alt';
            } elseif (strpos($shortcode['title'], 'Award') !== false || strpos($shortcode['title'], 'Endorsement') !== false) {
                $icon_class = 'dashicons-awards';
            } elseif (strpos($shortcode['title'], 'Course') !== false) {
                $icon_class = 'dashicons-welcome-learn-more';
            } elseif (strpos($shortcode['title'], 'Group') !== false || strpos($shortcode['title'], 'Organization') !== false) {
                $icon_class = 'dashicons-groups';
            } elseif (strpos($shortcode['title'], 'Public Official') !== false) {
                $icon_class = 'dashicons-businessman';
            }
            
            echo '<div class="engagifii-module-card active">'; // Using active class for consistent green styling
            echo '<div class="module-header">';
            
            // Module icon section
            echo '<div class="module-icon">';
            echo '<span class="dashicons ' . $icon_class . '"></span>';
            echo '</div>';
            
            // Module title section  
            echo '<div class="module-title-section">';
            echo '<h3 class="module-title">' . esc_html($shortcode['title']) . '</h3>';
            echo '<div class="shortcode-count">' . count($shortcode['list']) . ' shortcode' . (count($shortcode['list']) !== 1 ? 's' : '') . ' available</div>';
            echo '</div>';
            
            echo '</div>'; // End module-header
            
            // Module content
            echo '<div class="module-content">';
            echo '<div class="module-shortcodes">';
            
            foreach ($shortcode['list'] as $item) {
                echo '<div class="shortcode-item">';
                echo '<div class="shortcode-name">';
                echo '<span class="dashicons dashicons-media-code"></span>';
                echo esc_html($item['name']);
                echo '</div>';
                echo '<div class="shortcode-code">';
                echo '<code onclick="copyToClipboard(this)">' . esc_html($item['shortcode']) . '</code>';
                echo '<span class="copy-hint">Click to copy</span>';
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>'; // End module-shortcodes
            echo '</div>'; // End module-content
            echo '</div>'; // End engagifii-module-card
        }
        
        echo '</div>'; // End engagifii-modules-grid
        
        // Add instructions section
        echo '<div class="shortcode-instructions">';
        echo '<h3 class="instructions-title">';
        echo '<span class="dashicons dashicons-info"></span>';
        echo 'How to Use Shortcodes';
        echo '</h3>';
        echo '<div class="setup-steps">';
        echo '<div class="step-item">';
        echo '<span class="step-number">1</span>';
        echo '<span class="step-text">Create a <a href="' . admin_url('post-new.php?post_type=page') . '" target="_blank">page</a> or <a href="' . admin_url('post-new.php') . '" target="_blank">post</a></span>';
        echo '</div>';
        echo '<div class="step-item">';
        echo '<span class="step-number">2</span>';
        echo '<span class="step-text">Copy the shortcode you want to use from the cards above</span>';
        echo '</div>';
        echo '<div class="step-item">';
        echo '<span class="step-number">3</span>';
        echo '<span class="step-text">Paste the shortcode in your page/post content where you want the data to display</span>';
        echo '</div>';
        echo '</div>'; // End setup-steps
        echo '</div>'; // End shortcode-instructions
    }

    echo '</div>'; // End engagifii-content-wrapper shortcode-cards
}

function ebt_api_url_setings(){

	
}

/*function engagifii_font_family_render(){
	$options = get_option( 'ebt_api_settings_s2' );
	include( plugin_dir_path( __FILE__ ) . '/admin-fonts.php');
	

	if ( isset( $options['engagifii_font_family'])){
        $current = $options['engagifii_font_family'];
	}
    else{
        $current = '';
    }
?>
	<select name="ebt_api_settings[engagifii_font_family]">
		<option> </option>
	   <?php foreach( $fonts as $key => $font ):?>

	     <option <?php if($key == $current) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
	    <?php endforeach; ?>
	</select>


<?php    
}

function engagifii_apply_css_ebt_render($html_class){
	$options = get_option( 'ebt_api_settings' );
  if(isset($options['engagifii_apply_css_ebt'])){
    $engagifii_apply_css_ebt = $options['engagifii_apply_css_ebt'];
   }else{
   	$engagifii_apply_css_ebt = 0;
   }

    $checkedHtml = '';
    if($engagifii_apply_css_ebt==1)
    {
    	 $checkedHtml  = ' checked="checked"';
    }


    $_inputHtml = '<div class="engagifii-content-wrapper bg-grey bordered '.$html_class.'"><input type="checkbox" id="engagii_custom_css" name="ebt_api_settings[engagifii_apply_css_ebt]" value="1" '.$checkedHtml.' >';
    $_inputHtml.= '<span> <strong>Enable Customize CSS</strong></span></div>';
    echo $_inputHtml;
}*/


function ebt_api_url_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    if(isset($options['ebt_api_url']))
      $ebt_api_url = $options['ebt_api_url'];
    else
      $ebt_api_url ='';
    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_api_url]" class="postbox tnaUrl" value="'.$ebt_api_url.'">';
      echo $_inputHtml;
}

function ebt_tenant_code_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    $_tenantCode = '';
    $code = '';
    $engagifii_url = '';
    if(isset( $options['ebt_tenant_code']))
    {
      $_tenantCode = $options['ebt_tenant_code'];
      $code = $_tenantCode['tenant_code'];
      $engagifii_url = $_tenantCode['engagifii_url'];
    }
    

    $_inputHtmlHidden ='<input type="hidden" name="ebt_api_settings[ebt_tenant_code][tenant_code]" class="postbox" id="ebt_tenant_code_text" value="'.$code.'">';

    $_inputHtml = '<input type="text" name="ebt_api_settings[ebt_tenant_code][engagifii_url]" class="postbox tenantCode" value="'.$engagifii_url.'"  oninput="getTenantCode(this.value, this)" >&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$code.'</span>';

      echo $_inputHtml.$_inputHtmlHidden;
}

 

	function ebt_api_settings_section_callback( ) {
  		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
	$options = get_option( 'ebt_api_settings' );
	$dashboard_tenant_code= $options['dashboard_tenant_code'] ?? '';
    	?>
		<!-- Our admin page content should all be inside .wrap -->
  		<div class="wrap">
  		<style>
  		/* Style tabs to match Module Settings design */
  		.nav-tab-wrapper {
  		    background: white;
  		    border-radius: 12px;
  		    padding: 10px;
  		    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  		    margin-bottom: 10px;
  		    border: none;
  		}
  		
  		.nav-tab {
  		    border: 2px solid #e2e8f0 !important;
  		    border-radius: 8px !important;
  		    margin-right: 10px !important;
  		    padding: 12px 20px !important;
  		    background: #f8fafc !important;
  		    color: #4a5568 !important;
  		    font-weight: 500 !important;
  		    text-decoration: none !important;
  		    transition: all 0.3s ease !important;
  		    border-bottom: 2px solid #e2e8f0 !important;
  		    display: inline-flex !important;
  		    align-items: center !important;
  		    gap: 8px !important;
  		}
  		
  		.nav-tab:hover {
  		    border-color: #2271b1 !important;
  		    background: #edf2f7 !important;
  		    color: #2d3748 !important;
  		    transform: translateY(-2px) !important;
  		    box-shadow: 0 4px 12px rgba(34, 113, 177, 0.15) !important;
  		}
  		
  		.nav-tab-active,
  		.nav-tab-active:hover,
  		.nav-tab.nav-tab-active,
  		.nav-tab.nav-tab-active:hover,
  		.nav-tab-wrapper .nav-tab.nav-tab-active,
  		.nav-tab-wrapper .nav-tab.nav-tab-active:hover {
  		    background: #2271b1 !important;
  		    color: white !important;
  		    border-color: #2271b1 !important;
  		    box-shadow: 0 4px 12px rgba(34, 113, 177, 0.25) !important;
  		}
  		
  		/* Force active tab styling - highest specificity */
  		body.wp-admin .nav-tab-wrapper .nav-tab.nav-tab-active {
  		    background: #2271b1 !important;
  		    color: white !important;
  		    border-color: #2271b1 !important;
  		}
  		
  		/* Content sections styling - apply to actual content containers */
  		.engagifii-setting, .shortcode-section, .engagifii-content-wrapper {
  		    background: white;
  		    border-radius: 12px;
  		    padding: 30px;
  		    margin: 20px;
  		}
  		
  		/* Remove extra spacing from wrapper */
  		.wrap {
  		    margin-top: 0;
            padding-left: 20px;
            padding-right: 20px;
  		}
  		
  		/* Unified content wrapper - consistent padding and spacing for all tabs */
  		.engagifii-content-wrapper {
  		    margin: 20px !important;
  		    padding: 30px !important;
  		    background: white !important;
  		    border-radius: 12px !important;
  		    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
  		}
  		</style>
    		<!-- Here are our tabs -->
    		<nav class="nav-tab-wrapper wp-clearfix">
      			<a href="?page=engagifii-module-api" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">
      			    <span class="dashicons dashicons-art" style="font-size: 16px;"></span>
      			    Customize CSS
      			</a>
      			<a href="?page=engagifii-module-api&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">
      			    <span class="dashicons dashicons-admin-settings" style="font-size: 16px;"></span>
      			    API Settings
      			</a>
                <a href="?page=engagifii-module-api&tab=page-settings" class="nav-tab <?php if($tab==='page-settings'):?>nav-tab-active<?php endif; ?>">
                    <span class="dashicons dashicons-admin-page" style="font-size: 16px;"></span>
                    Page Settings
                </a>
               <?php if ($dashboard_tenant_code == 'psba') { ?> 
               <a href="?page=engagifii-module-api&tab=dashboard-settings" class="nav-tab <?php if($tab==='dashboard-settings'):?>nav-tab-active<?php endif; ?>">
                   <span class="dashicons dashicons-admin-users" style="font-size: 16px;"></span>
                   Profile Settings
               </a>
               <?php } ?>
      			<a href="?page=engagifii-module-api&tab=shortcode" class="nav-tab <?php if($tab==='shortcode'):?>nav-tab-active<?php endif; ?>">
      			    <span class="dashicons dashicons-shortcode" style="font-size: 16px;"></span>
      			    Shortcode Usage
      			</a>
    		</nav>

    		<?php switch($tab) :
      		case 'settings':
      			$this->engagifii_api_settings('show');
      			//$this->engagifii_apply_css_ebt_render('hide');
        	break;
      		case 'shortcode':
        		$this->ebt_api_shortocde_description();
        	break;
			case 'page-settings':
        		$this->engagifii_api_settings('hide');
        		//$this->engagifii_apply_css_ebt_render('hide');
        	break;
			case 'profile-settings':
        		$this->engagifii_api_settings('hide');
        		//$this->engagifii_apply_css_ebt_render('hide');
        	break;
      		default:
      			$this->engagifii_api_settings('hide');
        		//$this->engagifii_apply_css_ebt_render('show');

        	break;
    		endswitch; ?>
  		</div>
        <!--API Urls accordion ends--> 
    <?php
	}

	function lbt_api_url_render(  ) {
    $options = get_option( 'ebt_api_settings' );
    $lbt_api_url = '';
    if(isset($options['lbt_api_url']))
      $lbt_api_url = $options['lbt_api_url']; 
    $_inputHtml = '<input type="text"  class="postbox legisUrl"  name="ebt_api_settings[lbt_api_url]" value="'.$lbt_api_url.'">';
      echo $_inputHtml;
	}

	//API url for Events
	function evt_api_url_render(  ) {
		$options = get_option( 'ebt_api_settings' );
		$evt_api_url = '';
		if(isset($options['evt_api_url']))
		  $evt_api_url = $options['evt_api_url']; 
		$_inputHtml = '<input type="text"  class="postbox eventUrl"  name="ebt_api_settings[evt_api_url]" value="'.$evt_api_url.'">';
		  echo $_inputHtml;
		}
	//


	function engagifii_api_settings($html_class)
	{
		?>
			<div class="<?php echo $html_class; ?>" data-tab="settings">
			<h3 class="mb-0 bg-grey bordered d-flex justify-content-between accordion-btn">API URLs <i class="dashicons-before dashicons-arrow-down-alt2"></i></h3>
            <div class="engagifii-setting api-urls accordion-content" style="display:none;">
            <?php
			$options = get_option( 'ebt_api_settings' );
			$engagifii_apis = $options['engagifii_apis']?? [];
			$engagifii_apis['tenant']=$engagifii_apis['tenant']??'';
			$update_manually = $engagifii_apis['update_manually'] ?? null;
    		$update_manually_setting = ($update_manually == 1) ? 'checked' : '';
			?>
            	<div style=" position:relative; ">
                <div <?php //if($update_manually == 1){ echo 'style="display:none"'; } ?>>
                  <div class="form-group">
                          <label style="width: 150px;">Select Environment</label>
                          <select name="ebt_api_settings[engagifii_apis][environment]" class="select-env <?php if(!$options) { echo 'nullenv'; } ?>" >
                               <?php
                                  $envs = [
                                    '' => 'Production',
                                    '-qa' => 'QA',
                                    '-support' => 'Support',
                                    '-hotfix' => 'Hotfix',
                                    '-preview2' => 'Preview2',
                                    '-preview3' => 'Preview3',
                                    '-preview4' => 'Preview4',
                                    '-preview5' => 'Preview5',
                                    '-preview6' => 'Preview6',
                                    '-preview9' => 'Preview9',
                                    '-preview10' => 'Preview10',
                                    '-staging' => 'Staging'
                                  ];
                                
                                  foreach ($envs as $value => $label) {
                                    $selected = $engagifii_apis['environment'] === $value ? ' selected' : '';
                                    echo "<option value='$value'$selected>$label</option>";
                                  }
                              ?>
                          </select>
                          <span class="env-loading" style="display:none"><img style="max-width:100%" src="<?php echo ENGAGIFII_ASSETS_URL.'/images/loader.gif';?>" alt=""></span><span class="env-loading-msg"></span>
                          <?php
                              $apiSettings = ['crmUrl','reportUrl','revenueUrl','doUrl', 'authUrl','tnaUrl','eventUrl','legisUrl','resourceUrl'];
                              foreach ($apiSettings as $settingName) {
                                  $value = htmlspecialchars($engagifii_apis[$settingName], ENT_QUOTES, 'UTF-8');
                                  $inputField = "<input name='ebt_api_settings[engagifii_apis][$settingName]' class='$settingName' type='hidden' value='$value' />";
                                  echo $inputField;
                              }
                              ?>
                      </div>
                  <div class="form-group" >
                          <label style="width: 142px;">Tenant Code</label>
                         <input oninput="getTenantCode(this.value, this)" type="text" name="ebt_api_settings[engagifii_apis][tenant]" class="postbox tenantInput" value="<?php echo $engagifii_apis['tenant'];?>">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview"><?php echo $engagifii_apis['tenant'];?></span><input type="hidden"  class="postbox"  name="ebt_api_settings[engagifii_apis][tenant]" id="" value="<?php echo $engagifii_apis['tenant'];?>" required>
                      </div>
                 </div>
                 <div class="form-check form-switch" style="position:absolute; top:0; right:0">
                  <input class="form-check-input" type="checkbox" name="ebt_api_settings[engagifii_apis][update_manually]" id="update_manually" value="1" <?php echo $update_manually_setting; ?>> 
                   <label style="float:none" for="update_manually" class="form-check-label"><strong>update APIs separately</strong></label>
                </div>
               </div>
               <div class="apiUrls" <?php if($update_manually != 1){ echo 'style="display:none"'; } ?>>
               <hr>
            	<h4>Training & Accreditation API Settings</h4>
                <div class="form-group">
      					<label>API URL</label>
  						<?php $this->ebt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Tenant Code</label>
  						<?php $this->ebt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Legislation API Settings</h4>
                    <div class="form-group">
      					<label>API URL</label>
  						<?php $this->lbt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Tenant code</label>
  						<?php $this->lbt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Events API Settings</h4>
                    <div class="form-group">
      					<label>API URL</label>
  						<?php $this->evt_api_url_render(); ?>
  					</div>
  					<div class="form-group">
  						<label>Tenant code</label>
  						<?php $this->evt_tenant_code_render(); ?>
  					</div>
                    <hr>
                    <h4>Dashboard API Settings</h4>
  					<div class="form-group">
  						<label>Tenant code</label>
  						<?php echo '<input oninput="getTenantCode(this.value, this)" type="text" name="ebt_api_settings[dashboard_tenant_code]" class="postbox tenantCode" value="'.$options['dashboard_tenant_code'].'">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="ebt_tenantcode_preview">'.$options['dashboard_tenant_code'].'</span><input type="hidden"  class="postbox"  name="ebt_api_settings[dashboard_tenant_code]" id="" value="'.$options['dashboard_tenant_code'].'" required>'; ?>
  					</div>
                </div>
            </div>
            
<?php $enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && count($enabled_modules)<1) {
echo '<div class="notice notice-warning">';
        echo '<p><strong>No Modules available.</strong></p>';
        echo '<p>Please go to <a href="' . admin_url('admin.php?page=engagifii-settings') . '">Engagifii Settings</a> to enable the modules.</p>';
        echo '</div>';
}
?>
  			</div>
		<?php
	}

	function lbt_tenant_code_render(  ) {
    	$options = get_option( 'ebt_api_settings' );
      $_tenantCode = '';
      $code = '';
      $engagifii_url = '';
      if(isset($options['lbt_tenant_code'])){
          $_tenantCode = $options['lbt_tenant_code'];
          $code = $_tenantCode['tenant_code'];
          $engagifii_url = $_tenantCode['engagifii_url'];
      }
    	
    	$_inputHtmlHidden ='<input type="hidden"  class="postbox"  name="ebt_api_settings[lbt_tenant_code][tenant_code]" id="lbt_tenant_code_text" value="'.$code.'">';
    	$_inputHtml = '<input type="text"  class="postbox tenantCode"  name="ebt_api_settings[lbt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" oninput="getTenantCode(this.value, this)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="lbt_tenantcode_preview">'.$code.'</span>';
      	echo $_inputHtml.$_inputHtmlHidden;
	}


	//Events tenant code
	function evt_tenant_code_render(  ) {
    	$options = get_option( 'ebt_api_settings' );
      $_tenantCode = '';
      $code = '';
      $engagifii_url = '';
      if(isset($options['evt_tenant_code'])){
          $_tenantCode = $options['evt_tenant_code'];
          $code = $_tenantCode['tenant_code'];
          $engagifii_url = $_tenantCode['engagifii_url'];
      }
    	
    	$_inputHtmlHidden ='<input type="hidden"  class="postbox"  name="ebt_api_settings[evt_tenant_code][tenant_code]" id="evt_tenant_code_text" value="'.$code.'">';
    	$_inputHtml = '<input type="text"  class="postbox tenantCode"  name="ebt_api_settings[evt_tenant_code][engagifii_url]" value="'.$engagifii_url.'" oninput="getTenantCode(this.value, this)">&nbsp;&nbsp;<strong>Tenant Code:</strong><span id="evt_tenantcode_preview">'.$code.'</span>';
      	echo $_inputHtml.$_inputHtmlHidden;
	}

	//



	function engagifii_settings_api_view(  ) {	
		if ( ! current_user_can( 'manage_options' ) ) {
    		return;
  		}
		
		include_once( __DIR__.'/view/admin-settings-form.php' );
	}
	// function engagifii_modules(){
	// 	if ( ! current_user_can( 'manage_options' ) ) {
    // 		return;
  	// 	}
		
	// 	include_once( __DIR__.'/view/engagifii_modules.php' );
	// }

}

new ebtAdminConfigSettings();
