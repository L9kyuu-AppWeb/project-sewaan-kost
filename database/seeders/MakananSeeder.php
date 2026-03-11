<?php

namespace Database\Seeders;

use App\Models\Makanan;
use App\Models\Kost;
use Illuminate\Database\Seeder;

class MakananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get kosts
        $kost1 = Kost::where('nama_kost', 'Kost Mawar Biru')->first();
        $kost2 = Kost::where('nama_kost', 'Kost Anggrek Indah')->first();

        if ($kost1) {
            // Sample food data for Kost Mawar Biru
            $makananKost1 = [
                [
                    'nama_makanan' => 'Nasi Goreng Spesial',
                    'harga' => 15000,
                    'stok' => 20,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Mie Goreng Jawa',
                    'harga' => 13000,
                    'stok' => 15,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Ayam Bakar Madu',
                    'harga' => 20000,
                    'stok' => 10,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Soto Ayam Lamongan',
                    'harga' => 12000,
                    'stok' => 0,
                    'is_available' => false,
                ],
                [
                    'nama_makanan' => 'Gado-Gado Jakarta',
                    'harga' => 10000,
                    'stok' => 12,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Bakso Urat Sapi',
                    'harga' => 18000,
                    'stok' => 8,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Nasi Rames Komplit',
                    'harga' => 16000,
                    'stok' => 15,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Es Teh Manis',
                    'harga' => 3000,
                    'stok' => 50,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Jus Jeruk Segar',
                    'harga' => 5000,
                    'stok' => 30,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Kopi Hitam',
                    'harga' => 4000,
                    'stok' => 40,
                    'is_available' => true,
                ],
            ];

            foreach ($makananKost1 as $makanan) {
                Makanan::create([
                    'id_kost' => $kost1->id_kost,
                    'nama_makanan' => $makanan['nama_makanan'],
                    'harga' => $makanan['harga'],
                    'stok' => $makanan['stok'],
                    'is_available' => $makanan['is_available'],
                ]);
            }
        }

        if ($kost2) {
            // Sample food data for Kost Anggrek Indah
            $makananKost2 = [
                [
                    'nama_makanan' => 'Nasi Pecel Madiun',
                    'harga' => 11000,
                    'stok' => 18,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Rawon Daging Sapi',
                    'harga' => 22000,
                    'stok' => 10,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Lontong Balap Surabaya',
                    'harga' => 14000,
                    'stok' => 0,
                    'is_available' => false,
                ],
                [
                    'nama_makanan' => 'Tahu Campur Sidoarjo',
                    'harga' => 13000,
                    'stok' => 12,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Sate Ayam Madura',
                    'harga' => 25000,
                    'stok' => 15,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Nasi Bebek Sinjay',
                    'harga' => 20000,
                    'stok' => 8,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Es Campur Buah',
                    'harga' => 8000,
                    'stok' => 25,
                    'is_available' => true,
                ],
                [
                    'nama_makanan' => 'Wedang Jahe',
                    'harga' => 5000,
                    'stok' => 30,
                    'is_available' => true,
                ],
            ];

            foreach ($makananKost2 as $makanan) {
                Makanan::create([
                    'id_kost' => $kost2->id_kost,
                    'nama_makanan' => $makanan['nama_makanan'],
                    'harga' => $makanan['harga'],
                    'stok' => $makanan['stok'],
                    'is_available' => $makanan['is_available'],
                ]);
            }
        }
    }
}
