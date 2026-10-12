# Laravel Product CRUD Application

Aplikasi web manajemen produk (CRUD) sederhana berbasis **Laravel 12** dengan styling **Tailwind CSS** dan bundler **Vite**. Aplikasi ini mendukung pengelolaan produk (judul, deskripsi, harga, stok) beserta upload gambar produk.

---

## 📋 Prasyarat Sistem (Requirements)

Sebelum memulai instalasi, pastikan sistem Anda telah terpasang:

- **PHP** >= 8.3 (dengan ekstensi: `pdo`, `pdo_sqlite` / `pdo_mysql`, `mbstring`, `fileinfo`, `gd` atau `imagick`, `curl`)
- **Composer** (versi 2.x)
- **Node.js** (versi 18.x atau lebih baru) & **NPM**
- **Git**
- **Database** (SQLite bawaan/default atau MySQL / MariaDB)

---

## 🚀 Panduan Instalasi (Step-by-Step)

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek di komputer lokal:

### 1. Clone Repository

Unduh kode sumber proyek dari GitHub:

```bash
git clone https://github.com/Jen-Seime/latihan1.git
cd latihan1
```

---

### 2. Install Dependensi PHP (Composer)

Jalankan perintah berikut untuk mengunduh semua library PHP yang dibutuhkan:

```bash
composer install
```

---

### 3. Duplikasi File Environment (`.env`)

Salin template konfigurasi `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

*Untuk pengguna Windows (Command Prompt):*
```cmd
copy .env.example .env
```

---

### 4. Generate Application Key

Buat kunci enkripsi aplikasi Laravel:

```bash
php artisan key:generate
```

---

### 5. Konfigurasi Database

Secara default, Laravel telah dikonfigurasi menggunakan **SQLite**.

#### Opsi A: Menggunakan SQLite (Default & Paling Praktis)
Pastikan di file `.env` terdapat:
```env
DB_CONNECTION=sqlite
```
Jika file `database/database.sqlite` belum ada, buat file tersebut:
```bash
touch database/database.sqlite
```

#### Opsi B: Menggunakan MySQL
Jika ingin menggunakan MySQL, buka file `.env` lalu sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=password_anda
```
*(Pastikan Anda telah membuat database di MySQL terlebih dahulu).*

---

### 6. Jalankan Migrasi Database

Eksekusi migrasi untuk membuat tabel yang dibutuhkan (termasuk tabel `products`):

```bash
php artisan migrate
```

*(Opsional)* Jika ingin menambahkan data seed bawaan:
```bash
php artisan migrate --seed
```

---

### 7. Buat Symbolic Link Storage (Penting untuk Upload Gambar)

Aplikasi ini mengunggah gambar produk ke penyimpanan publik (`storage/app/public/products`). Anda wajib membuat symlink agar gambar dapat diakses di browser:

```bash
php artisan storage:link
```

---

### 8. Install & Build Dependensi Frontend (Node/NPM)

Install paket Node.js untuk Tailwind CSS & Vite:

```bash
npm install
```

Build aset frontend untuk mode produksi:
```bash
npm run build
```

---

## 🖥️ Menjalankan Aplikasi

Anda dapat menjalankan server lokal dengan salah satu cara berikut:

### Cara 1: Menjalankan Server & Vite Terpisah
Buka dua terminal terpisah:

**Terminal 1 (Laravel Server):**
```bash
php artisan serve
```

**Terminal 2 (Vite Hot-Reload untuk Development):**
```bash
npm run dev
```

### Cara 2: Menjalankan Sekaligus (Composer Script)
```bash
composer run dev
```

Aplikasi sekarang dapat diakses melalui browser di:
👉 **[http://localhost:8000](http://localhost:8000)** atau langsung ke daftar produk di **[http://localhost:8000/products](http://localhost:8000/products)**

---

## 📌 Fitur Aplikasi & Daftar Rute

Aplikasi menyediakan fitur lengkap CRUD Produk:

| Rute URL | Metode HTTP | Deskripsi |
| :--- | :--- | :--- |
| `/` | `GET` | Halaman utama / welcome |
| `/products` | `GET` | Menampilkan seluruh daftar produk (dengan paginasi) |
| `/products/create` | `GET` | Formulir tambah produk baru + upload gambar |
| `/products` | `POST` | Menyimpan data produk baru ke database |
| `/products/{id}` | `GET` | Menampilkan detail spesifik produk |
| `/products/{id}/edit`| `GET` | Formulir ubah data produk & ubah gambar |
| `/products/{id}` | `PUT/PATCH` | Memperbarui data produk |
| `/products/{id}` | `DELETE` | Menghapus data produk beserta filenya |

---

## 💡 Troubleshooting Umum

- **Gambar Produk Tidak Tampil (404 Not Found):**
  Pastikan perintah `php artisan storage:link` sudah berhasil dijalankan, dan nilai `APP_URL` pada `.env` sudah sesuai (misal: `APP_URL=http://localhost:8000`).
- **Izin Folder (Khusus Linux / macOS):**
  Jika terjadi kendala izin tulis (*permission denied*), beri hak akses pada folder storage dan cache:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```
- **Tampilan CSS Berantakan:**
  Jalankan `npm run build` atau pastikan `npm run dev` sedang berjalan di terminal Anda.

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan latihan dan pembelajaran, berlisensi di bawah [MIT License](LICENSE).
