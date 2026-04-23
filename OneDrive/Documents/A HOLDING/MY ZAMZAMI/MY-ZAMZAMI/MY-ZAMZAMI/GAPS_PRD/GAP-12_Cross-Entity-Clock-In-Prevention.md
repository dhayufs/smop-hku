# GAP-12: Cross-Entity Exploit Prevention pada Clock-In API

## Referensi PRD
- **PRD 5 — Modul Presensi Kehadiran**, Bagian 3, Kategori 4
- Baris 421

## Deskripsi Requirement PRD
> *"Jika ada kebocoran API, backend wajib memverifikasi bahwa ID Staf yang sedang diabsenkan (clock-in) benar-benar memiliki user_entity_access yang sah ke entitas yang dituju dalam request payload."*

## Kondisi Sistem Saat Ini
- API Clock-In (`/api/attendance/clock`) melakukan autentikasi user via `getAuthUser()`.
- **Perlu diverifikasi**: apakah backend secara eksplisit mengecek bahwa `entity_id` dalam request payload cocok dengan `allowed_entities` di JWT payload user, atau apakah staf bisa memanipulasi `entity_id` payload untuk absen di entitas lain.

## Gap yang Harus Ditutup
1. Audit endpoint `/api/attendance/clock` secara spesifik.
2. Pastikan ada pengecekan: `entity_id` dalam request body harus ada di `user.allowed_entities`.
3. Jika tidak cocok, tolak dengan 403 dan log ke `security_logs`.

## Tingkat Prioritas
🔴 **TINGGI** — Ini adalah celah keamanan kehadiran lintas entitas.
