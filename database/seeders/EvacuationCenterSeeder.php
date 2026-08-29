<?php

namespace Database\Seeders;

use App\Models\EvacuationCenter;
use App\Models\Evacuee;
use Illuminate\Database\Seeder;

class EvacuationCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            [
                'name' => 'Municipal gym (demo)',
                'barangay' => 'San Juan',
                'address' => 'Brgy. San Juan, Municipality of Baras, Rizal',
                'capacity' => 200,
                'current_occupancy' => 45,
                'status' => 'active',
                'contact_person' => 'John dela Cruz',
                'contact_number' => '0917-000-0001',
                'latitude' => 14.51630,
                'longitude' => 121.26560,
                'notes' => 'Demo center for group 1 evacuation testing.',
                'created_by' => 1,
            ],
            [
                'name' => 'San Juan Evacuation',
                'barangay' => 'San Juan',
                'address' => 'Brgy. San Juan, near town hall',
                'capacity' => 30,
                'current_occupancy' => 10,
                'status' => 'active',
                'contact_person' => 'Maria Santos',
                'contact_number' => '0917-000-0002',
                'latitude' => 14.51790,
                'longitude' => 121.26560,
                'notes' => 'Alternative evacuation site for San Juan barangay.',
                'created_by' => 1,
            ],
            [
                'name' => 'School BES',
                'barangay' => 'San Jose',
                'address' => 'Barangay San Jose, Baras, Rizal',
                'capacity' => 20,
                'current_occupancy' => 13,
                'status' => 'active',
                'contact_person' => 'Jose P. Ramos',
                'contact_number' => '0917-000-0003',
                'latitude' => 14.52200,
                'longitude' => 121.25840,
                'notes' => 'Small barangay elementary school used as evacuation center.',
                'created_by' => 1,
            ],
        ];

        foreach ($centers as $centerData) {
            $center = EvacuationCenter::create($centerData);

            if ($center->name === 'School BES') {
                Evacuee::create([
                    'evacuation_center_id' => $center->id,
                    'name' => 'Perez Family',
                    'family_members' => 4,
                    'barangay_origin' => 'San Jose',
                    'needs' => 'Medication for asthma',
                    'id_presented' => 'Valid ID',
                    'status' => 'checked_in',
                    'checked_in_at' => now()->subHours(3),
                    'recorded_by' => 1,
                ]);

                Evacuee::create([
                    'evacuation_center_id' => $center->id,
                    'name' => 'Garcia Family',
                    'family_members' => 2,
                    'barangay_origin' => 'San Jose',
                    'needs' => 'Infant care',
                    'id_presented' => 'Valid ID',
                    'status' => 'checked_in',
                    'checked_in_at' => now()->subHours(1),
                    'recorded_by' => 1,
                ]);
            }
        }
    }
}
