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
