<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Lab;
use Carbon\Carbon;

class BookingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generate booking data for today and next 7 days
     */
    public function run(): void
    {
        // Get all regular users (not staff/superadmin)
        $users = User::where('role', 'user')->get();
        
        // Get all labs
        $labs = Lab::all();
        
        if ($users->isEmpty() || $labs->isEmpty()) {
            $this->command->warn('No users or labs found. Please seed users and labs first.');
            return;
        }
        
        // Generate bookings for today and next 7 days
        $startDate = today();
        $endDate = today()->addDays(7);
        
        $purposes = [
            'Praktikum Pemrograman Web',
            'Praktikum Jaringan Komputer',
            'Praktikum Basis Data',
            'Penelitian Tugas Akhir',
            'Workshop Laravel',
            'Pelatihan Cyber Security',
            'Ujian Praktikum',
            'Kuliah Pengganti',
            'Diskusi Kelompok Proyek',
            'Simulasi Sistem Terdistribusi',
        ];
        
        // Time slots for bookings (working hours: 08:00 - 17:00)
        $timeSlots = [
            ['08:00', '10:00'],
            ['10:00', '12:00'],
            ['13:00', '15:00'],
            ['15:00', '17:00'],
        ];
        
        $bookingCount = 0;
        
        // Loop through each day
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            // Skip weekends
            if ($currentDate->isWeekend()) {
                $currentDate->addDay();
                continue;
            }
            
            // Generate 2-4 bookings per day
            $dailyBookings = rand(2, 4);
            
            for ($i = 0; $i < $dailyBookings; $i++) {
                // Random user and lab
                $user = $users->random();
                $lab = $labs->random();
                
                // Random time slot
                $timeSlot = $timeSlots[array_rand($timeSlots)];
                
                $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $timeSlot[0]);
                $endTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $timeSlot[1]);
                
                // Random status (mostly approved for schedule display)
                $status = ['approved', 'approved', 'approved', 'pending'][array_rand(['approved', 'approved', 'approved', 'pending'])];
                
                Booking::create([
                    'user_id' => $user->id,
                    'lab_id' => $lab->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'purpose' => $purposes[array_rand($purposes)],
                    'status' => $status,
                ]);
                
                $bookingCount++;
            }
            
            $currentDate->addDay();
        }
        
        $this->command->info("✅ Created {$bookingCount} booking records for the next 7 days");
        $this->command->info("📅 Date range: {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
    }
}
