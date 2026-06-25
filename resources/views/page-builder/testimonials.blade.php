<section class="py-16 md:py-20 px-4 bg-white">
    <div class="max-w-page mx-auto">
        <div class="section-heading"><h2>Отзывы довольных клиентов</h2></div>
        <div x-data="testimonials({{ json_encode($items ?? []) }})" class="relative max-w-2xl mx-auto">
            <template x-for="(item, i) in items" :key="i">
                <div x-show="current === i" x-transition class="testimonial">
                    <p class="testimonial__text" x-text="'«' + (item.text ?? '') + '»'"></p>
                    <p class="testimonial__author" x-text="'Отзыв от ' + (item.author ?? 'клиента')"></p>
                    <p class="text-xs text-[var(--k-color-text-muted)] mt-2">Опубликовано на Отзовик.ру</p>
                </div>
            </template>
            @if(count($items ?? []) > 1)
                <div class="flex justify-center gap-4 mt-6">
                    <button @click="prev()" class="slider-arrow" aria-label="Предыдущий">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="slider-arrow" aria-label="Следующий">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>
