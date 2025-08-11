<?php

/**
 * Class CSM_Premium
 * Handles premium features for the Custom Scripts Manager plugin
 * including scope management (global/specific pages) and target page selection
 */
class CSM_Premium
{
    /**
     * Initialize premium features by adding hooks and filters
     */
    public static function csm_init()
    {
        // Add filter to extend script fields with premium options
        add_filter('csm_script_fields', [__CLASS__, 'csm_add_premium_fields']);
        // Add action to render premium fields in the admin UI
        add_action('csm_after_basic_fields', [__CLASS__, 'csm_render_premium_fields']);

        // Set default options
        add_option('csm_frameworks_cdn_enabled', 0);
        add_option('csm_tailwind_enabled', 0);
        add_option('csm_bulma_enabled', 0);
        add_option('csm_bootstrap_css_enabled', 0);
        add_option('csm_bootstrap_js_enabled', 0);
        add_option('csm_bootstrap_popper_js_enabled', 0);
        add_option('csm_bootstrap_js_bundled_enabled', 0);
    }

    /**
     * Add premium fields to the script fields array
     * @param array $fields Existing fields array
     * @return array Modified fields array with premium fields
     */
    public static function csm_add_premium_fields($fields)
    {
        // Add scope selection field (global or specific pages)
        $fields['is_global'] = [
            'label' => esc_html__('Scope', 'custom-scripts-manager'),
            'type' => 'select',
            'options' => [
                '1' => esc_html__('Global (Entire Site)', 'custom-scripts-manager'),
                '0' => esc_html__('Specific Pages', 'custom-scripts-manager')
            ],
            'default' => '1'
        ];

        // Add target pages multiselect field (shown when scope is specific pages)
        $fields['target_pages'] = [
            'label' => esc_html__('Target Pages', 'custom-scripts-manager'),
            'type' => 'multiselect',
            'options' => self::csm_get_page_options(),
            'default' => [],
            'show_if' => ['is_global' => '0']
        ];

        return $fields;
    }

    /**
     * Render premium fields in the admin UI
     * @param object|null $script The script object being edited (null for new scripts)
     */
    public static function csm_render_premium_fields($script)
    {

        echo '<div class="csm-pro-card">';
        echo '<div class="csm-pro-badge">PRO</div>';
        echo '<div class="csm-field">';
        echo '    <label for="priority">' . esc_html__('Priority', 'custom-scripts-manager') . '</label>';
        echo '    <input type="number" name="priority" id="priority" disabled min="1" max="999" value="' . ($script ? esc_attr($script->priority) : 10) . '">';
        echo '    <p class="description">' . esc_html__('Lower numbers execute earlier (1-999)', 'custom-scripts-manager') . '</p>';
        echo '</div>';

        // Get current values or defaults
        $is_global = isset($script->is_global) ? $script->is_global : 1;
        $target_pages = isset($script->target_pages) ? maybe_unserialize($script->target_pages) : [];

        // Render scope selection field
        echo '<div class="csm-field">';
        echo '<label for="is_global">' . esc_html__('Scope', 'custom-scripts-manager') . '</label>';
        echo '<select name="is_global" id="is_global">';
        echo '<option value="1"' . selected($is_global, 1, false) . '>' . esc_html__('Global (Entire Site)', 'custom-scripts-manager') . '</option>';
        echo '<option value="0"' . selected($is_global, 0, false) . '>' . esc_html__('Specific Pages', 'custom-scripts-manager') . '</option>';
        echo '</select>';
        echo '</div>';

        // Render target pages multiselect (hidden by default if scope is global)
        echo '<div class="csm-field" id="target-pages-field"' . ($is_global ? ' style="display:none;"' : '') . '>';
        echo '<label>' . esc_html__('Select Pages', 'custom-scripts-manager') . '</label>';

        $pages = get_pages();
        // Handle login page option
        $target_pages = [];
        if ($script && !empty($script->target_pages)) {
            $unserialized = maybe_unserialize($script->target_pages);
            $target_pages = is_array($unserialized) ? $unserialized : [];
        }

        // Build multiselect options
        echo '<select name="target_pages[]" multiple="multiple" class="csm-multiselect" disabled>';
        echo '<option value="login"' . (in_array('login', $target_pages) ? ' selected' : '') . '>' . esc_html__('Login Page', 'custom-scripts-manager') . '</option>';

        // Add regular pages as options
        foreach ($pages as $page) {
            echo '<option value="' . esc_attr($page->ID) . '"' . (in_array($page->ID, $target_pages) ? ' selected' : '') . '>' . esc_attr($page->post_title) . '</option>';
        }

        echo '</select>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Get all available page options for target selection
     * @return array Associative array of page options (ID => title)
     */
    private static function csm_get_page_options()
    {
        $options = [];
        $pages = get_pages();

        // Special option for login page
        $options['login'] = esc_html__('Login Page', 'custom-scripts-manager');

        // Add regular pages
        foreach ($pages as $page) {
            $options[$page->ID] = esc_html($page->post_title);
        }

        return $options;
    }
}
