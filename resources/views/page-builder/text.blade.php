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
    $heading = $heading ?? '';
    $fontSize = $font_size ?? 16;
    $headingFontSize = $heading_font_size ?? 30;
    $textColor = $text_color ?? '#333333';
@endphp

<section class="py-12 px-4">
    <div class="mx-auto {{ $widthClass }} {{ $alignClass }}">
        @if($heading)
            <div class="section-heading mb-8">
                <h2 style="font-size: {{ $headingFontSize }}px;">{{ $heading }}</h2>
            </div>
        @endif
        <div class="prose prose-lg max-w-none
                    prose-headings:font-heading
                    prose-a:text-[var(--k-color-primary)]
                    prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-lg"
             style="font-size: {{ $fontSize }}px; color: {{ $textColor }};">
            {!! $content ?? '' !!}
        </div>
    </div>
</section>
