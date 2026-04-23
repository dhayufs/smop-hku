# GAP-11: Horizontal Privilege Escalation Prevention (Backend Enforcement Ketat)

## Referensi PRD
- **PRD 4 — Modul Master Data & Admin Control Panel**, Bagian 4, Kategori 3
- Baris 351

## Deskripsi Requirement PRD
> *"Backend wajib memastikan bahwa ID pengguna yang mengirim permintaan perubahan akses (Entity Admin) memiliki hak atas entity_id dan user_id yang sedang diubah. Jika seorang Entity Admin mencoba memanipulasi API Payload untuk mengedit staf di entitas tetangga, tolak dengan 403 Forbidden dan catat ke security_logs."*

## Kondisi Sistem Saat Ini
- Backend melakukan pengecekan admin di beberapa endpoint (cek `is_global_admin` atau `is_entity_admin`).
- Namun **tidak semua** endpoint admin melakukan **double-check** apakah `user_id` target benar-benar ada di entitas Entity Admin yang meminta.
- Beberapa endpoint hanya cek "apakah requester adalah admin" tanpa memvalidasi apakah target user ada di yurisdiksi entitasnya.
- Logging ke `security_logs` saat terjadi 403 belum konsisten di semua endpoint admin.

## Gap yang Harus Ditutup
1. Audit setiap endpoint di `/api/admin/*` dan `/api/attendance/approvals`.
2. Untuk setiap mutasi (POST/PATCH/DELETE), pastikan ada validasi bahwa target `user_id` berada di `entity_id` yang diminta oleh Entity Admin.
3. Jika gagal, tolak dengan 403 dan catat ke `security_logs`.

## Tingkat Prioritas
🔴 **TINGGI** — Ini adalah celah keamanan potensial yang harus diperkuat.
