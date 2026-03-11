<?php

namespace Database\Seeders;

use App\Models\GalonKatalog;
use App\Models\Kost;
use Illuminate\Database\Seeder;

class GalonSeeder extends Seeder
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
            // Sample galon types for Kost Mawar Biru
            $galonTypes1 = [
                [
                    'nama_air' => 'Isi Ulang Standar',
                    'harga' => 5000,
                    'is_available' => true,
                ],
                [
                    'nama_air' => 'AQUA 19L',
                    'harga' => 18000,
                    'is_available' => true,
                ],
                [
                    'nama_air' => 'VIT 19L',
                    'harga' => 17000,
                    'is_available' => true,
                ],
                [
                    'nama_air' => 'CLUB 19L',
                    'harga' => 16000,
                    'is_available' => true,
                ],
                [
                    'nama_air' => 'LE MINERALE 19L',
                    'harga' => 20000,
                    'is_available' => true,
                ],
            ];

            foreach ($galonTypes1 as $type) {
                GalonKatalog::create([
                    'id_kost' => $kost1->id_kost,
                    'nama_air' => $type['nama_air'],
                    'harga' => $type['harga'],
                    'is_available' => $type['is_available'],
                ]);
            }
        }

        if ($kost2) {
            // Sample galon types for Kost Anggrek Indah
            $galonTypes2 = [
                [
                    'nama_air' => 'Isi Ulang Biasa',
                    'harga' => 5000,
                    'is_available' => true,
                ],
                [
                    'nama_air' => 'AQUA 19L',
                    'harga' => 18000,
                    'is_available' => true,
                ],
                [
                    'nama_air' => 'VIT 19L',
                    'harga' => 17000,
                    'is_available' => false,
                ],
                [
                    'nama_air' => 'AMDIS 19L',
                    'harga' => 17000,
                    'is_available' => true,
                ],
            ];

            foreach ($galonTypes2 as $type) {
                GalonKatalog::create([
                    'id_kost' => $kost2->id_kost,
                    'nama_air' => $type['nama_air'],
                    'harga' => $type['harga'],
                    'is_available' => $type['is_available'],
                ]);
            }
        }
    }
}
