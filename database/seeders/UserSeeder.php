<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Chongong keron gemuh',
            'email' => 'chongongprecious@gmail.com',
            'password' =>  Hash::make('Keron484$'),
        ]);

        $this->command->info('User table seeded!');
    }
}
