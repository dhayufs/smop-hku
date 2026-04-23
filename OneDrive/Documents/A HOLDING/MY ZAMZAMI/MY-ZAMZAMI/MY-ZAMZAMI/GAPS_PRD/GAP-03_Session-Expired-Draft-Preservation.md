# GAP-03: Session-Expired Draft Preservation (Form Cuti Tersimpan saat 401)

## Referensi PRD
- **PRD 2 — Modul Middleware & Proteksi Isolasi Data**, Bagian 3, Workflow C
- Baris 183–186

## Deskripsi Requirement PRD
> *"Staf sedang mengisi Form Cuti yang panjang, namun Token JWT kedaluwarsa di latar belakang. Staf menekan 'Submit'. API menolak dengan 401. Frontend menangkap kode 401, **mengamankan draf form sementara (local storage)**, dan memunculkan Pop-up Modal 'Sesi Habis, Masukkan Ulang Kata Sandi'."*

PRD secara eksplisit menuntut:
1. Intersepsi error 401 secara global.
2. Penyimpanan data form ke `localStorage` sebelum redirect.
3. Pop-up modal re-login (bukan redirect ke halaman login terpisah).
4. Setelah re-login berhasil, form tadi otomatis dimuat kembali.

## Kondisi Sistem Saat Ini
- Middleware mendeteksi `!token` → redirect ke `/auth/login` dengan query param `?redirect=pathname`.
- **Tidak ada** mekanisme intersepsi 401 di frontend yang menyimpan draf form ke localStorage.
- **Tidak ada** pop-up modal re-login inline; user langsung dilempar ke halaman login terpisah.
- Data form yang sedang diisi hilang permanen saat redirect.

## Gap yang Harus Ditutup
1. Implementasi global API interceptor (misalnya via `fetch` wrapper atau Axios interceptor) yang menangkap response 401.
2. Saat 401 tertangkap, simpan state form aktif ke `localStorage`.
3. Tampilkan modal re-login tanpa meninggalkan halaman, atau restore form setelah redirect kembali.

## Tingkat Prioritas
🟡 **SEDANG** — UX improvement yang signifikan untuk mencegah kehilangan data user.
