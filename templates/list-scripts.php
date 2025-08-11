<div class="wrap csm-admin">
    <h1><?php esc_html_e('Custom Scripts Manager', 'custom-scripts-manager'); ?></h1>

    <?php settings_errors('csm_messages'); ?>

    <div class="csm-header">
        <a href="<?php echo esc_url(admin_url('admin.php?page=custom-scripts-manager&action=new')); ?>" class="button button-primary">
            <?php esc_html_e('Add New Script', 'custom-scripts-manager'); ?>
        </a>
    </div>

    <div class="csm-admin-container">
        <!-- Main Content Area -->
        <div class="csm-main-content">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Title', 'custom-scripts-manager'); ?></th>
                        <th><?php esc_html_e('Type', 'custom-scripts-manager'); ?></th>
                        <th><?php esc_html_e('Location', 'custom-scripts-manager'); ?></th>
                        <th><?php esc_html_e('Priority', 'custom-scripts-manager'); ?></th>
                        <th><?php esc_html_e('Scope', 'custom-scripts-manager'); ?></th>
                        <th><?php esc_html_e('Status', 'custom-scripts-manager'); ?></th>
                        <th><?php esc_html_e('Actions', 'custom-scripts-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($scripts)) : ?>
                        <tr>
                            <td colspan="6"><?php esc_html_e('No scripts found.', 'custom-scripts-manager'); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($scripts as $script) : ?>
                            <tr>
                                <td><?php echo esc_html($script->title); ?></td>
                                <td><?php echo esc_html(strtoupper($script->code_type)); ?></td>
                                <td><?php echo esc_html(ucwords(str_replace("_", " ", $script->location))); ?></td>
                                <td><?php echo esc_attr($script->priority); ?></td>
                                <td><?php echo $script->is_global ? esc_html__('Global (Entire Site)', 'custom-scripts-manager') : esc_html__('Specific Pages', 'custom-scripts-manager'); ?></td>
                                <td>
                                    <span class="csm-status <?php echo $script->is_active ? 'active' : 'inactive'; ?>">
                                        <?php echo $script->is_active ? esc_html__('Active', 'custom-scripts-manager') : esc_html__('Inactive', 'custom-scripts-manager'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    // Edit link with proper nonce
                                    $edit_nonce = wp_create_nonce('csm_edit_script_' . $script->id);
                                    ?>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=custom-scripts-manager&action=edit&id=' . $script->id . '&_wpnonce=' . $edit_nonce)); ?>">
                                        <?php esc_html_e('Edit', 'custom-scripts-manager'); ?>
                                    </a> |
                                    <?php
                                    // Delete link with proper nonce and confirmation
                                    $delete_nonce = wp_create_nonce('csm_delete_script_' . $script->id);
                                    $delete_url = add_query_arg([
                                        'page' => 'custom-scripts-manager',
                                        'action' => 'delete',
                                        'id' => $script->id,
                                        '_wpnonce' => $delete_nonce
                                    ], admin_url('admin.php'));
                                    ?>
                                    <a href="<?php echo esc_url($delete_url); ?>" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this script?', 'custom-scripts-manager')); ?>');">
                                        <?php esc_html_e('Delete', 'custom-scripts-manager'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- New Sidebar -->
        <div class="csm-admin-sidebar">
            <div class="csm-sidebar-card">
                <div class="csm-logo">
                    <img src="<?php echo esc_url(plugins_url('assets/images/custom-scripts-manager.png', dirname(__FILE__))); ?>" alt="<?php esc_attr_e('Custom Scripts Manager', 'custom-scripts-manager'); ?>" width="100px">
                </div>
                <h3><?php esc_html_e('Upgrade to Pro', 'custom-scripts-manager'); ?></h3>
                <p><?php esc_html_e('Unlock advanced features and priority support', 'custom-scripts-manager'); ?></p>
                <a href="<?php echo esc_url(admin_url('admin.php?page=csm-pricing')); ?>" class="plugin-btn button button-primary csm-upgrade-btn">
                    <?php esc_html_e('View Pricing', 'custom-scripts-manager'); ?>
                </a>
            </div>
            <div class="csm-sidebar-card">
                <div class="csm-logo">
                    <img src="<?php echo esc_url(CSM_PLUGIN_URL . 'assets/images/shahid-ifraheem-logo.webp'); ?>" alt="<?php esc_attr_e('Shahid Ifraheem', 'custom-scripts-manager'); ?>" width="100px">
                </div>
                <h3><?php esc_html_e('Shahid Ifraheem', 'custom-scripts-manager'); ?></h3>
                <p>
                    <?php esc_html_e('Full-stack web developer specializing in custom WordPress development, PHP systems, Chrome extensions, and scalable content management tools.', 'custom-scripts-manager'); ?>
                </p>
                <a href="<?php echo esc_url('https://shahidifraheem.com/contact'); ?>" target="_blank" rel="noopener noreferrer" class="csm-upgrade-btn button button-success">
                    <?php esc_html_e('Contact With Developer', 'custom-scripts-manager'); ?>
                </a>
            </div>
        </div>
    </div>
</div>