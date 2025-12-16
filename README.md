# SiPustaka — Layanan Busa Pustaka (CI4 + PWA)

Aplikasi peminjaman buku berbasis **Progressive Web App (PWA)** menggunakan **CodeIgniter 4**, **Bootstrap 5**, dan **JavaScript**.  
Fitur utama: katalog buku, detail buku + pengaturan tanggal peminjaman/pengembalian, akun user (Shield), admin dashboard, kelola buku, data peminjaman, upload cover buku & foto profil.

---

## 1) Kebutuhan Sistem

### Minimal

- PHP **8.1+**
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx) atau `php spark serve`

### Ekstensi PHP yang direkomendasikan

- intl, mbstring, json, mysqlnd, curl

---

## 2) Cara Clone Project dari GitHub

```bash
git clone https://github.com/USERNAME/NAMA_REPO.git
cd NAMA_REPO
```

---

## 3) Install Dependency (Composer)

```bash
composer install
```

> Folder `vendor/` akan dibuat otomatis setelah perintah ini.

---

## 4) Konfigurasi Environment (.env)

Di root project, buat file `.env` dari `env`:

**Windows (PowerShell)**

```powershell
copy env .env
```

**Linux/Mac**

```bash
cp env .env
```

Lalu buka `.env` dan atur minimal bagian ini:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = sipustaka
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
```

> Jika pakai Laragon/XAMPP dan port berbeda, sesuaikan `app.baseURL`.

---

## 5) Setup Database

### Opsi A (Direkomendasikan): Pakai Migration + Seeder

1. Buat database kosong di MySQL/MariaDB:

```sql
CREATE DATABASE sipustaka;
```

2. Jalankan migration:

```bash
php spark migrate
```

3. Jalankan seeder (data contoh):

```bash
php spark db:seed DatabaseSeeder
```

✅ Selesai. Database terisi data contoh (user + buku + contoh peminjaman).

---

### Opsi B: Import Database dari file `.sql`

Jika project menyediakan file SQL (mis. `sipustaka.sql`), kamu bisa import manual.

1. Buat database:

```sql
CREATE DATABASE sipustaka;
```

2. Import lewat phpMyAdmin:

- Buka phpMyAdmin → pilih database `sipustaka`
- Menu **Import** → pilih file `sipustaka.sql` → klik **Go**

Atau import lewat terminal:

```bash
mysql -u root -p sipustaka < sipustaka.sql
```

> Jika sudah import SQL, kamu **tidak perlu** migrate+seed lagi (kecuali memang ingin update struktur terbaru).

---

## 6) Menjalankan Aplikasi

### Opsi 1: Pakai Built-in Server CI4

```bash
php spark serve
```

Akses:

- `http://localhost:8080/`

### Opsi 2: Pakai Apache/Laragon/XAMPP (Disarankan)

Pastikan document root mengarah ke:

```
PROJECT/public
```

Akses:

- `http://sipustaka.test/` atau `http://localhost/sipustaka/public`

> CI4 memakai `public/index.php` sebagai entrypoint. Jadi **lebih aman** jika server mengarah ke folder `public`.

---

## 7) Akun Default (Jika Pakai Seeder)

Jika kamu menjalankan `DatabaseSeeder`, contoh akun:

- **Admin**

  - Username: `admin`
  - Password: `admin12345`

- **User**
  - Username: `andri`
  - Password: `user12345`

> Catatan: untuk Shield, email tersimpan di tabel `auth_identities`.

---

## 8) Perintah Penting (Pengembangan)

### Reset total database (HATI-HATI: menghapus data)

```bash
php spark migrate:refresh
php spark db:seed DatabaseSeeder
```

### Membuat migration baru

```bash
php spark make:migration NamaMigration
```

### Membuat seeder baru

```bash
php spark make:seeder NamaSeeder
```

---

## 9) Upload File (Cover Buku & Foto Profil)

Folder upload ada di:

- `public/uploads/covers/` (cover buku)
- `public/uploads/avatars/` (foto profil)

Jika folder belum ada, buat manual:

```bash
mkdir -p public/uploads/covers public/uploads/avatars
```

> Umumnya folder `public/uploads/` tidak disimpan di GitHub (ada di `.gitignore`) karena berisi file pengguna.

---

## 10) Catatan Tentang PWA

File penting PWA ada di:

- `public/manifest.webmanifest`
- `public/sw.js`

Pastikan PWA berjalan dengan HTTPS di production, atau minimal `localhost` saat development.

---

## 11) Troubleshooting

### A) URL masih ada `index.php`

Pastikan server mengarah ke folder `public/` atau `.htaccess`/rewrite aktif.

### B) Cover buku tidak tampil

Cek:

- kolom `books.cover` terisi nama file
- file ada di `public/uploads/covers/`
- form upload memakai `enctype="multipart/form-data"`

### C) Login/Register tidak ikut layout

Karena CI4 Shield memakai view bawaan, pastikan konfigurasi Shield diarahkan ke layout aplikasi (jika kamu melakukan override).

---

## 12) Lisensi

Project ini dibuat untuk kebutuhan pembelajaran/pengembangan. Silakan sesuaikan bagian lisensi sesuai kebutuhan.
