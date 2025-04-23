<?php

namespace App\Listeners;

use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class LogSuccessfulLogin
{
    /**
     * Handle the login event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Check for any login entries in the last 5 seconds to prevent duplicates
        $recentLogin = AuditLog::where('user_id', $event->user->id)
            ->where('action', 'login')
            ->where('created_at', '>=', now()->subSeconds(5))
            ->exists();
            
        if (!$recentLogin) {
            AuditService::logLogin();
        }
    }
} 