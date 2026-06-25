@php
    $alignClass = match($alignment ?? 'left') {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
    $widthClass = match($max_width ?? 'page') {
        'full' => 'max-w-none',
        'narrow' => 'max-w-3xl',
        default => 'max-w-page',
    };
@endphp

<section class="py-12 px-4">
    <div class="mx-auto {{ $widthClass }} {{ $alignClass }}">
        <div class="prose prose-lg max-w-none
                    prose-headings:font-heading
                    prose-a:text-[var(--k-color-primary)]
                    prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-lg">
            {!! $content ?? '' !!}
        </div>
    </div>
</section>
