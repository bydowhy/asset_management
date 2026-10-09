# Asset Management System

Sistem manajemen aset pabrik kertas berbasis web. Mengelola **equipment**,
**asset** (komponen), **failure**, **dokumen**, dan **foto** — dengan fokus
pada **historical tracking** penggantian asset dan relationship fungsional.

**Version**: 1.0.0  
**Tech Stack**: Laravel 13 + Vue 3 + Inertia + Tailwind CSS 4 + shadcn-vue

---

## 🎯 Fitur Utama

### Equipment Management
- Daftar equipment dengan filter lokasi (recursive) dan search
- Detail equipment dengan 4 tab: **Current Assets**, **History**,
  **Documents**, **Photos**
- **Install / Replace / Remove asset** langsung dari halaman equipment
- Auto-set status asset (active/inactive) saat install/replace/remove
- Auto-transfer relationship fungsional saat replace

### Asset Management
- Daftar asset dengan filter lokasi/type/status/manufacturer + search
- Detail asset dengan 6 tab: **Overview**, **Specifications**,
  **Relationships**, **Failures**, **Documents**, **Photos**
- **Dynamic specifications** per asset type (motor: power/voltage/rpm,
  pump: capacity/head/fluid, dst.)
- **Functional relationships** antar-asset (time-bound, auto-transfer)

### Failure Management
- Pencatatan failure per asset
- Filter by asset, type, date range
- Edit hanya oleh creator atau admin

### Documents & Photos
- Upload dokumen (PDF, DOCX, XLSX, PPTX, max 20 MB)
- Upload foto (JPG, PNG, max 10 MB)
- Polymorphic link ke equipment atau asset
- Photo lightbox preview (grid 8 kolom desktop)
- Smart redirect (upload dari equipment detail tetap di halaman itu)

### Dashboard
- Stat cards: Total Equipment, Total Assets, Active Assets, Failures (30d)
- Recent Failures widget
- Equipment Attention widget (90d, hanya failure setelah instalasi terakhir)

### Master Data (Admin Only)
- Locations (hierarki parent-child)
- Asset Types + dynamic specifications editor
- Relationship Types
- Document Types
- Users management
- Audit Logs

### Security
- Role-based access control (admin / user)
- Audit logging untuk semua aksi penting
- Private file storage (dokumen & foto)

---

## 🛠️ Requirements

- **PHP** 8.4+ (dengan ekstensi: pdo_mysql, mbstring, openssl, tokenizer)
- **MySQL** 8.0+ (atau MariaDB 10.6+)
- **Node.js** 20+ dan npm 10+
- **Composer** 2.x
- **Web Server**: Nginx atau Apache (atau `php artisan serve` untuk dev)

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/bydowhy/asset_management.git
cd asset_management
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME="Asset Management"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asset_management
DB_USERNAME=root
DB_PASSWORD=

# Untuk file upload (default: local/private)
FILESYSTEM_DISK=local
```

### 4. Setup Database

Buat database di MySQL:

```sql
CREATE DATABASE asset_management
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Jalankan migration + seeder:

```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Aplikasi

**Development mode** (dua terminal):

```bash
# Terminal 1 — Vite dev server
npm run dev

# Terminal 2 — Laravel
php artisan serve
```

Akses di `http://localhost:8000`.

**Production mode**:

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔑 Default Credentials

Setelah seeder dijalankan:

| Role | Username | Password | Akses |
|------|----------|----------|-------|
| **Admin** | `admin` | `password` | Full access |
| **User** | `maint` | `password` | Operasional (tanpa master data) |

> ⚠️ **Ganti password default** sebelum deploy ke production.

---

## 📁 Struktur Project

```
app/
├── Http/Controllers/     # Controllers
├── Http/Requests/        # Form validation
├── Models/               # Eloquent models (17 tabel)
├── Policies/             # Authorization
├── Services/             # Business logic
└── Support/              # Helpers (EntityLinkResolver)

resources/js/
├── components/           # UI components
│   ├── ui/               # shadcn-vue base
│   ├── AssetCombobox.vue # Combobox cari asset
│   ├── PhotoLightbox.vue # Preview foto
│   └── Pagination.vue    # Pagination reusable
├── layouts/              # App layouts
├── pages/                # Inertia pages (per modul)
└── types/                # TypeScript types

routes/
├── web.php               # Semua route

database/
├── migrations/           # Schema
└── seeders/              # Sample data
```

Untuk detail lengkap, lihat [`PROJECT_CONTEXT.md`](./PROJECT_CONTEXT.md).

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Atau pakai Pest
./vendor/bin/pest

# Code style check
composer lint:check

# Static analysis
composer types:check
```

Test suite mencakup 143 skenario (Blok 1–15) dari unit test hingga
end-to-end integration.

---

## 📚 Dokumentasi

| File | Isi |
|------|-----|
| [`PROJECT_CONTEXT.md`](./PROJECT_CONTEXT.md) | Konteks lengkap project (untuk AI assistant) |
| [`CHANGELOG.md`](./CHANGELOG.md) | Riwayat perubahan per versi |
| [`README.md`](./README.md) | File ini — setup & overview |

---

## 🤝 Kontribusi

Project ini internal (pabrik). Untuk kontribusi:

1. Buat branch fitur: `git checkout -b feature/nama-fitur`
2. Commit dengan pesan deskriptif
3. Push ke branch
4. Buat Pull Request

### Code Style
- PHP: Laravel Pint (`composer lint`)
- TypeScript/Vue: Vue TSC + ESLint via `vp check`
- Migration: gunakan UUID `CHAR(36)` untuk PK baru
- Model: `HasUuids`, `$incrementing = false`, `$keyType = 'string'`

---

## 📄 License

Proprietary — internal use only.
