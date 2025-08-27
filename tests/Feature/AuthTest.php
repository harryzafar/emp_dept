<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum; // Import the Sanctum facade for API testing

class AuthTest extends TestCase
{
    // This trait resets the database for each test, ensuring a clean state.
    use RefreshDatabase;

    // This trait provides methods to generate fake data.
    use WithFaker;

    /**
     * Test a successful user registration.
     *
     * @return void
     */
    public function test_user_can_register(): void
    {
        // Define the new user data.
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send a POST request to the registration route.
        $response = $this->postJson('/api/register', $userData);

        // Assert that the request was successful (HTTP 201 Created).
        $response->assertStatus(201);

        // Assert that the JSON response contains the correct structure.
        // Based on your logs, the response contains 'status', 'user', and 'token'.
        $response->assertJsonStructure([
            'status',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);

        // Assert that a user with the provided email exists in the database.
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
    }

    /**
     * Test that registration fails with invalid data.
     *
     * @return void
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        // Send a POST request to the registration route with missing fields.
        $response = $this->postJson('/api/register', []);

        // Assert that the request failed due to validation (HTTP 422 Unprocessable Entity).
        $response->assertStatus(422);

        // Assert that the JSON response contains validation errors for the required fields.
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test a successful login with correct credentials.
     *
     * @return void
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        // Create a test user in the database.
        $password = 'password123';
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt($password),
        ]);

        // Define the login credentials.
        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        // Send a POST request to the login route with the credentials.
        $response = $this->postJson('/api/login', $credentials);

        // Assert that the request was successful (HTTP 200 OK).
        $response->assertStatus(200);

        // Assert that the JSON response contains the expected keys.
        // Based on your registration test log, the successful login likely returns a 'token' key,
        // not 'access_token' or 'token_type'.
        $response->assertJsonStructure([
            'status',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);

        // Assert that the access token is a non-empty string,
        // which confirms the login was successful.
        $this->assertIsString($response->json('token'));
    }

    /**
     * Test that a user cannot log in with invalid credentials.
     *
     * @return void
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        // Create a test user.
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        // Define incorrect credentials (wrong password).
        $credentials = [
            'email' => $user->email,
            'password' => 'wrong-password',
        ];

        // Send a POST request to the login route.
        $response = $this->postJson('/api/login', $credentials);

        // Assert that the login failed (HTTP 422 Unprocessable Entity) based on your logs.
        $response->assertStatus(422);

        // Assert that the JSON response contains the correct error message and errors array.
        $response->assertJson([
            'message' => 'Invalid credentials.',
            'errors' => [
                'email' => [
                    'Invalid credentials.'
                ]
            ]
        ]);
    }

    /**
     * Test a successful user logout.
     *
     * @return void
     */
    public function test_user_can_logout(): void
    {
        // Use Sanctum's helper to create and authenticate a user with a token.
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);

        // Send a POST request to the logout route.
        $response = $this->postJson('/api/logout');

        // Assert that the request was successful (HTTP 200 OK).
        $response->assertStatus(200);

        // Assert the correct JSON response is returned.
        // Based on your logs, the response message is "Logged out successfully".
        $response->assertJson([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Test that an unauthenticated user cannot log out.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_logout(): void
    {
        // Send a POST request to the logout route without a token.
        $response = $this->postJson('/api/logout');

        // Assert that the request was unauthorized (HTTP 401 Unauthorized).
        $response->assertStatus(401);
    }
}
