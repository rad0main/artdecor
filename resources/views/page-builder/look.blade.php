@php
    $heading = $heading ?? 'Примерить скинали онлайн';
    $text = $text ?? 'Выберите нужный цвет фасадов кухни, нажав на нужный цвет на представленной палитре.';
    $textColor = $text_color ?? '#333333';
    $textSize = $text_size ?? 16;
    $colorNames = ['red','orange','yellow','green','blue','darkblue','purple','pink','brown','beige','white','gray','black'];
    $catalogImages = [
        ['url' => '/images/mainprod/skinali.jpg', 'title' => 'Однотонные'],
        ['url' => '/images/mainprod/holst.png', 'title' => 'С рисунком'],
        ['url' => '/images/mainprod/kuhnya.jpeg', 'title' => 'С подсветкой'],
        ['url' => '/images/mainprod/triplex.jpg', 'title' => '3D-скинали'],
        ['url' => '/images/mainprod/ognest.jpeg', 'title' => 'Термостойкие'],
        ['url' => '/images/mainprod/decor.jpg', 'title' => 'Декоративные'],
        ['url' => '/images/mainprod/dush.jpg', 'title' => 'Душевые'],
        ['url' => '/images/mainprod/dveri.jpg', 'title' => 'Стеклянные двери'],
        ['url' => '/images/mainprod/fotopechat.jpg', 'title' => 'Фотопечать'],
        ['url' => '/images/mainprod/cifra.jpg', 'title' => 'Цифровая печать'],
    ];
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

    {{-- Interactive kitchen — fixed 900px width --}}
    <div x-data="lookApp()" x-init="init()"
         data-colors='{{ json_encode($colorNames) }}'
         data-catalog='{{ json_encode($catalogImages) }}'
         class="relative mx-auto select-none"
         style="max-width: 900px; width: 100%;">

        {{-- The whole kitchen area --}}
        <div class="relative w-full">
            {{-- TOP FACADE (221px) --}}
            <div class="relative overflow-hidden" style="width: 100%; height: 221px;">
                <img :src="topImage()" alt="Верхний фасад"
                     class="w-full h-full object-cover object-top">

                {{-- Color palette on top facade --}}
                <div class="absolute top-2 right-2 flex flex-wrap justify-end gap-1 max-w-[200px]">
                    <template x-for="name in colors" :key="'t-' + name">
                        <button @click="topColor = name; $event.stopPropagation()"
                                class="w-5 h-5 rounded-full border border-white/60 shadow-sm transition-transform hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        (topColor === name ? ' outline: 2px solid var(--k-color-primary); outline-offset: 2px;' : '')"
                                :title="name">
                        </button>
                    </template>
                </div>
            </div>

            {{-- SKINALI area + catalog button --}}
            <div class="relative flex" style="min-height: 200px;">
                {{-- Catalog button (left side) --}}
                <div class="flex-shrink-0 flex flex-col items-center justify-center"
                     style="width: 60px; min-height: 200px;">
                    <button @click="catalogOpen = true"
                            class="flex flex-col items-center gap-1 px-2 py-4 bg-[var(--k-color-primary)] text-white rounded-lg hover:brightness-110 transition-all shadow-md text-xs font-heading writing-mode-vertical">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span class="mt-1">Каталог</span>
                    </button>
                </div>

                {{-- Selected skinali pattern --}}
                <div class="flex-1 bg-cover bg-center bg-no-repeat relative"
                     :style="'background-image: url(' + (selectedImage || '/images/mainprod/skinali.jpg') + '); min-height: 200px;'">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-white/5 pointer-events-none"></div>
                </div>
            </div>

            {{-- BOTTOM FACADE (306px) --}}
            <div class="relative overflow-hidden" style="width: 100%; height: 306px;">
                <img :src="bottomImage()" alt="Нижний фасад"
                     class="w-full h-full object-cover object-bottom">

                {{-- Color palette on bottom facade --}}
                <div class="absolute bottom-2 right-2 flex flex-wrap justify-end gap-1 max-w-[200px]">
                    <template x-for="name in colors" :key="'b-' + name">
                        <button @click="bottomColor = name; $event.stopPropagation()"
                                class="w-5 h-5 rounded-full border border-white/60 shadow-sm transition-transform hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        (bottomColor === name ? ' outline: 2px solid var(--k-color-primary); outline-offset: 2px;' : '')"
                                :title="name">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Catalog popup --}}
    <div x-show="catalogOpen"
         x-cloak
         @click.outside="catalogOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(0,0,0,0.6);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-heading font-bold text-[var(--k-color-text-primary)]">Каталог скинали</h3>
                <button @click="catalogOpen = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <template x-for="(img, idx) in catalog" :key="idx">
                    <button @click="selectImage(img.url)"
                            class="group relative rounded-lg overflow-hidden border border-gray-200 hover:border-[var(--k-color-primary)] transition-colors">
                        <div class="aspect-[4/3] bg-gray-100">
                            <img :src="img.url" :alt="img.title"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-2 text-xs font-heading text-center text-[var(--k-color-text-primary)] truncate" x-text="img.title"></div>
                    </button>
                </template>
            </div>
        </div>
    </div>
</section>
