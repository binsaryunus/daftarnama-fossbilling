# DaftarNama Registrar & TLD Sync for FOSSBilling

Extensi registrar DaftarNama untuk FOSSBilling, dibuat oleh apiku.id dengan bantuan AI, untuk import & sinkronisasi harga TLD dan operasi domain DaftarNama.

## Fitur
- Registrar DaftarNama (registrasi, transfer, renew, nameserver, EPP, dll).
- Import/sync harga TLD via UI modul (preview, pilih TLD, apply selected).
- Konversi harga ke currency default FOSSBilling.
- CLI opsional untuk import/sync.
- Validasi server-side: blokir SLD < 5 karakter untuk semua turunan `.id`.

## Struktur
- `library/Registrar/Adapter/DaftarNama.php` + `DaftarNama.json` (adapter).
- `library/Registrar/Adapter/sdk/` (SDK + helper DaftarNamaTldImporter).
- `modules/Daftarnamasync/` (mod UI, API, hook validasi, ikon).
- `daftarnama_tld_import.php` (CLI opsional).

## Instalasi
1. Salin file sesuai struktur ke root FOSSBilling.
2. Hapus cache autoload: `rm -f data/cache/classMap.php`.
3. Reload admin.
4. Konfigurasi registrar DaftarNama (API key, test mode) di System → Domain Registration → Registrars.

## Penggunaan
- UI sync: Admin → Extensions → DaftarnamaSync (settings) → Load Preview → centang TLD → Apply Selected.
- CLI: `php daftarnama_tld_import.php --api-key=KEY --auto --source-currency=IDR --dry-run --no-update`.
- Validasi .id: hook modul menolak order `.id` (dan turunannya) jika SLD < 5 karakter, berlaku untuk UI/API.

## Changelog
- v1.0.0
  - Adapter DaftarNama + SDK helper.
  - Modul Daftarnamasync (UI preview + select + sync; hook validasi .id).
  - CLI import/sync opsional.

## Lisensi
MIT License © 2025 apiku.id
