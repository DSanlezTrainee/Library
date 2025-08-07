<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de cover IDs reais do OpenLibrary
        $coverIds = [
            240727,
            8231856,
            10523354,
            295538,
            10270287,
            240726,
            11123230,
            240729,
            12914522,
            11123241,
            12620633,
            12524122,
            10909225,
            9018112,
            11123239,
            8618559,
            10270286,
            10527163,
            10524697,
            6812317,
            12450450,
            10909220,
            9875001,
            8672390,
            11140600,
            10656451,
            12548197,
            10341720,
            10302442,
            10525964,
            8740622,
            8499251,
            10484175,
            8427073,
            10302336,
            10694756,
            9495794,
            12520198,
            10647336,
        ];

        // Garante que cada imagem é única
        $coverId = $this->faker->unique()->randomElement($coverIds);

        // Geração de título mais realista
        $title = $this->generateRealisticTitle();

        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'name' => $title,
            'publisher_id' => Publisher::factory(),
            'bibliography' => $this->faker->paragraph(10),
            'cover_image' => "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg",
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }

    // Método privado que gera títulos realistas
    private function generateRealisticTitle(): string
    {
        $themes = ['Mistério', 'Segredo', 'Luz', 'Sombras', 'Silêncio', 'Verdade', 'Mentira', 'Destino', 'Guerra', 'Paz'];
        $places = ['Lisboa', 'Porto', 'Serra Negra', 'Vale do Eco', 'Ilha Perdida', 'Templo Antigo'];
        $things = ['Estrela', 'Espada', 'Profecia', 'Aliança', 'Chave', 'Herança'];
        $names = ['Aurora', 'Luna', 'Duarte', 'Gabriel', 'Marta', 'Elias'];

        $titleTemplates = [
            'O ' . $this->faker->randomElement($themes) . ' de ' . $this->faker->randomElement($places),
            'A Última ' . $this->faker->randomElement($things),
            'Entre ' . $this->faker->randomElement($things) . ' e ' . $this->faker->randomElement($things),
            'As Crónicas de ' . $this->faker->randomElement($names),
            'A Sombra da ' . $this->faker->randomElement($things),
        ];

        return $this->faker->unique()->randomElement($titleTemplates);
    }
}
