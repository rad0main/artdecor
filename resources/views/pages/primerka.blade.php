<x-layouts.app title="Онлайн примерка изображений на скинали">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="max-w-page mx-auto px-4 py-8"
          x-data="primerka()"
          x-init="init()">

        <h1 class="text-3xl font-heading font-bold text-[var(--k-color-text-primary)] mb-8">
            Онлайн примерка изображений на скинали
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Левая колонка: категории + цвета --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Категории --}}
                <div>
                    <h3 class="font-heading font-bold text-sm uppercase mb-3">Категории</h3>
                    <div class="space-y-1">
                        <template x-for="cat in categories" :key="cat.id">
                            <button @click="selectCategory(cat.id)"
                                    :class="{'text-[var(--k-color-primary)] font-bold': selectedCategory === cat.id}"
                                    class="block text-sm hover:text-[var(--k-color-primary)] transition-colors w-full text-left"
                                    x-text="cat.name">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Цвет верхних фасадов --}}
                <div>
                    <h3 class="font-heading font-bold text-sm uppercase mb-3">Цвет верхних фасадов</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="color in colors" :key="'top-' + color.id">
                            <button @click="selectTopColor(color.id)"
                                    :style="`background: ${color.hex}`"
                                    :class="{'ring-2 ring-offset-2 ring-[var(--k-color-primary)]': topColor === color.id}"
                                    class="w-8 h-8 rounded border border-gray-200 transition-all hover:scale-110"
                                    :title="color.name">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Цвет нижних фасадов --}}
                <div>
                    <h3 class="font-heading font-bold text-sm uppercase mb-3">Цвет нижних фасадов</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="color in colors" :key="'bottom-' + color.id">
                            <button @click="selectBottomColor(color.id)"
                                    :style="`background: ${color.hex}`"
                                    :class="{'ring-2 ring-offset-2 ring-[var(--k-color-primary)]': bottomColor === color.id}"
                                    class="w-8 h-8 rounded border border-gray-200 transition-all hover:scale-110"
                                    :title="color.name">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Центральная область: схема кухни --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- SVG-схема кухни --}}
                <div class="bg-[var(--k-color-bg-surface)] rounded-lg p-6 flex justify-center">
                    <svg viewBox="0 0 600 400" class="w-full max-w-[500px]">
                        {{-- Верхние фасады --}}
                        <rect x="20" y="20" width="560" height="100"
                              :fill="selectedTopColor"
                              rx="4" stroke="#ddd" stroke-width="1"/>

                        {{-- Фартук с изображением --}}
                        <image x="40" y="130" width="520" height="140"
                               xlink:href="" x-bind:href="selectedImage?.preview"
                               preserveAspectRatio="xMidYMid slice"/>

                        {{-- Бордюр-полка --}}
                        <rect x="20" y="270" width="560" height="6" fill="#ddd" rx="2"/>

                        {{-- Нижние фасады --}}
                        <rect x="20" y="280" width="560" height="100"
                              :fill="selectedBottomColor"
                              rx="4" stroke="#ddd" stroke-width="1"/>

                        {{-- Ручки --}}
                        <circle cx="100" cy="330" r="4" fill="#999"/>
                        <circle cx="300" cy="330" r="4" fill="#999"/>
                        <circle cx="500" cy="330" r="4" fill="#999"/>
                    </svg>
                </div>

                {{-- Артикул --}}
                <div class="text-center" x-show="selectedImage">
                    <p class="text-sm text-[var(--k-color-text-secondary)]">
                        Артикул: <strong class="text-[#3C3D41]" x-text="selectedImage?.title"></strong>
                    </p>
                </div>

                {{-- Кнопки действий --}}
                <div class="flex justify-center gap-4">
                    <button @click="addToFavorites()" :disabled="!selectedImage"
                            :class="{'opacity-50 cursor-not-allowed': !selectedImage}"
                            class="btn-primary px-6 py-3">
                        ♥ В избранное
                    </button>
                    <button @click="openOrderForm()" :disabled="!selectedImage"
                            :class="{'opacity-50 cursor-not-allowed': !selectedImage}"
                            class="btn-secondary px-6 py-3">
                        Заказать
                    </button>
                </div>

                {{-- Миниатюры изображений --}}
                <div>
                    <h3 class="font-heading font-bold text-sm uppercase mb-3">Выберите изображение</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        <template x-for="img in images" :key="img.id">
                            <img :src="img.thumb"
                                 :class="{'ring-2 ring-[var(--k-color-primary)]': selectedImage?.id === img.id}"
                                 @click="selectImage(img)"
                                 class="w-full aspect-square object-cover rounded cursor-pointer hover:opacity-80 transition-opacity"
                                 loading="lazy">
                        </template>
                    </div>
                </div>

                {{-- Моё избранное --}}
                <div x-data="favorites()" x-init="init()">
                    <h3 class="font-heading font-bold text-sm uppercase mb-3">
                        Моё избранное (<span x-text="items.length"></span>)
                    </h3>
                    <div class="flex gap-2 overflow-x-auto pb-2" x-show="items.length > 0">
                        <template x-for="fav in items" :key="fav.id">
                            <div class="relative flex-shrink-0">
                                <img :src="fav.thumb"
                                     @click="selectImage(fav)"
                                     class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-80 transition-opacity">
                                <button @click="toggle(fav.id)"
                                        class="absolute -top-1 -right-1 bg-white rounded-full p-0.5 text-xs shadow hover:text-[var(--k-color-primary)]">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                    <div x-show="items.length === 0" class="text-sm text-[var(--k-color-text-secondary)] py-4 text-center">
                        Добавьте изображения в избранное, чтобы быстро подставлять их в примерку
                    </div>
                </div>
            </div>
        </div>

        {{-- Модальное окно заказа --}}
        <x-modal name="order-form" title="Заказать">
            <form @submit.prevent="submitOrder" class="space-y-4">
                <input type="hidden" x-model="orderForm.article">
                <div>
                    <label class="block text-sm font-medium mb-1">Ваше имя</label>
                    <input type="text" x-model="orderForm.name" required
                           class="w-full px-3 py-2 border border-[var(--k-color-border)] rounded-lg text-sm
                                  focus:border-[var(--k-color-primary)] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Телефон</label>
                    <input type="tel" x-model="orderForm.phone" required
                           class="w-full px-3 py-2 border border-[var(--k-color-border)] rounded-lg text-sm
                                  focus:border-[var(--k-color-primary)] outline-none"
                           placeholder="+7 (999) 123-45-67">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Сообщение</label>
                    <textarea x-model="orderForm.message" rows="3"
                              class="w-full px-3 py-2 border border-[var(--k-color-border)] rounded-lg text-sm
                                     focus:border-[var(--k-color-primary)] outline-none"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full">Отправить заказ</button>
            </form>
        </x-modal>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
