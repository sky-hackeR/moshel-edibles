@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Session Audit Trail</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Session Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Session Meta Card -->
    <div class="col-xl-4">
        <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 80px; z-index: 10;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-md me-3">
                        <span class="avatar-title rounded-circle {{ $auditSession->role_badge_class }} font-size-20 fw-bold">
                            {{ strtoupper(substr($auditSession->user_name ?? 'U', 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <h5 class="font-size-16 mb-1 text-dark fw-bold">{{ $auditSession->user_name }}</h5>
                        <p class="text-muted mb-0 font-size-13">{{ $auditSession->user_email ?: 'No email' }}</p>
                        <span class="badge {{ $auditSession->role_badge_class }} mt-1">
                            {{ ucfirst($auditSession->user_type) }} Portal
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-borderless font-size-13 mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted" style="width: 35%;">Status:</th>
                                <td>
                                    @if($auditSession->is_actually_active)
                                        <span class="badge bg-soft-success text-success fw-bold">
                                            <span class="spinner-grow spinner-grow-sm me-1" style="width: 7px; height: 7px;" role="status"></span> Active
                                        </span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">Ended</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Session ID:</th>
                                <td><code class="font-size-11">{{ $auditSession->session_id }}</code></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Device:</th>
                                <td><i class="bx bx-laptop me-1 text-muted"></i>{{ $auditSession->device ?: 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">IP Address:</th>
                                <td><i class="bx bx-globe me-1 text-muted"></i>{{ $auditSession->ip_address ?: '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Login Time:</th>
                                <td>{{ $auditSession->started_at ? $auditSession->started_at->format('M d, Y h:i:s A') : '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Last Active:</th>
                                <td>{{ $auditSession->last_activity_at ? $auditSession->last_activity_at->format('M d, Y h:i:s A') : '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Duration:</th>
                                <td><span class="badge bg-light text-dark">{{ $auditSession->duration }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Total Recorded:</th>
                                <td><strong class="text-primary">{{ number_format($auditSession->logs()->count()) }}</strong> total operations</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('admin.auditLogs.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Back to Audit Sessions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Timeline of Actions -->
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
                    <div>
                        <h5 class="card-title text-dark fw-bold mb-1">Chronological Activity Trail</h5>
                        <small class="text-muted">All requests and operations performed during this session</small>
                    </div>
                    <span class="badge bg-primary rounded-pill font-size-12 px-3 py-2">
                        {{ number_format($logs->total()) }} Actions Found
                    </span>
                </div>

                <!-- High Volume Performance Notice -->
                @if($logs->total() > 50)
                    <div class="alert alert-info py-2 px-3 font-size-12 mb-3 d-flex align-items-center justify-content-between">
                        <span>
                            <i class="bx bx-info-circle me-1"></i>
                            Showing <strong>{{ $logs->firstItem() }}</strong> to <strong>{{ $logs->lastItem() }}</strong> of <strong>{{ number_format($logs->total()) }}</strong> actions (paginated in batches of 50 for speed).
                        </span>
                    </div>
                @endif

                <!-- Search & Category Filters -->
                <form method="GET" action="{{ route('admin.auditLogs.show', $auditSession) }}" class="row g-2 mb-4">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search actions, descriptions, URLs..." value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select form-select-sm">
                            <option value="all" {{ $category === 'all' ? 'selected' : '' }}>All Modules / Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                        @if(!empty($search) || $category !== 'all')
                            <a href="{{ route('admin.auditLogs.show', $auditSession) }}" class="btn btn-sm btn-light" title="Reset">
                                <i class="bx bx-reset"></i>
                            </a>
                        @endif
                    </div>
                </form>

                @if($logs->isNotEmpty())
                    <div class="timeline" style="position: relative; padding-left: 32px;">
                        <style>
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
                                margin-bottom: 22px;
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
                                padding: 14px 18px;
                                border: 1px solid #edf2f7;
                            }
                            .bg-purple {
                                background-color: #6f42c1 !important;
                            }
                        </style>

                        @foreach($logs as $log)
                            <div class="timeline-item">
                                <div class="timeline-badge">
                                    <i class="bx bx-radio-circle-marked"></i>
                                </div>
                                <div class="timeline-content shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <span class="badge {{ $log->category_badge_class }} me-1">{{ $log->category }}</span>
                                            <strong class="text-dark font-size-15">{{ $log->action }}</strong>
                                        </div>
                                        <small class="text-muted font-size-12">
                                            <i class="bx bx-time me-1"></i>{{ $log->created_at ? $log->created_at->format('h:i:s A (M d, Y)') : '—' }}
                                        </small>
                                    </div>

                                    <p class="text-muted mb-2 font-size-13">{{ $log->description }}</p>

                                    <div class="d-flex justify-content-between align-items-center font-size-12 pt-2 border-top">
                                        <span class="text-muted">
                                            <span class="badge bg-light text-dark fw-bold">{{ $log->method }}</span>
                                            <code class="text-dark">{{ $log->url ? preg_replace('#^https?://[^/]+#i', '', $log->url) : '—' }}</code>
                                        </span>
                                        @if(!empty($log->details))
                                            <button class="btn btn-sm btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#logDetails_{{ $log->uuid ?? $log->id }}">
                                                <i class="bx bx-code-alt me-1"></i> View Payload Data
                                            </button>
                                        @endif
                                    </div>

                                    @if(!empty($log->details))
                                        <div class="collapse mt-3" id="logDetails_{{ $log->uuid ?? $log->id }}">
                                            <div class="card card-body bg-dark text-light p-3 mb-0 rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-secondary">
                                                    <small class="text-muted text-uppercase fw-bold font-size-11">Request Parameters & State</small>
                                                    <span class="badge bg-secondary font-size-10">{{ count($log->details) }} Keys</span>
                                                </div>
                                                <pre class="text-light mb-0 font-size-12" style="max-height: 250px; overflow-y: auto;"><code>{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <span class="text-muted font-size-13">
                                Page <strong>{{ $logs->currentPage() }}</strong> of <strong>{{ $logs->lastPage() }}</strong>
                            </span>
                            <div>
                                {{ $logs->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-search-alt text-muted font-size-28 mb-2"></i>
                        <p class="text-muted mb-0">No actions matched your filter in this session.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
