@php
    $items = $items ?? [];
    $heading = $heading ?? '';
    $count = count($items);
    // Responsive widths: 2 cols on mobile, 3 on sm, full row on lg+
    $gridCols = $count <= 3 ? $count : ($count <= 4 ? 4 : ($count <= 6 ? $count : 6));
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
                @endphp
                <div class="flex flex-col items-center text-center"
                     style="flex: 1 1 {{ $count >= 4 ? '180px' : '200px' }}; max-width: 220px;">
                    {{-- SVG-иконка --}}
                    <div class="mb-4 flex items-center justify-center w-24 h-24 lg:w-28 lg:h-28">
                        @if($iconUrl)
                            <img src="{{ $iconUrl }}"
                                 alt="{{ $title }}"
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-16 h-16 rounded-full bg-[var(--k-color-primary)]/10 flex items-center justify-center text-[var(--k-color-primary)] text-2xl font-bold">+</div>
                        @endif
                    </div>
                    {{-- Текст --}}
                    <p class="text-sm md:text-base font-heading text-[var(--k-color-text-primary)] leading-tight max-w-[180px]">
                        {{ $title }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
