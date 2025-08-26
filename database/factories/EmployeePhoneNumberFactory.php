<?php

namespace Database\Factories;
use App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeePhoneNumber>
 */
class EmployeePhoneNumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'employee_id' => Employee::factory(),
            'phone' => $this->faker->phoneNumber(),
            'label' => $this->faker->randomElement(['mobile','home','work']),
            'is_primary' => $this->faker->boolean(30),
        ];
    }
}
