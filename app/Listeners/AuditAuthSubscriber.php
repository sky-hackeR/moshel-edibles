<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Services\Audit\AuditLogger;

class AuditAuthSubscriber
{
    public function handleLogin(Login $event): void
    {
        $guard = $event->guard ?: 'web';
        $user = $event->user;
        $request = request();

        if ($user) {
            $session = AuditLogger::getOrCreateSession($request, $guard, $user);
            if ($session) {
                AuditLogger::log('Auth', 'Login', "{$user->name} logged into the " . ucfirst($guard) . " portal.", [
                    'guard' => $guard,
                    'user_id' => $user->id,
                    'email' => $user->email,
                ], $request);
            }
        }
    }

    public function handleLogout(Logout $event): void
    {
        $guard = $event->guard ?: 'web';
        $user = $event->user;
        $request = request();

        AuditLogger::endSession($request, $guard, $user);
    }

    public function handleFailed(Failed $event): void
    {
        $guard = $event->guard ?: 'web';
        $request = request();

        // Failed login doesn't have an authenticated user, so we don't force a session
        // but we can note it if desired
    }

    public function subscribe($events)
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
        ];
    }
}
