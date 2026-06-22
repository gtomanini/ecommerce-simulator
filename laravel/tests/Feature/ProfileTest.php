<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_requires_authentication(): void
    {
        $this->getJson('/api/profile')->assertStatus(401);
        $this->putJson('/api/profile', [])->assertStatus(401);
    }

    public function test_can_view_own_profile(): void
    {
        $user = User::factory()->create([
            'phone' => '11999999999',
            'city' => 'Sao Paulo',
        ]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email,
                'phone' => '11999999999',
                'city' => 'Sao Paulo',
            ]);
    }

    public function test_can_update_profile(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile', [
            'name' => 'Updated Name',
            'phone' => '11988887777',
            'address' => 'New Street 456',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'zip' => '20000000',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Name',
                'phone' => '11988887777',
                'address' => 'New Street 456',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'zip' => '20000000',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'city' => 'Rio de Janeiro',
        ]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'profile_updated']);
    }

    public function test_profile_update_allows_partial_address_fields(): void
    {
        $user = User::factory()->create(['name' => 'Original']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile', [
            'phone' => '11900001111',
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Original', 'phone' => '11900001111']);
    }

    public function test_profile_update_validates_name_not_empty(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->putJson('/api/profile', ['name' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
