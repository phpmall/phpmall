<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Common;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_image_upload(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->postJson('/api/common/image', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'url',
                    'path',
                    'name',
                    'size',
                    'mimeType',
                ],
            ])
            ->assertJson(['code' => 0]);

        $path = $response->json('data.path');
        Storage::disk('public')->assertExists($path);
        $this->assertStringStartsWith('images/', $path);
    }

    public function test_image_upload_rejects_non_image(): void
    {
        $file = UploadedFile::fake()->create('test.txt', 1);

        $response = $this->postJson('/api/common/image', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
    }

    public function test_file_upload(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->postJson('/api/common/file', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'url',
                    'path',
                    'name',
                    'size',
                    'mimeType',
                ],
            ])
            ->assertJson(['code' => 0]);

        $path = $response->json('data.path');
        Storage::disk('public')->assertExists($path);
        $this->assertStringStartsWith('files/', $path);
    }

    public function test_oss_policy_returns_policy_data(): void
    {
        config()->set('filesystems.disks.oss', [
            'access_key_id' => 'test-access-key',
            'access_key_secret' => 'test-secret',
            'bucket' => 'test-bucket',
            'endpoint' => 'oss-cn-hangzhou.aliyuncs.com',
            'cdn_domain' => 'cdn.example.com',
            'callback_url' => 'https://example.com/callback',
        ]);

        $response = $this->postJson('/api/common/oss-policy', [
            'type' => 'image',
            'filename' => 'test.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'accessKeyId',
                    'policy',
                    'signature',
                    'host',
                    'expire',
                    'callback',
                    'dir',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.accessKeyId', 'test-access-key')
            ->assertJsonPath('data.host', 'cdn.example.com');

        $this->assertNotEmpty($response->json('data.policy'));
        $this->assertNotEmpty($response->json('data.signature'));
        $this->assertStringStartsWith('images/', $response->json('data.dir'));
    }

    public function test_oss_policy_validates_type(): void
    {
        $response = $this->postJson('/api/common/oss-policy', [
            'type' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_confirm_returns_url(): void
    {
        config()->set('filesystems.disks.oss', [
            'access_key_id' => 'test-access-key',
            'access_key_secret' => 'test-secret',
            'bucket' => 'test-bucket',
            'endpoint' => 'oss-cn-hangzhou.aliyuncs.com',
            'cdn_domain' => 'cdn.example.com',
        ]);

        $response = $this->postJson('/api/common/confirm', [
            'path' => 'images/2024/01/test.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'url',
                    'path',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.path', 'images/2024/01/test.jpg')
            ->assertJsonPath('data.url', 'https://cdn.example.com/images/2024/01/test.jpg');
    }

    public function test_confirm_validates_required_path(): void
    {
        $response = $this->postJson('/api/common/confirm', []);

        $response->assertStatus(422);
    }
}
