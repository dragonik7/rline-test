<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DirectoryTest extends TestCase
{

    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_create_directory()
    {
        $response = $this->postJson(route('directories.store'), [
            'name'      => 'Test Directory',
            'parent_id' => null,
        ]);

        $response->assertStatus(201)->assertJson(['name' => 'Test Directory']);
        $this->assertDatabaseHas('directories', ['name' => 'Test Directory', 'user_id' => $this->user->id]);
    }


    public function test_delete_directory()
    {
        Storage::fake('local');

        $directory = Directory::factory()->create(['user_id' => $this->user->id]);
        $files = File::factory(3)->create(['user_id' => $this->user->id, 'directory_id' => $directory->id]);
        foreach ($files as $file){
            Storage::put($file->path, 'Test content');
        }

        $response = $this->deleteJson(route('directories.destroy', ['id' => $directory->id]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('directories', ['id' => $directory->id]);

        $this->assertDatabaseCount('files', 0);
        foreach ($files as $file) {
            Storage::disk('local')->assertMissing($file->path);
        }
    }

    // Тест удаления директории

    public function test_rename_directory()
    {
        $directory = Directory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patchJson(route('directories.rename', $directory->id), [
            'name' => 'Renamed Directory',
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Directory renamed successfully']);
        $this->assertDatabaseHas('directories', ['id' => $directory->id, 'name' => 'Renamed Directory']);
    }

    // Тест переименования директории

    public function test_delete_directory_unauthorized()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        $directory = Directory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(route('directories.destroy', $directory->id));

        $response->assertStatus(403)->assertJson(['message' => 'Unauthorized']);
    }

    public function test_rename_directory_unauthorized()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        $directory = Directory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patchJson(route('directories.rename', $directory->id), [
            'name' => 'Renamed Directory',
        ]);

        $response->assertStatus(403)->assertJson(['message' => 'Unauthorized']);
    }
}
