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

    {{-- Interactive kitchen --}}
    <div x-data="lookApp()" x-init="init()"
         data-colors='{{ json_encode($colorNames) }}'
         data-catalog='{{ json_encode($catalogImages) }}'
         class="relative mx-auto select-none"
         style="max-width: 960px; width: 100%;">

        {{-- Main row: left sidebar + kitchen --}}
        <div class="flex">
            {{-- LEFT SIDEBAR: colors + catalog button --}}
            <div class="flex-shrink-0 flex flex-col items-center justify-between py-2"
                 style="width: 50px; min-height: 527px;">
                {{-- Top colors --}}
                <div class="flex flex-col gap-1.5 items-center">
                    <template x-for="name in colors" :key="'t-' + name">
                        <button @click="topColor = name"
                                class="w-4 h-4 rounded-full border-2 transition-all hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        'border-color: ' + (topColor === name ? 'var(--k-color-primary)' : 'rgba(255,255,255,0.6)') + ';'"
                                :title="name">
                        </button>
                    </template>
                </div>

                {{-- Catalog button between top and bottom --}}
                <button @click="catalogOpen = true"
                        class="flex items-center justify-center w-9 h-9 rounded-full bg-[var(--k-color-primary)] text-white hover:brightness-110 transition-all shadow-md"
                        title="Открыть каталог">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Bottom colors --}}
                <div class="flex flex-col gap-1.5 items-center">
                    <template x-for="name in colors" :key="'b-' + name">
                        <button @click="bottomColor = name"
                                class="w-4 h-4 rounded-full border-2 transition-all hover:scale-125"
                                :style="'background-color: ' + hexColor(name) + ';' +
                                        'border-color: ' + (bottomColor === name ? 'var(--k-color-primary)' : 'rgba(255,255,255,0.6)') + ';'"
                                :title="name">
                        </button>
                    </template>
                </div>
            </div>

            {{-- KITCHEN AREA --}}
            <div class="flex-1 relative">
                {{-- Top facade --}}
                <img :src="topImage()" alt="Верхний фасад"
                     class="block w-full"
                     style="height: 221px; object-fit: cover; object-position: top;">

                {{-- Skinali (backsplash) --}}
                <div class="w-full bg-cover bg-center bg-no-repeat"
                     :style="'background-image: url(' + (selectedImage || '/images/mainprod/skinali.jpg') + '); height: 200px;'">
                </div>

                {{-- Bottom facade --}}
                <img :src="bottomImage()" alt="Нижний фасад"
                     class="block w-full"
                     style="height: 306px; object-fit: cover; object-position: bottom;">
            </div>
        </div>

        {{-- Mobile placeholder --}}
        <div class="sm:hidden text-center py-8">
            <p class="text-sm font-heading text-[var(--k-color-text-secondary)]">Пожалуйста, поверните телефон для просмотра примерки</p>
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
                <h3 class="text-lg font-heading font-bold text-[var(--k-color-text-primary)]">Выберите изображение из каталога</h3>
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
