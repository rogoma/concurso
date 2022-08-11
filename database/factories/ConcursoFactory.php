<?php

namespace Database\Factories;

use App\Models\Concurso;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConcursoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Concurso::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'decripcion' => $this->faker->text(50),
            'cargo_id' => $this->faker->numberBetween(1,20),
            'cantidad_vacanc' => $this->faker->numberBetween(10,100),
            'salario' => $this->faker->numberBetween(3500000, 8000000),
            'ini_post' => $this->faker->dateTimeThisYear,
            'fin_post' => $this->faker->dateTimeThisYear,
            'ini_eva_doc' => $this->faker->dateTimeThisYear,
            'fin_eva_doc' => $this->faker->dateTimeThisYear,
            'ini_eva_cur' => $this->faker->dateTimeThisYear,
            'fin_eva_cur' => $this->faker->dateTimeThisYear,
            'ini_examen' => $this->faker->dateTimeThisYear,
            'fin_examen' => $this->faker->dateTimeThisYear,
            'ini_entrevista' => $this->faker->dateTimeThisYear,
            'fin_entrevista' => $this->faker->dateTimeThisYear,
            'user_crea' => 'admin',
            'fecha_crea' => date('Y-m-d'),
        ];
    }
}
