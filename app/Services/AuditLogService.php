<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class AuditLogService
{
    /**
     * Catat aksi penting ke audit_logs.
     */
    public function log(
        string $action,
        string $entityType,
        ?string $entityId = null,
        ?string $description = null,
    ): void {
        $userId = Auth::id();

        // Jika tidak ada user login (mis. dari seeder), skip.
        if (! $userId) {
            return;
        }

        AuditLog::create([
            'id' => (string) Str::uuid(),
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'created_at' => now(),
        ]);
    }
}