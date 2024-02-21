<?php
/*
Plugin Name: Page Duplicator
Description: A simple plugin to duplicate pages in WordPress.
Version: 1.0
Author:            Shishir Raven
Author URI:        https://iamshishir.com/
Author Email:      shishir.raven@gmail.com
*/

function duplicate_page($post_id) {
    if (!current_user_can('edit_posts')) {
        return;
    }

// get post from $_GET
    $post_id = $_GET['post'];

    // Get the original page
    $post = get_post($post_id);

    // Create a new post with the same content
    $new_post = array(
        'post_title' => $post->post_title.'_copy',
        'post_content' => $post->post_content,
        'post_status' => $post->post_status,
        'post_type' => $post->post_type,
    );

    $new_post_id = wp_insert_post($new_post);

    // If the page was duplicated successfully, redirect to the new page
    if ($new_post_id) {
        wp_redirect(get_edit_post_link($new_post_id, ''));
        exit;
    }
}

add_action('admin_action_duplicate_page', 'duplicate_page');

function add_duplicate_link($actions, $post) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . admin_url('admin.php?action=duplicate_page&post=' . $post->ID) . '">Duplicate</a>';
    }
    return $actions;
}

add_filter('page_row_actions', 'add_duplicate_link', 10, 2);
