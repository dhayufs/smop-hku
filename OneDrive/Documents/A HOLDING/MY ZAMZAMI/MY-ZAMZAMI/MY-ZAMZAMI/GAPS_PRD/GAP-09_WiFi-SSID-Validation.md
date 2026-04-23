# GAP-09: WiFi SSID Validation untuk Clock-In

## Referensi PRD
- **PRD 5 — Modul Presensi Kehadiran**, Bagian 1 & 3
- Baris 375: *"Setup Geofence (radius GPS) dan SSID WiFi kantor"*

## Deskripsi Requirement PRD
Tab "Lokasi & Keamanan" pada Pengaturan Kehadiran harus mendukung **2 metode validasi lokasi**:
1. **Geofence (GPS Radius)** ✅ — sudah diimplementasi.
2. **WiFi SSID Kantor** ❌ — belum diimplementasi.

Fitur WiFi SSID bertujuan sebagai metode validasi alternatif/tambahan: jika staf terhubung ke WiFi kantor yang terdaftar, sistem mengonfirmasi kehadirannya di lokasi yang benar.

## Kondisi Sistem Saat Ini
- Pengaturan Kehadiran hanya mendukung Geofence (lat/long + radius).
- Terdapat referensi "SSID" di `layoutReducer.js` dan `apps-layout/index.jsx` tapi hanya sebagai mock data/string statis.
- **Tidak ada** backend endpoint untuk menyimpan daftar WiFi SSID per entitas.
- **Tidak ada** validasi SSID di frontend saat clock-in.
- Web Browser API **tidak bisa** membaca nama WiFi SSID yang terhubung (limitasi browser). Fitur ini hanya feasible di **aplikasi native Android**.

## Gap yang Harus Ditutup
1. Tambahkan field "SSID WiFi Kantor" di Pengaturan Kehadiran (UI + Backend storage).
2. Jika di masa depan ada native Android app, integrasikan validasi SSID saat clock-in.
3. Untuk Web App, fitur ini bisa di-skip atau ditampilkan sebagai informasi saja.

## Tingkat Prioritas
🟢 **RENDAH** — Limitasi teknis browser membuat fitur ini hanya relevan untuk Android native app (belum ada di roadmap dekat).
