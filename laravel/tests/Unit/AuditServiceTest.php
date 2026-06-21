<?php

namespace Tests\Unit;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Start each test with a clean metrics file so the
        // first-write vs. increment branches are deterministic.
        @unlink(storage_path('prometheus_metrics.txt'));
    }

    public function test_metrics_file_first_write_and_increment_branches(): void
    {
        $user = User::factory()->create();

        // First order: first-write branches for action label, counter and revenue.
        AuditService::logAction($user, 'order_created', 'Order 1', 'Order', 1, ['total' => 100.00]);
        // Second order: increment-existing branches for action label, counter and revenue.
        AuditService::logAction($user, 'order_created', 'Order 2', 'Order', 2, ['total' => 50.00]);

        $content = file_get_contents(storage_path('prometheus_metrics.txt'));

        $this->assertStringContainsString('audit_actions_total{action="order_created"} 2', $content);
        $this->assertStringContainsString('orders_created_total 2', $content);
        $this->assertStringContainsString('orders_revenue_total 150', $content);
    }

    public function test_user_login_counter_first_write_and_increment(): void
    {
        $user = User::factory()->create();

        AuditService::logAction($user, 'user_login', 'Login 1');
        AuditService::logAction($user, 'user_login', 'Login 2');

        $content = file_get_contents(storage_path('prometheus_metrics.txt'));
        $this->assertStringContainsString('user_logins_total 2', $content);
    }

    public function test_unmapped_action_only_increments_generic_counter(): void
    {
        $user = User::factory()->create();

        // Hits the default arm of the match (no specific metric).
        $log = AuditService::logAction($user, 'some_other_action', 'Did something');

        $this->assertInstanceOf(AuditLog::class, $log);
        $content = file_get_contents(storage_path('prometheus_metrics.txt'));
        $this->assertStringContainsString('audit_actions_total{action="some_other_action"} 1', $content);
    }

    public function test_log_action_creates_audit_log_with_user(): void
    {
        $user = User::factory()->create();

        $log = AuditService::logAction(
            $user,
            'user_login',
            'User logged in',
            'User',
            $user->id
        );

        $this->assertInstanceOf(AuditLog::class, $log);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'user_login',
            'description' => 'User logged in',
            'model_type' => 'User',
            'model_id' => $user->id,
        ]);
    }

    public function test_log_action_works_without_user(): void
    {
        $log = AuditService::logAction(
            null,
            'guest_action',
            'Guest did something'
        );

        $this->assertInstanceOf(AuditLog::class, $log);
        $this->assertNull($log->user_id);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'guest_action',
            'user_id' => null,
        ]);
    }

    public function test_log_action_captures_ip_and_user_agent(): void
    {
        $user = User::factory()->create();

        $log = AuditService::logAction($user, 'user_login', 'Login');

        // request()->ip() returns a value during tests (e.g. 127.0.0.1)
        $this->assertNotNull($log->ip_address);
    }

    public function test_log_action_stores_changes_array(): void
    {
        $user = User::factory()->create();
        $changes = ['total' => 150.50, 'items_count' => 3];

        $log = AuditService::logAction(
            $user,
            'order_created',
            'Order created',
            'Order',
            1,
            $changes
        );

        $this->assertEquals($changes, $log->changes);
        $this->assertEqualsWithDelta(150.50, $log->changes['total'], 0.001);
    }

    public function test_log_action_handles_order_metrics(): void
    {
        $user = User::factory()->create();

        $log = AuditService::logAction(
            $user,
            'order_created',
            'Order created',
            'Order',
            1,
            ['total' => 99.90]
        );

        $this->assertInstanceOf(AuditLog::class, $log);
        $this->assertDatabaseHas('audit_logs', ['action' => 'order_created']);
    }

    public function test_log_action_handles_cart_item_added_metric(): void
    {
        $user = User::factory()->create();

        $log = AuditService::logAction(
            $user,
            'cart_item_added',
            'Item added',
            'CartItem',
            1,
            ['quantity' => 2]
        );

        $this->assertInstanceOf(AuditLog::class, $log);
    }

    public function test_log_action_handles_user_registered_metric(): void
    {
        $user = User::factory()->create();

        $log = AuditService::logAction(
            $user,
            'user_registered',
            'User registered',
            'User',
            $user->id
        );

        $this->assertInstanceOf(AuditLog::class, $log);
    }
}
