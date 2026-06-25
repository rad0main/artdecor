<footer class="bg-[var(--k-color-footer-bg)] text-[var(--k-color-footer-text)]">
    <div class="max-w-page mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Логотип + описание --}}
            <div>
                <a href="{{ route('home') }}" class="no-underline block">
                    <img src="{{ asset('logo.svg') }}" alt="ArtDecor" width="150" height="150" class="h-24 lg:h-[150px] w-auto brightness-0 invert opacity-80 hover:opacity-100 transition-opacity">
                </a>
                <p class="text-sm mt-3 leading-relaxed">Производство и продажа стеклянных изделий в Москве. Скинали, панно, двери, перегородки.</p>
            </div>

            {{-- Навигация --}}
            <div>
                <h4 class="font-heading font-bold text-white text-sm uppercase tracking-wider mb-4">Навигация</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Главная</a></li>
                    <li><a href="{{ route('catalog') }}" class="hover:text-white transition-colors">Каталог изображений</a></li>
                    <li><a href="{{ route('works') }}" class="hover:text-white transition-colors">Наши работы</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-white transition-colors">Услуги</a></li>
                    <li><a href="{{ route('contacts') }}" class="hover:text-white transition-colors">Контакты</a></li>
                </ul>
            </div>

            {{-- Адрес --}}
            <div>
                <h4 class="font-heading font-bold text-white text-sm uppercase tracking-wider mb-4">Адрес</h4>
                <p class="text-sm mb-2">г. Москва, Дмитровское пр., 2А</p>
                <p class="text-sm">Дмитровский</p>
            </div>

            {{-- Контакты + соцсети --}}
            <div>
                <h4 class="font-heading font-bold text-white text-sm uppercase tracking-wider mb-4">Контакты</h4>
                <p class="text-sm mb-1"><a href="mailto:{{ \App\Models\Setting::get('contacts.email') }}" class="hover:text-white transition-colors">{{ \App\Models\Setting::get('contacts.email') }}</a></p>
                <p class="text-sm mb-1"><a href="tel:{{ \App\Models\Setting::get('contacts.phone') }}" class="text-white font-semibold hover:text-[var(--k-color-primary)] transition-colors">{{ \App\Models\Setting::get('contacts.phone') }}</a></p>
                <p class="text-sm">{{ \App\Models\Setting::get('contacts.work_hours') }}</p>
                <div class="flex gap-3 mt-4">
                    <a href="https://vk.com/artdecor_photoglass" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[var(--k-color-primary)] transition-colors" aria-label="VK">
                        <img src="{{ asset('images/icons/vk.svg') }}" alt="VK" width="22" height="22" class="w-[22px] h-[22px]">
                    </a>
                    <a href="https://instagram.com/artdekor.photosteklo" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[var(--k-color-primary)] transition-colors" aria-label="Instagram">
                        <img src="{{ asset('images/icons/instagram.svg') }}" alt="Instagram" width="22" height="22" class="w-[22px] h-[22px]">
                    </a>
                    <a href="https://t.me/artdecor" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[var(--k-color-primary)] transition-colors" aria-label="Telegram">
                        <img src="{{ asset('images/icons/telegram.svg') }}" alt="Telegram" width="22" height="22" class="w-[22px] h-[22px]">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-page mx-auto px-4 py-4 text-center text-xs text-[var(--k-color-text-muted)]">
            &copy; {{ date('Y') }} ArtDecor. Все права защищены.
        </div>
    </div>
</footer>
