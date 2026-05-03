<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('pass');

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'username' => 'alex',
                'email_verified_at' => now(),
                'password' => $password,
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
