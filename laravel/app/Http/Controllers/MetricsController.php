<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Response;

class MetricsController extends Controller
{
    public function metrics(): Response
    {
        $metrics = $this->generateMetrics();

        return response($metrics, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    private function generateMetrics(): string
    {
        $metrics = "# HELP audit_actions_total Total audit actions\n";
        $metrics .= "# TYPE audit_actions_total counter\n";

        // Count actions by type
        $actions = AuditLog::select('action')
            ->distinct()
            ->pluck('action');

        foreach ($actions as $action) {
            $count = AuditLog::where('action', $action)->count();
            $metrics .= "audit_actions_total{action=\"{$action}\"} {$count}\n";
        }

        // Users registered
        $usersRegistered = AuditLog::where('action', 'user_registered')->count();
        $metrics .= "\n# HELP users_registered_total Total registered users\n";
        $metrics .= "# TYPE users_registered_total counter\n";
        $metrics .= "users_registered_total {$usersRegistered}\n";

        // User logins
        $userLogins = AuditLog::where('action', 'user_login')->count();
        $metrics .= "\n# HELP user_logins_total Total user logins\n";
        $metrics .= "# TYPE user_logins_total counter\n";
        $metrics .= "user_logins_total {$userLogins}\n";

        // Orders created
        $ordersCreated = AuditLog::where('action', 'order_created')->count();
        $metrics .= "\n# HELP orders_created_total Total orders created\n";
        $metrics .= "# TYPE orders_created_total counter\n";
        $metrics .= "orders_created_total {$ordersCreated}\n";

        // Total revenue
        $totalRevenue = AuditLog::where('action', 'order_created')
            ->whereNotNull('changes')
            ->get()
            ->sum(function ($log) {
                $changes = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
                return $changes['total'] ?? 0;
            });

        $metrics .= "\n# HELP orders_revenue_total Total revenue from orders\n";
        $metrics .= "# TYPE orders_revenue_total gauge\n";
        $metrics .= "orders_revenue_total {$totalRevenue}\n";

        // Cart items added
        $cartItemsAdded = AuditLog::where('action', 'cart_item_added')->count();
        $metrics .= "\n# HELP cart_items_added_total Total cart items added\n";
        $metrics .= "# TYPE cart_items_added_total counter\n";
        $metrics .= "cart_items_added_total {$cartItemsAdded}\n";

        return $metrics;
    }
}
