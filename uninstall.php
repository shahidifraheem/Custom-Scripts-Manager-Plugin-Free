<?php

/**
 * Uninstall handler for Custom Scripts Manager
 * 
 * @package Custom Scripts Manager
 */

// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load plugin constants
$plugin_path = plugin_dir_path(__FILE__);
if (file_exists($plugin_path . 'custom-scripts-manager.php')) {
    require_once $plugin_path . 'custom-scripts-manager.php';
}

/**
 * Handle plugin uninstallation
 */
function csm_uninstall_plugin()
{
    // Check if we should delete data
    $delete_data = false;

    // If in admin and this is a real uninstall request
    if (is_admin() && isset($_REQUEST['action']) && $_REQUEST['action'] === 'delete-selected') {
        // Check if our plugin is in the list of plugins to delete
        $plugins = isset($_REQUEST['checked']) ? (array) $_REQUEST['checked'] : array();
        if (in_array(plugin_basename(__FILE__), $plugins)) {
            // Show confirmation dialog
            if (!isset($_REQUEST['csm_confirm_delete'])) {
                wp_admin_notice(
                    '<p><strong>' . esc_html__('Custom Scripts Manager', 'custom-scripts-manager') . '</strong></p>' .
                        '<p>' . esc_html__('Do you also want to delete all plugin data (scripts, settings)? This cannot be undone.', 'custom-scripts-manager') . '</p>' .
                        '<form method="post" action="' . esc_url(admin_url('plugins.php?action=delete-selected&checked[]=' . urlencode(plugin_basename(__FILE__)))) . '">' .
                        '<input type="hidden" name="csm_confirm_delete" value="1">' .
                        '<button type="submit" class="button button-primary" name="csm_delete_data" value="1">' . esc_html__('Yes, delete all data', 'custom-scripts-manager') . '</button>' .
                        '<button type="submit" class="button" name="csm_delete_data" value="0">' . esc_html__('No, keep my data', 'custom-scripts-manager') . '</button>' .
                        wp_nonce_field('bulk-plugins', '_wpnonce', true, false) .
                        '</form>',
                    [
                        'type' => 'warning',
                        'additional_classes' => ['notice-alt'],
                        'paragraph_wrap' => false,
                    ]
                );
                exit;
            }

            // Process their choice
            if (isset($_REQUEST['csm_delete_data'])) {
                $delete_data = $_REQUEST['csm_delete_data'] === '1';
            }
        }
    }

    // If we should delete data
    if ($delete_data) {
        global $wpdb;

        // Delete database table
        $table_name = $wpdb->prefix . 'csm_scripts';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Delete options
        delete_option('csm_db_version');
        delete_option('csm_pro_activated');
        delete_option('csm_license_key');
        delete_option('csm_license_status');
        delete_option('csm_plan_name');
        delete_option('csm_expires_at');

        // Delete framework options
        delete_option('csm_frameworks_cdn_enabled');
        delete_option('csm_tailwind_enabled');
        delete_option('csm_bulma_enabled');
        delete_option('csm_bootstrap_css_enabled');
        delete_option('csm_bootstrap_js_enabled');
        delete_option('csm_bootstrap_popper_js_enabled');
        delete_option('csm_bootstrap_js_bundled_enabled');

        // Clear any transients
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_csm_%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_csm_%'");
    }
}

// Only run uninstaller if not in WordPress core uninstall process
if (!defined('WP_UNINSTALL_PLUGIN')) {
    register_uninstall_hook(__FILE__, 'csm_uninstall_plugin');
} else {
    csm_uninstall_plugin();
}
