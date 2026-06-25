@php
    $widthClass = match($width ?? 'page') {
        'full' => 'max-w-none',
        default => 'max-w-page',
    };
    $bgColor = $background_color ?? '#E1323D';
    $txtColor = $text_color ?? '#FFFFFF';
@endphp

<section class="py-16 px-4" style="background-color: {{ $bgColor }}; color: {{ $txtColor }};">
    <div class="mx-auto {{ $widthClass }} text-center">
        @if($title)
            <h2 class="text-3xl sm:text-4xl font-heading font-bold mb-4">{{ $title }}</h2>
        @endif
        @if($description)
            <div class="text-lg opacity-90 mb-8 max-w-2xl mx-auto">{{ $description }}</div>
        @endif
        @if($btn_text && $btn_url)
            <a href="{{ $btn_url }}"
               class="inline-flex items-center px-8 py-4 rounded-lg font-bold text-lg
                      transition-all duration-300 hover:scale-105"
               style="background-color: {{ $txtColor }}; color: {{ $bgColor }};">
                {{ $btn_text }}
            </a>
        @endif
    </div>
</section>
