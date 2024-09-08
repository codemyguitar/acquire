<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_be_created(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['']
        );

        $response = $this->get('/api/user');

        $response->assertStatus(200);
    }

    public function test_user_can_create_customers(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['']
        );

        $response = $this->post('/api/customers', [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'age' => 34,
            'dob' => Carbon::now()->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_view_customer_list(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['']
        );

        $response = $this->post('/api/customers', [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'age' => 34,
            'dob' => Carbon::now()->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/customers');

        $response->assertStatus(200);
    }

    public function test_user_can_update_customers(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['']
        );

        $response = $this->post('/api/customers', [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'age' => 34,
            'dob' => Carbon::now()->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
        ]);
        $response->assertStatus(200);

        $content = json_decode($response->getContent());

        $response = $this->put('/api/customers/' . $content->id, [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'age' => 34,
            'dob' => Carbon::now()->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_delete_customers(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['']
        );

        $response = $this->post('/api/customers', [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'age' => 34,
            'dob' => Carbon::now()->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
        ]);
        $response->assertStatus(200);

        $content = json_decode($response->getContent());

        $response = $this->delete('/api/customers/' . $content->id);

        $response->assertStatus(200);
    }
}
