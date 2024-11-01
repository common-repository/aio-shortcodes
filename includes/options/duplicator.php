<?php

// Register settings
function aiosc_duplicate_button() {
    add_option('aiosc_duplicate_enable', '1');
    register_setting('awp_duplicate_settings', 'aiosc_duplicate_enable', 'intval');
}
add_action('admin_init', 'aiosc_duplicate_button');



// Add "Duplicate Post" link to post row actions
function duplicate_post_row_actions($actions, $post) {
    //print_r($post);
    $post_types = get_post_types(array('public' => true), 'names');
    if ($post_types/*$post->post_type == 'post' || $post->post_type == 'page'*/) {
        $duplicate_url = wp_nonce_url(admin_url('admin.php?action=duplicate_post&post=' . $post->ID), 'duplicate_post_nonce');
        $actions['duplicate_post'] = '<a href="' . esc_url($duplicate_url) . '">Duplicate</a>';
    }
    return $actions;
}
add_filter('page_row_actions', 'duplicate_post_row_actions', 10, 2);
add_filter('post_row_actions', 'duplicate_post_row_actions', 10, 2);

// Duplicate post action
function duplicate_post_action() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'duplicate_post') {
        return;
    }

    // Check nonce
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'duplicate_post_nonce')) {
        wp_die('Security check failed');
    }

    // Get post ID
    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

    // Check if the post ID is valid
    if ($post_id <= 0) {
        wp_die('Invalid post ID');
    }

    // Get the original post data
    $original_post = get_post($post_id);

    // Set post data for duplication
    $post_data = array(
        'post_title' => $original_post->post_title,
        'post_content' => $original_post->post_content,
        'post_status' => 'draft',
        'post_type' => $original_post->post_type,
    );

    // Insert duplicated post
    $new_post_id = wp_insert_post($post_data);

    // Redirect to the new post editor
    if ($new_post_id) {
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    } else {
        wp_die('Error duplicating post');
    }
}
add_action('admin_action_duplicate_post', 'duplicate_post_action');
