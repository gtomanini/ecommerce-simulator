<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_works(): void
    {
        $this->getJson('/api/health')->assertStatus(200)->assertJson(['status' => 'ok']);
    }

    public function test_metrics_endpoint_is_public_and_returns_text(): void
    {
        $response = $this->get('/api/metrics');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertStringContainsString('audit_actions_total', $response->getContent());
    }

    public function test_metrics_count_registered_users(): void
    {
        $user = User::factory()->create();
        AuditLog::factory()->count(2)->create(['action' => 'user_registered', 'user_id' => $user->id]);

        $response = $this->get('/api/metrics');

        $content = $response->getContent();
        $this->assertStringContainsString('users_registered_total 2', $content);
        $this->assertStringContainsString('audit_actions_total{action="user_registered"} 2', $content);
    }

    public function test_metrics_count_logins_and_orders(): void
    {
        $user = User::factory()->create();
        AuditLog::factory()->count(3)->create(['action' => 'user_login', 'user_id' => $user->id]);
        AuditLog::factory()->create([
            'action' => 'order_created',
            'user_id' => $user->id,
            'changes' => ['total' => 150.50],
        ]);

        $response = $this->get('/api/metrics');
        $content = $response->getContent();

        $this->assertStringContainsString('user_logins_total 3', $content);
        $this->assertStringContainsString('orders_created_total 1', $content);
        $this->assertStringContainsString('orders_revenue_total 150.5', $content);
    }

    public function test_metrics_count_cart_items_added(): void
    {
        $user = User::factory()->create();
        AuditLog::factory()->count(4)->create(['action' => 'cart_item_added', 'user_id' => $user->id]);

        $response = $this->get('/api/metrics');

        $this->assertStringContainsString('cart_items_added_total 4', $response->getContent());
    }

    public function test_metrics_revenue_zero_with_no_orders(): void
    {
        $response = $this->get('/api/metrics');

        $this->assertStringContainsString('orders_revenue_total 0', $response->getContent());
    }
}
