<?php

namespace Database\Seeders;

use App\Models\CalonSantri;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        CalonSantri::create([
            'nama' => 'Restu Winaldi',
            'kode' => 'A230001',
        ]);
    }
}
