@php
    $widthClass = match($width ?? 'page') {
        'full' => 'max-w-none',
        default => 'max-w-page',
    };
    $bgColor = $background_color ?? 'var(--k-color-primary)';
    $txtColor = $text_color ?? '#FFFFFF';
    $padding = $padding ?? 'py-16 md:py-20';
@endphp

<section class="{{ $padding }} px-4" style="background-color: {{ $bgColor }}; color: {{ $txtColor }};">
    <div class="mx-auto {{ $widthClass }} text-center">
        @if($title)
            <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4">{{ $title }}</h2>
        @endif
        @if($description)
            <div class="text-base sm:text-lg opacity-90 mb-8 max-w-2xl mx-auto leading-relaxed">{!! $description !!}</div>
        @endif
        @if($btn_text && $btn_url)
            <a href="{{ $btn_url }}"
               class="inline-flex items-center px-8 py-3 rounded font-bold text-base
                      transition-all duration-200 hover:opacity-90"
               style="background-color: {{ $txtColor }}; color: {{ $bgColor }};">
                {{ $btn_text }}
            </a>
        @endif
    </div>
</section>
