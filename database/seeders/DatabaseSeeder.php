<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Superadmin
        User::create([
            'name' => 'Kepala Labor',
            'email' => 'superadmin@lab.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        // Create Staff (PJ Lab)
        User::create([
            'name' => 'PJ Lab',
            'email' => 'staff@lab.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        // Create Regular User (Dosen/Mahasiswa)
        User::create([
            'name' => 'Mahasiswa',
            'email' => 'user@lab.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Create Labs
        $lab1 = \App\Models\Lab::create([
            'name' => 'Lab Komputer 1',
            'capacity' => 30,
            'location' => 'Gedung A Lantai 2',
        ]);

        $lab2 = \App\Models\Lab::create([
            'name' => 'Lab Jaringan',
            'capacity' => 25,
            'location' => 'Gedung B Lantai 3',
        ]);

        // Create Sample Assets
        \App\Models\Asset::create([
            'lab_id' => $lab1->id,
            'code' => 'PC-001',
            'name' => 'Computer Desktop',
            'spec' => 'Intel Core i5, 8GB RAM, 512GB SSD',
            'condition' => 'good',
        ]);

        \App\Models\Asset::create([
            'lab_id' => $lab1->id,
            'code' => 'PC-002',
            'name' => 'Computer Desktop',
            'spec' => 'Intel Core i5, 8GB RAM, 512GB SSD',
            'condition' => 'good',
        ]);

        \App\Models\Asset::create([
            'lab_id' => $lab2->id,
            'code' => 'RTR-001',
            'name' => 'Cisco Router',
            'spec' => 'Cisco 2911 Series',
            'condition' => 'good',
        ]);

        \App\Models\Asset::create([
            'lab_id' => $lab2->id,
            'code' => 'SW-001',
            'name' => 'Cisco Switch',
            'spec' => 'Catalyst 2960',
            'condition' => 'bad',
        ]);
    }
}
