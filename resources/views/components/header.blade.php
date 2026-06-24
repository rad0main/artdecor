<header class="w-full bg-white relative z-50 sticky top-0">
    {{-- Верхняя полоса --}}
    <div class="border-b border-[var(--k-color-border)]">
        <div class="max-w-page mx-auto flex justify-between items-center h-10 px-4 text-xs">
            <a href="#" class="underline hover:text-[var(--k-color-primary)] transition-colors"
               x-data @click.prevent="$dispatch('open-modal', 'callback')">
                Заказать обратный звонок
            </a>
            <span class="font-bold text-sm">{{ \App\Models\Setting::get('contacts.phone') }}</span>
        </div>
    </div>

    {{-- Основная навигация --}}
    <div class="max-w-page mx-auto flex justify-between items-center h-16 px-4">
        {{-- Левое меню --}}
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Главная</a>
            <a href="{{ route('catalog') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Каталог</a>
            <a href="{{ route('works') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Наши работы</a>
        </nav>

        {{-- Логотип --}}
        <a href="{{ route('home') }}" class="text-2xl font-heading font-bold text-[var(--k-color-secondary)] no-underline">
            ArtDecor
        </a>

        {{-- Правое меню --}}
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('primerka') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Примерка</a>
            <a href="{{ route('services') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Услуги</a>
            <a href="{{ route('contacts') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)] transition-colors">Контакты</a>
        </nav>

        {{-- Мобильное меню --}}
        <button class="lg:hidden" x-data @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Меню">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</header>
