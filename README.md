# helpdesk-ticketing
Aplikasi web untuk manajemen tiket support internal perusahaan. Dibangun dengan **Laravel 12** dan **PostgreSQL**.
# 🎫 Helpdesk Ticketing System

Aplikasi web untuk manajemen tiket support internal perusahaan. Dibangun dengan **Laravel 12** dan **PostgreSQL**.

---

## 📋 Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database-postgresql)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Akun Default](#akun-default)
- [Cara Menggunakan](#cara-menggunakan)
- [Struktur Database](#struktur-database)
- [Troubleshooting](#troubleshooting)
- [Lisensi](#lisensi)

---

## ✨ Fitur

### 👤 User (Employee)
- Membuat tiket baru dengan **nomor unik otomatis** (format: `TCK-YYYYMMDD-XXXX`)
- Status awal tiket: **Open**
- Melihat daftar tiket milik sendiri
- Melihat detail tiket dan **history log** lengkap

### 🛠️ IT Support
- Melihat **semua tiket** dengan filter:
  - Status (`Open`, `On Progress`, `Resolved`, `Closed`)
  - Tanggal dibuat (dari – sampai)
  - Kategori
  - Prioritas (`Low`, `Medium`, `High`)
- Mengubah status tiket sesuai workflow:

  - Memberikan **catatan** setiap kali mengubah status
- Menambah catatan tambahan dan **lampiran file** (max 5MB)

### 📜 Ticket History (Log)
- Setiap perubahan (status update, catatan, upload file) tercatat di tabel `ticket_logs`
- History menampilkan waktu, user, aksi, dan detail perubahan

---

## 🧰 Teknologi

| Komponen | Versi / Nama |
|----------|--------------|
| PHP | 8.2+ |
| Laravel | 12.x |
| Database | PostgreSQL 14+ |
| Frontend | Bootstrap 5 (CDN) |
| Templating | Blade |

---

## 🚀 Instalasi

### 1. Clone Repository

git clone https://github.com/darrennicholas/helpdesk-ticketing
cd helpdesk-ticketing
### 2. Install Dependencies PHP
composer install
### 3. Environment Configuration
# Windows
copy .env.example .env

# Linux / Mac
cp .env.example .env
php artisan key:generate

### 4. Konfigurasi Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=helpdesk_db
DB_USERNAME=postgres
DB_PASSWORD=password_anda

SESSION_DRIVER=file
Catatan: Pastikan database helpdesk_db sudah dibuat di PostgreSQL.
Jika belum, buka psql atau pgAdmin dan jalankan:
CREATE DATABASE helpdesk_db;

### 5. Jalankan Migrasi & Seeder
php artisan migrate --seed
Seeder akan membuat:

6 kategori: Hardware, Software, Network, Email, Access Rights, Other

2 akun default: Employee dan IT Support

### 6. Storage Link (untuk file upload)
php artisan storage:link

### 7. Jalankan Server
php artisan serve

## 🔐 Akun Default

Setelah menjalankan seeder (`php artisan migrate --seed`), berikut adalah akun yang tersedia:

| Role | Email | Password |
|------|-------|----------|
| **Employee** (User) | `employee@helpdesk.com` | `password123` |
| **IT Support** | `support@helpdesk.com` | `password123` |

> Pendaftaran akun baru melalui halaman `/register` akan menghasilkan user dengan role `employee` secara otomatis.

---

## 🗄️ Struktur Database Schema & Relasi Antar Tabel

### Tabel `users`
Menyimpan data pengguna (Employee dan IT Support).

| Kolom | Tipe Data | Keterangan |
|-------|-----------|-------------|
| `id` | BIGINT (PK) | Auto increment, primary key |
| `name` | VARCHAR(255) | Nama lengkap |
| `email` | VARCHAR(255) | Unik, digunakan untuk login |
| `password` | VARCHAR(255) | Hash bcrypt |
| `role` | ENUM('employee','support') | Peran pengguna, default 'employee' |
| `remember_token` | VARCHAR(100) | Token "remember me" |
| `created_at` | TIMESTAMP | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Waktu update |

### Tabel `categories`
Menyimpan kategori tiket.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|-------------|
| `id` | BIGINT (PK) | Auto increment, primary key |
| `name` | VARCHAR(255) | Nama kategori (Hardware, Software, dll) |
| `slug` | VARCHAR(255) | URL friendly, unique |
| `created_at` | TIMESTAMP | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Waktu update |

### Tabel `tickets`
Menyimpan data tiket.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|-------------|
| `id` | BIGINT (PK) | Auto increment, primary key |
| `ticket_no` | VARCHAR(255) | Nomor tiket unik (TCK-YYYYMMDD-XXXX) |
| `user_id` | BIGINT (FK) | ID pembuat tiket (relasi ke `users.id`) |
| `category_id` | BIGINT (FK) | ID kategori (relasi ke `categories.id`) |
| `subject` | VARCHAR(255) | Judul tiket |
| `description` | TEXT | Deskripsi keluhan |
| `status` | ENUM | `Open`, `On Progress`, `Resolved`, `Closed` – default `Open` |
| `priority` | ENUM | `Low`, `Medium`, `High` – default `Medium` |
| `created_at` | TIMESTAMP | Waktu pembuatan tiket |
| `updated_at` | TIMESTAMP | Waktu update |

### Tabel `ticket_logs`
Menyimpan history log setiap perubahan pada tiket.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|-------------|
| `id` | BIGINT (PK) | Auto increment, primary key |
| `ticket_id` | BIGINT (FK) | ID tiket yang terkait (relasi ke `tickets.id`) |
| `user_id` | BIGINT (FK) | ID user yang melakukan perubahan (relasi ke `users.id`) |
| `action` | VARCHAR(255) | Jenis aksi: `created`, `status_updated`, `note_added`, `file_uploaded` |
| `old_status` | VARCHAR(255) | Status sebelum perubahan |
| `new_status` | VARCHAR(255) | Status setelah perubahan |
| `note` | TEXT | Catatan perubahan |
| `attachment` | VARCHAR(255) | Path file lampiran |
| `created_at` | TIMESTAMP | Waktu log |
| `updated_at` | TIMESTAMP | Waktu update |

### 🔗 Relasi Antar Tabel

| Relasi | Jenis | Foreign Key | Penjelasan |
|--------|-------|-------------|-------------|
| `users` → `tickets` | **One to Many** | `tickets.user_id` → `users.id` | Satu user dapat membuat banyak tiket |
| `categories` → `tickets` | **One to Many** | `tickets.category_id` → `categories.id` | Satu kategori dapat digunakan oleh banyak tiket |
| `tickets` → `ticket_logs` | **One to Many** | `ticket_logs.ticket_id` → `tickets.id` | Satu tiket memiliki banyak history log |
| `users` → `ticket_logs` | **One to Many** | `ticket_logs.user_id` → `users.id` | Satu user dapat membuat banyak log |

### ⚙️ Constraint & Aturan

- **CASCADE DELETE** pada `tickets.user_id` – jika user dihapus, semua tiket dan log-nya ikut terhapus
- **RESTRICT DELETE** pada `tickets.category_id` – kategori tidak bisa dihapus jika masih ada tiket yang menggunakannya
- **CASCADE DELETE** pada `ticket_logs.ticket_id` – jika tiket dihapus, semua history log-nya ikut terhapus
- **CASCADE DELETE** pada `ticket_logs.user_id` – jika user dihapus, semua log yang dibuatnya ikut terhapus


## 📖 Cara Menggunakan

### 👤 Sebagai Employee

1. **Login** dengan akun Employee (`employee@helpdesk.com` / `password123`).
2. Pada navbar, klik **New Ticket**.
3. Isi form tiket:
   - Pilih **Kategori** (Hardware, Software, dll)
   - Masukkan **Subjek** (judul keluhan)
   - Tulis **Deskripsi** lengkap
   - Pilih **Prioritas** (Low, Medium, High)
   - (Opsional) Lampirkan file (max 5MB)
4. Klik **Submit Ticket**.
5. Tiket baru akan muncul di halaman **My Tickets** dengan status `Open`.
6. Klik **View** untuk melihat detail tiket dan **History Log** perubahan.

---

### 🛠️ Sebagai IT Support

1. **Login** dengan akun IT Support (`support@helpdesk.com` / `password123`).
2. Buka menu **All Tickets**.
3. Gunakan **filter** untuk mencari tiket yang diinginkan:
   - Status (`Open`, `On Progress`, `Resolved`, `Closed`)
   - Kategori
   - Prioritas
   - Rentang tanggal (Date From – Date To)
4. Klik tombol **Process** pada tiket yang akan ditangani.
5. Pada halaman detail tiket, tersedia dua aksi:

   #### 🔄 Update Status
   - Tombol akan menampilkan **status selanjutnya** sesuai workflow:
     `Open → On Progress → Resolved → Closed`
   - Tambahkan **catatan** (wajib untuk dokumentasi, misal: *"Sedang dicek ke user"*)
   - (Opsional) Lampirkan file
   - Klik **Update Status**

   #### 📝 Add Note
   - Tambahkan **catatan tambahan** tanpa mengubah status
   - (Opsional) Lampirkan file
   - Klik **Add Note**

6. Setiap perubahan akan langsung terekam di **History Log** pada bagian bawah halaman.

## 📁 Migration Files

Semua migration file dapat diakses di folder `database/migrations/`.  
Berikut daftar file yang digunakan:

| File | Fungsi |
|------|--------|
| `2025_01_01_000001_create_users_table.php` | Membuat tabel `users` |
| `2025_01_01_000002_create_categories_table.php` | Membuat tabel `categories` |
| `2025_01_01_000003_create_tickets_table.php` | Membuat tabel `tickets` |
| `2025_01_01_000004_create_ticket_logs_table.php` | Membuat tabel `ticket_logs` |

Untuk menjalankan migration (termasuk seeder):

php artisan migrate --seed


## 🔐 Dua Akun Default untuk Login Langsung

Setelah menjalankan `php artisan migrate --seed`, tersedia dua akun yang dapat langsung digunakan:

| Role | Email | Password |
|------|-------|----------|
| **Employee** (User) | `employee@helpdesk.com` | `password123` |
| **IT Support** | `support@helpdesk.com` | `password123` |

Cukup buka halaman `/login` dan masukkan kredensial di atas.

---

## 📝 Cara Register Akun Baru

1. Kunjungi halaman `/register`.
2. Isi formulir:
   - `Name` – nama lengkap
   - `Email` – alamat email unik
   - `Password` – minimal 6 karakter
   - `Confirm Password` – ulangi password
3. Klik tombol **Register**.
4. Setelah berhasil, Anda akan **otomatis login** dan diarahkan ke halaman `My Tickets` (daftar tiket milik Anda).

---

## ❗ Role yang Bisa Dipilih Saat Register

**Tidak ada pilihan role.**  
Setiap pendaftaran baru akan secara otomatis diberikan role **`employee`** (User biasa).

---

## 🔍 Mengapa Tidak Bisa Memilih Role & Langsung Menjadi User?

Alasan keamanan dan segregasi akses:

1. **Perbedaan hak akses**  
   - `employee` hanya bisa membuat tiket, melihat tiket sendiri, dan melihat history-nya.  
   - `support` memiliki akses penuh ke **semua tiket**, dapat mengubah status, menambahkan catatan, dan menggunakan filter.  
   Memberikan opsi pilih role saat register akan memungkinkan siapa saja mendaftar sebagai `support` dan menyalahgunakan wewenang.

2. **Prinsip least privilege**  
   Setiap pengguna baru seharusnya hanya memiliki hak minimal yang diperlukan. Role `employee` adalah hak minimal untuk menggunakan sistem ticketing. Role `support` harus diberikan secara intentional oleh administrator (misal melalui database atau seeder).

3. **Audit dan akuntabilitas**  
   Menetapkan role `support` melalui jalur terkontrol (hanya admin/database) memudahkan pelacakan siapa yang memiliki akses istimewa.

> 💡 Jika memang diperlukan akun support tambahan, dapat dibuat melalui **seeder** atau **Tinker**:
> ```bash
> php artisan tinker
> ```
> ```php
> $user = new App\Models\User;
> $user->name = 'Support Baru';
> $user->email = 'support2@helpdesk.com';
> $user->password = bcrypt('rahasia');
> $user->role = 'support';
> $user->save();
> ```

Dengan mekanisme ini, sistem tetap aman, akun baru otomatis menjadi user biasa, dan role support tetap eksklusif.
