1. Alur Proses (Workflow)
    Pengaturan Menu (Pemilik): Pemilik kost menentukan daftar harga galon (Isi Ulang 5rb, Mineral Bermerek 18rb, dsb) di dashboard masing-masing kost.

    Pemesanan (Penyewa):
        Penyewa memilih jenis air.
        Wajib: Penyewa mengunggah Foto Galon Kosong yang diletakkan di depan kamar.

    Pembayaran: Penyewa membayar melalui Midtrans (Order ID: GALON-XXX).
    Verifikasi & Pengantaran (Pemilik):
        Setelah status settlement, Pemilik mengambil galon kosong dan mengisinya.
        Saat mengantarkan kembali, Wajib: Pemilik mengunggah Foto Galon Terisi yang sudah ditaruh di depan kamar penyewa sebagai bukti selesai.
    Selesai: Sistem menandai transaksi selesai.

2. Struktur Tabel: galon_katalog
    Tabel ini untuk menyimpan pilihan jenis air per kost.
        Nama Kolom	Tipe Data	Deskripsi
        id_galon_tipe	INT (PK)	ID tipe air.
        id_kost	INT (FK)	Relasi ke kost.id_kost.
        nama_air	VARCHAR	Contoh: "Isi Ulang Standar", "AQUA Asli".
        harga	DECIMAL	5000, 18000, dll.
        is_available	BOOLEAN	Status tersedia/tidak.

3. Struktur Tabel: pesanan_galon
    Tabel transaksi untuk mencatat pemesanan dan bukti foto.
        Nama Kolom	Tipe Data	Deskripsi
        id_order_galon	INT (PK)	ID transaksi galon.
        id_penyewa	INT (FK)	Siapa yang pesan.
        id_kost	INT (FK)	Lokasi kost.
        id_galon_tipe	INT (FK)	Jenis air yang dipilih.
        foto_kosong	VARCHAR	Wajib (Penyewa): URL/Path foto galon kosong.
        foto_terisi	VARCHAR	Wajib (Pemilik): URL/Path foto bukti sudah diantar.
        status_galon	ENUM	'menunggu_bayar', 'diproses', 'diambil', 'selesai'.
        total_bayar	DECIMAL	Harga air.

