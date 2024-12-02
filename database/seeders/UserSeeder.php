<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Fetch panel names from the external service.
     *
     * @return array
     */
    public function fetchPanelNames(): array
    {
        $response = Http::get('http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner');
        
        if ($response->ok()) {
            return json_decode($response->body(), true);
        }

        throw new \Exception('Failed to fetch panel data');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // Fetch the panel data from the external service
            $data = $this->fetchPanelNames();
            
            // Extract lecturer names
            $lecturerNames = array_column($data['list'], 'lecturer_name');
            
            // Remove duplicate names
            $uniqueLecturerNames = array_unique($lecturerNames);
    
            // Sort the lecturer names
            sort($uniqueLecturerNames);
    
            // Loop through the lecturer names and create users
            foreach ($uniqueLecturerNames as $index => $name) {
                // Determine if it's the first panel (role 1) or others (role 2)
                $role = ($index === 0) ? 1 : 2;
                $isPanel = 1;
                $email = strtolower(str_replace(' ', '.', $name)) . '@utm.my'; // Generate email based on the name
                
                User::create([
                    'name' => $name,
                    'username' => strtolower(str_replace(' ', '', $name)), // Remove spaces for username
                    'email' => $email,
                    'role' => $role,
                    'isPanel' => $isPanel,
                    'email_verified_at' => now(),
                    'password' => Hash::make(123456), // Default password
                ]);
            }

            // Optionally, you can display a success message or log the process
            $this->command->info('Users and panels have been successfully seeded.');

        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }
}
