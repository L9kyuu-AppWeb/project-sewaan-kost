1. Struktur Tabel: makanan
    Nama Kolom	Tipe Data	Atribut	Deskripsi
    id_makanan	INT	PK, AI	ID unik menu makanan.
    id_kost	INT	FK, Not Null	Relasi ke kost.id_kost (Menu ini tersedia di kost mana).
    nama_makanan	VARCHAR(100)	Not Null	Nama hidangan (contoh: Nasi Goreng Spesial).
    harga	DECIMAL(15,2)	Not Null	Harga per porsi di kost tersebut.
    stok	INT	Default 0	Jumlah porsi yang tersedia hari ini.
    is_available	BOOLEAN	Default TRUE	Switch On/Off (Tampilkan/Sembunyikan menu).
    foto_makanan	VARCHAR(255)	Nullable	Link gambar makanan agar lebih menarik.

2. Hak Akses (Role Permissions)
    Pembagian akses ini memastikan integritas data agar penyewa tidak bisa mengubah harga dan pemilik bisa mengelola bisnisnya dengan efisien.

    A. Role: Pemilik Kost (Admin)
    Pemilik memiliki kendali penuh terhadap suplai dan manajemen menu.
        Create: Menambah menu baru untuk kost miliknya.
        Read: Melihat semua daftar menu dan laporan penjualan makanan.
        Update: * Mengubah harga jika ada kenaikan bahan baku.
            Mengupdate jumlah stok harian.
            Mengubah is_available menjadi FALSE jika menu sudah habis atau dapur tutup.
        Delete: Menghapus menu yang sudah tidak ingin dijual lagi.

    B. Role: Penyewa
    Penyewa hanya memiliki akses pada sisi permintaan (demand).
        Create: Tidak bisa menambah menu. Penyewa hanya bisa membuat Pesanan (di tabel pesanan_makan).
        Read: * Hanya bisa melihat menu yang memiliki id_kost yang sama dengan tempat tinggalnya.
            Hanya bisa melihat menu yang is_available = TRUE dan stok > 0.
        Update: Tidak bisa mengubah data di tabel makanan.
        Delete: Tidak bisa menghapus data di tabel makanan.

untuk menu di role pemilik :
    Kelola ->
        Kamar
        Makanan

untuk menu di role Penyewa :
    Cari ->
        Kost
        Makanan