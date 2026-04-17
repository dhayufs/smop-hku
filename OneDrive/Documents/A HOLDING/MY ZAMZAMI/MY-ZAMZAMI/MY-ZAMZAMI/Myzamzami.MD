# MyZamzami (my.zamzami.or.id)

**MyZamzami** adalah sebuah ekosistem ruang kerja digital terpusat (_Enterprise Resource Planning & Human Resource Information System hybrid_) yang dirancang khusus untuk mengonsolidasikan seluruh operasional, manajemen kinerja, dan kolaborasi antar-entitas di bawah naungan Zamzami Internasional.

Sistem ini berfungsi sebagai _Single Source of Truth_ (sumber data tunggal) bagi entitas yang memiliki karakteristik operasional yang sangat berbeda-mulai dari pendidikan pesantren (Madinatul Qur'an), komersial travel (HaramainKU), edutech (Bimbingan Islam), hingga lembaga filantropi (Cinta Sedekah)-tanpa mengorbankan isolasi, privasi, dan tata kelola data masing-masing unit.

#### **Karakteristik Utama Sistem:**

- **Arsitektur Multi-Tenant yang Terisolasi Secara Logis** Sistem beroperasi pada satu basis data tunggal untuk memudahkan pengelolaan infrastruktur, namun dilengkapi dengan dinding isolasi data (_logical isolation_) yang ketat. Setiap transaksi dan data diikat dengan identitas entitas, memastikan kerahasiaan dan kepatuhan struktural antar unit bisnis.
- **Tata Kelola Akses Presisi Tinggi (Hybrid RBAC & RACI Matrix)** Sistem tidak menggunakan pendekatan _role-based_ tradisional yang kaku. Otorisasi dibangun di atas dua pilar:
  - **Statis & Terkendali:** _Superadmin_ memegang kendali absolut melalui fitur _Module Toggling_ untuk menyembunyikan atau menampilkan modul spesifik per individu, menjaga UI tetap bersih dan relevan dengan beban kerja staf.
  - **Dinamis & Lintas Fungsional:** Di dalam modul kolaboratif (seperti Manajemen Proyek), hak akses beradaptasi secara dinamis menggunakan parameter **RACI (Responsible, Accountable, Consulted, Informed)**, mendukung perbaikan proses bisnis (BPI) yang tangkas tanpa merusak hierarki struktural.
- **Fleksibilitas Lintas Platform (_API-First & Omnichannel_)** Dibangun dengan pendekatan _API-First_, antarmuka sistem menjamin pengalaman pengguna (UI/UX) yang identik, responsif, dan intuitif baik saat diakses melalui _browser_ desktop, tablet, maupun aplikasi _native_ Android. Struktur navigasi dirancang secara hierarkis (Modul > Sub-modul > Tab) untuk meminimalkan beban kognitif pengguna (_cognitive load_).
- **Integrasi Taktis ke Strategis** Sistem menjembatani jurang antara visi manajemen dan eksekusi harian. Modul taktis operasional (Presensi, TodoList, Penugasan, Notulen, Reminder) secara arsitektural dipersiapkan untuk terintegrasi dengan modul strategis tingkat tinggi (OKR, KPI, dan Dashboard), menciptakan transparansi dan akuntabilitas performa secara _real-time_.

**Tujuan Akhir (Ultimate Goal):** Menciptakan budaya kerja yang transparan, terukur, dan efisien melalui satu aplikasi serbaguna yang sangat mudah digunakan (_user-friendly_), memastikan setiap staf dari berbagai latar belakang literasi digital dapat mengadopsi sistem ini dengan kurva pembelajaran yang minimal.

# PRD: Modul Sistem Inti, Autentikasi & Database Master (MyZamzami)

**Instruksi Global untuk AI:** Bangun fondasi utama sistem ini menggunakan arsitektur _API-First_. Terapkan Single Sign-On (SSO) berbasis JSON Web Token (JWT). Buat skema database relasional (PostgreSQL/MySQL) yang mendasari sistem isolasi _multi-tenant_ (_logical isolation_) menggunakan parameter entity_id. Skema ini adalah prasyarat wajib sebelum modul bisnis apa pun dibangun.

## 1\. Skema Database Utama (Core Entity Relationship)

**A. Tabel entities (Master Unit Bisnis)**

- id (UUID, Primary Key)
- name (String: Madinatul Qur'an, HaramainKU, Bimbingan Islam, Cinta Sedekah)
- code (String, Unique: MQA, HKU, BIS, CSD)
- is_active (Boolean, Default: True)
- created_at & updated_at (Timestamp)

**B. Tabel users (Master Data Karyawan/Identitas Tunggal)**

- id (UUID, Primary Key)
- employee_id (String, Unique)
- full_name (String)
- email (String, Unique)
- password_hash (String)
- is_superadmin (Boolean, Default: False)
- created_at & updated_at (Timestamp)

**C. Tabel modules (Registri Menu & Antarmuka)**

- id (UUID, Primary Key)
- name (String)
- slug (String, Unique - untuk routing URL)
- parent_id (UUID, Foreign Key ke modules.id, Nullable)
- level (Integer: 1=Menu, 2=Sub-menu, 3=Tab)
- is_active_global (Boolean, Default: True)

**D. Tabel user_entity_access (Pemetaan Multitenant & Matriks RACI Dasar)**

- id (UUID, Primary Key)
- user_id (UUID, Foreign Key ke users.id)
- entity_id (UUID, Foreign Key ke entities.id)
- raci*baseline (Enum: Responsible, Accountable, Consulted, Informed - \_Tingkat akses default staf di entitas tersebut*)
- created_at (Timestamp)

**E. Tabel user_module_permissions (Kontrol Visibilitas Antarmuka)**

- id (UUID, Primary Key)
- user_id (UUID, Foreign Key ke users.id)
- module_id (UUID, Foreign Key ke modules.id)
- is_visible (Boolean, Default: True)

## 2\. Sitemap & Struktur Navigasi (UI/UX Layout)

**Menu Utama:** ⚙️ Pengaturan & Akun

- **Sub-Menu 1: Profil Pribadi**
  - _Tab 1: Identitas & Keamanan_ (Akses: Semua Staf) - Ubah kata sandi, pengaturan 2FA (opsional), dan informasi dasar.
  - _Tab 2: Sesi Aktif_ (Akses: Semua Staf) - Daftar perangkat yang sedang _login_ dengan opsi "Log Out from All Devices".
- **Sub-Menu 2: Manajemen Organisasi** (Akses: Superadmin)
  - _Tab 1: Master Karyawan_ - Antarmuka CRUD untuk tabel users.
  - _Tab 2: Master Entitas_ - Antarmuka CRUD untuk tabel entities.
- **Sub-Menu 3: Matriks Kontrol Akses** (Akses: Superadmin)
  - _Tab 1: Penempatan Entitas_ - Memasukkan/mengeluarkan staf dari entitas tertentu (mengubah user_entity_access).
  - _Tab 2: Module Toggling_ - Tabel matriks _checklist_ untuk menghidupkan/mematikan modul spesifik per staf (mengubah user_module_permissions).

## 3\. User Workflows (Alur Kerja Pengguna)

- **Workflow A: Otentikasi Terpusat (Login SSO)**
  - Pengguna memasukkan email dan kata sandi di halaman _Login_.
  - _Backend_ memvalidasi kredensial ke tabel users.
  - Jika valid, _backend_ mengumpulkan array entity_id dan module_id yang diizinkan untuk pengguna tersebut.
  - _Backend_ menghasilkan JWT berisi profil dasar dan array izin, lalu mengirimkannya ke klien (Web/Android).
  - _Frontend_ mengurai token, merender _Sidebar Menu_ hanya untuk modul yang diizinkan, dan mengarahkan pengguna ke Dasbor.
- **Workflow B: Switch Entity (Pindah Ruang Kerja)**
  - Staf yang terdaftar di >1 entitas mengklik _Dropdown_ Entitas di _Header/Top Bar_.
  - Staf memilih entitas yang berbeda (misal: pindah dari Bimbingan Islam ke Cinta Sedekah).
  - _Frontend_ memuat ulang status aplikasi (_state_) dan menyertakan entity*id baru ini di setiap \_Header Request API* berikutnya. Modul dan data di layar otomatis menyesuaikan.
- **Workflow C: Provisioning Karyawan Baru (Oleh Superadmin)**
  - Superadmin mendaftarkan email dan nama staf baru.
  - Superadmin menugaskan staf ke entitas "Madinatul Qur'an" dengan baseline peran "Responsible".
  - Superadmin membuka tab _Module Toggling_, mencentang modul "Presensi" dan membiarkan modul "Manajemen Proyek" kosong.
  - Staf baru _login_ pertama kali dan hanya melihat modul Presensi di lingkungan Madinatul Qur'an.

## 4\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)

### **Kategori 1: Employee Portal (Akses: Staf)**

- **Seamless Authentication:** Otentikasi menggunakan JWT yang disimpan secara aman (HttpOnly Cookie untuk Web, Encrypted Shared Preferences untuk Android).
- **Contextual Entity Switcher:** Tombol pemilih entitas di navigasi atas yang hanya muncul jika tabel user_entity_access mengembalikan lebih dari 1 entity_id untuk staf tersebut.
- **Active Session Manager:** Tabel _frontend_ yang menampilkan alamat IP, jenis _browser_/_device_, dan waktu _login_ terakhir, memberikan kontrol keamanan mandiri kepada staf.

### **Kategori 2: HRD/Admin Portal (Akses: Superadmin/HRD)**

- **Centralized User Provisioning:** Formulir satu pintu untuk mendaftarkan karyawan. Sistem otomatis menghasilkan kata sandi acak awal dan mengirimkannya via _email/WhatsApp_ (simulasi API).
- **Dynamic Permission Matrix:** Antarmuka berupa tabel _grid_ di mana baris adalah nama staf dan kolom adalah nama modul. Superadmin cukup melakukan _toggle_ _switch_ (on/off) untuk mengatur akses tanpa perlu menyentuh _database_. _Toggle_ ini langsung memicu API _update_ secara asinkron (_debounce_).

### **Kategori 3: Strategic Portal (Akses: Manajemen/System)**

- **Global Access Audit Log:** Fitur pencatatan tak kasat mata (_backend logging_). Setiap perubahan hak akses staf, penghapusan entitas, atau _login_ dari IP yang mencurigakan dicatat dengan stempel waktu untuk keperluan audit kepatuhan ISO/keamanan informasi.

### **Kategori 4: Non-Functional Requirements (Keamanan & Middleware)**

- **Strict Entity Middleware:** Buat _middleware_ global di _backend_ (VerifyEntityIsolation). _Middleware_ ini wajib mengeksekusi pengecekan: Apakah entity*id yang dikirim pada URL/Body Request cocok dengan daftar entity_id yang ada di dalam \_payload* JWT staf yang meminta? Jika tidak, tolak dengan 403 Forbidden.
- **Strict Module Middleware:** Buat _middleware_ (VerifyModuleAccess). Jika staf mencoba memanggil _endpoint_ API dari modul "Manajemen Proyek", _backend_ akan mengecek _payload_ JWT. Jika ID modul tersebut tidak ada, kembalikan 403 Forbidden.
- **Bcrypt Password Hashing:** Seluruh kata sandi di tabel users wajib dienkripsi dengan algoritma Bcrypt (minimal _salt rounds_ 10) sebelum disimpan.

# PRD: Modul Middleware & Proteksi Isolasi Data (MyZamzami)

**Instruksi Global untuk AI:** Modul ini murni berfokus pada arsitektur keamanan _Backend_ (API Gateway) dan penanganan _Error State_ di _Frontend_. Terapkan prinsip _Zero Trust Architecture_. Setiap rute API yang dipanggil wajib melewati 3 lapis pemeriksaan (_Auth, Entity, Module_). Demi performa (_low latency_), _middleware_ **tidak boleh** melakukan _query_ ke _database_; semua validasi akses harus bergantung murni pada _payload_ JWT yang telah didekripsi.

## 1\. Arsitektur Logika Middleware (Lapisan Keamanan API)

**A. Lapisan 1: Authentication Middleware (verify_jwt)**

- **Fungsi:** Memeriksa apakah permintaan HTTP membawa token JWT yang valid di _header_ Authorization: Bearer &lt;token&gt;.
- **Proses:** Memvalidasi _signature_ token dan memastikan masa berlakunya (_expiry time_) belum habis.
- **Tindakan:** Jika gagal, kembalikan status 401 Unauthorized.

**B. Lapisan 2: Entity Isolation Middleware (verify_entity_access)**

- **Fungsi:** Mencegah kebocoran data lintas perusahaan (_Multi-tenant logical firewall_).
- **Proses:** 1. Mengekstrak parameter entity*id yang diminta oleh klien (bisa dari \_URL Params*, _Query String_, atau _Request Body_).  
   2\. Mencocokkan entity*id tersebut dengan array allowed_entities yang ada di dalam \_payload* JWT.
- **Tindakan:** Jika entity*id tidak ada di array pengguna, tolak permintaan dengan status 403 Forbidden dan catat ke \_Security Log*.

**C. Lapisan 3: Module & Action Middleware (verify_module_permission)**

- **Fungsi:** Memastikan staf hanya bisa mengakses API dari modul yang diaktifkan oleh Superadmin.
- **Proses:** Mengecek rute API (misal: /api/attendance/\*) dan memvalidasi apakah ID modul/slug tersebut ada dalam array allowed_modules di JWT.
- **Tindakan:** Jika tidak ada izin, tolak dengan status 403 Forbidden.

## 2\. Sitemap & Antarmuka Penanganan Error (UI/UX Responses)

Karena ini adalah sistem _backend_, komponen _frontend_\-nya berbentuk halaman peringatan (_Fallback Pages_) yang harus dirancang rapi (sesuai _Jampack Template_).

**Menu Tak Terlihat (Error States & Fallbacks):**

- **Halaman 401: Sesi Berakhir**
  - _UI:_ Layar bersih dengan ikon gembok/waktu habis. Pesan: "Sesi Anda telah berakhir demi keamanan. Silakan masuk kembali."
  - _Aksi:_ Tombol besar "Kembali ke Halaman Login". (Sistem _Frontend_ harus menyimpan URL terakhir agar staf dikembalikan ke pekerjaan terakhirnya setelah _login_ ulang).
- **Halaman 403: Akses Ditolak**
  - _UI:_ Layar dengan ikon perisai merah/tanda seru. Pesan: "Anda tidak memiliki otoritas untuk melihat data entitas/modul ini."
  - _Aksi:_ Tombol "Kembali ke Dasbor Utama".
- **Komponen _Empty State_ (Jika belum ada akses)**
  - _UI:_ Jika seorang staf baru _login_ tapi Superadmin belum memasukkannya ke entitas mana pun, tampilkan ilustrasi _onboarding_ dengan pesan: "Akun Anda sedang disiapkan oleh HRD. Silakan hubungi atasan Anda untuk alokasi ruang kerja."

## 3\. User Workflows (Skenario Keamanan)

- **Workflow A: Skenario Normal (Jalur Hijau)**
  - Staf (HaramainKU) menekan menu "Riwayat Presensi".
  - _Frontend_ mengirim permintaan API GET /api/attendance dengan menyertakan entity_id HaramainKU dan Token JWT.
  - _Middleware_ membaca token: Token Valid \$\\rightarrow\$ Staf ini punya akses ke HaramainKU \$\\rightarrow\$ Modul Presensi aktif.
  - Permintaan diteruskan ke _Controller Database_, data dibalas dengan status 200 OK.
- **Workflow B: Percobaan Pelanggaran Akses Lintas Entitas (Jalur Merah)**
  - Staf (HaramainKU) memiliki niat buruk/iseng, mencoba menebak URL atau mengubah _payload_ API menggunakan _Postman/Browser Inspect Element_ untuk menarik data entity_id milik Cinta Sedekah.
  - Permintaan API masuk ke _Backend_.
  - _Middleware_ Lapisan 2 membaca token: Staf ini HANYA punya akses ke HaramainKU.
  - _Middleware_ langsung memblokir permintaan _sebelum_ menyentuh _database_. Mengembalikan 403 Forbidden.
- **Workflow C: Penanganan Sesi Berakhir yang Elegan**
  - Staf sedang mengisi Form Cuti yang panjang, namun Token JWT kedaluwarsa di latar belakang.
  - Staf menekan "Submit". API menolak dengan 401 Unauthorized.
  - _Frontend_ menangkap kode 401, mencegat pesan _error_ tersebut, mengamankan draf form sementara (_local storage_), dan memunculkan _Pop-up Modal_ "Sesi Habis, Masukkan Ulang Kata Sandi".

## 4\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)

### **Kategori 1: Employee Portal (Akses: Staf)**

- **Frictionless Security:** Staf tidak akan menyadari keberadaan _middleware_ ini selama mereka menggunakan aplikasi secara wajar. Pengecekan JWT sangat cepat (di bawah 10 milidetik).
- **Graceful Degradation:** Jika staf mengakses menu melalui tautan (_bookmark_ lama) ke modul yang aksesnya baru saja dicabut oleh Superadmin, mereka tidak akan melihat layar _crash_ (layar putih), melainkan dialihkan secara elegan ke halaman 403.

### **Kategori 2: Admin/HR Portal (Akses: HRD/Superadmin)**

- **Bulletproof Architecture:** HRD tidak perlu khawatir salah klik di UI _frontend_ yang dapat menyebabkan kebocoran data. Meskipun UI _frontend_ mengalami _bug_ dan menampilkan tombol yang tidak seharusnya, _backend API_ tetap menjadi benteng terakhir yang menggagalkan eksekusi.

### **Kategori 3: Kepatuhan & Manajemen Risiko (Strategic/Security)**

- **API Rate Limiting (Pencegahan Spam/DDoS):** Selain 3 lapis otorisasi di atas, tambahkan _middleware_ untuk membatasi jumlah _request_ (misal: maksimal 100 _request_ per menit per IP/User) untuk melindungi server dari serangan atau _looping error_ dari aplikasi klien.
- **Security Event Logger:** Buat tabel security*logs. Setiap kali \_Middleware* memuntahkan kode 403 Forbidden, sistem wajib mencatat _User ID, IP Address, Endpoint yang dituju_, dan _Timestamp_. Data ini penting untuk investigasi jika ada staf yang terus-menerus mencoba mengakses data rahasia.

### **Kategori 4: Non-Functional Requirements (Kinerja Sistem)**

- **Stateless Validation:** _Middleware_ tidak diizinkan melakukan kueri (SELECT \* FROM users...) ke dalam _database_ setiap kali ada _request_ masuk. Hal ini akan membuat _database_ kelebihan beban. Semua informasi hak akses (_Role, Entities, Modules_) **wajib** dikemas ke dalam _Payload_ JWT pada saat _login_, dan _middleware_ hanya bertugas membaca isi token tersebut.

# PRD: Modul UI Shell & Navigasi Dinamis Frontend (MyZamzami)

**Instruksi Global untuk AI:** Bangun _Frontend Application Shell_ menggunakan _framework_ modern (misal: Next.js/React atau Vue) dengan panduan gaya visual berbasis **Jampack Desktop Template** (bersih, minimalis, _padding_ luas). Terapkan pola _State-Driven UI_: jangan pernah melakukan _hardcode_ pada daftar menu. Navigasi harus di-_render_ secara dinamis berdasarkan respons API/Token JWT (allowed*modules). Pastikan aplikasi dirancang dengan pendekatan \_Mobile-First* namun tetap ekspansif dan elegan di layar Desktop.

## 1\. Arsitektur Tata Letak (_Layout Regions_)

**A. Region Kiri: Sidebar Dinamis (Level 1 - Modul Utama)**

- **Karakteristik:** Lebar tetap di Desktop, menjadi _Off-canvas Drawer_ (Menu Hamburger) di Mobile.
- **Komponen:**
  - Logo MyZamzami (atas).
  - Daftar Menu Utama (misal: Dasbor, Presensi, Proyek, Pengaturan).
  - _Active State Indicator_: Sorotan warna/latar belakang berbeda pada menu yang sedang dibuka.
  - Tombol _Collapse/Expand_ (Desktop) untuk meluaskan area kerja.

**B. Region Atas: Header & Top Bar (Level 2 - Sub-menu & Konteks)**

- **Karakteristik:** Melayang (_Sticky/Fixed Top_).
- **Komponen:**
  - _Breadcrumbs_ (Jejak Roti): Menunjukkan lokasi staf saat ini (misal: Presensi / Riwayat Pribadi).
  - **Entity Switcher (Krusial):** _Dropdown_ yang menampilkan logo/nama Entitas yang sedang aktif (HaramainKU, Bimbingan Islam, dll.). Hanya muncul jika staf terdaftar di >1 entitas.
  - _Action Center:_ Ikon Lonceng Notifikasi (dengan _badge_ merah).
  - _Profile Menu:_ Foto profil mini, nama staf, dan menu _dropdown_ (Pengaturan Akun, Log Out).

**C. Region Utama: Area Kerja & Tab (Level 3 - Halaman Fungsional)**

- **Karakteristik:** Area konten utama dengan latar belakang abu-abu terang (#F8F9FA atau serupa) agar kartu konten (_cards_) terlihat menonjol.
- **Komponen:**
  - Judul Halaman.
  - _Tab Navigation Bar_: Navigasi horizontal untuk sub-fitur (contoh di Presensi: Tab _Clock-In_, Tab _Riwayat_, Tab _Cuti_).
  - Konten dinamis yang merespons pilihan Tab.

## 2\. User Workflows (Alur Perilaku UI)

- **Workflow A: Inisialisasi Aplikasi (Post-Login Rendering)**
  - Staf berhasil _login_, _Frontend_ menerima Token JWT.
  - _State Management_ (misal: Redux/Zustand) menyimpan daftar allowed_modules dan allowed_entities.
  - _Frontend_ memetakan (_mapping_) Master Data Menu bawaan sistem dengan allowed_modules milik staf.
  - _Sidebar_ hanya menampilkan ikon/teks menu yang hasil pemetaannya valid (cocok). Menu yang tidak diizinkan diabaikan dari proses _rendering_ (tidak ada di DOM _browser_).
- **Workflow B: Responsivitas Mobile (Layar di bawah 768px)**
  - Staf membuka MyZamzami di _browser_ HP (Android/iOS).
  - _Sidebar_ otomatis disembunyikan. Header menampilkan ikon Hamburger (garis tiga) di sudut kiri atas.
  - _Tab Navigation_ di area kerja yang tadinya memanjang horizontal, berubah bentuk menjadi _Scrollable Tabs_ (bisa digeser ke kiri-kanan) atau _Dropdown Select_ agar tidak merusak tata letak layar kecil.
- **Workflow C: Transisi Ganti Entitas (Entity Switch)**
  - Staf mengklik _Entity Switcher_ di Header dan memilih "Cinta Sedekah".
  - _Frontend_ memicu _Global State Update_.
  - _Frontend_ memunculkan indikator _Loading_ transparan (sepersekian detik).
  - Seluruh komponen (Tabel, Dasbor, Presensi) yang menggunakan parameter entity*id otomatis memanggil ulang API (\_Re-fetch*) untuk menarik data Cinta Sedekah. Warna tema UI (opsional) bisa berubah sedikit untuk menegaskan perpindahan ruang kerja.

## 3\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)

### **Kategori 1: Employee Portal (Akses: Staf)**

- **Zero-Friction Navigation:** Pastikan perpindahan antar Tab (Level 3) tidak memicu _Full Page Reload_ (kedipan layar putih). Gunakan _Client-Side Routing_ untuk transisi halaman yang sehalus aplikasi _Native_.
- **Smart Redirects:** Jika staf mengakses URL langsung (misal: my.zamzami.or.id/hr/approve) tetapi ia adalah staf biasa, arahkan secara halus ke my.zamzami.or.id/dashboard tanpa _error_ yang mengintimidasi.

### **Kategori 2: HRD/Admin Portal (Akses: HRD/Superadmin)**

- **Superadmin Visual Cues:** Jika seseorang _login_ dengan atribut is*superadmin = true, berikan indikator visual khusus pada \_Header* (misal: pita tipis warna emas di bagian paling atas layar) agar mereka sadar bahwa mereka sedang memegang kendali penuh yang sensitif.

### **Kategori 3: Strategic Portal (Akses: Manajemen)**

- **Focus Mode (Expandable Workspace):** Eksekutif atau Manajer sering melihat dasbor analitik (seperti _Live Headcount_) yang lebar. Sediakan tombol panah kecil di bawah Sidebar untuk menciutkan menu (_Collapse_) menjadi hanya kumpulan ikon, sehingga area tabel/grafik menjadi selebar layar penuh.

### **Kategori 4: Non-Functional Requirements (Performa & Optimasi)**

- **Component Lazy Loading (Code Splitting):** Jangan memuat seluruh skrip aplikasi di awal. Jika staf tidak memiliki hak akses ke modul "Manajemen Proyek", _browser_ tidak boleh mengunduh _file javascript/komponen UI_ untuk Manajemen Proyek. Ini menghemat _bandwidth_ dan mempercepat _loading_ awal.
- **State Persistence:** Gunakan _Local Storage_ atau _Session Storage_ untuk menyimpan status UI non-kritis. Contoh: Jika staf memperkecil (_collapse_) Sidebar hari ini, besok saat ia buka aplikasi lagi, Sidebar harus tetap dalam keadaan kecil (mengingat preferensi pengguna).
- **PWA Readiness (Fondasi Aplikasi Android):** Konfigurasikan file manifest.json dan _Service Worker_ sejak awal. Ini adalah langkah vital agar nantinya aplikasi Web ini bisa di- _install_ langsung ke layar utama (_Homescreen_) HP Android staf layaknya aplikasi asli, mendukung _caching_ _asset_ gambar/ikon untuk mempercepat akses harian.

# PRD: Modul Master Data & Admin Control Panel

**Instruksi Global untuk AI:** Bangun modul ini dengan hierarki otorisasi administratif dua tingkat: **Global Superadmin** (Akses ke seluruh sistem & 4 entitas) dan **Entity Admin** (Akses terbatas hanya pada entitas tempat ia ditugaskan). Gunakan komponen _Data Table_ modern sesuai gaya visual **Jampack Desktop Template**. Pastikan antarmuka menyesuaikan secara dinamis (_conditional rendering_); Entity Admin tidak boleh melihat tombol, menu, atau data yang berada di luar yurisdiksi entitasnya.

## 1\. Arsitektur Logika Admin (Database & Middleware Update)

Sebelum menyusun UI, Antigravity harus memperbarui skema _backend_:

- **Perubahan Status Admin:** Tinggalkan boolean is_superadmin tunggal. Ubah menjadi:
  - is*global_admin (Boolean di tabel users): Jika \_true*, ia adalah "Dewa" yang menguasai seluruh sistem.
  - is*entity_admin (Boolean di tabel user_entity_access): Jika \_true* pada baris _entity_ tertentu, maka ia adalah penguasa di entitas tersebut (misal: Admin khusus Cinta Sedekah).
- **Middleware Baru (verify_admin_access):** _Middleware_ ini mengevaluasi tipe admin. Jika ia mencoba mengedit staf di HaramainKU, _middleware_ mengecek: _Apakah dia Global Admin? ATAU Apakah dia Entity Admin untuk HaramainKU?_ Jika keduanya salah, tolak dengan 403 Forbidden.

## 2\. Sitemap & Struktur Navigasi (UI/UX Layout)

**Menu Utama:** 👑 Admin Panel (Hanya muncul jika user adalah Global Admin atau Entity Admin)

- **Sub-Menu 1: Master Organisasi** _(Visibilitas: KHUSUS Global Admin)_
  - _Tab 1: Manajemen Entitas_ - CRUD untuk pendaftaran unit perusahaan (Madinatul Qur'an, HaramainKU, dll.).
  - _Tab 2: Registri Modul_ - Kontrol _Master Switch_ untuk mematikan/menyalakan modul secara global.
- **Sub-Menu 2: Manajemen Karyawan** _(Visibilitas: Global Admin & Entity Admin)_
  - _Tab 1: Direktori Staf_
    - **Jika Global Admin:** Melihat daftar seluruh staf dari semua entitas.
    - **Jika Entity Admin:** Hanya melihat daftar staf yang tergabung di entitasnya.
  - _Tab 2: Pendaftaran Karyawan (Onboarding)_ - Form pendaftaran akun baru. Entity Admin hanya bisa mendaftarkan staf untuk masuk ke entitas miliknya.
- **Sub-Menu 3: Kontrol Akses (Permission Matrix)** _(Visibilitas: Global Admin & Entity Admin)_
  - _Tab 1: Alokasi RACI Dasar_ - Mengatur level akses bawaan staf (Responsible, Accountable, dll.) pada entitas tersebut.
  - _Tab 2: Module Toggling_ - Tabel matriks _Toggle Switch_. Entity Admin hanya bisa menyalakan/mematikan modul bagi staf di bawah naungannya, dan hanya untuk modul yang diizinkan oleh Global Admin.

## 3\. User Workflows (Alur Kerja Pengguna)

- **Workflow A: Pendelegasian Wewenang (Oleh Global Admin)**
  - Global Admin masuk ke _Admin Panel_.
  - Membuka profil staf bernama "Ahmad" (Kepala HRD Bimbingan Islam).
  - Pada bagian hak akses, Global Admin mencentang opsi "Set as Entity Admin" khusus untuk kotak "Bimbingan Islam".
  - Ahmad kini memiliki akses ke _Admin Panel_, namun layarnya terkunci secara eksklusif hanya untuk mengelola data Bimbingan Islam.
- **Workflow B: Pengelolaan Karyawan oleh Entity Admin**
  - Ahmad (Entity Admin Bimbingan Islam) masuk ke _Direktori Staf_. Ia tidak bisa mencari atau melihat nama staf dari HaramainKU.
  - Ahmad mendaftarkan staf baru bernama "Budi".
  - Saat Ahmad mencoba menugaskan Budi ke entitas, sistem memblokir pilihan entitas lain. Budi otomatis hanya terdaftar di Bimbingan Islam.
  - Ahmad membuka _Module Toggling_ dan mengaktifkan modul "Presensi" untuk Budi.
- **Workflow C: Konflik Lintas Entitas (Cross-Entity Allocation)**
  - Budi (staf Bimbingan Islam) diminta untuk membantu proyek di Cinta Sedekah.
  - Ahmad (Entity Admin Bimbingan Islam) **tidak bisa** menambahkan Budi ke Cinta Sedekah karena itu di luar wewenangnya.
  - Hanya **Global Admin** ATAU **Entity Admin Cinta Sedekah** yang bisa menarik (mengundang) akun Budi ke dalam _workspace_ Cinta Sedekah melalui ID Karyawan/Email Budi.

## 4\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)

### **Kategori 1: Admin/HR Portal (Akses: Global Admin & Entity Admin)**

- **Contextual Data Tables:** Tabel yang digunakan di seluruh layar Admin harus memiliki filter entity*id tersembunyi yang diisi otomatis oleh sistem (\_backend*) jika yang _login_ adalah Entity Admin. Ini mencegah _bug UI_ menampilkan data yang salah.
- **Safe Invite System:** Sediakan fitur "Invite Existing Staff". Jika seorang staf sudah memiliki akun (misal terdaftar di Pesantren), Entity Admin Edutech tidak perlu membuat akun baru. Mereka cukup memasukkan email staf tersebut untuk mengirimkan undangan masuk ke entitas Edutech (menghindari duplikasi akun).

### **Kategori 2: Strategic Portal (Akses: Pemilik Sistem / Global Admin)**

- **Global Kill Switch:** Fitur eksklusif Global Admin. Jika Global Admin menonaktifkan akun seorang staf dari level _Root_ (Master Data), staf tersebut langsung kehilangan akses ke _seluruh_ entitas secara bersamaan.
- **Hierarchical Audit Trail:** Sistem _log_ mencatat siapa yang melakukan perubahan. Contoh di Dasbor Global Admin: "Akses modul keuangan Budi diaktifkan oleh Ahmad (Entity Admin) pada tanggal X".

### **Kategori 3: Non-Functional Requirements (Keamanan & Middleware)**

- **Strict UI Rendering Check:** _Frontend_ tidak boleh sekadar menyembunyikan tombol "Tambah Entitas" menggunakan CSS (display: none) bagi Entity Admin. Elemen tersebut harus benar-benar **tidak dirender** di DOM sejak awal menggunakan logika _Conditional Rendering_ di _framework frontend_ (berdasarkan _Payload_ JWT).
- **Horizontal Privilege Escalation Prevention:** _Backend_ wajib memastikan bahwa ID pengguna yang mengirim permintaan perubahan akses (Entity Admin) memiliki hak atas entity*id dan user_id yang sedang diubah. Jika seorang Entity Admin mencoba memanipulasi \_API Payload* untuk mengedit staf di entitas tetangga, tolak dengan 403 Forbidden dan catat ke security_logs.

# PRD: Modul Presensi Kehadiran

**Instruksi Global untuk AI:** Bangun modul ini dengan antarmuka yang bersih (_clean UI_) seperti Jampack Desktop Template. Terapkan navigasi 3 level (Menu > Sub-Menu > Tab). Pastikan seluruh tabel, _query backend_, dan visibilitas UI tunduk pada sistem Multi-Tier Admin: **Global Admin** dapat melihat/mengatur presensi seluruh entitas, sementara **Entity Admin** hanya memiliki akses _Read/Write/Approve_ terbatas pada entitas tempat ia ditugaskan.

## 1\. Sitemap & Struktur Navigasi (UI/UX Layout)

**Menu Utama:** ⏱️ Presensi

- **Sub-Menu 1: Dasbor**
  - _Tab 1: Dasbor Pribadi_ (Akses: Semua Staf) - Ringkasan kehadiran pribadi, sisa cuti, dan Gamifikasi (_Positive Nudges_).
  - _Tab 2: Live Headcount_ (Akses: Manajemen & Admin) - Radar status karyawan hari ini. _Entity Admin_ hanya melihat data entitasnya; _Global Admin_ melihat agregat semua entitas.
  - _Tab 3: Analitik Kinerja_ (Akses: Manajemen & Admin) - Data _Overwork & Burnout Detector_ (terfilter sesuai yurisdiksi entitas).
- **Sub-Menu 2: Pencatatan Kehadiran**
  - _Tab 1: Clock-In / Out_ (Akses: Semua Staf) - Antarmuka _action card_ utama untuk absen + validasi _Offline Mode_.
  - _Tab 2: Riwayat Pribadi_ (Akses: Semua Staf) - Tabel historis presensi mandiri.
  - _Tab 3: Rekapitulasi Induk_ (Akses: Global & Entity Admin) - Tabel master. Entity Admin tidak akan melihat _dropdown_ filter lintas entitas.
- **Sub-Menu 3: Pengajuan & Koreksi**
  - _Tab 1: Form Cuti & Izin_ (Akses: Semua Staf) - Form pengajuan dengan visibilitas kalender tim (khusus rekan satu entitas/divisi).
  - _Tab 2: Koreksi Mandiri_ (Akses: Semua Staf) - Form untuk merevisi jam absen (_human/system error_).
  - _Tab 3: Approval Center_ (Akses: Atasan & Admin) - Antrean persetujuan. Entity Admin hanya menerima form dari staf di entitasnya.
- **Sub-Menu 4: Pengaturan Kehadiran** (Akses: Global & Entity Admin)
  - _Tab 1: Aturan Cuti & Libur_ - Setup kuota cuti. (Entity Admin hanya bisa mengatur kuota lokal entitasnya, Global Admin bisa memotong cuti bersama massal).
  - _Tab 2: Lokasi & Keamanan_ - Setup Geofence (radius GPS) dan SSID WiFi kantor.
  - _Tab 3: Anomaly Radar_ - Pengaturan batas toleransi keterlambatan.

## 2\. User Workflows (Alur Kerja Pengguna yang Diperbarui)

- **Workflow A: Smart Clock-In (Harian)**
  - Staf membuka aplikasi (Mobile) > Masuk ke Tab _Clock-In_.
  - Sistem melakukan pengecekan isMockLocation dan status _root_ perangkat.
  - Sistem menarik aturan Geofence/WiFi spesifik milik entitas staf tersebut (misal: Bimbingan Islam mungkin punya toleransi radius 100m, sementara Pesantren hanya 30m).
  - Jika valid, tombol "Masuk" aktif. Data tersimpan dengan _timestamp_ (Mendukung _Offline Mode Sync_).
- **Workflow B: Pengajuan Cuti & Approval Berjenjang**
  - Staf mengisi Form Cuti. Sistem mengirim notifikasi ke Atasan Langsung.
  - Atasan menyetujui. Sistem mem- _forward_ ke HRD (_Entity Admin_ di entitas tersebut).
  - Jika HRD HaramainKU (_Entity Admin_) membuka _Approval Center_, ia tidak akan pernah melihat pengajuan cuti dari staf Cinta Sedekah. Ia hanya mengeksekusi data di wilayahnya.
- **Workflow C: Penyesuaian Geofence oleh Entity Admin**
  - Kantor Bimbingan Islam pindah gedung.
  - Entity Admin Bimbingan Islam masuk ke _Pengaturan Kehadiran > Lokasi_.
  - Ia memperbarui titik koordinat _Lat/Long_ kantor. Perubahan ini langsung berlaku bagi seluruh staf Bimbingan Islam tanpa mempengaruhi koordinat absen staf Madinatul Qur'an.

## 3\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)

### **Kategori 1: Employee Portal (Akses: Staf)**

- **Dynamic Geofencing Validation:** Validasi _frontend/backend_ harus memanggil tabel pengaturan lokasi berdasarkan entity_id yang sedang aktif di JWT pengguna.
- **Selfie Verification WFH:** Jika staf memilih mode Dinas Luar/WFH, wajib menyertakan swafoto _real-time_ (tidak bisa dari galeri) dan koordinat aktual, yang akan diberi penanda (_flag_) khusus di _Approval Center_.

### **Kategori 2: HR/Admin Portal (Akses: Global Admin & Entity Admin)**

- **Isolated Multi-Tier Approval:**
  - Fitur _Super-Override_ (pintas persetujuan darurat) hanya bisa digunakan oleh Entity Admin untuk staf di entitasnya, dan oleh Global Admin untuk seluruh staf.
- **Automated Leave Management (Scope Aware):**
  - Jika Global Admin memasukkan "Cuti Bersama Idul Fitri", sistem otomatis memotong saldo cuti di _semua_ entitas.
  - Jika Entity Admin Pesantren memasukkan "Libur Khusus Milad Pesantren", sistem _hanya_ memotong/menyesuaikan jadwal staf Pesantren.
- **Export & Anomaly Radar:** Laporan Excel/CSV dan Notifikasi peringatan (misal: "Staf X absen 3 hari berturut-turut") akan dialirkan secara spesifik ke _Dashboard_ Entity Admin yang berwenang.

### **Kategori 3: Strategic Portal (Akses: Manajemen & Pemilik)**

- **Aggregated Live Headcount:** Jika seorang Manajer level grup (Global Admin) membuka _Live Headcount Dashboard_, ia melihat diagram lingkaran kumulatif seluruh ekosistem Zamzami Internasional, dengan fitur _drill-down_ (klik untuk memecah data per entitas).
- **Burnout Detector:** Algoritma membaca pola _clock-out_ larut malam (>10 jam kerja). Global Admin bisa membandingkan "Tingkat Burnout" antara HaramainKU vs Bimbingan Islam secara langsung di satu layar.

### **Kategori 4: Non-Functional Requirements (Keamanan & Kestabilan)**

- **Cross-Entity Exploit Prevention:** Jika ada kebocoran API, _backend_ wajib memverifikasi bahwa ID Staf yang sedang diabsenkan (_clock-in_) benar-benar memiliki user*entity_access yang sah ke entitas yang dituju dalam \_request payload*.
- **Timestamp Tamper-Proof:** Waktu absen menggunakan waktu _Server (Backend)_, bukan waktu _Device/HP (Frontend)_, untuk mencegah staf mengakali jam keterlambatan dengan mengubah jam di ponsel mereka.

# PRD: Modul Notifikasi Terpusat (Sistem Saraf Pusat MyZamzami)

**Instruksi Global untuk AI:** Bangun modul ini bukan sekadar sebagai antarmuka pencatat pesan, melainkan sebagai mesin _Event-Driven_ cerdas (Sistem Saraf Pusat). Terapkan protokol komunikasi _real-time_ (WebSockets/SSE) dan sistem antrean pesan (_Message Broker_). Integrasikan filter entity*id untuk memastikan isolasi data \_Multi-Tier Admin* tetap terjaga. Antarmuka harus mengikuti gaya **Jampack Desktop Template** dengan penekanan pada _micro-interactions_ (seperti efek _hover_ dan _state_ tombol aksi).

## 1\. Skema Database & Message Queue (Fondasi Data)

- **Tabel notifications**
  - id (UUID), user_id (Penerima), entity_id (Konteks unit bisnis).
  - sender_id (Pengirim/Sistem).
  - title, message (String).
  - raci_tier (Integer: 1 = R/A, 2 = C/I).
  - is_actionable (Boolean), action_url (String/Endpoint API untuk eksekusi langsung).
  - status (Enum: unread, read, actioned).
  - scheduled*for (Timestamp - Untuk menahan pesan saat \_Quiet Hours*).
  - group*key (String - Kunci unik untuk algoritma \_Smart Batching*).
- **Tabel user_focus_states**
  - user_id (Primary Key).
  - focus_until (Timestamp - Waktu berakhirnya mode fokus).
  - quiet_hours_start, quiet_hours_end (Time - Jadwal Etis ditarik dari Modul Presensi).
- **Tabel emergency_override_logs** (Audit Trail)
  - id, sender_id, target_id, timestamp, context.

## 2\. Sitemap & Komponen Antarmuka (UI/UX)

Modul ini sebagian besar "hidup" di latar belakang dan merender dirinya pada komponen _Header_ global, bukan sebagai halaman penuh.

- **Komponen Header: Bell Icon (Notification Center)**
  - _Visual Indicator:_ Jika ada notifikasi Tier 1 (R/A), tampilkan _Badge_ Merah berangka. Jika hanya Tier 2 (C/I), tampilkan _Dot_ kecil tanpa angka.
  - _Dropdown Drawer:_ Dibagi menjadi 2 Tab: **"Butuh Tindakan"** (Actionable) dan **"Informasi"** (Read-only).
- **Komponen _In-Line Action Card_ (Di dalam Drawer)**
  - Kartu notifikasi dengan tombol aksi berjejer (contoh: tombol hijau \[Setujui\], tombol merah \[Tolak\]).
  - Ketika diklik, kartu menampilkan animasi _loading spinner_ sesaat, lalu menghilang secara halus (_fade out_).
- **Komponen Profil (Global Status)**
  - Menu _Dropdown_ Profil (Kanan Atas) memiliki _Toggle_ **"Mulai Mode Fokus"**.
  - Klik memunculkan _Dropdown_ wajib: \[30 Menit\], \[1 Jam\], \[2 Jam\].
  - Indikator Visual (🎧 atau ⛔) menempel di sebelah nama staf tersebut di _seluruh_ tabel dan obrolan dalam aplikasi.
- **Halaman Pengaturan Preferensi (User Settings)**
  - Tabel matriks _Omnichannel Routing_: Pengguna bisa mencentang (Web, Mobile Push, WhatsApp/Email) berdasarkan kategori notifikasi (Kritis, Standar, Rangkuman).

## 3\. User Workflows (Logika & Alur Eksekusi)

- **Workflow A: Penahanan Jam Tenang (Quiet Hours) & Sinkronisasi Pagi**
  - Pukul 22:30, staf HRD memvalidasi dokumen yang menyeret nama Staf B.
  - _Backend_ mengecek profil Staf B. Karena sudah di luar jam kerja (dan bukan darurat), _backend_ mengatur scheduled*for pada notifikasi tersebut menjadi esok hari pukul 08:00 (atau saat Staf B \_Clock-In*).
  - Pesan masuk ke antrean _Redis_. HP dan _Browser_ Staf B tidak berbunyi.
- **Workflow B: "Knock-Twice" Emergency Override**
  - Staf B sedang mengaktifkan Mode Fokus (Ikon 🎧 aktif).
  - Manajer (Atasan Langsung) mendelegasikan tugas kritis di sistem kepada Staf B.
  - Sistem mencegat Manajer dengan _Pop-up Prompt_: _"Staf B sedang Mode Fokus hingga 14:00. Apakah ini darurat?"_
  - Manajer memilih tombol \[Dobrak Mode Fokus\].
  - Sistem mencatat aksi ini di emergency*override_logs dan seketika menembakkan \_WebSockets/Push Notification* bersuara ke perangkat Staf B.
- **Workflow C: Eksekusi In-Line Action**
  - Atasan menerima notifikasi "Pengajuan Izin Cuti dari Fulan".
  - Atasan membuka ikon Lonceng, melihat kartu notifikasi, dan langsung mengklik tombol \[Setujui\] di dalam kartu tersebut.
  - _Frontend_ mengirim permintaan API latar belakang ke Modul Presensi/Cuti. Status berhasil, kartu notifikasi lenyap, tanpa Atasan perlu memuat (_load_) halaman Modul Presensi.

## 4\. Spesifikasi Arsitektur Logika (Backend & Integrasi)

### **Kategori 1: Mesin Filter Cerdas (Smart Algorithms)**

- **Smart Batching Engine:** Sebuah _CRON/Worker_ di _backend_ yang mengevaluasi tabel notifikasi setiap menit. Jika menemukan >3 entri dengan group_key yang sama dan status belum dibaca dalam rentang 30 menit terakhir, sistem menyembunyikan notifikasi individual tersebut dan membuat 1 notifikasi agregat (misal: "Ahmad dan 3 lainnya mengubah Dokumen X").
- **RACI-Driven Triage Rules:** Integrasikan ini dengan tabel user*entity_access (dari Modul Master Data). Nilai RACI menentukan rute pengiriman (Tier 1 = \_WebSockets Active Push*; Tier 2 = _Silent Database Insert_).

### **Kategori 2: Infrastruktur Kinerja (Performance & Sync)**

- **WebSockets / Server-Sent Events (SSE):** Terapkan komunikasi dua arah (_bidirectional_). Ketika _trigger_ terjadi di _backend_, dorong (_push_) _payload_ JSON langsung ke klien (_browser_ / aplikasi seluler) tanpa menunggu klien me-_refresh_ halaman.
- **Cross-Device State Management:** Jika _endpoint_ PUT /api/notifications/:id/action berhasil dieksekusi dari aplikasi Android, _backend_ wajib memancarkan _event_ notification*actioned melalui WebSockets ke seluruh sesi aktif milik \_user_id* tersebut. _Browser_ web yang sedang terbuka di laptop staf akan menangkap _event_ ini dan otomatis menghilangkan notifikasi tersebut dari layar tanpa perlu di-_refresh_.
- **Message Broker (Redis/RabbitMQ):** Wajib digunakan untuk mengelola tumpukan pengiriman massal (saat _Quiet Hours_ berakhir di pagi hari) agar server _database_ utama (PostgreSQL/MySQL) tidak _crash_ (_spike load_) akibat memproses ribuan notifikasi secara bersamaan pada pukul 08:00 WIB.

### **Kategori 3: Keamanan & Multi-Tier Admin Awareness**

- _Entity Context Payload:_ Setiap pesan WebSockets yang ditembakkan harus memuat parameter entity*id. Jika staf memiliki sesi aktif di ruang kerja HaramainKU, pastikan \_Pop-up Banner* untuk urusan Cinta Sedekah tidak muncul di layar tersebut secara _real-time_ (atau muncul dengan label entitas yang sangat kontras untuk mencegah salah klik).
- _Audit Log Visibility:_ Catatan dari penggunaan fitur "Dobrak/Override" akan disalurkan secara otomatis ke _Dashboard_ Analitik milik Global Admin atau Entity Admin (sesuai yurisdiksi) sebagai bahan evaluasi budaya kerja (apakah atasan terlalu sering mengganggu staf yang sedang fokus).

# PRD: Modul Kalender & Smart Reminder (Asisten Kognitif MyZamzami)

**Instruksi Global untuk AI:** Bangun modul ini sebagai pusat kendali waktu (_time-management hub_) dengan antarmuka dinamis menggunakan _library_ kalender yang solid (misal: FullCalendar). Prioritaskan _Neuro-friendly UI_: hindari kepadatan visual yang memicu kelelahan mata. Gunakan pemisahan _layer_ warna yang lembut (_pastel color-coding_) berdasarkan kategori. Seluruh _query_ dan visibilitas harus mengikat pada parameter entity_id dan user_id, agar kalender pribadi staf di entitas Bimbingan Islam tidak bocor ke entitas lain, kecuali untuk kalender "Libur Nasional/Grup".

## 1\. Skema Database Dasar (ERD & Rekayasa Pengulangan)

Sesuai strategi penyimpanan "Individual Entry" untuk fleksibilitas maksimal, Antigravity harus membangun tabel berikut:

- **Tabel reminders (Tabel Transaksional Utama)**
  - id (UUID, PK)
  - entity_id (UUID, FK - Untuk isolasi entitas)
  - user_id (UUID, FK - Pemilik jadwal)
  - title (String), description (Text, Nullable)
  - start*datetime, end_datetime (Timestamp - Wajib diindeks untuk performa \_query* per bulan)
  - is_all_day (Boolean)
  - category_id (Enum/String: Reminder, Meeting, Deadline, Event)
  - linked_module_slug (String - Konteks ke modul lain, misal: 'project-tasks')
  - linked_item_id (UUID - ID dari dokumen/tugas yang ditautkan)
  - status (Enum: pending, completed, snoozed, cancelled)
  - parent_recurring_id (UUID, Nullable - Untuk melacak apakah entri ini bagian dari sebuah seri berulang)
- **Tabel recurring_patterns (Mesin Cetak Jadwal)**
  - id (UUID, PK)
  - user_id (UUID)
  - frequency_type (Enum: daily, weekly, monthly, yearly, custom)
  - interval (Integer - misal: setiap '2' minggu)
  - custom_logic_json (JSON - Untuk pola rumit seperti "Senin & Rabu" atau "Tanggal 25")
  - end_date (Timestamp, Nullable)
  - holiday_behavior (Enum: skip, move_to_prev_workday, move_to_next_workday)
- **Tabel master_holidays (Kalender Statis)**
  - id, date, name, entity_scope (Apakah libur global seluruh Zamzami Internasional, atau libur khusus satu entitas).

## 2\. Sitemap & Komponen Antarmuka (UI/UX Layout)

**Menu Utama:** 📅 Kalender & Agenda

- **Komponen 1: Quick Capture (Header Global)**
  - Tombol melayang (FAB) atau ikon petir (⚡) di Header atas.
  - Membuka _Pop-up Modal_ instan. Input: "Apa yang perlu diingat?" dan "Kapan?". Eksekusi simpan di bawah 5 detik.
- **Sub-Menu 1: Kalender Utama (Multi-Scale View)**
  - _Toolbar Navigasi:_ Tombol navigasi (Bulan/Minggu/Hari/Agenda).
  - _Layer Toggle (Sidebar Kiri):_ Deretan _checkbox_ untuk memfilter tampilan. Misal: \[x\] Pengingat Pribadi, \[x\] Hari Libur, \[ \] Tenggat Waktu Proyek.
  - _Canvas Utama:_ _Grid_ kalender yang interaktif (mendukung _Drag & Drop_ untuk memindahkan jadwal, dan _Resize_ batas kotak untuk menambah durasi).
- **Sub-Menu 2: Action Tracker (Dasbor Kedisiplinan)**
  - _List View_ yang memprioritaskan jadwal hari ini (Hari Ini, Esok, Mendatang).
  - Terdapat _Badge Alert_ jika ada tugas kritis yang di-_snooze_ lebih dari 3 kali.

## 3\. User Workflows (Logika Eksekusi)

- **Workflow A: Penciptaan "Context-Priming" Reminder**
  - Staf membuka Modul Catatan, membaca notulen rapat.
  - Staf menekan tombol "Buat Pengingat dari Catatan ini".
  - _Pop-up Quick Capture_ muncul. _Field_ linked_module_slug dan linked_item_id otomatis terisi oleh sistem. Staf memasukkan waktu pengingat.
  - Saat alarm berbunyi esok harinya, _Pop-up Notifikasi_ memiliki tombol "Buka Catatan". Mengklik tombol itu langsung memuat dokumen terkait.
- **Workflow B: Eksekusi _Background Job_ untuk Pola Berulang**
  - Staf mengatur reminder "Laporan Mingguan" setiap hari Jumat pagi selama setahun ke depan, dengan opsi holiday_behavior: move_to_prev_workday.
  - Saat form di-_submit_, _Backend_ menyimpan data di recurring_patterns.
  - _Asynchronous Background Worker_ (skrip latar belakang) langsung berlari, melakukan iterasi (_looping_), mengecek apakah ada hari Jumat yang menabrak tanggal di tabel master_holidays.
  - Jika ya (misal Jumat itu tanggal merah), entri spesifik untuk minggu tersebut dicatat pada hari Kamis (start_datetime digeser). Sistem menghasilkan 52 baris unik di tabel reminders.
- **Workflow C: Auto-Escalation (Snooze Berlebih)**
  - Staf mendapat alarm untuk _Tenggat Waktu OKR_, namun menekan "Snooze 1 Jam".
  - Sistem memperbarui nilai status menjadi 'snoozed' dan menambah _counter_ tak kasat mata.
  - Setelah klik "Snooze" yang ketiga kali di hari yang sama, sistem secara otomatis mengirimkan notifikasi senyap (Lapisan Tier 2) kepada atasan langsung (berdasarkan Matriks RACI): "Fulan menunda tenggat waktu X sebanyak 3 kali".

## 4\. Spesifikasi Arsitektur Teknis & Kepatuhan AI

### **Kategori 1: Optimasi Kinerja Database (Frontend-Backend Handshake)**

- **Lazy Loading Calendar Data:** Antigravity tidak boleh menarik _seluruh_ data reminders milik staf sekaligus. Saat UI memuat tampilan "Agustus 2026", _Frontend_ hanya mengirim API _Request_ rentang waktu 1 Agustus hingga 31 Agustus. Jika staf menekan "Bulan Depan", barulah data September ditarik. Ini mencegah sistem lambat.

### **Kategori 2: Integrasi dengan Modul Notifikasi (Eskalasi)**

- **Unified Event Bus:** Modul Kalender tidak bertugas _memunculkan_ pop-up di layar pengguna. Modul Kalender murni bertugas mengatur jadwal. Ketika start*datetime tiba, Modul Kalender wajib mengirimkan sinyal/ \_payload* JSON ke **Modul Notifikasi Terpusat** (Sistem Saraf Pusat yang kita rancang sebelumnya). Modul Notifikasilah yang akan memutuskan apakah alarm ini berbunyi keras (Tier 1) atau diam (Tier 2).

### **Kategori 3: Arsitektur Edit Seri (Handling Edits on Recurring Events)**

- **Three-Way Edit Logic:** Ketika staf mengklik sebuah pengingat yang merupakan hasil pengulangan (memiliki parent*recurring_id), UI wajib memunculkan \_prompt* dengan 3 opsi sebelum menyimpan perubahan:
  - _This event only_ (Ubah hanya baris data tabel ini).
  - _This and following events_ (Ubah baris ini, dan _generate_ ulang semua baris masa depan yang memiliki ID _parent_ yang sama).
  - _All events in the series_ (Ubah dari masa lalu hingga masa depan).