<x-layouts.app title="Каталог изображений">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="max-w-page mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-[var(--k-color-text-primary)] mb-8">
            Каталог изображений
        </h1>

        <x-filters :categories="$categories" :colors="$colors" />

        <div id="catalog-grid"
             x-data="catalogGrid()"
             x-init="init()">

            {{-- Сетка карточек --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="image in images" :key="image.id">
                    <div class="card group">
                        <div class="relative aspect-[4/3] overflow-hidden bg-[var(--k-color-bg-surface)]">
                            <img :src="image.thumb"
                                 :alt="image.title"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <span class="text-sm text-[var(--k-color-text-secondary)]" x-text="image.title || 'Без артикула'"></span>
                            <button @click="toggleFavorite(image.id)"
                                    class="text-xl transition-all duration-200 hover:scale-110"
                                    :class="isFavorite(image.id) ? 'text-[var(--k-color-primary)]' : 'text-[var(--k-color-text-secondary)]'"
                                    aria-label="В избранное">
                                <span x-text="isFavorite(image.id) ? '♥' : '♡'">♡</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Пустое состояние --}}
            <div x-show="!loading && images.length === 0" class="text-center py-16">
                <p class="text-lg text-[var(--k-color-text-secondary)]">Изображения не найдены</p>
            </div>

            {{-- Скелетон при загрузке --}}
            <div x-show="loading && images.length === 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
                <template x-for="i in 8" :key="i">
                    <div class="animate-pulse">
                        <div class="aspect-[4/3] bg-gray-200 rounded-t-lg"></div>
                        <div class="p-3">
                            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Пагинация / Infinite scroll --}}
            <div class="text-center py-8">
                <button x-show="hasMore" @click="loadMore()"
                        :disabled="loading"
                        class="btn-primary px-8 py-3">
                    <span x-show="!loading">Загрузить ещё</span>
                    <span x-show="loading">Загрузка...</span>
                </button>
            </div>
        </div>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
