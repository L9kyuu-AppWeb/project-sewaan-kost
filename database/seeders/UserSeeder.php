<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Pemilik
        $pemilik1 = User::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'pemilik@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567890',
            'role' => 'pemilik',
            'nik' => null,
            'alamat_asal' => 'Jakarta, Indonesia',
        ]);

        // User Penyewa
        User::create([
            'nama_lengkap' => 'Andi Pratama',
            'email' => 'penyewa@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567891',
            'role' => 'penyewa',
            'nik' => '3171234567890001',
            'alamat_asal' => 'Bandung, Jawa Barat',
        ]);

        // Additional Pemilik
        $pemilik2 = User::create([
            'nama_lengkap' => 'Siti Nurhaliza',
            'email' => 'pemilik2@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567892',
            'role' => 'pemilik',
            'nik' => null,
            'alamat_asal' => 'Surabaya, Jawa Timur',
        ]);

        // Additional Penyewa
        User::create([
            'nama_lengkap' => 'Dewi Lestari',
            'email' => 'penyewa2@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567893',
            'role' => 'penyewa',
            'nik' => '3171234567890002',
            'alamat_asal' => 'Yogyakarta, DIY',
        ]);

        // Seed Kost data for pemilik 1
        $kost1 = Kost::create([
            'id_pemilik' => $pemilik1->id_user,
            'nama_kost' => 'Kost Mawar Biru',
            'alamat' => 'Jl. Mawar No. 123, Jakarta Selatan',
            'deskripsi' => 'Kost nyaman dengan lingkungan asri dan tenang, cocok untuk mahasiswa dan pekerja.',
            'fasilitas_umum' => 'WiFi, Parkir Motor, Kamar Mandi Dalam, AC, Lemari',
            'peraturan' => 'Jam malam 22:00 WIB, Dilarang merokok di dalam kamar, Tidak boleh membawa lawan jenis ke kamar.',
            'latitude' => -6.26154890,
            'longitude' => 106.81062350,
        ]);

        // Seed Kost data for pemilik 2
        $kost2 = Kost::create([
            'id_pemilik' => $pemilik2->id_user,
            'nama_kost' => 'Kost Anggrek Indah',
            'alamat' => 'Jl. Anggrek No. 45, Surabaya Pusat',
            'deskripsi' => 'Kost strategis dekat dengan kampus dan pusat perbelanjaan.',
            'fasilitas_umum' => 'WiFi, Parkir, Dapur Bersama, Mesin Cuci, CCTV 24 Jam',
            'peraturan' => 'Jam malam 23:00 WIB, Dilarang membuat kegaduhan, Wajib menjaga kebersihan.',
            'latitude' => -7.25753890,
            'longitude' => 112.75210200,
        ]);

        // Seed Kamar data for Kost 1 - all available initially
        Kamar::create([
            'id_kost' => $kost1->id_kost,
            'nomor_kamar' => 'A-01',
            'lantai' => 1,
            'harga_per_bulan' => 1500000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '3x4 meter',
            'fasilitas_kamar' => 'AC, Kamar mandi dalam, Kasur, Lemari',
        ]);

        Kamar::create([
            'id_kost' => $kost1->id_kost,
            'nomor_kamar' => 'A-02',
            'lantai' => 1,
            'harga_per_bulan' => 1500000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '3x4 meter',
            'fasilitas_kamar' => 'AC, Kamar mandi dalam, Kasur, Lemari',
        ]);

        Kamar::create([
            'id_kost' => $kost1->id_kost,
            'nomor_kamar' => 'B-01',
            'lantai' => 2,
            'harga_per_bulan' => 1800000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '4x4 meter',
            'fasilitas_kamar' => 'AC, Kamar mandi dalam, Kasur, Lemari, Meja belajar',
        ]);

        Kamar::create([
            'id_kost' => $kost1->id_kost,
            'nomor_kamar' => 'B-02',
            'lantai' => 2,
            'harga_per_bulan' => 2000000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '4x5 meter',
            'fasilitas_kamar' => 'AC, Kamar mandi dalam, Kasur, Lemari, Meja belajar, Balkon',
        ]);

        // Seed Kamar data for Kost 2 - all available initially
        Kamar::create([
            'id_kost' => $kost2->id_kost,
            'nomor_kamar' => '101',
            'lantai' => 1,
            'harga_per_bulan' => 1200000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '3x3 meter',
            'fasilitas_kamar' => 'Kipas angin, Kamar mandi luar, Kasur',
        ]);

        Kamar::create([
            'id_kost' => $kost2->id_kost,
            'nomor_kamar' => '102',
            'lantai' => 1,
            'harga_per_bulan' => 1200000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '3x3 meter',
            'fasilitas_kamar' => 'Kipas angin, Kamar mandi luar, Kasur',
        ]);

        Kamar::create([
            'id_kost' => $kost2->id_kost,
            'nomor_kamar' => '201',
            'lantai' => 2,
            'harga_per_bulan' => 1400000,
            'status_kamar' => 'tersedia',
            'ukuran_kamar' => '3x4 meter',
            'fasilitas_kamar' => 'AC, Kamar mandi dalam, Kasur, Lemari',
        ]);
    }
}
