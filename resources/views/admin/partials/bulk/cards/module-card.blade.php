<div class="col-xl-6 col-lg-6">

    <div class="card border">

        <div class="card-body">

            <div class="d-flex align-items-center">

                <div class="avatar-sm me-3">

                    <span class="avatar-title rounded-circle bg-soft-{{ $color }} text-{{ $color }}">

                        <i class="{{ $icon }} font-size-22"></i>

                    </span>

                </div>

                <div class="flex-grow-1">

                    <h5 class="mb-1">
                        {{ $title }}
                    </h5>

                    <p class="text-muted mb-0">
                        {{ $description }}
                    </p>

                </div>

            </div>

            <hr>

            {{-- Information --}}
            <div class="mb-3">

                <div class="d-flex justify-content-between mb-2">

                    <small class="text-muted">
                        <i class="bx bx-file me-1"></i>
                        Format
                    </small>

                    <span class="fw-semibold">
                        {{ $format ?? '.xlsx' }}
                    </span>

                </div>

                <div class="d-flex justify-content-between">

                    <small class="text-muted">
                        <i class="bx bx-history me-1"></i>
                        {{ $type == 'import' ? 'Last Import' : 'Last Export' }}
                    </small>

                    <span class="fw-semibold">
                        {{ $type == 'import'
                            ? ($lastImport ?? 'Never')
                            : ($lastExport ?? 'Never') }}
                    </span>

                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center">

                <small class="text-muted">
                    {{ $note }}
                </small>

                @if($type == 'import')

                    <div class="btn-group">

                        @isset($template)

                            <a href="{{ $template }}"
                               class="btn btn-soft-secondary btn-sm">

                                <i class="bx bx-download"></i>

                                Download Template

                            </a>

                        @endisset

                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#upload{{ $target }}">

                            <i class="bx bx-upload"></i>

                            Import

                        </button>

                    </div>

                @else

                    <a href="{{ $route }}"
                       class="btn btn-success btn-sm">

                        <i class="bx bx-download"></i>

                        Export

                    </a>

                @endif

            </div>

        </div>

    </div>

</div>