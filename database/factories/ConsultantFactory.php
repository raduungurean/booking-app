<?php

namespace Database\Factories;

use App\Models\Consultant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consultant>
 */
class ConsultantFactory extends Factory
{
    protected $model = Consultant::class;
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ];
    }
}






