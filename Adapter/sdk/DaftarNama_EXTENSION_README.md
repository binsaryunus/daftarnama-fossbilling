# DaftarNama Registrar & TLD Sync (Drop-in for FOSSBilling)

Paket ringan untuk import & sinkronisasi harga TLD DaftarNama ke FOSSBilling tanpa patch core.

## Isi Paket
- `library/Registrar/Adapter/DaftarNama.php` dan `DaftarNama.json` (adapter registrar)
- `library/Registrar/Adapter/sdk/helpers/DaftarNamaTldImporter.php` (helper import/sync)
- Modul UI sync: `modules/DaftarnamaSync` (controller, API, halaman admin).
- `daftarnama_tld_import.php` (CLI opsional)

## Requirement
- FOSSBilling (preview/main)
- PHP 8.1+ dengan cURL & OpenSSL
- API Key DaftarNama (production atau sandbox)

## Instalasi (drop-in)
1. Backup instalasi Anda.
2. Salin/overwrite file paket ini ke root FOSSBilling sesuai struktur.
3. Hapus cache autoload: `rm -f data/cache/classMap.php`.
4. Reload halaman admin FOSSBilling.

## Konfigurasi Registrar
1. Admin → System → Domain Registration → Registrars.
2. Pilih/klik **DaftarNama**.
3. Isi `API Key`, `API URL` (jika custom), `Test Mode` sesuai kebutuhan. Simpan.

## Sync via UI (di modul DaftarnamaSync)
- Admin → Extensions → DaftarNama Sync → isi opsi (source currency, update existing, dry-run) → Run Import & Sync.

## Sync via CLI (opsional)
```
php daftarnama_tld_import.php --api-key=APIKEY --auto \
  --source-currency=IDR --no-update --dry-run
```
- `--auto` menulis ke DB; hilangkan `--no-update` untuk memperbarui TLD yang ada; gunakan `--dry-run` untuk cek dulu.

## Perilaku Update
- `update existing = Yes`: harga TLD yang sudah ada diperbarui.
- `update existing = No`: hanya menambah TLD baru.
- `dry run = Yes`: tidak menulis DB.
- Harga dikonversi ke currency default FOSSBilling jika berbeda dari currency sumber API.

## Troubleshooting
- Error autoload: hapus `data/cache/classMap.php`, reload admin.
- Harga 0: pastikan API mengembalikan harga non-premium dan currency benar; jalankan ulang sync tanpa dry-run.
- Registrar tidak muncul: pastikan file adapter tersalin dan cache dibersihkan.

## Changelog
- v1.0: Rilis awal (adapter DaftarNama + helper di SDK + modul UI sync + CLI opsional).
