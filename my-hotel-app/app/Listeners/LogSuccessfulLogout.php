<?php

namespace App\Listeners;

use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Handle the logout event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if ($event->user) {
            // Check for any logout entries in the last 5 seconds to prevent duplicates
            $recentLogout = AuditLog::where('user_id', $event->user->id)
                ->where('action', 'logout')
                ->where('created_at', '>=', now()->subSeconds(5))
                ->exists();
                
            if (!$recentLogout) {
                AuditService::logLogout($event->user->id);
            }
        }
    }
} 