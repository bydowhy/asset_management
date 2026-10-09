# Changelog

Semua perubahan penting pada project ini didokumentasikan di file ini.
Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan project ini menggunakan [Semantic Versioning](https://semver.org/).

---

## [1.0.0] — 2026-10-09

Rilis pertama — **Asset Management System V1.0.0 (LOCKED)**.

### Added

#### Authentication & Authorization
- Authentication via Laravel Fortify (username + password, bukan email)
- Role-based access control: `admin` dan `user`
- Middleware `EnsureUserIsAdmin` untuk proteksi 6 halaman master data
- Sidebar auto-filter berdasarkan role user
- Register publik dinonaktifkan — user hanya dibuat oleh admin via `/users/create`

#### Master Data
- **Locations**: CRUD hierarki parent-child, anti-circular, restrict delete
- **Asset Types**: CRUD + inline editor untuk dynamic specifications
- **Relationship Types**: CRUD (Drives, Controls, Actuates, Powers)
- **Document Types**: CRUD (Data Sheet, Manual, Certificate)

#### Equipment Management
- CRUD Equipment (Index dengan filter lokasi recursive + search)
- Equipment Detail dengan 4 tab: Current Assets / History / Documents / Photos
- **Install Asset** ke equipment (dengan auto-set status → active)
- **Replace Asset** (auto-transfer relationship, auto-set status lama → inactive,
  auto-set status baru → active)
- **Remove Asset** (auto-close semua relationship aktif, auto-set status →
  inactive)
- Combobox searchable untuk pilih asset (ganti input UUID)

#### Asset Management
- CRUD Asset dengan **dynamic specifications** per asset type
- Asset Detail dengan 6 tab: Overview / Specifications / Relationships /
  Failures / Documents / Photos
- Filter lokasi (recursive) + filter type/status/manufacturer + search
- **Asset Relationships**: time-bound (`valid_from` / `valid_to`),
  auto-transfer saat replace, auto-close saat remove
- Specifications sync via `AssetSpecificationService`

#### Failure Management
- CRUD Failure (Index dengan filter asset/type/date range)
- Failure Detail
- Edit Failure hanya untuk creator atau admin
- `asset_id` dan `failure_date` terkunci setelah dibuat (tidak bisa edit)

#### Documents & Photos
- Upload dokumen (PDF, DOCX, XLSX, PPTX, max 20 MB)
- Upload foto (JPG, PNG, max 10 MB)
- **Polymorphic links** ke equipment atau asset
- Download dokumen via controller terautentikasi
- Photo preview via **lightbox** (grid 8 kolom desktop)
- Delete photo/dokumen (creator atau admin)
- Smart redirect: upload dari equipment/asset detail tetap di halaman tersebut
- Pagination di Index (Documents & Photos)

#### Dashboard
- 4 stat cards: Total Equipment, Total Assets, Active Assets, Failures (30d)
- Widget **Recent Failures** (5 terakhir)
- Widget **Equipment Attention** (90d) — hanya failure setelah instalasi terakhir
  asset yang masih aktif

#### Audit Log
- Automatic logging untuk semua aksi create/update/delete
- Login/logout logging via event listener
- Halaman Audit Logs dengan filter user/action/entity/date

#### UI/UX
- Toast notifications (vue-sonner) untuk success & error
- Pagination reusable component
- Status badge dengan warna konsisten
- Responsive sidebar (collapse/expand)
- Breadcrumb navigation
- Dark mode compatible (via starter kit)

### Changed
- Filter lokasi di Equipment & Assets menjadi **recursive** (termasuk subtree)
- Asset filter by location ditambahkan (via `whereHas` nested)
- Widget Dashboard Equipment Attention diubah dari asset-based ke
  equipment-based, dengan filter `failure_date >= installed_at`

### Fixed
- **Konfigurasi middleware `HandleInertiaRequests`** yang hilang karena
  dua blok `withMiddleware` terpisah di `bootstrap/app.php`
- **Duplikasi konfirmasi delete photo** (lightbox + parent handler)
- **Error message tidak muncul** di form Create Failure
- **Relationship tidak auto-transfer** saat replace asset (khusus incoming
  relationship: target = old asset)
- **Relationship tidak auto-close** saat remove asset
- **Sidebar active state** hilang saat masuk halaman detail (mis.
  `/equipment/{id}`) — sekarang pakai prefix match
- **Pagination blank** saat refresh (masalah `v-html` dalam Inertia `<Link>`)
- **Unique constraint violation** untuk relationship yang duplikat —
  sekarang ada validasi ramah di FormRequest
- **Validation `data_type`** di Asset Type Definitions (nilai `number` di DB
  tidak match dengan opsi `decimal/integer` di frontend)
- **Asset terbuat tanpa specification** meski required — sekarang dibungkus
  `DB::transaction`
- **404 / 500 error** untuk endpoint yang perlu validasi lebih ramah

### Security
- Documents di storage private, diakses via controller terautentikasi
- File upload divalidasi MIME type + size
- CSRF protection aktif (Laravel default)
- Password di-hash dengan bcrypt (Laravel default)
- Role-based middleware untuk halaman admin

### Database
- Schema V2: 17 tabel + tabel sistem Laravel
- Semua PK menggunakan `CHAR(36)` (UUID)
- Semua FK dengan constraint `ON DELETE` / `ON UPDATE` yang sesuai
- Composite unique constraints untuk data historis
- CHECK constraints untuk date consistency
- Indexes untuk foreign key & filter kolom

### Testing
- 143 skenario uji dieksekusi
- ~140 PASS, ~3 N/A (UI tidak menyediakan jalur)
- 0 FAIL setelah perbaikan

---

## [Unreleased] — Roadmap V1.1

### Planned
- [ ] Bulk import asset via Excel/CSV
- [ ] QR code generator untuk equipment
- [ ] Notifikasi email untuk failure kritis
- [ ] Export laporan PDF
- [ ] Global search di header
- [ ] Print-friendly equipment detail

### Considered
- Email verification untuk user baru
- Forgot password flow
- API endpoints untuk integrasi eksternal
- Predictive maintenance dari failure history
- Asset location tracking (jika asset bisa dipindah antar lokasi secara fisik)
