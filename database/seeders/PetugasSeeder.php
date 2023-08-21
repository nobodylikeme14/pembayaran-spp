<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petugas;
use Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Petugas::updateOrCreate(
            ['email' => 'malfauzy99@gmail.com'],
            [
                'nama' => 'Admin Fauzy',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'privilege' => "Administrator"
            ]
        );
    }
}
