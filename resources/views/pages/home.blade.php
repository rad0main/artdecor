<x-layouts.app title="Главная">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main>
        {{-- Hero-слайдер --}}
        <section class="relative bg-[var(--k-color-secondary)] overflow-hidden"
                 x-data="slider({{ json_encode($slides) }})" x-init="init()">
            <div class="relative h-[400px] md:h-[500px]">
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <div x-show="current === index"
                         x-transition:enter="transition-opacity duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0">
                        <img :src="slide.image" :alt="slide.title"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                </template>

                {{-- Текст на слайдере --}}
                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 text-white">
                    <template x-for="(slide, index) in slides" :key="'text-' + slide.id">
                        <div x-show="current === index" x-transition>
                            <h1 class="text-3xl md:text-5xl font-heading font-bold mb-4 drop-shadow-lg" x-text="slide.title"></h1>
                            <p class="text-lg md:text-xl mb-6 drop-shadow" x-text="slide.subtitle"></p>
                            <a :href="slide.link" class="btn-primary inline-block" x-text="slide.btn_text || 'Подробнее'"></a>
                        </div>
                    </template>
                </div>

                {{-- Точки пагинации --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <template x-for="(slide, index) in slides" :key="'dot-' + slide.id">
                        <button @click="current = index"
                                :class="current === index ? 'bg-white' : 'bg-white/50'"
                                class="w-3 h-3 rounded-full transition-colors"></button>
                    </template>
                </div>
            </div>
        </section>

        {{-- Категории продукции --}}
        <section class="max-w-page mx-auto px-4 py-16">
            <h2 class="text-2xl md:text-3xl font-heading font-bold text-center mb-12">Категории продукции</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($categories as $cat)
                    <a href="{{ route('catalog') }}?category={{ $cat->id }}"
                       class="flex flex-col items-center p-4 rounded-lg bg-[var(--k-color-bg-surface)] hover:bg-[var(--k-color-bg-surface-hover)] transition-colors group">
                        <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow">
                            <svg class="w-8 h-8 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-center">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Преимущества --}}
        <section class="bg-[var(--k-color-bg-surface)] py-16">
            <div class="max-w-page mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-heading font-bold text-center mb-12">Почему выбирают нас</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="p-6">
                        <div class="text-4xl mb-4">🛡️</div>
                        <h3 class="font-heading font-bold mb-2">Закалённое стекло</h3>
                        <p class="text-sm text-[var(--k-color-text-secondary)]">Все изделия только из закаленного стекла высокой прочности</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl mb-4">💰</div>
                        <h3 class="font-heading font-bold mb-2">Фиксированная стоимость</h3>
                        <p class="text-sm text-[var(--k-color-text-secondary)]">Прозрачное ценообразование без скрытых платежей</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl mb-4">🏭</div>
                        <h3 class="font-heading font-bold mb-2">Собственное производство</h3>
                        <p class="text-sm text-[var(--k-color-text-secondary)]">Полный цикл производства на собственной фабрике</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Последние работы --}}
        @if($featuredWorks->isNotEmpty())
            <section class="max-w-page mx-auto px-4 py-16">
                <h2 class="text-2xl md:text-3xl font-heading font-bold text-center mb-12">Последние работы</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredWorks as $work)
                        <x-work-card :work="[
                            'id' => $work->id,
                            'title' => $work->title,
                            'thumb' => $work->thumb_url,
                            'preview' => $work->preview_url,
                        ]" />
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('works') }}" class="text-[var(--k-color-primary)] font-bold hover:underline">
                        Смотреть все работы &rarr;
                    </a>
                </div>
            </section>
        @endif

        {{-- Форма обратной связи --}}
        <section class="bg-[var(--k-color-bg-surface)] py-16">
            <div class="max-w-page mx-auto px-4">
                <div class="max-w-lg mx-auto text-center">
                    <h2 class="text-2xl md:text-3xl font-heading font-bold mb-4">Закажите звонок</h2>
                    <p class="text-sm text-[var(--k-color-text-secondary)] mb-8">Оставьте заявку и мы перезвоним вам в течение 2 часов</p>

                    <form @submit.prevent="submitCallback" class="space-y-4"
                          x-data="{ phone: '', name: '' }"
                          x-init="$data.submitCallback = async function() { await axios.post('/api/order', { name, phone, source: 'callback' }); name = ''; phone = ''; alert('Спасибо! Мы перезвоним вам.'); }">
                        <input type="text" x-model="name" placeholder="Ваше имя" required
                               class="w-full px-4 py-3 border border-[var(--k-color-border)] rounded-lg text-sm
                                      focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)] outline-none">
                        <input type="tel" x-model="phone" placeholder="+7 (999) 123-45-67" required
                               class="w-full px-4 py-3 border border-[var(--k-color-border)] rounded-lg text-sm
                                      focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)] outline-none">
                        <button type="submit" class="btn-primary w-full">Заказать звонок</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
