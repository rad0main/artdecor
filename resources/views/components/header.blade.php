<header class="w-full bg-white border-b border-gray-200">
    {{-- Верхняя панель: телефон | email | часы --}}
    <div class="border-b border-gray-200">
        <div class="max-w-page mx-auto px-4">
            <div class="flex items-center justify-between h-10 text-xs md:text-sm">
                {{-- Левая часть: телефон с иконкой --}}
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[var(--k-color-accent-blue)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="text-brand-accent font-semibold hover:underline">
                        {{ \App\Models\Setting::get('contacts.phone') }}
                    </a>
                </div>

                {{-- Центр: email --}}
                <div class="text-[var(--k-color-text-secondary)]">
                    <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="hover:text-[var(--k-color-text-primary)] transition-colors">
                        {{ \App\Models\Setting::get('contacts.email') }}
                    </a>
                </div>

                {{-- Правая часть: режим работы --}}
                <div class="text-[var(--k-color-text-secondary)]">
                    <span class="font-semibold text-[var(--k-color-text-primary)]">Пн-Вс:</span> {{ \App\Models\Setting::get('contacts.work_hours') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Логотип по центру --}}
    <div class="flex justify-center py-3 lg:py-4 border-b border-gray-200">
        <a href="{{ route('home') }}" class="block">
            <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="78" height="78" class="h-12 lg:h-[78px] w-auto">
        </a>
    </div>

    {{-- Навигация --}}
    <div class="border-b border-gray-200">
        <div class="max-w-page mx-auto px-4">
            <div class="flex items-center justify-between h-12 lg:h-14">
                {{-- Левая группа навигации --}}
                <nav class="flex items-center">
                    <ul class="flex items-center gap-0.5">
                        <li><a href="{{ route('home') }}" class="header-nav-link-light active">Главная</a></li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="#" class="header-nav-link-light flex items-center gap-1">
                                О компании <span class="text-xs">▾</span>
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
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('catalog') }}" class="header-nav-link-light flex items-center gap-1">
                                Каталог изображений <span class="text-xs">▾</span>
                            </a>
                            <ul x-show="open" x-cloak
                                class="absolute top-full left-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[180px] z-50"
                                @mouseenter="open = true" @mouseleave="open = false">
                                <li><a href="#" class="dropdown-link">Однотонные скинали</a></li>
                                <li><a href="#" class="dropdown-link">Скинали с рисунком</a></li>
                                <li><a href="#" class="dropdown-link">3D скинали</a></li>
                                <li><a href="#" class="dropdown-link">Прозрачные скинали</a></li>
                                <li><a href="#" class="dropdown-link">Светящиеся скинали</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>

                {{-- Правая группа навигации --}}
                <nav class="flex items-center">
                    <ul class="flex items-center gap-0.5">
                        <li><a href="{{ route('contacts') }}" class="header-nav-link-light">Контакты</a></li>
                        <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('services') }}" class="header-nav-link-light flex items-center gap-1">
                                Услуги <span class="text-xs">▾</span>
                            </a>
                            <ul x-show="open" x-cloak
                                class="absolute top-full left-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[200px] z-50"
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

                {{-- Мобильная кнопка --}}
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

            {{-- О компании --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">
                    О компании
                    <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="{{ route('contacts') }}" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Контакты</a>
                    <a href="{{ route('works') }}" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Отзывы</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Видео</a>
                    <a href="{{ route('services') }}" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">О нас</a>
                </div>
            </div>

            {{-- Каталог изображений --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">
                    Каталог изображений
                    <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Однотонные скинали</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Скинали с рисунком</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">3D скинали</a>
                </div>
            </div>

            <a href="{{ route('contacts') }}" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Контакты</a>

            {{-- Услуги --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">
                    Услуги
                    <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="{{ route('services') }}#skinali" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Скинали</a>
                    <a href="{{ route('services') }}#panno" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Панно из стекла</a>
                    <a href="{{ route('services') }}#dveri" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Двери из стекла</a>
                    <a href="{{ route('services') }}#peregorodki" class="block text-[var(--k-color-text-secondary)] py-2 border-b border-gray-50">Перегородки</a>
                </div>
            </div>

            <a href="{{ route('services') }}#price" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Цены</a>
            <a href="{{ route('works') }}" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Наши работы</a>

            <hr class="my-3 border-gray-200">
            <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="block text-brand-accent font-bold py-1">{{ \App\Models\Setting::get('contacts.phone') }}</a>
            <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="block text-[var(--k-color-text-secondary)] py-1">{{ \App\Models\Setting::get('contacts.email') }}</a>
        </div>
    </div>
</header>
