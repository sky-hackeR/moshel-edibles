<div class="text-center py-5">

    <div class="mb-4">

        <i class="{{ $icon ?? 'bx bx-folder-open' }} display-4 text-muted"></i>

    </div>

    <h5>

        {{ $title ?? 'No Records Found' }}

    </h5>

    <p class="text-muted">

        {{ $message ?? 'Nothing to display at the moment.' }}

    </p>

    @isset($button)

        <a href="{{ $button['url'] }}"
           class="btn btn-primary">

            <i class="{{ $button['icon'] }}"></i>

            {{ $button['text'] }}

        </a>

    @endisset

</div>