@php
    $slides = $slides ?? [];
    $hasMultiple = count($slides) > 1;
    $showDots = $show_dots ?? true;
    $interval = $interval ?? 5.0;
    $barOpacity = ($bar_opacity ?? 40) / 100;
    // Fallback: use global title/text if no slides have content
    if (empty($slides) || (empty($slides[0]['image']) && empty($slides[0]['title']))) {
        $globalFallback = [['image' => '', 'title' => $title ?? 'Промо', 'text' => $text ?? 'Описание']];
        if (!empty($slides)) {
            $slides = $globalFallback;
        }
    }
@endphp

<section class="relative w-full overflow-hidden bg-[var(--k-color-secondary)]"
         x-data="slider({{ json_encode($slides) }}, {{ $interval * 1000 }}, {{ $barOpacity }})" x-init="init()">
    <div class="relative h-[300px] sm:h-[420px] md:h-[520px] lg:h-[600px]">
        {{-- Слайды --}}
        <template x-for="(slide, i) in slides" :key="i">
            <div x-show="current === i"
                 x-transition:enter="transition-opacity duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                {{-- Фоновое изображение --}}
                <div class="absolute inset-0 bg-cover bg-center"
                     :style="slide.image ? 'background-image: url(' + slide.image + ')' : ''">
                </div>
            </div>
        </template>

        {{-- Информационная полоса (снизу) --}}
        <div class="absolute bottom-0 left-0 right-0 z-10 flex items-center justify-center px-4 sm:px-6 lg:px-8"
             style="height: 20%; min-height: 70px;"
             :style="barStyle(current)">
            <template x-for="(slide, i) in slides" :key="'txt-' + i">
                <div x-show="current === i"
                     x-transition:enter="transition-all duration-500 delay-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="w-full text-center">
                    <h3 class="text-base sm:text-lg md:text-xl lg:text-2xl font-heading font-semibold leading-tight"
                        :style="'color: ' + (slide.text_color || '#333333') + ';'" x-text="slide.title"></h3>
                    <p class="text-xs sm:text-sm md:text-base mt-1 leading-snug"
                       :style="'color: ' + (slide.text_color || '#333333') + ';'" x-text="slide.text"></p>
                </div>
            </template>
        </div>

        {{-- Стрелки навигации --}}
        <template x-if="slides.length > 1">
            <div class="hidden md:block">
                <button @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-gray-100 transition-colors"
                        aria-label="Назад">
                    <svg class="w-5 h-5 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-gray-100 transition-colors"
                        aria-label="Вперёд">
                    <svg class="w-5 h-5 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </template>

        {{-- Точки пагинации --}}
        <template x-if="slides.length > 1 && {{ $showDots ? 'true' : 'false' }}">
            <div class="absolute bottom-[calc(20%+12px)] left-1/2 -translate-x-1/2 z-20 flex gap-2">
                <template x-for="(slide, i) in slides" :key="'dot-' + i">
                    <button @click="current = i"
                            :class="current === i ? 'bg-white w-8' : 'bg-white/50 w-3'"
                            class="h-3 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </template>
    </div>
</section>
