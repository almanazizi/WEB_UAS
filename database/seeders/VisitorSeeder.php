<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use App\Models\User;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = User::where('role', 'staff')->first();
        $approver = $staff ? $staff->id : 1;

        // Create some visitors with different statuses and dates
        $visitors = [
            // Pending visitors (today)
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@example.com',
                'phone' => '08123456789',
                'purpose' => 'Konsultasi project website',
                'person_to_meet' => 'Lab Manager',
                'photo' => 'dummy/visitor1.jpg',
                'status' => 'pending',
                'created_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'phone' => '08234567890',
                'purpose' => 'Meeting untuk kolaborasi riset',
                'person_to_meet' => 'Staff IT',
                'photo' => 'dummy/visitor2.jpg',
                'status' => 'pending',
                'created_at' => now()->subMinutes(15),
            ],
            
            // Active visitors (checked in today)
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '08345678901',
                'purpose' => 'Workshop pemrograman',
                'person_to_meet' => 'Koordinator Lab',
                'photo' => 'dummy/visitor3.jpg',
                'status' => 'active',
                'check_in_time' => now()->subHours(2),
                'approved_by' => $approver,
                'approved_at' => now()->subHours(2),
                'created_at' => now()->subHours(2)->subMinutes(10),
            ],
            [
                'name' => 'Dewi Lestari',
                'phone' => '08456789012',
                'purpose' => 'Troubleshooting sistem',
                'person_to_meet' => 'Teknisi',
                'photo' => 'dummy/visitor4.jpg',
                'status' => 'active',
                'check_in_time' => now()->subHour(),
                'approved_by' => $approver,
                'approved_at' => now()->subHour(),
                'created_at' => now()->subHour()->subMinutes(5),
            ],
            
            // Completed visitors (checked out today)
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko@example.com',
                'phone' => '08567890123',
                'purpose' => 'Pengambilan data penelitian',
                'person_to_meet' => 'Kepala Lab',
                'photo' => 'dummy/visitor5.jpg',
                'status' => 'completed',
                'check_in_time' => now()->subHours(4),
                'check_out_time' => now()->subHours(2),
                'approved_by' => $approver,
                'approved_at' => now()->subHours(4),
                'created_at' => now()->subHours(4)->subMinutes(10),
            ],
            
            // Yesterday's visitors
            [
                'name' => 'Fitri Handayani',
                'phone' => '08678901234',
                'purpose' => 'Konsultasi tugas akhir',
                'person_to_meet' => 'Dosen Pembimbing',
                'photo' => 'dummy/visitor6.jpg',
                'status' => 'completed',
                'check_in_time' => now()->subDay()->hour(10),
                'check_out_time' => now()->subDay()->hour(12),
                'approved_by' => $approver,
                'approved_at' => now()->subDay()->hour(10),
                'created_at' => now()->subDay()->hour(9)->subMinutes(20),
            ],
            [
                'name' => 'Gunawan Wijaya',
                'phone' => '08789012345',
                'purpose' => 'Instalasi software lab',
                'person_to_meet' => 'Staff Teknis',
                'photo' => 'dummy/visitor7.jpg',
                'status' => 'completed',
                'check_in_time' => now()->subDay()->hour(14),
                'check_out_time' => now()->subDay()->hour(16),
                'approved_by' => $approver,
                'approved_at' => now()->subDay()->hour(14),
                'created_at' => now()->subDay()->hour(13)->subMinutes(30),
            ],
            
            // Last week's visitors
            [
                'name' => 'Hendra Kusuma',
                'email' => 'hendra@example.com',
                'phone' => '08890123456',
                'purpose' => 'Survey untuk evaluasi lab',
                'person_to_meet' => 'Semua Staff',
                'photo' => 'dummy/visitor8.jpg',
                'status' => 'completed',
                'check_in_time' => now()->subDays(5)->hour(9),
                'check_out_time' => now()->subDays(5)->hour(11),
                'approved_by' => $approver,
                'approved_at' => now()->subDays(5)->hour(9),
                'created_at' => now()->subDays(5)->hour(8)->subMinutes(15),
            ],
        ];

        foreach ($visitors as $visitor) {
            Visitor::create($visitor);
        }

        $this->command->info('Created ' . count($visitors) . ' visitor records');
    }
}
