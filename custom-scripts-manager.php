<?php

/**
 * Plugin Name: Custom Scripts Manager
 * Description: Add custom CSS and JavaScript to your site with advanced placement options
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Shahid Ifraheem
 * Author URI: https://shahidifraheem.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-scripts-manager
 * Tags: custom css and js, add custom code, add tracking code, add custom css, add custom js
 */

defined('ABSPATH') || exit;

// Define plugin constants
define('CSM_VERSION', '1.0');
define('CSM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CSM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CSM_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once CSM_PLUGIN_DIR . 'includes/helper-functions.php';
require_once CSM_PLUGIN_DIR . 'includes/class-database.php';
require_once CSM_PLUGIN_DIR . 'includes/class-admin-interface.php';
require_once CSM_PLUGIN_DIR . 'includes/class-script-handler.php';

// Load premium features if available
if (file_exists(CSM_PLUGIN_DIR . 'includes/class-premium.php')) {
    require_once CSM_PLUGIN_DIR . 'includes/class-premium.php';
}

// Register activation/deactivation hooks
register_activation_hook(__FILE__, 'csm_activate_plugin');
register_deactivation_hook(__FILE__, ['CSM_Database', 'csm_deactivate']);

// Initialize plugin
add_action('plugins_loaded', 'csm_init_plugin');

/**
 * Plugin activation routine
 */
function csm_activate_plugin()
{
    // Clean output buffers
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    // Initialize database
    $result = CSM_Database::activate();

    if (is_wp_error($result)) {
        // Handle activation error if needed
    }
}

/**
 * Initialize plugin components
 */
function csm_init_plugin()
{
    // Initialize components
    CSM_Database::csm_init();
    CSM_Admin_Interface::csm_init();
    CSM_Script_Handler::csm_init();

    // Initialize premium features if available
    if (class_exists('CSM_Premium')) {
        CSM_Premium::csm_init();
    }
}

