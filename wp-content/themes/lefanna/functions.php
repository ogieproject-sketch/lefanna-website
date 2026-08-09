<?php
/**
 * Lefanna Theme Functions
 */

add_action('after_setup_theme', function() {
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('post-thumbnails');
    add_theme_support('block-templates');
});

// Add Favicon to wp_head
add_action('wp_head', function() {
    echo '<link rel="icon" type="image/png" href="/wp-content/themes/lefanna/images/favicon.png" />' . "\n";
    echo '<link rel="shortcut icon" type="image/png" href="/wp-content/themes/lefanna/images/favicon.png" />' . "\n";
    echo '<link rel="apple-touch-icon" href="/wp-content/themes/lefanna/images/favicon.png" />' . "\n";
});

// Clear template customizations from database to force WordPress to load HTML files from disk
add_action('init', function() {
    if (isset($_GET['clear_templates'])) {
        $posts = get_posts(array(
            'post_type' => array('wp_template', 'wp_template_part', 'page'),
            'post_status' => 'any',
            'numberposts' => -1
        ));
        foreach ($posts as $post) {
            if ($post->post_type === 'page') {
                wp_update_post(array(
                    'ID'          => $post->ID,
                    'post_content' => ''
                ));
            } else {
                wp_delete_post($post->ID, true);
            }
        }
        echo "Successfully cleared database templates and page content! Please refresh the page.";
        exit;
    }
});
