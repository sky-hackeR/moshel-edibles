<div class="col-xl-3 col-md-6">

    <div class="card mini-stats-wid">

        <div class="card-body">

            <div class="d-flex">

                <div class="flex-grow-1">

                    <p class="text-muted fw-medium mb-2">
                        {{ $title }}
                    </p>

                    <h4 class="mb-0">
                        {{ $value }}
                    </h4>

                    @isset($subtitle)
                        <small class="text-muted">
                            {{ $subtitle }}
                        </small>
                    @endisset

                </div>

                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-soft-{{ $color ?? 'primary' }}">

                    <span class="avatar-title rounded-circle bg-{{ $color ?? 'primary' }}">

                        <i class="{{ $icon }} font-size-24"></i>

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>