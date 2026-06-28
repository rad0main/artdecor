@php
    $heading = $heading ?? 'Примерить скинали онлайн';
    $headingColor = $heading_color ?? '#1a1a2e';
    $headingSize = $heading_size ?? 28;
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
            <h2 style="color: {{ $headingColor }}; font-size: {{ $headingSize }}px;">{{ $heading }}</h2>
        </div>
        <p class="text-center max-w-2xl mx-auto mt-2 leading-relaxed font-heading"
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

            {{-- SKINALI BACKGROUND (behind the facades, fills the backsplash zone) --}}
            <div class="absolute left-0 right-0 z-0 bg-cover bg-center bg-no-repeat bg-[#e8e8e8]"
                 style="top: 170px; height: 220px;"
                 :style="'background-image: url(' + (selectedImage || '') + '); top: 170px; height: 220px;'">
            </div>



            {{-- TOP FACADE (sits on top of skinali, transparent bottom reveals skinali) --}}
            <div class="relative z-[3] overflow-hidden" style="height: 221px;">
                <img :src="topImage()" alt="Верхний фасад"
                     class="w-full h-full object-cover object-top">
                {{-- Color palette on RIGHT side, top --}}
                <div class="absolute top-2 right-2 flex flex-wrap gap-1 max-w-[160px]">
                    <template x-for="name in colors" :key="'t-' + name">
                        <button @click="topColor = name; $event.stopPropagation()"
                                class="w-4 h-4 rounded-full border-2 transition-all hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        'border-color: ' + (topColor === name ? 'var(--k-color-primary)' : 'rgba(255,255,255,0.6)') + ';'"
                                :title="name"></button>
                    </template>
                </div>
            </div>

            {{-- BOTTOM FACADE (sits on top of skinali, transparent top reveals skinali) --}}
            <div class="relative z-[3] overflow-hidden" style="height: 306px;">
                <img :src="bottomImage()" alt="Нижний фасад"
                     class="w-full h-full object-cover object-bottom">
                {{-- Color palette on RIGHT side, bottom --}}
                <div class="absolute bottom-2 right-2 flex flex-wrap gap-1 max-w-[160px]">
                    <template x-for="name in colors" :key="'b-' + name">
                        <button @click="bottomColor = name; $event.stopPropagation()"
                                class="w-4 h-4 rounded-full border-2 transition-all hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        'border-color: ' + (bottomColor === name ? 'var(--k-color-primary)' : 'rgba(255,255,255,0.6)') + ';'"
                                :title="name"></button>
                    </template>
                </div>
            </div>

            {{-- Catalog button (above facades, left side of skinali zone) --}}
            <button @click="catalogOpen = true"
                    class="absolute z-[4] left-3 flex items-center justify-center w-10 h-10 rounded-full bg-[var(--k-color-primary)] text-white hover:brightness-110 transition-all shadow-lg"
                    style="top: 250px;"
                    title="Открыть каталог">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- CATALOG MODAL (overlays everything) --}}
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
