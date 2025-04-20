<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Login;

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
        AuditService::logLogin();
    }
} 