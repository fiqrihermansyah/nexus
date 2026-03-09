# DTM Nexus — Realtime Administrative Memo Management System

Dashboard modern untuk mengelola permintaan data memo internal. Dibangun dengan Laravel 11, TailwindCSS, dan AlpineJS.

---

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade + TailwindCSS + AlpineJS
- **Database**: MySQL
- **Charts**: Chart.js
- **Auth**: Laravel Session Auth

---

## Cara Install

### 1. Clone & Install Dependencies
```bash
git clone <repo-url> dtmnexus
cd dtmnexus
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai database lokal:
```env
DB_DATABASE=nexus_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Buat Database & Migrate
```bash
# Buat database di MySQL:
mysql -u root -p -e "CREATE DATABASE nexus_db;"

# Jalankan migrasi + seeder:
php artisan migrate --seed
```

### 4. Jalankan Server
```bash
php artisan serve
```

Buka http://localhost:8000

---

## Default Login

| Role  | Email                | Password |
|-------|----------------------|----------|
| Admin | admin@nexus.id    | password |
| User  | ezra@nexus.id     | password |

---

## Fitur Utama

### Dashboard
- Summary cards: Total Memo, Pending, Done, Discard
- Bar chart aktivitas memo 12 bulan terakhir
- Status distribution progress bar
- Tabel 5 memo terbaru

### Memo Request (Full CRUD)
- Tabel dengan search, filter status/kategori, filter tanggal
- Sort by tanggal
- Export CSV
- Badge status berwarna (Pending/On Progress/Done/Discard)
- Delete confirmation dialog

### Detail Memo
- Semua informasi lengkap
- Timeline aktivitas (dibuat, status berubah, update)

### User Management (Admin only)
- CRUD user via modal
- Role: admin / user

### UI/UX
- Toast notification untuk setiap aksi CRUD
- Modal form edit & delete confirmation
- Responsive: mobile, tablet, desktop
- Sidebar navigasi dengan active state indicator

---

## Struktur Folder

```
app/
├── Http/Controllers/
│   ├── Auth/LoginController.php
│   ├── DashboardController.php
│   ├── MemoController.php
│   └── UserController.php
├── Models/
│   ├── MemoRequest.php
│   ├── MemoActivity.php
│   └── User.php
database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_memo_requests_table.php
│   └── create_memo_activities_table.php
└── seeders/
    └── DatabaseSeeder.php
resources/views/
├── layouts/app.blade.php
├── auth/login.blade.php
├── dashboard/index.blade.php
├── memo/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── users/index.blade.php
routes/
├── web.php
└── api.php
```

---

## API Endpoints

Tersedia jika menggunakan Sanctum token:

```
GET    /api/memo          List semua memo
POST   /api/memo          Buat memo baru
GET    /api/memo/{id}     Detail memo
PUT    /api/memo/{id}     Update memo
DELETE /api/memo/{id}     Hapus memo
```

---

## Warna Tema

| Token          | Value    |
|----------------|----------|
| Primary        | #006747  |
| Primary Light  | #00855c  |
| Accent         | #E6F4EF  |
| Sidebar        | #0a0f0d  |

---

## Konfigurasi Tambahan (Optional)

### Pusher / Laravel Echo (Realtime)
Tambahkan di `.env`:
```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

---

## License
Internal use only — © 2026 DTM Nexus
