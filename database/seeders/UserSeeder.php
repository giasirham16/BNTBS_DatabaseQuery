<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data user lama untuk menghindari duplikasi
        User::truncate();

        // 🔹 Buat Superadmin
        User::create([
            'username' => 'superadmin1',
            'password' => Hash::make('Password1!'), // Ganti dengan password yang aman
            'role' => 'superadmin',
            'email'=> 'giasirham96@gmail.com',
            'statusApproval' => 2,
            'reasonApproval' => '-'
        ]);

        User::create([
            'username' => 'superadmin2',
            'password' => Hash::make('Password1!'), // Ganti dengan password yang aman
            'role' => 'superadmin',
            'email'=> 'giasirham96@gmail.com',
            'statusApproval' => 2,
            'reasonApproval' => '-'
        ]);
    }
}
