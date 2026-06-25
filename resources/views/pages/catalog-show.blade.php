<x-layouts.app :title="$image?->title ?? 'Каталог изображений'">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="max-w-page mx-auto px-4 py-8">
        {{-- Хлебные крошки --}}
        <nav class="flex items-center gap-2 text-sm text-[var(--k-color-text-secondary)] mb-6">
            <a href="{{ route('home') }}" class="hover:text-[var(--k-color-primary)] transition-colors">Главная</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-[var(--k-color-primary)] transition-colors">Каталог</a>
            <span>/</span>
            <a href="{{ route('catalog') }}?category={{ $cat->id }}" class="hover:text-[var(--k-color-primary)] transition-colors">{{ $cat->name }}</a>
            @if($image)
                <span>/</span>
                <span class="text-[var(--k-color-text-primary)]">{{ $image->title ?? 'Изображение' }}</span>
            @endif
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Основное изображение --}}
            <div class="lg:col-span-2">
                @if($image)
                    <div class="sticky top-20">
                        <div class="relative aspect-[4/3] overflow-hidden rounded-xl bg-[var(--k-color-bg-surface)] shadow-md">
                            <img src="{{ $image->original_url }}"
                                 alt="{{ $image->title }}"
                                 class="w-full h-full object-contain cursor-crosshair"
                                 x-data
                                 @click="$dispatch('open-lightbox', { id: {{ $image->id }}, url: '{{ $image->original_url }}', title: '{{ $image->title }}' })">
                        </div>

                        {{-- Миниатюры (для будущего использования с дополнительными медиа) --}}
                        <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                            <div class="w-20 h-20 rounded-lg overflow-hidden border-2 border-[var(--k-color-primary)] flex-shrink-0">
                                <img src="{{ $image->thumb_url }}" alt="" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Категория без выбранного изображения --}}
                    <div class="text-center py-16 bg-[var(--k-color-bg-surface)] rounded-xl">
                        <p class="text-lg text-[var(--k-color-text-secondary)]">Выберите изображение из каталога</p>
                    </div>
                @endif
            </div>

            {{-- Информация и действия --}}
            <div class="lg:col-span-1">
                @if($image)
                    <div class="space-y-6">
                        <div>
                            <h1 class="text-2xl font-heading font-bold text-[var(--k-color-text-primary)]">
                                {{ $image->title ?? 'Без названия' }}
                            </h1>
                            @if($image->category)
                                <p class="text-sm text-[var(--k-color-text-secondary)] mt-1">
                                    Категория: <span class="font-semibold">{{ $image->category->name }}</span>
                                </p>
                            @endif
                        </div>

                        {{-- Характеристики --}}
                        <div class="border-t border-[var(--k-color-border)] pt-4">
                            <dl class="space-y-2 text-sm">
                                @if($image->width && $image->height)
                                    <div class="flex justify-between">
                                        <dt class="text-[var(--k-color-text-secondary)]">Размер</dt>
                                        <dd class="font-semibold">{{ $image->width }} × {{ $image->height }} px</dd>
                                    </div>
                                @endif
                                @if($image->file_size)
                                    <div class="flex justify-between">
                                        <dt class="text-[var(--k-color-text-secondary)]">Размер файла</dt>
                                        <dd class="font-semibold">{{ round($image->file_size / 1024, 1) }} KB</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="text-[var(--k-color-text-secondary)]">Артикул</dt>
                                    <dd class="font-semibold">{{ $image->title ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Действия --}}
                        <div class="space-y-3">
                            <a href="{{ route('primerka') }}?image={{ $image->id }}"
                               class="btn-primary w-full text-center block">
                                Примерить
                            </a>

                            <button class="btn-secondary w-full"
                                    x-data
                                    @click="$dispatch('toggle-favorite', { imageId: {{ $image->id }} })">
                                ♡ В избранное
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Фильтры для просмотра категории --}}
                    <x-filters :categories="$categories" :colors="$colors" />

                    <div class="mt-6 text-center">
                        <a href="{{ route('catalog') }}" class="btn-primary inline-block">
                            Перейти в каталог
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Lightbox --}}
        <div x-data="lightbox()" x-show="open" x-init="init()"
             class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
             style="display: none"
             @keydown.escape.window="close()">
            <button @click="close()" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
            <div @click.outside="close()">
                <img :src="currentImage?.url" :alt="currentImage?.title"
                     class="max-w-full max-h-[90vh] object-contain rounded">
            </div>
        </div>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
