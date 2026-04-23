# GAP-04: Component Lazy Loading Berdasarkan Izin Modul

## Referensi PRD
- **PRD 3 — Modul UI Shell & Navigasi Dinamis Frontend**, Bagian 3, Kategori 4
- Baris 280

## Deskripsi Requirement PRD
> *"Jangan memuat seluruh skrip aplikasi di awal. Jika staf tidak memiliki hak akses ke modul 'Manajemen Proyek', browser tidak boleh mengunduh file javascript/komponen UI untuk Manajemen Proyek. Ini menghemat bandwidth dan mempercepat loading awal."*

PRD menuntut **permission-based code splitting** — bukan sekadar Next.js auto code splitting per halaman, melainkan lazy loading yang secara eksplisit dikendalikan oleh `allowed_modules` di JWT.

## Kondisi Sistem Saat Ini
- Next.js otomatis melakukan code splitting per halaman (setiap route punya chunk sendiri).
- **Tidak ada** mekanisme lazy load per modul yang dikontrol berdasarkan izin.
- Semua komponen/chunk tetap tersedia untuk di-download oleh browser, meskipun staf tidak memiliki akses.
- Checklist PRD saat ini juga sudah menandai ini sebagai ⚠️ (partial).

## Gap yang Harus Ditutup
1. Implementasi `React.lazy()` + `Suspense` atau `next/dynamic` pada modul-modul utama.
2. Periksa `allowed_modules` sebelum memuat chunk komponen.
3. Pastikan browser tidak mengunduh JavaScript untuk modul yang tidak diizinkan.

## Tingkat Prioritas
🟢 **RENDAH** — Next.js sudah menyediakan auto code splitting dasar. Enhancement ini adalah optimasi bandwidth, bukan fitur fungsional.
