<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuestVisitor;
use App\Models\Lab;
use Carbon\Carbon;

class GuestVisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all labs
        $labs = Lab::all();

        if ($labs->isEmpty()) {
            $this->command->warn('No labs found. Please seed labs first.');
            return;
        }

        // Sample names (Indonesian)
        $names = [
            'Ahmad Fauzi',
            'Siti Nurhaliza',
            'Budi Santoso',
            'Dewi Lestari',
            'Rizki Firmansyah',
            'Ayu Ting Ting',
            'Muhammad Hasan',
            'Putri Andini',
            'Eko Prasetyo',
            'Rina Anggraini',
            'Dimas Pratama',
            'Nurul Fadilah',
            'Arief Wijaya',
            'Maya Sari',
            'Hendro Gunawan',
            'Linda Susanti',
            'Yusuf Rahman',
            'Fitri Handayani',
            'Agus Salim',
            'Wulan Dari',
        ];

        // Sample purposes
        $purposes = [
            'Mengerjakan tugas praktikum',
            'Penelitian skripsi',
            'Mengerjakan project mata kuliah',
            'Belajar mandiri',
            'Praktikum mata kuliah',
            'Konsultasi dengan dosen',
            'Menggunakan software khusus',
            'Meeting kelompok',
            'Mengerjakan tugas akhir',
            'Praktikum pemrograman',
            'Ujian praktikum',
            'Latihan coding',
            'Riset dan eksperimen',
            'Workshop pelatihan',
            'Presentasi project',
        ];

        $guestCount = 0;

        // Create data for the last 14 days
        for ($day = 13; $day >= 0; $day--) {
            $date = Carbon::today()->subDays($day);
            
            // Random number of guests per day (5-15)
            $dailyGuests = rand(5, 15);

            for ($i = 0; $i < $dailyGuests; $i++) {
                // Random time between 08:00 and 17:00
                $hour = rand(8, 17);
                $minute = rand(0, 59);
                
                $checkInTime = $date->copy()->setTime($hour, $minute);

                // Generate NIM (format: year + random 6 digits)
                $year = rand(2020, 2024);
                $nim = $year . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

                GuestVisitor::create([
                    'nim' => $nim,
                    'nama' => $names[array_rand($names)],
                    'lab_id' => $labs->random()->id,
                    'purpose' => $purposes[array_rand($purposes)],
                    'check_in_time' => $checkInTime,
                ]);

                $guestCount++;
            }
        }

        $this->command->info("Created {$guestCount} guest visitor records for the last 14 days.");
    }
}
