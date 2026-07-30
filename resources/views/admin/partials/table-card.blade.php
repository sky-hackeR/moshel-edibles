<div class="card">

    @isset($title)

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    {{ $title }}
                </h4>

                {{ $header ?? '' }}

            </div>

        </div>

    @endisset

    <div class="card-body">

        {{ $slot }}

    </div>

</div>