<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Models\AuditSession;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit sessions and activity.
     */
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');
        $status = $request->query('status', 'all');
        $search = trim($request->query('search', ''));
        $date = $request->query('date');

        $nowMinus30 = Carbon::now()->subMinutes(30);

        // Base query for sessions
        $query = AuditSession::withCount('logs')->latest('last_activity_at');

        if (in_array($role, ['admin', 'staff', 'customer'], true)) {
            $query->where('user_type', $role);
        }

        if ($status === 'active') {
            $query->where('is_active', true)->where('last_activity_at', '>=', $nowMinus30);
        } elseif ($status === 'ended') {
            $query->where(function ($q) use ($nowMinus30) {
                $q->where('is_active', false)
                  ->orWhere('last_activity_at', '<', $nowMinus30);
            });
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('user_email', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('device', 'like', "%{$search}%")
                  ->orWhere('session_id', 'like', "%{$search}%")
                  ->orWhereHas('logs', function ($logQ) use ($search) {
                      $logQ->where('action', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%")
                           ->orWhere('category', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($date)) {
            $query->whereDate('started_at', Carbon::parse($date));
        }

        $sessions = $query->paginate(20)->withQueryString();

        // Calculate summary statistics
        $stats = [
            'total_sessions' => AuditSession::count(),
            'active_sessions' => AuditSession::where('is_active', true)->where('last_activity_at', '>=', $nowMinus30)->count(),
            'total_actions' => AuditLog::count(),
            'admin_sessions' => AuditSession::where('user_type', 'admin')->count(),
            'staff_sessions' => AuditSession::where('user_type', 'staff')->count(),
            'customer_sessions' => AuditSession::where('user_type', 'customer')->count(),
        ];

        return view('admin.erp.auditLogs.index', compact('sessions', 'stats', 'role', 'status', 'search', 'date'));
    }

    /**
     * Display detailed activity for a specific session with high-volume pagination support.
     */
    public function show(Request $request, AuditSession $auditSession)
    {
        $search = trim($request->query('search', ''));
        $category = $request->query('category', 'all');

        $logsQuery = $auditSession->logs();

        if ($category !== 'all' && !empty($category)) {
            $logsQuery->where('category', $category);
        }

        if (!empty($search)) {
            $logsQuery->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // For AJAX Modal preview: Fetch top 50 to keep JSON response fast and light
        if ($request->ajax() || $request->wantsJson()) {
            $totalCount = $auditSession->logs()->count();
            $recentLogs = (clone $logsQuery)->latest('id')->take(50)->get()->reverse()->values();

            return response()->json([
                'success' => true,
                'session' => [
                    'id' => $auditSession->id,
                    'uuid' => $auditSession->uuid,
                    'session_id' => $auditSession->session_id,
                    'user_type' => ucfirst($auditSession->user_type),
                    'user_type_raw' => $auditSession->user_type,
                    'badge_class' => $auditSession->role_badge_class,
                    'user_name' => $auditSession->user_name,
                    'user_email' => $auditSession->user_email,
                    'ip_address' => $auditSession->ip_address,
                    'device' => $auditSession->device,
                    'started_at' => $auditSession->started_at ? $auditSession->started_at->format('M d, Y h:i:s A') : '—',
                    'last_activity_at' => $auditSession->last_activity_at ? $auditSession->last_activity_at->format('M d, Y h:i:s A') : '—',
                    'duration' => $auditSession->duration,
                    'is_active' => $auditSession->is_active,
                    'logs_count' => $totalCount,
                    'is_truncated' => $totalCount > 50,
                ],
                'logs' => $recentLogs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'uuid' => $log->uuid,
                        'category' => $log->category,
                        'category_badge' => $log->category_badge_class,
                        'action' => $log->action,
                        'description' => $log->description,
                        'method' => $log->method,
                        'url' => $log->url,
                        'ip_address' => $log->ip_address,
                        'details' => $log->details,
                        'time' => $log->created_at ? $log->created_at->format('h:i:s A') : '—',
                        'datetime' => $log->created_at ? $log->created_at->format('M d, Y h:i:s A') : '—',
                    ];
                }),
            ]);
        }

        // For Full Page view: Paginate 50 items per page with category and search filtering
        $logs = $logsQuery->latest('id')->paginate(50)->withQueryString();
        $categories = AuditLog::where('audit_session_id', $auditSession->id)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('admin.erp.auditLogs.show', compact('auditSession', 'logs', 'categories', 'search', 'category'));
    }

    /**
     * Get details for an individual log entry.
     */
    public function logDetails(AuditLog $auditLog)
    {
        return response()->json([
            'success' => true,
            'log' => [
                'id' => $auditLog->id,
                'action' => $auditLog->action,
                'category' => $auditLog->category,
                'description' => $auditLog->description,
                'method' => $auditLog->method,
                'url' => $auditLog->url,
                'ip_address' => $auditLog->ip_address,
                'details' => $auditLog->details,
                'created_at' => $auditLog->created_at ? $auditLog->created_at->format('M d, Y h:i:s A') : '—',
            ],
        ]);
    }
}
