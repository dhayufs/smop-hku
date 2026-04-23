# GAP-10: Entity Context Payload pada WebSockets/SSE

## Referensi PRD
- **PRD 6 — Modul Notifikasi Terpusat**, Bagian 4, Kategori 3
- Baris 501

## Deskripsi Requirement PRD
> *"Setiap pesan WebSockets yang ditembakkan harus memuat parameter entity_id. Jika staf memiliki sesi aktif di ruang kerja HaramainKU, pastikan Pop-up Banner untuk urusan Cinta Sedekah tidak muncul di layar tersebut secara real-time (atau muncul dengan label entitas yang sangat kontras untuk mencegah salah klik)."*

## Kondisi Sistem Saat Ini
- SSE events dikirim ke semua sesi aktif user tanpa filter `entity_id`.
- Notifikasi dari semua entitas muncul di dropdown bell icon tanpa memperhatikan entitas mana yang sedang aktif di layar.
- Tidak ada label visual "entitas yang kontras" pada notifikasi lintas entitas.

## Gap yang Harus Ditutup
1. Sertakan `entity_id` di setiap SSE payload.
2. Di frontend, filter notifikasi SSE berdasarkan `activeEntity.id` yang sedang dibuka.
3. Untuk notifikasi entitas lain, tampilkan badge/label entitas yang kontras.

## Tingkat Prioritas
🟢 **RENDAH** — Secara fungsional notifikasi sudah bekerja. Ini adalah improvement isolasi visual.
