# GAP-13: Override Audit Log pada Dashboard Admin

## Referensi PRD
- **PRD 6 — Modul Notifikasi Terpusat**, Bagian 4, Kategori 3
- Baris 502

## Deskripsi Requirement PRD
> *"Catatan dari penggunaan fitur 'Dobrak/Override' akan disalurkan secara otomatis ke Dashboard Analitik milik Global Admin atau Entity Admin (sesuai yurisdiksi) sebagai bahan evaluasi budaya kerja (apakah atasan terlalu sering mengganggu staf yang sedang fokus)."*

## Kondisi Sistem Saat Ini
- Knock-Twice (Override Focus Mode) sudah diimplementasi di `notifications.js`.
- Namun **tidak ada** tabel `emergency_override_logs` yang mencatat aksi ini (lihat GAP-06).
- **Tidak ada** tampilan analitik di dashboard admin yang menunjukkan frekuensi override.
- Admin tidak bisa memonitor "siapa yang sering mendispatch fokus stafnya".

## Gap yang Harus Ditutup
1. Setelah tabel `emergency_override_logs` dibuat (GAP-06), tampilkan datanya di UI.
2. Bisa berupa widget/kartu di halaman Audit Logs atau Dashboard admin.
3. Tunjukkan statistik: frekuensi override per manajer, waktu override, dll.

## Tingkat Prioritas
🟢 **RENDAH** — Ini fitur analitik pelengkap yang berguna untuk evaluasi budaya kerja.
