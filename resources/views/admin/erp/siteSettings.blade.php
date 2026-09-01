@extends('admin.layout.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">System Configuration</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Site Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-5">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0">Update Site Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/updateSiteInfo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="site_info_id" value="{{ $setting?->id }}">

                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" class="form-control" name="site_name" value="{{ old('site_name', $setting?->site_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Site Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $setting?->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Change Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                <small class="text-muted">Recommended: 200x50px</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Change Favicon</label>
                                <input type="file" class="form-control" name="favicon" accept="image/*">
                                <small class="text-muted">Recommended: 32x32px</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-save me-1"></i> Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card border shadow-none">
            <div class="card-header bg-transparent border-bottom text-primary">
                <h5 class="card-title mb-0 font-weight-bold">Current Info</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Site Name</th>
                                <td class="text-dark font-weight-medium">{{ $setting?->site_name ?? 'Not Set' }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td class="text-muted">{!! $setting?->description ?? 'No description provided.' !!}</td>
                            </tr>
                            <tr>
                                <th>Branding</th>
                                <td>
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="text-center">
                                            <p class="mb-1 small text-muted">Logo</p>
                                            @if($setting?->logo)
                                                <div class="p-2 border rounded bg-light">
                                                    <img src="{{ asset($setting->logo) }}" alt="Logo" style="max-height: 40px;">
                                                </div>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">Missing</span>
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            <p class="mb-1 small text-muted">Favicon</p>
                                            @if($setting?->favicon)
                                                <img src="{{ asset($setting->favicon) }}" alt="Favicon" class="rounded" style="width: 32px; height: 32px; object-fit: contain;">
                                            @else
                                                <span class="badge bg-soft-danger text-danger">Missing</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @if(!$setting || empty($setting->logo) || empty($setting->favicon))
                    <div class="alert alert-soft-warning mt-4 mb-0 border-dashed" role="alert">
                        <i class="bx bx-error-circle me-2"></i> 
                        <strong>Attention:</strong> Some branding assets are missing. Dashboard access is currently restricted.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection