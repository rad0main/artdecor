<x-layouts.app title="Контакты">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="max-w-page mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-[var(--k-color-text-primary)] mb-8">
            Контакты
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            {{-- Контактная информация --}}
            <div class="space-y-6">
                <div class="card p-6 space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="text-xl">📍</span>
                        <div>
                            <p class="font-bold">Адрес</p>
                            <p class="text-sm text-[var(--k-color-text-secondary)]">{{ $address ?? 'Не указан' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="text-xl">📞</span>
                        <div>
                            <p class="font-bold">Телефон</p>
                            <p class="text-sm text-[var(--k-color-text-secondary)]">{{ $phone ?? 'Не указан' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="text-xl">✉️</span>
                        <div>
                            <p class="font-bold">Email</p>
                            <p class="text-sm text-[var(--k-color-text-secondary)]">{{ $email ?? 'Не указан' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="text-xl">🕐</span>
                        <div>
                            <p class="font-bold">Часы работы</p>
                            <p class="text-sm text-[var(--k-color-text-secondary)]">{{ $workHours ?? 'Не указаны' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Карта --}}
            <div class="card overflow-hidden" style="min-height: 400px;">
                <div id="map" class="w-full h-full min-h-[400px] bg-[var(--k-color-bg-surface)] flex items-center justify-center">
                    <p class="text-sm text-[var(--k-color-text-secondary)]">Карта загружается...</p>
                </div>
            </div>
        </div>

        {{-- Форма обратной связи --}}
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-heading font-bold text-center mb-8">Напишите нам</h2>
            <form @submit.prevent="submitContact" class="space-y-4"
                  x-data="{ name: '', phone: '', email: '', message: '' }"
                  x-init="$data.submitContact = async function() {
                      await axios.post('/api/order', { name, phone, message, source: 'question' });
                      name = ''; phone = ''; email = ''; message = '';
                      alert('Сообщение отправлено!');
                  }">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" x-model="name" placeholder="Ваше имя" required
                           class="w-full px-4 py-3 border border-[var(--k-color-border)] rounded-lg text-sm
                                  focus:border-[var(--k-color-primary)] outline-none">
                    <input type="tel" x-model="phone" placeholder="Телефон" required
                           class="w-full px-4 py-3 border border-[var(--k-color-border)] rounded-lg text-sm
                                  focus:border-[var(--k-color-primary)] outline-none">
                </div>
                <input type="email" x-model="email" placeholder="Email"
                       class="w-full px-4 py-3 border border-[var(--k-color-border)] rounded-lg text-sm
                              focus:border-[var(--k-color-primary)] outline-none">
                <textarea x-model="message" rows="4" placeholder="Ваше сообщение" required
                          class="w-full px-4 py-3 border border-[var(--k-color-border)] rounded-lg text-sm
                                 focus:border-[var(--k-color-primary)] outline-none"></textarea>
                <button type="submit" class="btn-primary w-full">Отправить сообщение</button>
            </form>
        </div>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
