# GAP-07: Tabel `notifications` — Kolom `scheduled_for` & `group_key`

## Referensi PRD
- **PRD 6 — Modul Notifikasi Terpusat**, Bagian 1 (Skema Database)
- Baris 430–438

## Deskripsi Requirement PRD
PRD mendefinisikan skema tabel `notifications` dengan kolom-kolom berikut yang krusial:

| Field | Deskripsi |
|-------|-----------|
| `scheduled_for` | Timestamp — untuk menahan pesan saat Quiet Hours |
| `group_key` | String — kunci unik untuk Smart Batching Engine |

**Smart Batching Engine (Baris 490):**
> *"Jika menemukan >3 entri dengan group_key yang sama dan status belum dibaca dalam 30 menit, sistem menyembunyikan notifikasi individual dan membuat 1 notifikasi agregat."*

**Quiet Hours Scheduling (Baris 471):**
> *"Backend mengatur scheduled_for pada notifikasi tersebut menjadi esok hari pukul 08:00."*

## Kondisi Sistem Saat Ini
- Tabel `notifications` ada tapi **tidak memiliki** kolom `scheduled_for`.
- Quiet Hours diimplementasi via `localStorage` settings di frontend, bukan scheduled delay di backend.
- Smart Batching menggunakan `batchKey` parameter di `notifications.js`, tapi tidak ada kolom `group_key` di tabel.
- Tidak ada CRON/Worker yang mengagregasi notifikasi berdasarkan group_key secara periodik.

## Gap yang Harus Ditutup
1. Tambahkan kolom `scheduled_for` dan `group_key` pada tabel `notifications`.
2. Implementasi backend logic: jika Quiet Hours aktif, set `scheduled_for` ke waktu pagi.
3. Buat Worker/CRON yang periodik mengagregasi notifikasi dengan `group_key` sama.
4. Pastikan SSE hanya push notifikasi yang `scheduled_for <= NOW()`.

## Tingkat Prioritas
🟡 **SEDANG** — Notifikasi sudah berjalan. Enhancement ini untuk kecerdasan scheduling dan batching.
