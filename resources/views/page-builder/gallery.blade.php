@php
    $colsClass = match((int)($columns ?? 3)) {
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 sm:grid-cols-2',
        3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
    $gapClass = match($gap ?? 'md') {
        'sm' => 'gap-2',
        'lg' => 'gap-8',
        default => 'gap-4',
    };
@endphp

<section class="py-12 px-4">
    <div class="max-w-page mx-auto">
        <div class="grid {{ $colsClass }} {{ $gapClass }}">
            @foreach($images ?? [] as $item)
                <div class="card group cursor-pointer"
                     @if($lightbox ?? true)
                         x-data
                         @click="$dispatch('open-lightbox', { url: '{{ $item['image'] ?? '' }}', caption: '{{ $item['caption'] ?? '' }}' })"
                     @endif>
                    <div class="aspect-[4/3] overflow-hidden bg-[var(--k-color-bg-surface)]">
                        <img src="{{ $item['image'] ?? '' }}"
                             alt="{{ $item['caption'] ?? '' }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @if($item['caption'] ?? false)
                        <div class="p-3">
                            <p class="text-sm text-[var(--k-color-text-secondary)]">{{ $item['caption'] }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @if($lightbox ?? true)
        <div x-data="lightbox()" x-show="open" x-init="init()"
             class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
             style="display: none"
             @keydown.escape.window="close()">
            <button @click="close()" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
            <div @click.outside="close()">
                <img :src="currentImage?.url" :alt="currentImage?.caption"
                     class="max-w-full max-h-[90vh] object-contain rounded">
                <p x-show="currentImage?.caption" class="text-white text-center mt-2" x-text="currentImage?.caption"></p>
            </div>
        </div>
    @endif
</section>
