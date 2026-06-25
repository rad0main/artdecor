@php
    $widthClass = match($width ?? 'full') {
        'page' => 'max-w-page',
        'contain' => 'max-w-3xl',
        default => 'max-w-none',
    };
@endphp

<section class="py-8 px-4">
    <div class="mx-auto {{ $widthClass }}">
        @if($image)
            <figure class="text-center">
                <img src="{{ $image }}" alt="{{ $alt ?? '' }}"
                     class="mx-auto rounded-lg @if(($shadow ?? false)) shadow-lg @endif
                            @if(($rounded ?? false)) rounded-2xl @endif
                            max-w-full h-auto">
                @if($caption)
                    <figcaption class="mt-3 text-sm text-[var(--k-color-text-secondary)]">{{ $caption }}</figcaption>
                @endif
            </figure>
        @endif
    </div>
</section>
