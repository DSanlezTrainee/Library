<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
class PublisherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $prefixes = ['Editora', 'Editorial', 'Livros', 'Publicações', 'Grupo Editorial'];
        $suffixes = ['Horizonte', 'Caminho', 'Nova Geração', 'Atlas', 'Planeta', 'Global', 'Alfa'];

        $name = $this->faker->randomElement($prefixes) . ' ' . $this->faker->randomElement($suffixes);

        return [
            'name' => $name,
            'logo' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&size=200&background=random',
        ];
    }
}
