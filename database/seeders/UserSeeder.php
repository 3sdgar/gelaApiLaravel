<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Edgar',
            'email' => 'edgar@gmail.com',
            'password' => Hash::make('12345'), // Laravel encriptará esta contraseña
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
