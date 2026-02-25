# 💰 MODUL UANG SAKU (ALLOWANCE)

## Overview

Modul Uang Saku untuk mengelola pembayaran uang saku pemagang berdasarkan kehadiran mereka.

## ✅ Status: SELESAI

## 📋 Fitur yang Sudah Dibuat

### 1. **Models** (3 files)

- ✅ `AllowancePeriodModel.php` - Mengelola periode pembayaran
- ✅ `AllowanceModel.php` - Mengelola data uang saku per pemagang
- ✅ `AllowanceSlipModel.php` - Mengelola slip pembayaran

### 2. **Controller**

- ✅ `AllowanceController.php` dengan 9 methods:
  - `my()` - Untuk intern melihat riwayat uang saku
  - `period()` - Untuk admin/HR mengelola periode
  - `createPeriod()` - Untuk membuat periode baru
  - `index()` - Untuk melihat daftar allowances per periode
  - `calculate()` - Untuk menghitung uang saku berdasarkan kehadiran
  - `payment()` - Untuk finance melihat antrian pembayaran
  - `processPayment($id)` - Untuk memproses pembayaran
  - `downloadSlip($id)` - Untuk download slip pembayaran
  - Helper methods: `countWorkingDays()`, `generateSlip()`, `generateSlipHTML()`

### 3. **Views** (4 files)

- ✅ `my.php` - Untuk intern (riwayat uang saku + download slip)
- ✅ `period.php` - Untuk admin/HR (kelola periode + kalkulasi)
- ✅ `index.php` - Untuk admin/HR/Finance (lihat detail per periode)
- ✅ `payment.php` - Untuk finance (proses pembayaran)

### 4. **Routes**

- ✅ Routes sudah ditambahkan di `Routes.php`:
  - `GET /allowance/my` (intern)
  - `GET /allowance/slip/{id}` (intern)
  - `GET /allowance/period` (admin/HR/Finance)
  - `POST /allowance/period/create` (admin/HR)
  - `GET /allowance` (admin/HR/Finance)
  - `POST /allowance/calculate` (admin/HR)
  - `GET /allowance/payment` (finance)
  - `POST /allowance/process-payment/{id}` (finance)

### 5. **Upload Directories**

- ✅ `writable/uploads/bukti_transfer/` - Untuk bukti transfer finance
- ✅ `writable/uploads/slips/` - Untuk slip pembayaran yang digenerate

## 🔄 Alur Proses Bisnis

### 1. Admin/HR Membuat Periode

1. Login sebagai Admin/HR
2. Buka menu **Uang Saku** → **Periode**
3. Klik tombol **Buat Periode Baru**
4. Isi form:
   - Nama Periode (contoh: "Uang Saku Januari 2026")
   - Tanggal Mulai (contoh: 15 Desember 2025)
   - Tanggal Selesai (contoh: 14 Januari 2026)
5. Klik **Simpan**
6. Status periode: **Draft**

### 2. Admin/HR Menghitung Uang Saku

1. Di halaman **Periode**, klik tombol **Hitung** pada periode yang masih Draft
2. Sistem akan:
   - Menghitung hari kerja (exclude weekend)
   - Mengambil data kehadiran dari tabel `attendances`
   - Menghitung total: `total_hadir × rate_per_hari`
   - Mengambil data rekening dari tabel `interns`
   - Menyimpan ke tabel `allowances`
3. Status periode berubah menjadi: **Terhitung**

### 3. Admin/HR Melihat Detail

1. Buka menu **Uang Saku** untuk melihat detail per periode
2. Bisa memilih periode dari dropdown
3. Melihat breakdown per pemagang:
   - NIK, Nama, Divisi
   - Total Hadir, Alpha
   - Total Uang Saku
   - Data Rekening

### 4. Finance Memproses Pembayaran

1. Login sebagai Finance
2. Buka menu **Pembayaran Uang Saku**
3. Akan melihat daftar pemagang yang pembayarannya sudah **Approved**
4. Klik tombol **Proses** pada setiap pemagang
5. Isi form:
   - Tanggal Transfer (auto-fill hari ini)
   - Upload Bukti Transfer (optional)
   - Catatan (optional)
6. Klik **Konfirmasi Pembayaran**
7. Sistem akan:
   - Update status menjadi **Paid**
   - Generate slip pembayaran otomatis
   - Menyimpan slip dengan nomor format: `SLIP/YYYY/MM/0001`

### 5. Intern Melihat Riwayat & Download Slip

1. Login sebagai Intern
2. Buka menu **Uang Saku Saya**
3. Melihat riwayat semua periode:
   - Detail kehadiran (hadir, alpha, izin, sakit)
   - Total yang diterima
   - Status pembayaran
4. Jika status **Dibayar**, bisa download slip dengan klik tombol **Download**

## 📊 Struktur Database

### Tabel: `allowance_periods`

- id_period (PK, auto increment)
- nama_periode
- tanggal_mulai, tanggal_selesai
- status (draft/calculated/approved/paid)
- total_pemagang, total_nominal
- calculated_at, calculated_by
- approved_at, approved_by
- paid_at, paid_by

### Tabel: `allowances`

- id_allowance (PK, auto increment)
- id_period (FK)
- id_user (FK)
- total_hari_kerja
- total_hadir, total_alpha, total_izin, total_sakit
- rate_per_hari
- total_uang_saku
- nomor_rekening, nama_bank, atas_nama
- status_pembayaran (pending/approved/paid)
- tanggal_transfer, bukti_transfer, catatan

### Tabel: `allowance_slips`

- id_slip (PK, auto increment)
- id_allowance (FK)
- nomor_slip (format: SLIP/YYYY/MM/0001)
- file_path
- generated_at, generated_by

## 🎨 Fitur UI

### Modern & User-Friendly

- ✅ **Responsive layout** dengan Bootstrap grid
- ✅ **Color-coded badges** untuk status:
  - Draft: Gray
  - Calculated: Blue
  - Approved: Yellow
  - Paid: Green
- ✅ **Empty states** dengan icon dan pesan yang jelas
- ✅ **Summary cards** dengan avatar icons
- ✅ **Modal forms** untuk input data
- ✅ **AJAX submissions** dengan SweetAlert confirmations
- ✅ **Inline validation** dengan error messages
- ✅ **Loading states** pada tombol submit

### Keamanan

- ✅ **CSRF Protection** pada semua form
- ✅ **Role-based access** (auth filters)
- ✅ **File upload validation** (max 2MB, image only)
- ✅ **Protected uploads directory** dengan index.html

## 🔧 Konfigurasi

### Setting Rate Per Hari

Sistem akan mengambil rate dari tabel `settings` dengan key `allowance_rate_per_day`.
Default: **Rp 100,000** per hari

Untuk mengubah rate:

```sql
INSERT INTO settings (setting_key, setting_value)
VALUES ('allowance_rate_per_day', '150000')
ON DUPLICATE KEY UPDATE setting_value = '150000';
```

## 📝 Catatan Penting

### 1. **Kalkulasi Otomatis**

- Sistem menghitung hari kerja (exclude weekend)
- Hanya pemagang dengan status `active` yang dikalkulasi
- Data kehadiran diambil dari tabel `attendances` sesuai periode

### 2. **Rekening Bank**

- Data rekening diambil dari tabel `interns`
- Jika rekening belum ada, finance tidak bisa memproses pembayaran
- Rekening harus diisi di profile intern terlebih dahulu

### 3. **Slip Pembayaran**

- Format nomor: `SLIP/2026/02/0001` (auto increment per bulan)
- File disimpan sebagai HTML (bisa diubah ke PDF dengan DOMPDF)
- Hanya bisa didownload oleh intern yang bersangkutan

### 4. **Status Workflow**

Period:

- `draft` → belum dihitung
- `calculated` → sudah dihitung, menunggu approval
- `approved` → disetujui, siap dibayar
- `paid` → sudah dibayar semua

Allowance:

- `pending` → baru dihitung, menunggu approval
- `approved` → disetujui, antrian pembayaran
- `paid` → sudah dibayar dan slip generated

## ✅ Checklist Testing

Sebelum deploy ke production, test:

- [ ] Admin/HR bisa membuat periode baru
- [ ] Sistem menghitung uang saku dengan benar
- [ ] Finance bisa melihat antrian pembayaran
- [ ] Finance bisa proses pembayaran dengan upload bukti
- [ ] Slip ter-generate otomatis dengan nomor urut benar
- [ ] Intern bisa melihat riwayat uang saku
- [ ] Intern bisa download slip setelah paid
- [ ] Validasi form berfungsi dengan baik
- [ ] Role-based access control berfungsi
- [ ] Error handling berfungsi dengan baik

## 🚀 Next Steps

Setelah modul ini selesai ditest, prioritas selanjutnya:

1. **KPI System** (7 modules)
2. **Audit Log**
3. **Reports**
4. **Archive & Notifications**

---

**Created:** 2026-02-XX  
**Status:** ✅ Ready for Testing  
**Developer:** GitHub Copilot
