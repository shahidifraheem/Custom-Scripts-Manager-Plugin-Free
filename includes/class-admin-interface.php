<?php

/**
 * Class CSM_Admin_Interface
 * Handles all admin-facing functionality for the Custom Scripts Manager plugin
 * including menu creation, page rendering, asset management, and settings registration.
 */
class CSM_Admin_Interface
{
    /**
     * Initialize all admin hooks and actions
     */
    public static function csm_init()
    {
        add_action('admin_menu', [__CLASS__, 'csm_add_admin_menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'csm_enqueue_admin_assets']);

        add_action('admin_enqueue_scripts', [__CLASS__, 'csm_enqueue_framework_assets']);
    }

    /**
     * Register the admin menu and submenu items
     */
    public static function csm_add_admin_menu()
    {
        // Main menu item
        add_menu_page(
            esc_html__('Custom Scripts Manager', 'custom-scripts-manager'),
            esc_html__('Scripts Manager', 'custom-scripts-manager'),
            'manage_options',
            'custom-scripts-manager',
            [__CLASS__, 'csm_render_admin_page'],
            'dashicons-editor-code',
            80
        );

        // Submenu item for framework management
        add_submenu_page(
            'custom-scripts-manager',
            esc_html__('Framework Manager', 'custom-scripts-manager'),
            esc_html__('Frameworks', 'custom-scripts-manager'),
            'manage_options',
            'csm-frameworks',
            [__CLASS__, 'csm_render_frameworks_page']
        );

        add_submenu_page(
            'custom-scripts-manager',
            esc_html__('Pricing', 'custom-scripts-manager'),
            esc_html__('Pricing', 'custom-scripts-manager'),
            'manage_options',
            'csm-pricing',
            [__CLASS__, 'csm_render_pricing_page']
        );
    }

    /**
     * Enqueue admin-specific assets
     * @param string $hook The current admin page hook
     */
    public static function csm_enqueue_admin_assets($hook)
    {
        // Only load assets on our plugin's main page
        if (strpos($hook, 'csm-frameworks') !== false) {
            return;
        }

        // CodeMirror for syntax highlighting
        wp_enqueue_code_editor(['type' => 'text/css']);
        wp_enqueue_code_editor(['type' => 'application/javascript']);

        wp_enqueue_style(
            'csm-admin-css',
            esc_url(CSM_PLUGIN_URL . 'assets/css/admin.css'),
            [],
            esc_attr(CSM_VERSION)
        );

        wp_enqueue_script(
            'csm-admin-js',
            esc_url(CSM_PLUGIN_URL . 'assets/js/admin.js'),
            ['jquery', 'wp-codemirror'],
            esc_attr(CSM_VERSION),
            true
        );
    }

    /**
     * Main admin page router - handles different actions (list, edit, delete, save)
     */
    public static function csm_render_admin_page()
    {
        // Sanitize input parameters first
        $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : 'list';
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;

        // Verify nonce for actions that modify data
        switch ($action) {
            case 'edit':
                if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(
                    sanitize_text_field(wp_unslash($_GET['_wpnonce'])),
                    'csm_edit_script_' . $id
                )) {
                    wp_die(esc_html__('Security check failed', 'custom-scripts-manager'));
                }
                break;

            case 'delete':
                if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(
                    sanitize_text_field(wp_unslash($_GET['_wpnonce'])),
                    'csm_delete_script_' . $id
                )) {
                    wp_die(esc_html__('Security check failed', 'custom-scripts-manager'));
                }
                break;

            case 'save':
                if (!isset($_POST['csm_nonce']) || !wp_verify_nonce(
                    sanitize_text_field(wp_unslash($_POST['csm_nonce'])),
                    'csm_save_script'
                )) {
                    wp_die(esc_html__('Security check failed', 'custom-scripts-manager'));
                }
                break;
        }

        // Route to appropriate handler based on action
        switch ($action) {
            case 'new':
                self::csm_render_edit_form(0);
                break;

            case 'edit':
                self::csm_render_edit_form($id);
                break;

            case 'delete':
                self::csm_handle_delete($id);
                self::csm_render_list();
                break;

            case 'save':
                self::csm_handle_save();
                self::csm_render_list();
                break;

            default:
                self::csm_render_list();
        }
    }

    /**
     * Render the script listing page
     */
    private static function csm_render_list()
    {
        // Check for transient notice
        if ($notice = get_transient('csm_save_notice')) {
            add_settings_error(
                'csm_messages',
                'csm_message',
                $notice,
                'success'
            );
            delete_transient('csm_save_notice');
        }

        try {
            $scripts = CSM_Database::csm_get_scripts();
        } catch (Exception $e) {
            add_settings_error(
                'csm_messages',
                'csm_message',
                esc_html__('Database error occurred. Please deactivate and reactivate the plugin.', 'custom-scripts-manager'),
                'error'
            );
            $scripts = [];
        }

        include CSM_PLUGIN_DIR . 'templates/list-scripts.php';
    }

    /**
     * Render the script edit form
     * @param int $id The script ID to edit (0 for new script)
     */
    private static function csm_render_edit_form($id = 0)
    {
        $script = $id ? CSM_Database::csm_get_script($id) : null;
        $template_path = CSM_PLUGIN_DIR . 'templates/edit-script.php';

        if (file_exists($template_path)) {
            include $template_path;
        } else {
            wp_die(esc_html__('Template file missing', 'custom-scripts-manager'));
        }
    }

    /**
     * Handle script saving from the edit form
     */
    private static function csm_handle_save()
    {
        // Verify nonce first
        if (!isset($_POST['csm_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['csm_nonce'])), 'csm_save_script')) {
            wp_die(esc_html__('Security check failed', 'custom-scripts-manager'));
        }

        // Verify user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(
                esc_html__('You do not have sufficient permissions to perform this action.', 'custom-scripts-manager'),
                esc_html__('Permission Error', 'custom-scripts-manager'),
                ['response' => 403]
            );
        }

        // Validate required fields
        if (!isset($_POST['title']) || !isset($_POST['code_type']) || !isset($_POST['code_content'])) {
            wp_die(esc_html__('Required fields are missing', 'custom-scripts-manager'));
        }

        // Prepare and sanitize form data
        $data = [
            'title' => isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '',
            'description' => isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '',
            'code_type' => isset($_POST['code_type']) ? sanitize_text_field(wp_unslash($_POST['code_type'])) : 'js',
            'code_content' => isset($_POST['code_content']) ? csm_sanitize_code_content(wp_unslash($_POST['code_content']), sanitize_text_field(wp_unslash($_POST['code_type']))) : '',
            'location' => isset($_POST['location']) && in_array($_POST['location'], ['header', 'footer', 'after_body']) ? sanitize_text_field(wp_unslash($_POST['location'])) : 'footer',
            'priority' => 10,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'is_global' => 1,
        ];

        if (isset($_POST['id'])) {
            $data['id'] = absint($_POST['id']);
        }

        CSM_Database::csm_save_script($data);

        // Store success message in a transient
        set_transient('csm_save_notice', esc_html__('Script saved successfully.', 'custom-scripts-manager'), 30);

        // JavaScript Redirect back to the list view
        echo '<script type="text/javascript">
            window.location.href = "' . esc_url(admin_url('admin.php?page=custom-scripts-manager')) . '";
        </script>';
        exit;
    }

    /**
     * Handle script deletion
     * @param int $id The script ID to delete
     */
    /**
     * Handle script deletion
     * @param int $id The script ID to delete
     */
    private static function csm_handle_delete($id)
    {
        // Verify nonce first
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(
            sanitize_text_field(wp_unslash($_GET['_wpnonce'])),
            'csm_delete_script_' . $id
        )) {
            wp_die(esc_html__('Security check failed', 'custom-scripts-manager'));
        }

        // Verify user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(
                esc_html__('You do not have sufficient permissions to perform this action.', 'custom-scripts-manager'),
                esc_html__('Permission Error', 'custom-scripts-manager'),
                ['response' => 403]
            );
        }

        // Perform the deletion
        $deleted = CSM_Database::csm_delete_script($id);

        if ($deleted) {
            set_transient('csm_delete_notice', esc_html__('Script deleted successfully.', 'custom-scripts-manager'), 30);
        } else {
            set_transient('csm_delete_notice', esc_html__('Failed to delete script.', 'custom-scripts-manager'), 30);
        }

        // JavaScript Redirect back to the list view
        echo '<script type="text/javascript">
            window.location.href = "' . esc_url(admin_url('admin.php?page=custom-scripts-manager')) . '";
        </script>';
        exit;
    }

    /**
     * Render the frameworks management page
     */
    public static function csm_render_frameworks_page()
    {
        include CSM_PLUGIN_DIR . 'templates/frameworks-page.php';
    }

    public static function csm_render_pricing_page()
    {
        include CSM_PLUGIN_DIR . 'templates/pricing-page.php';
    }

    /**
     * Enqueue assets specific to the frameworks page
     * @param string $hook The current admin page hook
     */
    public static function csm_enqueue_framework_assets($hook)
    {
        if (strpos($hook, 'csm-frameworks') !== false) {
            wp_enqueue_style(
                'csm-frameworks',
                esc_url(CSM_PLUGIN_URL . 'assets/css/frameworks.css'),
                [],
                esc_attr(CSM_VERSION)
            );
        }
    }
}
