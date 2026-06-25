<?php

namespace Database\Factories;

use App\Modules\Auth\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->slug(),
            'display_name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'parent_id' => 0,
            'type' => 'menu',
            'route' => fake()->url(),
            'icon' => null,
            'sort' => 0,
            'status' => 1,
        ];
    }
}
