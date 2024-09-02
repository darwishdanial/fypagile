<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'course' => fake()->randomElement($array = array ('SECJ','SECV','SECB')),
            'matric' => fake()->unique()->numerify('A19EC####'),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' =>  fake()->unique()->numerify('+601#-#######'),
            'cohort' =>  fake()->randomElement($array = array ('2018/2022','2019/2023','2020/2024')),
            'sessionpsm'=>  fake()->randomElement($array = array ('2022/2023','2021/2022','2023/2024')),
            'psm'=> null,
            'supervisorId'=> null,
        ];
    }


}
