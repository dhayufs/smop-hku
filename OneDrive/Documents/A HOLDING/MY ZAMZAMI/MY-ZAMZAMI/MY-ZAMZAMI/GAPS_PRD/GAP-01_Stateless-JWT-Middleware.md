# GAP-01: Stateless JWT Middleware (3-Layer Security)

## Referensi PRD
- **PRD 2 — Modul Middleware & Proteksi Isolasi Data**, Bagian 1 & 4
- Halaman: Baris 127–208

## Deskripsi Requirement PRD
PRD menuntut 3 lapisan middleware terpisah yang berjalan secara **stateless** (tanpa query database):
1. **Lapisan 1 — `verify_jwt`**: Validasi signature & expiry token.
2. **Lapisan 2 — `verify_entity_access`**: Cocokkan `entity_id` dari request dengan `allowed_entities` di JWT payload.
3. **Lapisan 3 — `verify_module_permission`**: Cocokkan slug modul dari URL API dengan `allowed_modules` di JWT payload.

PRD juga menegaskan:
> *"Middleware **tidak boleh** melakukan query ke database; semua validasi akses harus bergantung murni pada payload JWT."*

## Kondisi Sistem Saat Ini
- `middleware.js` hanya melakukan 2 hal:
  - **Rate Limiting** (in-memory Map).
  - **Cookie check** (ada/tidaknya `auth_token` → redirect ke login).
- **Tidak ada** lapisan `verify_entity_access` maupun `verify_module_permission` di level middleware.
- Validasi entitas dilakukan **per-route di dalam API handler** (`getAuthUser()`) yang melakukan **query ke database** setiap kali dipanggil — bertentangan langsung dengan prinsip Stateless PRD.
- JWT payload saat ini tidak menyertakan array `allowed_entities` dan `allowed_modules` secara lengkap saat sign-in.

## Gap yang Harus Ditutup
1. Perkaya JWT payload saat login untuk menyertakan `allowed_entities[]` dan `allowed_modules[]`.
2. Buat 3 fungsi middleware terpisah (`verify_jwt`, `verify_entity_access`, `verify_module_permission`) yang berjalan berurutan.
3. Hapus/kurangi query database di `getAuthUser()` agar lebih mendekati prinsip stateless.

## Tingkat Prioritas
🔴 **TINGGI** — Ini adalah fondasi arsitektur keamanan yang disebut PRD sebagai "Zero Trust Architecture".
