<?php

/**
 * Helper functions for Custom Scripts Manager
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitize custom code content
 * 
 * @param string $content The code content to sanitize
 * @param string $type The type of code ('js' or 'css')
 * @return string Sanitized code content
 */
/**
 * Sanitize CSS/JS code content
 * 
 * @param string $content The code content to sanitize
 * @param string $type Either 'css' or 'js'
 * @return string Sanitized code content
 */
function csm_sanitize_code_content($content, $type)
{
    // Always remove NULL bytes and strip slashes first
    $content = str_replace("\0", '', wp_unslash($content));

    // Sanitize based on code type
    switch ($type) {
        case 'css':
            // Remove HTML tags but preserve content
            $content = wp_strip_all_tags($content);

            // Remove dangerous CSS constructs
            $patterns = [
                '/expression\(/i',
                '/javascript\s*:/i',
                '/@import\s*url\(/i',
                '/<\/?style\b[^>]*>/i'
            ];
            $content = preg_replace($patterns, '', $content);

            // Preserve formatting by encoding line breaks temporarily
            $content = str_replace("\n", "CSM_NEWLINE", $content);
            $content = esc_textarea($content);
            $content = str_replace("CSM_NEWLINE", "\n", $content);
            break;

        case 'js':
            // Remove script tags but preserve content
            $content = preg_replace('/<\/?script\b[^>]*>/i', '', $content);

            // Remove PHP and server-side tags
            $content = str_replace(
                ['<?', '?>', '<%', '%>', '`'],
                '',
                $content
            );

            // Allow quotes and other necessary JS characters
            $content = preg_replace_callback(
                '/[^\w\s\-\.\,\;\:\+\*\=\!\?\&\|\/\(\)\[\]\{\}\<\>\n\r\'\"\\\\]/',
                function ($matches) {
                    // Allow quotes and backslashes by not removing them
                    if (in_array($matches[0], ['"', "'", '\\'])) {
                        return $matches[0];
                    }
                    return '';
                },
                $content
            );
            break;
    }

    // Final trim and return
    return trim($content);
}