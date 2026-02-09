<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShieldSeeder::class,
        ]);

        if (env('APP_ENV') === 'local') {
            User::create([
                'name'     => 'Admin User',
                'email'    => 'admin@admin.nl',
                'password' => Hash::make('password'),
            ])->assignRole('super_admin');
        }
    }
}
