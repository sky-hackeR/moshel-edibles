{{-- ========================================================================= --}}
{{-- ASSET AUDIT & STORAGE MATRIX ACCORDION                                   --}}
{{-- ========================================================================= --}}
<div class="card border-0 shadow-sm mt-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between cursor-pointer" data-bs-toggle="collapse" data-bs-target="#assetAuditCollapse" style="cursor: pointer;">
        <h6 class="card-title mb-0 fw-bold text-dark font-size-13">
            <i class="bx bx-list-check me-1 text-success"></i> Asset Audit & Storage Matrix
        </h6>
        <i class="bx bx-chevron-down font-size-16 text-muted"></i>
    </div>
    <div class="collapse show" id="assetAuditCollapse">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-nowrap align-middle mb-0 font-size-12">
                    <thead class="table-light">
                        <tr>
                            <th>Component</th>
                            <th>Storage Path</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">
                                <i class="bx bx-image-alt text-primary me-1"></i> Main Logo
                            </td>
                            <td class="text-muted font-monospace font-size-11">
                                {{ $setting?->logo ?? 'uploads/siteInfo/logo.png' }}
                            </td>
                            <td class="text-end">
                                @if($hasLogo)
                                    <span class="badge bg-success-subtle text-success">Installed</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Missing</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">
                                <i class="bx bx-globe text-info me-1"></i> Favicon
                            </td>
                            <td class="text-muted font-monospace font-size-11">
                                {{ $setting?->favicon ?? 'uploads/siteInfo/favicon.png' }}
                            </td>
                            <td class="text-end">
                                @if($hasFavicon)
                                    <span class="badge bg-success-subtle text-success">Installed</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Default</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">
                                <i class="bx bx-envelope text-warning me-1"></i> Email Alerts
                            </td>
                            <td class="text-muted font-size-11">
                                moshedib@moshedibles.com
                            </td>
                            <td class="text-end">
                                <span class="badge bg-success-subtle text-success">Verified</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">
                                <i class="bx bx-lock-alt text-secondary me-1"></i> Currency Standard
                            </td>
                            <td class="text-muted font-size-11">
                                Nigerian Naira (NGN ₦)
                            </td>
                            <td class="text-end">
                                <span class="badge bg-primary-subtle text-primary">Active</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
