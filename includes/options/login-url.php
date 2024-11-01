<?php

// Register settings and fields for login URL
function aio_login_url_settings_init()
{
    register_setting('aio_shortcodes_settings_group', 'aio_hide_login_url_toggle');
    register_setting('aio_shortcodes_settings_group', 'aio_login_url_slug');

    add_settings_section('aio_login_url_main', 'Custom Admin URL Settings', '__return_false', 'aio_shortcodes_settings');

    add_settings_field('aio_hide_login_url_toggle', 'AIO Hide Login', 'aiosc_hide_login_url_toggle_callback', 'aio_shortcodes_settings', 'aio_login_url_main');
    add_settings_field('aio_login_url_slug', 'Custom Login URL', 'aio_login_url_slug_callback', 'aio_shortcodes_settings', 'aio_login_url_main');


    /*********Redirection settings */
    register_setting('aio_shortcodes_settings_group', 'aio_redirection_page');
    add_settings_field('aio_redirection_page', 'Custom Redirection Page', 'aio_redirection_page_callback', 'aio_shortcodes_settings', 'aio_login_url_main');



    $show_url = get_option('aio_hide_login_url_toggle');

    if (!$show_url) {

        update_option('aio_login_url_slug', '');
        update_option('aio_redirection_page', '');
        flush_rewrite_rules(true);
    }
}
add_action('admin_init', 'aio_login_url_settings_init');


add_action('update_option', 'custom_login_url_slug_updated', 10, 3);
add_filter('login_url', 'aio_admin_login_url_change', 10, 3);


// Custom Login URL toggle field callback
function aiosc_hide_login_url_toggle_callback()
{
    $hide_login_url_toggle = get_option('aio_hide_login_url_toggle');
?>

    <label for="aio_hide_login_url_toggle">
        <input type="checkbox" id="aio_hide_login_url_toggle" name="aio_hide_login_url_toggle" <?php checked(1, $hide_login_url_toggle, true); ?> value="1" />
        Show/Hide AIO Change Admin URL Section
    </label>

<?php
}

// Custom Login URL slug field callback
function aio_login_url_slug_callback()
{
    $custom_login_url_slug = get_option('aio_login_url_slug', '');

    $custom_login_url_slug_empty = empty($custom_login_url_slug) ? 'login' : '';
?>
    Current Login URL: <code><?php echo site_url() . '/'; ?> </code>
    <label for="aio_login_url_slug">
        <input type="text" id="aio_login_url_slug" name="aio_login_url_slug" value="<?php echo esc_attr($custom_login_url_slug); ?>" placeholder="<?php echo $custom_login_url_slug_empty; ?>" /><code>/</code>
    </label>
    <p>Enhance website security by modifying the login URL and restricting entry to wp-login.php and wp-admin for unauthorized users.</p>
<?php
}

function aio_redirection_page_callback()
{
    $aio_redirection_page = get_option('aio_redirection_page', '');
    $aio_redirection_page_empty = empty($aio_redirection_page) ? '404' : '';
?>

    Current redirection: <code><?php echo site_url() . '/'; ?></code>

    <label for="aio_redirection_page">
        <input type="text" id="aio_redirection_page" name="aio_redirection_page" value="<?php echo esc_attr($aio_redirection_page); ?>" placeholder="<?php echo $aio_redirection_page_empty; ?>" /><code>/</code>
    </label>
    <p>Custom redirection page (enter the path, e.g., /404-page).</p>
<?php
}
function aio_admin_login_url_change($login_url, $redirect, $force_reauth)
{


    if (!is_user_logged_in()) {

        $custom_login_url_slug = trim(get_option('aio_login_url_slug'), '/');
        $aio_redirection_page = get_option('aio_redirection_page', '/');

        if (empty($custom_login_url_slug)) {
            return $login_url;
        }

       
        $current_saved_url = home_url('/' . $custom_login_url_slug);
        $redirection_page_url = home_url('/' . $aio_redirection_page);

        $current_request_uri = $_SERVER['REQUEST_URI'];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $domain = $_SERVER['HTTP_HOST'];
        $current_url_request = $protocol . '://' . $domain . $current_request_uri;

        if ($current_saved_url !== $current_url_request) {
           
            if (!headers_sent()) {

                if (!empty($aio_redirection_page)) {
                    wp_redirect($redirection_page_url);
                } else {
                    
                    wp_redirect(home_url('/404'));
                }
            }
           
        } else {

            return site_url($custom_login_url_slug, 'login');
        }
    }else{
        return $login_url;
    }
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php?action=logout') !== false) {
        if (!empty($custom_login_url_slug)) {
            wp_redirect(site_url($custom_login_url_slug, 'login'));
            exit;
        } else {
            wp_redirect(wp_logout_url());
        }
    }
}

/** Function to update admin url if given */
function custom_login_url_slug_updated($option, $old_value, $value)
{
    if ($option === 'aio_login_url_slug' && $old_value !== $value) {
        $custom_login_url_slug = trim($value, '/');
        if (!empty($custom_login_url_slug)) {
            add_rewrite_rule("^$custom_login_url_slug/?$", 'wp-login.php', 'top');
            flush_rewrite_rules(true);
        } else {
            flush_rewrite_rules(true);
        }
    }
}
