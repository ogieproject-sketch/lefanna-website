<?php
// Load WordPress bootstrap
define('WP_USE_THEMES', false);
require_once('wp-load.php');

header('Content-Type: text/plain');

echo "WordPress Bootstrap Loaded.\n";

// Query all posts of type wp_template
$args = array(
    'post_type' => 'wp_template',
    'post_status' => 'any',
    'posts_per_page' => -1
);
$posts = get_posts($args);

echo "Found " . count($posts) . " custom templates in the database:\n";
foreach ($posts as $post) {
    echo "- ID: {$post->ID}, Name: {$post->post_name}, Title: {$post->post_title}, Type: {$post->post_type}\n";
    wp_delete_post($post->ID, true);
    echo "  --> DELETED template {$post->post_name} from database!\n";
}

// Query all posts of type wp_template_part
$args_parts = array(
    'post_type' => 'wp_template_part',
    'post_status' => 'any',
    'posts_per_page' => -1
);
$parts = get_posts($args_parts);
echo "\nFound " . count($parts) . " custom template parts in the database:\n";
foreach ($parts as $part) {
    echo "- ID: {$part->ID}, Name: {$part->post_name}, Title: {$part->post_title}\n";
}

echo "\nDone!\n";
