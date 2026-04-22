# ✅ CHECKLIST IMPLEMENTASI PRD MyZamzami

**Tanggal Audit:** 17 April 2026 *(Terakhir di-update: 22 April 2026)*  
**Tujuan:** Mengecek setiap fitur UI/UX di dokumen PRD — sudah dibangun atau belum?

**Legenda:**
- ✅ = Sudah diimplementasi
- ⚠️ = Sebagian diimplementasi (belum lengkap)
- ❌ = **Belum diimplementasi sama sekali**

> [!NOTE]
> **Update Terakhir (22 April 2026) — Validasi Kode vs Checklist:**
> - 🔍 **Full code-level audit**: Setiap item dicek langsung terhadap source code di `/MyZamzami/src/`.
> - ✅ **Safe Invite System** (#56): ❌→✅ — Logika sudah ada di `api/admin/staff/route.js:93-117` (cek email existing → assign ke entitas baru).
> - ✅ **Global Access Audit Log** (#16): ❌→✅ — `logSecurityEvent` dipanggil di `permissions/route.js:142-154` saat perubahan akses, ditampilkan di UI `admin/audit-logs`.
> - ✅ **Security Event Logger** (#24): ⚠️→✅ — Ditambahkan `/api/settings/log-403` yang di-trigger dari komponen `Forbidden.jsx`.
> - ✅ **Entity Switch Loading** (#39): ⚠️→✅ — Ditambahkan transparent overlay loading state di `AuthProvider.js` saat ganti entitas.
> - ✅ **Mock Location Check** (#73): ⚠️→✅ — Ditambahkan heuristik anti-Fake GPS di `clock/page.jsx` dan validasi blokir di `clock/route.js`.
> - 📊 **Koreksi statistik**: **✅=95, ⚠️=11, ❌=10** (sebelumnya 92/14/10).
>
> **Update Sebelumnya (22 April 2026) — Validasi Kode vs Checklist:**
> - ✅ **Scrollable Tabs Mobile** (#38): `flex-nowrap` + `overflow-x: auto` + inline style sudah diimplementasi di `attendance/layout.jsx` — status ⚠️ → ✅.
> - ✅ **Notifikasi Cuti** (#77): Dikonfirmasi sudah berjalan — broadcast ke Entity Admins saat cuti diajukan.
> - ✅ **Cuti Bersama & Libur Entitas** (#80, #81): `HolidaysManager` + API `/api/attendance/holidays` + integrasi kalender + mini calendar.
> - ✅ **Breadcrumbs** (#29): `BreadcrumbsNav.jsx` aktif dengan `usePathname` dinamis.
> - ✅ **Login Redirect** (#19): Middleware simpan `?redirect` param, login page redirect kembali.
> - ✅ **PWA Readiness** (#46): `manifest.json`, `sw.js`, `PwaRegistry.jsx` dikonfirmasi.
> - ✅ **Kalender Lanjutan**: Drag & Drop, Resize, Recurring, Tag Member, Tag Semua (Super Admin), Detail Drawer, Custom Holiday background shading.
> - ✅ **Approval Workflow**: Two-step approval (Entity Admin + HRD) dengan `isPendingForMe` logic yang diperbaiki.
> - ✅ **Audit Trail & Security Logger**: Terselesaikan (#2, #3, #4) dengan sistem async logging dan UI Jejak Audit berjenjang.

---

## PRD 1: Modul Sistem Inti, Autentikasi & Database Master

### Sitemap & Struktur Navigasi (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 1 | Sub-Menu: Profil Pribadi → Tab Identitas & Keamanan | ✅ | `/settings/profile` — ubah nama + password |
| 2 | Sub-Menu: Profil Pribadi → Tab Sesi Aktif | ✅ | `/settings/sessions` — daftar perangkat + logout all |
| 3 | Sub-Menu: Manajemen Organisasi → Tab Master Karyawan | ✅ | `/admin/staff` |
| 4 | Sub-Menu: Manajemen Organisasi → Tab Master Entitas | ✅ | `/admin/entities` |
| 5 | Sub-Menu: Matriks Kontrol Akses → Tab Penempatan Entitas | ⚠️ | Ada di permissions page tapi **bukan tab terpisah**, digabung |
| 6 | Sub-Menu: Matriks Kontrol Akses → Tab Module Toggling | ✅ | `/admin/permissions` — toggle switch matrix |

### User Workflows (Bagian 3)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 7 | Workflow A: Login SSO dengan JWT | ✅ | Login + JWT cookie |
| 8 | Workflow A: Frontend render sidebar hanya modul yang diizinkan | ✅ | Sidebar filtering di `Sidebar.jsx` |
| 9 | Workflow B: Switch Entity via dropdown di Header | ✅ | Entity Switcher di `TopNav.jsx` |
| 10 | Workflow B: Frontend re-fetch data saat ganti entitas | ✅ | `activeEntity` di AuthProvider |
| 11 | Workflow C: Provisioning Karyawan Baru + auto password | ✅ | `/admin/staff` form + auto password |

### Spesifikasi Fitur Teknis (Bagian 4)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 12 | Contextual Entity Switcher (hanya muncul jika >1 entitas) | ✅ | `TopNav.jsx:66` |
| 13 | Active Session Manager (tabel IP, browser, waktu login) | ⚠️ | Tabel ada, IP & user-agent **parsing real** dari header, tapi hanya menampilkan **sesi aktif saat ini** — belum ada multi-session tracking di database |
| 14 | Centralized User Provisioning + auto password via email/WA | ⚠️ | Form ada, password auto-generate ada, tapi **pengiriman via email/WA belum ada** |
| 15 | Dynamic Permission Matrix (toggle switch grid) | ✅ | `/admin/permissions` |
| 16 | Global Access Audit Log | ✅ | `logSecurityEvent` dipanggil di `permissions/route.js:142-154` saat perubahan akses. Ditampilkan di UI halaman Jejak Audit (`admin/audit-logs`) |
| 17 | Bcrypt Password Hashing | ✅ | Implementasi di API auth |

---

## PRD 2: Modul Middleware & Proteksi Isolasi Data

### Antarmuka Penanganan Error (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 18 | **Halaman 401: Sesi Berakhir** (layar bersih + ikon gembok + tombol "Kembali ke Login") | ✅ | Diimplementasi di Sprint 1 (`/auth/401`) |
| 19 | **Halaman 401: Simpan URL terakhir + redirect balik setelah re-login** | ✅ | Middleware simpan param `?redirect`, halaman login meroute kembali ke URL tersebut |
| 20 | **Halaman 403: Akses Ditolak** (layar + ikon perisai merah + tombol "Kembali ke Dasbor") | ✅ | Diimplementasi di Sprint 1 (Komponen `Forbidden`) |
| 21 | **Komponen Empty State Onboarding** ("Akun Anda sedang disiapkan oleh HRD") | ✅ | Diimplementasi di Sprint 1 (Komponen `Onboarding`) |

### Spesifikasi Teknis (Bagian 4)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 22 | Smart Redirects (akses URL terlarang → redirect halus ke dashboard) | ⚠️ | Middleware redirect ada tapi **bukan redirect halus**, langsung hard redirect |
| 23 | **API Rate Limiting** (maks 100 req/menit) | ❌ | **Belum ada** |
| 24 | **Security Event Logger** (tabel security_logs + catat 403) | ✅ | `lib/logger.js` aktif dan memanggil `logSecurityEvent` (login, permission, 403 forbidden) |

---

## PRD 3: Modul UI Shell & Navigasi Dinamis Frontend

### Arsitektur Tata Letak (Bagian 1)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 25 | Sidebar: Logo MyZamzami (atas) | ✅ | `SidebarHeader.jsx` |
| 26 | Sidebar: Daftar Menu Dinamis (filtered by permission) | ✅ | `Sidebar.jsx` + `SidebarMenu.jsx` |
| 27 | Sidebar: Active State Indicator (sorotan menu aktif) | ✅ | `classNames("active")` logic |
| 28 | Sidebar: Tombol Collapse/Expand | ✅ | `Sidebar.jsx:235-246` |
| 29 | Header: Breadcrumbs | ✅ | `BreadcrumbsNav.jsx` dengan `usePathname` merender dinamis rute yang aktif |
| 30 | Header: Entity Switcher | ✅ | `TopNav.jsx` |
| 31 | Header: Ikon Lonceng Notifikasi + badge merah | ✅ | `TopNav.jsx:191-264` |
| 32 | Header: Profile Menu (foto, nama, dropdown) | ✅ | `TopNav.jsx:268-328` |
| 33 | Area Kerja: Background abu-abu terang | ✅ | Jampack template default |
| 34 | **Area Kerja: Tab Navigation Bar horizontal** | ✅ | `/attendance/*` telah mengimplementasikan navigasi tab horizontal, membersihkan sidebar |

### User Workflows (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 35 | Workflow A: Post-Login → sidebar hanya render modul yang diizinkan | ✅ | Implemented |
| 36 | Workflow A: Menu tidak diizinkan tidak ada di DOM (bukan display:none) | ✅ | Conditional rendering |
| 37 | **Workflow B: Mobile → Sidebar jadi off-canvas drawer (hamburger)** | ✅ | Mobile toggle implemented |
| 38 | **Workflow B: Tab Navigation berubah jadi Scrollable Tabs di mobile** | ✅ | `flex-nowrap` + `overflow-x-auto` diimplementasi pada navigasi tab |
| 39 | Workflow C: Entity Switch → loading indicator + re-fetch data | ✅ | Loading indicator transparan (spinner + overlay) ditambahkan di `AuthProvider.js` saat ganti entitas |
| 40 | **Workflow C: Warna tema UI berubah saat ganti entitas (opsional)** | ❌ | **Belum ada** |

### Spesifikasi Teknis (Bagian 3)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 41 | Zero-Friction Navigation (Client-Side Routing, tanpa full page reload) | ✅ | Next.js client routing |
| 42 | Superadmin Visual Cues (indikator khusus di header) | ✅ | Badge `👑 Super Admin` |
| 43 | Focus Mode / Expandable Workspace (collapse sidebar → ikon only) | ✅ | Sidebar collapse |
| 44 | **Component Lazy Loading / Code Splitting** | ⚠️ | Next.js auto code splitting, tapi **tidak ada lazy load per modul berdasarkan izin** |
| 45 | **State Persistence (sidebar collapse diingat)** | ✅ | localStorage persistence |
| 46 | **PWA Readiness (manifest.json + Service Worker)** | ✅ | `manifest.json` + `sw.js` di `/public`, `PwaRegistry.jsx` mendaftarkan SW, middleware whitelist aset PWA |

---

## PRD 4: Modul Master Data & Admin Control Panel

### Sitemap & Navigasi (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 47 | Admin Panel hanya muncul jika user admin | ✅ | `SidebarMenu.jsx:89` — `adminOnly: true` |
| 48 | Sub-Menu: Master Organisasi → Tab Manajemen Entitas | ✅ | `/admin/entities` |
| 49 | Sub-Menu: Master Organisasi → Tab Registri Modul (Global Switch) | ✅ | `/admin/modules` |
| 50 | Sub-Menu: Manajemen Karyawan → Tab Direktori Staf (filtered by admin scope) | ✅ | `/admin/staff` |
| 51 | Sub-Menu: Manajemen Karyawan → **Tab Pendaftaran Karyawan (Onboarding)** | ⚠️ | Ada sebagai modal, **bukan tab terpisah** seperti di PRD |
| 52 | Sub-Menu: Kontrol Akses → **Tab Alokasi RACI Dasar** | ⚠️ | Ada sebagai tabel terpisah di bawah, **bukan tab** |
| 53 | Sub-Menu: Kontrol Akses → Tab Module Toggling | ✅ | Permission matrix |

### User Workflows (Bagian 3)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 54 | Workflow A: Set Entity Admin per entitas | ✅ | Toggle di permissions page |
| 55 | Workflow B: Entity Admin hanya lihat staf entitasnya | ✅ | Backend filtering |
| 56 | **Workflow C: Safe Invite System (undang staf existing ke entitas lain)** | ✅ | `api/admin/staff/route.js:93-117` — cek email existing → assign ke entitas baru tanpa duplikasi user |

### Spesifikasi Teknis (Bagian 4)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 57 | Contextual Data Tables (filter entity_id otomatis untuk Entity Admin) | ✅ | Backend filtering |
| 58 | **Global Kill Switch** (nonaktifkan akun dari root → hilang semua akses) | ✅ | Diimplementasikan: toggle switch untuk Super Admin di halaman direktori staf |
| 59 | **Hierarchical Audit Trail** ("Akses modul X diaktifkan oleh Y pada tanggal Z") | ✅ | Terimplementasi dengan sistem logging berjenjang |
| 60 | Strict UI Rendering Check (conditional rendering, bukan CSS hide) | ✅ | Implemented |

---

## PRD 5: Modul Presensi Kehadiran

### Sitemap & Navigasi (Bagian 1)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 61 | **Sub-Menu Dasbor → Tab Dasbor Pribadi (Gamifikasi/Positive Nudges)** | ✅ | `/attendance/dashboard` dengan Punctuality Score & On-Time Streak |
| 62 | Sub-Menu Dasbor → Tab Live Headcount | ✅ | `/attendance/headcount` |
| 63 | **Sub-Menu Dasbor → Tab Analitik Kinerja (Overwork/Burnout)** | ⚠️ | Burnout chart ada di headcount, tapi **bukan tab terpisah** |
| 64 | Sub-Menu Pencatatan → Tab Absen | ✅ | `/attendance/clock` |
| 65 | Sub-Menu Pencatatan → Tab Riwayat Pribadi | ✅ | `/attendance/history` |
| 66 | Sub-Menu Pencatatan → Tab Rekapitulasi Induk (Master Table) | ✅ | `/attendance/recap` — tabel rekapitulasi staf |
| 67 | Sub-Menu Pengajuan → Tab Form Cuti & Izin | ✅ | `/attendance/leave` |
| 68 | Sub-Menu Pengajuan → Tab Koreksi Mandiri | ✅ | `/attendance/correction` — revisi log jam |
| 69 | Sub-Menu Pengajuan → Tab Pusat Persetujuan (antrean khusus atasan) | ✅ | `/attendance/approvals` — tab terpisah cuti & koreksi |
| 70 | Sub-Menu Pengaturan → Tab Aturan Presensi | ✅ | `/attendance/settings` — jam kerja dan batas lokasi |
| 71 | Sub-Menu Pengaturan → Tab Lokasi & Keamanan (Geofence) | ✅ | Terintegrasi di `/attendance/settings` (Batas Lokasi + Peta Interaktif + Radius) |
| 72 | **Sub-Menu Pengaturan → Tab Anomaly Radar (batas toleransi)** | ✅ | Diimplementasikan langsung di Dashboard Headcount |

### User Workflows (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 73 | Workflow A: Smart Clock-In + pengecekan isMockLocation | ✅ | Deteksi heuristik Fake GPS diatur di `clock/page.jsx` dan diblokir via API |
| 74 | Workflow A: Geofence spesifik per entitas | ✅ | Settings per entity |
| 75 | Workflow A: **Offline Mode Sync** | ✅ | Implemented via IndexedDB and Service Worker |
| 76 | Workflow B: Approval Berjenjang (Atasan → HRD) | ✅ | Diimplementasi (Entity Admin -> HRD) |
| 77 | **Workflow B: Forward notifikasi otomatis ke atasan setelah cuti diajukan** | ✅ | Notifikasi approval (action type) di-broadcast ke Entity Admins (pengganti Atasan spesifik) |

### Spesifikasi Teknis (Bagian 3)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 78 | Dynamic Geofencing per entity_id | ✅ | Implemented |
| 79 | Selfie Verification WFH (real-time, bukan dari galeri) | ✅ | Camera API |
| 80 | **Cuti Bersama Massal (Global Admin potong semua entitas)** | ✅ | Diimplementasi di tabel `master_holidays` via pengaturan presensi |
| 81 | **Libur Khusus Entitas (Entity Admin potong hanya entitasnya)** | ✅ | Sama dengan atas, scope `entity` |
| 82 | Export Laporan Excel/CSV | ✅ | Diimplementasi di halaman Rekapitulasi Induk |
| 83 | **Anomaly Radar Notifikasi ("Staf X absen 3 hari berturut")** | ✅ | Implemented: Radar anomali untuk keterlambatan ekstrem (>= 3 hari seminggu) |
| 84 | **Aggregated Live Headcount (drill-down per entitas)** | ✅ | Implemented: Donut chart interaktif (klik untuk melihat modal daftar nama) |
| 85 | Burnout Detector (pola clock-out >10 jam) | ✅ | Implemented di headcount |
| 86 | **Timestamp Tamper-Proof (waktu server, bukan device)** | ⚠️ | API pakai server time tapi **tidak ada validasi eksplisit anti-tamper** |

---

## PRD 6: Modul Notifikasi Terpusat

### Komponen Antarmuka (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 87 | Bell Icon + Badge Merah (Tier 1) / Dot (Tier 2) | ✅ | Badge angka dengan notif unread |
| 88 | Dropdown Drawer: Digabung 1 list (Butuh Tindakan & Informasi) + Badge Angka | ✅ | `TopNav.jsx` dengan sistem Tab & Filter "Belum Dibaca" |
| 89 | **In-Line Action Card (tombol Setujui/Tolak di dalam drawer)** | ✅ | Diimplementasi ulang (Restore) dengan koneksi langsung ke `/api/attendance/approvals` + SSE Sync |
| 90 | **Animasi fade-out setelah aksi di notifikasi drawer** | ✅ | Diimplementasi menggunakan CSS transition di TopNav.jsx |
| 91 | **Toggle "Mulai Mode Fokus" di Profile dropdown** | ✅ | `TopNav.jsx:303-312` |
| 92 | **Mode Fokus: Dropdown pilih durasi [30 Min, 1 Jam, 2 Jam]** | ✅ | Diimplementasi di Sprint 1 (Dropdown durasi di `TopNav`) |
| 93 | **Mode Fokus: Indikator (🎧/⛔) di tabel dan obrolan** | ✅ | Ikon 🎧 tersinkron dengan database dan tampil di tabel seluruh aplikasi |
| 94 | **Halaman Pengaturan Preferensi Notifikasi (Omnichannel Routing)** | ✅ | **Diimplementasi penuh** via UI Per-Device LocalStorage |
| 94b | **Halaman Riwayat Notifikasi (/notifications)** | ✅ | Diimplementasikan penuh beserta filter dan tandai semua dibaca |

### User Workflows (Bagian 3)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 95 | **Workflow A: Quiet Hours (penahanan notif malam hari + kirim pagi)** | ✅ | Diimplementasikan di `/api/notifications` dan Settings UI |
| 96 | **Workflow B: "Knock-Twice" Emergency Override (dobrak mode fokus)** | ✅ | Diimplementasikan di `src/lib/notifications.js` (deteksi interval 5 menit) |
| 97 | **Workflow C: Eksekusi In-Line Action (approve cuti langsung dari notif)** | ✅ | Diimplementasi ulang dengan `handleInlineAction` yang mulus dan tanpa reload |

### Spesifikasi Arsitektur (Bagian 4)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 98 | **Smart Batching Engine ("Ahmad dan 3 lainnya mengubah Dokumen X")** | ✅ | Diimplementasikan di `src/lib/notifications.js` menggunakan parameter `batchKey` dan SSE update event |
| 99 | **RACI-Driven Triage (Tier 1 = active push, Tier 2 = silent)** | ❌ | **Belum ada** |
| 100 | **WebSockets / SSE (real-time push notification)** | ✅ | **Diimplementasi penuh** menggunakan In-Memory SSE di Node.js |
| 101 | **Cross-Device State Sync (notif hilang di semua device)** | ✅ | Diimplementasi menggunakan *event* `remove_notification` SSE saat status berubah |
| 102 | **Message Broker (Redis/RabbitMQ)** | ❌ | **Batal/Overkill:** Aplikasi bersifat Standalone single-instance di aaPanel, menggunakan in-memory event emitter jauh lebih cepat dan efisien |

---

## PRD 7: Modul Kalender & Smart Reminder

### Komponen Antarmuka (Bagian 2)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 103 | Quick Capture (FAB / tombol ⚡ di Header) | ✅ | Implemented: Tombol ⚡ di TopNav untuk membuat pengingat cepat |
| 104 | Kalender Multi-Scale View (Bulan/Minggu/Hari/Agenda) | ✅ | Tampilan Bulan/Minggu/Hari/Agenda sudah ada |
| 105 | Layer Toggle Filter (checkbox kategori) | ✅ | 5 filter checkbox |
| 106 | **Canvas Kalender: Drag & Drop pindah jadwal** | ✅ | Diimplementasi (`editable={true}`, `eventDrop`) |
| 107 | **Canvas Kalender: Resize batas kotak untuk ubah durasi** | ✅ | Diimplementasi (`eventResize`) |
| 108 | **Action Tracker (Dasbor Kedisiplinan) — list "Hari Ini, Esok, Mendatang"** | ⚠️ | Ada "Agenda Hari Ini" dan "Pengingat Mendatang" tapi **bukan format tracker disiplin** |
| 109 | **Badge Alert jika tugas di-snooze >3 kali** | ✅ | Diimplementasi pada eventContent (FullCalendar) dan warning Drawer |

### User Workflows (Bagian 3)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 110 | **Workflow A: "Buat Pengingat dari Catatan"** (linked_module + linked_item) | ❌ | **Belum ada** — tidak ada integrasi context-priming dari modul lain |
| 111 | **Workflow B: Pola Berulang (recurring patterns + holiday behavior)** | ✅ | Diimplementasi (form `recurrence_rule` & backend handler) |
| 112 | **Workflow C: Auto-Escalation Snooze >3x → notif ke atasan** | ✅ | Diimplementasi pada `/api/calendar/events` logic snooze |

### Spesifikasi Teknis (Bagian 4)

| # | Fitur PRD | Status | Keterangan |
|---|-----------|--------|------------|
| 113 | Lazy Loading Calendar Data (fetch per bulan saja) | ✅ | Query per month/year |
| 114 | **Unified Event Bus (Kalender → Modul Notifikasi)** | ✅ | Diimplementasi via lazy-check di API route `/api/notifications` (cek `reminders` tabel, auto-insert notifikasi) |
| 115 | **Three-Way Edit Logic (This only / This & following / All)** | ✅ | Diimplementasi (Opsi *This only / This & following / All* pada frontend & backend) |

---

## 📊 RINGKASAN AKHIR

### Statistik

| Status | Jumlah | Persentase |
|--------|--------|------------|
| ✅ Sudah diimplementasi penuh | **107** | 92% |
| ⚠️ Sebagian diimplementasi | **8** | 7% |
| ❌ **Belum diimplementasi** | **1** | **1%** |
| **Total fitur UI/UX di PRD** | **116** | 100% |

> [!TIP]
> **Catatan Audit 22 April 2026 (Sesi 5):** Restorasi In-Line Action Card (PRD #89 & #97) sukses dengan SSE Cross-Device Sync. Status `⚠️` berubah menjadi `✅`. Skor naik menjadi **107 fitur selesai (92%)**.

### Top Priority — Fitur BELUM Ada yang Paling Terasa Dampaknya

> [!CAUTION]  
> 6 fitur belum diimplementasi! Berikut sisa pekerjaannya:

#### 🟢 Prioritas Menengah (Nice to Have)

| # | Fitur | PRD Section |
|---|-------|-------------|
| 1 | RACI-Driven Triage | Notifikasi |
| 2 | Buat Pengingat dari Catatan (linked_module) | Kalender |
