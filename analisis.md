CREATE TABLE laundry_katalog (
    id_laundry_tipe INT AUTO_INCREMENT PRIMARY KEY,
    id_kost INT NOT NULL,
    nama_layanan VARCHAR(100) NOT NULL,
    harga_per_kg DECIMAL(15, 2) NOT NULL,
    CONSTRAINT fk_laundry_kost FOREIGN KEY (id_kost) REFERENCES kost (id_kost) ON DELETE CASCADE
);

CREATE TABLE pesanan_laundry (
    id_order_laundry INT AUTO_INCREMENT PRIMARY KEY,
    id_penyewa INT NOT NULL,
    id_kost INT NOT NULL,
    id_laundry_tipe INT NOT NULL,
    berat_kg DECIMAL(5, 2),
    total_harga DECIMAL(15, 2),
    foto_awal VARCHAR(255),
    tgl_selesai_estimasi DATE, -- Diisi manual oleh pemilik setelah bayar
    foto_selesai VARCHAR(255), -- Diisi saat selesai
    tgl_selesai_aktual DATETIME, -- Terisi otomatis saat foto_selesai diupload
    status_laundry ENUM('menunggu_jemput', 'menunggu_bayar', 'sedang_dicuci', 'siap_antar', 'selesai') DEFAULT 'menunggu_jemput',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_laundry_user FOREIGN KEY (id_penyewa) REFERENCES users(id_user),
    CONSTRAINT fk_laundry_kost_id FOREIGN KEY (id_kost) REFERENCES kost(id_kost),
    CONSTRAINT fk_laundry_tipe_id FOREIGN KEY (id_laundry_tipe) REFERENCES laundry_katalog(id_laundry_tipe)
);

Alur Proses Laundry (Revisi)
    1.Pengajuan (Penyewa):
        Penyewa memilih layanan (Cuci Kering, Cuci Lipat, Setrika, dll).
        Wajib: Penyewa mengunggah Foto Pakaian (untuk bukti jumlah/kondisi awal).
        Status: menunggu_jemput.

    2.Penimbangan (Pemilik): 
        Pemilik jemput baju -> Input Berat (Kg) -> Status: menunggu_pembayaran.

    3.Pembayaran (Penyewa): 
        Penyewa bayar via Midtrans.

    4.Update Komitmen (Pemilik) - [WAJIB]:
        Begitu status pembayaran settlement, Pemilik wajib mengisi tgl_selesai_estimasi di sistem.
        Status berubah menjadi: sedang_dicuci.

    5.Penyelesaian & Foto (Pemilik):
        Setelah cucian rapi, Pemilik mengunggah foto_selesai.
        Sistem mencatat tgl_selesai_aktual (kapan foto diunggah).
        Jika tgl_selesai_aktual > tgl_selesai_estimasi, sistem bisa menandai sebagai "Terlambat".
        Status berubah menjadi: siap_antar.

    6.Konfirmasi (Penyewa): Penyewa klik "Selesai" setelah baju diterima.
    
Logika Bisnis & Validasi di Sistem
    Validasi Tombol Update: Pemilik tidak bisa mengunggah foto_selesai sebelum mengisi tgl_selesai_estimasi. Ini memaksa pemilik memberikan kepastian waktu kepada penyewa.

    Audit Keterlambatan:
    Di dashboard Admin/Pemilik, Anda bisa menampilkan indikator:
    SQL

    -- Logika sederhana cek terlambat
    IF (tgl_selesai_aktual > tgl_selesai_estimasi) THEN "TERLAMBAT"

    Transparansi: Penyewa bisa melihat di aplikasinya: "Estimasi selesai: 14 Maret. Status: Sedang Dicuci". Jika pada tanggal 14 pemilik belum upload foto, penyewa punya dasar kuat untuk komplain.