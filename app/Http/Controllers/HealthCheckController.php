<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthCheckController extends Controller
{
    public function check(): JsonResponse
    {
        $status = 'healthy';
        $checks = [];

        // Check database
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'connected';
        } catch (Throwable $e) {
            $status = 'unhealthy';
            $checks['database'] = 'disconnected';
        }

        // Check app storage writability
        $checks['storage'] = is_writable(storage_path()) ? 'writable' : 'read-only';
        if ($checks['storage'] !== 'writable') {
            $status = 'degraded';
        }

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'app' => config('app.name'),
            'checks' => $checks,
        ], $status === 'healthy' ? 200 : 503);
    }
}
