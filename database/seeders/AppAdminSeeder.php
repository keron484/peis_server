<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppAdmin;
use Illuminate\Support\Facades\Hash;

class AppAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AppAdmin::create([
            'name' => 'Chongong keron gemuh',
            'email' => 'chongongprecious@gmail.com',
            'password' =>  Hash::make('Keron484$'),
        ]);

        $this->command->info('AppAdmin table seeded!');
    }
}
