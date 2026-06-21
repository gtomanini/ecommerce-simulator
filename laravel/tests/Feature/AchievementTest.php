<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_achievements_require_authentication(): void
    {
        $this->getJson('/api/achievements')->assertStatus(401);
    }

    public function test_can_list_user_achievements(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $achievement = Achievement::factory()->create();
        UserAchievement::factory()->create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
        ]);

        $response = $this->getJson('/api/achievements');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
        $this->assertEquals($achievement->id, $response->json()[0]['achievement']['id']);
    }

    public function test_returns_empty_when_no_achievements(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/achievements');

        $response->assertStatus(200)
            ->assertJson([]);
    }
}
