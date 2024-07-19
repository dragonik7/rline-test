<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_upload_files()
    {
        Storage::fake('local');

        $directory = Directory::factory()->create(['user_id' => $this->user->id]);

        $files = [
            UploadedFile::fake()->create('file1.txt', 100),
            UploadedFile::fake()->create('file2.txt', 200),
        ];

        $response = $this->postJson(route('files.upload', ['directoryId' => $directory->id]), [
            'files' => $files,
        ]);

        $response->assertStatus(201);
        $this->assertCount(2, File::all());

        Storage::disk('local')->assertExists(File::first()->path);
    }

    public function test_delete_file()
    {
        Storage::fake('local');

        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        $file = File::factory()->create(['user_id' => $this->user->id, 'directory_id' => $directory->id]);

        Storage::put($file->path, 'Test content');

        $response = $this->deleteJson(route('files.destroy', ['id' => $file->id]));

        $response->assertStatus(204);
        $this->assertCount(0, File::all());
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_rename_file()
    {
        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        $file = File::factory()->create(['user_id' => $this->user->id, 'directory_id' => $directory->id]);

        $response = $this->patchJson(route('files.rename', ['id' => $file->id]), [
            'name' => 'new_name.txt',
        ]);
        $response->assertStatus(200);
        $this->assertEquals('new_name.txt', $file->fresh()->name);
    }

    public function test_show_file_info()
    {
        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        $file = File::factory()->create(['user_id' => $this->user->id, 'directory_id' => $directory->id]);

        $response = $this->getJson(route('files.show', ['id' => $file->id]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id'          => $file->id,
                'name'        => $file->name,
                'size'        => $file->size,
                'uploaded_at' => $file->uploaded_at,
                'is_public'   => $file->is_public,
                'unique_link' => $file->unique_link,
                'path'        => $file->path,
            ]);
    }

    public function test_toggle_file_public()
    {
        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        $file = File::factory()->create(
            ['user_id' => $this->user->id, 'directory_id' => $directory->id, 'is_public' => false]
        );

        $response = $this->patchJson(route('files.toggle-public', ['id' => $file->id]));

        $response->assertStatus(200);
        $this->assertTrue($file->fresh()->is_public);
    }

    public function test_download_file()
    {
        Storage::fake('local');

        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        $file = File::factory()->create(['user_id' => $this->user->id, 'directory_id' => $directory->id]);

        Storage::put($file->path, 'Test content');

        $response = $this->getJson(route('files.download', ['uniqueLink' => $file->unique_link]));

        $response->assertStatus(200)
            ->assertHeader('Content-Disposition', 'attachment; filename="'.$file->name.'"');
    }

    public function test_get_current_space()
    {
        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        File::factory()->create(['user_id' => $this->user->id, 'directory_id' => $directory->id, 'size' => 500]);
        File::factory()->create(['user_id' => $this->user->id, 'directory_id' => $directory->id, 'size' => 1500]);

        $response = $this->getJson(route('files.get-current-space'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'total_size' => 2000,
            ]);
    }

}
