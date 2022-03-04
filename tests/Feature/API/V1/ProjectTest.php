<?php

namespace Tests\Feature\API\V1;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     @test
     */
    public function unlogged_user_cannot_access_to_project()
    {
        $this->getJson('/api/projects')
            ->assertUnauthorized();
    }
    /**
     @test
     */
    public function logged_user_can_get_list_projects()
    {
        $project = Project::factory()->create();
        $user = $this->getLoggedUser();
        $project->users()->attach($user);
        $this->getJson('/api/projects')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
    /**
     @test
     */
    public function logged_user_can_create_project()
    {
        $this->getLoggedUser();
        $payload = [
            'name' => 'project',
            'status' => 1,
            'description' => 'description project',
            'duration' => 2,
            'level' => 3,
        ];
        $this->postJson('api/projects', $payload)
            ->assertStatus(201);
        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseHas('projects', [
            'id' => 1,
            'name' => $payload['name'],
            'description' => $payload['description']
        ]);
    }
    /** @test */
    public function validation_for_creating_project()
    {
        $this->getLoggedUser();

        $this->postJson('/api/projects')
            ->assertStatus(422)->assertJsonValidationErrors(['name', 'description']);
    }
    /** @test */
    public function logged_user_can_read_project()
    {
        $this->getLoggedUser();
        $project = Project::factory()->create();
        $this->getJson('/api/projects/' . $project->id)
            ->assertOk()
            ->assertJsonPath('data.name', $project->name);
    }
    /** @test */
    public function logged_user_can_update_project()
    {
        $this->getLoggedUser();
        $project = Project::factory()->create();
        $payload = ['name' => $this->faker->sentence()];
        $this->putJson('/api/projects/' . $project->id, $payload)
            ->assertOK()
            ->assertJsonPath('data.name', $payload['name']);
        $this->assertDatabaseHas('projects', ['name' => $payload['name'], 'id' => 1]);
    }
    /** @test */
    public function validation_for_updating_project()
    {
        $this->getLoggedUser();
        $project = Project::factory()->create();
        $this->putJson('/api/projects/' . $project->id)
            ->assertStatus(422)->assertJsonValidationErrors(['name']);
    }
    /** @test */
    public function logged_user_can_remove_project()
    {
        $this->getLoggedUser();
        $project = Project::factory()->create();
        $this->deleteJson('/api/projects/' . $project->id)
            ->assertOK();
        $this->assertDatabaseMissing('projects', ['id' => 1, 'name' => $project->name]);
        $this->assertDatabaseCount('projects', 0);
    }
}
