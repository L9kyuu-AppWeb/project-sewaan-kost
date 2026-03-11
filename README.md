# 🏠 Sewa An Kost

**Sistem Manajemen Sewa Kost Terpadu**

Platform modern untuk mengelola properti kost dengan fitur lengkap: sewa kamar, pesanan makanan, galon, dan laundry dalam satu sistem terintegrasi.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Midtrans](https://img.shields.io/badge/Payment-Midtrans-00AED6?style=for-the-badge&logo=midtrans&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Pengguna & Role](#-pengguna--role)
- [Fitur Detail](#-fitur-detail)
- [Struktur Database](#-struktur-database)
- [Screenshot](#-screenshot)
- [Kontribusi](#-kontribusi)
- [License](#-license)

---

## ✨ Fitur Utama

### 🛏️ **Sewa Kamar Kost**
- Pencarian kost berbasis lokasi dengan filter lengkap
- Booking kamar dengan integrasi pembayaran Midtrans
- Manajemen kontrak dan status pembayaran
- Tracking okupansi real-time
- Auto-update status kamar (tersedia/dipesan/terisi)

### 🍽️ **Pesanan Makanan**
- **Shopping cart system** - Multiple items dalam satu pesanan
- Katalog menu per kost dengan foto dan stok
- Pembayaran terintegrasi untuk semua item sekaligus
- Tracking status: menunggu_bayar → diproses → dikirim → selesai
- Riwayat pesanan lengkap

### 💧 **Pesanan Galon**
- Upload foto galon kosong sebagai bukti penjemputan
- Katalog jenis air (AQUA, VIT, CLUB, dll) dengan harga per kost
- Upload foto galon terisi sebagai bukti pengantaran
- Tracking status: menunggu_jemput → menunggu_bayar → sedang_diisi → siap_antar → selesai
- Audit keterlambatan pengantaran

### 👕 **Laundry**
- Input berat pakaian dengan kalkulasi harga otomatis
- Estimasi waktu selesai yang wajib diisi owner
- Upload foto sebelum dan sesudah cuci
- Tracking status: menunggu_jemput → menunggu_bayar → sedang_dicuci → siap_antar → selesai
- **Audit keterlambatan** - Sistem otomatis deteksi jika terlambat dari estimasi

### 📊 **Dashboard Pemilik Kost**
- Statistik pendapatan terpisah per layanan (Kamar, Makanan, Galon, Laundry)
- Ringkasan pesanan dengan filter status
- Manajemen fitur per kost (enable/disable layanan)
- Verifikasi pembayaran terpusat
- Laporan keuangan bulanan dan tahunan

### 💳 **Pembayaran Digital (Midtrans)**
- **Multiple payment methods**: Transfer Bank, E-Wallet (GoPay, OVO, Dana), QRIS, Alfamart/Indomaret
- Auto-verifikasi pembayaran via callback
- Order ID unik per tipe: `KAMAR-{id}-{ts}`, `MAKANAN-{id}-{ts}`, `GALON-{id}-{ts}`, `LAUNDRY-{id}-{ts}`
- Tracking status pembayaran real-time
- Snap token untuk pembayaran yang aman

---

## 🚀 Teknologi yang Digunakan

| Teknologi | Versi | Deskripsi |
|-----------|-------|-----------|
| **Laravel** | 11.x | PHP Framework |
| **PHP** | 8.2+ | Backend Language |
| **MySQL** | 8.0+ | Database |
| **Midtrans** | Latest | Payment Gateway |
| **Bootstrap** | 5.x | CSS Framework |
| **Blade** | Latest | Template Engine |

---

## 📦 Instalasi

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (optional, untuk asset compilation)

### Langkah Instalasi

1. **Clone repository**
```bash
git clone https://github.com/yourusername/sewaan-kost.git
cd sewaan-kost
```

2. **Install dependencies**
```bash
composer install
npm install  # Optional
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database** (edit file `.env`)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-sewaan-kost
DB_USERNAME=root
DB_PASSWORD=
```

5. **Konfigurasi Midtrans** (edit file `.env`)
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SKIP_SSL_VERIFICATION=true
```

6. **Run migrations & seeders**
```bash
php artisan migrate --seed
```

7. **Create storage link**
```bash
php artisan storage:link
```

8. **Run development server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Default User Accounts (Setelah Seed)

**Pemilik Kost:**
- Email: `pemilik@example.com`
- Password: `password123`

**Penyewa:**
- Email: `penyewa@example.com`
- Password: `password123`

### Midtrans Configuration

Untuk mode development, gunakan sandbox Midtrans:
- Sandbox URL: `https://app.sandbox.midtrans.com`
- Production URL: `https://app.midtrans.com`

Dapatkan API keys dari [Midtrans Dashboard](https://dashboard.midtrans.com)

---

## 👥 Pengguna & Role

### 🔑 **Pemilik Kost**
- Manage properti kost (CRUD)
- Manage kamar (CRUD)
- **Manage katalog makanan** (CRUD)
- **Manage katalog galon** (CRUD)
- **Manage katalog laundry** (CRUD)
- Verifikasi & tracking pesanan
- Lihat dashboard pendapatan
- **Enable/disable fitur per kost**

### 🔑 **Penyewa**
- Cari dan pesan kamar kost
- **Pesan makanan** (multiple items)
- **Pesan galon** (dengan upload foto)
- **Pesan laundry** (dengan tracking berat)
- Lihat riwayat pesanan
- Pembayaran via Midtrans
- Tracking status pesanan

---

## 📖 Fitur Detail

### 1. Sistem Pembayaran Terintegrasi

Semua pembayaran menggunakan **Midtrans** dengan order ID unik:

| Tipe | Format Order ID | Contoh |
|------|----------------|---------|
| Kamar | `KAMAR-{id_pesan}-{timestamp}M` | `KAMAR-1-1773182971M` |
| Makanan | `MAKANAN-{id_order}-{timestamp}` | `MAKANAN-5-1773182972` |
| Galon | `GALON-{id_order}-{timestamp}` | `GALON-3-1773182973` |
| Laundry | `LAUNDRY-{id_order}-{timestamp}` | `LAUNDRY-2-1773182971` |

### 2. Feature Toggle System

Pemilik kost dapat **enable/disable** fitur per kost:
- Fitur Makanan
- Fitur Galon
- Fitur Laundry

**Default: Semua fitur DISABLED**

Jika fitur disabled:
- Menu tidak ditampilkan di navigation penyewa
- Akses URL langsung akan redirect dengan error message
- Dashboard hanya menampilkan fitur yang enabled

### 3. Audit & Tracking

**Laundry Keterlambatan:**
- Owner wajib isi `tgl_selesai_estimasi` setelah pembayaran
- Sistem auto-record `tgl_selesai_aktual` saat upload foto selesai
- Visual indicator jika terlambat (red highlight)
- Dashboard menampilkan jumlah pesanan terlambat

**Bukti Foto:**
- Galon: Foto galon kosong + Foto galon terisi
- Laundry: Foto pakaian sebelum + sesudah cuci

---

## 🗄️ Struktur Database

### Tables Utama

| Table | Deskripsi |
|-------|-----------|
| `users` | Data pengguna (pemilik & penyewa) |
| `kost` | Data properti kost |
| `kamar` | Data kamar per kost |
| `pesan` | Pemesanan kamar |
| `pembayarans` | Pembayaran (all types) |
| `makanan` | Katalog menu makanan |
| `pesanan_makanan_header` | Header pesanan makanan (multi-item) |
| `pesanan_makanan_detail` | Detail item pesanan makanan |
| `galon_katalog` | Katalog jenis air galon |
| `pesanan_galon` | Pesanan galon |
| `laundry_katalog` | Katalog layanan laundry |
| `pesanan_laundry` | Pesanan laundry |
| `kost_settings` | Feature toggle per kost |

### Feature Toggle Schema

```sql
CREATE TABLE kost_settings (
    id_setting BIGINT PRIMARY KEY,
    id_kost BIGINT UNIQUE,
    enable_makanan BOOLEAN DEFAULT FALSE,
    enable_galon BOOLEAN DEFAULT FALSE,
    enable_laundry BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_kost) REFERENCES kost(id_kost) ON DELETE CASCADE
);
```

---

## 📸 Screenshot

### Homepage
![Homepage](./public/screenshots/homepage.png)

### Dashboard Pemilik
![Dashboard Pemilik](./public/screenshots/dashboard-pemilik.png)

### Pesanan Makanan (Cart System)
![Pesanan Makanan](./public/screenshots/pesanan-makanan.png)

### Pesanan Galon
![Pesanan Galon](./public/screenshots/pesanan-galon.png)

### Pesanan Laundry
![Pesanan Laundry](./public/screenshots/pesanan-laundry.png)

### Feature Toggle Settings
![Feature Settings](./public/screenshots/kost-settings.png)

---

## 🤝 Kontribusi

Terima kasih telah mempertimbangkan untuk berkontribusi! Silakan:

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

---

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 📞 Support

Jika ada pertanyaan atau masalah:
- 📧 Email: support@sewaan-kost.com
- 📚 Dokumentasi: [Wiki](https://github.com/yourusername/sewaan-kost/wiki)
- 🐛 Bug Report: [GitHub Issues](https://github.com/yourusername/sewaan-kost/issues)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - PHP Framework
- [Midtrans](https://midtrans.com) - Payment Gateway
- [Bootstrap](https://getbootstrap.com) - CSS Framework
- [Font Awesome](https://fontawesome.com) - Icons

---

<p align="center">
  Made with ❤️ by <strong>Sewa An Kost Team</strong>
</p>
