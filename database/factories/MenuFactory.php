<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{

    protected function withFaker()
    {
        $faker =  parent::withFaker();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));

        return $faker;
    }


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->foodName(),
        ];
    }
}
