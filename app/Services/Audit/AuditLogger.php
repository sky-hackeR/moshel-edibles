<?php

namespace App\Services\Audit;

use App\Models\AuditSession;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuditLogger
{
    /**
     * Resolve the current portal/user based on route path and auth guards.
     * Prevents admin auth in one tab from polluting customer storefront logs.
     */
    public static function resolveCurrentUser(?Request $request = null): array
    {
        $request = $request ?: request();
        $path = $request ? trim($request->path(), '/') : '';

        // 1. Admin Portal Routes
        if (str_starts_with($path, 'admin')) {
            if (Auth::guard('admin')->check()) {
                return ['admin', Auth::guard('admin')->user()];
            }
            // Do NOT create guest admin sessions for unauthenticated admin login/assets
            return [null, null];
        }

        // 2. Staff Portal Routes
        if (str_starts_with($path, 'staff')) {
            if (Auth::guard('staff')->check()) {
                return ['staff', Auth::guard('staff')->user()];
            }
            // Do NOT create guest staff sessions
            return [null, null];
        }

        // 3. Storefront / Customer Routes (products, cart, checkout, account, contact, home)
        if (Auth::guard('customer')->check()) {
            return ['customer', Auth::guard('customer')->user()];
        }

        // Return customer guest for storefront
        return ['customer', null];
    }

    /**
     * Get or create an audit session with smart deduplication.
     */
    public static function getOrCreateSession(?Request $request = null, ?string $userType = null, $user = null): ?AuditSession
    {
        $request = $request ?: request();
        if (!$request || !$request->hasSession()) {
            return null;
        }

        if (!$userType) {
            [$userType, $user] = self::resolveCurrentUser($request);
        }

        if (!$userType) {
            return null;
        }

        $sessionId = $request->session()->getId();
        if (!$sessionId) {
            return null;
        }

        // 1. If authenticated user, prevent duplicate sessions by reusing existing active session
        if ($user && isset($user->id)) {
            $auditSession = AuditSession::where('user_type', $userType)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->where('last_activity_at', '>=', now()->subMinutes(60))
                ->latest('last_activity_at')
                ->first();

            if ($auditSession) {
                $auditSession->update([
                    'session_id' => $sessionId,
                    'last_activity_at' => now(),
                    'ip_address' => $request->ip(),
                ]);
                return $auditSession;
            }
        }

        // 2. Check by exact session ID and user type
        $auditSession = AuditSession::where('session_id', $sessionId)
            ->where('user_type', $userType)
            ->first();

        // 3. For guest storefront visitors: deduplicate within 30 minutes on same IP & Device
        if (!$auditSession && $userType === 'customer' && !$user) {
            $auditSession = AuditSession::where('user_type', 'customer')
                ->whereNull('user_id')
                ->where('is_active', true)
                ->where('ip_address', $request->ip())
                ->where('user_agent', Str::limit($request->userAgent() ?? '', 500))
                ->where('last_activity_at', '>=', now()->subMinutes(30))
                ->latest('last_activity_at')
                ->first();

            if ($auditSession) {
                $auditSession->update([
                    'session_id' => $sessionId,
                    'last_activity_at' => now(),
                ]);
                return $auditSession;
            }
        }

        // 3. If still no session, create one
        if (!$auditSession) {
            $userName = $user ? ($user->name ?? $user->email ?? 'User') : ($userType === 'customer' ? 'Store Visitor' : 'Guest');
            $userEmail = $user ? ($user->email ?? null) : null;
            $userId = $user ? $user->id : null;

            $auditSession = AuditSession::create([
                'session_id' => $sessionId,
                'user_type' => $userType,
                'user_id' => $userId,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit($request->userAgent() ?? '', 500),
                'device' => self::parseDevice($request->userAgent()),
                'started_at' => now(),
                'last_activity_at' => now(),
                'is_active' => true,
            ]);
        } else {
            // If session was guest and now has authenticated user, enrich it
            $updates = [
                'last_activity_at' => now(),
                'is_active' => true,
            ];

            if ($user && isset($user->id) && !$auditSession->user_id) {
                $updates['user_id'] = $user->id;
                $updates['user_name'] = $user->name ?? $user->email ?? $auditSession->user_name;
                $updates['user_email'] = $user->email ?? $auditSession->user_email;
            }

            $auditSession->update($updates);
        }

        return $auditSession;
    }

    /**
     * Enrich a guest session with customer contact information (e.g. from checkout or inquiry form).
     */
    public static function enrichSessionContact(?Request $request, string $name, ?string $email = null): void
    {
        try {
            $request = $request ?: request();
            if (!$request || !$request->hasSession()) {
                return;
            }

            $sessionId = $request->session()->getId();
            $session = AuditSession::where('session_id', $sessionId)
                ->where('user_type', 'customer')
                ->latest('id')
                ->first();

            if ($session) {
                $session->update([
                    'user_name' => $name,
                    'user_email' => $email ?: $session->user_email,
                    'last_activity_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Log an action to the audit trail.
     */
    public static function log(
        string $category,
        string $action,
        ?string $description = null,
        array $details = [],
        ?Request $request = null
    ): ?AuditLog {
        try {
            $request = $request ?: request();
            [$userType, $user] = self::resolveCurrentUser($request);

            if (!$userType) {
                return null;
            }

            $auditSession = self::getOrCreateSession($request, $userType, $user);
            if (!$auditSession) {
                return null;
            }

            // Sanitize sensitive values
            $cleanDetails = self::sanitizeDetails($details);

            $log = AuditLog::create([
                'audit_session_id' => $auditSession->id,
                'user_type' => $userType,
                'user_id' => $user ? $user->id : null,
                'category' => $category,
                'action' => $action,
                'description' => $description ?: $action,
                'method' => $request ? $request->method() : 'GET',
                'url' => $request ? Str::limit($request->fullUrl(), 500) : null,
                'route_name' => $request && $request->route() ? $request->route()->getName() : null,
                'ip_address' => $request ? $request->ip() : null,
                'details' => !empty($cleanDetails) ? $cleanDetails : null,
                'created_at' => now(),
            ]);

            $auditSession->update(['last_activity_at' => now()]);

            return $log;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * End session on logout.
     */
    public static function endSession(?Request $request = null, ?string $userType = null, $user = null): void
    {
        try {
            $request = $request ?: request();
            $sessionId = $request && $request->hasSession() ? $request->session()->getId() : null;

            // Find sessions to end: by authenticated user_id if present, or by session_id
            $query = AuditSession::where('is_active', true);
            if ($user && isset($user->id)) {
                $query->where('user_id', $user->id);
                if ($userType) {
                    $query->where('user_type', $userType);
                }
            } elseif ($sessionId) {
                $query->where('session_id', $sessionId);
                if ($userType) {
                    $query->where('user_type', $userType);
                }
            } else {
                return;
            }

            $sessions = $query->get();
            foreach ($sessions as $session) {
                $session->update([
                    'is_active' => false,
                    'last_activity_at' => now(),
                ]);

                AuditLog::create([
                    'audit_session_id' => $session->id,
                    'user_type' => $session->user_type,
                    'user_id' => $session->user_id,
                    'category' => 'Auth',
                    'action' => 'Logout',
                    'description' => "{$session->user_name} logged out.",
                    'method' => $request ? $request->method() : 'POST',
                    'url' => $request ? Str::limit($request->fullUrl(), 500) : null,
                    'route_name' => $request && $request->route() ? $request->route()->getName() : null,
                    'ip_address' => $request ? $request->ip() : null,
                    'created_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Sanitize input payload so sensitive keys are never recorded in plain text.
     */
    public static function sanitizeDetails(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'pin', '_token', 'token', 'secret', 'credit_card', 'card_number', 'cvv'];

        $cleaned = [];
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys, true)) {
                $cleaned[$key] = '[HIDDEN]';
            } elseif (is_array($value)) {
                $cleaned[$key] = self::sanitizeDetails($value);
            } elseif (is_string($value) && strlen($value) > 500) {
                $cleaned[$key] = Str::limit($value, 500);
            } else {
                $cleaned[$key] = $value;
            }
        }

        return $cleaned;
    }

    /**
     * Parse User Agent into a human-readable Device/Browser string.
     */
    public static function parseDevice(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        $platform = 'Unknown OS';
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        $browser = 'Unknown Browser';
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        return "{$browser} on {$platform}";
    }
}
