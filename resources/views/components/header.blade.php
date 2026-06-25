<header class="w-full bg-white relative z-50 sticky top-0 shadow-sm">
    {{-- Верхняя полоса: контакты + соцсети --}}
    <div class="hidden lg:block border-b border-[var(--k-color-border)] bg-[var(--k-color-bg-surface)]">
        <div class="max-w-page mx-auto flex justify-between items-center h-10 px-4 text-xs">
            <div class="flex items-center gap-4">
                <button type="button"
                        class="text-[var(--k-color-primary)] font-bold hover:underline transition-colors"
                        x-data @click.prevent="$dispatch('open-modal', 'callback')">
                    Заказать обратный звонок
                </button>
                <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}"
                   class="font-bold text-sm text-[var(--k-color-secondary)] hover:text-[var(--k-color-primary)] transition-colors">
                    {{ \App\Models\Setting::get('contacts.phone') }}
                </a>
            </div>
            <div class="flex items-center gap-4 text-[var(--k-color-text-secondary)]">
                <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="hover:text-[var(--k-color-primary)] transition-colors">
                    {{ \App\Models\Setting::get('contacts.email') }}
                </a>
                <span>Режим работы</span>
                <span class="font-semibold text-[var(--k-color-secondary)]">{{ \App\Models\Setting::get('contacts.work_hours') }}</span>
            </div>
        </div>
    </div>

    {{-- Основная навигация --}}
    <div class="max-w-page mx-auto flex items-center justify-between h-16 lg:h-20 px-4">
        {{-- Левое меню --}}
        <nav class="hidden lg:flex items-center gap-6">
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="#" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors py-2 flex items-center gap-1">
                    О компании
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <div x-show="open" x-cloak
                     class="absolute top-full left-0 mt-0 bg-white rounded-lg shadow-lg border border-[var(--k-color-border)] py-2 min-w-[180px] z-50"
                     @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Главная</a>
                    <a href="{{ route('contacts') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Контакты</a>
                    <a href="{{ route('works') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Отзывы</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">О нас</a>
                </div>
            </div>
            <a href="{{ route('catalog') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors py-2">Каталог изображений</a>
        </nav>

        {{-- Логотип --}}
        <a href="{{ route('home') }}" class="flex-shrink-0 no-underline">
            <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="180" height="54" class="h-10 lg:h-[54px] w-auto">
        </a>

        {{-- Правое меню --}}
        <nav class="hidden lg:flex items-center gap-6">
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="#" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors py-2 flex items-center gap-1">
                    Услуги
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <div x-show="open" x-cloak
                     class="absolute top-full right-0 mt-0 bg-white rounded-lg shadow-lg border border-[var(--k-color-border)] py-2 min-w-[200px] z-50"
                     @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Скинали</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Панно из стекла</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Панно с подсветкой</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Двери из стекла</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Перегородки</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">Триплекс</a>
                    <a href="{{ route('services') }}" class="block px-4 py-2 text-sm hover:text-[var(--k-color-primary)] hover:bg-[var(--k-color-bg-surface)] transition-colors">УФ-печать на стекле</a>
                </div>
            </div>
            <a href="{{ route('primerka') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors py-2">Примерка</a>
            <a href="{{ route('works') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors py-2">Наши работы</a>
        </nav>

        {{-- Мобильное меню --}}
        <button class="lg:hidden" x-data @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Меню">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Мобильное меню (выезжающее) --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.outside="mobileMenuOpen = false"
         class="lg:hidden border-t border-[var(--k-color-border)] bg-white">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Главная</a>
            <a href="{{ route('catalog') }}" class="block text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Каталог</a>
            <a href="{{ route('services') }}" class="block text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Услуги</a>
            <a href="{{ route('primerka') }}" class="block text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Примерка</a>
            <a href="{{ route('works') }}" class="block text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Наши работы</a>
            <a href="{{ route('contacts') }}" class="block text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Контакты</a>
            <hr class="border-[var(--k-color-border)]">
            <a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="block font-bold text-sm">{{ \App\Models\Setting::get('contacts.phone') }}</a>
            <a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="block text-sm text-[var(--k-color-text-secondary)]">{{ \App\Models\Setting::get('contacts.email') }}</a>
        </div>
    </div>
</header>
