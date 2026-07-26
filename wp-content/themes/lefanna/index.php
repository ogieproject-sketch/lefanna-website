<?php
/**
 * Main Template File
 * Lefanna Theme
 */

$front_page = __DIR__ . '/templates/front-page.html';
$index_page = __DIR__ . '/templates/index.html';

if ( file_exists( $front_page ) ) {
    include $front_page;
} elseif ( file_exists( $index_page ) ) {
    include $index_page;
} else {
    wp_head();
    echo "<h1>Lefanna Theme</h1>";
    wp_footer();
}
