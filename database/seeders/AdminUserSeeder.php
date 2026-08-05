<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // غيّر الإيميل والباسورد دي فورًا بعد أول تسجيل دخول
        User::updateOrCreate(
            ['email' => 'admin@poyekhali.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('ChangeMe123!'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
