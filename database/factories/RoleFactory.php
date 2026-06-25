<?php

namespace Database\Factories;

use App\Modules\Auth\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->slug(),
            'display_name' => fake()->jobTitle(),
            'description' => fake()->sentence(),
            'status' => 1,
            'sort' => 0,
        ];
    }
}
