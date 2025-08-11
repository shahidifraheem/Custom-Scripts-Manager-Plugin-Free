<div class="wrap csm-frameworks<?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? '' : ' no-pro' ?>">
    <h1><?php esc_html_e('Framework Manager', 'custom-scripts-manager'); ?></h1>

    <div class="csm-framework-grid">
        <!-- Bootstrap Card -->
        <div class="csm-framework-card csm-pro-card">
            <div class="csm-pro-badge">PRO</div>
            <h2>Bootstrap CSS</h2>
            <div class="csm-toggle-container">
                <label class="csm-switch">
                    <input type="checkbox" id="csm-bootstrap-css-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_bootstrap_css_enabled'), 1); ?>>
                    <span class="csm-slider"></span>
                </label>
                <span class="csm-toggle-label"><?php esc_html_e('Enable Bootstrap CSS', 'custom-scripts-manager'); ?></span>
            </div>
            <div class="csm-framework-details">
                <h3>v5.3.7</h3>
                <p>Most popular HTML/CSS/JS framework</p>
                <div class="csm-framework-links">
                    <a href="https://getbootstrap.com/docs/5.3/getting-started/introduction/" target="_blank">Documentation</a>
                </div>
            </div>
        </div>

        <div class="csm-framework-card csm-pro-card">
            <div class="csm-pro-badge">PRO</div>
            <h2>Bootstrap JS</h2>
            <div class="csm-toggle-container">
                <label class="csm-switch">
                    <input type="checkbox" id="csm-bootstrap-js-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_bootstrap_js_enabled'), 1); ?>>
                    <span class="csm-slider"></span>
                </label>
                <span class="csm-toggle-label"><?php esc_html_e('Enable Bootstrap JS', 'custom-scripts-manager'); ?></span>
            </div>
            <div class="csm-framework-details">
                <h3>v5.3.7</h3>
                <p>Most popular HTML/CSS/JS framework</p>
                <div class="csm-framework-links">
                    <a href="https://getbootstrap.com/docs/5.3/getting-started/introduction/" target="_blank">Documentation</a>
                </div>
            </div>
        </div>

        <div class="csm-framework-card csm-pro-card">
            <div class="csm-pro-badge">PRO</div>
            <h2>Bootstrap Popper JS</h2>
            <div class="csm-toggle-container">
                <label class="csm-switch">
                    <input type="checkbox" id="csm-bootstrap-popper-js-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_bootstrap_popper_js_enabled'), 1); ?>>
                    <span class="csm-slider"></span>
                </label>
                <span class="csm-toggle-label"><?php esc_html_e('Enable Bootstrap Popper JS', 'custom-scripts-manager'); ?></span>
            </div>
            <div class="csm-framework-details">
                <h3>v5.3.7</h3>
                <p>Most popular HTML/CSS/JS framework</p>
                <div class="csm-framework-links">
                    <a href="https://getbootstrap.com/docs/5.3/getting-started/introduction/" target="_blank">Documentation</a>
                </div>
            </div>
        </div>

        <div class="csm-framework-card csm-pro-card">
            <div class="csm-pro-badge">PRO</div>
            <h2>Bootstrap Bundeled JS</h2>
            <div class="csm-toggle-container">
                <label class="csm-switch">
                    <input type="checkbox" id="csm-bootstrap-js-bundled-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_bootstrap_js_bundled_enabled'), 1); ?>>
                    <span class="csm-slider"></span>
                </label>
                <span class="csm-toggle-label"><?php esc_html_e('Enable Bootstrap Bundeled JS', 'custom-scripts-manager'); ?></span>
            </div>
            <div class="csm-framework-details">
                <h3>v5.3.7</h3>
                <p>Most popular HTML/CSS/JS framework</p>
                <div class="csm-framework-links">
                    <a href="https://getbootstrap.com/docs/5.3/getting-started/introduction/" target="_blank">Documentation</a>
                </div>
            </div>
        </div>

        <!-- Tailwind Card -->
        <div class="csm-framework-card csm-pro-card">
            <div class="csm-pro-badge">PRO</div>
            <h2>Tailwind CSS</h2>
            <div class="csm-toggle-container">
                <label class="csm-switch">
                    <input type="checkbox" id="csm-tailwind-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_tailwind_enabled'), 1); ?>>
                    <span class="csm-slider"></span>
                </label>
                <span class="csm-toggle-label"><?php esc_html_e('Enable Tailwind', 'custom-scripts-manager'); ?></span>
            </div>
            <div class="csm-framework-details">
                <h3>v3.4.17</h3>
                <p>Utility-first CSS framework</p>
                <div class="csm-framework-links">
                    <a href="https://tailwindcss.com/docs" target="_blank">Documentation</a>
                </div>
                <small style="color: red;"><b>Note:</b> Not recommended for production website.</small>
            </div>
        </div>

        <!-- Bulma Card -->
        <div class="csm-framework-card csm-pro-card">
            <div class="csm-pro-badge">PRO</div>
            <h2>Bulma</h2>
            <div class="csm-toggle-container">
                <label class="csm-switch">
                    <input type="checkbox" id="csm-bulma-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_bulma_enabled'), 1); ?>>
                    <span class="csm-slider"></span>
                </label>
                <span class="csm-toggle-label"><?php esc_html_e('Enable Bulma', 'custom-scripts-manager'); ?></span>
            </div>
            <div class="csm-framework-details">
                <h3>v1.0.4</h3>
                <p>Modern CSS framework based on Flexbox</p>
                <div class="csm-framework-links">
                    <a href="https://bulma.io/documentation/" target="_blank">Documentation</a>
                </div>
            </div>
        </div>
    </div>

    <div class="csm-framework-card csm-pro-card" style="margin-top: 20px;">
        <div class="csm-pro-badge">PRO</div>
        <h2>Enable Framework CDN</h2>
        <p>Default farmeworks are Self Hosted</p>
        <div class="csm-toggle-container">
            <label class="csm-switch">
                <input type="checkbox" id="csm-frameworks-cdn-toggle" <?php echo get_option('csm_pro_activated') || in_array(get_option('csm_plan_name'), ['personal', 'developer', 'agency']) || get_option('csm_license_status') ? "" : "disabled" ?> <?php checked(get_option('csm_frameworks_cdn_enabled'), 1); ?>>
                <span class="csm-slider"></span>
            </label>
            <span class="csm-toggle-label"><?php esc_html_e('Enable Frameworks CDN', 'custom-scripts-manager'); ?></span>
        </div>
    </div>
</div>