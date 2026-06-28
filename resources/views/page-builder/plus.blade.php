@php
    $items = $items ?? [];
    $heading = $heading ?? '';
    $count = count($items);
@endphp

<section class="py-12 md:py-16 bg-white">
    <div class="max-w-page mx-auto px-4">
        @if($heading)
            <div class="section-heading mb-10">
                <h2>{{ $heading }}</h2>
            </div>
        @endif

        <div class="flex flex-wrap justify-center gap-6 md:gap-8 lg:gap-10">
            @foreach($items as $item)
                @php
                    $iconUrl = $item['icon'] ?? '';
                    $title = $item['title'] ?? '';
                    $iconColor = $item['icon_color'] ?? '#ed2529';
                    $textColor = $item['text_color'] ?? '#333333';
                    $textSize = $item['text_size'] ?? 16;
                    $bold = !empty($item['bold']);
                    $italic = !empty($item['italic']);
                @endphp
                <div class="flex flex-col items-center text-center"
                     style="flex: 1 1 {{ $count >= 4 ? '180px' : '200px' }}; max-width: 220px;">
                    {{-- SVG-иконка с динамическим цветом через mask --}}
                    <div class="mb-4 w-24 h-24 lg:w-28 lg:h-28">
                        @if($iconUrl)
                            <div class="w-full h-full"
                                 style="background-color: {{ $iconColor }}; -webkit-mask: url({{ $iconUrl }}) no-repeat center / contain; mask: url({{ $iconUrl }}) no-repeat center / contain;"></div>
                        @else
                            <div class="w-full h-full rounded-full bg-[var(--k-color-primary)]/10 flex items-center justify-center text-[var(--k-color-primary)] text-2xl font-bold">+</div>
                        @endif
                    </div>
                    {{-- Текст --}}
                    <p class="font-heading leading-tight max-w-[180px]"
                       style="color: {{ $textColor }}; font-size: {{ $textSize }}px; font-weight: {{ $bold ? 'bold' : 'normal' }}; font-style: {{ $italic ? 'italic' : 'normal' }};">
                        {{ $title }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
