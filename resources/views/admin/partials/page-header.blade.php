<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <div>
                <h4 class="mb-sm-0 font-size-18">
                    {{ $title ?? 'Dashboard' }}
                </h4>

                @isset($description)
                    <p class="text-muted mb-0 mt-1">
                        {{ $description }}
                    </p>
                @endisset
            </div>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">
                        {{ $breadcrumb ?? $title }}
                    </li>
                </ol>
            </div>

        </div>
    </div>
</div>