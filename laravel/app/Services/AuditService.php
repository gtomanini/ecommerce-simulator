<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditService
{
    public static function logAction(
        ?User $user,
        string $action,
        string $description,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $changes = null
    ): AuditLog {
        try {
            $request = request();

            $auditLog = AuditLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'description' => $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'changes' => $changes,
            ]);

            // Log Prometheus metrics
            self::recordMetric($action, $changes);

            return $auditLog;
        } catch (\Exception $e) {
            Log::error('Failed to create audit log', [
                'error' => $e->getMessage(),
                'action' => $action,
            ]);

            return null;
        }
    }

    private static function recordMetric(string $action, ?array $changes = null): void
    {
        try {
            // Record action count
            $prometheusFile = storage_path('prometheus_metrics.txt');

            // Increment counter based on action
            self::incrementCounter($prometheusFile, 'audit_actions_total', $action);

            // Record specific metrics
            match ($action) {
                'user_registered' => self::incrementCounter($prometheusFile, 'users_registered_total'),
                'user_login' => self::incrementCounter($prometheusFile, 'user_logins_total'),
                'order_created' => self::recordOrderMetrics($prometheusFile, $changes),
                'cart_item_added' => self::incrementCounter($prometheusFile, 'cart_items_added_total'),
                default => null,
            };
        } catch (\Exception $e) {
            Log::debug('Failed to record Prometheus metric', ['error' => $e->getMessage()]);
        }
    }

    private static function incrementCounter(string $file, string $metric, ?string $label = null): void
    {
        $content = file_exists($file) ? file_get_contents($file) : '';

        if ($label) {
            $pattern = "/^{$metric}\{[^}]*label=\"{$label}\"[^}]*\}\s+(\d+(?:\.\d+)?)/m";
            if (preg_match($pattern, $content, $matches)) {
                $newValue = (int) $matches[1] + 1;
                $content = preg_replace($pattern, "{$metric}{action=\"{$label}\"} {$newValue}", $content);
            } else {
                $content .= "{$metric}{action=\"{$label}\"} 1\n";
            }
        } else {
            $pattern = "/^{$metric}\s+(\d+(?:\.\d+)?)/m";
            if (preg_match($pattern, $content, $matches)) {
                $newValue = (int) $matches[1] + 1;
                $content = preg_replace($pattern, "{$metric} {$newValue}", $content);
            } else {
                $content .= "{$metric} 1\n";
            }
        }

        file_put_contents($file, $content);
    }

    private static function recordOrderMetrics(string $file, ?array $changes): void
    {
        self::incrementCounter($file, 'orders_created_total');

        if ($changes && isset($changes['total'])) {
            $content = file_exists($file) ? file_get_contents($file) : '';
            $revenue = (float) $changes['total'];

            $pattern = '/^orders_revenue_total\s+(\d+(?:\.\d+)?)/m';
            if (preg_match($pattern, $content, $matches)) {
                $newValue = (float) $matches[1] + $revenue;
                $content = preg_replace($pattern, "orders_revenue_total {$newValue}", $content);
            } else {
                $content .= "orders_revenue_total {$revenue}\n";
            }

            file_put_contents($file, $content);
        }
    }
}
