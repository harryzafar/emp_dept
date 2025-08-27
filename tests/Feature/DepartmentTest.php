<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Create test user for authentication
        $this->user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);
    }

    protected function authHeader()
    {
        $token = $this->user->createToken('test_token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    /** @test */
    public function it_can_list_departments()
    {
        Department::factory()->count(3)->create();

        $response = $this->getJson('/api/departments', $this->authHeader());

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_department()
    {
        $data = ['name' => 'Human Resources'];

        $response = $this->postJson('/api/departments', $data, $this->authHeader());

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Human Resources']);

        $this->assertDatabaseHas('departments', ['name' => 'Human Resources']);
    }

    /** @test */
    public function it_can_show_a_department()
    {
        $department = Department::factory()->create();

        $response = $this->getJson("/api/departments/{$department->id}", $this->authHeader());

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $department->id]);
    }

    /** @test */
    public function it_can_update_a_department()
    {
        $department = Department::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/departments/{$department->id}", [
            'name' => 'Updated Name'
        ], $this->authHeader());

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('departments', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_department()
    {
        $department = Department::factory()->create();

        $response = $this->deleteJson("/api/departments/{$department->id}", [], $this->authHeader());

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Department deleted successfully']);

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }
}
