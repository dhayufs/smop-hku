# GAP-02: 2FA (Two-Factor Authentication) — Opsional tapi Disebut di PRD

## Referensi PRD
- **PRD 1 — Modul Sistem Inti, Autentikasi & Database Master**, Bagian 2
- Baris 73: *"pengaturan 2FA (opsional)"*

## Deskripsi Requirement PRD
Tab "Identitas & Keamanan" di halaman Profil Pribadi harus menyediakan opsi untuk **mengaktifkan 2FA (Two-Factor Authentication)**. PRD menandainya sebagai "opsional", tetapi tetap secara eksplisit menyebutkannya sebagai fitur yang harus ada di antarmuka.

## Kondisi Sistem Saat Ini
- Halaman `/settings/profile` hanya mendukung ubah nama dan ubah password.
- **Tidak ada** implementasi 2FA sama sekali (tidak ada TOTP, OTP via email, atau mekanisme verifikasi dua langkah lainnya).
- Tidak ada UI toggle untuk mengaktifkan/menonaktifkan 2FA.

## Gap yang Harus Ditutup
1. Tambahkan toggle "Aktifkan 2FA" pada halaman Profil Pribadi.
2. Implementasi mekanisme TOTP (Google Authenticator) atau OTP via email.
3. Pada saat login, jika 2FA aktif, tampilkan layar input kode verifikasi sebelum mengeluarkan JWT.

## Tingkat Prioritas
🟡 **SEDANG** — PRD menandainya sebagai "opsional", sehingga bisa dijadwalkan di fase selanjutnya.
