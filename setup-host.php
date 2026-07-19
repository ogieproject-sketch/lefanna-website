<?php
// Load WordPress bootstrap
define('WP_USE_THEMES', false);
require_once('wp-load.php');

header('Content-Type: text/plain; charset=utf-8');

// Token verification for security
$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($token !== 'omanera_standalone_setup_9931') {
    http_response_code(403);
    die('Forbidden: Invalid token.');
}

echo "Lefanna Standalone Host Setup:\n";
echo "==============================\n\n";

// 1. Activate lefanna-helper plugin
$plugin_path = 'lefanna-helper/lefanna-helper.php';
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (!is_plugin_active($plugin_path)) {
    activate_plugin($plugin_path);
    echo "[OK] Plugin \"Lefanna Helper\" berhasil diaktifkan.\n";
} else {
    echo "[INFO] Plugin \"Lefanna Helper\" sudah aktif.\n";
}

// 2. Switch theme to lefanna
if (get_stylesheet() !== 'lefanna') {
    switch_theme('lefanna');
    echo "[OK] Tema diubah secara aktif ke \"lefanna\".\n";
} else {
    echo "[INFO] Tema \"lefanna\" sudah aktif.\n";
}

// 3. Create pages
$homepage_title = 'Selamat Datang di Lefanna Experience';
$homepage = get_page_by_title($homepage_title);
if (!$homepage) {
    $post_id = wp_insert_post(array(
        'post_title'    => $homepage_title,
        'post_content'  => '<!-- wp:paragraph -->\n<p>Selamat! Lingkungan WordPress Playground Anda berhasil dikonfigurasi. Anda dapat mulai mengedit tema di <code>wp-content/themes/lefanna</code> atau plugin di <code>wp-content/plugins/lefanna-helper</code>.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons -->\n<div class="wp-block-buttons"><!-- wp:button -->\n<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/wp-admin/">Masuk ke Dashboard</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ));
    update_option('show_on_front', 'page');
    update_option('page_on_front', $post_id);
    echo "[OK] Halaman utama dibuat: \"{$homepage_title}\" (ID: {$post_id})\n";
} else {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $homepage->ID);
    echo "[INFO] Halaman utama \"{$homepage_title}\" sudah ada. Memastikan pengaturan halaman depan aktif.\n";
}

// Create remaining pages
$hotels_page_title = 'Hotels & Resorts';
$hotels_page = get_page_by_title($hotels_page_title);
if (!$hotels_page) {
    $post_id = wp_insert_post(array(
        'post_title'    => $hotels_page_title,
        'post_content'  => '<!-- wp:paragraph -->\n<p>Explore our premium collections of boutique hotels and luxury resorts.</p>\n<!-- /wp:paragraph -->',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ));
    echo "[OK] Halaman dibuat: \"{$hotels_page_title}\" (ID: {$post_id})\n";
} else {
    echo "[INFO] Halaman \"{$hotels_page_title}\" sudah ada.\n";
}

$villas_page_title = 'Villas in Bali';
$villas_page = get_page_by_title($villas_page_title);
if (!$villas_page) {
    $post_id = wp_insert_post(array(
        'post_title'    => $villas_page_title,
        'post_content'  => '<!-- wp:paragraph -->\n<p>Explore our premium collections of private villas in Bali.</p>\n<!-- /wp:paragraph -->',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ));
    echo "[OK] Halaman dibuat: \"{$villas_page_title}\" (ID: {$post_id})\n";
} else {
    echo "[INFO] Halaman \"{$villas_page_title}\" sudah ada.\n";
}

$exp_parent_title = 'Experiences';
$exp_parent = get_page_by_title($exp_parent_title);
if (!$exp_parent) {
    $parent_id = wp_insert_post(array(
        'post_title'    => $exp_parent_title,
        'post_content'  => '<!-- wp:paragraph -->\n<p>Discover unique experiences masterfully tailored by Lefanna.</p>\n<!-- /wp:paragraph -->',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ));
    echo "[OK] Halaman Induk dibuat: \"{$exp_parent_title}\" (ID: {$parent_id})\n";
} else {
    $parent_id = $exp_parent->ID;
    echo "[INFO] Halaman Induk \"{$exp_parent_title}\" sudah ada.\n";
}

$experiences_subpages = array(
    'Journeys' => 'Explore unique journeys.',
    'Celebrations' => 'Boutique weddings and private dinners.',
    'Culture & Conservations' => 'Balinese dance, woodcarving, and village walks.',
    'Active Adventure' => 'Volcano trekking and river rafting.',
    'Wellness' => 'Daily yoga, spa treatment, and meditation.'
);

foreach ($experiences_subpages as $sub_title => $sub_desc) {
    $subpage = get_page_by_title($sub_title);
    if (!$subpage) {
        $post_id = wp_insert_post(array(
            'post_title'    => $sub_title,
            'post_content'  => '<!-- wp:paragraph -->\n<p>' . $sub_desc . '</p>\n<!-- /wp:paragraph -->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_parent'   => $parent_id
        ));
        echo "[OK] Halaman Anak dibuat: \"{$sub_title}\" (ID: {$post_id}) di bawah parent ID: {$parent_id}\n";
    } else {
        echo "[INFO] Halaman Anak \"{$sub_title}\" sudah ada.\n";
    }
}

$corporate_pages = array(
    'About Us' => 'About Lefanna Experience.',
    'Offers' => 'Explore exclusive packages.',
    'Investor Relations' => 'Investor and partnership information.',
    'Careers' => 'Explore hotel job vacancies.',
    'Newsrooms' => 'Latest press releases and awards.',
    'Contact Us' => 'Get in touch with our office.',
    'Lefanna Leisure Unlimited Services' => 'PLUS corporate services.'
);

foreach ($corporate_pages as $corp_title => $corp_desc) {
    $page = get_page_by_title($corp_title);
    if (!$page) {
        $post_id = wp_insert_post(array(
            'post_title'    => $corp_title,
            'post_content'  => '<!-- wp:paragraph -->\n<p>' . $corp_desc . '</p>\n<!-- /wp:paragraph -->',
            'post_status'   => 'publish',
            'post_type'     => 'page'
        ));
        echo "[OK] Halaman dibuat: \"{$corp_title}\" (ID: {$post_id})\n";
    } else {
        echo "[INFO] Halaman \"{$corp_title}\" sudah ada.\n";
    }
}

echo "\nSetup selesai dengan sukses!\n";
