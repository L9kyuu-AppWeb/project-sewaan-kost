<?php

namespace Database\Seeders;

use App\Models\LaundryKatalog;
use App\Models\Kost;
use Illuminate\Database\Seeder;

class LaundrySeeder extends Seeder
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
            // Sample laundry services for Kost Mawar Biru
            $laundryTypes1 = [
                [
                    'nama_layanan' => 'Cuci Kering Lipat',
                    'harga_per_kg' => 8000,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Cuci Setrika',
                    'harga_per_kg' => 10000,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Setrika Saja',
                    'harga_per_kg' => 5000,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Cuci Komplit (Express 2 Jam)',
                    'harga_per_kg' => 15000,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Cuci Bedcover/Selimut',
                    'harga_per_kg' => 12000,
                    'is_available' => true,
                ],
            ];

            foreach ($laundryTypes1 as $type) {
                LaundryKatalog::create([
                    'id_kost' => $kost1->id_kost,
                    'nama_layanan' => $type['nama_layanan'],
                    'harga_per_kg' => $type['harga_per_kg'],
                    'is_available' => $type['is_available'],
                ]);
            }
        }

        if ($kost2) {
            // Sample laundry services for Kost Anggrek Indah
            $laundryTypes2 = [
                [
                    'nama_layanan' => 'Cuci Kering Lipat',
                    'harga_per_kg' => 7000,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Cuci Setrika',
                    'harga_per_kg' => 9000,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Setrika Saja',
                    'harga_per_kg' => 4500,
                    'is_available' => true,
                ],
                [
                    'nama_layanan' => 'Cuci Komplit Express',
                    'harga_per_kg' => 14000,
                    'is_available' => false,
                ],
                [
                    'nama_layanan' => 'Cuci Sepatu',
                    'harga_per_kg' => 25000,
                    'is_available' => true,
                ],
            ];

            foreach ($laundryTypes2 as $type) {
                LaundryKatalog::create([
                    'id_kost' => $kost2->id_kost,
                    'nama_layanan' => $type['nama_layanan'],
                    'harga_per_kg' => $type['harga_per_kg'],
                    'is_available' => $type['is_available'],
                ]);
            }
        }
    }
}
