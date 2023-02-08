<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{

    const UNSPLASH_SEARCH = 'https://api.unsplash.com/search/photos?client_id={client_id}&query={query}';

    protected function withFaker()
    {
        $faker = parent::withFaker();
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
        $foodname = $this->faker->foodName();
        $foodUrl = Http::withHeaders([
            'Accept-Version' => 'v1'
        ])->get(self::UNSPLASH_SEARCH, [
            'client_id' => config('vendor.unsplash.client_id'),
            'query'     => $foodname,
            'per_page'  => 1
        ])->json()['results'][0]['urls']['small'];
        return [
            "name"      => $foodname,
            "image_url" => $foodUrl,
        ];
    }
}
