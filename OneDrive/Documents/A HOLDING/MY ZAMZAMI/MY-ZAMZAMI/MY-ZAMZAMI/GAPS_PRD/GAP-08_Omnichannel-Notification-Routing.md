# GAP-08: Omnichannel Notification Routing (Web Push, WhatsApp, Email)

## Referensi PRD
- **PRD 6 — Modul Notifikasi Terpusat**, Bagian 2 (Komponen Antarmuka)
- Baris 462–463

## Deskripsi Requirement PRD
> *"Tabel matriks Omnichannel Routing: Pengguna bisa mencentang (Web, Mobile Push, WhatsApp/Email) berdasarkan kategori notifikasi (Kritis, Standar, Rangkuman)."*

PRD menuntut notifikasi bisa dikirim ke **3 channel** berdasarkan preferensi user:
1. **Web** (SSE / In-App) ✅ — sudah ada
2. **Mobile Push Notification** ❌ — belum ada
3. **WhatsApp / Email** ❌ — belum ada

## Kondisi Sistem Saat Ini
- Notifikasi hanya dikirim via **SSE (In-App/Web)**.
- Pengaturan preferensi notifikasi ada di `/settings/notifications` tapi hanya menyimpan ke **localStorage** (per-device preference), bukan ke backend routing.
- **Tidak ada** integrasi WhatsApp API (WA Business API / Wablas / Fonnte).
- **Tidak ada** push notification via Firebase Cloud Messaging (FCM) meskipun PWA sudah terpasang.
- **Tidak ada** email notification untuk event/reminder kritis.
- Matriks routing di UI mungkin ada, tapi tanpa backend yang mengirim ke channel selain SSE, matriks itu tidak fungsional.

## Gap yang Harus Ditutup
1. Implementasi Web Push Notification via FCM (karena PWA/Service Worker sudah ada).
2. Integrasi WhatsApp API (pilih provider: Wablas, Fonnte, atau WA Business API resmi).
3. Gunakan `nodemailer` (sudah ada) untuk email notification kritis.
4. Buat backend routing engine: baca preferensi user → kirim ke channel yang dicentang.
5. Pindahkan preferensi dari localStorage ke tabel database agar bisa dibaca oleh backend.

## Tingkat Prioritas
🟡 **SEDANG** — Ini fitur "nice to have" yang meningkatkan engagement. Bisa dirilis bertahap per channel.
