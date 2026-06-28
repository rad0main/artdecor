@php
    $heading = $heading ?? 'Примерить скинали онлайн';
    $text = $text ?? 'Выберите нужный цвет фасадов кухни, нажав на нужный цвет на представленной палитре.';
    $textColor = $text_color ?? '#333333';
    $textSize = $text_size ?? 16;
    $colorNames = ['red','orange','yellow','green','blue','darkblue','purple','pink','brown','beige','white','gray','black'];

    $categories = [
        'des' => 'Дизайнерские', 'abs' => 'Абстракция', 'arh' => 'Архитектура',
        'wat' => 'Водопады', 'cit' => 'Города', 'eat' => 'Еда и напитки',
        'ani' => 'Животный мир', 'sea' => 'Море', 'nat' => 'Природа',
        'tex' => 'Текстуры', 'flo' => 'Цветы',
    ];

    // Build image list per category
    $catImages = [];
    foreach ($categories as $slug => $name) {
        $dir = public_path('images/temp_cat/' . $slug);
        if (is_dir($dir)) {
            $files = array_values(array_filter(scandir($dir), fn($f) => preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f)));
            sort($files);
            $catImages[$slug] = $files;
        } else {
            $catImages[$slug] = [];
        }
    }
@endphp

<section class="py-12 md:py-16 bg-white">
    <div class="max-w-page mx-auto px-4 mb-8">
        <div class="section-heading">
            <h2>{{ $heading }}</h2>
        </div>
        <p class="text-center max-w-2xl mx-auto mt-4 leading-relaxed font-heading"
           style="color: {{ $textColor }}; font-size: {{ $textSize }}px;">
            {{ $text }}
        </p>
    </div>

    <div x-data="lookApp()" x-init="init()"
         data-colors='{{ json_encode($colorNames) }}'
         data-categories='{{ json_encode($categories) }}'
         data-images='{{ json_encode($catImages) }}'
         data-basepath='/images/temp_cat'
         class="relative mx-auto select-none"
         style="max-width: 900px; width: 100%;">

        {{-- Kitchen container (relative for modal positioning) --}}
        <div class="relative w-full" style="min-height: 527px;">

            {{-- TOP FACADE --}}
            <div class="relative overflow-hidden" style="height: 221px;">
                <img :src="topImage()" alt="Верхний фасад"
                     class="w-full h-full object-cover object-top">
                <div class="absolute top-2 right-2 flex flex-wrap justify-end gap-1 max-w-[160px]">
                    <template x-for="name in colors" :key="'t-' + name">
                        <button @click="topColor = name; $event.stopPropagation()"
                                class="w-4 h-4 rounded-full border-2 transition-all hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        'border-color: ' + (topColor === name ? 'var(--k-color-primary)' : 'rgba(255,255,255,0.6)') + ';'"
                                :title="name"></button>
                    </template>
                </div>
            </div>

            {{-- BOTTOM FACADE --}}
            <div class="relative overflow-hidden" style="height: 306px;">
                <img :src="bottomImage()" alt="Нижний фасад"
                     class="w-full h-full object-cover object-bottom">
                <div class="absolute bottom-2 right-2 flex flex-wrap justify-end gap-1 max-w-[160px]">
                    <template x-for="name in colors" :key="'b-' + name">
                        <button @click="bottomColor = name; $event.stopPropagation()"
                                class="w-4 h-4 rounded-full border-2 transition-all hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        'border-color: ' + (bottomColor === name ? 'var(--k-color-primary)' : 'rgba(255,255,255,0.6)') + ';'"
                                :title="name"></button>
                    </template>
                </div>
            </div>

            {{-- SKINALI overlay --}}
            <div class="absolute left-1/2 -translate-x-1/2 z-10"
                 style="width: 80%; height: 220px; top: calc(50% - 110px);">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-lg shadow-xl"
                     :style="'background-image: url(' + (selectedImage || '') + '); background-color: #e8e8e8;'">
                </div>
                <div class="absolute inset-0 bg-gradient-to-b from-white/5 via-transparent to-white/10 pointer-events-none rounded-lg"></div>

                {{-- Fixture overlay: 4 screw holes + faucet --}}
                <div class="absolute inset-0 pointer-events-none z-20 rounded-lg overflow-hidden">
                    {{-- Faucet --}}
                    <svg class="absolute top-0 left-1/2 -translate-x-1/2 w-16 h-16 opacity-80" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M45 10v15h10V10H45z" fill="#c0c0c0" stroke="#999" stroke-width="1"/>
                        <path d="M35 25h30v5H35z" fill="#c0c0c0" stroke="#999" stroke-width="1"/>
                        <path d="M30 30h40v40H30z" fill="#d4d4d4" stroke="#999" stroke-width="1"/>
                        <path d="M35 55c-3 0-5 2-5 5c0 8 10 20 10 20s10-12 10-20c0-3-2-5-5-5h-10z" fill="#e0e0e0" stroke="#aaa" stroke-width="0.5"/>
                        <path d="M38 58c-1 0-2 1-2 2c0 5 6 12 7 12s7-7 7-12c0-1-1-2-2-2H38z" fill="#eee"/>
                    </svg>
                    {{-- 4 screw holes --}}
                    <div class="absolute top-2 left-2 w-5 h-5 rounded-full bg-gradient-to-br from-gray-400 via-gray-300 to-gray-500 border border-gray-500 shadow-inner"></div>
                    <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-gradient-to-br from-gray-400 via-gray-300 to-gray-500 border border-gray-500 shadow-inner"></div>
                    <div class="absolute bottom-2 left-2 w-5 h-5 rounded-full bg-gradient-to-br from-gray-400 via-gray-300 to-gray-500 border border-gray-500 shadow-inner"></div>
                    <div class="absolute bottom-2 right-2 w-5 h-5 rounded-full bg-gradient-to-br from-gray-400 via-gray-300 to-gray-500 border border-gray-500 shadow-inner"></div>
                    {{-- Cross screws inside holes --}}
                    <div class="absolute top-2 left-2 w-5 h-5 flex items-center justify-center pointer-events-none">
                        <svg width="14" height="14" viewBox="0 0 10 10"><path d="M0 4h10M4 0v10" stroke="#777" stroke-width="1.5"/></svg>
                    </div>
                    <div class="absolute top-2 right-2 w-5 h-5 flex items-center justify-center pointer-events-none">
                        <svg width="14" height="14" viewBox="0 0 10 10"><path d="M0 4h10M4 0v10" stroke="#777" stroke-width="1.5"/></svg>
                    </div>
                    <div class="absolute bottom-2 left-2 w-5 h-5 flex items-center justify-center pointer-events-none">
                        <svg width="14" height="14" viewBox="0 0 10 10"><path d="M0 4h10M4 0v10" stroke="#777" stroke-width="1.5"/></svg>
                    </div>
                    <div class="absolute bottom-2 right-2 w-5 h-5 flex items-center justify-center pointer-events-none">
                        <svg width="14" height="14" viewBox="0 0 10 10"><path d="M0 4h10M4 0v10" stroke="#777" stroke-width="1.5"/></svg>
                    </div>
                </div>

                {{-- Catalog button on the LEFT side --}}
                <button @click="openCatalog()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-20 flex items-center justify-center w-10 h-10 rounded-full bg-[var(--k-color-primary)] text-white hover:brightness-110 transition-all shadow-lg"
                        title="Открыть каталог">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            {{-- CATALOG MODAL (overlays the kitchen block) --}}
            <div x-show="catalogOpen"
                 x-cloak
                 class="absolute inset-0 z-30 bg-white/95 rounded-lg shadow-2xl flex overflow-hidden"
                 style="min-height: 527px;">
                {{-- Categories sidebar (left) --}}
                <div class="w-44 flex-shrink-0 border-r border-gray-200 overflow-y-auto p-3 bg-gray-50">
                    <template x-for="(catName, catSlug) in categories" :key="catSlug">
                        <button @click="currentCategory = catSlug"
                                class="block w-full text-left px-3 py-2 rounded-lg text-sm font-heading transition-colors"
                                :class="currentCategory === catSlug ? 'bg-[var(--k-color-primary)] text-white' : 'text-[var(--k-color-text-secondary)] hover:bg-gray-200'"
                                x-text="catName">
                        </button>
                    </template>
                </div>
                {{-- Images grid (right) --}}
                <div class="flex-1 overflow-y-auto p-3">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-heading text-[var(--k-color-text-secondary)]" x-text="'Всего: ' + filteredImages().length"></span>
                        <button @click="catalogOpen = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        <template x-for="(img, idx) in filteredImages()" :key="idx">
                            <button @click="selectImage(img)"
                                    class="group relative aspect-[4/3] rounded-lg overflow-hidden border-2 border-transparent hover:border-[var(--k-color-primary)] transition-colors bg-gray-100">
                                <img :src="basePath + '/' + currentCategory + '/' + img"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile placeholder --}}
        <div class="sm:hidden text-center py-8">
            <p class="text-sm font-heading text-[var(--k-color-text-secondary)]">Пожалуйста, поверните телефон для просмотра примерки</p>
        </div>
    </div>
</section>
