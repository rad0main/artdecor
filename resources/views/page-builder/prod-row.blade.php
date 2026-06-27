@php
    $items = $items ?? [];
    $heading = $heading ?? '';
    $barOpacity = ($bar_opacity ?? 40) / 100; // convert 0-100 → 0.0-1.0
@endphp

<section class="py-12 md:py-16 bg-white">
    <div class="max-w-page mx-auto px-4">
        @if($heading)
            <div class="section-heading mb-10">
                <h2>{{ $heading }}</h2>
            </div>
        @endif

        {{-- Grid 4×3 без ячейки 11 (4-й ряд: 10-пусто-12) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 max-w-[1200px] mx-auto">
            @foreach($items as $i => $item)
                @php
                    $imgUrl = $item['image'] ?? '';
                    // URL-encode filename only (spaces, parens → %20, %28, %29)
                    $parts = explode('/', $imgUrl);
                    $filename = array_pop($parts);
                    $encodedUrl = implode('/', $parts) . '/' . rawurlencode($filename);
                    $title = $item['title'] ?? '';
                    $link = $item['link'] ?? '#';
                @endphp

                <a href="{{ $link }}"
                   class="group relative block overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 bg-[var(--k-color-bg-surface)]"
                   style="aspect-ratio: 370 / 250; max-width: 370px; width: 100%; margin: 0 auto;">

                    {{-- Изображение на весь блок (100%) --}}
                    @if($imgUrl)
                        <img src="{{ $encodedUrl }}"
                             alt="{{ $title }}"
                             loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-[var(--k-color-text-muted)] text-sm bg-gray-100">
                            Нет изображения
                        </div>
                    @endif

                    {{-- Белая полупрозрачная полоса поверх изображения (1/4 снизу) --}}
                    <div class="absolute bottom-0 left-0 right-0 flex items-center justify-center px-3"
                         style="height: 25%; min-height: 50px; background-color: rgba(255, 255, 255, {{ $barOpacity }});">
                        <h2 class="text-base md:text-lg lg:text-xl font-heading text-[var(--k-color-text-primary)] text-center leading-tight truncate max-w-full">
                            {{ $title }}
                        </h2>
                    </div>
                </a>

                {{-- Пустая ячейка после 10-го блока (позиция 11 в сетке 4×3) --}}
                @if($i === 9)
                    <div class="hidden lg:block" aria-hidden="true"></div>
                @endif
            @endforeach
        </div>
    </div>
</section>
