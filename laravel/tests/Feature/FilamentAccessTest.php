<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_panel_only_for_admins(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $regular = User::factory()->create(['is_admin' => false]);

        $panel = filament()->getPanel('admin');

        $this->assertTrue($admin->canAccessPanel($panel));
        $this->assertFalse($regular->canAccessPanel($panel));
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_is_forbidden(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }

    public function test_admin_user_can_access_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertSuccessful();
    }

    public function test_admin_can_manage_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get('/admin/users')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/users/create')->assertSuccessful();
    }

    public function test_admin_can_open_product_edit_with_images(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $product = \App\Models\Product::factory()->create();

        // The product edit page hosts the images relation manager.
        $this->actingAs($admin)->get("/admin/products/{$product->id}/edit")->assertSuccessful();
    }
}
