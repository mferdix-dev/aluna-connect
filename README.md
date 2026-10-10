# Aluna Connect - Modern Social Media Platform

Aplikasi Sosial Media yang telah dimodernisasi dari PHP Native (XAMPP/MySQL) menjadi **Laravel 13** modern dengan arsitektur MVC, desain UI modern & minimalis (responsif untuk desktop & mobile), **Dark Mode otomatis berbasis jam komputer**, **Pesan/Chat dengan polling**, **Sistem Alert & Notifikasi Otomatis**, serta dilengkapi rangkaian pengujian otomatis (**47 Unit & Feature Tests**).

## Lampiran post & popup pesan

- Di desktop, tombol **Pesan** kanan bawah (atau tautan pesan pada profil/sidebar/notifikasi) membuka panel mengambang di atas halaman yang sedang dibaca, tanpa pindah halaman. Di ponsel panel menutupi layar dan dapat ditutup untuk kembali ke halaman semula. Panel tersedia pada halaman aplikasi setelah login; layar login dan register tidak menampilkannya. Pesan masuk diperiksa berkala (polling 2,5 detik), bukan melalui WebSocket.
- Pengaturan **Otomatis / Terang / Gelap** sekarang ada di halaman **Pengaturan Aplikasi** (`/settings`). Preferensi disimpan di perangkat lewat `localStorage`; mode otomatis mengikuti jam lokal perangkat.
- Form post mendukung sampai **10 lampiran** gambar (JPEG, PNG, WebP, GIF) dan video (MP4, WebM, MOV), maksimal **20 MB per file**. Post lama dengan satu gambar tetap dapat ditampilkan.
- File baru disimpan pada disk `public` Laravel. Setelah instalasi baru, jalankan `php artisan storage:link` dan `php artisan migrate`; jangan gunakan `migrate:fresh` pada database berisi data penting.
- Untuk video, PHP perlu `upload_max_filesize=20M` dan `post_max_size=210M` atau lebih. Server lain mungkin mempunyai batas HTTP tersendiri.
- Uji dengan `php artisan test` (47 tests passed).

---

## 🚀 Fitur Utama

### 1. 🌙 Dark Mode Otomatis (Menyesuaikan Jam Komputer)
- **Otomatis Tanpa Konfigurasi**: Secara default mendeteksi jam lokal pada komputer:
  - **Pukul 18:00 - 06:00 (Malam)**: Tampilan otomatis beralih ke **Mode Gelap (Dark Mode)** yang ramah mata.
  - **Pukul 06:00 - 18:00 (Siang)**: Tampilan otomatis beralih ke **Mode Terang (Light Mode)**.
- **Pengaturan Tema**: Pilih Otomatis, Gelap, atau Terang di halaman Pengaturan Aplikasi; preferensi tersimpan di *localStorage*.
- **Bebas Flicker**: Script inisialisasi tema instan di `<head>` mencegah kedipan putih saat berpindah halaman.

### 2. 💬 Real-Time Messaging & Chat 1-on-1
- **Daftar Kontak Percakapan**: Menampilkan daftar pesan terakhir, jumlah pesan belum dibaca (*unread count*), dan shortcut kontak komunitas.
- **Ruang Obrolan Interaktif**:
  - Tampilan bubble chat modern ala aplikasi chatting terkini (biru gradient untuk pesan sendiri, slate untuk pesan masuk).
  - Pengiriman pesan asinkron via AJAX tanpa memuat ulang (*reload*) halaman.
  - **Polling Real-Time**: Pesan baru dari lawan bicara otomatis muncul dalam hitungan detik.
  - Penandaan status terbaca (*auto mark as read*) saat percakapan dibuka.

### 3. 🔔 Alert & Notifikasi Real-Time (Pop-up Toast)
- **Pemberitahuan Otomatis Saat Ada Aktivitas**:
  - ❤️ Ketika seseorang menyukai (*Like*) postingan Anda.
  - 💬 Ketika seseorang mengomentari postingan Anda.
  - ✉️ Ketika seseorang mengirim pesan obrolan baru.
- **Floating Toast Alert**: Banner alert melayang di pojok kanan atas muncul otomatis saat ada aktivitas baru lengkap dengan avatar pengirim dan tautan langsung ke postingan/obrolan terkait.
- **Badge Counter Dinamis**: Angka notifikasi dan pesan pada navbar/sidebar otomatis diperbarui secara live.
- **Halaman Notifikasi**: Halaman `/notifications` untuk melihat rekam jejak aktivitas dan tombol "Tandai Semua Dibaca".

### 4. 📱 Desain UI Modern, Minimalis & Responsif
- **Desktop**: Layout 3 kolom (Sidebar navigasi elegan, Feed utama di tengah, Widget pencarian dan daftar obrolan cepat di kanan).
- **Mobile**: Navigasi bawah (*Bottom Navigation Bar*) fleksibel untuk pengalaman satu tangan yang nyaman di smartphone.
- **Micro-Interactions**: Tombol Like asinkron, Live Image Preview saat posting/komentar, salin tautan postingan.

### 5. 🛡️ Backend Arsitektur Modern Laravel 13
- **Autentikasi Aman**: Login dengan Username atau Email, password di-hash dengan Bcrypt.
- **Role & Otorisasi**: Pengguna reguler dan Administrator sistem (dengan akses moderasi & hapus).
- **Database Siap Pakai (SQLite Default)**: Tidak membutuhkan Apache/MySQL dari XAMPP, langsung jalan seketika! *(Dukungan MySQL tetap tersedia melalui konfigurasi `.env`)*.

---

## 🔑 Akun Demo Siap Pakai

Semua akun berikut telah di-seed ke dalam database:

| Tipe Akun | Username | Password | Keterangan |
|---|---|---|---|
| **Admin** | `useradmin` | `admin123` | Administrator sistem |
| **User** | `user123` | `password123` | Akun pengembang / tester |

*(Tersedia tombol satu-klik di halaman login untuk mengisi akun demo secara otomatis)*

---

## 💻 Cara Menjalankan Aplikasi

1. Clone repositori:
   ```bash
   git clone https://github.com/mferdix-dev/aluna-connect.git
   cd aluna-connect
   ```

2. Salin environment file & pasang dependensi:
   ```bash
   cp .env.example .env
   composer install
   php artisan key:generate
   ```

3. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

4. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

5. Buka di browser:
   👉 **`http://127.0.0.1:8000`**

---

## 🧪 Rangkaian Unit & Feature Testing

Aplikasi ini dilengkapi pengujian otomatis (**47 Test Cases & 151 Assertions**, 100% Passed):
- `AuthTest`: Pengujian login (username/email), register, logout, proteksi rute, dan reset password.
- `PostTest`: Pengujian pembuatan postingan, upload file/media, filter pencarian, dan hak hapus author/admin.
- `CommentTest`: Pengujian tambah komentar, hapus komentar sendiri, dan proteksi otorisasi.
- `LikeTest`: Pengujian toggle like/unlike dengan respons JSON.
- `MessageTest`: Pengujian inbox pesan, pengiriman pesan, pembuatan notifikasi pesan, dan polling real-time.
- `NotificationTest`: Pengujian auto-trigger notifikasi saat post di-like/comment, API unread count, dan mark all as read.
- `ProfileTest`: Pengujian tampilan profil publik dan pembaruan data pengguna.
- `SocialModelTest`: Pengujian helper model, relasi, avatar fallback, dan generator kode unik.

Untuk menjalankan seluruh tes:
```bash
php artisan test
```
