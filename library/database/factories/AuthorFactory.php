<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['men', 'women']);
        $number = $this->faker->numberBetween(0, 99);

        return [
            'name' => $this->faker->name(),
            'photo' => "https://randomuser.me/api/portraits/{$gender}/{$number}.jpg",
        ];
    }
}
