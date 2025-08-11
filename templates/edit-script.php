<div class="wrap csm-admin">
    <h1><?php echo $script ? esc_html__('Edit Script', 'custom-scripts-manager') : esc_html__('Add New Script', 'custom-scripts-manager'); ?></h1>

    <div class="csm-admin-container">
        <!-- Main Content Area -->
        <div class="csm-main-content<?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? '' : ' no-pro'; ?>">
            <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=custom-scripts-manager&action=save')); ?>">
                <?php wp_nonce_field('csm_save_script', 'csm_nonce'); ?>

                <?php if ($script) : ?>
                    <input type="hidden" name="id" value="<?php echo absint($script->id); ?>">
                <?php endif; ?>

                <div class="csm-field">
                    <label for="title"><?php esc_html_e('Title', 'custom-scripts-manager'); ?></label>
                    <input type="text" name="title" id="title" value="<?php echo $script ? esc_attr($script->title) : ''; ?>" required>
                </div>

                <div class="csm-field">
                    <label for="description"><?php esc_html_e('Description', 'custom-scripts-manager'); ?></label>
                    <textarea name="description" id="description"><?php echo $script ? esc_textarea($script->description) : ''; ?></textarea>
                </div>

                <div class="csm-field">
                    <label for="code_type"><?php esc_html_e('Code Type', 'custom-scripts-manager'); ?></label>
                    <select name="code_type" id="code_type">
                        <option value="css" <?php selected($script ? $script->code_type : '', 'css'); ?>><?php esc_html_e('CSS', 'custom-scripts-manager'); ?></option>
                        <option value="js" <?php selected($script ? $script->code_type : '', 'js'); ?>><?php esc_html_e('JavaScript', 'custom-scripts-manager'); ?></option>
                    </select>
                </div>

                <div class="csm-field">
                    <label for="code_content"><?php esc_html_e('Code', 'custom-scripts-manager'); ?></label>
                    <textarea
                        name="code_content"
                        id="code_content"
                        class="csm-code-editor"
                        data-mode="<?php echo isset($script->code_type) && $script->code_type == 'css' ? 'css' : 'js'; ?>"><?php echo isset($script) ? esc_textarea($script->code_content) : ''; ?></textarea>
                </div>

                <div class="csm-field">
                    <label for="location"><?php esc_html_e('Location', 'custom-scripts-manager'); ?></label>
                    <select name="location" id="location">
                        <option value="header" <?php selected($script ? $script->location : '', 'header'); ?>>
                            <?php esc_html_e('Header (inside &lt;head&gt;)', 'custom-scripts-manager'); ?>
                        </option>
                        <option value="after_body" <?php selected($script ? $script->location : '', 'after_body'); ?>>
                            <?php esc_html_e('After &lt;body&gt; tag', 'custom-scripts-manager'); ?>
                        </option>
                        <option value="footer" <?php selected($script ? $script->location : '', 'footer'); ?>>
                            <?php esc_html_e('Footer (before &lt;/body&gt;)', 'custom-scripts-manager'); ?>
                        </option>
                    </select>
                </div>

                <div class="csm-field">
                    <label>
                        <input type="checkbox" name="is_active" id="is_active" value="1" <?php checked(isset($script->is_active) && $script->is_active, true); ?>>
                        <?php esc_html_e('Active', 'custom-scripts-manager'); ?>
                    </label>
                </div>

                <?php do_action('csm_after_basic_fields', $script); ?>

                <div class="csm-submit">
                    <button type="submit" class="button button-primary"><?php esc_html_e('Save Script', 'custom-scripts-manager'); ?></button>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=custom-scripts-manager')); ?>" class="button"><?php esc_html_e('Cancel', 'custom-scripts-manager'); ?></a>
                </div>

            </form>
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