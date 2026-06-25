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
            <h2 class="text-3xl sm:text-4xl font-heading font-bold mb-4">{{ $title }}</h2>
        @endif
        @if($description)
            <div class="text-lg opacity-90 mb-8 max-w-2xl mx-auto leading-relaxed">{!! $description !!}</div>
        @endif
        @if($btn_text && $btn_url)
            <a href="{{ $btn_url }}"
               class="inline-flex items-center px-10 py-4 rounded-lg font-bold text-lg
                      transition-all duration-300 hover:scale-105 hover:-translate-y-0.5 shadow-lg"
               style="background-color: {{ $txtColor }}; color: {{ $bgColor }};">
                {{ $btn_text }}
            </a>
        @endif
    </div>
</section>
