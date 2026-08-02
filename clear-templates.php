<?php
// Load WordPress bootstrap
define('WP_USE_THEMES', false);
require_once('wp-load.php');

header('Content-Type: text/plain');

echo "WordPress Bootstrap Loaded.\n";

// Query all posts of type wp_template
global $wpdb;

// 1. Direct DB deletion of cached templates and template parts
$deleted_templates = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type IN ('wp_template', 'wp_template_part')");
echo "Deleted {$deleted_templates} cached template records from database table wp_posts.\n";

// 2. Clear WP Object Cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "WordPress Object Cache flushed.\n";
}

// 3. Clear PHP OPcache
if (function_exists('opcache_reset')) {
    @opcache_reset();
    echo "PHP OPcache reset.\n";
}

// 4. Clear LiteSpeed Cache if installed
if (class_exists('LiteSpeed\Purge')) {
    LiteSpeed\Purge::purge_all();
    echo "LiteSpeed Cache purged.\n";
}

echo "\nDone clearing templates and caches!\n";
