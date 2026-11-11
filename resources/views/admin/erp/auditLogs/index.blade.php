@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Audit &amp; Activity Log</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Audit Log</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Total Sessions</p>
                        <h3 class="mb-0 text-dark fw-bold">{{ number_format($stats['total_sessions']) }}</h3>
                        <small class="text-muted">Recorded user sessions</small>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle bg-soft-white text-white font-size-22">
                            <i class="bx bx-time-five"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Active Now</p>
                        <h3 class="mb-0 text-success fw-bold">
                            <span class="spinner-grow spinner-grow-sm text-success me-1" role="status"></span>
                            {{ number_format($stats['active_sessions']) }}
                        </h3>
                        <small class="text-muted">Live connected sessions</small>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle bg-soft-white text-white font-size-22">
                            <i class="bx bx-pulse"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Total Actions Logged</p>
                        <h3 class="mb-0 text-primary fw-bold">{{ number_format($stats['total_actions']) }}</h3>
                        <small class="text-muted">Operations & requests</small>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle bg-soft-white text-white font-size-22">
                            <i class="bx bx-list-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Sessions by Portal</p>
                        <div class="d-flex gap-2 align-items-center mt-2">
                            <span class="badge bg-danger">Admin: {{ $stats['admin_sessions'] }}</span>
                            <span class="badge bg-primary">Staff: {{ $stats['staff_sessions'] }}</span>
                            <span class="badge bg-success">Customer: {{ $stats['customer_sessions'] }}</span>
                        </div>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle bg-soft-white text-white font-size-22">
                            <i class="bx bx-shield-quarter"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Role Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $role === 'all' ? 'active' : '' }}" href="{{ route('admin.auditLogs.index', array_merge(request()->query(), ['role' => 'all'])) }}">
                    <i class="bx bx-grid-alt me-1"></i> All Sessions
                    <span class="badge bg-secondary rounded-pill ms-1">{{ $stats['total_sessions'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $role === 'admin' ? 'active' : '' }}" href="{{ route('admin.auditLogs.index', array_merge(request()->query(), ['role' => 'admin'])) }}">
                    <i class="bx bx-shield text-danger me-1"></i> Admin Sessions
                    <span class="badge bg-danger rounded-pill ms-1">{{ $stats['admin_sessions'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $role === 'staff' ? 'active' : '' }}" href="{{ route('admin.auditLogs.index', array_merge(request()->query(), ['role' => 'staff'])) }}">
                    <i class="bx bx-user-check text-primary me-1"></i> Staff Sessions
                    <span class="badge bg-primary rounded-pill ms-1">{{ $stats['staff_sessions'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $role === 'customer' ? 'active' : '' }}" href="{{ route('admin.auditLogs.index', array_merge(request()->query(), ['role' => 'customer'])) }}">
                    <i class="bx bx-cart text-success me-1"></i> Customer Sessions
                    <span class="badge bg-success rounded-pill ms-1">{{ $stats['customer_sessions'] }}</span>
                </a>
            </li>
        </ul>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('admin.auditLogs.index') }}" class="row g-2 mb-4 align-items-center">
            <input type="hidden" name="role" value="{{ $role }}">
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, IP, device, action..." value="{{ $search }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Sessions Only</option>
                    <option value="ended" {{ $status === 'ended' ? 'selected' : '' }}>Ended Sessions Only</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ $date }}" title="Filter by Started Date">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                @if(!empty($search) || $status !== 'all' || !empty($date) || $role !== 'all')
                    <a href="{{ route('admin.auditLogs.index') }}" class="btn btn-light" title="Reset Filters">
                        <i class="bx bx-reset"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Sessions Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Portal / Role</th>
                        <th>Device & IP</th>
                        <th>Started At</th>
                        <th>Last Active</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th class="text-end">Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs me-2">
                                        <span class="avatar-title rounded-circle {{ $session->role_badge_class }} font-size-12 fw-bold">
                                            {{ strtoupper(substr($session->user_name ?? 'U', 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold">{{ $session->user_name }}</h5>
                                        <small class="text-muted">{{ $session->user_email ?: 'No email' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($session->user_type === 'admin')
                                    <span class="badge bg-danger"><i class="bx bx-shield me-1"></i>Admin</span>
                                @elseif($session->user_type === 'staff')
                                    <span class="badge bg-primary"><i class="bx bx-user-check me-1"></i>Staff</span>
                                @else
                                    <span class="badge bg-success"><i class="bx bx-cart me-1"></i>Customer</span>
                                @endif
                            </td>
                            <td>
                                <span class="d-block text-dark font-size-13"><i class="bx bx-laptop text-muted me-1"></i>{{ $session->device ?: 'Unknown' }}</span>
                                <small class="text-muted"><i class="bx bx-globe text-muted me-1"></i>{{ $session->ip_address ?: '—' }}</small>
                            </td>
                            <td>
                                <span class="d-block font-size-13 text-dark">{{ $session->started_at ? $session->started_at->format('M d, Y') : '—' }}</span>
                                <small class="text-muted">{{ $session->started_at ? $session->started_at->format('h:i:s A') : '—' }}</small>
                            </td>
                            <td>
                                <span class="d-block font-size-13 text-dark">{{ $session->last_activity_at ? $session->last_activity_at->diffForHumans() : '—' }}</span>
                                <small class="text-muted">{{ $session->last_activity_at ? $session->last_activity_at->format('h:i:s A') : '—' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-size-12">{{ $session->duration }}</span>
                            </td>
                            <td>
                                @if($session->is_actually_active)
                                    <span class="badge bg-soft-success text-success fw-bold px-2 py-1">
                                        <span class="spinner-grow spinner-grow-sm me-1" style="width: 7px; height: 7px;" role="status"></span> Active
                                    </span>
                                @else
                                    <span class="badge bg-soft-secondary text-secondary fw-medium px-2 py-1">Ended</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-soft-info text-info font-size-12 fw-bold">
                                    <i class="bx bx-check-double me-1"></i>{{ $session->logs_count }} {{ Str::plural('action', $session->logs_count) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect view-session-btn"
                                    data-session-id="{{ $session->id }}"
                                    data-session-uuid="{{ $session->uuid }}"
                                    data-session-url="{{ route('admin.auditLogs.show', $session) }}">
                                    <i class="bx bx-detail me-1"></i> View Activity
                                </button>
                                <a href="{{ route('admin.auditLogs.show', $session) }}" class="btn btn-sm btn-light" title="Full Page View">
                                    <i class="bx bx-link-external"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-secondary text-secondary font-size-28">
                                        <i class="bx bx-shield-x"></i>
                                    </span>
                                </div>
                                <h5 class="text-dark">No Audit Sessions Found</h5>
                                <p class="text-muted mb-0">No user sessions matched your selected criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted font-size-13">
                    Showing <strong>{{ $sessions->firstItem() }}</strong> to <strong>{{ $sessions->lastItem() }}</strong> of <strong>{{ $sessions->total() }}</strong> sessions
                </div>
                <div>
                    {{ $sessions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Interactive Activity Timeline Modal -->
<div class="modal fade" id="sessionActivityModal" tabindex="-1" aria-labelledby="sessionActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="sessionActivityModalLabel">
                        <i class="bx bx-history text-primary me-1"></i> Session Activity Log
                    </h5>
                    <small class="text-muted" id="modalSessionSubtitle">Loading session details...</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Session Meta Header in Modal -->
            <div class="p-3 bg-white border-bottom" id="modalSessionMeta" style="display:none;">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" id="modalRoleBadge">Role</span>
                            <strong class="text-dark" id="modalUserName">User</strong>
                            <span class="text-muted font-size-12" id="modalUserEmail">email</span>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="badge bg-light text-dark me-2" id="modalDevice">Device</span>
                        <span class="badge bg-light text-dark" id="modalIp">IP</span>
                    </div>
                    <div class="col-12 mt-2 pt-2 border-top d-flex justify-content-between text-muted font-size-12">
                        <span><strong>Started:</strong> <span id="modalStartedAt">—</span></span>
                        <span><strong>Last Active:</strong> <span id="modalLastActive">—</span></span>
                        <span><strong>Duration:</strong> <span id="modalDuration">—</span></span>
                    </div>
                </div>
            </div>

            <div class="modal-body p-4">
                <!-- Loading Spinner -->
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Retrieving audit timeline...</p>
                </div>

                <!-- Timeline Container -->
                <div id="modalTimeline" style="display:none;">
                    <div id="modalTimelineNotice" class="alert alert-info py-2 px-3 font-size-12 mb-3 d-flex align-items-center justify-content-between" style="display:none;">
                        <span id="modalTimelineNoticeText"></span>
                        <a href="#" id="modalTimelineNoticeLink" class="btn btn-sm btn-outline-info font-size-11 ms-2">View All</a>
                    </div>
                    <div class="timeline" id="timelineList"></div>
                </div>

                <!-- Empty Timeline -->
                <div id="modalEmpty" class="text-center py-5" style="display:none;">
                    <i class="bx bx-info-circle text-muted font-size-28 mb-2"></i>
                    <p class="text-muted mb-0">No actions recorded for this session yet.</p>
                </div>
            </div>

            <div class="modal-footer bg-light d-flex justify-content-between">
                <a href="#" id="modalFullPageLink" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-link-external me-1"></i> Open Full Audit Page
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Clean Vertical Timeline Styling */
.timeline {
    position: relative;
    padding-left: 32px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 6px;
    bottom: 6px;
    width: 2px;
    background: #e9ecef;
}
.timeline-item {
    position: relative;
    margin-bottom: 24px;
}
.timeline-item:last-child {
    margin-bottom: 0;
}
.timeline-badge {
    position: absolute;
    left: -32px;
    top: 2px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #556ee6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: #556ee6;
}
.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px 16px;
    border: 1px solid #edf2f7;
}
.bg-purple {
    background-color: #6f42c1 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activityModal = new bootstrap.Modal(document.getElementById('sessionActivityModal'));
    const modalSubtitle = document.getElementById('modalSessionSubtitle');
    const modalMeta = document.getElementById('modalSessionMeta');
    const modalLoading = document.getElementById('modalLoading');
    const modalTimeline = document.getElementById('modalTimeline');
    const modalEmpty = document.getElementById('modalEmpty');
    const timelineList = document.getElementById('timelineList');
    const modalFullPageLink = document.getElementById('modalFullPageLink');

    const modalRoleBadge = document.getElementById('modalRoleBadge');
    const modalUserName = document.getElementById('modalUserName');
    const modalUserEmail = document.getElementById('modalUserEmail');
    const modalDevice = document.getElementById('modalDevice');
    const modalIp = document.getElementById('modalIp');
    const modalStartedAt = document.getElementById('modalStartedAt');
    const modalLastActive = document.getElementById('modalLastActive');
    const modalDuration = document.getElementById('modalDuration');
    const modalTimelineNotice = document.getElementById('modalTimelineNotice');
    const modalTimelineNoticeText = document.getElementById('modalTimelineNoticeText');
    const modalTimelineNoticeLink = document.getElementById('modalTimelineNoticeLink');

    document.querySelectorAll('.view-session-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const sessionUrl = this.getAttribute('data-session-url');
            
            // Reset view
            modalMeta.style.display = 'none';
            modalTimeline.style.display = 'none';
            modalEmpty.style.display = 'none';
            modalLoading.style.display = 'block';
            timelineList.innerHTML = '';
            modalSubtitle.textContent = 'Loading activity timeline...';
            modalFullPageLink.href = sessionUrl;

            activityModal.show();

            fetch(sessionUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                modalLoading.style.display = 'none';

                if (!data.success) {
                    modalSubtitle.textContent = 'Failed to load session details';
                    return;
                }

                const s = data.session;
                modalSubtitle.textContent = `Session ID: ${s.session_id}`;
                modalRoleBadge.className = `badge ${s.badge_class}`;
                modalRoleBadge.textContent = s.user_type;
                modalUserName.textContent = s.user_name;
                modalUserEmail.textContent = s.user_email ? `(${s.user_email})` : '';
                modalDevice.textContent = s.device;
                modalIp.textContent = s.ip_address;
                modalStartedAt.textContent = s.started_at;
                modalLastActive.textContent = s.last_activity_at;
                modalDuration.textContent = s.duration;
                modalMeta.style.display = 'block';

                if (s.is_truncated) {
                    modalTimelineNotice.style.display = 'flex';
                    modalTimelineNoticeText.innerHTML = `<i class="bx bx-info-circle me-1"></i> Showing <strong>50 most recent actions</strong> of <strong>${s.logs_count.toLocaleString()}</strong>. Open full page to filter and view all.`;
                    modalTimelineNoticeLink.href = sessionUrl;
                } else {
                    modalTimelineNotice.style.display = 'none';
                }

                if (!data.logs || data.logs.length === 0) {
                    modalEmpty.style.display = 'block';
                    return;
                }

                modalTimeline.style.display = 'block';
                timelineList.innerHTML = data.logs.map((log, index) => {
                    const hasDetails = log.details && Object.keys(log.details).length > 0;
                    const detailsId = `logDetails_${log.id}_${index}`;
                    const detailsJson = hasDetails ? JSON.stringify(log.details, null, 2) : '';

                    return `
                        <div class="timeline-item">
                            <div class="timeline-badge">
                                <i class="bx bx-radio-circle-marked"></i>
                            </div>
                            <div class="timeline-content shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="badge ${log.category_badge} me-1">${log.category}</span>
                                        <strong class="text-dark font-size-14">${log.action}</strong>
                                    </div>
                                    <small class="text-muted font-size-12"><i class="bx bx-time me-1"></i>${log.time}</small>
                                </div>
                                <p class="text-muted mb-2 font-size-13">${log.description || ''}</p>
                                <div class="d-flex justify-content-between align-items-center font-size-12 pt-2 border-top">
                                    <span class="text-muted">
                                        <span class="badge bg-light text-dark">${log.method}</span>
                                        <code>${log.url ? log.url.replace(/^https?:\/\/[^\/]+/i, '') : '—'}</code>
                                    </span>
                                    ${hasDetails ? `
                                        <button class="btn btn-sm btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#${detailsId}">
                                            <i class="bx bx-code-alt me-1"></i> Payload Details
                                        </button>
                                    ` : ''}
                                </div>
                                ${hasDetails ? `
                                    <div class="collapse mt-2" id="${detailsId}">
                                        <pre class="bg-dark text-light p-2 rounded font-size-11 mb-0" style="max-height: 200px; overflow-y: auto;"><code>${detailsJson}</code></pre>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
            })
            .catch(err => {
                modalLoading.style.display = 'none';
                modalSubtitle.textContent = 'Error loading session activity: ' + err.message;
            });
        });
    });
});
</script>

@endsection
