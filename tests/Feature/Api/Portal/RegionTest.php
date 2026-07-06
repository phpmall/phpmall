<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Portal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_top_level_regions(): void
    {
        DB::table('system_regions')->insert([
            [
                'parent_code' => '0',
                'name' => 'Beijing',
                'code' => '110000',
                'level' => 1,
                'zip_code' => '100000',
                'has_children' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_code' => '0',
                'name' => 'Shanghai',
                'code' => '310000',
                'level' => 1,
                'zip_code' => '200000',
                'has_children' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->getJson('/api/portal/regions?parent_code=0');

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'parentCode',
                            'name',
                            'code',
                            'level',
                            'zipCode',
                            'hasChildren',
                        ],
                    ],
                ],
            ])
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.items.0.name', 'Beijing')
            ->assertJsonPath('data.items.0.code', '110000')
            ->assertJsonPath('data.items.0.hasChildren', true);
    }

    public function test_it_returns_children_regions_by_parent_code(): void
    {
        DB::table('system_regions')->insert([
            [
                'parent_code' => '0',
                'name' => 'Beijing',
                'code' => '110000',
                'level' => 1,
                'zip_code' => '100000',
                'has_children' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_code' => '110000',
                'name' => 'Dongcheng',
                'code' => '110101',
                'level' => 3,
                'zip_code' => '100010',
                'has_children' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->getJson('/api/portal/regions?parent_code=110000');

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.name', 'Dongcheng')
            ->assertJsonPath('data.items.0.parentCode', '110000')
            ->assertJsonPath('data.items.0.hasChildren', false);
    }

    public function test_it_defaults_to_top_level_when_parent_code_is_missing(): void
    {
        DB::table('system_regions')->insert([
            [
                'parent_code' => '0',
                'name' => 'Guangdong',
                'code' => '440000',
                'level' => 1,
                'zip_code' => '510000',
                'has_children' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->getJson('/api/portal/regions');

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.name', 'Guangdong');
    }

    public function test_it_returns_empty_list_when_no_regions_match(): void
    {
        $response = $this->getJson('/api/portal/regions?parent_code=999999');

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonCount(0, 'data.items');
    }

    public function test_it_validates_parent_code_length(): void
    {
        $response = $this->getJson('/api/portal/regions?parent_code='.str_repeat('a', 21));

        $response->assertStatus(422);
    }
}
