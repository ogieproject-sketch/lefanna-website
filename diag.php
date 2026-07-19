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

echo "\nChecking directories:\n";
$document_root = $_SERVER['DOCUMENT_ROOT'];
echo "DOCUMENT_ROOT: $document_root\n";

if (is_dir($document_root)) {
    $files = scandir($document_root);
    echo "Files in DOCUMENT_ROOT:\n";
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $document_root . '/' . $file;
        echo "- $file (" . (is_dir($path) ? "DIR" : filesize($path) . " bytes") . ")\n";
    }
    
    // Check if there is public_html/public_html
    $nested_public = $document_root . '/public_html';
    if (is_dir($nested_public)) {
        echo "\n[FOUND] Nested public_html exists! Listing its contents:\n";
        $nested_files = scandir($nested_public);
        foreach ($nested_files as $nfile) {
            if ($nfile === '.' || $nfile === '..') continue;
            echo "  |- $nfile\n";
        }
    } else {
        echo "\n[INFO] No nested public_html directory found.\n";
    }
    
    // Force delete .ftp-deploy-sync-state.json to trigger full sync
    $sync_state_file = $document_root . '/.ftp-deploy-sync-state.json';
    if (is_file($sync_state_file)) {
        if (unlink($sync_state_file)) {
            echo "\n[OK] successfully DELETED .ftp-deploy-sync-state.json on server. Next deploy will be a FULL sync.\n";
        } else {
            echo "\n[ERROR] Failed to delete .ftp-deploy-sync-state.json\n";
        }
    } else {
        echo "\n[INFO] No .ftp-deploy-sync-state.json file found to delete.\n";
    }

    // Clean up utility files for security
    if (isset($_GET['cleanup']) && $_GET['cleanup'] === '1') {
        echo "\nPerforming cleanup of utility files for security:\n";
        $setup_file = $document_root . '/setup-host.php';
        if (is_file($setup_file)) {
            unlink($setup_file);
            echo "- Deleted setup-host.php\n";
        }
        $diag_file = $document_root . '/diag.php';
        if (is_file($diag_file)) {
            echo "- Deleted diag.php (self-delete)\n";
            unlink($diag_file);
        }
        exit;
    }
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
