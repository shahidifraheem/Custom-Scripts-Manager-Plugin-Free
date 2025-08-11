<?php

/**
 * Class CSM_Database
 * Handles all database operations for the Custom Scripts Manager plugin
 * including table creation, script CRUD operations, and data retrieval
 */
class CSM_Database
{
    // Database table name and version
    private static $table_name;
    private static $db_version = '1.0';

    /**
     * Initialize the database class
     * Sets up table name and ensures table exists
     */
    public static function csm_init()
    {
        global $wpdb;
        self::$table_name = $wpdb->prefix . 'csm_scripts';

        // Add this to ensure table exists on every admin page load
        if (is_admin()) {
            self::csm_create_table();
        }
    }

    /**
     * Plugin activation hook - creates the database table
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public static function activate()
    {
        // Ensure no output during activation
        ob_start();
        $result = self::csm_create_table();
        ob_end_clean();
        return $result;
    }

    /**
     * Create the custom scripts table if it doesn't exist
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    private static function csm_create_table()
    {
        global $wpdb;

        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '" . self::$table_name . "'") === self::$table_name) {
            return true;
        }

        // Table doesn't exist, create it
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . self::$table_name . " (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            code_type enum('css','js') NOT NULL,
            code_content longtext NOT NULL,
            location enum('header','footer','after_body') NOT NULL DEFAULT 'footer',
            priority int NOT NULL DEFAULT 10,
            is_active tinyint(1) NOT NULL DEFAULT 1,
            is_global tinyint(1) NOT NULL DEFAULT 1,
            target_pages text,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);

        // Verify creation
        if ($wpdb->get_var("SHOW TABLES LIKE '" . self::$table_name . "'") === self::$table_name) {
            update_option('csm_db_version', self::$db_version);
            return true;
        }

        return new WP_Error('db_error', 'Failed to create database table');
    }

    /**
     * Plugin deactivation hook (currently does nothing)
     */
    public static function csm_deactivate()
    {
        // Optionally clean up on deactivation
    }

    /**
     * Get all scripts with optional filtering
     * @param string|null $type Filter by script type (css/js)
     * @param bool|null $global Filter by global status
     * @return array List of script objects
     */
    public static function csm_get_scripts($type = null, $global = null)
    {
        global $wpdb;

        $where = "WHERE 1=1";

        if (!is_null($type)) {
            $where .= $wpdb->prepare(" AND code_type = %s", $type);
        }

        if (!is_null($global)) {
            $where .= $wpdb->prepare(" AND is_global = %d", $global);
        }

        return $wpdb->get_results("SELECT * FROM " . self::$table_name . " $where ORDER BY priority");
    }

    /**
     * Get all active scripts with optional filtering
     * @param string|null $type Filter by script type (css/js)
     * @param bool|null $global Filter by global status
     * @return array List of active script objects
     */
    public static function csm_get_active_scripts($type = null, $global = null)
    {
        global $wpdb;

        $where = "WHERE is_active = 1"; // Only active scripts by default

        if (!is_null($type)) {
            $where .= $wpdb->prepare(" AND code_type = %s", $type);
        }

        if (!is_null($global)) {
            $where .= $wpdb->prepare(" AND is_global = %d", $global);
        }

        return $wpdb->get_results("SELECT * FROM " . self::$table_name . " $where ORDER BY priority");
    }

    /**
     * Get a single script by ID
     * @param int $id Script ID
     * @return object|null Script object or null if not found
     */
    public static function csm_get_script($id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM " . self::$table_name . " WHERE id = %d", $id));
    }

    /**
     * Save a script (insert or update)
     * @param array $data Script data including all fields
     * @return int The script ID (new or updated)
     */
    public static function csm_save_script($data)
    {
        global $wpdb;

        // Remove slashes from code content
        if (isset($data['code_content'])) {
            $data['code_content'] = wp_unslash($data['code_content']);
        }

        // Rest of your save logic
        if (isset($data['id'])) {
            $wpdb->update(self::$table_name, $data, ['id' => $data['id']]);
            return $data['id'];
        } else {
            $wpdb->insert(self::$table_name, $data);
            return $wpdb->insert_id;
        }
    }

    /**
     * Delete a script by ID
     * @param int $id Script ID to delete
     * @return bool|int Number of rows deleted or false on error
     */
    public static function csm_delete_script($id)
    {
        global $wpdb;
        return $wpdb->delete(self::$table_name, ['id' => $id]);
    }
}
