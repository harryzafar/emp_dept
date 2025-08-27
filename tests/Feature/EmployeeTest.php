<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $department;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $this->department = Department::factory()->create();
    }

    protected function authHeader()
    {
        $token = $this->user->createToken('test_token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    /** @test */
    public function it_can_list_employees()
    {
        // Ensure fresh database with 5 employees
        Employee::factory()->count(5)->create();

        $response = $this->getJson('/api/employees', $this->authHeader());

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data'); // if your API wraps results in "data"
    }

    /** @test */
    public function it_can_create_an_employee()
    {
        $data = [
            'department_id' => Department::factory()->create()->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'date_of_birth' => '1990-01-01',
            'designation' => 'Developer',
            'phone_numbers' => [
                ['phone' => '1234567890'],
                ['phone' => '9876543210'],
            ],
            'addresses' => [
                ['line1' => '123 Main St'],
                ['line1' => '456 Second St'],
            ]
        ];

        $response = $this->postJson('/api/employees', $data, $this->authHeader());

        $response->assertStatus(201)
            ->assertJsonFragment(['first_name' => 'John']);
    }

    /** @test */
    public function it_can_show_an_employee()
    {
        $employee = Employee::factory()->create([
            'department_id' => $this->department->id,
        ]);

        $response = $this->getJson("/api/employees/{$employee->id}", $this->authHeader());

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $employee->id]);
    }

    /** @test */
    public function it_can_update_an_employee()
    {

        $employee = Employee::factory()->create();
        $data = [
            'first_name' => 'Updated',
            'last_name' => 'Employee',
            'email' => 'updated@example.com',
            'date_of_birth' => '1992-02-02',
            'designation' => 'Manager',
        ];

        $response = $this->putJson("/api/employees/{$employee->id}", $data, $this->authHeader());

        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'Updated']);
    }

    /** @test */
    public function it_can_delete_an_employee()
    {
        $employee = Employee::factory()->create([
            'department_id' => $this->department->id,
        ]);

        $response = $this->deleteJson("/api/employees/{$employee->id}", [], $this->authHeader());

        $response->assertStatus(200)
            ->assertJson(['message' => 'Employee deleted successfully']);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
