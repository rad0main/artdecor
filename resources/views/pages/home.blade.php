<x-layouts.app title="Главная">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main>
        {{-- ═══ Hero-слайдер ═══ --}}
        <section class="relative bg-[var(--k-color-secondary)] overflow-hidden"
                 x-data="slider({{ json_encode($slides) }})" x-init="init()">
            <div class="relative h-[400px] md:h-[500px] lg:h-[600px]">
                {{-- Слайды --}}
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <div x-show="current === index"
                         x-transition:enter="transition-opacity duration-700"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img :src="slide.image" :alt="slide.title"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    </div>
                </template>

                {{-- Текст на слайдере --}}
                <div class="absolute inset-0 flex items-end">
                    <div class="w-full max-w-page mx-auto px-4 pb-16 md:pb-24">
                        <template x-for="(slide, index) in slides" :key="'text-' + slide.id">
                            <div x-show="current === index"
                                 x-transition:enter="transition-all duration-700 delay-200"
                                 x-transition:enter-start="opacity-0 translate-y-8"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <h1 class="text-3xl md:text-5xl lg:text-6xl font-heading font-bold text-white mb-4 drop-shadow-lg leading-tight"
                                    x-text="slide.title"></h1>
                                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl drop-shadow" x-text="slide.subtitle"></p>
                                <a :href="slide.link" class="btn-primary text-base md:text-lg px-10 py-4 inline-block"
                                   x-text="slide.btn_text || 'Подробнее'"></a>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Стрелки навигации --}}
                <template x-if="slides.length > 1">
                    <div class="hidden md:block">
                        <button @click="prev()"
                                class="slider-arrow absolute left-6 top-1/2 -translate-y-1/2 z-10"
                                aria-label="Предыдущий слайд">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click="next()"
                                class="slider-arrow absolute right-6 top-1/2 -translate-y-1/2 z-10"
                                aria-label="Следующий слайд">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </template>

                {{-- Точки пагинации --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(slide, index) in slides" :key="'dot-' + slide.id">
                        <button @click="current = index"
                                :class="current === index ? 'bg-white w-8' : 'bg-white/50 w-3'"
                                class="h-3 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        </section>

        {{-- ═══ Категории продукции (Наша продукция) ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading">
                    <h2>Наша продукция</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories as $cat)
                        <a href="{{ route('catalog') }}?category={{ $cat->id }}"
                           class="production-card">
                            <div class="production-card__image bg-[var(--k-color-bg-surface)]">
                                <div class="flex items-center justify-center h-full text-[var(--k-color-text-secondary)] text-sm">
                                    {{ $cat->name }}
                                </div>
                            </div>
                            <div class="production-card__title">{{ $cat->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ Преимущества (Почему выбирают нас) ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
            <div class="max-w-page mx-auto px-4">
                <div class="section-heading">
                    <h2>Почему выбирают нас</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- 1 --}}
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M36.3833 36.9995H33.2999C32.9589 36.9995 32.6832 36.7239 32.6832 36.3828V14.7994H4.31677V36.3831C4.31677 36.7241 4.04057 36.9998 3.70005 36.9998H0.616719C0.276198 36.9998 0 36.7241 0 36.3831V10.4826C0 10.2581 0.122084 10.051 0.31873 9.94307L18.202 0.0762132C18.3926 -0.0293316 18.6239 -0.0256559 18.8119 0.0843522L36.6952 10.5677C36.884 10.6779 36.9997 10.8804 36.9997 11.0993V36.3828C37 36.7239 36.7243 36.9995 36.3833 36.9995Z" fill="currentColor"/>
                                <path d="M30.8333 37H23.4332C23.0921 37 22.8165 36.7243 22.8165 36.3833V28.9831C22.8165 28.642 23.0921 28.3663 23.4332 28.3663H30.8333C31.1743 28.3663 31.45 28.642 31.45 28.9831V36.3833C31.4497 36.7243 31.1743 37 30.8333 37Z" fill="currentColor"/>
                                <path d="M21.5834 12.3333H15.4167C15.0762 12.3333 14.8 12.0571 14.8 11.7166V9.24997C14.8 8.90944 15.0762 8.63324 15.4167 8.63324H21.5834C21.9244 8.63324 22.2001 8.90944 22.2001 9.24997V11.7166C22.2001 12.0569 21.9244 12.3333 21.5834 12.3333Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Только закалённое стекло</h3>
                        <p class="feature-card__desc">Все изделия изготавливаются из закаленного стекла высокой прочности, безопасного и долговечного.</p>
                    </div>

                    {{-- 2 --}}
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32 9.19352C32 4.12435 27.8592 0 22.7693 0C19.2344 0 16.162 1.99065 14.6112 4.90311H0.615436C0.274796 4.90311 0 5.17706 0 5.51606V37.387C0 37.7261 0.274796 38 0.615436 38H27.6925C28.0329 38 28.3079 37.7263 28.3079 37.387V16.5313C30.5445 14.8518 32 12.1925 32 9.19352ZM27.077 36.7744H1.23087V6.12902H14.077C13.9349 6.52725 13.8104 6.93368 13.7241 7.35492H3.07691C2.73654 7.35492 2.46148 7.62861 2.46148 7.96788V15.3228C2.46148 15.6618 2.73627 15.9358 3.07691 15.9358H16.5145C18.1615 17.4523 20.3545 18.3873 22.7693 18.3873C24.3248 18.3873 25.7896 17.9989 27.077 17.319V36.7744ZM22.7693 17.1614C18.3582 17.1614 14.7691 13.5871 14.7691 9.19352C14.7691 4.80024 18.3579 1.22564 22.7693 1.22564C27.1806 1.22564 30.7694 4.79998 30.7694 9.19352C30.7691 13.5868 27.1803 17.1614 22.7693 17.1614Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Фиксированная стоимость</h3>
                        <p class="feature-card__desc">Прозрачное ценообразование без скрытых платежей. Точная цена известна до начала производства.</p>
                    </div>

                    {{-- 3 --}}
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg viewBox="0 0 33 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32.1148 27.1898L29.4997 25.6544L32.1078 24.1383C32.6661 23.8138 32.9998 23.2298 32.9998 22.576C32.9998 21.9253 32.6691 21.343 32.1148 21.0176L29.5005 19.483L32.1078 17.9661C32.6661 17.6415 32.9998 17.0576 32.9998 16.4038C32.9998 15.7531 32.6691 15.1707 32.1148 14.8454L29.7219 13.4403C29.7219 7.31729 29.7796 7.62583 29.5883 7.39361C29.4657 7.2445 29.5296 7.33359 27.0582 5.86665C26.786 5.70694 26.4376 5.80119 26.2808 6.07796C26.1238 6.35472 26.217 6.70889 26.4895 6.86833L27.9391 7.71899L23.5532 9.87825C23.3566 9.97494 23.2319 10.1778 23.2324 10.3997C23.233 10.6219 23.3582 10.8243 23.5554 10.9201L27.9298 13.053C26.2282 14.0422 17.6977 19.002 16.8172 19.5137C16.7414 19.5577 16.6436 19.5577 16.5677 19.5137L15.8639 19.1049C15.5909 18.9461 15.2433 19.0425 15.0879 19.3192C14.9316 19.5968 15.0264 19.9502 15.2986 20.1088L16.0033 20.5176C16.2127 20.6395 16.4507 20.7041 16.6919 20.7041C16.934 20.7041 17.172 20.6395 17.3814 20.5176L28.4955 14.0561L31.5461 15.8468C31.9676 16.0942 31.9692 16.7143 31.5437 16.962C23.3794 21.6903 26.8242 19.7052 17.0061 25.4132C16.8151 25.5243 16.5693 25.5243 16.3781 25.4132L7.52939 20.2693C7.25719 20.1104 6.90967 20.2068 6.7534 20.4844C6.59713 20.7612 6.69196 21.1145 6.96496 21.2734L15.8137 26.4179C16.0805 26.573 16.3848 26.6547 16.6919 26.6547C16.9999 26.6547 17.3039 26.573 17.571 26.4179L28.3492 20.143L31.5461 22.0193C31.9676 22.2667 31.9692 22.8868 31.5437 23.1345C31.2437 23.3088 31.2974 23.2784 17.0061 31.5863C16.8151 31.6974 16.5693 31.6965 16.3781 31.5863L13.2578 29.7717C12.9848 29.6128 12.6373 29.7092 12.4818 29.9868C12.3256 30.2635 12.4204 30.6177 12.6926 30.7758L15.8137 32.5904C16.0805 32.7455 16.384 32.8278 16.6919 32.8278C16.9999 32.8278 17.3039 32.7452 17.571 32.5904L28.3566 26.3193L31.5458 28.1917C31.9681 28.4397 31.9681 29.0609 31.5434 29.307L28.8668 30.8633C28.3617 31.1566 28.5701 31.9437 29.1497 31.9437C29.4192 31.9437 29.3153 31.9043 32.1076 30.3119C33.2936 29.6215 33.2987 27.8846 32.1148 27.1898Z" fill="currentColor"/>
                                <path d="M6.79589 6.83726C7.08626 6.83726 6.57311 7.02358 16.5667 1.19086C16.8066 1.04936 16.505 1.06647 24.5189 5.71145C24.7903 5.87116 25.1386 5.7761 25.2954 5.49933C25.4525 5.22338 25.3593 4.86921 25.0876 4.70977C17.1998 0.15279 17.3622 0.000148255 16.692 0.000148255C16.0276 0.000148255 16.6084 -0.0924691 6.51087 5.75844C6.00815 6.05313 6.21544 6.83726 6.79589 6.83726Z" fill="currentColor"/>
                                <path d="M26.8921 32.0111L17.0059 37.7585C16.8149 37.8696 16.5691 37.8696 16.3779 37.7585L14.5743 36.7098C14.3013 36.5509 13.9537 36.6474 13.7983 36.925C13.642 37.2017 13.7368 37.5559 14.0098 37.714C15.6403 38.5972 15.9336 39.0003 16.6917 39.0003C17.5241 39.0003 17.0021 38.9989 27.457 33.0152C27.7292 32.8571 27.824 32.5032 27.6678 32.2262C27.5126 31.9486 27.1645 31.8522 26.8921 32.0111Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Собственное производство</h3>
                        <p class="feature-card__desc">Полный цикл производства на собственной фабрике. Контроль качества на каждом этапе.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ Текстовый блок о компании ═══ --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-page mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-heading font-bold mb-6 leading-tight">
                            Элегантный дизайн<br>любой комнаты
                        </h2>
                        <div class="text-[var(--k-color-text-secondary)] leading-relaxed space-y-4">
                            <p>Уникальность и красота — вот что отличает изделия из стекла от компании ArtDecor. Мы специализируемся на производстве стен, дверей, потолков и фартуков для кухни (скинали) из стекла.</p>
                            <p>Наши изделия не просто функциональны, они еще и великолепно смотрятся благодаря уникальной подсветке, которая добавляет цвета и создает эффект объемности на стекле.</p>
                            <p>Вы можете придать своей кухне или любому другому помещению роскошный вид, выбрав из нашего широкого ассортимента изображений.</p>
                        </div>
                        <a href="{{ route('catalog') }}" class="btn-primary mt-6 inline-flex">
                            Смотреть каталог
                        </a>
                    </div>
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-[var(--k-color-bg-surface)] shadow-lg">
                            @if($featuredWorks->isNotEmpty())
                                <img src="{{ $featuredWorks->first()->thumb_url ?? '' }}" alt="Наши работы"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-[var(--k-color-text-muted)] text-lg">
                                    ArtDecor
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ Последние работы ═══ --}}
        @if($featuredWorks->isNotEmpty())
            <section class="py-16 md:py-20 bg-[var(--k-color-bg-surface)]">
                <div class="max-w-page mx-auto px-4">
                    <div class="section-heading">
                        <h2>Наши работы</h2>
                    </div>
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
                    <div class="text-center mt-10">
                        <a href="{{ route('works') }}" class="btn-secondary inline-flex">
                            Смотреть все работы
                        </a>
                    </div>
                </div>
            </section>
        @endif

        {{-- ═══ Форма обратной связи ═══ --}}
        <section class="py-16 md:py-20 bg-[var(--k-color-primary)] text-white">
            <div class="max-w-page mx-auto px-4">
                <div class="max-w-lg mx-auto text-center">
                    <h2 class="text-3xl md:text-4xl font-heading font-bold mb-4">Закажите звонок</h2>
                    <p class="text-lg text-white/90 mb-8">Оставьте заявку и мы перезвоним вам в течение 2 часов</p>

                    <form @submit.prevent="submitCallback" class="space-y-4"
                          x-data="{ phone: '', name: '' }"
                          x-init="$data.submitCallback = async function() {
                              await axios.post('/api/order', { name, phone, source: 'callback' });
                              name = ''; phone = '';
                              alert('Спасибо! Мы перезвоним вам.');
                          }">
                        <input type="text" x-model="name" placeholder="Ваше имя" required
                               class="w-full px-5 py-4 rounded-lg text-sm text-[var(--k-color-text-primary)]
                                      placeholder-gray-400 outline-none focus:ring-2 focus:ring-white/50">
                        <input type="tel" x-model="phone" placeholder="+7 (999) 123-45-67" required
                               class="w-full px-5 py-4 rounded-lg text-sm text-[var(--k-color-text-primary)]
                                      placeholder-gray-400 outline-none focus:ring-2 focus:ring-white/50">
                        <button type="submit"
                                class="w-full px-8 py-4 rounded-lg font-bold text-base
                                       bg-white text-[var(--k-color-primary)]
                                       hover:bg-gray-100 transition-colors">
                            Заказать звонок
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
