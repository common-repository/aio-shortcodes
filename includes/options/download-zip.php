<?php

add_action('plugins_loaded', 'aiosc_downloads');

function aiosc_downloads(){
    add_filter('plugin_action_links', 'aiosc_plugin_download_links', 10, 4);
    add_filter('theme_action_links', 'aiosc_theme_download_links', 10, 2);
    add_action('admin_footer-themes.php', 'aiosc_script', 99);

    if(isset($_GET['aiosc_dnd_zip']) && wp_verify_nonce($_GET['_wpnonce'], 'aiosc-download')){
        aiosc_download();
    }
}

function aiosc_plugin_download_links($links, $file, $plugin_data, $context) {
    $display_download_link = get_option('display_download_link');

    if ($display_download_link) {
        // Add the download link
        $download_query = build_query(array('aiosc_dnd_zip' => 'plugin', 'object' => $file));
        $download_link = sprintf('<a style="color: green;" href="%s">%s</a>',
            wp_nonce_url(admin_url('?' . $download_query), 'aiosc-download'),
            __('Download ZIP')
        );

        array_push($links, $download_link);
    }

    return $links;
}

function aiosc_theme_download_links($links, $theme) {
    $display_download_link = get_option('display_download_link');

    if ($display_download_link) {
        // Add the download link
        $download_query = build_query(array('aiosc_dnd_zip' => 'theme', 'object' => $theme->get_stylesheet()));
        $download_link = sprintf('<a href="%s">%s</a>',
            wp_nonce_url(admin_url('?' . $download_query), 'aiosc-download'),
            __('Download')
        );

        array_push($links, $download_link);
    }

    return $links;
}


function aiosc_script(){
    $query = build_query(array('aiosc_dnd_zip' => 'theme', 'object' => '_obj_'));
    $url = wp_nonce_url(admin_url('?' . $query), 'aiosc-download');
    $label = __('Download');
    $current_theme = get_stylesheet();

    $script_template = '<script type="text/javascript" id="wp-downloader">
        (function($){
            var url = "%s",
                label = "%s",
                current = "%s",
                button = \'<a class="button button-primary download hide-if-no-js" href="\' + url + \'">\' + label + \'</a>\';

            $(window).load(function(){
                $("#current-theme .theme-options").after(\'<div class="theme-options"><a href="\' + url.replace("_obj_", current) + \'">\' + label + \'</a></div>\');

                $("#wpbody .theme .theme-actions .load-customize").each(function(i, e){
                    var btn = $(button),
                        $e = $(e),
                        href = $e.prop("href");

                    btn.prop("href", url.replace("_obj_", href.replace(/.*theme=(.*)(&|$)/, "$1")));

                    $e.parent().append(btn);
                });
            });

            var d = $("#tmpl-theme-single").html(),
                ar = new RegExp(\'(<div class="active-theme">)(([\n\t]*(<#|<a).*[\n\t]*)*)(</div>)\', "mi");
                ir = new RegExp(\'(<div class="inactive-theme">)(([\n\t]*(<#|<a).*[\n\t]*)*)(</div>)\', "mi");

            d = d.replace(ar, "$1$2" + button + "$5");
            d = d.replace(ir, "$1$2" + button + "$5");

            $("#tmpl-theme-single").html(d);

            $(document).on("click", "a.button.download", function(e){
                e.preventDefault();
                var $this = $(this),
                    href = $(this).parent().find(".load-customize").attr("href"),
                    theme;

                theme = href.replace(/.*theme=(.*)(&|$)/, "$1");
                href = url.replace("_obj_", theme).replace(new RegExp("&amp;", "g"), "&");

                window.location = href;
            });
        }(jQuery))
    </script>';

    printf($script_template, $url, $label, $current_theme);
}

function aiosc_download() {
    if (!class_exists('PclZip')) {
        include ABSPATH . 'wp-admin/includes/class-pclzip.php';
    }

    $what = isset($_GET['aiosc_dnd_zip']) ? sanitize_text_field($_GET['aiosc_dnd_zip']) : '';
    $object = isset($_GET['object']) ? sanitize_text_field($_GET['object']) : '';

    if (empty($what) || empty($object)) {
        wp_die('Invalid request.');
    }

    $root = '';
    $path = '';

    switch ($what) {
        case 'plugin':
            if (strpos($object, '/')) {
                $object = dirname($object);
            }
            $root = WP_PLUGIN_DIR;
            break;
        case 'muplugin':
            if (strpos($object, '/')) {
                $object = dirname($object);
            }
            $root = WPMU_PLUGIN_DIR;
            break;
        case 'theme':
            $root = get_theme_root($object);
            break;
        default:
            wp_die('Invalid request.');
    }

    $object = sanitize_file_name($object);
    if (empty($object)) {
        wp_die('Invalid request.');
    }

    $path = trailingslashit($root) . $object;

    if (!file_exists($path)) {
        wp_die('File not found: ' . $path);
    }

    $fileName = $object . '.zip';

    $upload_dir = wp_upload_dir();
    $tmpFile = trailingslashit($upload_dir['path']) . $fileName;

    $archive = new PclZip($tmpFile);
    $result = $archive->add($path, PCLZIP_OPT_REMOVE_PATH, $root);

    if ($result === 0) {
        wp_die('Error creating ZIP archive: ' . $archive->errorInfo(true));
    }

    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');

    readfile($tmpFile);
    unlink($tmpFile);

    exit;
}
