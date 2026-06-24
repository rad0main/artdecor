<x-layouts.app title="Наши работы">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="max-w-page mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-[var(--k-color-text-primary)] mb-8">
            Наши работы
        </h1>

        {{-- Фильтр по категориям --}}
        <div class="flex flex-wrap gap-2 mb-8"
             x-data="{ category: '' }">
            <button @click="category = ''; loadWorks()"
                    :class="category === '' ? 'bg-[var(--k-color-primary)] text-white' : 'bg-[var(--k-color-bg-surface)] hover:bg-[var(--k-color-bg-surface-hover)]'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                Все
            </button>
            @foreach($categories as $cat)
                <button @click="category = '{{ $cat->id }}'; loadWorks()"
                        :class="category === '{{ $cat->id }}' ? 'bg-[var(--k-color-primary)] text-white' : 'bg-[var(--k-color-bg-surface)] hover:bg-[var(--k-color-bg-surface-hover)]'"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Сетка работ --}}
        <div x-data="worksGrid()" x-init="init()">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="work in works" :key="work.id">
                    <div class="work-card relative overflow-hidden rounded-lg group cursor-pointer"
                         @click="openLightbox(work)">
                        <div class="aspect-[4/3] bg-[var(--k-color-bg-surface)]">
                            <img :src="work.thumb" :alt="work.title"
                                 loading="lazy"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="text-white font-bold text-sm" x-text="work.title"></h3>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="text-center py-8">
                <button x-show="hasMore" @click="loadMore()"
                        class="btn-primary px-8 py-3">
                    Загрузить ещё
                </button>
            </div>
        </div>

        {{-- Lightbox --}}
        <div x-data="lightbox()" x-show="open" x-init="init()"
             class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
             style="display: none"
             @keydown.escape.window="close()">
            <button @click="close()" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
            <div @click.outside="close()">
                <img :src="currentImage?.preview" :alt="currentImage?.title"
                     class="max-w-full max-h-[90vh] object-contain rounded">
            </div>
        </div>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
