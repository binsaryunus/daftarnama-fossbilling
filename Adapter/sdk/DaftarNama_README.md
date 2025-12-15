# DaftarNama Registrar Adapter untuk FOSSBilling

## Deskripsi
Adapter domain registrar untuk integrasi FOSSBilling dengan provider DaftarNama.id.

## Fitur Utama
- ✅ Cek ketersediaan domain
- ✅ Registrasi domain baru
- ✅ Transfer domain
- ✅ Perpanjangan domain
- ✅ Modifikasi nameserver
- ✅ Mendapatkan EPP/Auth code
- ✅ Cek domain dapat ditransfer
- ✅ Mendapatkan detail domain

## Konfigurasi

### Parameter Wajib
- **API Key**: API Key dari akun DaftarNama Anda
- **Account Description**: Deskripsi akun untuk identifikasi

### Parameter Opsional
- **API URL**: Custom API URL (default: https://api.daftarnama.id/v1)
- **Test Mode**: 
  - `0` = Live Mode (produksi)
  - `1` = Sandbox Mode (testing)

## Instalasi
1. Copy file `DaftarNama.php` ke folder `library/Registrar/Adapter/`
2. Copy file `DaftarNama.json` ke folder `library/Registrar/Adapter/`
3. Login ke admin FOSSBilling
4. Pergi ke System > Domain Registration
5. Add new registrar dan pilih "DaftarNama"
6. Masukkan API Key dan Account Description

## Endpoint API yang Digunakan
- `/check` - Cek ketersediaan domain
- `/register` - Registrasi domain
- `/renew` - Perpanjangan domain
- `/transfer` - Transfer domain
- `/checktransfer` - Cek domain dapat ditransfer
- `/domaininfo` - Informasi domain
- `/modifyns` - Ubah nameserver
- `/geteppcode` - Dapatkan EPP code

## Mode Testing
Adapter dilengkapi dengan fallback testing mode yang akan:
- Return response sukses jika API call gagal
- Menggunakan data dummy untuk testing
- Mencatat error di log tanpa mengganggu workflow

## Dukungan TLD
Mendukung TLD populer Indonesia dan internasional:
- .com, .net, .org, .info, .biz
- .id, .co.id, .web.id, .ac.id, .sch.id
- .my.id, .ponpes.id, .desa.id
- Dan lainnya sesuai dengan DaftarNama

## Error Handling
- SSL certificate validation dapat dinonaktifkan untuk development
- Dual fallback dengan CURL dan file_get_contents
- Response timeout 30 detik
- Graceful degradation ke test mode jika API tidak tersedia

## Troubleshooting

### SSL Certificate Issues
Jika ada masalah SSL, adapter otomatis disable SSL verification.

### Connection Issues  
Adapter akan mencoba CURL terlebih dahulu, jika gagal akan fallback ke file_get_contents.

### API Response Issues
Adapter dapat menangani berbagai format response dari DaftarNama API.

## Support
Untuk support teknis atau bug report, silakan hubungi developer.

## Changelog
- v1.0 - Initial release dengan semua fitur dasar
- v1.1 - Added real API integration
- v1.2 - Enhanced error handling dan fallback system