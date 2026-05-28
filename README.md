# Sistem Manajemen Qurban

Aplikasi web berbasis Laravel untuk membantu panitia qurban (Idul Adha) mengelola alur kerja dari kehadiran pekurban hingga pengambilan bagian. Cocok digunakan oleh masjid, sekolah, pesantren, atau organisasi Islam lainnya.

**Gratis, open source, silahkan dikembangkan lebih lanjut.**

---

## Fitur Utama

### Alur Kerja Domba
```
Kehadiran → Kandang → Sembelih → Seset → Pengambilan
```

### Alur Kerja Sapi
```
Kehadiran → Checklist Sapi (lengkap) → Pengambilan
```

| Fitur | Keterangan |
|---|---|
| Registrasi Kehadiran | Absensi pekurban + penyerahan tagging, generate kode QR otomatis |
| Notifikasi WhatsApp | Kirim kode & link journey ke pekurban via WhatsApp API atau manual |
| Public Journey Page | Halaman tanpa login untuk pekurban pantau status qurbannya |
| Checklist Kandang | Ambil domba, foto hidup, OTW sembelih |
| Checklist Sembelih | Video sembelih, foto sembelih, OTW seset |
| Checklist Seset | Mulai seset, bagian pekurban, kesesuaian bagian, OTW pengambilan |
| Checklist Sapi | Foto hidup, video sembelih, mulai seset, kesesuaian bagian, OTW pengambilan |
| Checklist Pengambilan | Verifikasi bagian siap diambil + konfirmasi sudah diambil |
| Import Excel | Upload data pekurban & hewan dari spreadsheet |
| Laporan Sembelih | Rekap per kelompok hewan dengan progress bar |
| Manajemen User | Multi-user dengan role admin & operator |
| Log Aktivitas | Audit trail semua perubahan checklist |
| Pengaturan | Toggle WhatsApp API on/off tanpa ubah kode |

---

## Teknologi

- **Backend**: Laravel 13, PHP 8.3
- **Database**: SQLite (default, zero-config) / MySQL (produksi)
- **Frontend**: Bootstrap 5.3.3 + Bootstrap Icons 1.11.3 (CDN, tanpa Node.js)
- **Import Excel**: [maatwebsite/excel](https://laravel-excel.com)
- **WhatsApp**: Integrasi via REST API (opsional)

---

## Instalasi

### Kebutuhan

- PHP 8.3+
- Composer
- Extension PHP: `pdo`, `pdo_sqlite` (atau `pdo_mysql` untuk MySQL)

### Langkah Cepat

```bash
# 1. Clone repositori
git clone <url-repo> qurban
cd qurban

# 2. Install dependensi
composer install

# 3. Buat file konfigurasi
cp .env.example .env
php artisan key:generate

# 4. Jalankan migrasi (SQLite otomatis dibuat)
php artisan migrate

# 5. Buat akun admin pertama
php artisan tinker
>>> App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'role'=>'admin'])

# 6. Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

### Menggunakan MySQL (Produksi)

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qurban
DB_USERNAME=root
DB_PASSWORD=secret
```

Kemudian jalankan:
```bash
php artisan migrate
```

---

## Konfigurasi

### Identitas Organisasi

Edit file `app/Services/WhatsAppService.php` pada method `buildPesan()` untuk menyesuaikan nama panitia dan pesan notifikasi:

```php
// Ubah baris ini sesuai nama organisasi Anda
".:: Panitia Qurban KAF Pusat Depok 1447H ::."
```

### WhatsApp API (Opsional)

Aplikasi mendukung pengiriman notifikasi otomatis via WhatsApp API. Tambahkan ke `.env`:

```env
WHATSAPP_API_URL=https://your-wa-api-server/send-message
WHATSAPP_API_KEY=your-api-key
WHATSAPP_SESSION=your-session-id
```

> API yang digunakan mengikuti format: `POST` dengan body `{ sessionId, chatId, message, typingTime }` dan header `X-Api-Key`. Sesuaikan `WhatsAppService.php` jika provider Anda berbeda.

Jika tidak punya WhatsApp API, fitur **WA Manual** tetap tersedia — tombol akan membuka WhatsApp dengan pesan yang sudah diisi otomatis.

Toggle WhatsApp API bisa dilakukan kapan saja di menu **Admin → Pengaturan** tanpa perlu ubah kode atau restart server.

---

## Import Data Hewan

Format Excel yang diterima (kolom minimal):

| nomor_urut | jenis | nama_hewan | nama_pekurban | nomor_wa | keterangan |
|---|---|---|---|---|---|
| D-001 | domba | Unyil | Ahmad Fauzi | 08123456789 | |
| S-001/1 | sapi | Makmur | Budi Santoso | 08198765432 | titip ojek |

- **jenis**: `domba` atau `sapi`
- **nomor_wa**: format bebas (08xx atau +628xx), akan dinormalisasi otomatis
- **keterangan**: opsional, tampil di checklist pengambilan

Download template Excel di menu **Import Excel → Download Template**.

---

## Struktur Halaman

```
/                          Dashboard ringkasan progres
/journey                   Public — cari status qurban by kode
/journey/{kode}            Public — detail journey pekurban (tanpa login)

/checklist/kehadiran       Registrasi kehadiran pekurban
/checklist/kandang         Checklist kandang (domba)
/checklist/sembelih        Checklist sembelih (domba)
/checklist/seset           Checklist seset (domba)
/checklist/sapi            Checklist lengkap sapi
/checklist/pengambilan     Checklist pengambilan bagian

/laporan/sembelih          Laporan rekap sembelih per kelompok hewan

/users                     Manajemen user (admin only)
/logs                      Log aktivitas (admin only)
/settings                  Pengaturan sistem (admin only)
```

---

## Role Pengguna

| Role | Akses |
|---|---|
| **Admin** | Semua fitur + manajemen user + log + pengaturan |
| **Operator** | Semua checklist, laporan, import data |

Tambah user baru melalui menu **Admin → Manajemen User**.

---

## Struktur Database

```
hewan                    Data pekurban & hewan
checklist_kehadiran      Absensi + penyerahan tagging + kode QR
checklist_kandang        Checklist kandang (domba)
checklist_sembelih       Checklist sembelih (domba)
checklist_seset          Checklist seset (domba)
checklist_sapi           Checklist lengkap (sapi)
checklist_pengambilan    Checklist pengambilan bagian
settings                 Konfigurasi runtime (toggle WA, dll)
activity_logs            Audit trail seluruh perubahan
users                    Akun pengguna
```

---

## Deployment ke Shared Hosting / VPS

```bash
# Di server
git clone <url-repo> qurban
cd qurban
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env sesuaikan APP_URL, DB_*, dll
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

Pastikan `APP_URL` di `.env` sesuai domain Anda agar link journey yang dikirim via WhatsApp benar.

```env
APP_URL=https://qurban.namamasjid.org
APP_ENV=production
APP_DEBUG=false
```

---

## Pengembangan Lebih Lanjut

Beberapa ide fitur yang bisa ditambahkan:

- **QR Code Scanner** — scan kode pekurban saat pengambilan
- **Laporan lengkap** — export PDF/Excel rekap keseluruhan
- **Notifikasi tahap lanjut** — WA otomatis saat sembelih/OTW pengambilan
- **Multi-panitia / multi-event** — mendukung beberapa tahun/lokasi
- **Dashboard realtime** — auto-refresh tanpa reload halaman
- **Foto upload** — lampirkan foto hewan langsung dari checklist

---

## Lisensi

MIT License — bebas digunakan, dimodifikasi, dan didistribusikan untuk keperluan apapun.

---

> Dibuat untuk membantu kelancaran pelaksanaan ibadah qurban. Semoga bermanfaat.
> Jazakumullahu khairan katsiran.
