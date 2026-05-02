# 🎬 PRODUCT REQUIREMENTS DOCUMENT (PRD)
## StreamVid – Web Streaming Film Berbasis Subscription

---

## 1. 📌 Product Overview

**Nama Produk:** StreamVid  
**Jenis:** Website Video Streaming (Fullstack)  
**Framework:** Laravel 12  
**Database:** MySQL (Laragon)  

### 🎯 Deskripsi
StreamVid adalah platform streaming film berbasis web yang memungkinkan pengguna menonton film dengan sistem berlangganan (subscription), serta menyediakan dashboard admin untuk pengelolaan konten dan pengguna.

---

## 2. 🎯 Objectives

- Membangun platform streaming modern (mirip Netflix)
- Mengimplementasikan sistem subscription
- Mengelola konten film secara terpusat
- Mengintegrasikan frontend dan backend dalam satu sistem

---

## 3. 👥 User Roles

### 👤 User (Pengguna)
- Melihat katalog film
- Melihat detail film
- Bookmark film (My List)
- Berlangganan
- Menonton film (jika aktif subscription)

### 🧑‍💼 Admin
- Mengelola film
- Mengelola user
- Mengelola subscription
- Export laporan
- Monitoring sistem

---

## 4. 🧭 User Flow

### 🔓 Guest
1. Masuk ke Home
2. Klik film → diarahkan login
3. Login / Register

### 👤 User
1. Login → Home
2. Klik detail film
3. Jika belum subscribe → diarahkan ke subscription
4. Jika sudah → bisa play
5. Tambah ke My List

### 🧑‍💼 Admin
1. Login
2. Redirect ke dashboard admin
3. Kelola sistem

---

## 5. 🧱 Features & Functional Requirements

---

### 🏠 5.1 Home Page

#### Komponen:
- Navbar
- Hero Section
- Film Catalog
- Footer

#### Hero Section:
- Judul film
- Sinopsis
- Genre (multi)
- Tahun rilis
- Tombol:
  - Tonton Sekarang
  - Detail Film

#### Behavior:
- Belum login → redirect login saat klik tombol
- Film ditampilkan random
- Tambahan:
  - Featured Film
  - Trending Section

---

### 🎞️ 5.2 Film Catalog

- Grid:
  - 2 baris × 5 kolom
- Pagination jika >10 film
- Random berdasarkan genre

#### Tambahan:
- 🔍 Search (judul film)
- 🎯 Filter:
  - Genre
  - Tahun rilis

---

### 🔐 5.3 Authentication

- Register
- Login
- Logout

#### Navbar:
- Guest → Login | Sign Up  
- User → Profile | Logout  

#### Redirect:
- User → Home  
- Admin → Dashboard  

---

### 🎥 5.4 Films Page

- Menampilkan semua film
- Filter:
  - Genre
  - Tahun
- Pagination
- Search

---

### 📄 5.5 Detail Film

#### Hero:
- Judul
- Genre
- Durasi
- Tahun

#### Aksi:
- ❌ Belum subscribe → tombol play disabled
- ✅ Sudah subscribe → bisa play

#### Rekomendasi:
- 1 baris × 5 film
- Berdasarkan:
  - Genre
  - Film populer

---

### 💳 5.6 Subscription

| Paket | Harga |
|------|------|
| Basic | 15.000 |
| Standard | 29.000 |
| Premium | 49.000 |

#### Komponen:
- Card paket
- Perbandingan fitur
- Tombol pilih

---

### 💰 5.7 Payment (Simulasi)

#### Catatan:
Tidak menggunakan pembayaran asli (mock payment)

#### Flow:
1. Pilih paket
2. Halaman konfirmasi
3. Klik "Bayar"
4. Sistem langsung sukses

#### UI:
- Ringkasan:
  - Nama user
  - Paket
  - Harga
- Metode:
  - Transfer bank
  - E-wallet
  - QRIS

#### Behavior:
- Insert ke database
- Update subscription → aktif
- Redirect ke success page

#### UX Tambahan:
- Loading 2–3 detik
- Fake Invoice ID

---

### ✅ 5.8 Payment Success

- Pesan berhasil
- Info paket
- Tombol "Mulai Nonton"

---

### ⭐ 5.9 My List

- Film favorit
- Bisa tambah / hapus
- Grid layout

---

### ⏱️ 5.10 Watch History (Tambahan)

- Film terakhir ditonton
- Timestamp (opsional)

---

### ⭐ 5.11 Rating System (Opsional)

- Rating 1–5
- Like / Favorite

---

### 🔔 5.12 Notifikasi

- Status subscription
- Reminder expired

---

## 🧑‍💼 5.13 Admin Dashboard

### Layout:
- Sidebar
- Content area

---

### 📊 Dashboard
- Total user
- Total subscriber
- Total film
- Revenue (simulasi)

---

### 🎬 Manage Film
- CRUD film:
  - Judul
  - Deskripsi
  - Genre
  - Tahun
  - Durasi
  - Thumbnail
  - Video URL

---

### 👥 Manage Users
- List user
- Status:
  - Free
  - Basic
  - Standard
  - Premium

---

### 💳 Manage Subscription
- Status aktif / expired
- Riwayat

---

### 📄 Reports
- Export PDF:
  - User
  - Film
  - Subscription

---

### ⚙️ Settings (Opsional)
- Ganti banner
- Featured film
- Logo

---

## 6. 🗄️ Database Design

### users
- id
- name
- email
- password
- role

### films
- id
- title
- description
- genre
- duration
- release_year
- thumbnail
- video_url

### subscriptions
- id
- user_id
- package
- start_date
- end_date
- status

### payments
- id
- user_id
- subscription_id
- method
- amount
- status

### my_list
- id
- user_id
- film_id

### watch_history
- id
- user_id
- film_id
- watched_at

---

## 7. 🧩 Tech Stack

- Backend: Laravel 12  
- Frontend: Blade + Tailwind CSS  
- Database: MySQL  
- Auth: Laravel Breeze  
- PDF: DomPDF  

---

## 8. 🎨 UI/UX Guidelines

- Dark mode (Netflix style)
- Responsive design
- Card layout
- Hover effect
- Fokus thumbnail

---

## 9. 🔒 Access Control

| Fitur | Guest | User | Admin |
|------|------|------|------|
| Lihat film | ✅ | ✅ | ✅ |
| Detail film | ❌ | ✅ | ✅ |
| Play film | ❌ | Subscription | ✅ |
| My List | ❌ | ✅ | ❌ |
| Dashboard | ❌ | ❌ | ✅ |

---

## 10. 🛡️ Security & Middleware

- Middleware:
  - auth
  - role (admin/user)
- Proteksi:
  - Admin route tidak bisa diakses user
  - Film hanya bisa diputar jika subscription aktif

---

## 11. 🧪 Validasi

- Email unik
- Password minimal 8 karakter
- Input form wajib valid
- Payment simulasi (success / failed)

---

## 12. ⚠️ Edge Cases

- User klik play tanpa subscription → redirect
- Subscription expired → blok akses
- Duplicate email
- Session timeout

---

## 13. 📊 Logging (Opsional)

- Login user
- Subscribe
- Nonton film

---

## 14. 🚀 Future Improvements

- AI recommendation
- Multi-device streaming
- Review system
- Search lebih advanced

---

## 15. 📌 Kesimpulan

StreamVid adalah sistem streaming fullstack dengan:
- Role-based system (user & admin)
- Subscription model
- Payment simulasi
- Dashboard admin lengkap
- UX mendekati real product
