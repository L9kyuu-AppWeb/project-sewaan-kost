<?php

namespace Database\Seeders;

use App\Models\Kost;
use App\Models\KostSetting;
use Illuminate\Database\Seeder;

class KostSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all kosts
        $kosts = Kost::all();

        foreach ($kosts as $kost) {
            // Create settings with all features disabled by default
            KostSetting::firstOrCreate(
                ['id_kost' => $kost->id_kost],
                [
                    'enable_makanan' => false,
                    'enable_galon' => false,
                    'enable_laundry' => false,
                ]
            );
        }

        $this->command->info('Kost settings created successfully for ' . $kosts->count() . ' kost(s).');
    }
}
