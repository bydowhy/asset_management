<?php

namespace App\Listeners;

use App\Services\AuditLogService;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

    public function handle(Logout $event): void
    {
        if (! $event->user) return;

        $this->audit->log(
            'logout',
            'user',
            $event->user->id,
            "User '{$event->user->username}' logged out"
        );
    }
}