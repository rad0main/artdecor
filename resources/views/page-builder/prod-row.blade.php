@php
    $items = $items ?? [];
    $heading = $heading ?? '';
@endphp

<section class="py-12 md:py-16 bg-white">
    <div class="max-w-page mx-auto px-4">
        @if($heading)
            <div class="section-heading mb-10">
                <h2>{{ $heading }}</h2>
            </div>
        @endif

        {{-- Grid 3×3 без центральной ячейки (поз. 8) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 max-w-[1200px] mx-auto">
            @foreach($items as $i => $item)
                @php
                    $imgUrl = $item['image'] ?? '';
                    $title = $item['title'] ?? '';
                    $link = $item['link'] ?? '#';
                @endphp

                <a href="{{ $link }}"
                   class="group relative block overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 bg-[var(--k-color-bg-surface)]"
                   style="aspect-ratio: 370 / 250; max-width: 370px; width: 100%; margin: 0 auto;">

                    {{-- Изображение (3/4 высоты) --}}
                    <div class="absolute top-0 left-0 right-0 overflow-hidden"
                         style="height: 75%;">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}"
                                 alt="{{ $title }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[var(--k-color-text-muted)] text-sm bg-gray-100">
                                Нет изображения
                            </div>
                        @endif
                    </div>

                    {{-- Белая полупрозрачная полоса (1/4 снизу) --}}
                    <div class="absolute bottom-0 left-0 right-0 bg-white/40 backdrop-blur-[2px] flex items-center px-4"
                         style="height: 25%; min-height: 50px;">
                        <h2 class="text-sm md:text-base font-heading font-bold text-[var(--k-color-text-primary)] leading-tight truncate max-w-full">
                            {{ $title }}
                        </h2>
                    </div>
                </a>

                {{-- Пустая ячейка после 7-го блока (позиция 8 в сетке) --}}
                @if($i === 6)
                    <div class="hidden lg:block" aria-hidden="true"></div>
                @endif
            @endforeach
        </div>
    </div>
</section>
