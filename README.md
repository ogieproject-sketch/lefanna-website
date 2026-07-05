# Proyek WordPress Lokal (WordPress Playground)

Proyek ini adalah template pengembangan WordPress lokal yang dirancang khusus untuk berjalan langsung menggunakan Node.js tanpa memerlukan instalasi global untuk PHP, MySQL, Apache/Nginx, atau Docker.

Lingkungan ini ditenagai oleh **WordPress Playground CLI** yang menggunakan WebAssembly (Wasm) untuk menjalankan PHP di Node.js, dengan basis data berbasis SQLite.

## Persyaratan Sistem

- **Node.js** (Versi 20 atau lebih baru)
- **npm** (Bawaan dari Node.js)

## Cara Menjalankan Proyek

Buka terminal di direktori ini (`d:/lefanna2`) dan jalankan skrip berikut:

### 1. Menjalankan Server Pengembangan
Untuk memulai server WordPress:
```bash
npm run dev
```
Perintah ini akan secara otomatis:
- Menjalankan server lokal WordPress di port acak (biasanya port `8080`).
- Membuka browser Anda secara otomatis dan masuk ke halaman Dashboard Admin (`/wp-admin/`).
- Memuat konfigurasi otomatis dari `blueprint.json` (termasuk mengaktifkan tema dan plugin kustom).

**Catatan Login:**
Anda akan langsung masuk sebagai administrator. Jika Anda perlu login manual, gunakan kredensial berikut:
- **Username:** `admin`
- **Password:** `password`

### 2. Mengatur Ulang Situs (Reset Database)
Jika Anda ingin menghapus semua data, postingan, dan pengaturan di database untuk memulai kembali dari awal:
```bash
npm run reset
```

---

## Struktur Direktori Kustom

Anda dapat langsung mengembangkan tema dan plugin secara lokal melalui folder berikut:

### 1. Tema Kustom (`wp-content/themes/antigravity-theme/`)
Ini adalah **WordPress Block Theme (FSE)** modern dan minimalis. Anda dapat memodifikasi file berikut untuk menyesuaikan tampilan:
- `style.css`: Berisi informasi metadata tema.
- `theme.json`: Mengontrol konfigurasi global CSS, palet warna, tipografi, dan gaya blok Gutenberg.
- `templates/index.html`: Berisi template block HTML untuk halaman utama/beranda.

### 2. Plugin Kustom (`wp-content/plugins/antigravity-helper/`)
Ini adalah plugin pembantu sederhana untuk menyisipkan kode PHP kustom:
- `antigravity-helper.php`: Digunakan untuk menambahkan hooks (actions & filters), custom post types, atau fitur backend kustom lainnya.

---

## Fitur Unggulan WordPress Playground

- **Zero Dependency:** Cukup jalankan perintah npm, server langsung siap.
- **SQLite Database:** Semua data disimpan di SQLite local state secara persisten di folder direktori user Anda (`~/.wordpress-playground/sites/`).
- **Instant Activation:** Setiap kali dijalankan ulang, tema `antigravity-theme` dan plugin `antigravity-helper` akan otomatis aktif secara bawaan melalui skrip di `blueprint.json`.
