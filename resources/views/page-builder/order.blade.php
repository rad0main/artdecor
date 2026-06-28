@php
    $heading = $heading ?? 'Оставить заявку';
    $headingColor = $heading_color ?? '#1a1a2e';
    $headingSize = $heading_size ?? 28;
    $text = $text ?? 'Заполните форму и мы свяжемся с вами в ближайшее время';
    $textColor = $text_color ?? '#333333';
    $textSize = $text_size ?? 16;
    $btnText = $btn_text ?? 'Отправить';
    $btnBgColor = $btn_bg_color ?? '#D32F2F';
    $btnTextColor = $btn_text_color ?? '#FFFFFF';
    $bgColor = $bg_color ?? '#f8f9fa';
    $privacyText = $privacy_text ?? 'Согласен с условиями обработки и хранения персональных данных';
@endphp

<section class="py-12 md:py-16" style="background-color: {{ $bgColor }};">
    <div class="mx-auto px-4 text-center" style="max-width: 900px;">
        <div class="section-heading">
            <h2 style="color: {{ $headingColor }}; font-size: {{ $headingSize }}px;">{{ $heading }}</h2>
        </div>
        <p class="mx-auto leading-relaxed font-heading -mt-2"
           style="color: {{ $textColor }}; font-size: {{ $textSize }}px;">
            {{ $text }}
        </p>

        <div x-data="orderForm()" class="mt-3 max-w-3xl mx-auto">
            <form @submit.prevent="submit">
                {{-- Name + Phone + Submit in one row --}}
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <input type="text" x-model="name" maxlength="30" required
                           placeholder="Имя"
                           class="flex-1 min-w-0 px-4 py-3 rounded-lg border border-gray-300 focus:border-[var(--k-color-primary)] focus:ring-2 focus:ring-[var(--k-color-primary)]/20 outline-none transition-all text-sm">
                    <input type="tel" x-model="phone" maxlength="20" required
                           placeholder="+7 (___) ___-__-__"
                           @input="formatPhone"
                           class="flex-1 min-w-0 px-4 py-3 rounded-lg border border-gray-300 focus:border-[var(--k-color-primary)] focus:ring-2 focus:ring-[var(--k-color-primary)]/20 outline-none transition-all text-sm">
                    <button type="submit"
                            :disabled="sending || !agreed"
                            class="flex-shrink-0 px-6 py-3 rounded-lg font-heading font-semibold text-base transition-all hover:brightness-110 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
                            :style="{ backgroundColor: '{{ $btnBgColor }}', color: '{{ $btnTextColor }}' }">
                        <span x-show="!sending">{{ $btnText }}</span>
                        <span x-show="sending" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Отправка...
                        </span>
                    </button>
                </div>

                {{-- Privacy checkbox --}}
                <div class="flex items-start justify-center gap-2 text-xs text-gray-500 mt-3">
                    <input type="checkbox" x-model="agreed"
                           class="mt-0.5 rounded border-gray-300 text-[var(--k-color-primary)] focus:ring-[var(--k-color-primary)] cursor-pointer"
                           checked>
                    <label class="text-left cursor-pointer leading-relaxed select-none">
                        {!! Str::of($privacyText)->replace(
                            'условиями',
                            '<a href="#" @click.prevent="$dispatch(\'open-privacy\')" class="text-[var(--k-color-primary)] underline hover:no-underline">условиями</a>'
                        ) !!}
                    </label>
                </div>
            </form>

            {{-- Success message --}}
            <div x-show="submitted" x-cloak
                 class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                Спасибо! Ваша заявка принята. Мы перезвоним вам в ближайшее время.
            </div>

            {{-- Error message --}}
            <div x-show="error" x-cloak
                 class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"
                 x-text="error">
            </div>
        </div>
    </div>

    {{-- Privacy modal --}}
    <div x-data="{ open: false }"
         @open-privacy.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="open = false"
             class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[80vh] overflow-y-auto p-6 relative">
            <button @click="open = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            <h3 class="text-lg font-heading font-semibold mb-3">Условия обработки и хранения персональных данных</h3>
            <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                <p>Нажимая кнопку «Отправить», вы даёте согласие на обработку своих персональных данных в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных».</p>
                <p>Ваши данные (имя и номер телефона) используются исключительно для связи с вами по вашему запросу и не передаются третьим лицам.</p>
                <p>Срок хранения персональных данных — до момента достижения цели их обработки или отзыва согласия субъектом персональных данных.</p>
                <p>Вы можете отозвать своё согласие в любой момент, направив уведомление на нашу электронную почту.</p>
            </div>
            <button @click="open = false"
                    class="mt-4 px-6 py-2 rounded-lg text-sm font-heading font-semibold text-white transition-all hover:brightness-110"
                    style="background-color: {{ $btnBgColor }};">
                Закрыть
            </button>
        </div>
    </div>
</section>
