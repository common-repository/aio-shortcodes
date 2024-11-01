<?php

// Inside your main plugin file or functions file
add_action('admin_menu', 'aio_shortcodes_add_settings_page');

function aio_shortcodes_add_settings_page() {
    // Navigate up one level before going into the "assets" folder
    $icon_url = plugin_dir_url(dirname(__FILE__)) . 'assets/images/aio-menu-icon.png';

    add_menu_page('AIO Shortcodes Settings', 'AIO Shortcodes', 'manage_options', 'aio_shortcodes_settings', 'aio_shortcodes_settings_page', $icon_url);
}

function aio_shortcodes_settings_page() {
    // Display your settings page content here
    ?>
    <div class="wrap">
        <h2>AIO Shortcodes Settings</h2>
        <form method="post" action="options.php" id="sss">
            <?php
                settings_fields('aio_shortcodes_settings_group');
                do_settings_sections('aio_shortcodes_settings');
                submit_button();
              
            ?>
        </form>
    </div>
    <?php
    
}

// Inside your main plugin file or functions file
add_action('admin_init', 'aio_shortcodes_register_settings');

function aio_shortcodes_register_settings() {
    register_setting('aio_shortcodes_settings_group', 'display_download_link');
    register_setting('aio_shortcodes_settings_group', 'display_duplicate_link'); // New setting for Duplicate link

    add_settings_section('aiosc_main_section', 'Main Settings', 'aio_shortcodes_section_callback', 'aio_shortcodes_settings');

    add_settings_field('display_download_link', 'Plugin/Theme Downloader', 'aiosc_display_download_link_callback', 'aio_shortcodes_settings', 'aiosc_main_section');

    // New settings field for Duplicate link
    add_settings_field('display_duplicate_link', 'Post/Page Duplicator', 'aiosc_display_duplicate_link_callback', 'aio_shortcodes_settings', 'aiosc_main_section');

    /*********Settings fields For custom css section */
    register_setting('aio_shortcodes_settings_group', 'aio_custom_css_toggle');
    add_settings_field('aio_custom_css_toggle', 'Custom Simple CSS', 'aio_custom_css_toggle_callback', 'aio_shortcodes_settings', 'aiosc_main_section');
    /****** */


    /********Settings fields For Search exclude settig */

    register_setting('aio_shortcodes_settings_group', 'aio_search_excluder');
    add_settings_field('aio_search_excluder', 'Search Excluder', 'aiosc_search_excluder_callback', 'aio_shortcodes_settings', 'aiosc_main_section');

}

// Section callback content if needed
function aio_shortcodes_section_callback() {}

// Callback function for Download link
function aiosc_display_download_link_callback() {
    $display_download_link = get_option('display_download_link');
    ?>
    <label>
        <input type="checkbox" name="display_download_link" <?php checked(1, $display_download_link, true); ?> value="1" />
        Show/hide the <strong>Download ZIP</strong> link
    </label>
    
    <?php
}

// Callback function for Duplicate link
function aiosc_display_duplicate_link_callback() {  
        $display_duplicate_link = get_option('display_duplicate_link');
        
            ?>
                <label>
                    <input type="checkbox" name="display_duplicate_link" <?php checked(1, $display_duplicate_link, true); ?> value="1" />
                    Show/hide the <strong>Duplicate Link</strong> inside post, pages and custom posts list
                </label>
            <?php
}

function aio_custom_css_toggle_callback() {
    $display_custom_css_toggle = get_option('aio_custom_css_toggle');
    $aio_customiser_css = get_option('aio_custom_css');
 
        ?>  <div class="">
                <label for="aio_custom_css_toggle">
                    <input type="checkbox" id="aio_custom_css_toggle" name="aio_custom_css_toggle" <?php checked(1, $display_custom_css_toggle, true);  ?> value="1" />
                    Enable Custom CSS in Customizer
                </label>
            </div>
            <div class="code-editor customizers-code-editor">
                <div class="code-line-numbers">
                    
                </div>
                <div class="code-textarea-wrapper">
                   <textarea id="message" name="aio_custom_css" rows="4" cols="50"><?php echo $aio_customiser_css ?></textarea>
                </div>
            </div>            
        <?php
}


function aiosc_search_excluder_callback() {
    $show_metabox = get_option('aio_search_excluder');
    ?>
         <div class="aio_search">
            <label for="aio_search_excluder">
                <input type="checkbox" id="aio_search_excluder" name="aio_search_excluder" <?php checked(1, $show_metabox, true); ?> value="1" />
                Enable Search Exclude on Post Edit Screen and Search Post/Pages to Exclude from Search 
            </label>
        
        </div> 
    <?php

     $post_types = get_post_types(array('public' => true), 'names');
     $posts_args = array(
        'post_type' => $post_types,
        'orderby'   => 'meta_key',
        'meta_query' => array(
            'meta_key' => array(
            'key' => 'exclude_from_search',                  
        )));
      
      $results = new WP_Query($posts_args);

     ?>
    <table id='selUser' style='width: 200px;' class="post-exclude-show">
        <thead>
            <tr>
                <th class="exclude_table"><?php echo esc_html('No:'); ?></th>
                <th><?php echo esc_html('Exclude from Search'); ?></th>
                <th class="exclude_delete"><?php echo esc_html('Action'); ?></th>
            </tr>
        </thead>
        <tbody>
        
        <?php
        $loader = plugin_dir_url(dirname(__FILE__)) . 'assets/images/spinner_loader.gif';
            $i = 1;
            if( $results->have_posts() ) :
                while( $results->have_posts() ) : $results->the_post();	
                    $title = ( mb_strlen( $results->post->post_title ) > 50 ) ? mb_substr( $results->post->post_title, 0, 49 ) . '&hellip;' : $results->post->post_title;
                        ?>  <tr>
                                <td class="exclude_table"> <?php echo $i ?> </td>
                                <td> <?php echo $title ?> </td>
                                <td class="exclude_delete"><button type="button" post-id="<?php echo $results->post->ID;  ?>" class="exclude-post-delete"> <?php echo esc_html('Delete'); ?></button>
                                <img src="<?php echo esc_url($loader); ?>">
                            </td>                       
                            </tr>  
                        <?php
                        $i++;
                endwhile;
            endif;
            ?>
        <tbody>
    </table>     
    <?php

}

