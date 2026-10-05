<?php

namespace App\Listeners;

use App\Services\AuditLogService;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

    public function handle(Login $event): void
    {
        $this->audit->log(
            'login',
            'user',
            $event->user->id,
            "User '{$event->user->username}' logged in"
        );
    }
}