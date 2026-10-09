# Asset Management System — Project Context

> **Paste file ini di awal sesi baru** saat berkonsultasi dengan AI assistant
> agar konteks project dapat dipahami tanpa perlu menjelaskan ulang dari awal.

---

## 1. Overview

Sistem manajemen aset pabrik kertas berbasis web. Mengelola **equipment**,
**asset** (komponen), **failure**, **dokumen**, dan **foto**. Fokus utama pada
**historical tracking** penggantian asset — siapa terpasang di mana, kapan,
dan relationship fungsional antar-asset.

| Item | Value |
|------|-------|
| **Current Version** | 1.0.0 |
| **Status** | Production-ready (V1 locked) |
| **Repository** | `https://github.com/bydowhy/asset_management` |
| **Bahasa** | Indonesia (UI), English (code) |

---

## 2. Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 13.17, PHP 8.4 |
| Frontend | Vue 3.5, Inertia 3.0, Tailwind CSS 4.1, shadcn-vue (reka-ui) |
| Database | MySQL 8.0+ |
| Auth | Laravel Fortify 1.37 (username + password, bukan email) |
| File Storage | Laravel Storage disk `local` (private) |
| Toast | vue-sonner 2.0 |
| Icons | @lucide/vue 1.53 |
| State | Vue Composition API + `useForm` dari Inertia |
| Build | Vite 8, vue-tsc |

**Dependencies utama (composer.json):**
```
laravel/framework: ^13.17
inertiajs/inertia-laravel: ^3.0
laravel/fortify: ^1.37
laravel/wayfinder: ^0.1
```

**Dependencies utama (package.json):**
```
@inertiajs/vue3: ^3.0
vue: ^3.5
tailwindcss: ^4.1
reka-ui: ^2.11
vue-sonner: ^2.0
@lucide/vue: ^1.53
```

---

## 3. Struktur Database (Schema V2)

**Total 17 tabel** + tabel sistem Laravel (sessions, cache, jobs, dll).

### Tabel Utama

| Tabel | Deskripsi |
|-------|-----------|
| `locations` | Hierarki lokasi (parent-child, self-referencing FK) |
| `equipment` | Unit fisik (mis. P-201 Fan Pump) |
| `assets` | Komponen (mis. MTR-0001 Motor) |
| `asset_types` | Master tipe asset (PUMP, MOTOR, INVERTER, dll.) |
| `asset_type_definitions` | Definisi spesifikasi dinamis per tipe asset |
| `asset_specifications` | Nilai spesifikasi per asset (key-value dinamis) |
| `equipment_assets` | History instalasi asset ↔ equipment (time-bound) |
| `asset_relationships` | Relasi fungsional antar-asset (time-bound) |
| `relationship_types` | Master tipe relationship (DRIVES, CONTROLS, dll.) |
| `failures` | Riwayat failure per asset |
| `documents` | Metadata dokumen |
| `document_types` | Master tipe dokumen (DATA_SHEET, MANUAL, dll.) |
| `document_links` | Polymorphic link dokumen → equipment/asset |
| `photos` | Metadata foto |
| `photo_links` | Polymorphic link foto → equipment/asset |
| `users` | User (role: admin / user) |
| `audit_logs` | Log semua aksi penting |

### Detail Kolom Kunci

**`locations`**
```sql
id CHAR(36) PK
parent_id CHAR(36) NULL FK → locations.id
name VARCHAR(100)
code VARCHAR(50) UNIQUE
```

**`equipment`**
```sql
id CHAR(36) PK
location_id CHAR(36) FK → locations.id
tag VARCHAR(50) UNIQUE      -- mis. P-201
name VARCHAR(150)
equipment_type VARCHAR(50)
```

**`assets`**
```sql
id CHAR(36) PK
asset_code VARCHAR(50) UNIQUE   -- mis. MTR-0001
asset_type_id CHAR(36) FK → asset_types.id
manufacturer VARCHAR(100)
model VARCHAR(100)
serial_number VARCHAR(100)
status VARCHAR(20) CHECK (status IN ('active','inactive','scrapped'))
```

**`equipment_assets`** (history instalasi)
```sql
id CHAR(36) PK
equipment_id CHAR(36) FK
asset_id CHAR(36) FK
relationship_role VARCHAR(50)   -- pump / motor / inverter
installed_at DATETIME
removed_at DATETIME NULL         -- NULL = masih terpasang
UNIQUE(equipment_id, asset_id, installed_at)
CHECK (removed_at IS NULL OR removed_at >= installed_at)
```

**`asset_relationships`** (relasi fungsional)
```sql
id CHAR(36) PK
source_asset_id CHAR(36) FK → assets.id
target_asset_id CHAR(36) FK → assets.id
relationship_type_id CHAR(36) FK → relationship_types.id
valid_from DATETIME
valid_to DATETIME NULL           -- NULL = relasi aktif
UNIQUE(source_asset_id, target_asset_id, relationship_type_id, valid_from)
CHECK (valid_to IS NULL OR valid_to >= valid_from)
```

**`asset_specifications`**
```sql
id CHAR(36) PK
asset_id CHAR(36) FK
definition_id CHAR(36) FK → asset_type_definitions.id
value TEXT
UNIQUE(asset_id, definition_id)
```

**`asset_type_definitions`**
```sql
id CHAR(36) PK
asset_type_id CHAR(36) FK
name VARCHAR(100)         -- "Power", "Voltage", dsb.
code VARCHAR(50)          -- "power", "voltage" (lowercase)
data_type VARCHAR(20)     -- decimal | integer | varchar | boolean | text
unit VARCHAR(50) NULL     -- "kW", "V", "rpm"
is_required BOOLEAN
sort_order INT
UNIQUE(asset_type_id, code)
```

**`failures`**
```sql
id CHAR(36) PK
asset_id CHAR(36) FK → assets.id
failure_date DATETIME
failure_type VARCHAR(100)
symptom TEXT
root_cause TEXT
action_taken TEXT
downtime_hours DECIMAL(10,2)
created_by CHAR(36) FK → users.id
```

**`users`**
```sql
id CHAR(36) PK
username VARCHAR(255) UNIQUE
name VARCHAR(255)
email VARCHAR(255) UNIQUE
password VARCHAR(255)
department VARCHAR(255) NULL
role VARCHAR(20)          -- admin | user | guest
email_verified_at TIMESTAMP NULL
two_factor_secret TEXT NULL
remember_token VARCHAR(100) NULL
created_at / updated_at
```

**`audit_logs`**
```sql
id CHAR(36) PK
user_id CHAR(36) FK → users.id
action VARCHAR(50)        -- create | update | delete | login | logout
entity_type VARCHAR(50)   -- equipment | asset | failure | dll.
entity_id CHAR(36) NULL
description TEXT
ip_address VARCHAR(45)
created_at DATETIME
```

---

## 4. Business Rules Kunci

Aturan-aturan ini **tidak bisa dipaksakan oleh FK/constraint** dan divalidasi
di **application layer** (Service classes):

| # | Rule | Lokasi Validasi |
|---|------|-----------------|
| BR-1 | Satu asset tidak boleh aktif di 2 equipment sekaligus | `AssetInstallationService::install/replace` |
| BR-2 | Satu equipment tidak boleh punya 2 asset aktif dengan role sama | `AssetInstallationService::install` |
| BR-3 | Asset `scrapped` tidak boleh diinstall | `AssetInstallationService::ensureNotScrapped` |
| BR-4 | Source dan target relationship tidak boleh sama | `AssetRelationshipService::create` |
| BR-5 | Kombinasi (source, target, type, valid_from) unik | FormRequest + DB unique |
| BR-6 | `valid_to` >= `valid_from` | DB CHECK + Service |
| BR-7 | `removed_at` >= `installed_at` | DB CHECK + Service |
| BR-8 | Install/Replace → status asset baru = `active` | `AssetInstallationService` |
| BR-9 | Remove/Replace → status asset lama = `inactive` | `AssetInstallationService` |
| BR-10 | Replace asset → auto-transfer relationship fungsional | `AssetRelationshipService::transferActiveFrom` |
| BR-11 | Remove asset → auto-close semua relationship aktif | `AssetRelationshipService::closeAllActiveFor` |
| BR-12 | Spec `is_required=true` wajib diisi | `AssetSpecificationService::sync` |
| BR-13 | Definition spec harus milik asset type yang sama | `AssetSpecificationService::sync` |
| BR-14 | Delete master data di-restrict jika masih direferensikan | Controller `destroy` masing-masing |
| BR-15 | Hanya admin yang bisa akses 6 halaman master data | Middleware `admin` + `AppSidebar` |

---

## 5. Role & Permission

| Halaman | Admin | User |
|---------|:-----:|:----:|
| Dashboard | ✅ | ✅ |
| Equipment | ✅ | ✅ |
| Assets | ✅ | ✅ |
| Failures | ✅ | ✅ |
| Documents | ✅ | ✅ |
| Photos | ✅ | ✅ |
| Locations | ✅ | ❌ |
| Asset Types | ✅ | ❌ |
| Relationship Types | ✅ | ❌ |
| Document Types | ✅ | ❌ |
| Users | ✅ | ❌ |
| Audit Logs | ✅ | ❌ |

**Implementasi:**
- **Sidebar**: `AppSidebar.vue` filter menu via `computed` berdasarkan `auth.user.role`.
- **Backend**: Middleware `EnsureUserIsAdmin` (alias `admin`) di `bootstrap/app.php`.

---

## 6. Keputusan Desain Penting

### 6.1 Kenapa `equipment_assets` (pivot table) bukan `equipment.motor_id`?

Untuk **historical tracking** penggantian asset. Dengan tabel pivot, kita bisa
menjawab "Motor apa yang terpasang di P-201 pada 2025-01-01?" dan "Motor apa
saja yang pernah terpasang di P-201?" — pertanyaan yang tidak bisa dijawab
kalau hanya simpan `motor_id` di tabel equipment.

### 6.2 Kenapa `asset_specifications` dinamis?

Setiap asset type punya spesifikasi berbeda:
- **Motor**: power, voltage, current, frequency, rpm
- **Pump**: capacity, head, speed, fluid
- **Inverter**: power, input_voltage, output_voltage, control_mode

Kalau pakai kolom fixed di tabel `assets`, kita harus bikin kolom untuk semua
kemungkinan (boros) atau ubah schema setiap ada asset type baru. Dengan
`asset_type_definitions` + `asset_specifications`, schema stabil.

### 6.3 Kenapa relationship time-bound (`valid_from` / `valid_to`)?

Untuk historical "inverter mana yang drive motor ini pada tanggal X". Saat
replace inverter, kita tidak hapus relasi lama — kita tutup (`valid_to`) dan
buat baru. History tetap terjaga.

### 6.4 Kenapa polymorphic links tidak pakai FK?

Tabel `document_links` dan `photo_links` bisa menunjuk ke `equipment` **atau**
`asset`. MySQL tidak mendukung FK polymorphic. Integritas dijaga di application
layer via `EntityLinkResolver`.

### 6.5 Kenapa auto-transfer relationship saat replace?

Karena secara fungsional, inverter baru yang menggantikan inverter lama
seharusnya otomatis drive motor yang sama. User tidak perlu setup ulang.
Kalau perlu pengecualian, user bisa `End` relationship manual setelah replace.

---

## 7. Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/          # Semua controller
│   │   ├── AssetController.php
│   │   ├── AssetRelationshipController.php
│   │   ├── AssetSpecificationController.php
│   │   ├── AssetTypeController.php
│   │   ├── AssetTypeDefinitionController.php
│   │   ├── AuditLogController.php
│   │   ├── Auth/             # Fortify-based auth
│   │   ├── DashboardController.php
│   │   ├── DocumentController.php
│   │   ├── DocumentTypeController.php
│   │   ├── EquipmentAssetController.php
│   │   ├── EquipmentController.php
│   │   ├── FailureController.php
│   │   ├── LocationController.php
│   │   ├── PhotoController.php
│   │   ├── RelationshipTypeController.php
│   │   └── UserController.php
│   ├── Middleware/
│   │   └── EnsureUserIsAdmin.php
│   └── Requests/             # FormRequest validasi
├── Models/                   # 17 model Eloquent (HasUuids, no timestamps)
├── Policies/                 # Authorization
├── Services/                 # Business logic
│   ├── AssetInstallationService.php
│   ├── AssetRelationshipService.php
│   ├── AssetService.php
│   ├── AssetSpecificationService.php
│   ├── AssetTypeDefinitionService.php
│   ├── AuditLogService.php
│   ├── EquipmentService.php
│   └── MediaService.php
└── Support/
    └── EntityLinkResolver.php

resources/js/
├── components/
│   ├── ui/                   # shadcn-vue base components
│   ├── AppSidebar.vue
│   ├── AppSidebarHeader.vue
│   ├── AppHeader.vue
│   ├── NavMain.vue
│   ├── NavUser.vue
│   ├── AssetCombobox.vue     # Combobox cari asset (untuk install/replace)
│   ├── Pagination.vue        # Komponen pagination reusable
│   └── PhotoLightbox.vue     # Grid + lightbox preview foto
├── composables/
│   └── useFlashToast.ts      # Auto-toast dari flash message
├── layouts/
│   ├── AppLayout.vue
│   └── app/
│       └── AppSidebarLayout.vue
├── pages/
│   ├── auth/                 # Login, ForgotPassword (register disabled)
│   ├── Dashboard.vue
│   ├── Equipment/            # Index, Create, Edit, Show
│   ├── Assets/               # Index, Create, Edit, Show
│   ├── Failures/             # Index, Create, Edit, Show
│   ├── Documents/            # Index, Create, Show
│   ├── Photos/               # Index, Create
│   ├── Locations/            # Index, Create, Edit
│   ├── AssetTypes/           # Index, Create, Edit
│   ├── RelationshipTypes/    # Index, Create, Edit
│   ├── DocumentTypes/        # Index, Create, Edit
│   ├── Users/                # Index, Create, Edit
│   └── AuditLogs/            # Index
├── types/
└── routes/                   # Wayfinder-generated route helpers
```

---

## 8. Halaman yang Sudah Ada (V1.0.0)

### Dashboard
- 4 stat cards: Total Equipment, Total Assets, Active Assets, Failures (30d)
- Widget **Recent Failures** (5 terakhir, link ke asset)
- Widget **Equipment Attention** (90d, hanya failure setelah instalasi terakhir)

### Equipment
- **Index**: list + filter lokasi (recursive, dengan count) + search
- **Show**: 4 tab — Current Assets / History / Documents / Photos
  - Tab Current Assets: list asset aktif + tombol Install/Replace/Remove
  - Tab History: timeline instalasi
- **Create / Edit**: form dasar (location, tag, name, type)

### Assets
- **Index**: list + filter lokasi/type/status/manufacturer + search
- **Show**: 6 tab — Overview / Specifications / Relationships / Failures /
  Documents / Photos
- **Create / Edit**: form + **dynamic specifications** (muncul otomatis
  sesuai asset type yang dipilih)

### Failures
- **Index**: list + filter asset/type/date range
- **Create / Edit / Show**: form lengkap

### Documents
- **Index**: list + filter type + search + pagination
- **Create**: upload + link ke equipment/asset (opsional)
- **Show**: detail metadata

### Photos
- **Index**: grid thumbnail (8 kolom desktop) + lightbox preview + pagination
- **Create**: upload + link ke equipment/asset (opsional)

### Master Data (admin-only)
- Locations (hierarki, anti-circular, restrict delete)
- Asset Types + Asset Type Definitions (inline table editor)
- Relationship Types
- Document Types

### Admin (admin-only)
- Users (CRUD, password optional saat edit)
- Audit Logs (filter by user/action/entity/date)

---

## 9. Known Limitations (V1.0.0)

| # | Limitasi | Rencana |
|---|----------|---------|
| 1 | Register publik dinonaktifkan (user hanya dibuat admin) | By design |
| 2 | Email verification & forgot password belum aktif | Roadmap V1.1 |
| 3 | Failure edit hanya untuk creator atau admin | By design |
| 4 | Asset replace hanya via combobox UUID/asset_code | ✅ Sudah ada |
| 5 | Widget Equipment Attention hanya hitung failure setelah instalasi terakhir | By design |
| 6 | Failed login tidak di-log | By design (V1 simplicity) |
| 7 | UI mobile belum dioptimasi penuh | Fokus desktop |
| 8 | Belum ada export PDF/Excel | Roadmap V1.1 |
| 9 | Belum ada QR code generator untuk equipment | Roadmap V1.1 |
| 10 | Belum ada notifikasi email untuk failure kritis | Roadmap V1.1 |

---

## 10. Roadmap V1.1 (Draft)

- [ ] **Bulk import asset** via Excel/CSV
- [ ] **QR code generator** untuk equipment (cetak & tempel di mesin)
- [ ] **Notifikasi email** untuk failure dengan downtime > threshold
- [ ] **Export laporan** PDF (failure report, asset list)
- [ ] **Global search** di header (cari equipment/asset dari manapun)
- [ ] **Print-friendly view** untuk equipment detail

---

## 11. Setup Cepat

```bash
# 1. Clone & install dependencies
git clone https://github.com/bydowhy/asset_management.git
cd asset_management
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
# Edit .env → set DB_DATABASE=asset_management, DB_USERNAME, DB_PASSWORD

# 3. Database
php artisan migrate:fresh --seed

# 4. Build & run
npm run dev
# Di terminal lain:
php artisan serve
```

**Kredensial default (dari seeder):**

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `password` |
| User | `maint` | `password` |

---

## 12. Cara Pakai File Ini

Saat memulai sesi baru dengan AI assistant, cukup paste:

> "Ini context project saya: [paste isi PROJECT_CONTEXT.md].
> Saya mau tambahkan fitur X. Ini file Y yang relevan: [paste file].
> Bisa bantu implementasi?"

AI akan langsung paham konteks tanpa perlu penjelasan ulang.
