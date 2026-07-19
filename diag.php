<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

header('Content-Type: text/plain; charset=utf-8');

$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($token !== 'omanera_diag_8821') {
    http_response_code(403);
    die('Forbidden');
}

echo "Lefanna Diagnostic Utility:\n";
echo "==========================\n\n";

echo "Active Theme: " . get_stylesheet() . "\n";
echo "Active Plugins:\n";
print_r(get_option('active_plugins'));

echo "\nChecking wp-content/themes/lefanna/ directory:\n";
$theme_dir = wp_normalize_path(get_theme_file_path());
echo "Theme Path: $theme_dir\n";

if (is_dir($theme_dir)) {
    $files = scandir($theme_dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $theme_dir . '/' . $file;
        echo "- $file (" . (is_dir($path) ? "DIR" : filesize($path) . " bytes") . ")\n";
        if (is_dir($path) && $file === 'templates') {
            $templates = scandir($path);
            foreach ($templates as $tmpl) {
                if ($tmpl === '.' || $tmpl === '..') continue;
                echo "  |- $tmpl (" . filesize($path . '/' . $tmpl) . " bytes)\n";
            }
        }
    }
} else {
    echo "[ERROR] Theme directory does not exist!\n";
}

echo "\nPHP Version: " . phpversion() . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Display Errors: " . ini_get('display_errors') . "\n";

// Check for recent PHP errors if logged
$log_path = ini_get('error_log');
echo "Error Log Path: $log_path\n";
if ($log_path && is_file($log_path)) {
    echo "Last 10 lines of error log:\n";
    $lines = file($log_path);
    $last_lines = array_slice($lines, -10);
    echo implode("", $last_lines);
} else {
    echo "No error log file found or accessible.\n";
}
