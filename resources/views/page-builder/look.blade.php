@php
    $heading = $heading ?? 'Примерить скинали онлайн';
    $text = $text ?? 'Выберите нужный цвет фасадов кухни, нажав на нужный цвет на представленной палитре.';
    $textColor = $text_color ?? '#333333';
    $textSize = $text_size ?? 16;
    $factoryColors = ['red' => '#e74c3c', 'orange' => '#f39c12', 'yellow' => '#f1c40f', 'green' => '#27ae60', 'blue' => '#2980b9',
                      'darkblue' => '#1a3a5c', 'purple' => '#8e44ad', 'pink' => '#e91e9b', 'brown' => '#795548',
                      'beige' => '#f5e6d3', 'white' => '#f5f5f5', 'gray' => '#95a5a6', 'black' => '#2c3e50'];
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

<section class="py-12 md:py-16 bg-white overflow-x-hidden">
    <div class="max-w-page mx-auto px-4 mb-8">
        <div class="section-heading">
            <h2>{{ $heading }}</h2>
        </div>
        <p class="text-center max-w-2xl mx-auto mt-4 leading-relaxed font-heading"
           style="color: {{ $textColor }}; font-size: {{ $textSize }}px;">
            {{ $text }}
        </p>
    </div>

    {{-- Интерактивная кухня --}}
    <div x-data="lookApp()" x-init="init()"
         data-colors='{{ json_encode($factoryColors) }}'
         data-catalog='{{ json_encode($catalogImages) }}'
         class="relative select-none"
         style="max-width: 800px; margin: 0 auto;">

        {{-- Кухонный блок --}}
        <div class="relative mx-auto" style="width: 90%; max-width: 700px; aspect-ratio: 4 / 5;">

            {{-- Верхний фасад (шкаф) --}}
            <div class="absolute top-0 left-0 right-0 transition-colors duration-300 rounded-t-lg"
                 :style="'background: linear-gradient(135deg, ' + getColor(currentFacade) + ' 0%, ' + darken(getColor(currentFacade), 20) + ' 100%); height: 25%; z-index: 3; border-bottom: 4px solid ' + darken(getColor(currentFacade), 30) + ';'"
                 style="box-shadow: inset 0 2px 8px rgba(0,0,0,0.15);">
                {{-- Ручка --}}
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 w-16 h-1.5 rounded-full opacity-60"
                     :style="'background: ' + (currentFacade === 'white' || currentFacade === 'beige' ? '#666' : 'rgba(255,255,255,0.5)') + ';'"></div>
            </div>

            {{-- Скинали (центральная область) --}}
            <div class="absolute left-0 right-0 transition-all duration-500 bg-cover bg-center"
                 :style="'top: 25%; height: 50%; background-image: url(' + (selectedImage || '/images/mainprod/skinali.jpg') + '); z-index: 2; border-left: 2px solid #ddd; border-right: 2px solid #ddd;'">
                {{-- Эффект стекла --}}
                <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                {{-- Имитация подсветки --}}
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-white/60 to-transparent"></div>
            </div>

            {{-- Нижний фасад (шкаф) --}}
            <div class="absolute bottom-0 left-0 right-0 transition-colors duration-300 rounded-b-lg"
                 :style="'background: linear-gradient(135deg, ' + getColor(currentFacade) + ' 0%, ' + darken(getColor(currentFacade), 20) + ' 100%); height: 25%; z-index: 3; border-top: 4px solid ' + darken(getColor(currentFacade), 30) + ';'"
                 style="box-shadow: inset 0 -2px 8px rgba(0,0,0,0.15);">
                {{-- Ручка --}}
                <div class="absolute top-3 left-1/2 -translate-x-1/2 w-16 h-1.5 rounded-full opacity-60"
                     :style="'background: ' + (currentFacade === 'white' || currentFacade === 'beige' ? '#666' : 'rgba(255,255,255,0.5)') + ';'"></div>
            </div>
        </div>

        {{-- Палитры цветов --}}
        <div class="mt-6 px-4">
            <p class="text-xs font-heading text-[var(--k-color-text-secondary)] mb-2 text-center">Выберите цвет фасадов:</p>
            <div class="flex flex-wrap justify-center gap-2">
                <template x-for="(hex, name) in colors" :key="name">
                    <button @click="currentFacade = name"
                            class="w-7 h-7 rounded-full border-2 transition-all duration-200 hover:scale-110"
                            :style="'background-color: ' + hex + '; border-color: ' + (currentFacade === name ? 'var(--k-color-primary)' : 'transparent') + ';'"
                            :title="name">
                    </button>
                </template>
            </div>
        </div>

        {{-- Кнопка каталога --}}
        <div class="mt-6 text-center">
            <button @click="catalogOpen = true"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--k-color-primary)] text-white rounded-lg font-heading hover:brightness-110 transition-all duration-200 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span>Открыть каталог изображений</span>
            </button>
        </div>

        {{-- Каталог (попап) --}}
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
                        <button @click="selectImage(img.url); catalogOpen = false"
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
    </div>
</section>
