<header class="w-full bg-[var(--k-color-secondary)] relative z-50">
    {{-- Верхняя полоса: контакты слева, логотип по центру, email/часы справа --}}
    <div class="header__top border-b border-white/10">
        <div class="max-w-page mx-auto px-4">
            <div class="flex items-center justify-between h-auto py-2 sm:py-3 relative">
                {{-- Левая часть: кнопка звонка, телефон, соцсети --}}
                <div class="flex items-center gap-2 sm:gap-4 text-xs lg:text-sm">
                    <button type="button"
                            class="header__btn font-bold text-[var(--k-color-primary)] hover:underline transition-colors whitespace-nowrap"
                            x-data @click.prevent="$dispatch('open-modal', 'callback')">
                        Заказать обратный звонок
                    </button>
                    <div class="header__phone">
                        <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}"
                           class="text-white font-bold whitespace-nowrap hover:text-[var(--k-color-primary)] transition-colors">
                            {{ \App\Models\Setting::get('contacts.phone') }}
                        </a>
                    </div>
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="https://vk.com/artdecor_photoglass" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity">
                            <img src="{{ asset('images/icons/vk_red.svg') }}" alt="VK" width="22" height="22" class="swing">
                        </a>
                        <a href="https://instagram.com/artdekor.photosteklo" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity">
                            <img src="{{ asset('images/icons/instagram.svg') }}" alt="Instagram" width="22" height="22" class="swing">
                        </a>
                    </div>
                </div>

                {{-- Логотип по центру --}}
                <div class="header__logo absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="78" height="78" class="h-10 lg:h-[78px] w-auto">
                    </a>
                </div>

                {{-- Правая часть: email, режим работы --}}
                <div class="flex items-center gap-2 sm:gap-4 text-xs lg:text-sm">
                    <div class="header__text">
                        <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}"
                           class="text-gray-300 hover:text-white transition-colors">
                            {{ \App\Models\Setting::get('contacts.email') }}
                        </a>
                    </div>
                    <div class="hidden md:block header__text text-gray-400">Режим работы</div>
                    <div class="header__time text-white font-semibold whitespace-nowrap">{{ \App\Models\Setting::get('contacts.work_hours') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Нижняя навигация: левое меню / правое меню --}}
    <div class="header__middle border-t border-white/5 bg-[var(--k-color-secondary)]">
        <div class="max-w-page mx-auto px-4">
            <div class="flex items-center justify-between h-12 lg:h-14">
                {{-- Левое меню --}}
                <nav class="hidden lg:flex items-center">
                    <ul class="flex items-center gap-1">
                        <li><a href="{{ route('home') }}" class="header-nav-link active">Главная</a></li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="#" class="header-nav-link flex items-center gap-1">
                                О компании
                                <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            <ul x-show="open" x-cloak
                                class="absolute top-full left-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[160px] z-50"
                                @mouseenter="open = true" @mouseleave="open = false">
                                <li><a href="{{ route('contacts') }}" class="dropdown-link">Контакты</a></li>
                                <li><a href="{{ route('works') }}" class="dropdown-link">Отзывы</a></li>
                                <li><a href="#" class="dropdown-link">Видео</a></li>
                                <li><a href="{{ route('services') }}" class="dropdown-link">О нас</a></li>
                                <li><a href="#" class="dropdown-link">Способы оплаты</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('catalog') }}" class="header-nav-link">Каталог изображений</a></li>
                    </ul>
                </nav>

                {{-- Правое меню --}}
                <nav class="hidden lg:flex items-center ml-auto">
                    <ul class="flex items-center gap-1">
                        <li><a href="{{ route('contacts') }}" class="header-nav-link">Контакты</a></li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('services') }}" class="header-nav-link flex items-center gap-1">
                                Услуги
                                <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            <ul x-show="open" x-cloak
                                class="absolute top-full left-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[200px] z-50"
                                @mouseenter="open = true" @mouseleave="open = false">
                                <li><a href="/uslugi/#skinali" class="dropdown-link">Скинали</a></li>
                                <li><a href="/uslugi/#skinali-s-podsvetkoj" class="dropdown-link">Скинали с подсветкой</a></li>
                                <li><a href="/uslugi/#zerkala" class="dropdown-link">Зеркала</a></li>
                                <li><a href="/uslugi/#panno-s-podsvetkoj" class="dropdown-link">Панно с подсветкой</a></li>
                                <li><a href="/uslugi/#panno" class="dropdown-link">Панно из стекла</a></li>
                                <li><a href="/uslugi/#dveri" class="dropdown-link">Двери из стекла</a></li>
                                <li><a href="/uslugi/#peregorodki" class="dropdown-link">Перегородки</a></li>
                                <li><a href="/uslugi/#triplex" class="dropdown-link">Триплекс</a></li>
                                <li><a href="/uslugi/#uf-print" class="dropdown-link">УФ-печать на стекле</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('services') }}#price" class="header-nav-link">Цены</a></li>
                        <li><a href="{{ route('works') }}" class="header-nav-link">Наши работы</a></li>
                    </ul>
                </nav>

                {{-- Мобильная кнопка --}}
                <button class="lg:hidden text-white ml-auto" x-data @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Меню">
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
         class="lg:hidden border-t border-white/10 bg-[var(--k-color-secondary)]">
        <div class="px-4 py-4 space-y-2 text-sm">
            <a href="{{ route('home') }}" class="block text-white font-bold py-1">Главная</a>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-1 text-white font-bold py-1 w-full text-left">
                    О компании
                    <svg class="w-3 h-3 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1">
                    <a href="{{ route('contacts') }}" class="block text-gray-300 py-1">Контакты</a>
                    <a href="{{ route('works') }}" class="block text-gray-300 py-1">Отзывы</a>
                    <a href="#" class="block text-gray-300 py-1">Видео</a>
                    <a href="{{ route('services') }}" class="block text-gray-300 py-1">О нас</a>
                </div>
            </div>
            <a href="{{ route('catalog') }}" class="block text-white font-bold py-1">Каталог изображений</a>
            <a href="{{ route('contacts') }}" class="block text-white font-bold py-1">Контакты</a>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-1 text-white font-bold py-1 w-full text-left">
                    Услуги
                    <svg class="w-3 h-3 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1">
                    <a href="/uslugi/#skinali" class="block text-gray-300 py-1">Скинали</a>
                    <a href="/uslugi/#skinali-s-podsvetkoj" class="block text-gray-300 py-1">Скинали с подсветкой</a>
                    <a href="/uslugi/#panno" class="block text-gray-300 py-1">Панно из стекла</a>
                    <a href="/uslugi/#dveri" class="block text-gray-300 py-1">Двери из стекла</a>
                    <a href="/uslugi/#peregorodki" class="block text-gray-300 py-1">Перегородки</a>
                </div>
            </div>
            <a href="{{ route('services') }}#price" class="block text-white font-bold py-1">Цены</a>
            <a href="{{ route('works') }}" class="block text-white font-bold py-1">Наши работы</a>
            <hr class="border-white/10 my-2">
            <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="block text-white font-bold py-1">{{ \App\Models\Setting::get('contacts.phone') }}</a>
            <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="block text-gray-300 py-1">{{ \App\Models\Setting::get('contacts.email') }}</a>
        </div>
    </div>
</header>
