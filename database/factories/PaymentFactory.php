<?php

namespace Database\Factories;

use App\Models\UserLogin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            "description_id" => $this->faker->numberBetween(1, 999),
            "amount" => $this->faker->numberBetween(100, 2000),
            "date_post" => $this->faker->dateTimeBetween('-2 years', '-1 year'),
            "record_by_id" => $this->faker->randomElement([16]),
            "payment_semester" => $this->faker->randomElement([1, 2]),
            "deadline" => $this->faker->dateTimeThisYear(),
            "created_at" => $this->faker->dateTimeBetween('-2 years', '-1 year')
        ];
    }
}
