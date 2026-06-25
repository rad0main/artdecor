<header class="w-full bg-white sticky top-0 z-50">
    {{-- Верхняя строка --}}
    <div class="max-w-page mx-auto px-4">
        <div class="flex items-center justify-between h-10 lg:h-10 text-xs">
            {{-- Левая часть --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <button type="button"
                        class="font-bold text-xs text-[var(--k-color-primary)] hover:underline transition-colors whitespace-nowrap"
                        x-data @click.prevent="$dispatch('open-modal', 'callback')">
                    Заказать обратный звонок
                </button>
                <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}"
                   class="flex items-center gap-1 font-semibold text-brand-accent hover:underline transition-colors whitespace-nowrap text-xs lg:text-sm">
                    <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ \App\Models\Setting::get('contacts.phone') }}
                </a>
                <div class="hidden sm:flex items-center gap-0.5">
                    <a href="https://vk.com/artdecor_photoglass" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity" aria-label="VK">
                        <svg class="w-4 h-4 text-[var(--k-color-primary)]" fill="currentColor" viewBox="0 0 24 24"><path d="M15.684 0H8.316C3.718 0 0 3.718 0 8.316v7.368C0 20.282 3.718 24 8.316 24h7.368C20.282 24 24 20.282 24 15.684V8.316C24 3.718 20.282 0 15.684 0zm3.554 16.728h-1.896c-.504 0-.66-.384-1.56-1.296-.78-.78-1.128-.876-1.332-.876-.264 0-.372.108-.372.648v1.104c0 .348-.12.528-1.008.528-1.488 0-3.144-.912-4.296-2.604-1.644-2.112-2.052-3.504-2.052-3.816 0-.168.072-.324.54-.324h1.896c.408 0 .564.18.732.612.792 2.016 2.112 3.792 2.652 3.792.204 0 .3-.108.3-.636v-2.484c-.072-1.104-.648-1.2-.648-1.596 0-.192.156-.36.348-.36h2.988c.36 0 .48.192.48.612v3.3c0 .36.156.48.252.48.204 0 .372-.12.732-.48.72-.816 1.26-1.944 1.26-1.944.072-.168.192-.312.372-.312h1.896c.552 0 .648.312.528.648-.288.972-2.136 3.456-2.136 3.456-.168.276-.216.408 0 .672.156.216.672.648 1.008 1.044.468.516.828.972 1.02 1.332.192.36.072.54-.42.54z"/></svg>
                    </a>
                    <a href="https://instagram.com/artdekor.photosteklo" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity" aria-label="Instagram">
                        <svg class="w-4 h-4 text-[var(--k-color-primary)]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://t.me/artdecor" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity" aria-label="Telegram">
                        <svg class="w-4 h-4 text-[var(--k-color-primary)]" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    </a>
                </div>
            </div>
            {{-- Правая часть --}}
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

    {{-- Полные разделительные линии (скрываются под логотипом) --}}
    <div class="border-t border-gray-200"></div>

    {{-- Строка логотипа — абсолютное позиционирование для наложения на линии --}}
    <div class="relative">
        <div class="max-w-page mx-auto px-4">
            {{-- Логотип по центру, перекрывает обе линии --}}
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                <div class="bg-white px-4 py-1">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="150" height="150" class="h-[110px] lg:h-[150px] w-auto">
                    </a>
                </div>
            </div>
            {{-- Невидимый заполнитель для высоты логотипа --}}
            <div class="h-[80px] lg:h-[100px]"></div>
        </div>
    </div>

    {{-- Нижняя линия --}}
    <div class="border-t border-gray-200"></div>

    {{-- Нижняя строка: навигация --}}
    <div class="max-w-page mx-auto px-4">
        <div class="flex items-center justify-between h-10 lg:h-10">
            {{-- Левая группа --}}
            <nav class="hidden lg:flex items-center">
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
                        </ul>
                    </li>
                </ul>
            </nav>

            {{-- Правая группа --}}
            <nav class="hidden lg:flex items-center">
                <ul class="flex items-center gap-0.5">
                    <li><a href="{{ route('contacts') }}" class="header-nav-link-light">Контакты</a></li>
                    <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <a href="{{ route('services') }}" class="header-nav-link-light flex items-center gap-1">
                            Услуги <span class="text-xs">▾</span>
                        </a>
                        <ul x-show="open" x-cloak
                            class="absolute top-full right-0 mt-0 bg-white rounded shadow-lg border border-gray-100 py-1 min-w-[200px] z-50"
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
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">
                    О компании <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="{{ route('contacts') }}" class="block text-[var(--k-color-text-secondary)] py-2">Контакты</a>
                    <a href="{{ route('works') }}" class="block text-[var(--k-color-text-secondary)] py-2">Отзывы</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">Видео</a>
                    <a href="{{ route('services') }}" class="block text-[var(--k-color-text-secondary)] py-2">О нас</a>
                </div>
            </div>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">
                    Каталог изображений <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1">
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">Однотонные скинали</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">Скинали с рисунком</a>
                    <a href="#" class="block text-[var(--k-color-text-secondary)] py-2">3D скинали</a>
                </div>
            </div>
            <a href="{{ route('contacts') }}" class="block text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">Контакты</a>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left text-[var(--k-color-text-primary)] font-semibold py-2 border-b border-gray-100">
                    Услуги <span class="text-xs transition-transform" :class="open && 'rotate-180'">▾</span>
                </button>
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
