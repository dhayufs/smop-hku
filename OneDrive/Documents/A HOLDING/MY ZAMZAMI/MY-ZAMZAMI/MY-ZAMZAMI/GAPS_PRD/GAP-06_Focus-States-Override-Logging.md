# GAP-06: Tabel `user_focus_states` & Emergency Override Logging

## Referensi PRD
- **PRD 6 — Modul Notifikasi Terpusat**, Bagian 1 (Skema Database)
- Baris 439–444

## Deskripsi Requirement PRD
PRD mendefinisikan 2 tabel dedikasi:

**Tabel `user_focus_states`:**
| Field | Deskripsi |
|-------|-----------|
| `user_id` | Primary Key |
| `focus_until` | Timestamp — waktu berakhirnya mode fokus |
| `quiet_hours_start` | Time — awal jam tenang |
| `quiet_hours_end` | Time — akhir jam tenang |

**Tabel `emergency_override_logs`:**
| Field | Deskripsi |
|-------|-----------|
| `id` | UUID |
| `sender_id` | Siapa yang men-override |
| `target_id` | Siapa yang di-override |
| `timestamp` | Waktu kejadian |
| `context` | Konteks/alasan |

## Kondisi Sistem Saat Ini
- Mode fokus disimpan langsung di tabel `users` sebagai kolom `is_focused` (boolean).
- **Tidak ada** tabel `user_focus_states` terpisah.
- **Tidak ada** field `focus_until` (waktu berakhir otomatis).
- Quiet Hours disimpan via `localStorage` di frontend settings notification, bukan di tabel backend terpisah.
- **Tidak ada** tabel `emergency_override_logs`.
- Knock-Twice logic ada di `notifications.js` tapi tanpa audit logging ke tabel khusus.

## Gap yang Harus Ditutup
1. Migrasi `is_focused` ke tabel `user_focus_states` dengan kolom `focus_until`.
2. Pindahkan `quiet_hours` dari localStorage ke backend.
3. Buat tabel `emergency_override_logs` dan catat setiap aksi override.
4. Tampilkan log override di dashboard admin sebagai bahan evaluasi.

## Tingkat Prioritas
🟡 **SEDANG** — Fitur fokus sudah berjalan secara fungsional. Enhancement ini untuk compliance arsitektur PRD.
