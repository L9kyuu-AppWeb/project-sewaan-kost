saya ingin update modul pembayaran :
    id_bayar	INT	PK, AI	ID unik internal sistem.
    id_pesan	INT	FK, Not Null	Relasi ke pemesanan.id_pesan.
    order_id	VARCHAR(50)	Unique	ID pesanan yang dikirim ke Midtrans (biasanya gabungan ID Pesan + Timestamp).
    transaction_id	VARCHAR(100)	Nullable	ID Transaksi resmi dari pihak Midtrans.
    gross_amount	DECIMAL(15,2)	Not Null	Nominal pembayaran.
    payment_type	VARCHAR(50)	Nullable	Otomatis terisi (e-wallet, bank_transfer, dll).
    transaction_status	VARCHAR(20)	Not Null	Status dari Midtrans: pending, settlement, expire, cancel.
    snap_token	VARCHAR(255)	Nullable	Token untuk memunculkan pop-up pembayaran Midtrans.
    updated_at	TIMESTAMP	DEFAULT NOW()	Waktu terakhir status berubah.

Logika Integrasi Midtrans

    Checkout: Saat penyewa klik "Bayar", sistem Anda memanggil API Midtrans untuk mendapatkan snap_token. Simpan token ini di tabel.

    Payment: Penyewa membayar melalui UI Midtrans (GoPay, VA Bank, dll).

    Notification (Webhook): Midtrans akan mengirimkan data JSON ke URL server Anda (Notification URL).

        Jika status settlement, sistem Anda otomatis mengubah transaction_status di tabel ini dan mengubah status_pesan di tabel pemesanan menjadi 'aktif'.

        Jika status expire atau cancel, sistem otomatis membuka kembali status kamar menjadi 'tersedia'.
