# **MyZamzami (my.zamzami.or.id)**

**MyZamzami** adalah sebuah ekosistem ruang kerja digital terpusat (*Enterprise Resource Planning & Human Resource Information System hybrid*) yang dirancang khusus untuk mengonsolidasikan seluruh operasional, manajemen kinerja, dan kolaborasi antar-entitas di bawah naungan Zamzami Internasional.

Sistem ini berfungsi sebagai *Single Source of Truth* (sumber data tunggal) bagi entitas yang memiliki karakteristik operasional yang sangat berbeda—mulai dari pendidikan pesantren (Madinatul Qur'an), komersial travel (HaramainKU), edutech (Bimbingan Islam), hingga lembaga filantropi (Cinta Sedekah)—tanpa mengorbankan isolasi, privasi, dan tata kelola data masing-masing unit.

#### **Karakteristik Utama Sistem:**

1. **Arsitektur Multi-Tenant yang Terisolasi Secara Logis** Sistem beroperasi pada satu basis data tunggal untuk memudahkan pengelolaan infrastruktur, namun dilengkapi dengan dinding isolasi data (*logical isolation*) yang ketat. Setiap transaksi dan data diikat dengan identitas entitas, memastikan kerahasiaan dan kepatuhan struktural antar unit bisnis.  
2. **Tata Kelola Akses Presisi Tinggi (Hybrid RBAC & RACI Matrix)** Sistem tidak menggunakan pendekatan *role-based* tradisional yang kaku. Otorisasi dibangun di atas dua pilar:  
   * **Statis & Terkendali:** *Superadmin* memegang kendali absolut melalui fitur *Module Toggling* untuk menyembunyikan atau menampilkan modul spesifik per individu, menjaga UI tetap bersih dan relevan dengan beban kerja staf.  
   * **Dinamis & Lintas Fungsional:** Di dalam modul kolaboratif (seperti Manajemen Proyek), hak akses beradaptasi secara dinamis menggunakan parameter **RACI (Responsible, Accountable, Consulted, Informed)**, mendukung perbaikan proses bisnis (BPI) yang tangkas tanpa merusak hierarki struktural.  
3. **Fleksibilitas Lintas Platform (*API-First & Omnichannel*)** Dibangun dengan pendekatan *API-First*, antarmuka sistem menjamin pengalaman pengguna (UI/UX) yang identik, responsif, dan intuitif baik saat diakses melalui *browser* desktop, tablet, maupun aplikasi *native* Android. Struktur navigasi dirancang secara hierarkis (Modul \> Sub-modul \> Tab) untuk meminimalkan beban kognitif pengguna (*cognitive load*).  
4. **Integrasi Taktis ke Strategis** Sistem menjembatani jurang antara visi manajemen dan eksekusi harian. Modul taktis operasional (Presensi, TodoList, Penugasan, Notulen, Reminder) secara arsitektural dipersiapkan untuk terintegrasi dengan modul strategis tingkat tinggi (OKR, KPI, dan Dashboard), menciptakan transparansi dan akuntabilitas performa secara *real-time*.

**Tujuan Akhir (Ultimate Goal):** Menciptakan budaya kerja yang transparan, terukur, dan efisien melalui satu aplikasi serbaguna yang sangat mudah digunakan (*user-friendly*), memastikan setiap staf dari berbagai latar belakang literasi digital dapat mengadopsi sistem ini dengan kurva pembelajaran yang minimal.

# **PRD: Modul Sistem Inti, Autentikasi & Database Master (MyZamzami)**

**Instruksi Global untuk AI:** Bangun fondasi utama sistem ini menggunakan arsitektur *API-First*. Terapkan Single Sign-On (SSO) berbasis JSON Web Token (JWT). Buat skema database relasional (PostgreSQL/MySQL) yang mendasari sistem isolasi *multi-tenant* (*logical isolation*) menggunakan parameter entity\_id. Skema ini adalah prasyarat wajib sebelum modul bisnis apa pun dibangun.

## **1\. Skema Database Utama (Core Entity Relationship)**

**A. Tabel entities (Master Unit Bisnis)**

* id (UUID, Primary Key)  
* name (String: Madinatul Qur'an, HaramainKU, Bimbingan Islam, Cinta Sedekah)  
* code (String, Unique: MQA, HKU, BIS, CSD)  
* is\_active (Boolean, Default: True)  
* created\_at & updated\_at (Timestamp)

**B. Tabel users (Master Data Karyawan/Identitas Tunggal)**

* id (UUID, Primary Key)  
* employee\_id (String, Unique)  
* full\_name (String)  
* email (String, Unique)  
* password\_hash (String)  
* is\_superadmin (Boolean, Default: False)  
* created\_at & updated\_at (Timestamp)

**C. Tabel modules (Registri Menu & Antarmuka)**

* id (UUID, Primary Key)  
* name (String)  
* slug (String, Unique \- untuk routing URL)  
* parent\_id (UUID, Foreign Key ke modules.id, Nullable)  
* level (Integer: 1=Menu, 2=Sub-menu, 3=Tab)  
* is\_active\_global (Boolean, Default: True)

**D. Tabel user\_entity\_access (Pemetaan Multitenant & Matriks RACI Dasar)**

* id (UUID, Primary Key)  
* user\_id (UUID, Foreign Key ke users.id)  
* entity\_id (UUID, Foreign Key ke entities.id)  
* raci\_baseline (Enum: Responsible, Accountable, Consulted, Informed \- *Tingkat akses default staf di entitas tersebut*)  
* created\_at (Timestamp)

**E. Tabel user\_module\_permissions (Kontrol Visibilitas Antarmuka)**

* id (UUID, Primary Key)  
* user\_id (UUID, Foreign Key ke users.id)  
* module\_id (UUID, Foreign Key ke modules.id)  
* is\_visible (Boolean, Default: True)

---

## **2\. Sitemap & Struktur Navigasi (UI/UX Layout)**

**Menu Utama:** ⚙️ Pengaturan & Akun

* **Sub-Menu 1: Profil Pribadi**  
  * *Tab 1: Identitas & Keamanan* (Akses: Semua Staf) \- Ubah kata sandi, pengaturan 2FA (opsional), dan informasi dasar.  
  * *Tab 2: Sesi Aktif* (Akses: Semua Staf) \- Daftar perangkat yang sedang *login* dengan opsi "Log Out from All Devices".  
* **Sub-Menu 2: Manajemen Organisasi** (Akses: Superadmin)  
  * *Tab 1: Master Karyawan* \- Antarmuka CRUD untuk tabel users.  
  * *Tab 2: Master Entitas* \- Antarmuka CRUD untuk tabel entities.  
* **Sub-Menu 3: Matriks Kontrol Akses** (Akses: Superadmin)  
  * *Tab 1: Penempatan Entitas* \- Memasukkan/mengeluarkan staf dari entitas tertentu (mengubah user\_entity\_access).  
  * *Tab 2: Module Toggling* \- Tabel matriks *checklist* untuk menghidupkan/mematikan modul spesifik per staf (mengubah user\_module\_permissions).

---

## **3\. User Workflows (Alur Kerja Pengguna)**

* **Workflow A: Otentikasi Terpusat (Login SSO)**  
  1. Pengguna memasukkan email dan kata sandi di halaman *Login*.  
  2. *Backend* memvalidasi kredensial ke tabel users.  
  3. Jika valid, *backend* mengumpulkan array entity\_id dan module\_id yang diizinkan untuk pengguna tersebut.  
  4. *Backend* menghasilkan JWT berisi profil dasar dan array izin, lalu mengirimkannya ke klien (Web/Android).  
  5. *Frontend* mengurai token, merender *Sidebar Menu* hanya untuk modul yang diizinkan, dan mengarahkan pengguna ke Dasbor.  
* **Workflow B: Switch Entity (Pindah Ruang Kerja)**  
  1. Staf yang terdaftar di \>1 entitas mengklik *Dropdown* Entitas di *Header/Top Bar*.  
  2. Staf memilih entitas yang berbeda (misal: pindah dari Bimbingan Islam ke Cinta Sedekah).  
  3. *Frontend* memuat ulang status aplikasi (*state*) dan menyertakan entity\_id baru ini di setiap *Header Request API* berikutnya. Modul dan data di layar otomatis menyesuaikan.  
* **Workflow C: Provisioning Karyawan Baru (Oleh Superadmin)**  
  1. Superadmin mendaftarkan email dan nama staf baru.  
  2. Superadmin menugaskan staf ke entitas "Madinatul Qur'an" dengan baseline peran "Responsible".  
  3. Superadmin membuka tab *Module Toggling*, mencentang modul "Presensi" dan membiarkan modul "Manajemen Proyek" kosong.  
  4. Staf baru *login* pertama kali dan hanya melihat modul Presensi di lingkungan Madinatul Qur'an.

---

## **4\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)**

### **Kategori 1: Employee Portal (Akses: Staf)**

* **Seamless Authentication:** Otentikasi menggunakan JWT yang disimpan secara aman (HttpOnly Cookie untuk Web, Encrypted Shared Preferences untuk Android).  
* **Contextual Entity Switcher:** Tombol pemilih entitas di navigasi atas yang hanya muncul jika tabel user\_entity\_access mengembalikan lebih dari 1 entity\_id untuk staf tersebut.  
* **Active Session Manager:** Tabel *frontend* yang menampilkan alamat IP, jenis *browser*/*device*, dan waktu *login* terakhir, memberikan kontrol keamanan mandiri kepada staf.

### **Kategori 2: HRD/Admin Portal (Akses: Superadmin/HRD)**

* **Centralized User Provisioning:** Formulir satu pintu untuk mendaftarkan karyawan. Sistem otomatis menghasilkan kata sandi acak awal dan mengirimkannya via *email/WhatsApp* (simulasi API).  
* **Dynamic Permission Matrix:** Antarmuka berupa tabel *grid* di mana baris adalah nama staf dan kolom adalah nama modul. Superadmin cukup melakukan *toggle* *switch* (on/off) untuk mengatur akses tanpa perlu menyentuh *database*. *Toggle* ini langsung memicu API *update* secara asinkron (*debounce*).

### **Kategori 3: Strategic Portal (Akses: Manajemen/System)**

* **Global Access Audit Log:** Fitur pencatatan tak kasat mata (*backend logging*). Setiap perubahan hak akses staf, penghapusan entitas, atau *login* dari IP yang mencurigakan dicatat dengan stempel waktu untuk keperluan audit kepatuhan ISO/keamanan informasi.

### **Kategori 4: Non-Functional Requirements (Keamanan & Middleware)**

* **Strict Entity Middleware:** Buat *middleware* global di *backend* (VerifyEntityIsolation). *Middleware* ini wajib mengeksekusi pengecekan: Apakah entity\_id yang dikirim pada URL/Body Request cocok dengan daftar entity\_id yang ada di dalam *payload* JWT staf yang meminta? Jika tidak, tolak dengan 403 Forbidden.  
* **Strict Module Middleware:** Buat *middleware* (VerifyModuleAccess). Jika staf mencoba memanggil *endpoint* API dari modul "Manajemen Proyek", *backend* akan mengecek *payload* JWT. Jika ID modul tersebut tidak ada, kembalikan 403 Forbidden.  
* **Bcrypt Password Hashing:** Seluruh kata sandi di tabel users wajib dienkripsi dengan algoritma Bcrypt (minimal *salt rounds* 10\) sebelum disimpan.

# **PRD: Modul Middleware & Proteksi Isolasi Data (MyZamzami)**

**Instruksi Global untuk AI:** Modul ini murni berfokus pada arsitektur keamanan *Backend* (API Gateway) dan penanganan *Error State* di *Frontend*. Terapkan prinsip *Zero Trust Architecture*. Setiap rute API yang dipanggil wajib melewati 3 lapis pemeriksaan (*Auth, Entity, Module*). Demi performa (*low latency*), *middleware* **tidak boleh** melakukan *query* ke *database*; semua validasi akses harus bergantung murni pada *payload* JWT yang telah didekripsi.

## **1\. Arsitektur Logika Middleware (Lapisan Keamanan API)**

**A. Lapisan 1: Authentication Middleware (verify\_jwt)**

* **Fungsi:** Memeriksa apakah permintaan HTTP membawa token JWT yang valid di *header* Authorization: Bearer \<token\>.  
* **Proses:** Memvalidasi *signature* token dan memastikan masa berlakunya (*expiry time*) belum habis.  
* **Tindakan:** Jika gagal, kembalikan status 401 Unauthorized.

**B. Lapisan 2: Entity Isolation Middleware (verify\_entity\_access)**

* **Fungsi:** Mencegah kebocoran data lintas perusahaan (*Multi-tenant logical firewall*).  
* **Proses:** 1\. Mengekstrak parameter entity\_id yang diminta oleh klien (bisa dari *URL Params*, *Query String*, atau *Request Body*).  
  2\. Mencocokkan entity\_id tersebut dengan array allowed\_entities yang ada di dalam *payload* JWT.  
* **Tindakan:** Jika entity\_id tidak ada di array pengguna, tolak permintaan dengan status 403 Forbidden dan catat ke *Security Log*.

**C. Lapisan 3: Module & Action Middleware (verify\_module\_permission)**

* **Fungsi:** Memastikan staf hanya bisa mengakses API dari modul yang diaktifkan oleh Superadmin.  
* **Proses:** Mengecek rute API (misal: /api/attendance/\*) dan memvalidasi apakah ID modul/slug tersebut ada dalam array allowed\_modules di JWT.  
* **Tindakan:** Jika tidak ada izin, tolak dengan status 403 Forbidden.

---

## **2\. Sitemap & Antarmuka Penanganan Error (UI/UX Responses)**

Karena ini adalah sistem *backend*, komponen *frontend*\-nya berbentuk halaman peringatan (*Fallback Pages*) yang harus dirancang rapi (sesuai *Jampack Template*).

**Menu Tak Terlihat (Error States & Fallbacks):**

* **Halaman 401: Sesi Berakhir**  
  * *UI:* Layar bersih dengan ikon gembok/waktu habis. Pesan: "Sesi Anda telah berakhir demi keamanan. Silakan masuk kembali."  
  * *Aksi:* Tombol besar "Kembali ke Halaman Login". (Sistem *Frontend* harus menyimpan URL terakhir agar staf dikembalikan ke pekerjaan terakhirnya setelah *login* ulang).  
* **Halaman 403: Akses Ditolak**  
  * *UI:* Layar dengan ikon perisai merah/tanda seru. Pesan: "Anda tidak memiliki otoritas untuk melihat data entitas/modul ini."  
  * *Aksi:* Tombol "Kembali ke Dasbor Utama".  
* **Komponen *Empty State* (Jika belum ada akses)**  
  * *UI:* Jika seorang staf baru *login* tapi Superadmin belum memasukkannya ke entitas mana pun, tampilkan ilustrasi *onboarding* dengan pesan: "Akun Anda sedang disiapkan oleh HRD. Silakan hubungi atasan Anda untuk alokasi ruang kerja."

---

## **3\. User Workflows (Skenario Keamanan)**

* **Workflow A: Skenario Normal (Jalur Hijau)**  
  1. Staf (HaramainKU) menekan menu "Riwayat Presensi".  
  2. *Frontend* mengirim permintaan API GET /api/attendance dengan menyertakan entity\_id HaramainKU dan Token JWT.  
  3. *Middleware* membaca token: Token Valid $\\rightarrow$ Staf ini punya akses ke HaramainKU $\\rightarrow$ Modul Presensi aktif.  
  4. Permintaan diteruskan ke *Controller Database*, data dibalas dengan status 200 OK.  
* **Workflow B: Percobaan Pelanggaran Akses Lintas Entitas (Jalur Merah)**  
  1. Staf (HaramainKU) memiliki niat buruk/iseng, mencoba menebak URL atau mengubah *payload* API menggunakan *Postman/Browser Inspect Element* untuk menarik data entity\_id milik Cinta Sedekah.  
  2. Permintaan API masuk ke *Backend*.  
  3. *Middleware* Lapisan 2 membaca token: Staf ini HANYA punya akses ke HaramainKU.  
  4. *Middleware* langsung memblokir permintaan *sebelum* menyentuh *database*. Mengembalikan 403 Forbidden.  
* **Workflow C: Penanganan Sesi Berakhir yang Elegan**  
  1. Staf sedang mengisi Form Cuti yang panjang, namun Token JWT kedaluwarsa di latar belakang.  
  2. Staf menekan "Submit". API menolak dengan 401 Unauthorized.  
  3. *Frontend* menangkap kode 401, mencegat pesan *error* tersebut, mengamankan draf form sementara (*local storage*), dan memunculkan *Pop-up Modal* "Sesi Habis, Masukkan Ulang Kata Sandi".

---

## **4\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)**

### **Kategori 1: Employee Portal (Akses: Staf)**

* **Frictionless Security:** Staf tidak akan menyadari keberadaan *middleware* ini selama mereka menggunakan aplikasi secara wajar. Pengecekan JWT sangat cepat (di bawah 10 milidetik).  
* **Graceful Degradation:** Jika staf mengakses menu melalui tautan (*bookmark* lama) ke modul yang aksesnya baru saja dicabut oleh Superadmin, mereka tidak akan melihat layar *crash* (layar putih), melainkan dialihkan secara elegan ke halaman 403\.

### **Kategori 2: Admin/HR Portal (Akses: HRD/Superadmin)**

* **Bulletproof Architecture:** HRD tidak perlu khawatir salah klik di UI *frontend* yang dapat menyebabkan kebocoran data. Meskipun UI *frontend* mengalami *bug* dan menampilkan tombol yang tidak seharusnya, *backend API* tetap menjadi benteng terakhir yang menggagalkan eksekusi.

### **Kategori 3: Kepatuhan & Manajemen Risiko (Strategic/Security)**

* **API Rate Limiting (Pencegahan Spam/DDoS):** Selain 3 lapis otorisasi di atas, tambahkan *middleware* untuk membatasi jumlah *request* (misal: maksimal 100 *request* per menit per IP/User) untuk melindungi server dari serangan atau *looping error* dari aplikasi klien.  
* **Security Event Logger:** Buat tabel security\_logs. Setiap kali *Middleware* memuntahkan kode 403 Forbidden, sistem wajib mencatat *User ID, IP Address, Endpoint yang dituju*, dan *Timestamp*. Data ini penting untuk investigasi jika ada staf yang terus-menerus mencoba mengakses data rahasia.

### **Kategori 4: Non-Functional Requirements (Kinerja Sistem)**

* **Stateless Validation:** *Middleware* tidak diizinkan melakukan kueri (SELECT \* FROM users...) ke dalam *database* setiap kali ada *request* masuk. Hal ini akan membuat *database* kelebihan beban. Semua informasi hak akses (*Role, Entities, Modules*) **wajib** dikemas ke dalam *Payload* JWT pada saat *login*, dan *middleware* hanya bertugas membaca isi token tersebut.

# **PRD: Modul UI Shell & Navigasi Dinamis Frontend (MyZamzami)**

**Instruksi Global untuk AI:** Bangun *Frontend Application Shell* menggunakan *framework* modern (misal:/React atau Vue) dengan panduan gaya visual berbasis **Jampack Desktop Template** (bersih, minimalis, *padding* luas). Terapkan pola *State-Driven UI*: jangan pernah melakukan *hardcode* pada daftar menu. Navigasi harus di-*render* secara dinamis berdasarkan respons API/Token JWT (allowed\_modules). Pastikan aplikasi dirancang dengan pendekatan *Mobile-First* namun tetap ekspansif dan elegan di layar Desktop.

## **1\. Arsitektur Tata Letak (*Layout Regions*)**

**A. Region Kiri: Sidebar Dinamis (Level 1 \- Modul Utama)**

* **Karakteristik:** Lebar tetap di Desktop, menjadi *Off-canvas Drawer* (Menu Hamburger) di Mobile.  
* **Komponen:**  
  * Logo MyZamzami (atas).  
  * Daftar Menu Utama (misal: Dasbor, Presensi, Proyek, Pengaturan).  
  * *Active State Indicator*: Sorotan warna/latar belakang berbeda pada menu yang sedang dibuka.  
  * Tombol *Collapse/Expand* (Desktop) untuk meluaskan area kerja.

**B. Region Atas: Header & Top Bar (Level 2 \- Sub-menu & Konteks)**

* **Karakteristik:** Melayang (*Sticky/Fixed Top*).  
* **Komponen:**  
  * *Breadcrumbs* (Jejak Roti): Menunjukkan lokasi staf saat ini (misal: Presensi / Riwayat Pribadi).  
  * **Entity Switcher (Krusial):** *Dropdown* yang menampilkan logo/nama Entitas yang sedang aktif (HaramainKU, Bimbingan Islam, dll.). Hanya muncul jika staf terdaftar di \>1 entitas.  
  * *Action Center:* Ikon Lonceng Notifikasi (dengan *badge* merah).  
  * *Profile Menu:* Foto profil mini, nama staf, dan menu *dropdown* (Pengaturan Akun, Log Out).

**C. Region Utama: Area Kerja & Tab (Level 3 \- Halaman Fungsional)**

* **Karakteristik:** Area konten utama dengan latar belakang abu-abu terang (\#F8F9FA atau serupa) agar kartu konten (*cards*) terlihat menonjol.  
* **Komponen:**  
  * Judul Halaman.  
  * *Tab Navigation Bar*: Navigasi horizontal untuk sub-fitur (contoh di Presensi: Tab *Clock-In*, Tab *Riwayat*, Tab *Cuti*).  
  * Konten dinamis yang merespons pilihan Tab.

---

## **2\. User Workflows (Alur Perilaku UI)**

* **Workflow A: Inisialisasi Aplikasi (Post-Login Rendering)**  
  1. Staf berhasil *login*, *Frontend* menerima Token JWT.  
  2. *State Management* (misal: Redux/Zustand) menyimpan daftar allowed\_modules dan allowed\_entities.  
  3. *Frontend* memetakan (*mapping*) Master Data Menu bawaan sistem dengan allowed\_modules milik staf.  
  4. *Sidebar* hanya menampilkan ikon/teks menu yang hasil pemetaannya valid (cocok). Menu yang tidak diizinkan diabaikan dari proses *rendering* (tidak ada di DOM *browser*).  
* **Workflow B: Responsivitas Mobile (Layar di bawah 768px)**  
  1. Staf membuka MyZamzami di *browser* HP (Android/iOS).  
  2. *Sidebar* otomatis disembunyikan. Header menampilkan ikon Hamburger (garis tiga) di sudut kiri atas.  
  3. *Tab Navigation* di area kerja yang tadinya memanjang horizontal, berubah bentuk menjadi *Scrollable Tabs* (bisa digeser ke kiri-kanan) atau *Dropdown Select* agar tidak merusak tata letak layar kecil.  
* **Workflow C: Transisi Ganti Entitas (Entity Switch)**  
  1. Staf mengklik *Entity Switcher* di Header dan memilih "Cinta Sedekah".  
  2. *Frontend* memicu *Global State Update*.  
  3. *Frontend* memunculkan indikator *Loading* transparan (sepersekian detik).  
  4. Seluruh komponen (Tabel, Dasbor, Presensi) yang menggunakan parameter entity\_id otomatis memanggil ulang API (*Re-fetch*) untuk menarik data Cinta Sedekah. Warna tema UI (opsional) bisa berubah sedikit untuk menegaskan perpindahan ruang kerja.

---

## **3\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)**

### **Kategori 1: Employee Portal (Akses: Staf)**

* **Zero-Friction Navigation:** Pastikan perpindahan antar Tab (Level 3\) tidak memicu *Full Page Reload* (kedipan layar putih). Gunakan *Client-Side Routing* untuk transisi halaman yang sehalus aplikasi *Native*.  
* **Smart Redirects:** Jika staf mengakses URL langsung (misal: my.zamzami.or.id/hr/approve) tetapi ia adalah staf biasa, arahkan secara halus ke my.zamzami.or.id/dashboard tanpa *error* yang mengintimidasi.

### **Kategori 2: HRD/Admin Portal (Akses: HRD/Superadmin)**

* **Superadmin Visual Cues:** Jika seseorang *login* dengan atribut is\_superadmin \= true, berikan indikator visual khusus pada *Header* (misal: pita tipis warna emas di bagian paling atas layar) agar mereka sadar bahwa mereka sedang memegang kendali penuh yang sensitif.

### **Kategori 3: Strategic Portal (Akses: Manajemen)**

* **Focus Mode (Expandable Workspace):** Eksekutif atau Manajer sering melihat dasbor analitik (seperti *Live Headcount*) yang lebar. Sediakan tombol panah kecil di bawah Sidebar untuk menciutkan menu (*Collapse*) menjadi hanya kumpulan ikon, sehingga area tabel/grafik menjadi selebar layar penuh.

### **Kategori 4: Non-Functional Requirements (Performa & Optimasi)**

* **Component Lazy Loading (Code Splitting):** Jangan memuat seluruh skrip aplikasi di awal. Jika staf tidak memiliki hak akses ke modul "Manajemen Proyek", *browser* tidak boleh mengunduh *file javascript/komponen UI* untuk Manajemen Proyek. Ini menghemat *bandwidth* dan mempercepat *loading* awal.  
* **State Persistence:** Gunakan *Local Storage* atau *Session Storage* untuk menyimpan status UI non-kritis. Contoh: Jika staf memperkecil (*collapse*) Sidebar hari ini, besok saat ia buka aplikasi lagi, Sidebar harus tetap dalam keadaan kecil (mengingat preferensi pengguna).  
* **PWA Readiness (Fondasi Aplikasi Android):** Konfigurasikan file manifest.json dan *Service Worker* sejak awal. Ini adalah langkah vital agar nantinya aplikasi Web ini bisa di- *install* langsung ke layar utama (*Homescreen*) HP Android staf layaknya aplikasi asli, mendukung *caching* *asset* gambar/ikon untuk mempercepat akses harian.

# **PRD: Modul Master Data & Admin Control Panel**

**Instruksi Global untuk AI:** Bangun modul ini dengan hierarki otorisasi administratif dua tingkat: **Global Superadmin** (Akses ke seluruh sistem & 4 entitas) dan **Entity Admin** (Akses terbatas hanya pada entitas tempat ia ditugaskan). Gunakan komponen *Data Table* modern sesuai gaya visual **Jampack Desktop Template**. Pastikan antarmuka menyesuaikan secara dinamis (*conditional rendering*); Entity Admin tidak boleh melihat tombol, menu, atau data yang berada di luar yurisdiksi entitasnya.

## **1\. Arsitektur Logika Admin (Database & Middleware Update)**

Sebelum menyusun UI, Antigravity harus memperbarui skema *backend*:

* **Perubahan Status Admin:** Tinggalkan boolean is\_superadmin tunggal. Ubah menjadi:  
  * is\_global\_admin (Boolean di tabel users): Jika *true*, ia adalah "Dewa" yang menguasai seluruh sistem.  
  * is\_entity\_admin (Boolean di tabel user\_entity\_access): Jika *true* pada baris *entity* tertentu, maka ia adalah penguasa di entitas tersebut (misal: Admin khusus Cinta Sedekah).  
* **Middleware Baru (verify\_admin\_access):** *Middleware* ini mengevaluasi tipe admin. Jika ia mencoba mengedit staf di HaramainKU, *middleware* mengecek: *Apakah dia Global Admin? ATAU Apakah dia Entity Admin untuk HaramainKU?* Jika keduanya salah, tolak dengan 403 Forbidden.

---

## **2\. Sitemap & Struktur Navigasi (UI/UX Layout)**

**Menu Utama:** 👑 Admin Panel (Hanya muncul jika user adalah Global Admin atau Entity Admin)

* **Sub-Menu 1: Master Organisasi** *(Visibilitas: KHUSUS Global Admin)*  
  * *Tab 1: Manajemen Entitas* \- CRUD untuk pendaftaran unit perusahaan (Madinatul Qur'an, HaramainKU, dll.).  
  * *Tab 2: Registri Modul* \- Kontrol *Master Switch* untuk mematikan/menyalakan modul secara global.  
* **Sub-Menu 2: Manajemen Karyawan** *(Visibilitas: Global Admin & Entity Admin)*  
  * *Tab 1: Direktori Staf*  
    * **Jika Global Admin:** Melihat daftar seluruh staf dari semua entitas.  
    * **Jika Entity Admin:** Hanya melihat daftar staf yang tergabung di entitasnya.  
  * *Tab 2: Pendaftaran Karyawan (Onboarding)* \- Form pendaftaran akun baru. Entity Admin hanya bisa mendaftarkan staf untuk masuk ke entitas miliknya.  
* **Sub-Menu 3: Kontrol Akses (Permission Matrix)** *(Visibilitas: Global Admin & Entity Admin)*  
  * *Tab 1: Alokasi RACI Dasar* \- Mengatur level akses bawaan staf (Responsible, Accountable, dll.) pada entitas tersebut.  
  * *Tab 2: Module Toggling* \- Tabel matriks *Toggle Switch*. Entity Admin hanya bisa menyalakan/mematikan modul bagi staf di bawah naungannya, dan hanya untuk modul yang diizinkan oleh Global Admin.

---

## **3\. User Workflows (Alur Kerja Pengguna)**

* **Workflow A: Pendelegasian Wewenang (Oleh Global Admin)**  
  1. Global Admin masuk ke *Admin Panel*.  
  2. Membuka profil staf bernama "Ahmad" (Kepala HRD Bimbingan Islam).  
  3. Pada bagian hak akses, Global Admin mencentang opsi "Set as Entity Admin" khusus untuk kotak "Bimbingan Islam".  
  4. Ahmad kini memiliki akses ke *Admin Panel*, namun layarnya terkunci secara eksklusif hanya untuk mengelola data Bimbingan Islam.  
* **Workflow B: Pengelolaan Karyawan oleh Entity Admin**  
  1. Ahmad (Entity Admin Bimbingan Islam) masuk ke *Direktori Staf*. Ia tidak bisa mencari atau melihat nama staf dari HaramainKU.  
  2. Ahmad mendaftarkan staf baru bernama "Budi".  
  3. Saat Ahmad mencoba menugaskan Budi ke entitas, sistem memblokir pilihan entitas lain. Budi otomatis hanya terdaftar di Bimbingan Islam.  
  4. Ahmad membuka *Module Toggling* dan mengaktifkan modul "Presensi" untuk Budi.  
* **Workflow C: Konflik Lintas Entitas (Cross-Entity Allocation)**  
  1. Budi (staf Bimbingan Islam) diminta untuk membantu proyek di Cinta Sedekah.  
  2. Ahmad (Entity Admin Bimbingan Islam) **tidak bisa** menambahkan Budi ke Cinta Sedekah karena itu di luar wewenangnya.  
  3. Hanya **Global Admin** ATAU **Entity Admin Cinta Sedekah** yang bisa menarik (mengundang) akun Budi ke dalam *workspace* Cinta Sedekah melalui ID Karyawan/Email Budi.

---

## **4\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)**

### **Kategori 1: Admin/HR Portal (Akses: Global Admin & Entity Admin)**

* **Contextual Data Tables:** Tabel yang digunakan di seluruh layar Admin harus memiliki filter entity\_id tersembunyi yang diisi otomatis oleh sistem (*backend*) jika yang *login* adalah Entity Admin. Ini mencegah *bug UI* menampilkan data yang salah.  
* **Safe Invite System:** Sediakan fitur "Invite Existing Staff". Jika seorang staf sudah memiliki akun (misal terdaftar di Pesantren), Entity Admin Edutech tidak perlu membuat akun baru. Mereka cukup memasukkan email staf tersebut untuk mengirimkan undangan masuk ke entitas Edutech (menghindari duplikasi akun).

### **Kategori 2: Strategic Portal (Akses: Pemilik Sistem / Global Admin)**

* **Global Kill Switch:** Fitur eksklusif Global Admin. Jika Global Admin menonaktifkan akun seorang staf dari level *Root* (Master Data), staf tersebut langsung kehilangan akses ke *seluruh* entitas secara bersamaan.  
* **Hierarchical Audit Trail:** Sistem *log* mencatat siapa yang melakukan perubahan. Contoh di Dasbor Global Admin: "Akses modul keuangan Budi diaktifkan oleh Ahmad (Entity Admin) pada tanggal X".

### **Kategori 3: Non-Functional Requirements (Keamanan & Middleware)**

* **Strict UI Rendering Check:** *Frontend* tidak boleh sekadar menyembunyikan tombol "Tambah Entitas" menggunakan CSS (display: none) bagi Entity Admin. Elemen tersebut harus benar-benar **tidak dirender** di DOM sejak awal menggunakan logika *Conditional Rendering* di *framework frontend* (berdasarkan *Payload* JWT).  
* **Horizontal Privilege Escalation Prevention:** *Backend* wajib memastikan bahwa ID pengguna yang mengirim permintaan perubahan akses (Entity Admin) memiliki hak atas entity\_id dan user\_id yang sedang diubah. Jika seorang Entity Admin mencoba memanipulasi *API Payload* untuk mengedit staf di entitas tetangga, tolak dengan 403 Forbidden dan catat ke security\_logs.

# **PRD: Modul Presensi Kehadiran** 

**Instruksi Global untuk AI:** Bangun modul ini dengan antarmuka yang bersih (*clean UI*) seperti Jampack Desktop Template. Terapkan navigasi 3 level (Menu \> Sub-Menu \> Tab). Pastikan seluruh tabel, *query backend*, dan visibilitas UI tunduk pada sistem Multi-Tier Admin: **Global Admin** dapat melihat/mengatur presensi seluruh entitas, sementara **Entity Admin** hanya memiliki akses *Read/Write/Approve* terbatas pada entitas tempat ia ditugaskan.

## **1\. Sitemap & Struktur Navigasi (UI/UX Layout)**

**Menu Utama:** ⏱️ Presensi

* **Sub-Menu 1: Dasbor**  
  * *Tab 1: Dasbor Pribadi* (Akses: Semua Staf) \- Ringkasan kehadiran pribadi, sisa cuti, dan Gamifikasi (*Positive Nudges*).  
  * *Tab 2: Live Headcount* (Akses: Manajemen & Admin) \- Radar status karyawan hari ini. *Entity Admin* hanya melihat data entitasnya; *Global Admin* melihat agregat semua entitas.  
  * *Tab 3: Analitik Kinerja* (Akses: Manajemen & Admin) \- Data *Overwork & Burnout Detector* (terfilter sesuai yurisdiksi entitas).  
* **Sub-Menu 2: Pencatatan Kehadiran**  
  * *Tab 1: Clock-In / Out* (Akses: Semua Staf) \- Antarmuka *action card* utama untuk absen \+ validasi *Offline Mode*.  
  * *Tab 2: Riwayat Pribadi* (Akses: Semua Staf) \- Tabel historis presensi mandiri.  
  * *Tab 3: Rekapitulasi Induk* (Akses: Global & Entity Admin) \- Tabel master. Entity Admin tidak akan melihat *dropdown* filter lintas entitas.  
* **Sub-Menu 3: Pengajuan & Koreksi**  
  * *Tab 1: Form Cuti & Izin* (Akses: Semua Staf) \- Form pengajuan dengan visibilitas kalender tim (khusus rekan satu entitas/divisi).  
  * *Tab 2: Koreksi Mandiri* (Akses: Semua Staf) \- Form untuk merevisi jam absen (*human/system error*).  
  * *Tab 3: Approval Center* (Akses: Atasan & Admin) \- Antrean persetujuan. Entity Admin hanya menerima form dari staf di entitasnya.  
* **Sub-Menu 4: Pengaturan Kehadiran** (Akses: Global & Entity Admin)  
  * *Tab 1: Aturan Cuti & Libur* \- Setup kuota cuti. (Entity Admin hanya bisa mengatur kuota lokal entitasnya, Global Admin bisa memotong cuti bersama massal).  
  * *Tab 2: Lokasi & Keamanan* \- Setup Geofence (radius GPS) dan SSID WiFi kantor.  
  * *Tab 3: Anomaly Radar* \- Pengaturan batas toleransi keterlambatan.

---

## **2\. User Workflows (Alur Kerja Pengguna yang Diperbarui)**

* **Workflow A: Smart Clock-In (Harian)**  
  1. Staf membuka aplikasi (Mobile) \> Masuk ke Tab *Clock-In*.  
  2. Sistem melakukan pengecekan isMockLocation dan status *root* perangkat.  
  3. Sistem menarik aturan Geofence/WiFi spesifik milik entitas staf tersebut (misal: Bimbingan Islam mungkin punya toleransi radius 100m, sementara Pesantren hanya 30m).  
  4. Jika valid, tombol "Masuk" aktif. Data tersimpan dengan *timestamp* (Mendukung *Offline Mode Sync*).  
* **Workflow B: Pengajuan Cuti & Approval Berjenjang**  
  1. Staf mengisi Form Cuti. Sistem mengirim notifikasi ke Atasan Langsung.  
  2. Atasan menyetujui. Sistem mem- *forward* ke HRD (*Entity Admin* di entitas tersebut).  
  3. Jika HRD HaramainKU (*Entity Admin*) membuka *Approval Center*, ia tidak akan pernah melihat pengajuan cuti dari staf Cinta Sedekah. Ia hanya mengeksekusi data di wilayahnya.  
* **Workflow C: Penyesuaian Geofence oleh Entity Admin**  
  1. Kantor Bimbingan Islam pindah gedung.  
  2. Entity Admin Bimbingan Islam masuk ke *Pengaturan Kehadiran \> Lokasi*.  
  3. Ia memperbarui titik koordinat *Lat/Long* kantor. Perubahan ini langsung berlaku bagi seluruh staf Bimbingan Islam tanpa mempengaruhi koordinat absen staf Madinatul Qur'an.

---

## **3\. Spesifikasi Fitur Teknis (Berdasarkan Aktor)**

### **Kategori 1: Employee Portal (Akses: Staf)**

* **Dynamic Geofencing Validation:** Validasi *frontend/backend* harus memanggil tabel pengaturan lokasi berdasarkan entity\_id yang sedang aktif di JWT pengguna.  
* **Selfie Verification WFH:** Jika staf memilih mode Dinas Luar/WFH, wajib menyertakan swafoto *real-time* (tidak bisa dari galeri) dan koordinat aktual, yang akan diberi penanda (*flag*) khusus di *Approval Center*.

### **Kategori 2: HR/Admin Portal (Akses: Global Admin & Entity Admin)**

* **Isolated Multi-Tier Approval:**  
  * Fitur *Super-Override* (pintas persetujuan darurat) hanya bisa digunakan oleh Entity Admin untuk staf di entitasnya, dan oleh Global Admin untuk seluruh staf.  
* **Automated Leave Management (Scope Aware):**  
  * Jika Global Admin memasukkan "Cuti Bersama Idul Fitri", sistem otomatis memotong saldo cuti di *semua* entitas.  
  * Jika Entity Admin Pesantren memasukkan "Libur Khusus Milad Pesantren", sistem *hanya* memotong/menyesuaikan jadwal staf Pesantren.  
* **Export & Anomaly Radar:** Laporan Excel/CSV dan Notifikasi peringatan (misal: "Staf X absen 3 hari berturut-turut") akan dialirkan secara spesifik ke *Dashboard* Entity Admin yang berwenang.

### **Kategori 3: Strategic Portal (Akses: Manajemen & Pemilik)**

* **Aggregated Live Headcount:** Jika seorang Manajer level grup (Global Admin) membuka *Live Headcount Dashboard*, ia melihat diagram lingkaran kumulatif seluruh ekosistem Zamzami Internasional, dengan fitur *drill-down* (klik untuk memecah data per entitas).  
* **Burnout Detector:** Algoritma membaca pola *clock-out* larut malam (\>10 jam kerja). Global Admin bisa membandingkan "Tingkat Burnout" antara HaramainKU vs Bimbingan Islam secara langsung di satu layar.

### **Kategori 4: Non-Functional Requirements (Keamanan & Kestabilan)**

* **Cross-Entity Exploit Prevention:** Jika ada kebocoran API, *backend* wajib memverifikasi bahwa ID Staf yang sedang diabsenkan (*clock-in*) benar-benar memiliki user\_entity\_access yang sah ke entitas yang dituju dalam *request payload*.  
* **Timestamp Tamper-Proof:** Waktu absen menggunakan waktu *Server (Backend)*, bukan waktu *Device/HP (Frontend)*, untuk mencegah staf mengakali jam keterlambatan dengan mengubah jam di ponsel mereka.

# **PRD: Modul Notifikasi Terpusat (Sistem Saraf Pusat MyZamzami)**

**Instruksi Global untuk AI:** Bangun modul ini bukan sekadar sebagai antarmuka pencatat pesan, melainkan sebagai mesin *Event-Driven* cerdas (Sistem Saraf Pusat). Terapkan protokol komunikasi *real-time* (WebSockets/SSE) dan sistem antrean pesan (*Message Broker*). Integrasikan filter entity\_id untuk memastikan isolasi data *Multi-Tier Admin* tetap terjaga. Antarmuka harus mengikuti gaya **Jampack Desktop Template** dengan penekanan pada *micro-interactions* (seperti efek *hover* dan *state* tombol aksi).

## **1\. Skema Database & Message Queue (Fondasi Data)**

* **Tabel notifications**  
  * id (UUID), user\_id (Penerima), entity\_id (Konteks unit bisnis).  
  * sender\_id (Pengirim/Sistem).  
  * title, message (String).  
  * raci\_tier (Integer: 1 \= R/A, 2 \= C/I).  
  * is\_actionable (Boolean), action\_url (String/Endpoint API untuk eksekusi langsung).  
  * status (Enum: unread, read, actioned).  
  * scheduled\_for (Timestamp \- Untuk menahan pesan saat *Quiet Hours*).  
  * group\_key (String \- Kunci unik untuk algoritma *Smart Batching*).  
* **Tabel user\_focus\_states**  
  * user\_id (Primary Key).  
  * focus\_until (Timestamp \- Waktu berakhirnya mode fokus).  
  * quiet\_hours\_start, quiet\_hours\_end (Time \- Jadwal Etis ditarik dari Modul Presensi).  
* **Tabel emergency\_override\_logs** (Audit Trail)  
  * id, sender\_id, target\_id, timestamp, context.

---

## **2\. Sitemap & Komponen Antarmuka (UI/UX)**

Modul ini sebagian besar "hidup" di latar belakang dan merender dirinya pada komponen *Header* global, bukan sebagai halaman penuh.

* **Komponen Header: Bell Icon (Notification Center)**  
  * *Visual Indicator:* Jika ada notifikasi Tier 1 (R/A), tampilkan *Badge* Merah berangka. Jika hanya Tier 2 (C/I), tampilkan *Dot* kecil tanpa angka.  
  * *Dropdown Drawer:* Dibagi menjadi 2 Tab: **"Butuh Tindakan"** (Actionable) dan **"Informasi"** (Read-only).  
* **Komponen *In-Line Action Card* (Di dalam Drawer)**  
  * Kartu notifikasi dengan tombol aksi berjejer (contoh: tombol hijau \[Setujui\], tombol merah \[Tolak\]).  
  * Ketika diklik, kartu menampilkan animasi *loading spinner* sesaat, lalu menghilang secara halus (*fade out*).  
* **Komponen Profil (Global Status)**  
  * Menu *Dropdown* Profil (Kanan Atas) memiliki *Toggle* **"Mulai Mode Fokus"**.  
  * Klik memunculkan *Dropdown* wajib: \[30 Menit\], \[1 Jam\], \[2 Jam\].  
  * Indikator Visual (🎧 atau ⛔) menempel di sebelah nama staf tersebut di *seluruh* tabel dan obrolan dalam aplikasi.  
* **Halaman Pengaturan Preferensi (User Settings)**  
  * Tabel matriks *Omnichannel Routing*: Pengguna bisa mencentang (Web, Mobile Push, WhatsApp/Email) berdasarkan kategori notifikasi (Kritis, Standar, Rangkuman).

---

## **3\. User Workflows (Logika & Alur Eksekusi)**

* **Workflow A: Penahanan Jam Tenang (Quiet Hours) & Sinkronisasi Pagi**  
  1. Pukul 22:30, staf HRD memvalidasi dokumen yang menyeret nama Staf B.  
  2. *Backend* mengecek profil Staf B. Karena sudah di luar jam kerja (dan bukan darurat), *backend* mengatur scheduled\_for pada notifikasi tersebut menjadi esok hari pukul 08:00 (atau saat Staf B *Clock-In*).  
  3. Pesan masuk ke antrean *Redis*. HP dan *Browser* Staf B tidak berbunyi.  
* **Workflow B: "Knock-Twice" Emergency Override**  
  1. Staf B sedang mengaktifkan Mode Fokus (Ikon 🎧 aktif).  
  2. Manajer (Atasan Langsung) mendelegasikan tugas kritis di sistem kepada Staf B.  
  3. Sistem mencegat Manajer dengan *Pop-up Prompt*: *"Staf B sedang Mode Fokus hingga 14:00. Apakah ini darurat?"*  
  4. Manajer memilih tombol \[Dobrak Mode Fokus\].  
  5. Sistem mencatat aksi ini di emergency\_override\_logs dan seketika menembakkan *WebSockets/Push Notification* bersuara ke perangkat Staf B.  
* **Workflow C: Eksekusi In-Line Action**  
  1. Atasan menerima notifikasi "Pengajuan Izin Cuti dari Fulan".  
  2. Atasan membuka ikon Lonceng, melihat kartu notifikasi, dan langsung mengklik tombol \[Setujui\] di dalam kartu tersebut.  
  3. *Frontend* mengirim permintaan API latar belakang ke Modul Presensi/Cuti. Status berhasil, kartu notifikasi lenyap, tanpa Atasan perlu memuat (*load*) halaman Modul Presensi.

---

## **4\. Spesifikasi Arsitektur Logika (Backend & Integrasi)**

### **Kategori 1: Mesin Filter Cerdas (Smart Algorithms)**

* **Smart Batching Engine:** Sebuah *CRON/Worker* di *backend* yang mengevaluasi tabel notifikasi setiap menit. Jika menemukan \>3 entri dengan group\_key yang sama dan status belum dibaca dalam rentang 30 menit terakhir, sistem menyembunyikan notifikasi individual tersebut dan membuat 1 notifikasi agregat (misal: "Ahmad dan 3 lainnya mengubah Dokumen X").  
* **RACI-Driven Triage Rules:** Integrasikan ini dengan tabel user\_entity\_access (dari Modul Master Data). Nilai RACI menentukan rute pengiriman (Tier 1 \= *WebSockets Active Push*; Tier 2 \= *Silent Database Insert*).

### **Kategori 2: Infrastruktur Kinerja (Performance & Sync)**

* **WebSockets / Server-Sent Events (SSE):** Terapkan komunikasi dua arah (*bidirectional*). Ketika *trigger* terjadi di *backend*, dorong (*push*) *payload* JSON langsung ke klien (*browser* / aplikasi seluler) tanpa menunggu klien me-*refresh* halaman.  
* **Cross-Device State Management:** Jika *endpoint* PUT /api/notifications/:id/action berhasil dieksekusi dari aplikasi Android, *backend* wajib memancarkan *event* notification\_actioned melalui WebSockets ke seluruh sesi aktif milik *user\_id* tersebut. *Browser* web yang sedang terbuka di laptop staf akan menangkap *event* ini dan otomatis menghilangkan notifikasi tersebut dari layar tanpa perlu di-*refresh*.  
* **Message Broker (Redis/RabbitMQ):** Wajib digunakan untuk mengelola tumpukan pengiriman massal (saat *Quiet Hours* berakhir di pagi hari) agar server *database* utama (PostgreSQL/MySQL) tidak *crash* (*spike load*) akibat memproses ribuan notifikasi secara bersamaan pada pukul 08:00 WIB.

### **Kategori 3: Keamanan & Multi-Tier Admin Awareness**

* *Entity Context Payload:* Setiap pesan WebSockets yang ditembakkan harus memuat parameter entity\_id. Jika staf memiliki sesi aktif di ruang kerja HaramainKU, pastikan *Pop-up Banner* untuk urusan Cinta Sedekah tidak muncul di layar tersebut secara *real-time* (atau muncul dengan label entitas yang sangat kontras untuk mencegah salah klik).  
* *Audit Log Visibility:* Catatan dari penggunaan fitur "Dobrak/Override" akan disalurkan secara otomatis ke *Dashboard* Analitik milik Global Admin atau Entity Admin (sesuai yurisdiksi) sebagai bahan evaluasi budaya kerja (apakah atasan terlalu sering mengganggu staf yang sedang fokus).

# **PRD: Modul Kalender & Smart Reminder (Asisten Kognitif MyZamzami)**

**Instruksi Global untuk AI:** Bangun modul ini sebagai pusat kendali waktu (*time-management hub*) dengan antarmuka dinamis menggunakan *library* kalender yang solid (misal: FullCalendar). Prioritaskan *Neuro-friendly UI*: hindari kepadatan visual yang memicu kelelahan mata. Gunakan pemisahan *layer* warna yang lembut (*pastel color-coding*) berdasarkan kategori. Seluruh *query* dan visibilitas harus mengikat pada parameter entity\_id dan user\_id, agar kalender pribadi staf di entitas Bimbingan Islam tidak bocor ke entitas lain, kecuali untuk kalender "Libur Nasional/Grup".

## **1\. Skema Database Dasar (ERD & Rekayasa Pengulangan)**

Sesuai strategi penyimpanan "Individual Entry" untuk fleksibilitas maksimal, Antigravity harus membangun tabel berikut:

* **Tabel reminders (Tabel Transaksional Utama)**  
  * id (UUID, PK)  
  * entity\_id (UUID, FK \- Untuk isolasi entitas)  
  * user\_id (UUID, FK \- Pemilik jadwal)  
  * title (String), description (Text, Nullable)  
  * start\_datetime, end\_datetime (Timestamp \- Wajib diindeks untuk performa *query* per bulan)  
  * is\_all\_day (Boolean)  
  * category\_id (Enum/String: Reminder, Meeting, Deadline, Event)  
  * linked\_module\_slug (String \- Konteks ke modul lain, misal: 'project-tasks')  
  * linked\_item\_id (UUID \- ID dari dokumen/tugas yang ditautkan)  
  * status (Enum: pending, completed, snoozed, cancelled)  
  * parent\_recurring\_id (UUID, Nullable \- Untuk melacak apakah entri ini bagian dari sebuah seri berulang)  
* **Tabel recurring\_patterns (Mesin Cetak Jadwal)**  
  * id (UUID, PK)  
  * user\_id (UUID)  
  * frequency\_type (Enum: daily, weekly, monthly, yearly, custom)  
  * interval (Integer \- misal: setiap '2' minggu)  
  * custom\_logic\_json (JSON \- Untuk pola rumit seperti "Senin & Rabu" atau "Tanggal 25")  
  * end\_date (Timestamp, Nullable)  
  * holiday\_behavior (Enum: skip, move\_to\_prev\_workday, move\_to\_next\_workday)  
* **Tabel master\_holidays (Kalender Statis)**  
  * id, date, name, entity\_scope (Apakah libur global seluruh Zamzami Internasional, atau libur khusus satu entitas).

---

## **2\. Sitemap & Komponen Antarmuka (UI/UX Layout)**

**Menu Utama:** 📅 Kalender & Agenda

* **Komponen 1: Quick Capture (Header Global)**  
  * Tombol melayang (FAB) atau ikon petir (⚡) di Header atas.  
  * Membuka *Pop-up Modal* instan. Input: "Apa yang perlu diingat?" dan "Kapan?". Eksekusi simpan di bawah 5 detik.  
* **Sub-Menu 1: Kalender Utama (Multi-Scale View)**  
  * *Toolbar Navigasi:* Tombol navigasi (Bulan/Minggu/Hari/Agenda).  
  * *Layer Toggle (Sidebar Kiri):* Deretan *checkbox* untuk memfilter tampilan. Misal: \[x\] Pengingat Pribadi, \[x\] Hari Libur, \[ \] Tenggat Waktu Proyek.  
  * *Canvas Utama:* *Grid* kalender yang interaktif (mendukung *Drag & Drop* untuk memindahkan jadwal, dan *Resize* batas kotak untuk menambah durasi).  
* **Sub-Menu 2: Action Tracker (Dasbor Kedisiplinan)**  
  * *List View* yang memprioritaskan jadwal hari ini (Hari Ini, Esok, Mendatang).  
  * Terdapat *Badge Alert* jika ada tugas kritis yang di-*snooze* lebih dari 3 kali.

---

## **3\. User Workflows (Logika Eksekusi)**

* **Workflow A: Penciptaan "Context-Priming" Reminder**  
  1. Staf membuka Modul Catatan, membaca notulen rapat.  
  2. Staf menekan tombol "Buat Pengingat dari Catatan ini".  
  3. *Pop-up Quick Capture* muncul. *Field* linked\_module\_slug dan linked\_item\_id otomatis terisi oleh sistem. Staf memasukkan waktu pengingat.  
  4. Saat alarm berbunyi esok harinya, *Pop-up Notifikasi* memiliki tombol "Buka Catatan". Mengklik tombol itu langsung memuat dokumen terkait.  
* **Workflow B: Eksekusi *Background Job* untuk Pola Berulang**  
  1. Staf mengatur reminder "Laporan Mingguan" setiap hari Jumat pagi selama setahun ke depan, dengan opsi holiday\_behavior: move\_to\_prev\_workday.  
  2. Saat form di-*submit*, *Backend* menyimpan data di recurring\_patterns.  
  3. *Asynchronous Background Worker* (skrip latar belakang) langsung berlari, melakukan iterasi (*looping*), mengecek apakah ada hari Jumat yang menabrak tanggal di tabel master\_holidays.  
  4. Jika ya (misal Jumat itu tanggal merah), entri spesifik untuk minggu tersebut dicatat pada hari Kamis (start\_datetime digeser). Sistem menghasilkan 52 baris unik di tabel reminders.  
* **Workflow C: Auto-Escalation (Snooze Berlebih)**  
  1. Staf mendapat alarm untuk *Tenggat Waktu OKR*, namun menekan "Snooze 1 Jam".  
  2. Sistem memperbarui nilai status menjadi 'snoozed' dan menambah *counter* tak kasat mata.  
  3. Setelah klik "Snooze" yang ketiga kali di hari yang sama, sistem secara otomatis mengirimkan notifikasi senyap (Lapisan Tier 2\) kepada atasan langsung (berdasarkan Matriks RACI): "Fulan menunda tenggat waktu X sebanyak 3 kali".

---

## **4\. Spesifikasi Arsitektur Teknis & Kepatuhan AI**

### **Kategori 1: Optimasi Kinerja Database (Frontend-Backend Handshake)**

* **Lazy Loading Calendar Data:** Antigravity tidak boleh menarik *seluruh* data reminders milik staf sekaligus. Saat UI memuat tampilan "Agustus 2026", *Frontend* hanya mengirim API *Request* rentang waktu 1 Agustus hingga 31 Agustus. Jika staf menekan "Bulan Depan", barulah data September ditarik. Ini mencegah sistem lambat.

### **Kategori 2: Integrasi dengan Modul Notifikasi (Eskalasi)**

* **Unified Event Bus:** Modul Kalender tidak bertugas *memunculkan* pop-up di layar pengguna. Modul Kalender murni bertugas mengatur jadwal. Ketika start\_datetime tiba, Modul Kalender wajib mengirimkan sinyal/ *payload* JSON ke **Modul Notifikasi Terpusat** (Sistem Saraf Pusat yang kita rancang sebelumnya). Modul Notifikasilah yang akan memutuskan apakah alarm ini berbunyi keras (Tier 1\) atau diam (Tier 2).

### **Kategori 3: Arsitektur Edit Seri (Handling Edits on Recurring Events)**

* **Three-Way Edit Logic:** Ketika staf mengklik sebuah pengingat yang merupakan hasil pengulangan (memiliki parent\_recurring\_id), UI wajib memunculkan *prompt* dengan 3 opsi sebelum menyimpan perubahan:  
  1. *This event only* (Ubah hanya baris data tabel ini).  
  2. *This and following events* (Ubah baris ini, dan *generate* ulang semua baris masa depan yang memiliki ID *parent* yang sama).  
  3. *All events in the series* (Ubah dari masa lalu hingga masa depan).

