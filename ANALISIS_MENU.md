# Analisis Menu & Proses Bisnis - Muamalat Internship Program (MIP)

**Tanggal Analisis:** 22 Februari 2026
**Framework:** CodeIgniter 4 (v4.6.3)
**Template:** Sneat Bootstrap + ApexCharts

---

## DAFTAR ISI

1. [Ringkasan Status Menu](#1-ringkasan-status-menu)
2. [Detail Menu yang SUDAH Berjalan](#2-detail-menu-yang-sudah-berjalan)
3. [Detail Menu yang BELUM Berjalan](#3-detail-menu-yang-belum-berjalan)
4. [Komponen yang Hilang (Missing)](#4-komponen-yang-hilang-missing)
5. [Proses Bisnis per Modul](#5-proses-bisnis-per-modul)

---

## 1. Ringkasan Status Menu

| #   | Menu                   | URL                               | Role                       | Status            | Keterangan                          |
| --- | ---------------------- | --------------------------------- | -------------------------- | ----------------- | ----------------------------------- |
| 1   | Dashboard              | `/dashboard`                      | Semua                      | ✅ Berjalan       | 5 view per role                     |
| 2   | Data Role              | `/role`                           | Admin                      | ✅ Berjalan       | CRUD lengkap                        |
| 3   | Data User              | `/user`                           | Admin, HR                  | ✅ Berjalan       | CRUD lengkap                        |
| 4   | Data Divisi            | `/divisi`                         | Admin                      | ✅ Berjalan       | CRUD lengkap                        |
| 5   | Data Pemagang          | `/intern`                         | Admin, HR                  | ✅ Berjalan       | CRUD lengkap                        |
| 6   | Check-In / Check-Out   | `/attendance/checkin`             | Intern                     | ✅ Berjalan       | GPS + foto                          |
| 7   | Rekap Absensi          | `/attendance`                     | Intern                     | ✅ Berjalan       | Rekap bulanan                       |
| 8   | Koreksi Absensi        | `/attendance/correction`          | Intern                     | ✅ Berjalan       | Submit koreksi                      |
| 9   | Cuti/Izin/Sakit        | `/leave/my`                       | Intern                     | ✅ Berjalan       | Submit cuti/izin                    |
| 10  | Data Absensi           | `/attendance/all`                 | Admin, HR, Mentor, Finance | ✅ Berjalan       | View semua absensi                  |
| 11  | Approval Koreksi       | `/attendance/correction/approval` | Admin, HR, Mentor          | ✅ Berjalan       | Approve/reject                      |
| 12  | Approval Cuti/Izin     | `/leave/approval`                 | Admin, HR, Mentor          | ✅ Berjalan       | Approve/reject                      |
| 13  | Aktivitas Harian       | `/activity/my`                    | Intern                     | ✅ Berjalan       | CRUD aktivitas                      |
| 14  | Project Mingguan       | `/project/my`                     | Intern                     | ✅ Berjalan       | CRUD proyek                         |
| 15  | Approval Aktivitas     | `/activity/approval`              | Mentor                     | ✅ Berjalan       | batch approve                       |
| 16  | Assessment Project     | `/project/assessment`             | Mentor                     | ✅ Berjalan       | Penilaian proyek                    |
| 17  | Data Aktivitas (All)   | `/activity`                       | Admin, HR                  | ✅ Berjalan       | Lihat semua aktivitas               |
| 18  | Data Project (All)     | `/project`                        | Admin, HR                  | ✅ Berjalan       | Lihat semua proyek                  |
| 19  | Master Indikator KPI   | `/kpi/indicators`                 | Admin                      | ✅ Berjalan       | CRUD indikator                      |
| 20  | Pengaturan             | `/settings`                       | Admin                      | ✅ Berjalan       | CRUD settings                       |
| 21  | Login / Logout         | `/login`, `/logout`               | Semua                      | ✅ Berjalan       | Auth lengkap                        |
| 22  | **Profil Saya**        | `/profile`                        | Semua                      | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 23  | **KPI Saya**           | `/kpi/my`                         | Intern                     | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 24  | **Ranking Pemagang**   | `/kpi/ranking`                    | Intern + Semua             | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 25  | **KPI Bulanan**        | `/kpi/monthly`                    | Admin, HR, Mentor          | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 26  | **KPI Periode**        | `/kpi/period`                     | Admin, HR                  | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 27  | **Pemagang Terbaik**   | `/kpi/best`                       | Admin, HR, Mentor          | ❌ TIDAK BERJALAN | Route + Controller + View tidak ada |
| 28  | **KPI Assessment**     | `/kpi/assessment`                 | Mentor                     | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 29  | **KPI Calculation**    | `/kpi/calculation`                | Admin, HR                  | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 30  | **KPI Analytics**      | `/kpi/analytics`                  | Admin, HR                  | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 31  | **Uang Saku Saya**     | `/allowance/my`                   | Intern                     | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 32  | **Periode Pembayaran** | `/allowance/period`               | Admin, HR, Finance         | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 33  | **Data Uang Saku**     | `/allowance`                      | Admin, HR, Finance         | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 34  | **Proses Pembayaran**  | `/allowance/payment`              | Finance                    | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 35  | **Laporan Absensi**    | `/report/attendance`              | Admin, HR, Finance, Mentor | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 36  | **Laporan Aktivitas**  | `/report/activity`                | Admin, HR, Mentor          | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 37  | **Laporan KPI**        | `/report/kpi`                     | Admin, HR, Mentor          | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 38  | **Laporan Keuangan**   | `/report/allowance`               | Admin, HR, Finance         | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 39  | **Data Arsip**         | `/archive`                        | Admin, HR                  | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 40  | **Audit Log**          | `/audit`                          | Admin                      | ❌ TIDAK BERJALAN | Controller & View tidak ada         |
| 41  | **Notifikasi (API)**   | `/api/notifications`              | Semua                      | ❌ TIDAK BERJALAN | Controller tidak ada                |

**Skor:** 21 menu berjalan / 41 total = **51.2% selesai**

---

## 2. Detail Menu yang SUDAH Berjalan

### 2.1 Autentikasi (Auth)

- **Login** (`/login`) — Form login dengan email + password
- **Logout** (`/logout`) — Hapus session & redirect ke login
- **Controller:** `AuthController` — `login()`, `processLogin()`, `logout()`, `checkSession()`
- **View:** `auth/login.php`
- **Filter:** Guest filter (redirect ke dashboard jika sudah login)

### 2.2 Dashboard

- **URL:** `/dashboard`
- **Controller:** `Dashboard` — 18+ endpoint JSON untuk chart/tabel
- **Views:** `dashboard/admin.php`, `dashboard/hr.php`, `dashboard/mentor.php`, `dashboard/finance.php`, `dashboard/intern.php`
- **Fitur per role:** Stat cards, chart (ApexCharts), tabel data via AJAX

### 2.3 Manajemen Role (Admin)

- **CRUD lengkap:** index, create, store, detail, edit, update, delete, toggleStatus
- **Controller:** `RoleController`
- **Views:** `admin/role/index.php`, `create.php`, `edit.php`, `detail.php`
- **Model:** `RoleModel`

### 2.4 Manajemen User (Admin + HR)

- **CRUD lengkap:** index, create, store, detail, edit, update, delete, getNextNIK
- **Controller:** `UserController`
- **Views:** `admin/user/index.php`, `create.php`, `edit.php`, `detail.php`
- **Model:** `UserModel`

### 2.5 Manajemen Divisi (Admin)

- **CRUD lengkap:** index, create, store, detail, edit, update, delete, toggleStatus
- **Controller:** `DivisiController`
- **Views:** `admin/divisi/index.php`, `create.php`, `edit.php`, `detail.php`
- **Model:** `DivisiModel`

### 2.6 Manajemen Pemagang (Admin + HR)

- **CRUD lengkap:** index, create, store, detail, edit, update, delete, toggleStatus, downloadDocument
- **Controller:** `InternController`
- **Views:** `admin/intern/index.php`, `create.php`, `edit.php`, `detail.php`
- **Model:** `InternModel`

### 2.7 Absensi

- **Intern:** Check-in/out (GPS + selfie), Rekap bulanan, Koreksi absensi
- **Admin/HR/Mentor:** View semua, Approval koreksi (approve/reject)
- **Controller:** `AttendanceController` — 10 method lengkap
- **Views:** `attendance/checkin.php`, `index.php`, `correction.php`, `all.php`, `correction_approval.php`
- **Model:** `AttendanceModel`

### 2.8 Cuti/Izin/Sakit

- **Intern:** Lihat cuti saya, Buat pengajuan baru
- **Admin/HR/Mentor:** Approval cuti/izin (approve/reject)
- **Controller:** `LeaveController` — 6 method lengkap
- **Views:** `leave/my.php`, `leave/approval.php`
- **Model:** `LeaveModel`

### 2.9 Aktivitas Harian

- **Intern:** CRUD aktivitas + attachment
- **Mentor:** Approval (single & batch), reject dengan catatan
- **Admin/HR:** View semua, Dashboard statistik, Export
- **Controller:** `ActivityController` — 16 method lengkap
- **Views:** `activity/my.php`, `create.php`, `edit.php`, `detail.php`, `approval.php`, `index.php`, `dashboard.php`
- **Model:** `DailyActivityModel`

### 2.10 Proyek Mingguan

- **Intern:** CRUD proyek + attachment
- **Mentor:** Assessment/penilaian proyek
- **Admin/HR:** View semua, Dashboard statistik, Export
- **Controller:** `ProjectController` — 14 method lengkap
- **Views:** `project/my.php`, `create.php`, `edit.php`, `detail.php`, `assessment.php`, `index.php`, `dashboard.php`
- **Model:** `WeeklyProjectModel`

### 2.11 Master Indikator KPI (Admin)

- **CRUD lengkap:** index, create, store, edit, update, toggleStatus, delete
- **Controller:** `KpiIndicatorController`
- **Views:** `admin/kpi/indicators/index.php`, `create.php`, `edit.php`
- **Model:** `KpiIndicatorModel`

### 2.12 Pengaturan Sistem (Admin)

- **CRUD lengkap + bulkUpdate**
- **Controller:** `SettingController`
- **Views:** `admin/settings/index.php`, `create.php`, `edit.php`, `detail.php`
- **Model:** `SettingModel`

---

## 3. Detail Menu yang BELUM Berjalan

### 3.1 ❌ Profil Saya (`/profile`)

**Missing:**

- Controller: `ProfileController` (tidak ada)
- Views: `profile/index.php` (tidak ada)
- Dibutuhkan: Form update profil, ganti password, upload foto

### 3.2 ❌ KPI Saya - Intern (`/kpi/my`)

**Missing:**

- Controller: `Intern\MyKpiController` (tidak ada)
- Views: `kpi/my/` folder (tidak ada)
- Route ada: `kpi/my/`, `kpi/my/monthly/{bulan}/{tahun}`, `kpi/my/breakdown`, `kpi/my/history`

### 3.3 ❌ Ranking Pemagang (`/kpi/ranking`)

**Missing:**

- Controller: `KpiRankingController` (tidak ada)
- Views: `kpi/ranking/` folder (tidak ada)
- Route ada: `kpi/ranking`, `kpi/ranking/division/{id}`

### 3.4 ❌ KPI Assessment - Mentor (`/kpi/assessment`)

**Missing:**

- Controller: `Mentor\KpiAssessmentController` (tidak ada)
- Views: `kpi/assessment/` folder (tidak ada)
- Route ada: index, form/{id}, submit, history/{id}

### 3.5 ❌ KPI Calculation - Admin/HR (`/kpi/calculation`)

**Missing:**

- Controller: `Admin\KpiCalculationController` (tidak ada)
- Views: `kpi/calculation/` folder (tidak ada)
- Route ada: index, calculate, recalculate/{id}

### 3.6 ❌ KPI Bulanan (`/kpi/monthly`)

**Missing:**

- Controller: `Admin\KpiMonthlyController` (tidak ada)
- Views: `kpi/monthly/` folder (tidak ada)
- Route ada: index, view/{id}/{id}, finalize/{id}, export

### 3.7 ❌ KPI Periode (`/kpi/period`)

**Missing:**

- Controller: `Admin\KpiPeriodController` (tidak ada)
- Views: `kpi/period/` folder (tidak ada)
- Route ada: index, calculate, best-interns, generate-certificate/{id}, certificate/download/{id}

### 3.8 ❌ Pemagang Terbaik (`/kpi/best`)

**Missing:**

- **Route juga tidak ada** (hanya link di sidebar)
- Controller: Tidak ada
- Views: Tidak ada
- Seharusnya bagian dari KPI Period (`/kpi/period/best-interns`)

### 3.9 ❌ KPI Analytics - Admin/HR (`/kpi/analytics`)

**Missing:**

- Controller: `Admin\KpiAnalyticsController` (tidak ada)
- Views: `kpi/analytics/` folder (tidak ada)
- Route ada: index, distribution, trends, export-report

### 3.10 ❌ Uang Saku Saya - Intern (`/allowance/my`)

**Missing:**

- Controller: `AllowanceController` (tidak ada)
- Views: `allowance/` folder (tidak ada)
- Model: `AllowanceModel` (tidak ada)

### 3.11 ❌ Periode Pembayaran (`/allowance/period`)

**Missing:**

- Sama — perlu `AllowanceController`
- Model: `AllowancePeriodModel` (tidak ada)

### 3.12 ❌ Data Uang Saku (`/allowance`)

**Missing:**

- Sama — perlu `AllowanceController`

### 3.13 ❌ Proses Pembayaran - Finance (`/allowance/payment`)

**Missing:**

- Sama — perlu `AllowanceController`

### 3.14 ❌ Laporan Absensi (`/report/attendance`)

**Missing:**

- Controller: `ReportController` (tidak ada)
- Views: `report/` folder (tidak ada)

### 3.15 ❌ Laporan Aktivitas (`/report/activity`)

**Missing:** Sama — perlu `ReportController`

### 3.16 ❌ Laporan KPI (`/report/kpi`)

**Missing:** Sama — perlu `ReportController`

### 3.17 ❌ Laporan Keuangan (`/report/allowance`)

**Missing:** Sama — perlu `ReportController`

### 3.18 ❌ Data Arsip (`/archive`)

**Missing:**

- Controller: `ArchiveController` (tidak ada)
- Views: `archive/` folder (tidak ada)
- Model: `ArchivedInternModel` (tidak ada)

### 3.19 ❌ Audit Log (`/audit`)

**Missing:**

- Controller: `AuditController` (tidak ada)
- Views: `admin/audit/` folder (tidak ada)
- Model: `AuditLogModel` (sudah ada)

### 3.20 ❌ Notifikasi API (`/api/notifications`)

**Missing:**

- Controller: `NotificationController` (tidak ada)
- Model: `NotificationModel` (tidak ada)

---

## 4. Komponen yang Hilang (Missing)

### 4.1 Controller yang Perlu Dibuat (13 controller)

| #   | Controller                 | Namespace                | Tujuan                                              |
| --- | -------------------------- | ------------------------ | --------------------------------------------------- |
| 1   | `ProfileController`        | `App\Controllers`        | Profil user, ganti password, upload foto            |
| 2   | `AllowanceController`      | `App\Controllers`        | CRUD uang saku, periode, pembayaran, slip           |
| 3   | `ReportController`         | `App\Controllers`        | Generate laporan absensi, aktivitas, KPI, keuangan  |
| 4   | `ArchiveController`        | `App\Controllers`        | Arsip data pemagang yang selesai                    |
| 5   | `AuditController`          | `App\Controllers`        | View log audit sistem                               |
| 6   | `NotificationController`   | `App\Controllers`        | API notifikasi (get unread, mark read)              |
| 7   | `KpiRankingController`     | `App\Controllers`        | Ranking/Leaderboard pemagang                        |
| 8   | `KpiCalculationController` | `App\Controllers\Admin`  | Kalkulasi otomatis skor KPI                         |
| 9   | `KpiMonthlyController`     | `App\Controllers\Admin`  | Hasil KPI bulanan, finalize                         |
| 10  | `KpiPeriodController`      | `App\Controllers\Admin`  | Hasil KPI per periode, pemagang terbaik, sertifikat |
| 11  | `KpiAnalyticsController`   | `App\Controllers\Admin`  | Analitik & distribusi KPI                           |
| 12  | `KpiAssessmentController`  | `App\Controllers\Mentor` | Form penilaian manual oleh mentor                   |
| 13  | `MyKpiController`          | `App\Controllers\Intern` | Dashboard KPI untuk pemagang                        |

### 4.2 Model yang Perlu Dibuat (9 model)

| #   | Model                       | Tabel                    | PK                 |
| --- | --------------------------- | ------------------------ | ------------------ |
| 1   | `AllowancePeriodModel`      | `allowance_periods`      | `id_period`        |
| 2   | `AllowanceModel`            | `allowances`             | `id_allowance`     |
| 3   | `AllowanceSlipModel`        | `allowance_slips`        | `id_slip`          |
| 4   | `KpiAssessmentModel`        | `kpi_assessments`        | `id_assessment`    |
| 5   | `KpiMonthlyResultModel`     | `kpi_monthly_results`    | `id_result`        |
| 6   | `KpiPeriodResultModel`      | `kpi_period_results`     | `id_period_result` |
| 7   | `NotificationModel`         | `notifications`          | `id_notification`  |
| 8   | `ArchivedInternModel`       | `archived_interns`       | `id_archive`       |
| 9   | `AttendanceCorrectionModel` | `attendance_corrections` | `id_correction`    |

> **Catatan:** Tabel migration untuk semua model di atas sudah ada dan database sudah ter-migrasi. Hanya Model class-nya yang belum dibuat.

### 4.3 View yang Perlu Dibuat

| #   | Folder View              | Estimasi File                                                         |
| --- | ------------------------ | --------------------------------------------------------------------- |
| 1   | `views/profile/`         | `index.php`                                                           |
| 2   | `views/allowance/`       | `my.php`, `index.php`, `period.php`, `payment.php`                    |
| 3   | `views/report/`          | `attendance.php`, `activity.php`, `kpi.php`, `allowance.php`          |
| 4   | `views/archive/`         | `index.php`, `view.php`                                               |
| 5   | `views/admin/audit/`     | `index.php`                                                           |
| 6   | `views/kpi/my/`          | `dashboard.php`, `monthly_detail.php`, `breakdown.php`, `history.php` |
| 7   | `views/kpi/ranking/`     | `index.php`                                                           |
| 8   | `views/kpi/assessment/`  | `index.php`, `form.php`, `history.php`                                |
| 9   | `views/kpi/calculation/` | `index.php`                                                           |
| 10  | `views/kpi/monthly/`     | `index.php`, `view.php`                                               |
| 11  | `views/kpi/period/`      | `index.php`, `best_interns.php`                                       |
| 12  | `views/kpi/analytics/`   | `index.php`, `distribution.php`, `trends.php`                         |

**Total estimasi: ~28 file view baru**

### 4.4 Route yang Hilang

| #   | Route           | Keterangan                                       |
| --- | --------------- | ------------------------------------------------ |
| 1   | `GET /kpi/best` | Link ada di sidebar tapi route tidak terdefinisi |

---

## 5. Proses Bisnis per Modul

### 5.1 Modul Profil (❌ Belum Ada)

```
[User Login] → [Klik "Profil Saya"] → [Halaman Profil]
  ├── Lihat Informasi: nama, email, NIK, divisi, foto
  ├── [Update Profil] → POST /profile/update → Validasi → Simpan → Flash success
  ├── [Ganti Password] → POST /profile/change-password → Cek password lama → Hash baru → Simpan
  └── [Upload Foto] → Upload ke writable/uploads → Update DB → Tampilkan foto baru
```

### 5.2 Modul KPI — Assessment oleh Mentor (❌ Belum Ada)

```
[Mentor Login] → [KPI Assessment]
  ├── [Lihat Daftar Mentee] → Tabel mentee dengan status penilaian bulan ini
  ├── [Pilih Mentee] → Form penilaian per indikator KPI
  │     ├── Loop: setiap indikator aktif
  │     │     ├── Tampilkan nama + bobot indikator
  │     │     ├── Input nilai (0-100) → nilai_raw
  │     │     └── Otomatis: nilai_weighted = nilai_raw × (bobot/100)
  │     └── [Submit] → POST /kpi/assessment/submit
  │           ├── Validasi: setiap indikator harus terisi
  │           ├── Simpan ke tabel `kpi_assessments`
  │           └── Kirim notifikasi ke pemagang
  └── [History] → Lihat riwayat penilaian per pemagang per bulan
```

### 5.3 Modul KPI — Kalkulasi Otomatis Admin/HR (❌ Belum Ada)

```
[Admin/HR Login] → [KPI Calculation]
  ├── [Pilih Bulan + Tahun]
  ├── [Hitung KPI] → POST /kpi/calculation/calculate
  │     ├── Loop: setiap pemagang aktif
  │     │     ├── Ambil indikator tipe "auto" (kehadiran, aktivitas, proyek)
  │     │     │     ├── Kehadiran: (total_hadir / total_hari_kerja) × 100
  │     │     │     ├── Aktivitas: (approved / submitted) × 100
  │     │     │     └── Proyek: avg(nilai_assessment)
  │     │     ├── Ambil indikator tipe "manual" → dari tabel kpi_assessments (mentor)
  │     │     ├── Hitung total_score = Σ(nilai_weighted)
  │     │     └── Simpan ke `kpi_monthly_results`
  │     ├── Ranking otomatis berdasarkan total_score DESC
  │     └── Kategori performa: excellent(≥90), good(≥75), average(≥60), below_average(≥40), poor(<40)
  └── [Recalculate] → Hitung ulang 1 pemagang tertentu
```

### 5.4 Modul KPI — Hasil Bulanan (❌ Belum Ada)

```
[Admin/HR/Mentor Login] → [KPI Bulanan]
  ├── [Pilih Bulan + Tahun] → Tabel semua pemagang + skor + rank + kategori
  ├── [View Detail] → Breakdown per indikator (chart radar/bar)
  ├── [Finalize] → POST /kpi/monthly/finalize/{id}
  │     ├── Set is_finalized = true, finalized_at, finalized_by
  │     └── Setelah finalized, tidak bisa di-edit
  └── [Export] → Download Excel/PDF hasil KPI bulanan
```

### 5.5 Modul KPI — Periode & Pemagang Terbaik (❌ Belum Ada)

```
[Admin/HR Login] → [KPI Periode]
  ├── [Calculate Periode] → POST /kpi/period/calculate
  │     ├── Pilih periode: tanggal mulai + selesai
  │     ├── Hitung avg total_score dari semua bulan dalam periode
  │     ├── Ranking akhir berdasarkan avg_total_score
  │     ├── Tentukan is_best_intern (rank #1 atau top N)
  │     └── Simpan ke `kpi_period_results`
  ├── [Best Interns] → Halaman daftar pemagang terbaik
  │     ├── Tabel: Rank, Nama, Skor, Divisi
  │     └── Badge/trophy untuk top 3
  ├── [Generate Sertifikat] → POST /kpi/period/generate-certificate/{id}
  │     ├── Generate PDF sertifikat dengan data pemagang
  │     └── Simpan path ke sertifikat_file
  └── [Download Sertifikat] → GET /kpi/period/certificate/download/{id}
```

### 5.6 Modul KPI — KPI Saya / Intern (❌ Belum Ada)

```
[Intern Login] → [KPI Saya]
  ├── [Dashboard] → Total skor bulan ini, ranking, kategori, chart tren 6 bulan
  ├── [Monthly Detail] → Breakdown skor per indikator bulan tertentu
  ├── [Breakdown] → Penjelasan cara hitung setiap indikator
  └── [History] → Riwayat skor KPI sejak mulai magang
```

### 5.7 Modul KPI — Ranking/Leaderboard (❌ Belum Ada)

```
[Semua Role Login] → [Ranking Pemagang]
  ├── [Leaderboard Global] → Tabel: Rank, Nama, Divisi, Skor, Kategori
  │     ├── Top 3 ditampilkan dengan highlight khusus
  │     ├── Filter by bulan/tahun
  │     └── Pagination
  └── [By Division] → Ranking per divisi tertentu
```

### 5.8 Modul KPI — Analytics (❌ Belum Ada)

```
[Admin/HR Login] → [KPI Analytics]
  ├── [Distribution] → Chart distribusi kategori performa (pie/bar)
  │     ├── Berapa % excellent, good, average, dll
  │     └── Tren distribusi per bulan
  ├── [Trends] → Chart tren rata-rata skor KPI per bulan (line chart)
  │     ├── Overall dan per divisi
  │     └── Bandingkan antar divisi
  └── [Export Report] → Download laporan analitik lengkap (PDF/Excel)
```

### 5.9 Modul Uang Saku / Allowance (❌ Belum Ada)

```
=== FLOW ADMIN/HR ===
[Admin/HR Login] → [Periode Pembayaran]
  ├── [Lihat Daftar Periode] → Tabel: nama_periode, tanggal, status, total
  └── [Calculate] → POST /allowance/calculate
        ├── Tentukan periode (15-an bulan ini ke 15-an bulan depan)
        ├── Loop: setiap pemagang aktif
        │     ├── Hitung total_hari_kerja (weekdays dalam periode)
        │     ├── Hitung total_hadir dari tabel attendances
        │     ├── Hitung total_alpha, izin, sakit
        │     ├── total_uang_saku = total_hadir × rate_per_hari (default Rp 100.000)
        │     └── Simpan ke tabel `allowances` (status: pending)
        ├── Update allowance_periods: status → calculated, total_nominal, total_pemagang
        └── Flash success

[Admin/HR Login] → [Data Uang Saku]
  ├── [Lihat per Periode] → Filter by periode
  ├── Tabel: Nama, Divisi, Hadir, Alpha, Izin, Sakit, Total
  └── Status: pending / approved / paid

=== FLOW FINANCE ===
[Finance Login] → [Proses Pembayaran]
  ├── [Lihat Daftar Menunggu Bayar] → Allowances dengan status 'approved'
  ├── [Bayar] → POST /allowance/process-payment/{id}
  │     ├── Update status → paid
  │     ├── Set tanggal_transfer = today
  │     ├── Upload bukti_transfer (opsional)
  │     └── Generate slip otomatis → simpan ke `allowance_slips`
  └── Bulk payment (bayar semua sekaligus)

=== FLOW INTERN ===
[Intern Login] → [Uang Saku Saya]
  ├── [Lihat Riwayat] → Tabel: Periode, Hari Hadir, Jumlah, Status
  └── [Download Slip] → GET /allowance/slip/{id} → Download PDF slip pembayaran
```

### 5.10 Modul Laporan / Report (❌ Belum Ada)

```
[Admin/HR/Finance/Mentor Login] → [Laporan]

  ├── [Laporan Absensi] → /report/attendance
  │     ├── Filter: tanggal mulai, tanggal selesai, divisi, pemagang
  │     ├── Tabel: Tanggal, Nama, Jam Masuk, Jam Keluar, Status
  │     ├── Summary: total hadir, terlambat, alpha, izin, sakit
  │     └── [Export] → Download Excel/PDF
  │
  ├── [Laporan Aktivitas] → /report/activity (bukan Finance)
  │     ├── Filter: periode, divisi, pemagang, kategori
  │     ├── Tabel: Tanggal, Nama, Judul, Kategori, Status Approval
  │     ├── Summary: jumlah per kategori, approval rate
  │     └── [Export] → Download Excel/PDF
  │
  ├── [Laporan KPI] → /report/kpi (bukan Finance)
  │     ├── Filter: bulan/tahun, divisi
  │     ├── Tabel: Nama, Skor Total, Rank, Kategori
  │     ├── Chart: distribusi kategori
  │     └── [Export] → Download Excel/PDF
  │
  └── [Laporan Keuangan] → /report/allowance
        ├── Filter: periode, divisi, status pembayaran
        ├── Tabel: Nama, Periode, Hadir, Total, Status
        ├── Summary: total nominal, jumlah pemagang, pending vs paid
        └── [Export] → Download Excel/PDF
```

### 5.11 Modul Arsip (❌ Belum Ada)

```
[Admin/HR Login] → [Data Arsip]
  ├── [Lihat Daftar Arsip] → Tabel pemagang yang sudah di-arsip
  │     ├── Kolom: Nama, NIK, Divisi, Periode, %Kehadiran, KPI, Rank
  │     └── [View Detail] → Summary lengkap (JSON summary_data)
  └── [Proses Arsip] → POST /archive/process
        ├── Pilih pemagang yang sudah selesai (status: completed)
        ├── Kumpulkan semua data:
        │     ├── Total hari hadir & kerja
        │     ├── Persentase kehadiran
        │     ├── Final KPI score & rank
        │     ├── Total uang saku yang diterima
        │     └── Summary JSON (detail absensi, aktivitas, proyek)
        ├── Simpan ke tabel `archived_interns`
        └── Flash success
```

### 5.12 Modul Audit Log (❌ Belum Ada)

```
[Admin Login] → [Audit Log]
  ├── [Lihat Log] → Tabel: Waktu, User, Aksi, Modul, Detail
  │     ├── Filter: tanggal, user, modul, aksi
  │     ├── Pagination
  │     └── Detail: old_data vs new_data (JSON diff)
  └── Auto-record: setiap Create/Update/Delete di sistem
        ├── Modul: attendance, activity, project, kpi, allowance, user, dll
        ├── Data: IP address, user agent, old data, new data
        └── Implementasi via Events/Listeners atau Model afterInsert/afterUpdate
```

### 5.13 Modul Notifikasi (❌ Belum Ada)

```
[Semua User Login] → [Bell icon di navbar]
  ├── [GET /api/notifications] → JSON: notifikasi belum dibaca
  │     ├── Count badge di navbar
  │     └── Dropdown list notifikasi terbaru
  ├── [Klik notifikasi] → POST /api/notification/mark-read/{id} → Redirect ke link
  └── Trigger notifikasi dari:
        ├── Aktivitas di-approve/reject → notify intern
        ├── Proyek dinilai → notify intern
        ├── KPI dikalkulasi → notify intern
        ├── Koreksi absensi di-approve/reject → notify intern
        ├── Cuti di-approve/reject → notify intern
        ├── Pembayaran uang saku selesai → notify intern
        └── Ada submission baru → notify mentor/admin/hr
```

---

## Lampiran: Tabel Database yang Sudah Ada (20 tabel)

| #   | Tabel                    | Migration | Model                   | Seeder |
| --- | ------------------------ | --------- | ----------------------- | ------ |
| 1   | `roles`                  | ✅        | ✅ `RoleModel`          | ✅     |
| 2   | `divisis`                | ✅        | ✅ `DivisiModel`        | ✅     |
| 3   | `settings`               | ✅        | ✅ `SettingModel`       | ✅     |
| 4   | `users`                  | ✅        | ✅ `UserModel`          | ✅     |
| 5   | `interns`                | ✅        | ✅ `InternModel`        | ✅     |
| 6   | `attendances`            | ✅        | ✅ `AttendanceModel`    | ✅     |
| 7   | `attendance_corrections` | ✅        | ❌ Perlu dibuat         | ❌     |
| 8   | `leaves`                 | ✅        | ✅ `LeaveModel`         | ✅     |
| 9   | `daily_activities`       | ✅        | ✅ `DailyActivityModel` | ✅     |
| 10  | `weekly_projects`        | ✅        | ✅ `WeeklyProjectModel` | ✅     |
| 11  | `kpi_indicators`         | ✅        | ✅ `KpiIndicatorModel`  | ✅     |
| 12  | `kpi_assessments`        | ✅        | ❌ Perlu dibuat         | ❌     |
| 13  | `kpi_period_results`     | ✅        | ❌ Perlu dibuat         | ❌     |
| 14  | `kpi_monthly_results`    | ✅        | ❌ Perlu dibuat         | ❌     |
| 15  | `allowance_periods`      | ✅        | ❌ Perlu dibuat         | ❌     |
| 16  | `allowances`             | ✅        | ❌ Perlu dibuat         | ❌     |
| 17  | `allowance_slips`        | ✅        | ❌ Perlu dibuat         | ❌     |
| 18  | `notifications`          | ✅        | ❌ Perlu dibuat         | ❌     |
| 19  | `archived_interns`       | ✅        | ❌ Perlu dibuat         | ❌     |
| 20  | `audit_logs`             | ✅        | ✅ `AuditLogModel`      | ❌     |

---

## Prioritas Pengerjaan (Rekomendasi)

### Prioritas 1 — Fundamental (Perlu segera)

1. **ProfileController** — Semua user butuh akses profil
2. **AllowanceController + Models** — Finance tidak bisa bekerja tanpa ini
3. **AuditController** — Admin butuh monitoring

### Prioritas 2 — KPI System (Core feature)

4. **Mentor\KpiAssessmentController** — Mentor menilai pemagang
5. **Admin\KpiCalculationController** — Hitung skor otomatis
6. **Admin\KpiMonthlyController** — Hasil bulanan
7. **Intern\MyKpiController** — Pemagang lihat skor sendiri
8. **KpiRankingController** — Leaderboard

### Prioritas 3 — Reporting & Analytics

9. **ReportController** — 4 jenis laporan
10. **Admin\KpiPeriodController** — Pemagang terbaik + sertifikat
11. **Admin\KpiAnalyticsController** — Analitik KPI

### Prioritas 4 — Supporting

12. **ArchiveController** — Arsip data
13. **NotificationController** — Sistem notifikasi real-time

---

_Dokumen ini di-generate otomatis berdasarkan analisis kode sumber project MIP._
