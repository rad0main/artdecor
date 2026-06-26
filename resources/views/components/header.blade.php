<header class="w-full bg-white sticky top-0 z-50"
        x-data="headerScroll()"
        :class="scrolled ? 'shadow-md' : ''">
    {{-- Верхняя часть — схлопывается при скролле --}}
    <div class="overflow-hidden transition-all duration-300 ease-in-out"
         :class="scrolled ? 'max-h-0 opacity-0' : 'max-h-[200px] opacity-100'">
        {{-- Топ-бар --}}
        <div class="max-w-page mx-auto px-4">
            <div class="flex items-center justify-between h-10 lg:h-10 text-xs">
                <div class="flex items-center gap-2 sm:gap-3">
                    <button type="button"
                            class="text-xs text-[var(--k-color-primary)] hover:underline transition-colors whitespace-nowrap font-heading"
                            x-data @click.prevent="$dispatch('open-modal', 'callback')">
                        Заказать обратный звонок
                    </button>
                    <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}"
                       class="flex items-center gap-1 text-brand-accent hover:underline transition-colors whitespace-nowrap text-xs lg:text-sm font-heading">
                        <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ \App\Models\Setting::get('contacts.phone') }}
                    </a>
                    <div class="hidden sm:flex items-center gap-1.5">
                        <a href="https://vk.com/artdecor_photoglass" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity" aria-label="VK">
                            <img src="{{ asset('images/icons/vk.svg') }}" alt="VK" width="20" height="20" class="w-5 h-5">
                        </a>
                        <a href="https://instagram.com/artdekor.photosteklo" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity" aria-label="Instagram">
                            <img src="{{ asset('images/icons/instagram.svg') }}" alt="Instagram" width="20" height="20" class="w-5 h-5">
                        </a>
                        <a href="https://t.me/artdecor" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity" aria-label="Telegram">
                            <img src="{{ asset('images/icons/telegram.svg') }}" alt="Telegram" width="20" height="20" class="w-5 h-5">
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-5 text-xs">
                    <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}"
                       class="text-[var(--k-color-text-secondary)] hover:text-[var(--k-color-text-primary)] transition-colors">
                        {{ \App\Models\Setting::get('contacts.email') }}
                    </a>
                    <div class="text-[var(--k-color-text-secondary)] whitespace-nowrap">
                        <span class="font-semibold text-[var(--k-color-text-primary)]">Пн-Вс:</span> {{ \App\Models\Setting::get('contacts.work_hours') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Разделитель --}}
        <div class="border-t border-gray-200"></div>

        {{-- Большой логотип --}}
        <div class="relative flex items-center justify-center h-[80px] lg:h-[100px]">
            <div class="bg-white px-4 py-1">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="150" height="150" class="h-[80px] lg:h-[110px] w-auto">
                </a>
            </div>
        </div>

        {{-- Разделитель --}}
        <div class="border-t border-gray-200"></div>
    </div>

    {{-- Нижняя строка: навигация + мини-логотип --}}
    <div class="max-w-page mx-auto px-4">
        <div class="transition-all duration-300 ease-in-out"
             :class="scrolled ? 'h-[95px] lg:h-[95px]' : 'h-12 lg:h-14'">
            <div class="flex items-center justify-between h-full">
                {{-- Левое меню --}}
                <nav class="hidden lg:flex items-center flex-shrink-0">
                    <ul class="flex items-center gap-0.5">
                        <li><a href="{{ route('home') }}" class="header-nav-link-light active">Главная</a></li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="#" class="header-nav-link-light flex items-center gap-1">О компании <span class="text-xs">▾</span></a>
                            <ul x-show="open" x-cloak class="absolute top-full left-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[160px] z-50"
                                @mouseenter="open = true" @mouseleave="open = false">
                                <li><a href="{{ route('contacts') }}" class="dropdown-link">Контакты</a></li>
                                <li><a href="{{ route('works') }}" class="dropdown-link">Отзывы</a></li>
                                <li><a href="#" class="dropdown-link">Видео</a></li>
                                <li><a href="{{ route('services') }}" class="dropdown-link">О нас</a></li>
                                <li><a href="#" class="dropdown-link">Способы оплаты</a></li>
                            </ul>
                        </li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('catalog') }}" class="header-nav-link-light flex items-center gap-1">Каталог изображений <span class="text-xs">▾</span></a>
                            <ul x-show="open" x-cloak class="absolute top-full left-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[180px] z-50"
                                @mouseenter="open = true" @mouseleave="open = false">
                                <li><a href="#" class="dropdown-link">Однотонные скинали</a></li>
                                <li><a href="#" class="dropdown-link">Скинали с рисунком</a></li>
                                <li><a href="#" class="dropdown-link">3D скинали</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>

                {{-- Мини-логотип по центру --}}
                <div class="flex items-center justify-center flex-shrink-0 overflow-hidden transition-all duration-300"
                     :class="scrolled ? 'w-[90px] opacity-100' : 'w-0 opacity-0'">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="90" height="90" class="h-[70px] lg:h-[85px] w-auto">
                        </a>
                    </div>
                </div>

                {{-- Правое меню --}}
                <nav class="hidden lg:flex items-center flex-shrink-0">
                    <ul class="flex items-center gap-0.5">
                        <li><a href="{{ route('contacts') }}" class="header-nav-link-light">Контакты</a></li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('services') }}" class="header-nav-link-light flex items-center gap-1">Услуги <span class="text-xs">▾</span></a>
                            <ul x-show="open" x-cloak class="absolute top-full right-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[200px] z-50"
                                @mouseenter="open = true" @mouseleave="open = false">
                                <li><a href="{{ route('services') }}#skinali" class="dropdown-link">Скинали</a></li>
                                <li><a href="{{ route('services') }}#skinali-s-podsvetkoj" class="dropdown-link">Скинали с подсветкой</a></li>
                                <li><a href="{{ route('services') }}#panno" class="dropdown-link">Панно из стекла</a></li>
                                <li><a href="{{ route('services') }}#panno-s-podsvetkoj" class="dropdown-link">Панно с подсветкой</a></li>
                                <li><a href="{{ route('services') }}#dveri" class="dropdown-link">Двери из стекла</a></li>
                                <li><a href="{{ route('services') }}#peregorodki" class="dropdown-link">Перегородки</a></li>
                                <li><a href="{{ route('services') }}#triplex" class="dropdown-link">Триплекс</a></li>
                                <li><a href="{{ route('services') }}#uf-print" class="dropdown-link">УФ-печать на стекле</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('services') }}#price" class="header-nav-link-light">Цены</a></li>
                        <li><a href="{{ route('works') }}" class="header-nav-link-light">Наши работы</a></li>
                    </ul>
                </nav>

                <button class="lg:hidden text-[var(--k-color-text-primary)] ml-auto" x-data @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Меню">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Мобильное меню --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.outside="mobileMenuOpen = false"
         class="lg:hidden border-b border-gray-200 bg-white shadow-md">
        <div class="px-4 py-4 space-y-1 text-sm">
            <a href="{{ route('home') }}" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Главная</a>
            <div x-data="{ open: false }"><button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">О компании <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span></button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="{{ route('contacts') }}" class="block text-[var(--k-color-text-secondary)] py-2">Контакты</a>
                    <a href="{{ route('works') }}" class="block text-[var(--k-color-text-secondary)] py-2">Отзывы</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">Видео</a>
                    <a href="{{ route('services') }}" class="block text-[var(--k-color-text-secondary)] py-2">О нас</a>
                </div>
            </div>
            <div x-data="{ open: false }"><button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Каталог изображений <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span></button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">Однотонные скинали</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">Скинали с рисунком</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">3D скинали</a>
                </div>
            </div>
            <a href="{{ route('contacts') }}" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Контакты</a>
            <div x-data="{ open: false }"><button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Услуги <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span></button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="{{ route('services') }}#skinali" class="block text-[var(--k-color-text-secondary)] py-2">Скинали</a>
                    <a href="{{ route('services') }}#panno" class="block text-[var(--k-color-text-secondary)] py-2">Панно из стекла</a>
                    <a href="{{ route('services') }}#dveri" class="block text-[var(--k-color-text-secondary)] py-2">Двери из стекла</a>
                    <a href="{{ route('services') }}#peregorodki" class="block text-[var(--k-color-text-secondary)] py-2">Перегородки</a>
                </div>
            </div>
            <a href="{{ route('services') }}#price" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Цены</a>
            <a href="{{ route('works') }}" class="block text-[var(--k-color-text-primary)] font-semibold py-2">Наши работы</a>
            <hr class="my-3 border-gray-200">
            <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="block text-brand-accent font-bold py-1">{{ \App\Models\Setting::get('contacts.phone') }}</a>
            <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="block text-[var(--k-color-text-secondary)] py-1">{{ \App\Models\Setting::get('contacts.email') }}</a>
        </div>
    </div>
</header>
