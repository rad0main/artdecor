@php
    $heightClass = match($height ?? 'medium') {
        'small' => 'h-[300px] sm:h-[300px]',
        'medium' => 'h-[400px] sm:h-[500px]',
        'large' => 'h-[500px] sm:h-[700px]',
        'fullscreen' => 'h-screen',
        default => 'h-[500px] sm:h-[700px]',
    };
    $slides = $slides ?? [];
    $hasMultiple = count($slides) > 1;
@endphp

<section class="relative {{ $heightClass }} overflow-hidden bg-[var(--k-color-secondary)]">
    @if(!empty($slides))
        <div x-data="slider({{ json_encode(collect($slides)->map(fn($s) => [
            'id' => $s['id'] ?? uniqid(),
            'title' => $s['title'] ?? $title ?? '',
            'subtitle' => $s['subtitle'] ?? $subtitle ?? '',
            'link' => $s['btn_url'] ?? $btn_url ?? '#',
            'btn_text' => $s['btn_text'] ?? $btn_text ?? 'Подробнее',
            'image' => $s['image'] ?? '',
        ])->toArray()) }})" x-init="init()" class="h-full">
            <template x-for="(slide, i) in slides" :key="i">
                <div x-show="current === i"
                     x-transition:enter="transition-opacity duration-700"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-700"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <div class="absolute inset-0 bg-cover bg-center"
                         :style="slide.image ? 'background-image: url(' + slide.image + ')' : ''">
                    </div>
                </div>
            </template>

            @if($overlay ?? true)
                <div class="absolute inset-0 bg-black/40"></div>
            @endif

            <div class="absolute inset-0 flex items-center">
                <div class="max-w-page mx-auto px-4 w-full">
                    <template x-for="(slide, i) in slides" :key="'t-'+i">
                        <div x-show="current === i"
                             x-transition:enter="transition-all duration-500 delay-200"
                             x-transition:enter-start="opacity-0 translate-y-6"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 leading-tight" x-text="slide.title"></h1>
                            <p class="text-lg text-white/90 mb-6 max-w-xl" x-text="slide.subtitle"></p>
                            <a :href="slide.link" class="btn-primary px-10 py-4" x-text="slide.btn_text"></a>
                        </div>
                    </template>
                </div>
            </div>

            @if($hasMultiple)
                <button @click="prev()" class="slider-arrow absolute left-4 top-1/2 -translate-y-1/2 z-10" aria-label="Назад">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="next()" class="slider-arrow absolute right-4 top-1/2 -translate-y-1/2 z-10" aria-label="Вперёд">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(slide, i) in slides" :key="'d-'+i">
                        <button @click="current = i"
                                :class="current === i ? 'bg-white w-8' : 'bg-white/50 w-3'"
                                class="h-3 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            @endif
        </div>
    @else
        {{-- Single slide / fallback when no slides configured --}}
        @if($overlay ?? true)
            <div class="absolute inset-0 bg-black/40"></div>
        @endif
        <div class="absolute inset-0 flex items-center">
            <div class="max-w-page mx-auto px-4 w-full">
                <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 leading-tight">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-lg text-white/90 mb-6 max-w-xl">{{ $subtitle }}</p>
                @endif
                @if($btn_text && $btn_url)
                    <a href="{{ $btn_url }}" class="btn-primary px-10 py-4">{{ $btn_text }}</a>
                @endif
            </div>
        </div>
    @endif
</section>
