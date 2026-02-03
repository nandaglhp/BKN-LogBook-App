<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('visitors')->insert([
            'nik'        => '001',
            'name'       => 'Visitor 001',
            'phone'      => '001',
            'pfp_path'   => 'v-001.png',
            'ktp_path'   => 'ktp-001.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
