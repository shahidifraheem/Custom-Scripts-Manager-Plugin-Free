<?php

/**
 * Class CSM_Script_Handler
 * Handles the enqueueing and output of scripts and styles throughout the site
 * including frontend, login page, and framework management
 */
class CSM_Script_Handler
{
    /**
     * Initialize all script handling hooks
     */
    public static function csm_init()
    {
        // General scripts that run on all pages
        add_action('init', [__CLASS__, 'csm_enqueue_general_scripts']);
    }

    /**
     * Enqueue global scripts and styles that should run on all pages
     */
    public static function csm_enqueue_general_scripts()
    {
        // Get active global JavaScripts
        $scripts = CSM_Database::csm_get_active_scripts('js', true);
        // Get active global CSS styles
        $styles = CSM_Database::csm_get_active_scripts('css', true);



        // Process each JavaScript
        foreach ($scripts as $script) {
            if (!$script->is_active) continue; // Skip inactive scripts

            self::csm_add_script($script);
        }

        // Process each CSS style
        foreach ($styles as $style) {
            if (!$style->is_active) continue; // Skip inactive styles

            self::csm_add_style($style);
        }
    }

    /**
     * Helper method to add a JavaScript to the appropriate hook
     * @param object $script The script object from database
     */
    private static function csm_add_script($script)
    {
        // Determine the appropriate WordPress hook based on location
        $hook = match ($script->location) {
            'header'     => 'wp_head',
            'after_body' => 'wp_body_open',
            'footer'     => 'wp_footer',
            default      => 'wp_footer' // Fallback
        };

        // Add the action to output the script
        add_action($hook, function () use ($script) {
            $title = esc_html($script->title);
            $id = absint($script->id);
            $content = wp_kses($script->code_content, [
                'script' => [],
                'div' => [],
                'span' => [],
                'a' => ['href' => [], 'title' => []],
                'br' => [],
                'em' => [],
                'strong' => [],
            ]);

            echo wp_kses(
                "\n<!-- Custom Scripts Manager: {$title} -->\n" .
                    "<script id='csm-script-{$id}'>\n" .
                    $content .
                    "\n</script>\n",
                [
                    'script' => ['id' => true],
                    'div' => ['id' => true],
                    'span' => ['id' => true],
                    'a' => ['href' => true, 'title' => true],
                    'br' => [],
                    'em' => [],
                    'strong' => [],
                    '!--' => []
                ]
            );
        }, $script->priority ?: 10);
    }

    /**
     * Helper method to add a CSS style to the appropriate hook
     * @param object $style The style object from database
     */
    private static function csm_add_style($style)
    {
        // Determine the appropriate WordPress hook based on location
        $hook = match ($style->location) {
            'header'     => 'wp_head',
            'after_body' => 'wp_body_open',
            'footer'     => 'wp_footer',
            default      => 'wp_head' // Fallback
        };

        // Add the action to output the style
        add_action($hook, function () use ($style) {
            $title = esc_html($style->title);
            $id = absint($style->id);
            $content = wp_strip_all_tags($style->code_content);

            echo wp_kses(
                "\n<!-- Custom Scripts Manager: {$title} -->\n" .
                    "<style id='csm-style-{$id}'>\n" .
                    $content .
                    "\n</style>\n",
                [
                    'style' => ['id' => true],
                    '!--' => []
                ]
            );
        }, $style->priority ?: 10);
    }
}
