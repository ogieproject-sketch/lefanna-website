<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

header('Content-Type: text/plain');

$pages = get_posts(array(
    'post_type' => 'page',
    'post_status' => 'any',
    'posts_per_page' => -1
));

echo "Pages found in database:\n";
foreach ($pages as $page) {
    $template = get_post_meta($page->ID, '_wp_page_template', true);
    echo "- ID: {$page->ID}, Title: {$page->post_title}, Slug: {$page->post_name}, Status: {$page->post_status}, Template: {$template}\n";
}
