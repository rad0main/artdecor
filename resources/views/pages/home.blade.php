<x-layouts.app title="Главная">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main>
        {{-- ═══ 1. Hero-слайдер ═══ --}}
        <section class="relative bg-[var(--k-color-secondary)] overflow-hidden"
                 x-data="slider({{ json_encode($slides) }})" x-init="init()">
            <div class="relative h-[380px] md:h-[480px] lg:h-[560px]">
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <div x-show="current === index"
                         x-transition:enter="transition-opacity duration-700"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0">
                        <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40"></div>
                    </div>
                </template>

                <div class="absolute inset-0 flex items-center">
                    <div class="w-full max-w-page mx-auto px-4">
                        <template x-for="(slide, index) in slides" :key="'t-'+slide.id">
                            <div x-show="current === index"
                                 x-transition:enter="transition-all duration-500 delay-200"
                                 x-transition:enter-start="opacity-0 translate-y-6"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 leading-tight" x-text="slide.title"></h1>
                                <p class="text-lg text-white/90 mb-6 max-w-xl" x-text="slide.subtitle"></p>
                                <a :href="slide.link" class="btn-primary px-10 py-4" x-text="slide.btn_text || 'Подробнее'"></a>
                            </div>
                        </template>
                    </div>
                </div>

                @if(count($slides) > 1)
                    <button @click="prev()" class="slider-arrow absolute left-4 top-1/2 -translate-y-1/2 z-10" aria-label="Назад">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="slider-arrow absolute right-4 top-1/2 -translate-y-1/2 z-10" aria-label="Вперёд">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                @endif
            </div>
        </section>

        {{-- ═══ 2. Баннер акции ═══ --}}
        <section class="bg-[var(--k-color-bg-surface)] py-6">
            <div class="max-w-page mx-auto px-4">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
                    <span class="text-lg md:text-xl font-heading font-bold text-[var(--k-color-text-primary)]">Акция на световые скинали!</span>
                    <span class="text-sm text-[var(--k-color-text-secondary)]">Ограниченная акция — скинали со скидкой 10%!</span>
                    <a href="{{ route('services') }}" class="btn-primary text-sm px-6 py-2">Подробнее</a>
                </div>
            </div>
        </section>

        {{-- ═══ 3. Наша продукция ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading"><h2>Наша продукция</h2></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories->take(9) as $cat)
                        <a href="{{ route('catalog') }}?category={{ $cat->id }}" class="product-card">
                            <div class="product-card__image">
                                <div class="flex items-center justify-center h-full text-[var(--k-color-text-muted)] text-sm">
                                    {{ $cat->name }}
                                </div>
                            </div>
                            <div class="product-card__title">{{ $cat->name }}</div>
                        </a>
                    @endforeach
                    @if($categories->count() > 9)
                        <a href="{{ route('catalog') }}" class="product-card">
                            <div class="product-card__image flex items-center justify-center bg-[var(--k-color-bg-surface)]">
                                <span class="text-[var(--k-color-primary)] font-heading font-bold text-lg">Ещё +{{ $categories->count() - 9 }}</span>
                            </div>
                            <div class="product-card__title">Смотреть все</div>
                        </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- ═══ 4. Элегантный дизайн + иконки ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
            <div class="max-w-page mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-heading font-bold mb-6 leading-tight">
                            Элегантный дизайн<br>любой комнаты
                        </h2>
                        <div class="text-[var(--k-color-text-secondary)] leading-relaxed space-y-4">
                            <p>Уникальность и красота – вот что отличает изделия из стекла от компании ArtDecor. Мы специализируемся на производстве стен, дверей, потолков и фартуков для кухни (скинали) из стекла. <a href="{{ route('works') }}" class="text-[var(--k-color-primary)] font-semibold hover:underline">Наши изделия</a> не просто функциональны.</p>
                            <p>Вы можете придать своей кухне роскошный вид, <a href="{{ route('catalog') }}" class="text-[var(--k-color-primary)] font-semibold hover:underline">выбрав из нашего широкого ассортимента</a>.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-4">
                            <div class="feature-icon">
                                <svg viewBox="0 0 37 37" fill="none"><path d="M36.38 37H33.3V14.8H4.3V36.4C4.3 36.7 4 37 3.7 37H0.6C0.3 37 0 36.7 0 36.4V10.5C0 10.3 0.1 10 0.3 9.9L18.2 0.1C18.4 0 18.6 0 18.8 0.1L36.7 10.6C36.9 10.7 37 10.9 37 11.1V36.4C37 36.7 36.7 37 36.38 37Z" fill="currentColor"/></svg>
                            </div>
                            <h3 class="font-heading font-bold text-sm mb-1">Закалённое стекло</h3>
                            <p class="text-xs text-[var(--k-color-text-secondary)]">Только закалённое стекло высокой прочности</p>
                        </div>
                        <div class="text-center p-4">
                            <div class="feature-icon">
                                <svg viewBox="0 0 32 38" fill="none"><path d="M32 9.2C32 4.1 27.9 0 22.8 0C19.2 0 16.2 2 14.6 4.9H0.6C0.3 4.9 0 5.2 0 5.5V37.4C0 37.7 0.3 38 0.6 38H27.7C28 38 28.3 37.7 28.3 37.4V16.5C30.5 14.9 32 12.2 32 9.2ZM27.1 36.8H1.2V6.1H14.1C13.9 6.5 13.8 6.9 13.7 7.4H3.1C2.7 7.4 2.5 7.6 2.5 8V15.3C2.5 15.7 2.7 15.9 3.1 15.9H16.5C18.2 17.5 20.4 18.4 22.8 18.4C24.3 18.4 25.8 18 27.1 17.3V36.8ZM22.8 17.2C18.4 17.2 14.8 13.6 14.8 9.2C14.8 4.8 18.4 1.2 22.8 1.2C27.2 1.2 30.8 4.8 30.8 9.2C30.8 13.6 27.2 17.2 22.8 17.2Z" fill="currentColor"/></svg>
                            </div>
                            <h3 class="font-heading font-bold text-sm mb-1">Фиксированная цена</h3>
                            <p class="text-xs text-[var(--k-color-text-secondary)]">Прозрачное ценообразование</p>
                        </div>
                        <div class="text-center p-4">
                            <div class="feature-icon">
                                <svg viewBox="0 0 33 39" fill="none"><path d="M32.1 27.2L29.5 25.7L32.1 24.1C32.7 23.8 33 23.2 33 22.6C33 21.9 32.7 21.3 32.1 21L29.5 19.5L32.1 18C32.7 17.6 33 17.1 33 16.4C33 15.8 32.7 15.2 32.1 14.8L29.7 13.4C29.7 7.3 29.8 7.6 29.6 7.4C29.5 7.2 29.5 7.3 27.1 5.9C26.8 5.7 26.4 5.8 26.3 6.1C26.1 6.4 26.2 6.7 26.5 6.9L27.9 7.7L23.6 9.9C23.4 10 23.2 10.2 23.2 10.4C23.2 10.6 23.4 10.8 23.6 10.9L27.9 13.1C26.2 14 17.7 19 16.8 19.5C16.7 19.6 16.6 19.6 16.6 19.5L15.9 19.1C15.6 18.9 15.2 19 15.1 19.3C14.9 19.6 15 19.9 15.3 20.1L16 20.5C16.2 20.6 16.5 20.7 16.7 20.7C16.9 20.7 17.2 20.6 17.4 20.5L28.5 14.1L31.5 15.8C32 16.1 32 16.7 31.5 17C23.4 21.7 26.8 19.7 17 25.4C16.8 25.5 16.6 25.5 16.4 25.4L7.5 20.3C7.3 20.1 6.9 20.2 6.8 20.5C6.6 20.8 6.7 21.1 7 21.3L15.8 26.4C16.1 26.6 16.4 26.7 16.7 26.7C17 26.7 17.3 26.6 17.6 26.4L28.4 20.1L31.5 22C32 22.3 32 22.9 31.5 23.1C31.2 23.3 31.3 23.3 17 31.6C16.8 31.7 16.6 31.7 16.4 31.6L13.3 29.8C13 29.6 12.6 29.7 12.5 30C12.3 30.3 12.4 30.6 12.7 30.8L15.8 32.6C16.1 32.7 16.4 32.8 16.7 32.8C17 32.8 17.3 32.7 17.6 32.6L28.4 26.3L31.5 28.2C32 28.4 32 29.1 31.5 29.3L28.9 30.9C28.4 31.2 28.6 31.9 29.1 31.9C29.4 31.9 29.3 31.9 32.1 30.3C33.3 29.6 33.3 27.9 32.1 27.2Z" fill="currentColor"/></svg>
                            </div>
                            <h3 class="font-heading font-bold text-sm mb-1">Своё производство</h3>
                            <p class="text-xs text-[var(--k-color-text-secondary)]">Полный цикл на собственной фабрике</p>
                        </div>
                        <div class="text-center p-4">
                            <div class="feature-icon">
                                <svg viewBox="0 0 32 32" fill="none"><path d="M16 0C7.16 0 0 7.16 0 16C0 24.84 7.16 32 16 32C24.84 32 32 24.84 32 16C32 7.16 24.84 0 16 0ZM16 29.33C8.64 29.33 2.67 23.36 2.67 16C2.67 8.64 8.64 2.67 16 2.67C23.36 2.67 29.33 8.64 29.33 16C29.33 23.36 23.36 29.33 16 29.33Z" fill="currentColor"/><path d="M21.78 14.67H17.33V10.22C17.33 9.55 16.79 9 16.11 15.44 9 14.67 9.55 14.67 10.22V16C14.67 16.67 15.21 17.22 15.89 17.22H21.78C22.45 17.22 23 16.67 23 16C23 15.33 22.45 14.67 21.78 14.67Z" fill="currentColor"/></svg>
                            </div>
                            <h3 class="font-heading font-bold text-sm mb-1">Защитная плёнка</h3>
                            <p class="text-xs text-[var(--k-color-text-secondary)]">Оклейка дополнительной защитной плёнкой</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 5. Виды скинали (табы) ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading"><h2>Виды скинали</h2></div>
                <div x-data="tabs(0)" class="mb-8">
                    <div class="flex flex-wrap justify-center gap-2 border-b border-[var(--k-color-border)] pb-0 mb-8">
                        <button class="tab-btn" :class="active === 0 && 'active'" @click="active = 0">Однотонные скинали</button>
                        <button class="tab-btn" :class="active === 1 && 'active'" @click="active = 1">Скинали с рисунком</button>
                        <button class="tab-btn" :class="active === 2 && 'active'" @click="active = 2">3D скинали</button>
                        <button class="tab-btn" :class="active === 3 && 'active'" @click="active = 3">Прозрачные</button>
                        <button class="tab-btn" :class="active === 4 && 'active'" @click="active = 4">Светящиеся</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="col-span-1 md:col-span-2 aspect-[16/9] rounded-lg bg-[var(--k-color-bg-surface)] flex items-center justify-center text-[var(--k-color-text-muted)]">Фото кухни</div>
                        <div class="aspect-[4/3] rounded-lg bg-[var(--k-color-bg-surface)] flex items-center justify-center text-[var(--k-color-text-muted)]">Фото</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 6. Примерить скинали онлайн ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
            <div class="max-w-page mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-heading font-bold mb-4">Примерить скинали онлайн</h2>
                        <p class="text-[var(--k-color-text-secondary)] leading-relaxed mb-6">
                            Воспользуйтесь нашим онлайн-конфигуратором, чтобы подобрать идеальный скинали для вашей кухни. <a href="{{ route('primerka') }}" class="text-[var(--k-color-primary)] font-semibold hover:underline">Свяжитесь с нами</a> для профессиональной консультации.
                        </p>
                        <a href="{{ route('primerka') }}" class="btn-primary">Открыть каталог</a>
                    </div>
                    <div class="aspect-[4/3] rounded-lg bg-white shadow-md flex items-center justify-center">
                        <div class="text-center text-[var(--k-color-text-muted)]">
                            <p class="text-sm mb-2">Конфигуратор</p>
                            <div class="flex justify-center gap-2 mt-4">
                                <span class="w-6 h-6 rounded-full bg-[#E8D5B7] border-2 border-white shadow"></span>
                                <span class="w-6 h-6 rounded-full bg-[#D4A574] border-2 border-white shadow"></span>
                                <span class="w-6 h-6 rounded-full bg-[#8B7355] border-2 border-white shadow"></span>
                                <span class="w-6 h-6 rounded-full bg-[#2C2C2C] border-2 border-white shadow"></span>
                                <span class="w-6 h-6 rounded-full bg-[#E8E8E8] border-2 border-white shadow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 7. Оставьте заявку на замер ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface-alt)]">
            <div class="max-w-page mx-auto px-4">
                <div class="max-w-xl mx-auto text-center">
                    <h2 class="text-3xl font-heading font-bold mb-3">Оставьте заявку на замер</h2>
                    <p class="text-[var(--k-color-text-secondary)] mb-8">Мы перезвоним в течение 5 минут и дадим вам скидку 10%</p>
                    <form @submit.prevent="submitCallback" class="space-y-4"
                          x-data="{ phone: '', name: '', agree: false }"
                          x-init="$data.submitCallback = async function() {
                              await axios.post('/api/order', { name, phone, source: 'measurement' });
                              name = ''; phone = ''; agree = false;
                              alert('Спасибо! Мы перезвоним в течение 5 минут.');
                          }">
                        <input type="text" x-model="name" placeholder="Имя" required class="input-blue">
                        <input type="tel" x-model="phone" placeholder="Телефон" required class="input-blue">
                        <button type="submit" class="btn-primary w-full">Отправить</button>
                        <label class="flex items-center justify-center gap-2 text-xs text-[var(--k-color-text-secondary)] cursor-pointer">
                            <input type="checkbox" x-model="agree" required class="w-4 h-4">
                            <span>Согласен с обработкой персональных данных</span>
                        </label>
                    </form>
                </div>
            </div>
        </section>

        {{-- ═══ 8. Совместный проект ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-heading font-bold mb-4">Совместный проект с Никасом Сафроновым</h2>
                        <p class="text-[var(--k-color-text-secondary)] leading-relaxed mb-6">
                            Уникальная коллаборация с известным дизайнером. Вместе мы создаём интерьерные решения, которые сочетают в себе красоту стекла и современный дизайн.
                        </p>
                        <a href="{{ route('works') }}" class="btn-primary">Смотреть проект</a>
                    </div>
                    <div class="aspect-[4/3] rounded-lg bg-[var(--k-color-bg-surface)] shadow-md flex items-center justify-center">
                        <div class="text-center text-[var(--k-color-text-muted)]">
                            <p class="text-sm">Изображение проекта</p>
                            <div class="flex justify-center gap-2 mt-4">
                                <span class="w-2.5 h-2.5 rounded-full bg-[var(--k-color-primary)]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[var(--k-color-border)]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[var(--k-color-border)]"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 9. Наши цены ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading"><h2>Наши цены</h2></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="price-card">
                        <div class="price-card__name">Эконом</div>
                        <div class="price-card__price">от 4 700 <span>₽/м²</span></div>
                        <ul class="price-card__features">
                            <li>Стандартное стекло 6мм</li>
                            <li>Однотонная печать</li>
                            <li>Монтаж включён</li>
                            <li>Гарантия 1 год</li>
                        </ul>
                        <a href="{{ route('contacts') }}" class="btn-primary w-full">Заказать</a>
                    </div>
                    <div class="price-card price-card--featured">
                        <div class="price-card__name">Стандарт</div>
                        <div class="price-card__price">от 6 800 <span>₽/м²</span></div>
                        <ul class="price-card__features">
                            <li>Закалённое стекло 6мм</li>
                            <li>УФ-печать любого рисунка</li>
                            <li>Монтаж включён</li>
                            <li>Защитная плёнка</li>
                            <li>Гарантия 3 года</li>
                        </ul>
                        <a href="{{ route('contacts') }}" class="btn-primary w-full">Заказать</a>
                    </div>
                    <div class="price-card">
                        <div class="price-card__name">Премиум</div>
                        <div class="price-card__price">от 35 000 <span>₽/м²</span></div>
                        <ul class="price-card__features">
                            <li>Закалённое стекло 8мм</li>
                            <li>3D-эффект или подсветка</li>
                            <li>Дизайн-проект</li>
                            <li>Премиум монтаж</li>
                            <li>Гарантия 5 лет</li>
                        </ul>
                        <a href="{{ route('contacts') }}" class="btn-primary w-full">Заказать</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 10. Отзывы ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading"><h2>Отзывы довольных клиентов</h2></div>
                <div x-data="testimonials([
                    { text: 'Отличная компания! Сделали скинали для кухни точно в срок. Качество стекла и печати на высшем уровне. Рекомендую!', author: 'Алексей М.' },
                    { text: 'Очень доволен результатом. Скинали с подсветкой смотрятся потрясающе. Спасибо команде ArtDecor за профессионализм!', author: 'Елена К.' },
                    { text: 'Заказывали панно для гостиной. Превзошли все ожидания! Дизайнер помог подобрать идеальный рисунок.', author: 'Дмитрий С.' },
                ])" class="relative">
                    <template x-for="(item, i) in items" :key="i">
                        <div x-show="current === i" x-transition class="testimonial">
                            <p class="testimonial__text" x-text="'«' + item.text + '»'"></p>
                            <p class="testimonial__author" x-text="'Отзыв от ' + item.author"></p>
                            <p class="text-xs text-[var(--k-color-text-muted)] mt-2">Опубликовано на Отзовик.ру</p>
                        </div>
                    </template>
                    <div class="flex justify-center gap-4 mt-4">
                        <button @click="prev()" class="slider-arrow" aria-label="Предыдущий отзыв">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="next()" class="slider-arrow" aria-label="Следующий отзыв">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 11. Видео ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading"><h2>АртДекор на Первом канале в передаче «Фазенда»</h2></div>
                <p class="text-center text-[var(--k-color-text-secondary)] max-w-2xl mx-auto mb-8">
                    Наша компания принимала участие в съёмках программы <a href="#" class="text-[var(--k-color-primary)] font-semibold hover:underline">«Фазенда»</a> на Первом канале. Посмотрите, как мы создаём наши изделия.
                </p>
                <div class="max-w-3xl mx-auto">
                    <div class="video-preview aspect-video bg-[var(--k-color-secondary)]">
                        <div class="video-preview__play">
                            <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 mt-4">
                        <div class="stars">
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-[var(--k-color-text-primary)]">4.9</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ 12. FAQ + Статистика ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading"><h2>Часто задаваемые вопросы</h2></div>
                <p class="text-center text-[var(--k-color-text-secondary)] mb-12">Более 12 лет опыта работы со стеклом</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="stat-block">
                        <div class="stat-block__number">12</div>
                        <div class="stat-block__label">лет на рынке</div>
                    </div>
                    <div class="stat-block">
                        <div class="stat-block__number">4 320+</div>
                        <div class="stat-block__label">довольных клиентов</div>
                    </div>
                    <div class="stat-block">
                        <div class="stat-block__number">от 7</div>
                        <div class="stat-block__label">рабочих дней</div>
                    </div>
                </div>

                <div class="max-w-3xl mx-auto" x-data="accordion()">
                    @php
                        $faqs = [
                            ['q' => 'Какие сроки изготовления скинали?', 'a' => 'Стандартный срок изготовления от 7 до 14 рабочих дней в зависимости от сложности и размера изделия.'],
                            ['q' => 'Какой у вас опыт работы?', 'a' => 'Более 12 лет мы специализируемся на производстве изделий из стекла.'],
                            ['q' => 'Какие гарантии вы даёте?', 'a' => 'Мы предоставляем гарантию от 1 до 5 лет в зависимости от выбранного тарифа.'],
                            ['q' => 'Можно ли установить скинали самостоятельно?', 'a' => 'Мы рекомендуем профессиональный монтаж, но предоставляем инструкции для самостоятельной установки.'],
                            ['q' => 'Какие способы оплаты вы принимаете?', 'a' => 'Мы принимаем наличные, безналичный расчёт, а также оплату банковской картой.'],
                            ['q' => 'Есть ли доставка в другие города?', 'a' => 'Да, мы осуществляем доставку по всей России транспортными компаниями.'],
                            ['q' => 'Как ухаживать за скинали?', 'a' => 'Для ухода используйте мягкую ткань и неабразивные моющие средства.'],
                            ['q' => 'Можно ли сделать скинали нестандартного размера?', 'a' => 'Да, мы изделия по индивидуальным размерам заказчика.'],
                            ['q' => 'Что входит в стоимость?', 'a' => 'Стоимость включает материалы, печать, закалку и монтаж.'],
                        ];
                    @endphp
                    @foreach($faqs as $i => $faq)
                        <div class="accordion-item">
                            <button class="accordion-item__header" @click="toggle({{ $i }})">
                                <span><span class="text-[var(--k-color-accent-blue)] mr-2">{{ $i + 1 }}.</span>{{ $faq['q'] }}</span>
                                <svg class="accordion-item__arrow" :class="openIndex === {{ $i }} && 'open'" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openIndex === {{ $i }}" x-collapse class="accordion-item__body">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ 13. Остались вопросы? ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
            <div class="max-w-page mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-3xl font-heading font-bold mb-4">Остались вопросы?</h2>
                        <p class="text-[var(--k-color-text-secondary)] leading-relaxed mb-6">
                            Свяжитесь с нами любым удобным способом. Мы всегда рады помочь вам с выбором идеального решения для вашего интерьера.
                        </p>
                        <div class="space-y-3">
                            <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="flex items-center gap-3 text-[var(--k-color-text-primary)] font-semibold hover:text-[var(--k-color-primary)] transition-colors">
                                <svg class="w-5 h-5 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ \App\Models\Setting::get('contacts.phone') }}
                            </a>
                            <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="flex items-center gap-3 text-[var(--k-color-text-primary)] font-semibold hover:text-[var(--k-color-primary)] transition-colors">
                                <svg class="w-5 h-5 text-[var(--k-color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ \App\Models\Setting::get('contacts.email') }}
                            </a>
                        </div>
                    </div>
                    <div>
                        <form @submit.prevent="submitCallback" class="space-y-4 bg-white p-6 rounded-lg shadow-md"
                              x-data="{ phone: '', name: '' }"
                              x-init="$data.submitCallback = async function() {
                                  await axios.post('/api/order', { name, phone, source: 'questions' });
                                  name = ''; phone = '';
                                  alert('Спасибо! Мы свяжемся с вами.');
                              }">
                            <h3 class="font-heading font-bold text-lg mb-4">Напишите нам</h3>
                            <input type="text" x-model="name" placeholder="Имя" required class="input-default">
                            <input type="tel" x-model="phone" placeholder="Телефон" required class="input-default">
                            <button type="submit" class="btn-primary w-full">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
