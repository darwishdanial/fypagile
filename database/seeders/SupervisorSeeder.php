<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Database\Seeder;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
        $users = User::all();

        foreach ($users as $user) {
           $supervisor = new Supervisor([
                'userId' => $user->id,
            ]);

             $supervisor->save();
         }
     }

    // public function run()
    // {
    //     $users = User::whereIn('role', [1, 2])->get();


    //     foreach ($users as $user) {
    //         $supervisor = new Supervisor([
    //             'userId' => $user->id,
    //         ]);

    //         $supervisor->save();
    //     }
    // }

}
