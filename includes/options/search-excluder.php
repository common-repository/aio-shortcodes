<?php

// Add meta box to post edit screen if the setting is enabled
function exclude_from_search_meta_box() {
    $show_metabox = get_option('aio_search_excluder');
    //$post_types = array('post', 'page');
    $post_types = get_post_types(array('public' => true), 'names');
    if ($show_metabox) {
        add_meta_box(
            'exclude-from-search',
            'AIO Search Exclude ',
            'exclude_from_search_meta_box_callback',
            $post_types,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'exclude_from_search_meta_box');


function exclude_from_search_meta_box_callback($post) {
    $exclude_from_search = get_post_meta($post->ID, 'exclude_from_search', true);
    ?>
    <label for="exclude_from_search">
        <input type="checkbox" id="exclude_from_search" name="exclude_from_search" <?php checked($exclude_from_search, 'on'); ?> />
        Exclude From Search
    </label>
    <?php
}
function save_exclude_from_search_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['exclude_from_search'])) {
        update_post_meta($post_id, 'exclude_from_search', 'on');
    } else {
        delete_post_meta($post_id, 'exclude_from_search');
    }
}
add_action('save_post', 'save_exclude_from_search_meta_box');

function exclude_posts_from_search($query) { 
    $aio_metabox_condition = get_option('aio_search_excluder');
    if($aio_metabox_condition){
    if ($query->is_search && !is_admin()) {
        $query->set('meta_query', array(
            array(
                'key' => 'exclude_from_search',
                'value' => 'on',
                'compare' => 'NOT EXISTS',
            ),
        ));
    }
    }

}
add_action('pre_get_posts', 'exclude_posts_from_search');


add_action('admin_init', 'save_selected_posts');
function save_selected_posts() {

    if (isset($_POST['submit']) && isset($_POST['aio_custom_css'])) {  
        /* ----- update theme mod customizer css ----- */
        $aio_customizer_css = isset($_POST['aio_custom_css']) ? $_POST['aio_custom_css'] : '';
        update_option('aio_custom_css', $aio_customizer_css);
    }
}

/*----------- Delete Exclude key From Post Meta --------*/
add_action('wp_ajax_exclude_post_visible_ajax', 'exclude_post_visible_ajax');
add_action('wp_ajax_nopriv_exclude_post_visible_ajax', 'exclude_post_visible_ajax');
function exclude_post_visible_ajax() {
    if (isset($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        if (is_numeric($post_id) && intval($post_id) > 0) {
            delete_post_meta($post_id, 'exclude_from_search');
            wp_send_json_success();
            wp_die();
        } else {
            wp_send_json_error('Invalid post ID.');
        }
    } else {
        wp_send_json_error('No post ID provided.');
    }
}


function sws_bulk_edit_options($bulk_actions) {
    $bulk_actions['hide_from_search'] = esc_html__('Hide from Search', 'search-exclude');
    $bulk_actions['show_in_search'] = esc_html__('Show in Search', 'search-exclude');
    return $bulk_actions;
}

function sws_update_post_meta_hide() {
    $post_ids = isset($_REQUEST['post']) ? $_REQUEST['post'] : array();

    foreach ($post_ids as $post_id) {       
        update_post_meta($post_id, 'exclude_from_search', 'on');
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
    exit();
}

function sws_update_post_meta_show() {
    $post_ids = isset($_REQUEST['post']) ? $_REQUEST['post'] : array();

    foreach ($post_ids as $post_id) {       
        delete_post_meta($post_id, 'exclude_from_search');
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
    exit();
}

/* -----Add Extra Column In Post Table------ */

function sws_custom_search_include_column_header($columns) {
     unset( $columns['date'] );	
     $columns['search_included'] = 'Exclude From Search';
     $columns['date'] = 'Date';   
    return $columns;
}
function sws_custom_search_include_column_content($column_name, $post_id) {
   if ($column_name === 'search_included') {
        $exclude_from_search = get_post_meta($post_id, 'exclude_from_search', true);

        if ($exclude_from_search === 'on') {
            echo 'Hidden';
        } else {
            echo 'Visible';
        }
    }
}

function sws_quick_edit_custom_fields($column_name, $post_type) {
    global $post;
    if ($column_name === 'search_included') {
        $exclude_from_search = get_post_meta($post->ID, 'exclude_from_search', true);
        $checked = ($exclude_from_search === 'on') ? 'checked' : '';

        echo '<fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">                  
                      <label for="exclude_from_search_' . $post->ID . '">
                       <input type="checkbox" id="exclude_from_search_' . $post->ID . '" class="exclude_from_search" name="exclude_from_search" ' . $checked . '>
                         Exclude From Search
                     </label>
                </div>
            </fieldset>';
    }
}

// Save the custom field value from Quick Edit
function sws_save_quick_edit_custom_fields($post_id, $post) {
    if (isset($_REQUEST['exclude_from_search'])) {
        update_post_meta($post_id, 'exclude_from_search', 'on');
    } else {
        delete_post_meta($post_id, 'exclude_from_search');
    }
}

/* --------search exclude functionality add in bulk edit and table column --------*/
add_action('wp_loaded' , 'sws_search_exlude_check');
function sws_search_exlude_check(){
    $exlude_functionality = get_option('aio_search_excluder');

    if($exlude_functionality === "1"){

        add_filter('manage_posts_columns', 'sws_custom_search_include_column_header');

        add_filter('manage_pages_columns', 'sws_custom_search_include_column_header');

        add_action('manage_posts_custom_column', 'sws_custom_search_include_column_content', 10, 2);

        add_action('manage_pages_custom_column', 'sws_custom_search_include_column_content', 10, 2);

        add_action('quick_edit_custom_box', 'sws_quick_edit_custom_fields', 10, 2);

        add_action('save_post', 'sws_save_quick_edit_custom_fields', 10, 2);

        add_action('admin_action_hide_from_search', 'sws_update_post_meta_hide');

        add_action('admin_action_show_in_search', 'sws_update_post_meta_show');   
        
        $all_post_types = get_post_types(array('public' => true), 'names');       
       
        foreach ($all_post_types as $post_type) {

            add_filter("bulk_actions-edit-$post_type", 'sws_bulk_edit_options');

        }
    }
}




