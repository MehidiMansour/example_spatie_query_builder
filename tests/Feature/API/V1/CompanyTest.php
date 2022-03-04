<?php

namespace Tests\Feature\API\V1;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     @test
     */
    public function unlogged_user_cannot_access_to_company()
    {
        $this->withExceptionHandling();
        $this->getJson('/api/companies')
            ->assertUnauthorized();
    }
    /**
     @test
     */
    public function logged_user_can_get_list_companies()
    {
        $this->withExceptionHandling();
        $user = $this->getLoggedUser();
        $company = Company::factory(['user_id' => $user->id])->create();
        $this->getJson('/api/companies')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
    /**
     @test
     */
    public function logged_user_can_create_company()
    {

        $user = $this->getLoggedUser();
        $payload = [
            'name' => $this->faker->sentence(),
        ];
        $this->postJson('api/companies', $payload)
            ->assertJsonPath('data.user.name', $user->name)
            ->assertStatus(201);
        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'name' => $payload['name'],
            'user_id' => $user->id,
        ]);
    }
    /** @test */
    public function validation_for_creating_company()
    {
        $user = $this->getLoggedUser();

        $this->postJson('/api/companies')
            ->assertStatus(422)->assertJsonValidationErrors(['name']);
    }
    /** @test */
    public function logged_user_can_read_company()
    {
        $this->withExceptionHandling();
        $user = $this->getLoggedUser();
        $company = Company::factory(['user_id' => $user->id])->create();
        $this->getJson('/api/companies/' . $company->id)
            ->assertOk()
            ->assertJsonPath('data.name', $company->name)
            ->assertJsonPath('data.user.name', $user->name);
    }
    /** @test */
    public function logged_user_can_update_company()
    {
        $user = $this->getLoggedUser();
        $company = Company::factory(['user_id' => $user->id])->create();
        $payload = ['name' => $this->faker->sentence()];
        $this->putJson('/api/companies/' . $company->id, $payload)
            ->assertOK()
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.user.name', $user->name);
        $this->assertDatabaseHas('companies', ['name' => $payload['name'], 'id' => 1]);
    }
    /** @test */
    public function logged_user_can_remove_company()
    {
        $user =  $this->getLoggedUser();
        $company = Company::factory(['user_id' => $user->id])->create();
        $this->deleteJson('/api/companies/' . $company->id)
            ->assertOK();
        $this->assertDatabaseMissing('companies', ['id' => 1, 'name' => $company->name]);
        $this->assertDatabaseCount('companies', 0);
    }
    /** @test */
    public function user_can_manage_only_our_companies()
    {
        $user = $this->getLoggedUser();
        $company1 = Company::factory(['user_id' => $user->id])->create();
        $company2 = Company::factory()->create();
        /** index */
        $this->getJson('/api/companies')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', $company1->name)
            ->assertJsonPath('data.0.user.name', $user->name);
        /** show */
        $this->getJson('/api/companies/' . $company2->id)
            ->assertForbidden();
        /** update */
        $this->putJson('/api/companies/' . $company2->id, ['name' => $this->faker->sentence()])
            ->assertForbidden();
        /** delete */
        $this->deleteJson('/api/companies/' . $company2->id)
            ->assertForbidden();
    }
    /** @test */
    public function mine_working_fine()
    {
        $user = $this->getLoggedUser();
        $project1 = Company::factory(['user_id' => $user->id])->create();
        $project2 = Company::factory()->create();
        $this->assertTrue($project1->isMine);
        $this->assertFalse($project2->isMine);
    }
}
