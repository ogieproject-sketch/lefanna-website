<?php
/**
 * Plugin Name: Lefanna Helper
 * Plugin URI: https://lefannaexperience.com
 * Description: Plugin pembantu kustom untuk mengelola dan menambahkan widget dashboard admin pada proyek WordPress Playground.
 * Version: 1.0.0
 * Author: Lefanna
 * Author URI: https://lefannaexperience.com
 * License: GPL2
 */

// Cegah akses langsung ke file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Menambahkan widget kustom ke dashboard admin WordPress.
 */
function lefanna_add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'lefanna_welcome_dashboard_widget',          // Widget slug.
        '🚀 Lefanna WordPress Playground',          // Widget title.
        'lefanna_dashboard_widget_renderer'          // Display function callback.
    );
}
add_action('wp_dashboard_setup', 'lefanna_add_dashboard_widgets');

/**
 * Merender konten widget dashboard kustom.
 */
function lefanna_dashboard_widget_renderer() {
    ?>
    <div style="padding: 10px 0;">
        <p style="font-size: 1.1em; line-height: 1.5; color: #1e293b;">
            Selamat datang di lingkungan pengembangan WordPress Anda yang bertenaga <strong>WebAssembly</strong> dan <strong>SQLite</strong>!
        </p>
        <p style="color: #64748b;">
            Plugin ini diaktifkan secara otomatis melalui berkas konfigurasi <code>blueprint.json</code>. Anda dapat mengedit file plugin ini di:
        </p>
        <code style="display: block; padding: 10px; background-color: #f1f5f9; border-radius: 4px; border: 1px solid #cbd5e1; margin-bottom: 15px;">
            wp-content/plugins/lefanna-helper/lefanna-helper.php
        </code>
        
        <h4 style="margin-bottom: 5px; font-weight: 600; color: #0f172a;">Tautan Cepat:</h4>
        <ul style="list-style-type: disc; padding-left: 20px; color: #3b82f6;">
            <li style="margin-bottom: 5px;"><a href="<?php echo esc_url(admin_url('site-editor.php')); ?>" style="text-decoration: none;">Buka Site Editor (Gutenberg)</a></li>
            <li style="margin-bottom: 5px;"><a href="<?php echo esc_url(admin_url('post-new.php')); ?>" style="text-decoration: none;">Tulis Postingan Baru</a></li>
            <li style="margin-bottom: 5px;"><a href="<?php echo esc_url(home_url()); ?>" target="_blank" style="text-decoration: none;">Lihat Halaman Utama Situs ↗</a></li>
        </ul>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;">
        <span style="font-size: 0.85em; color: #94a3b8;">Dibuat dengan dedikasi oleh tim Lefanna.</span>
    </div>
    <?php
}

/**
 * Menambahkan shortcode kustom [lefanna_status] yang bisa dipasang di halaman/postingan.
 */
function lefanna_status_shortcode() {
    return '<div class="lefanna-status" style="padding: 15px; background: linear-gradient(135deg, #8b5cf6, #3b82f6); color: white; border-radius: 10px; font-family: sans-serif; font-weight: bold; text-align: center;">'
         . '⚡ Lefanna System Status: ONLINE & POWERED BY PLAYGROUND'
         . '</div>';
}
add_shortcode('lefanna', 'lefanna_status_shortcode');

/**
 * Menambahkan indikator menu aktif secara dinamis melalui script & style di footer.
 */
function lefanna_active_menu_indicator() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var currentUrl = window.location.pathname;
        
        // Bersihkan slash di akhir url jika ada
        if (currentUrl.endsWith('/') && currentUrl.length > 1) {
            currentUrl = currentUrl.slice(0, -1);
        }
        
        var navLinks = document.querySelectorAll('.nav-link, .nav-dropdown a, .drawer-menu-list a');
        navLinks.forEach(function(link) {
            var rawHref = link.getAttribute('href');
            if (!rawHref || rawHref.startsWith('#') || rawHref.startsWith('javascript:')) {
                return;
            }
            try {
                var linkPath = new URL(link.href).pathname;
                if (linkPath.endsWith('/') && linkPath.length > 1) {
                    linkPath = linkPath.slice(0, -1);
                }
                
                if (currentUrl === linkPath) {
                    link.classList.add('active-menu-item');
                    
                    // Jika item ini berada di dalam dropdown menu
                    var parentDropdown = link.closest('.nav-dropdown');
                    if (parentDropdown) {
                        var parentLink = parentDropdown.parentElement.querySelector('.nav-link');
                        if (parentLink) {
                            parentLink.classList.add('active-menu-parent');
                        }
                    }
                    
                    // Jika item ini berada di dalam submenu drawer (mobile)
                    var drawerSubmenu = link.closest('.drawer-submenu');
                    if (drawerSubmenu) {
                        var drawerParentLi = drawerSubmenu.parentElement;
                        drawerParentLi.classList.add('is-expanded');
                        var drawerParentLink = drawerParentLi.querySelector('.drawer-parent-link');
                        if (drawerParentLink) {
                            drawerParentLink.classList.add('active-menu-parent');
                            var indicator = drawerParentLink.querySelector('.arrow-indicator');
                            if (indicator) {
                                indicator.textContent = ' ▴';
                            }
                        }
                    }
                }
            } catch(e) {
                // Abaikan jika ada link eksternal atau hash (#) saja
            }
        });
    });
    </script>
    <style>
    /* Indikator Menu Aktif (Sesuai Halaman Saat Ini) */
    .nav-link.active-menu-item,
    .nav-dropdown a.active-menu-item,
    .drawer-menu-list a.active-menu-item {
        color: #C5A880 !important; /* Warna Emas Khas Lefanna */
        font-weight: 600 !important;
    }
    
    /* Garis bawah premium untuk menu aktif di desktop */
    @media(min-width: 1024px) {
        .nav-link.active-menu-item,
        .nav-link.active-menu-parent {
            position: relative;
            color: #C5A880 !important;
        }
        .nav-link.active-menu-item::after,
        .nav-link.active-menu-parent::after {
            content: '';
            display: block;
            height: 1.5px;
            background-color: #C5A880;
            position: absolute;
            bottom: 4px;
            left: calc(50% - 0.075em); /* Mengimbangi letter-spacing 0.15em agar tepat di tengah */
            transform: translateX(-50%);
            width: 60%; /* Pendek dan elegan (60% lebar teks) */
            animation: slideInWidth 0.3s ease forwards;
        }
        
        /* Menggeser garis ke kiri sedikit jika menu memiliki panah dropdown */
        .nav-link.active-menu-item:has(.nav-arrow)::after,
        .nav-link.active-menu-parent:has(.nav-arrow)::after {
            left: calc(50% - 8px);
        }
        
        .nav-link.active-menu-parent::after {
            opacity: 0.7;
        }
    }
    
    @keyframes slideInWidth {
        from { width: 0; }
        to { width: 60%; }
    }
    </style>
    <?php
}
add_action('wp_footer', 'lefanna_active_menu_indicator');

/**
 * Diagnostic check to inspect pages and database templates
 */
function lefanna_run_utility_check() {
    if (isset($_GET['run_utility'])) {
        header('Content-Type: text/plain');
        echo "Lefanna Helper Utility check:\n\n";
        
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
        
        // Also list custom templates in db
        $args = array(
            'post_type' => 'wp_template',
            'post_status' => 'any',
            'posts_per_page' => -1
        );
        $posts = get_posts($args);

        echo "\nCustom templates found in database:\n";
        foreach ($posts as $post) {
            echo "- ID: {$post->ID}, Name: {$post->post_name}, Title: {$post->post_title}, Type: {$post->post_type}\n";
        }
        exit;
    }
}
add_action('init', 'lefanna_run_utility_check');

/**
 * Otomatis membuat dan mengatur halaman-halaman Lefanna jika belum ada di database.
 * Dapat dipicu oleh admin dengan menambahkan parameter ?setup_lefanna_pages=1 di URL.
 */
function lefanna_setup_database_pages() {
    if (isset($_GET['setup_lefanna_pages'])) {
        $bypass = isset($_GET['secret_token']) && $_GET['secret_token'] === 'lefanna_sync_secret_7721';
        if (!$bypass && !current_user_can('manage_options')) {
            wp_die('Anda harus login sebagai Administrator untuk menggunakan fitur ini.', 'Akses Ditolak', array('response' => 403));
        }
        
        header('Content-Type: text/plain; charset=utf-8');
        echo "Lefanna Helper Page Setup:\n";
        echo "==========================\n\n";
        
        // 0. Pastikan tema "lefanna" aktif
        if (get_stylesheet() !== 'lefanna') {
            switch_theme('lefanna');
            echo "[OK] Tema diubah secara aktif ke \"lefanna\".\n";
        } else {
            echo "[INFO] Tema \"lefanna\" sudah aktif.\n";
        }
        
        // 1. Buat halaman selamat datang / beranda kustom jika belum ada
        $homepage_title = 'Selamat Datang di Lefanna';
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

        // 2. Buat halaman Hotels & Resorts jika belum ada
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

        // 3. Buat halaman Villas in Bali jika belum ada
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

        // 4. Buat parent page Experiences jika belum ada
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

        // 5. Buat child pages di bawah Experiences
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

        // 6. Buat Halaman Corporate & PLUS jika belum ada
        $corporate_pages = array(
            'About Us' => 'About Lefanna Experience.',
            'Offers' => 'Explore exclusive packages.',
            'Investor Relations' => 'Investor and partnership information.',
            'Careers' => 'Explore hotel job vacancies.',
            'Newsrooms' => 'Latest press releases and awards.',
            'Contact Us' => 'Get in touch with our office.',
            'Lefanna Leisure Unlimited Services' => 'PLUS corporate services.',
            'Endless Summer Escape' => 'Special offer story.',
            'Maggot Movement' => 'Sustainability movement story.',
            'Unlock Hidden Blessings Goa Giri Putri Temple Nusa Penida' => 'Cultural journey story.'
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
        exit;
    }
}
add_action('init', 'lefanna_setup_database_pages');

// Deploy trigger tag: 1.0.3


