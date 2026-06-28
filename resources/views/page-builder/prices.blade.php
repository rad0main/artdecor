@php
    $heading = $heading ?? 'Наши цены';
    $headingColor = $heading_color ?? '#1a1a2e';
    $headingSize = $heading_size ?? 28;
    $prices = $prices ?? [];
@endphp

<section class="py-16 md:py-20 px-4">
    <div class="max-w-page mx-auto">
        @if($heading)
            <div class="section-heading">
                <h2 style="color: {{ $headingColor }}; font-size: {{ $headingSize }}px;">{{ $heading }}</h2>
            </div>
        @endif

        <div x-data="pricesOrder()" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            @foreach($prices as $index => $price)
                @php
                    $isFeatured = $price['featured'] ?? false;
                    $features = is_string($price['features'] ?? '')
                        ? array_filter(explode("\n", $price['features']))
                        : ($price['features'] ?? []);
                @endphp
                <div class="price-card @if($isFeatured) price-card--featured @endif">
                    <div class="price-card__name">{{ $price['name'] ?? '' }}</div>
                    <div class="price-card__price">от {{ $price['price'] ?? '' }} <span>{{ $price['unit'] ?? '₽/м²' }}</span></div>
                    @if(!empty($features))
                        <ul class="price-card__features">
                            @foreach($features as $feature)
                                <li>{{ trim($feature) }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button @click="open('{{ $price['name'] }}', '{{ $price['price'] }}', '{{ $price['unit'] }}')"
                            class="btn-primary w-full text-center cursor-pointer">
                        {{ $price['btn_text'] ?? 'Заказать' }}
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Order modal --}}
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background-color: rgba(0,0,0,0.5);">
            <div @click.away="modalOpen = false"
                 class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
                <button @click="modalOpen = false"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                <h3 class="text-lg font-heading font-semibold mb-1">Заказать</h3>
                <p class="text-sm text-gray-500 mb-4" x-text="'Тариф: ' + tariffName + ' — ' + tariffPrice + ' ' + tariffUnit"></p>

                <form @submit.prevent="submit" class="flex flex-col gap-3">
                    <input type="text" x-model="name" maxlength="30" required
                           placeholder="Имя"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[var(--k-color-primary)] focus:ring-2 focus:ring-[var(--k-color-primary)]/20 outline-none transition-all text-sm">
                    <input type="tel" x-model="phone" maxlength="20" required
                           placeholder="+7 (___) ___-__-__"
                           @input="formatPhone"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[var(--k-color-primary)] focus:ring-2 focus:ring-[var(--k-color-primary)]/20 outline-none transition-all text-sm">

                    <div class="flex items-start gap-2 text-xs text-gray-500 mt-1">
                        <input type="checkbox" x-model="agreed"
                               class="mt-0.5 rounded border-gray-300 text-[var(--k-color-primary)] focus:ring-[var(--k-color-primary)] cursor-pointer"
                               checked>
                        <label class="cursor-pointer leading-relaxed select-none">
                            Согласен с <a href="#" @click.prevent="$dispatch('open-privacy')" class="text-[var(--k-color-primary)] underline hover:no-underline">условиями</a> обработки и хранения персональных данных
                        </label>
                    </div>

                    <button type="submit" :disabled="sending || !agreed"
                            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!sending">Отправить</span>
                        <span x-show="sending" class="inline-flex items-center gap-2 justify-center">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Отправка...
                        </span>
                    </button>
                </form>

                <div x-show="submitted" x-cloak class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm text-center">
                    Спасибо! Заявка принята.
                </div>
                <div x-show="error" x-cloak class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm text-center" x-text="error"></div>
            </div>
        </div>

        {{-- Privacy modal --}}
        <div x-data="{ privacyOpen: false }"
             @open-privacy.window="privacyOpen = true"
             x-show="privacyOpen"
             x-cloak
             class="fixed inset-0 z-[60] flex items-center justify-center p-4"
             style="background-color: rgba(0,0,0,0.5);">
            <div @click.away="privacyOpen = false"
                 class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[80vh] overflow-y-auto p-6 relative">
                <button @click="privacyOpen = false"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                <h3 class="text-lg font-heading font-semibold mb-3">Условия обработки и хранения персональных данных</h3>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>Нажимая кнопку «Отправить», вы даёте согласие на обработку своих персональных данных в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных».</p>
                    <p>Ваши данные (имя и номер телефона) используются исключительно для связи с вами по вашему запросу и не передаются третьим лицам.</p>
                    <p>Срок хранения персональных данных — до момента достижения цели их обработки или отзыва согласия субъектом персональных данных.</p>
                    <p>Вы можете отозвать своё согласие в любой момент, направив уведомление на нашу электронную почту.</p>
                </div>
                <button @click="privacyOpen = false"
                        class="mt-4 px-6 py-2 rounded-lg text-sm font-heading font-semibold text-white transition-all hover:brightness-110"
                        style="background-color: var(--k-color-primary);">
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</section>
