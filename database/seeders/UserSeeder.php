<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Teachers
            ['John Smith', 'john.smith@gmail.com', 'teacher'],

            // Students
            ['Alice Brown', 'alice.brown@gmail.com', 'student'],
            ['Bob Davis', 'bob.davis@gmail.com', 'student'],
            ['Carol Wilson', 'carol.wilson@example.com', 'student'],
            ['David Miller', 'david.miller@example.com', 'student'],
            ['Emma Garcia', 'emma.garcia@example.com', 'student'],
        ];

        foreach ($users as $u) {
            User::create([
                'name' => $u[0],
                'email' => $u[1],
                'password' => Hash::make('password123'),
                'role' => $u[2],
            ]);
        }

        $this->command->info("Users seeded successfully!");
    }
}