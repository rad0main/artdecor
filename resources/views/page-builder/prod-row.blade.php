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
                   class="group relative block overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300"
                   style="aspect-ratio: 370 / 250; max-width: 370px; width: 100%; margin: 0 auto;">

                    {{-- Изображение на весь блок --}}
                    @if($imgUrl)
                        <img src="{{ $imgUrl }}"
                             alt="{{ $title }}"
                             loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-[var(--k-color-text-muted)] text-sm bg-gray-100">
                            Нет изображения
                        </div>
                    @endif

                    {{-- Полупрозрачный градиент снизу для читаемости текста --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>

                    {{-- Текст по центру блока --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <h2 class="text-sm md:text-base lg:text-lg font-heading text-white text-center leading-tight px-3 max-w-full">
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
