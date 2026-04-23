# GAP-05: Tabel `recurring_patterns` & Holiday-Aware Recurring Engine

## Referensi PRD
- **PRD 7 — Modul Kalender & Smart Reminder**, Bagian 1 (Skema Database) & Bagian 3 (Workflow B)
- Baris 524–533, 561–565

## Deskripsi Requirement PRD
PRD mendefinisikan tabel `recurring_patterns` yang terpisah dari `reminders`:

| Field | Deskripsi |
|-------|-----------|
| `frequency_type` | daily, weekly, monthly, yearly, custom |
| `interval` | Misal: setiap '2' minggu |
| `custom_logic_json` | JSON untuk pola rumit ("Senin & Rabu") |
| `end_date` | Tanggal berakhir pola |
| `holiday_behavior` | skip, move_to_prev_workday, move_to_next_workday |

**Workflow B** menjelaskan:
> *"Staf mengatur reminder 'Laporan Mingguan' setiap Jumat... Asynchronous Background Worker melakukan iterasi, mengecek apakah ada hari Jumat yang menabrak tanggal di tabel master_holidays. Jika ya, entri spesifik untuk minggu tersebut dicatat pada hari Kamis. Sistem menghasilkan 52 baris unik di tabel reminders."*

## Kondisi Sistem Saat Ini
- Recurring diimplementasikan secara **inline** di tabel `reminders` melalui kolom `recurrence_rule` (string: "daily", "weekly", dll.) dan `recurrence_until`.
- **Tidak ada** tabel `recurring_patterns` terpisah.
- **Tidak ada** field `holiday_behavior` (skip/move_to_prev/move_to_next).
- **Tidak ada** `custom_logic_json` untuk pola rumit (mis. "setiap Senin & Rabu").
- **Tidak ada** `interval` (mis. "setiap 2 minggu").
- Backend generate recurring events secara langsung tanpa background worker.
- Recurring events tidak pernah di-shift/skip berdasarkan libur nasional.

## Gap yang Harus Ditutup
1. Buat tabel `recurring_patterns` sesuai skema PRD.
2. Implementasi `holiday_behavior` yang mengecek `master_holidays` saat generating entries.
3. Tambahkan UI opsi "Jika jatuh di hari libur: [Lewati / Geser ke sebelumnya / Geser ke sesudahnya]".
4. Support `interval` (setiap N hari/minggu/bulan) dan `custom_logic_json`.

## Tingkat Prioritas
🟡 **SEDANG** — Fitur recurring dasar sudah berjalan. Enhancement ini untuk pola kompleks dan holiday-awareness.
