@php
    $slides = $slides ?? [];
    $heading = $heading ?? 'Виды скинали';
    $total = count($slides);
@endphp

<section class="py-12 md:py-16 bg-white">
    {{-- Заголовок --}}
    <div class="max-w-page mx-auto px-4 mb-8">
        <div class="section-heading">
            <h2>{{ $heading }}</h2>
        </div>
    </div>

    <div x-data="typesSlider({{ json_encode($slides) }})" x-init="init()"
         class="relative select-none">

        {{-- Подзаголовки на всю ширину --}}
        <div class="w-full px-4 sm:px-6 lg:px-8 mb-6">
            <div class="flex flex-nowrap justify-between gap-2 max-w-full mx-auto"
                 style="max-width: 1400px;">
                <template x-for="(slide, i) in slides" :key="'sub-' + i">
                    <button @click="goTo(i)"
                            class="relative text-xs sm:text-sm md:text-base lg:text-lg font-heading whitespace-nowrap px-1 sm:px-2 py-2 transition-colors duration-200 text-center flex-1"
                            :class="current === i ? 'text-[var(--k-color-primary)]' : 'text-[var(--k-color-text-secondary)] hover:text-[var(--k-color-text-primary)]'">
                        <span x-text="slide.subtitle"></span>
                        <span class="absolute left-1/2 -translate-x-1/2 w-2.5 h-2.5 rounded-full transition-all duration-300"
                              :class="current === i ? 'bg-[var(--k-color-primary)] scale-100 bottom-0' : 'bg-transparent scale-0 bottom-0'"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Карусель на всю ширину с peek --}}
        <div class="relative w-full overflow-visible" style="width: 100vw; margin-left: calc(-50vw + 50%);">
            <div class="flex items-center transition-transform duration-500 ease-out will-change-transform"
                 :style="'transform: translateX(calc(20vw - ' + (current * 60) + 'vw));'">
                <template x-for="(slide, i) in slides" :key="'car-' + i">
                    <div class="flex-shrink-0 transition-all duration-500 ease-out px-3 relative"
                         :style="'width: 60vw; opacity: ' + (i === current ? 1 : 0.45) + '; transform: scale(' + (i === current ? 1 : 0.82) + ');'">
                        <div class="relative w-full overflow-hidden rounded-xl shadow-lg bg-gray-100"
                             style="aspect-ratio: 16 / 9;">
                            <img :src="slide.image || ''"
                                 :alt="slide.subtitle || ''"
                                 class="absolute inset-0 w-full h-full object-cover"
                                 loading="lazy">
                        </div>
                        {{-- Стрелки между слайдами (только для активного) --}}
                        <template x-if="slides.length > 1 && i === current">
                            <div class="hidden md:block">
                                <button @click="prev()"
                                        class="absolute -left-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center hover:bg-gray-100 transition-colors border border-gray-200"
                                        :class="current === 0 ? 'opacity-30 pointer-events-none' : ''"
                                        aria-label="Назад">
                                    <svg class="w-6 h-6 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="next()"
                                        class="absolute -right-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center hover:bg-gray-100 transition-colors border border-gray-200"
                                        :class="current === slides.length - 1 ? 'opacity-30 pointer-events-none' : ''"
                                        aria-label="Вперёд">
                                    <svg class="w-6 h-6 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

        {{-- Текст активного слайда --}}
        <div class="max-w-3xl mx-auto mt-8 px-4 sm:px-6 lg:px-8 text-center">
            <template x-for="(slide, i) in slides" :key="'txt-' + i">
                <p x-show="current === i"
                   x-transition:enter="transition-opacity duration-300"
                   x-transition:enter-start="opacity-0"
                   x-transition:enter-end="opacity-100"
                   class="text-sm md:text-base lg:text-lg text-[var(--k-color-text-secondary)] leading-relaxed"
                   x-text="slide.text || ''"></p>
            </template>
        </div>
    </div>
</section>
